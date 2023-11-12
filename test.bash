#!/bin/bash

podman build -t svenlatham-website . || exit 1
podman run -v ./src/:/src/ -it -p 5000:5000 svenlatham-website server -h 0.0.0.0