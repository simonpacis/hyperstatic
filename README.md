# hyperStatic 

Hyper-minimal PHP static file templating engine

Renders .php-files as .html-files.

## To use

### Setup
Have the following folder structure in your project directory:

```
dist/
src/
project_name.json
```

You can call `project_name.json` whatever you want, then a `project_name/` directory will be created.

In `src/` you keep all your .php-files.
(So, index.php, about.php etc.)

These files will all be converted to index.html, about.html etc.
in the `project_name/` directory.
You can put your stylesheets, scripts, etc.
in the `dist/` directory, they will be copied to the `project_name/` directory, where your .html-files will also be created.

### the JSON-file
Go to the Templating-section of this document to learn how to use the json-file.
You don't have to use templating, and can leave it empty as so:

```json
{}
```

### Usage
Drop the hyperstatic.php-file into your project directory.

Run `php hyperstatic.php`

And it will convert all .php-files in `src/` to .html-files in `dist/`.
It processes includes and all php-functions.

Protip: Using a tool like [php-watcher](https://github.com/seregazhuk/php-watcher) is a great way to watch the src/-directory for changes, and run hyperStatic automatically.
Use this oneliner in your project directory after you've installed php-watcher:

```
php-watcher --watch .<!-- --> --watch src --ext=php,json hyperstatic.php
```

## Templating
hyperStatic can insert strings of text into your files.

### Setup
In your `project_name.json`-file you type strings of text that you want inserted in your html-documents.
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

### Multi-JSON
If you want to generate multiple versions of your site with a different .json-file for each (for e.g.
multiple languages), simply create multiple .json-files and populate them.
Directories will be created for each one.
Like so:

```
dist/
src/
project_name.json
project_name/
project_name_en.json
project_name_en/
```


### Functions
Functions relating to the templating engine are prefixed with "hs" - except for the output-function which is just hs().

#### hs()
Outputs corresponding key in strings.json.

```php

hs($key);

```

### hscontains()
Returns an array of entries which keys contain the given string.

```php
hscontains($key);
```

So, a json-file with the entries "navigation.first" and "navigation.second" will both be returned if the function is run as follows:

```php

hscontains('navigation');

```


