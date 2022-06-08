<?php

/*
 *
 *
 *	hyperStatic. A simple templating engine in PHP.
 *
 *
 * */


$directory = new DirectoryIterator(getcwd());

function isValidFile(SplFileInfo $file_info)
{
    return $file_info->isFile()
        && 'php' === $file_info->getExtension()
        && basename(__FILE__) !== $file_info->getBasename();
}

function parseFile(SplFileInfo $file_info)
{
    ob_start();
    require_once $file_info->getBasename();
    $data = ob_get_contents();
    ob_end_clean();

    return $data;
}

if(!is_dir(getcwd(). "/dist"))
{
	mkdir(getcwd(). '/dist');
}


foreach ($directory as $file_info) {
    if (isValidFile($file_info)) {
        $data = parseFile($file_info);
        $file = "dist/" . $file_info->getBasename('.php') . '.html';
        file_put_contents(getcwd() . "/" . $file, $data);
    }
}
