# hyperStatic 
*Warning: This project is an idea under development.*

Templating engine.
Outputs .html files.

## To use

### Setup
Have the following folder structure in your project directory:

```
dist/
src/
```

In `src/` you keep all the .php-files.
So, index.php, about.php etc.
etc.

These files will all be converted to index.html, about.html etc.
in the `dist/` directory.
You can out your stylesheets, scripts, etc.
in the dist directory to begin with, then the .html files will be added by hyperStatic.

### Usage

Run `php hyperstatic.php`

Converts all .php files to .html.
Processes includes etc.

## Templating
hyperStatic can insert strings of text into your files.

### Setup
Create a file 
