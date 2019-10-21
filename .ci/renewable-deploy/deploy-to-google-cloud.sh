#!/bin/bash -ex

gcloud config set project ${GOOGLE_CLOUD_PROJECT_ID}
gcloud config set compute/zone ${GOOGLE_CLOUD_ZONE}
gcloud auth activate-service-account --key-file /tmp/infrastructure/google-cloud/service-account.json

# when cluster is not up and running the result of `gcloud container clusters list` is empty
IS_CLUSTER_UP=$(gcloud container clusters list --filter name=${GOOGLE_CLOUD_CLUSTER_NAME})
if [ -z ${IS_CLUSTER_UP} ]; then
  echo "Cluster \"${GOOGLE_CLOUD_CLUSTER_NAME}\" does not exist."
  echo "Creating cluster..."
  gcloud container clusters create ${GOOGLE_CLOUD_CLUSTER_NAME}
else
  echo "Cluster \"${GOOGLE_CLOUD_CLUSTER_NAME}\" already exists."
fi

gcloud container clusters get-credentials ${GOOGLE_CLOUD_CLUSTER_NAME}
kubectl config set-context --current --namespace=production

kubectl apply -k kubernetes/kustomize/overlays/production
# apply loadbalancer
kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/nginx-0.26.1/deploy/static/mandatory.yaml
kubectl apply -f kubernetes/base/load-balancer.yml

# wait until pod webserver-php-fpm is up
kubectl rollout status -n production deployment/webserver-php-fpm --watch
