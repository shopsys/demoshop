#!/bin/bash +ex

# Creates a new Jenkins job from a template, enables it and builds it.
#
# Uses Jenkins CLI (https://jenkins.io/doc/book/managing/cli/)
# Expects $JENKINS_URL (url of Jenkins) and $JENKINS_CLI_JAR (path to jenkins-cli.jar) to be set as environment variables
#
# Usage: create_jenkins_job.sh <new_job_name> <template_job_name> [<view_name>]
#
# Example: create_jenkins_job.sh $BUILD_PARAMETER template demos

# ANSI color codes
RED="\e[31m"
GREEN="\e[32m"
BLUE="\e[34m"
NC="\e[0m"

NEW_JOB_NAME=$1
TEMPLATE_JOB_NAME=$2
VIEW_NAME=$3

if [[ ! "$NEW_JOB_NAME" =~ ^[a-z0-9]([a-z0-9-]*[a-z0-9])?$ ]]; then
    echo -e "${RED}Invalid project name!${NC}"
    echo -e "${BLUE}Use only lowercase letters and numbers, you can include dashes in the middle (eg. \"my-demo-42\").${NC}"
    exit 1
fi
if [[ -z "$JENKINS_URL" ]]; then
    echo -e "${RED}Jenkins URL has not been provided (in \$JENKINS_URL environment variable)!${NC}"
    exit 1
fi
if [[ -z "$JENKINS_CLI_JAR" ]]; then
    echo -e "${RED}Path to the Jenkins CLI jarfile has not been provided (in \$JENKINS_CLI_JAR environment variable)!${NC}"
    exit 1
fi

echo -e "${BLUE}Creating a new project \"$NEW_JOB_NAME\"...${NC}"

java -jar "$JENKINS_CLI_JAR" -s "$JENKINS_URL" copy-job "$TEMPLATE_JOB_NAME" "$NEW_JOB_NAME"
if [[ ! $? -eq 0 ]]; then
    echo -e "${RED}Project creation failed!${NC}"
    exit 1
fi

if [[ -n "$VIEW_NAME" ]]; then
    java -jar "$JENKINS_CLI_JAR" -s "$JENKINS_URL" add-job-to-view "$VIEW_NAME" "$NEW_JOB_NAME"
fi
java -jar "$JENKINS_CLI_JAR" -s "$JENKINS_URL" enable-job "$NEW_JOB_NAME"

JENKINS_JOB_URL="${JENKINS_URL%/}/job/$NEW_JOB_NAME"
echo -e "${BLUE}Building the new project...${NC} ($JENKINS_JOB_URL)"

java -jar "$JENKINS_CLI_JAR" -s "$JENKINS_URL" build -f -v "$NEW_JOB_NAME"
if [[ ! $? -eq 0 ]]; then
    echo -e "${RED}Build of the new project has failed!${NC}"
    echo -e "Check the details of the build on ${BLUE}$JENKINS_JOB_URL${NC}"
    echo -e "You can either try to rebuild it manually or delete it"
    exit 1
fi

echo -e "${GREEN}Project \"$NEW_JOB_NAME\" created and built successfully!${NC}"
echo -e "You can delete it on ${BLUE}$JENKINS_JOB_URL${NC} when you don't need it anymore"
