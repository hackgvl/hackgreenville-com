# Hack Greenville

[Main Docs](../README.md)

## Image Optimization

I ran these 2 commands that use `mogrify` to optimize the images in the `resources/sass/img` directory.

```bash
find ./resources/sass/img/ -iname '*.jpg' -exec mogrify -strip -interlace Plane -gaussian-blur 0.05 -quality 85% {} \;
find ./resources/sass/img/ -iname '*.png' -exec mogrify -strip {} \;
```

mogrify - is a small application used to script image editing, its pretty powerful.
