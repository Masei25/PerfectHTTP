<?php

require "vendor/autoload.php";

use Src\PerfectHTTP;

$client = new PerfectHTTP();

$client->setRequest([
    'METHOD' => 'GET', 
    'URL' => 'http://jsonplaceholder.typicode.com/comments', 
    'OPTIONS' => [], 
    'HEADER' => []
]);

$res = $client->response;
echo($res);