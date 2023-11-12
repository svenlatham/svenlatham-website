#!/bin/bash
# We're going to use this to build a local output in _build/

mkdir ./_site/
docker build -t svenlatham-website . || exit 1
docker run -v ./_site/:/build/ svenlatham-website build --output-path=/build/
