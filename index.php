<?php
// * Make sure config.php exists before loading it
if(!file_exists("config.php")) {
	print('ERROR: Before using this script, copy config-default.php to config.php and customize it.');
	exit();
}

//Twitter API
include_once "api.php";
$api = new TwitterAPI();

//Feed class
include_once "feed.php";
$feed = new RssFeed();

// * Fetch $count from the URL if the user supplied it
if (isset($_GET["count"])) {
	if (is_int(intval($_GET["count"]))) {
		$api->setConfig( "count", intval( $_GET["count"] ) );
	}
}

// * Now, based on what variables are in the URL, pick the type
// * Of query we're going to do.
// *
// * Note these use the ternary operator (?:) to keep things short.
// * It works like: (if this is true) ? (then return this) : (else return this)
$twitter_data = null;

switch ( $_GET['source'] ) {
	case "screen_name":
		$screen_name = htmlspecialchars($_GET["screen_name"]);
		$twitter_data = $api->user($screen_name);
	break;
	case "list":
		$list = htmlspecialchars($_GET["list"]);
		$owner = false;
		if(isset($_GET["owner"])) {
			$owner = htmlspecialchars($_GET["owner"]);
		} 
		$twitter_data = $api->lists($list, $owner);
	break;
	case "keyword":
		$twitter_data = $api->search($_GET["q"]);
		//$twitter_data = $twitter_data['statuses']; //Slightly different on searches
	break;
	case "home":
		$twitter_data = $api->home();
	break;
}

print_r ( $twitter_data );
?>
