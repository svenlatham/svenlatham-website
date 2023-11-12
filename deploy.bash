#!/bin/bash

# Use podman in place of docker if available
if command -v podman &> /dev/null
then
    BUILDER=podman
else
    BUILDER=docker
fi

# Stage 1 - Build the site
$BUILDER build -t svenlatham-website . || exit 1
CONTAINER_ID=$($BUILDER run -d svenlatham-website build --output-path=/build/)
$BUILDER wait $CONTAINER_ID
$BUILDER commit $CONTAINER_ID build

# Stage 2 - embed the build into nginx and deploy
$BUILDER build -t cr.brightbox.com/acc-uww2d/sven/svenlatham-website -f deploy/Dockerfile . || exit 1
$BUILDER push cr.brightbox.com/acc-uww2d/sven/svenlatham-website