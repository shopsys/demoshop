#!/bin/bash -e

# Builds a new demo instance using Docker Compose.
# Uses URLs http://<demo_domain> and http://2.<demo_domain>
# Adds nginx configuration into /etc/nginx/conf.d/<JOB_NAME>.conf
#
# Usage: build_demo.sh <demo_domain>
#
# Example: build_demo.sh my-demo.example.com

# ANSI color codes
RED="\e[31m"
GREEN="\e[32m"
BLUE="\e[34m"
NC="\e[0m"

DEMO_DOMAIN=$1
JOB_NAME=${JOB_NAME:-$DEMO_DOMAIN}
WORKSPACE=${WORKSPACE:-$PWD}
ELASTICSEARCH_NETWORK=${ELASTICSEARCH_NETWORK:-shared-demo-elasticsearch-network}
ELASTICSEARCH_INDEX_PREFIX=${JOB_NAME}
ELASTICSEARCH_CONTAINER=${ELASTICSEARCH_CONTAINER:-shared-demo-elasticsearch-instance}

if [[ ! "$DEMO_DOMAIN" =~ ^[a-z0-9]([a-z0-9.-]*[a-z0-9])?$ ]]; then
    echo -e "${RED}Invalid demo domain!${NC}"
    echo -e "${BLUE}Use only lowercase letters and numbers, you can include dashes and dots in the middle (eg. \"my-demo42.example.com\").${NC}"
    exit 1
fi

echo -e "${BLUE}Configuring your demo-shop...${NC}"

# Configuration files are baked into the Docker image at the moment
cp "$WORKSPACE/app/config/parameters.yml.dist" "$WORKSPACE/app/config/parameters.yml"
cp "$WORKSPACE/app/config/domains_urls.yml.dist" "$WORKSPACE/app/config/domains_urls.yml"

# Disable master e-mail and mailer whitelist
sed -i "s/mailer_master_email_address:.*/mailer_master_email_address: ~/" "$WORKSPACE/app/config/parameters.yml"
sed -i "s/mailer_delivery_whitelist:.*/mailer_delivery_whitelist: ~/" "$WORKSPACE/app/config/parameters.yml"

# Fetch all domain IDs
DOMAIN_IDS=$(cat "$WORKSPACE/app/config/domains_urls.yml" | grep -Po 'id: ([0-9]+)$' | sed -r 's/id: ([0-9]+)/\1/')

# Modify public URLs to $DOMAIN_ID.$DEMO_DOMAIN ($DOMAIN_ID is omitted for the first domain)
for DOMAIN_ID in $DOMAIN_IDS; do
    if [[ "$DOMAIN_ID" == "1" ]]; then
        # 1st domain has URL without a number prefix
        sed -i "/id: 1/,/url:/{s/url:.*/url: http:\/\/$DEMO_DOMAIN/}" "$WORKSPACE/app/config/domains_urls.yml"
    else
        # 2nd and subsequent domains have URLs with DOMAIN_ID prefix
        sed -i "/id: $DOMAIN_ID/,/url:/{s/url:.*/url: http:\/\/$DOMAIN_ID.$DEMO_DOMAIN/}" "$WORKSPACE/app/config/domains_urls.yml"
    fi
done

echo -e "${BLUE}Connecting to shared elasticsearch instance...${NC}"

NETWORK_CONNECTION_REQUIRED='false'

if docker network create "$ELASTICSEARCH_NETWORK" &> /dev/null; then
    echo "A network $ELASTICSEARCH_NETWORK has been created."
    NETWORK_CONNECTION_REQUIRED='true'
else
    echo "The network $ELASTICSEARCH_NETWORK already exists."
fi

if [[ -z "$(docker ps -q -f name="$ELASTICSEARCH_CONTAINER")" ]]; then
    echo "Creating a shared elasticsearch container $ELASTICSEARCH_CONTAINER..."
    docker run --name "$ELASTICSEARCH_CONTAINER" --ulimit "nofile=65536:65536" --env "discovery.type=single-node" -d "docker.elastic.co/elasticsearch/elasticsearch-oss:6.3.2"
    echo "The elasticsearch container has been created."
    NETWORK_CONNECTION_REQUIRED='true'
else
    echo "The elasticsearch container $ELASTICSEARCH_CONTAINER already exists."
fi

if [[ "$NETWORK_CONNECTION_REQUIRED" == 'true' ]]; then
    echo "Connecting the container to the network...."
    docker network connect "$ELASTICSEARCH_NETWORK" "$ELASTICSEARCH_CONTAINER"
fi

ELASTICSEARCH_IP=$(docker inspect -f "{{ with (index .NetworkSettings.Networks \"$ELASTICSEARCH_NETWORK\") }}{{ .IPAddress }}{{ end }}" "$ELASTICSEARCH_CONTAINER")
if [[ -z "$ELASTICSEARCH_IP" ]]; then
    echo -e "${RED}The IP address of the elasticsearch container inside the shared network could not be obtained!${NC}"
    exit 1
else
    echo -e "The IP address of ${BLUE}$ELASTICSEARCH_CONTAINER${NC} inside ${BLUE}$ELASTICSEARCH_NETWORK${NC} is ${BLUE}$ELASTICSEARCH_IP${NC}"
fi

# Copy the demo Docker Compose configuration
cp docker/conf/docker-compose.demo.yml.dist docker-compose.yml

echo -e "${BLUE}Building Docker images and starting up the containers...${NC}"

# Export variables used in Docker Compose configuration
export JOB_NAME
export ELASTICSEARCH_NETWORK
export ELASTICSEARCH_INDEX_PREFIX
export ELASTICSEARCH_IP

# Build the Docker image and start the containers
docker-compose up --build --force-recreate -d

# Configure the nginx
echo "upstream $JOB_NAME-upstream {
    server $(docker-compose port webserver 8080);
}

server {
    listen 80;
    server_name $DEMO_DOMAIN *.$DEMO_DOMAIN;

    location / {
        proxy_pass http://$JOB_NAME-upstream;
        proxy_redirect off;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Host \$host;
    }
}" > "/etc/nginx/conf.d/$JOB_NAME.conf"

# Reload nginx to apply the new configuration
# "jenkins" user has been allowed to run "nginx" command as super-user without password prompt via /etc/sudoers configuration
# see https://www.digitalocean.com/community/tutorials/how-to-edit-the-sudoers-file-on-ubuntu-and-centos#how-to-modify-the-sudoers-file
sudo nginx -s reload

echo -e "${BLUE}Building the application...${NC}"

# Wait a moment for postgres to start, ten seconds should suffice
# (avoids having to implement a check according to https://docs.docker.com/compose/startup-order/)
sleep 10

# Build the application inside php-fpm container
docker-compose exec -T php-fpm ./phing db-create db-demo grunt error-pages-generate product-search-recreate-structure product-search-export-products warmup

# Display success message and available domains
echo -e "${GREEN}Demo-shop \"$DEMO_DOMAIN\" successfully created!${NC}"
echo "It's publicly accessible on these URLs:"
for DOMAIN_ID in $DOMAIN_IDS; do
    if [[ "$DOMAIN_ID" == "1" ]]; then
        echo -e "- ${BLUE}http://$DEMO_DOMAIN${NC}"
        echo -e "- ${BLUE}http://$DEMO_DOMAIN/admin${NC}"
    else
        echo -e "- ${BLUE}http://$DOMAIN_ID.$DEMO_DOMAIN${NC}"
    fi
done
