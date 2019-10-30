#!/bin/bash -ex

GKE_CONTAINER_PHP_FPM="php-fpm"

gcloud config set project ${GOOGLE_CLOUD_PROJECT_ID}
gcloud config set compute/zone ${GOOGLE_CLOUD_ZONE}
gcloud auth activate-service-account --key-file /tmp/infrastructure/google-cloud/service-account.json
gcloud container clusters get-credentials ${GOOGLE_CLOUD_CLUSTER_NAME}
kubectl config set-context --current --namespace=production

# Listing pods to know what is really running - only for debugging purposes
kubectl get pods -l app=webserver-php-fpm
# Find running webserver and postgres
POD_PHP_FPM=$(kubectl get pods -l app=webserver-php-fpm | grep "Running" | awk '{print $1}')
POD_POSTGRES=$(kubectl get pods -l app=postgres -o=jsonpath='{.items[0].metadata.name}')

# Download fashionable data from google storage
gsutil cp gs://${GOOGLE_CLOUD_STORAGE_BUCKET_NAME_DEMO_FILES}/demo-sql.sql demo-sql.sql
kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- wget -O web-content.zip https://storage.googleapis.com/${GOOGLE_CLOUD_STORAGE_BUCKET_NAME_DEMO_FILES}/web-content.zip --no-cache
kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- unzip -o web-content.zip

kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- php phing maintenance-on
# Drop, create and fill database
kubectl exec ${POD_POSTGRES} -- dropdb shopsys
kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- php phing db-create
kubectl exec -i ${POD_POSTGRES} -- psql shopsys < demo-sql.sql

kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- php phing clean-cache db-migrations

# remove actual images, copy new ones, remove temp files
kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- rm -r web/content web-content.zip
kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- mv web-content web/content
rm -r demo-sql.sql

kubectl exec ${POD_PHP_FPM} -c ${GKE_CONTAINER_PHP_FPM} -- php phing clean-cache product-search-export-products maintenance-off
