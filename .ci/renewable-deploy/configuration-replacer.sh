#!/bin/bash -ex

# Create real parameters files to be modified and applied to the cluster as configmaps
cp config/domains_urls.yml.dist config/domains_urls.yml
cp config/parameters.yml.dist config/parameters.yml

DOCKER_PHP_FPM_IMAGE=${DOCKER_USERNAME}/php-fpm:${DOCKER_IMAGE_TAG_PHP_FPM}
DOCKER_ELASTIC_IMAGE=${DOCKER_USERNAME}/elasticsearch:${DOCKER_IMAGE_TAG_ELASTIC}
PATH_CONFIG_DIRECTORY='config'

FILES=$( find kubernetes -type f )
VARS=(
    FIRST_DOMAIN_HOSTNAME
    SECOND_DOMAIN_HOSTNAME
    THIRD_DOMAIN_HOSTNAME
    DOCKER_PHP_FPM_IMAGE
    DOCKER_ELASTIC_IMAGE
    PATH_CONFIG_DIRECTORY
    GKE_LOAD_BALANCER_IP
    TLS_CERT_ENCODED
    TLS_KEY_ENCODED
)

for FILE in $FILES; do
    for VAR in ${VARS[@]}; do
        sed -i "s|{{$VAR}}|${!VAR}|g" "$FILE"
    done
done
unset FILES
unset VARS

# Set domain urls
yq write --inplace config/domains_urls.yml domains_urls[0].url https://${FIRST_DOMAIN_HOSTNAME}
yq write --inplace config/domains_urls.yml domains_urls[1].url https://${SECOND_DOMAIN_HOSTNAME}
yq write --inplace config/domains_urls.yml domains_urls[2].url https://${THIRD_DOMAIN_HOSTNAME}
# Add a mask for trusted proxies so that load balanced traffic is trusted and headers from outside of the network are not lost
yq write --inplace config/parameters.yml parameters.trusted_proxies[+] 10.0.0.0/8

# create links for additional kbuernetes configuration
mkdir -p kubernetes/base/config/config
ln -f config/domains_urls.yml kubernetes/base/config/config/domains_urls.yml
ln -f config/parameters.yml kubernetes/base/config/config/parameters.yml
