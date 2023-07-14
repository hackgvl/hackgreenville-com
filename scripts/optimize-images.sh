#!/bin/bash

echo "Optimizing jpg images in resources/sass/img directory..."
find ./resources/sass/img/ -iname '*.jpg' -exec mogrify -strip -interlace Plane -gaussian-blur 0.05 -quality 85% {} \;
echo "done"

echo "Optimizing png images in resources/sass/img directory..."
find ./resources/sass/img/ -iname '*.png' -exec mogrify -strip {} \;
echo "done"
