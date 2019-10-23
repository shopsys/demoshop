#!/bin/bash -ex

.ci/renewable-deploy/variable-validator.sh

# edit configuration from ENV variables
docker run \
  -e DOCKER_IMAGE_TAG_PHP_FPM \
  -e DOCKER_IMAGE_TAG_ELASTIC \
  -e DOCKER_USERNAME \
  -e FIRST_DOMAIN_HOSTNAME \
  -e SECOND_DOMAIN_HOSTNAME \
  -e THIRD_DOMAIN_HOSTNAME \
  -e GKE_LOAD_BALANCER_IP \
  -e TLS_CERT_ENCODED \
  -e TLS_KEY_ENCODED \
  -v ${WORKSPACE}:/code \
  -w /code \
  --rm \
  shopsys/kubernetes-buildpack:0.2.0 \
  .ci/renewable-deploy/configuration-replacer.sh

# Pull or create docker images
## Required ENVs: DOCKER_USERNAME, DOCKER_PASSWORD, DOCKER_IMAGE_TAG_PHP_FPM, DOCKER_IMAGE_TAG_ELASTIC
.ci/renewable-deploy/image-creator.sh

docker run \
  -e GOOGLE_CLOUD_PROJECT_ID \
  -e GOOGLE_CLOUD_ZONE \
  -e GOOGLE_CLOUD_CLUSTER_NAME \
  -v ${WORKSPACE}:/code \
  -v ${GOOGLE_CLOUD_SERVICE_ACCOUNT_FILE_PATH}:"/tmp/infrastructure/google-cloud/service-account.json" \
  -w /code \
  --rm \
  google/cloud-sdk \
  .ci/renewable-deploy/deploy-to-google-cloud.sh

docker run \
  -e GOOGLE_CLOUD_PROJECT_ID \
  -e GOOGLE_CLOUD_ZONE \
  -e GOOGLE_CLOUD_CLUSTER_NAME \
  -e GOOGLE_CLOUD_STORAGE_BUCKET_NAME_DEMO_FILES \
  -v ${WORKSPACE}:/code \
  -v ${GOOGLE_CLOUD_SERVICE_ACCOUNT_FILE_PATH}:"/tmp/infrastructure/google-cloud/service-account.json" \
  -w /code \
  --rm \
  google/cloud-sdk \
  .ci/renewable-deploy/renewal-script.sh
