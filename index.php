<?php
error_reporting(0);

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

//Some helper functions
include_once "functions.php";

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

//We'll need to set these individually for the feed header
$id = null; 
$title = null;
$link = null;

switch ( $_GET['source'] ) {
	case "user":
		$screen_name = htmlspecialchars($_GET["q"]);
		$twitter_data = $api->user($screen_name);

		$id = "tag:twitter.com,2006:/".$screen_name;
		$title = "User: ".$screen_name;
		$link = "https://twitter.com/".$screen_name;
	break;
	case "list":
		$list = htmlspecialchars($_GET["q"]);
		$owner = $api->getConfig("screen_name");
		if(isset($_GET["owner"])) {
			$owner = htmlspecialchars($_GET["owner"]);
		}
		$twitter_data = $api->lists($list, $owner);

		$id = "tag:twitter.com,2006:/".$owner."/".$list;
		$title = "User: ".$owner;
		$link = "https://twitter.com/".$owner."/".$list;
	break;
	case "keyword":
		$twitter_data = $api->search($_GET["q"]);
		$twitter_data = $twitter_data['statuses']; //Slightly different on searches

		$id = "tag:twitter.com,2006:/search/".$_GET["q"];
		$title = "Search: ".$_GET["q"];
		$link = "https://twitter.com/search?q=".$_GET["q"];
	break;
	case "home":
		$twitter_data = $api->home();

		$id = "tag:twitter.com,2006:/".$api->getConfig("screen_name");
		$title = "User: ".$api->getConfig("screen_name");
		$link = "https://twitter.com/".$api->getConfig("screen_name");
	break;
}

if ( $twitter_data )
	$updated = $twitter_data[0]['created_at'];


$feed->start($id, $title, $updated, $link);
foreach ( $twitter_data as $i => $data ) {
	$id = 'tag:twitter.com,' . date("Y-m-d", strtotime($data['created_at'])) . ':/' . $data['user']['screen_name'] . '/statuses/' . $data['id_str'];
	$link = 'https://twitter.com/'.$data['user']['screen_name'].'/statuses/'. $data['id_str'];
	$title = $data['user']['screen_name'].': '.htmlspecialchars($data['text']);
	$summary = $data['user']['screen_name'].': '.$data['text'];
	$content = $data['text'];
	$updated = date('c', strtotime($data['created_at']));
	$author = $data['user']['screen_name'];
	$categories = array();
	foreach ( $data['entities']['hashtags'] as $tag )
		$categories[] = $tag['text'];

	$feed->entry ( $id, $link, $title, $summary, $content, $updated, $author, $categories );
}
$feed->end();
?>
