<?php

class TwitterAPI {
	private $_config;
	private $_host = 'api.twitter.com';
	private $_method = 'GET';
	private $_path = '/1.1/lists/statuses.json';  // api call path
	private $_error = '';

	public function __construct() {
		include_once "config.php";
		$this->_config = $config;
	}

	public function lists($slug, $owner=false) {
		$this->_path = '/1.1/lists/statuses.json'; // api call path
		$query = array( // query parameters
		    'owner_screen_name' => ($owner) ? $owner : $this->config['screen_name'],
		    'slug' => $slug,
		    'count' => $this->_config['count'],
		    'include_rts' => $this->_config['list_include_rts'],
			'include_entities' => 'true',
		    'trim_user' => 'false'
		);

		return $this->_getData($query);
	}

	public function search($keyword) {
		 $this->_path = '/1.1/search/tweets.json';
		 $query = array( // query parameters
			'q' => $keyword,
			'count' => $this->_config['count'],
			'include_entities' => 'true',
			'result_type' => $this->_config['search_result_type']
		);

		return $this->_getData($query);
	}

	public function user($screen_name) {
		$this->_path = '/1.1/statuses/user_timeline.json';
		$query = array( // query parameters
		    'screen_name' => $screen_name,
		    'count' => $this->_config['count'],
		    'trim_user' => 'false',
			'exclude_replies' => !$this->_config['user_include_replies'],
			'include_rts' => $this->_config['user_include_rts'],
		);

		return $this->_getData($query);
	}

	public function home() {
		$this->_path = '/1.1/statuses/home_timeline.json'; // api call path

		$query = array( // query parameters
			'count' => $this->_config['count'],
			'trim_user' => 'false',
			'exclude_replies' => !$this->_config['home_include_replies'],
			'include_entities' => 'true',
			'include_rts' => $this->_config['home_include_rts'],
		);
		return $this->_getData($query);
	}

	public function follow($screen_name, $id) {
		$this->_path = "/1.1/friendships/create.json";
		$this->_method = "POST";
		$data = array("follow" => "true");
		if ( isset($screen_name) )
			$data['screen_name'] = $screen_name;
		elseif ( isset($id) )
			$data['id'] = $id;

		return $this->_getData($query, $data);
	}

	private function _getData($query, $post_fields = FALSE) {
		$oauth = array(
		    'oauth_consumer_key' => $this->_config['consumer_key'],
		    'oauth_token' => $this->_config['token'],
		    'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
		    'oauth_timestamp' => time(),
		    'oauth_signature_method' => 'HMAC-SHA1',
		    'oauth_version' => '1.0'
		);

		$oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting

		$arr = array_merge($oauth, $query); // combine the values THEN sort

		asort($arr); // secondary sort (value)
		ksort($arr); // primary sort (key)

		// http_build_query automatically encodes, but our parameters
		// are already encoded, and must be by this point, so we undo
		// the encoding step
		$querystring = urldecode(http_build_query($arr, '', '&'));

		$url = "https://".$this->_host.$this->_path;

		// mash everything together for the text to hash
		$base_string = $this->_method."&".rawurlencode($url)."&".rawurlencode($querystring);

		// same with the key
		$key = rawurlencode($this->_config['consumer_secret'])."&".rawurlencode($this->_config['token_secret']);

		// generate the hash
		$signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));

		// this time we're using a normal GET query, and we're only encoding the query params
		// (without the oauth params)
		$url .= "?".http_build_query($query);
		$url=str_replace("&amp;","&",$url); //Patch by @Frewuill
		$url = str_replace("%25", "%", $url);

		$oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
		ksort($oauth); // probably not necessary, but twitter's demo does it

		// also not necessary, but twitter's demo does this too
		$func = function ($str) { return '"'.$str.'"'; };
		$oauth = array_map($func, $oauth);

		// this is the full value of the Authorization line
		$auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));

		// if you're doing post, you need to skip the GET building above
		// and instead supply query parameters to CURLOPT_POSTFIELDS
		$options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
		                  //CURLOPT_POSTFIELDS => $postfields,
		                  CURLOPT_HEADER => false,
		                  CURLOPT_URL => $url,
		                  CURLOPT_RETURNTRANSFER => true,
		                  CURLOPT_SSL_VERIFYPEER => false);

		if ( $post_fields ) {
			$options[CURLOPT_POSTFIELDS] = http_build_query($post_fields);
		}

		// do our business
		$feed = curl_init();
		curl_setopt_array($feed, $options);
		$json = curl_exec($feed);
		if ( !$json )
			$this->_error = curl_error($feed);
		curl_close($feed);

		return json_decode($json, true);
	}

	public function getError() {
		return $this->_error;
	}

	public function setConfig ( $which, $value ) {
		$this->_config[$which] = $value;
	}

	public function getConfig ( $which ) {
		return $this->_config[$which];
	}
}

?>