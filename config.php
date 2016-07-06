<?php 

// If working locally

// Database Constants
// defined("DB_SERVER")? null : define("DB_SERVER", "127.0.0.1");
// defined("DB_USER") ? null : define("DB_USER", "waiter");
// defined("DB_PASS") ? null : define("DB_PASS", "12345"); 
// defined("DB_NAME") ? null : define("DB_NAME", "smartorder");


// If working with heroku

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

defined("DB_SERVER")? null : define("DB_SERVER", $url["host"]);
defined("DB_USER") ? null : define("DB_USER", $url["user"]);
defined("DB_PASS") ? null : define("DB_PASS", $url["pass"]); 
defined("DB_NAME") ? null : define("DB_NAME", substr($url["path"], 1));

?>