<?php
	require_once "../include/config.php"; //adding config file
	require_once "../include/ShortUrl.php"; //adding ShortUrl file

	$code = $_GET["c"];

	try {
	    $pdo = new PDO(DB_PDODRIVER . ":host=" . DB_HOST . ";dbname=" . DB_DATABASE,
	        DB_USERNAME, DB_PASSWORD);
	} //connecting to database

	catch (\PDOException $e) {
	    header("Location: error.html");
	    exit;
	} //error message

	$shortUrl = new ShortUrl($pdo);
	try {
	    $url = $shortUrl->shortCodeToUrl($code);
	    header("HTTP/1.1 301 Moved Permanently");
	    header("Location: " . $url);
	}
	catch (\Exception $e) {
	print_r($e);
	    header("Location: error.html");
	    exit;
	}
