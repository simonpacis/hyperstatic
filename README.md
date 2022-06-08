# hyperStatic 

Hyper-minimal PHP static file templating engine

Renders .php-files as .html-files.

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
Drop the hyperstatic.php-file into your project directory.

Run `php hyperstatic.php`

And it will convert all .php-files in `src/` to .html-files in `dist/`.
It processes includes and all php-functions.

## Templating
hyperStatic can insert strings of text into your files.

### Setup
Create a file called `strings.json` in the project directory.
So, next to `dist/` and `src/`.

In the strings.json you type strings of text that you want inserted in your html-documents.
E.g.:

```json
{
  "json_key_here": "This text will be outputted in the html-file."
}
```

### Usage
In your .php-files you type

```php

<?= hs('json_key_here') ?>

```

And the text in the 'json_key_here' entry in strings.json will be outputted in your .html-file.

### Functions
Functions relating to the templating engine are prefixed with "hs" - except for the output-function which is just hs().

#### hs()
Outputs corresponding key in strings.json.

```php

hs($key);

```

### hscontains()
