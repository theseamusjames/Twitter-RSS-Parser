<?php

class RssFeed {
	private $_protocol;

	function __construct() {
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
		    || $_SERVER['SERVER_PORT'] == 443) {

		    $this->_protocol = 'https://';
		} else {
		    $this->_protocol = 'http://';
		}
	}

	public function start($id, $title, $updated, $link) {
		header('Content-type: application/atom+xml; charset=utf-8');
		print('<?xml version="1.0" encoding="utf-8"?>'. PHP_EOL);
		print('<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en" xml:base="'.$_SERVER['SERVER_NAME'].'">'. PHP_EOL);

		print('<id>'.$id.'</id>'. PHP_EOL);
		print('<title>'. urldecode($title) . '</title>'. PHP_EOL);
		print('<updated>'.$updated.'</updated>'. PHP_EOL);

		print('<link href="'.$link.'"/>'. PHP_EOL);
		print('<link href="'.$this->_protocol.$_SERVER['SERVER_NAME'].str_replace("&", "&amp;", $_SERVER['REQUEST_URI']).'" rel="self" type="application/atom+xml" />'. PHP_EOL);
	}

	public function entry($id, $link, $title, $summary, $content, $updated, $author, $categories) {
		print(PHP_EOL. '	<entry>'. PHP_EOL);
		print('		<id>'. $id . '</id>'. PHP_EOL);
		print('		<link href="'.$link.'" rel="alternate" type="text/html"/>'. PHP_EOL);
		print('		<title>'.$title.'</title>'. PHP_EOL);
		print('		<summary type="html"><![CDATA['.$summary.']]></summary>'. PHP_EOL);
		
		$feedContent = '		<content type="html"><![CDATA[<p>'.$content.'</p>]]></content>';
		$text = processString($feedContent);
		
		print($text . PHP_EOL);
		print('		<updated>'.$updated.'</updated>'. PHP_EOL);
		print('		<author><name>'.$author.'</name></author>'. PHP_EOL);
		
		if (count($categories) > 0){
			foreach ( $categories as $category){
				print('		<category term="'.$category.'"/>'. PHP_EOL);
			}
		}
		
		print('	</entry>'. PHP_EOL);
	}

	public function end() {
		print('</feed>'. PHP_EOL);
		print('<!-- vim:ft=xml -->');
	}
}

?>
