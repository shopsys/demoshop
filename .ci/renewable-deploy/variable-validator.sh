#! /bin/bash -ex

RED="\e[31m"
NC="\e[0m"

if [[ -z "$WORKSPACE" ]]; then
    echo -e "${RED}Actual workspace path has not been provided (in \$WORKSPACE environment variable)!${NC}"
    exit 1
fi

if [[ -z "$DOCKER_USERNAME" ]]; then
    echo -e "${RED}Username for Docker Hub has not been provided (in \$DOCKER_USERNAME environment variable)!${NC}"
    exit 1
fi

if [[ -z "$DOCKER_PASSWORD" ]]; then
    echo -e "${RED}Password for Docker Hub has not been provided (in \$DOCKER_PASSWORD environment variable)!${NC}"
    exit 1
fi

if [[ -z "$DOCKER_IMAGE_TAG_PHP_FPM" ]]; then
    echo -e "${RED}Specified image tag for php-fpm image has not been provided (in \$DOCKER_IMAGE_TAG_PHP_FPM environment variable)!${NC}"
    exit 1
fi

if [[ -z "$DOCKER_IMAGE_TAG_ELASTIC" ]]; then
    echo -e "${RED}Specified image tag for elasticsearch image has not been provided (in \$DOCKER_IMAGE_TAG_ELASTIC environment variable)!${NC}"
    exit 1
fi

if [[ -z "$FIRST_DOMAIN_HOSTNAME" ]]; then
    echo -e "${RED}Hostname for first domain has not been provided (in \$FIRST_DOMAIN_HOSTNAME environment variable)!${NC}"
    exit 1
fi

if [[ -z "$SECOND_DOMAIN_HOSTNAME" ]]; then
    echo -e "${RED}Hostname for first domain has not been provided (in \$SECOND_DOMAIN_HOSTNAME environment variable)!${NC}"
    exit 1
fi

if [[ -z "$THIRD_DOMAIN_HOSTNAME" ]]; then
    echo -e "${RED}Hostname for first domain has not been provided (in \$THIRD_DOMAIN_HOSTNAME environment variable)!${NC}"
    exit 1
fi

if [[ -z "$GOOGLE_CLOUD_PROJECT_ID" ]]; then
    echo -e "${RED}Google Cloud Project ID has not been provided (in \$GOOGLE_CLOUD_PROJECT_ID environment variable)!${NC}"
    exit 1
fi

if [[ -z "$GOOGLE_CLOUD_ZONE" ]]; then
    echo -e "${RED}Zone for Google Cloud Project has not been provided (in \$GOOGLE_CLOUD_ZONE environment variable)!${NC}"
    exit 1
fi

if [[ -z "$GOOGLE_CLOUD_SERVICE_ACCOUNT_FILE_PATH" ]]; then
    echo -e "${RED}Path to local service account file for Google Cloud Project has not been provided (in \$GOOGLE_CLOUD_SERVICE_ACCOUNT_FILE_PATH environment variable)!${NC}"
    exit 1
fi

if [[ -z "$GOOGLE_CLOUD_CLUSTER_NAME" ]]; then
    echo -e "${RED}Cluster name for Google Kubernetes Engine has not been provided (in \$GOOGLE_CLOUD_CLUSTER_NAME environment variable)!${NC}"
    exit 1
fi

if [[ -z "$GKE_LOAD_BALANCER_IP" ]]; then
    echo -e "${RED}IP address for load balancer has not been provided (in \$GKE_LOAD_BALANCER_IP environment variable)!${NC}"
    exit 1
fi

if [[ -z "$GOOGLE_CLOUD_STORAGE_BUCKET_NAME_DEMO_FILES" ]]; then
    echo -e "${RED}Google Cloud Storage bucket name with demo files has not been provided (in \$GKE_LOAD_BALANCER_IP environment variable)!${NC}"
    exit 1
fi

if [[ -z "$TLS_CERT_ENCODED" ]]; then
    echo -e "${RED}Encoded TLS certificate has not been provided (in \$TLS_CERT_ENCODED environment variable)!${NC}"
    exit 1
fi

if [[ -z "$TLS_KEY_ENCODED" ]]; then
    echo -e "${RED}Encoded TLS key has not been provided (in \$TLS_KEY_ENCODED environment variable)!${NC}"
    exit 1
fi
