<?php

require_once 'config.php';
// $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

// $server = $url["host"];
// $username = $url["user"];
// $password = $url["pass"];
// $db = substr($url["path"], 1);

// $conn = new mysqli($server, $username, $password, $db);

$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
?>