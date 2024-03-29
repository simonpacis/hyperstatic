# hyperStatic 

Hyper-minimal PHP static file templating engine

Renders .php-files as .html-files.

## To use

### Setup
Have the following folder structure in your project directory:

```
assets/
src/
project_name.json
```

You can call `project_name.json` whatever you want, then a `project_name/` directory will be created.

In `src/` you keep all your .php-files.
(So, index.php, about.php etc.)

These files will all be converted to index.html, about.html etc.
in a directory called `dist/project_name/`.
You can put your stylesheets, scripts, etc.
in the `assets/` directory, they will be copied to the `dist/project_name/` directory, where your .html-files will also be created.


### the JSON-file
Go to the Templating-section of this document to learn how to use the json-file.
You don't have to use templating, and can leave it empty as so:

```json
{}
```

### Usage
Drop the hyperstatic.php-file into your project directory.

Run `php hyperstatic.php`

And it will convert all .php-files in `src/` to .html-files in `project_name/`.
It processes includes and all php-functions.

Protip: Using a tool like [php-watcher](https://github.com/seregazhuk/php-watcher) is a great way to watch the src/-directory for changes, and run hyperStatic automatically.
Use this oneliner in your project directory after you've installed php-watcher:

```
php-watcher --watch . --watch src --ext=php,json hyperstatic.php
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

Don't limit yourself to strings thought.
Arrays, objects, booleans, integers etc can all be written in the json-file and will be returned by the `hs` function.
Like so:

```
{
	"json_array": ['one', 'two']
}
```

Could be used like this in the .php-file:

```php

foreach(hs('json_array') as $key => $value)
{
	echo $value;
}
```

The sky's the limit, use all of php with hyperStatic.

### Multi-JSON
If you want to generate multiple versions of your site with a different .json-file for each (for e.g.
multiple languages), simply create multiple .json-files and populate them.
Directories will be created for each one.
Like so:

```
assets/
src/
project_name.json
project_name_en.json
```

If you generate multiple sites, you can put assets in a subdirectory in `assets` called the project_name, and those will only be copied to that outputted site.
Like so:

```
assets/
assets/project_name/app.js
project_name.json
project_name_en.json
```

This will generate the following folder structure:

```
assets/
dist/
dist/project_name
dist/project_name_en
src/
```

The `assets/project_name/app.js` will only show up in `dist/project_name/app.js` and not in `dist/project_name_en`.


#### Limiting pages for certain versions
Create a "pages"-entry in the json-file, and type in which .php-files you want converted for this given version.
Like so:

```json
"pages": ["index.php", "about.php"],
```

And only those .php-files will be converted for that given version.

### Components
You can create reusable components that are easily called from the code, and that you can easily pass variables from JSON onto.

#### Creating a component
Create a .php-file in your `src` directory.
You could create a subdirectory like `src/components`, and create it there.

Here's an example of a paragraph component.
This code is in a file placed in `src/components/p.php`, and contains the following code:

```php
<p class="paragraph"><?= hscv(); ?></p>
```

`hscv()` echoes the passed variable, and is short for hyperStatic Component Variable.

#### Printing a component
In `src/index.php` we want to call the new paragraph component to use it, and we do it as such:

```php
<?php hsc('index.paragraph', 'components/p.php'); ?>
```

This will automatically pass the 'index.paragraph' key to the p.php-component, where hscv() will print it out.
In our instance the key contains "Hello World".
The resulting HTML will be:

```html
<p class="paragraph">Hello World</p>

```

### Documentation
Documentation can be accessed at [https://simonpacis.github.io/hyperstatic/](https://simonpacis.github.io/hyperstatic/).
