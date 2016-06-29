<?php

require_once 'config.php';

// If working with heroku

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$connection = new PDO("mysql:host=".$server.";dbname=".$db, $username, $password);

// Uncomment if working locally with phpmyadmin
// $connection = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
?>