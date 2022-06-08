<?php

/*
 *
 *
 *	hyperStatic. A simple templating engine in PHP.
 *
 *
 * */



//$raw_cwd = substr(getcwd(), 0, strrpos(getcwd(), "/"));
$raw_cwd = getcwd();
$src_cwd = $raw_cwd . "/src"; 
$pure_dist_cwd = $raw_cwd . "/dist"; 
$directory = new DirectoryIterator($src_cwd);
$project_directory = new DirectoryIterator($raw_cwd);


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
	global $src_cwd;
	ob_start();
	require_once $src_cwd . "/" . $file_info->getBasename();
	$data = ob_get_contents();
	ob_end_clean();

	return $data;
}

foreach($project_directory as $file_info)
{
	if($file_info->getExtension() == "json")
	{
		$json = json_decode(file_get_contents($raw_cwd . '/' . $file_info->getBasename()), true);
		$dist_cwd = $raw_cwd . "/" . $file_info->getBasename('.json'); 


		if(!is_dir($dist_cwd))
		{
			mkdir($dist_cwd);
		}

		shell_exec("cp -r $pure_dist_cwd/ $dist_cwd");


		foreach ($directory as $file_info) {
			if (isValidFile($file_info)) {
				$data = parseFile($file_info);
				$file = $file_info->getBasename('.php') . '.html';
				file_put_contents($dist_cwd . "/" . $file, $data);
			}
		}
	}
}
