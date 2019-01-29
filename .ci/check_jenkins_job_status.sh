#!/bin/bash +ex

# Checks whether a specified Jenkins job succeeded in the last build.
#
# Expects $JENKINS_URL (url of Jenkins) to be set as environment variables
#
# Usage: check_jenkins_job_status.sh <check_job_name>
#
# Example: check_jenkins_job_status.sh  master

# ANSI color codes
RED="\e[31m"
GREEN="\e[32m"
BLUE="\e[34m"
NC="\e[0m"

CHECK_JOB_NAME=$1

# Use Python to parse the JSON as it is usually installed on a machine
JOB_STATUS=$(curl -s "$JENKINS_URL/job/$CHECK_JOB_NAME/lastBuild/api/json" | python -c "import sys, json; print json.load(sys.stdin)['result']")

if [[ ! $? -eq 0 ]]; then
    echo -e "${RED}The status of $CHECK_JOB_NAME could not be obtained.${NC}\nMaybe the job does not exists."
    exit 1
fi

case "$JOB_STATUS" in
"SUCCESS") echo -e "${GREEN}The last build of $CHECK_JOB_NAME succeeded.${NC}" ;;
"FAILURE") echo -e "${RED}The last build of $CHECK_JOB_NAME failed!${NC}" ;;
"None")    echo -e "${RED}The last build of $CHECK_JOB_NAME has no status!${NC}\nMaybe the build has not finished yet." ;;
*)         echo -e "${RED}Unexpected status of $CHECK_JOB_NAME: \"$JOB_STATUS\"!${NC}" ;;
esac

if [[ "$JOB_STATUS" != "SUCCESS" ]]; then
    echo -e "Check the details on ${BLUE}"${JENKINS_URL%/}/job/$CHECK_JOB_NAME"${NC}"
    exit 1
fi
