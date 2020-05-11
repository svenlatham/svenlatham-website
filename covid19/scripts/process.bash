#!/bin/bash
cd output
rm *
cd ..
php process.php
cd output
cp * /mnt/c/projects/svenlatham-website/covid19/data/google/
cd ..