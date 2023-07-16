#!/bin/bash

#RECURSIVELY Find JPG files in ALL directory and optimize all of them to keep Google Page Speed Happy skip anything with an improvement of less than 3% (-T3)
find resources/sass/img/ -type f -name "*.jpg" -exec jpegoptim -m85 -T3 --strip-all {} \;

# recursively compress all PNGs in and below the current directory
find resources/sass/img/ -name '*.png' -exec pngquant --ext .png --quality=65-80 --force --skip-if-larger 256 {} \;
