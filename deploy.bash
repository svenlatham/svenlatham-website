#!/bin/bash


# Stage 1 - Build the site
docker build -t svenlatham-website . || exit 1
CONTAINER_ID=$(docker run -d svenlatham-website build --output-path=/build/)
docker wait $CONTAINER_ID
docker commit $CONTAINER_ID build

# Stage 2 - embed the build into nginx and deploy
docker build -t cr.brightbox.com/acc-uww2d/sven/svenlatham-website -f deploy/Dockerfile . || exit 1
docker push cr.brightbox.com/acc-uww2d/sven/svenlatham-website