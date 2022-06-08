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

In `src/` you keep all your .php-files.
(So, index.php, about.php etc.)

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
Create a file called `strings.json` in the project directory.
So, next to `dist/` and `src/`.

### Usage
In your .php-files you type

```php

<?= hs('json_key') ?>

```
