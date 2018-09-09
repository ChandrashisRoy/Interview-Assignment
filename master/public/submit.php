<?php
    require_once "../include/config.php"; //adding config file
    require_once "../include/ShortUrl.php"; //adding ShortUrl file

    try {
        $pdo = new PDO(DB_PDODRIVER . ":host=" . DB_HOST . ";dbname=" . DB_DATABASE,
            DB_USERNAME, DB_PASSWORD);
    }//connecting to database

    catch (\PDOException $e) {
        header("Location: error.html");
        exit;
    }//error message

    $shortUrl = new ShortUrl($pdo);
    try {
        $code = $shortUrl->urlToShortCode($_POST["url"]);
    }
    catch (\Exception $e) {
        header("Location: error.html");
        exit;
    }
    $url = SHORTURL_PREFIX . $code;

    echo <<<ENDHTML
<html>
    <head>
        <title>URL Shortener</title>
    </head>
    <body>
        <p style="text-align: center; padding:10%">Success! ! !<strong>Your Short URL is:</strong> <a href="$url">$url</a></p>
    </body>
</html>
ENDHTML;
