<?php

/**
 * hyperStatic. A simple templating engine in PHP.
 * */



$raw_cwd = getcwd();
$src_cwd = $raw_cwd . "/src"; 
$raw_dist_cwd = $raw_cwd . "/dist"; 
$pure_dist_cwd = $raw_cwd . "/assets"; 
$directory = new DirectoryIterator($src_cwd);
$project_directory = new DirectoryIterator($raw_cwd);
$data = null;
$has_rsync = null;


/**
 * If $key is found in the associated .json-file, it returns the value associated with it.
 *
 * @param string $key The $key you are looking to get the value from in the associated .json-file.
 *
 * @return string|null The value associated with $key in the associated .json-file or null if nothing found.
 */
function hs($key)
{
	global $json;
	if(array_key_exists($key, $json))
	{
		return $json[$key];
	}
}

/**
 * Returns keys (and their values) that contain $substr in the associated .json-file.
 *
 * E.g. if you pass "navigation" as the $substr parameter, all entries in the associated .json-file that contain "navigation" will be returned.
 *
 * @param string $substr The substring to look for. 
 * @param bool $sort Optional. Whether or not to sort the returned keys alphabetically. Defaults to false.
 *
 * @return array The entries in which the key contained $substr.
 */
function hscontains($substr, $sort = false)
{
	global $json;
	$js_contains = [];
	foreach($json as $key => $json_string)
	{
		if(strpos($key, $substr) !== false)
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
/**
 * Returns true if a key in the associated .json-file with $key exists.
 *
 * @param string $key The $key to look for.
 *
 * @return bool True if $key exists, false if not.
 */
function hsexists($key)
{
	global $json;
	if(array_key_exists($key, $json))
	{
		return true;
	} else {
		return false;
	}
}
/**
 * Returns the included hyperStatic Component found at $component, and populates it with the data found at $key in the assoicated .json-file.
 *
 * @param string $key The $key to populate the component with data from.
 * @param string $component The path to the component that is to be included and pre-populated.
 *
 * @return string The included component pre-populated.
 */
function hsc($key, $component)
{
	global $json, $src_cwd, $data;

	$GLOBALS['data'] = hs($key);
	return include($src_cwd . "/" .  $component); 

}

/**
 * Returns the component variable for the current component. Can only be called from within a component.
 *
 * @param string $variable Optional. The name of the variable stored in $GLOBALS. hsc() sets it to "data". Defaults to "data".
 *
 * @return mixed The component variable for the current component. 
 */
function hscv($variable = "data")
{
	return $GLOBALS[$variable];
}

/**
 * Copies a directory and all of its contents from $src to $dest.
 *
 * If your system has rsync installed it is possible to exclude a certain file or directory from being copied. If not, everything will be copied.
 *
 * @param string $src The path to copy files from.
 * @param string $dest The path to copy files to.
 * @param string $exclude Optional. Path to file or directory that is not to be copied. Defaults to "".
 *
 * @return void
 */
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

/**
 * Checks whether a passed file is valid for our purposes.
 *
 * Being valid here means that it is a .php-file, not hyperstatic.php, and not a directory.
 *
 * @param SplFileInfo $file The file to evaluate.
 *
 * @return bool Whether the file is valid or not.
 */
function isValidFile(SplFileInfo $file)
{
	return $file->isFile()
		&& 'php' === $file->getExtension()
		&& basename(__FILE__) !== $file->getBasename();
}

/**
 * Compiles the passed .php-file, so that the outputs of processing it can be stored as a string in a variable.
 *
 * @param SplFileInfo $file The file to compile.
 * 
 * @return string The compiled .php-file.
 */
function parseFile(SplFileInfo $file)
{
	global $src_cwd;
	ob_start();
	include $src_cwd . "/" . $file->getBasename();
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

		if(array_key_exists("pages", $json))
		{
			foreach($directory as $file_info)
			{
				if(isValidFile($file_info))
				{
					if(in_array($file_info->getBasename(), $json['pages']))
					{
						$data = parseFile($file_info);
						$file = $file_info->getBasename('.php') . '.html';
						file_put_contents($dist_cwd . "/" . $file, $data);
					}
				}
			}

		} else { // Convert all .php-files found.
			foreach ($directory as $file_info) {
				if (isValidFile($file_info)) {
					$data = parseFile($file_info);
					$file = $file_info->getBasename('.php') . '.html';
					file_put_contents($dist_cwd . "/" . $file, $data);
				}
			}
		}
	}
}
