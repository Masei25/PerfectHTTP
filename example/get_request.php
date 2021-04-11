<?php

require "vendor/autoload.php";

use Src\PerfectHTTP;

$client = new PerfectHTTP();
$client->setRequest([
    'METHOD' => 'GET', 
    'URL' => 'https://jsonplaceholder.typicode.com/comments', 
    'OPTIONS' => [
        'postId' => 5
    ], 
    'HEADER' => []
]);

echo $client->response;