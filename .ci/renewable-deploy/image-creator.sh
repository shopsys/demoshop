#!/bin/bash -ex

# Login to Docker Hub for pushing images into register
echo ${DOCKER_PASSWORD} | docker login --username ${DOCKER_USERNAME} --password-stdin
## Docker image for application php-fpm container
docker image pull ${DOCKER_USERNAME}/php-fpm:${DOCKER_IMAGE_TAG_PHP_FPM} || (
    echo "Image not found (see warning above), building it instead..." &&
    docker image build \
        --tag ${DOCKER_USERNAME}/php-fpm:${DOCKER_IMAGE_TAG_PHP_FPM} \
        --target production \
        --no-cache \
        -f docker/php-fpm/Dockerfile \
        . &&
    docker image push ${DOCKER_USERNAME}/php-fpm:${DOCKER_IMAGE_TAG_PHP_FPM}
)

## Docker image for application elasticsearch container
docker image pull ${DOCKER_USERNAME}/elasticsearch:${DOCKER_IMAGE_TAG_ELASTIC} || (
    echo "Image not found (see warning above), building it instead..." &&
    docker image build \
        --tag ${DOCKER_USERNAME}/elasticsearch:${DOCKER_IMAGE_TAG_ELASTIC} \
        -f docker/elasticsearch/Dockerfile \
        . &&
    docker image push ${DOCKER_USERNAME}/elasticsearch:${DOCKER_IMAGE_TAG_ELASTIC}
)
