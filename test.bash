#!/bin/bash

docker build -t svenlatham-website . || exit 1
docker run -v ./src/:/src/ -it -p 5000:5000 svenlatham-website server -h 0.0.0.0