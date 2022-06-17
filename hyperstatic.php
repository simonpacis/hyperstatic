<?php

/*
 *
 *
 *	hyperStatic. A simple templating engine in PHP.
 *
 *
 * */



$raw_cwd = getcwd();
$src_cwd = $raw_cwd . "/src"; 
$raw_dist_cwd = $raw_cwd . "/dist"; 
$pure_dist_cwd = $raw_cwd . "/assets"; 
$directory = new DirectoryIterator($src_cwd);
$project_directory = new DirectoryIterator($raw_cwd);
$data = null;


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

function hsexists($string)
{
	global $json;
	if(array_key_exists($string, $json))
	{
		return true;
	} else {
		return false;
	}
}

function hsc($string, $component)
{
	global $json, $src_cwd, $data;

	$GLOBALS['data'] = hs($string);
	return include($src_cwd . "/" .  $component); 

}

function hscv($variable = "data")
{
	return $GLOBALS[$variable];
}

$has_rsync = null;
function copyDirectory($src, $dest, $exclude = "")
{
	global $has_rsync;
	if($has_rsync == null)
	{
		if(shell_exec("command -v rsync"))
		{
			$has_rsync = true;
		} else {
			$has_rsync = false;
		}
	}

	if($has_rsync)
	{
		if($exclude != "")
		{
			shell_exec("rsync -r --exclude '".$exclude."' '". $src ."' '". $dest."'");
		} else {
			shell_exec("rsync -r ". $src ." ". $dest);
		}
	} else {
		shell_exec("cp -r ". $src ." ". $dest);
	}




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

if(!is_dir($raw_dist_cwd))
{
	mkdir($raw_dist_cwd);
}

foreach($project_directory as $file_info)
{
	if($file_info->getExtension() == "json")
	{
		$json = json_decode(file_get_contents($raw_cwd . '/' . $file_info->getBasename()), true);
		$dist_cwd = $raw_dist_cwd . "/" . $file_info->getBasename('.json'); 


		if(!is_dir($dist_cwd))
		{
			mkdir($dist_cwd);
		}


		if(is_dir($pure_dist_cwd . "/" . $file_info->getBasename('.json')))
		{
			copyDirectory($pure_dist_cwd . "/", $dist_cwd, "/" . $file_info->getBasename('.json'));
			copyDirectory($pure_dist_cwd."/".$file_info->getBasename('.json')."/", $dist_cwd);
		} else {
			copyDirectory($pure_dist_cwd . "/", $dist_cwd);
		}





		foreach ($directory as $file_info) {
			if (isValidFile($file_info)) {
				$data = parseFile($file_info);
				$file = $file_info->getBasename('.php') . '.html';
				file_put_contents($dist_cwd . "/" . $file, $data);
			}
		}
	}
}
