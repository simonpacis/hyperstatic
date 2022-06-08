<?php

$json = json_decode(file_get_contents($raw_cwd . '/strings.json'), true);

function hs($string)
{
	global $json;
	if(array_key_exists($string, $json))
	{
		return $json[$string];
	}
}
