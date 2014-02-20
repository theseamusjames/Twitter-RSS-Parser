<?php 
// * ***************************************************************
// * API tokens
// * ***************************************************************
$config["token"] = 'XXXX';
$config["token_secret"] = 'XXXX';
$config["consumer_key"] = 'XXXX';
$config["consumer_secret"] = 'XXXX';


// * Enter your twitter username below.  This is used for the home
// * view, and a default for lists if one isn't specified in the URL.
$screen_name = ""; //No @ symbol


// * URL by appending "&count=X" to any query.
// * Note: Count includes RTs, even if you exclude them below.
// * max value: 200
// * default:   10
$config["count"] = 10;



// * ***************************************************************
// * User Status Parameters	
// * ***************************************************************

// * Max tweets to retrieve. Count includes RTs, even if you exclude them below.
// * max value: 200
// * default:   "count
$config["user_count"] = $count;

// * Sets whether retweets are included in the timeline.  Retweets count
// * towards the "count limit even if this is true, they're just hidden.
// * default: false
$config["user_include_rts"] = false;

// * Sets whether replies are included in the timeline.  Replies count
// * towards the "count limit even if this is true, they're just hidden.
// * default: false
$config["user_include_replies"] = false;



// * ***************************************************************
// * List Parameters	
// * ***************************************************************

// * Max tweets to retrieve. Count includes RTs, even if you exclude them below.
// * max value: 200
// * default:   "count
$config["list_count"] = $count;

// * Sets whether retweets are included in the timeline.  Retweets count
// * towards the "count limit even if this is true, they're just hidden.
// * default: true
$config["list_include_rts"] = false;



// * ***************************************************************
// * Home Timeline Parameters
// * ***************************************************************

// * Max tweets to retrieve. Count includes RTs, even if you exclude them below.
// * max value: 200
// * default:   "count
$config["home_count"] = $count;

// * Sets whether retweets are included in the timeline.  Retweets count
// * towards the "count limit even if this is true, they're just hidden.
// * default: true
$config["home_include_rts"] = true;

// * Sets whether replies are included in the timeline.  Replies count
// * towards the "count limit even if this is true, they're just hidden.
// * default: true
$config["home_include_replies"] = true;



// * ***************************************************************
// * Search Parameters
// * ***************************************************************

// * Max tweets to retrieve. Count includes RTs, even if you exclude them below.
// * max value: 200
// * default:   "count
$config["search_count"] = $count;

// * Type of search results to return.
// * options: mixed|recent|popular
// * default: mixed
$config["search_result_type"] = 'mixed';
);