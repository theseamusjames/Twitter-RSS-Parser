<?php

function processString($s) {
    return preg_replace('/https?:\/\/[\w\-\.!~#?&=+\*\'"(),\/]+/','<a href="$0">$0</a>',$s);
}

function printArray($s){
	print("<pre>");
	print_r($s);
	print("</pre>". PHP_EOL);
}

if (isset( $_GET["test"] )){	
	print('id: ' . gettype($twitter_data[0]['id']). '<br>'. PHP_EOL);
	print('id_str: ' . gettype($twitter_data[0]['id_str']). PHP_EOL);
	
	if ($_GET["test"] == 'json')
		$test = $json;
	else
		$test = $twitter_data;
	
	printArray($test);
	
	printArray("url: + " . $url);
}

?>