<?php

/*
 *
 *
 *	hyperStatic. A simple templating engine in PHP.
 *
 *
 * */


$directory = new DirectoryIterator(getcwd());

$raw_cwd = substr(getcwd(), 0, strrpos(getcwd(), "/"));
$dist_cwd = $raw_cwd . "/dist"; 

if(file_exists($raw_cwd . '/strings.json'))
{
	$json = json_decode(file_get_contents($raw_cwd . '/strings.json'), true);
} else {
	$json = [];
}

function hs($string)
{
	global $json;
	if(array_key_exists($string, $json))
	{
		return $json[$string];
	}
}

function hscontains($string, $sort = false)
{
	global $json;
	$js_contains = [];
	foreach($json as $key => $json_string)
	{
		if(strpos($key, $string) !== false)
		{
			$js_contains[$key] = $json_string;
		}
	}

	if($sort)
	{
		ksort($js_contains);
	}
	return $js_contains;
}


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



if(!is_dir($dist_cwd))
{
	mkdir($dist_cwd);
}


foreach ($directory as $file_info) {
    if (isValidFile($file_info)) {
        $data = parseFile($file_info);
        $file = "dist/" . $file_info->getBasename('.php') . '.html';
        file_put_contents(substr(getcwd(), 0, strrpos( getcwd(), '/')) . "/" . $file, $data);
    }
}
