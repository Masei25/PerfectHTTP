<?php

require "vendor/autoload.php";

use Src\PerfectHTTP;

$client = new PerfectHTTP();
$client->setRequest([
	"URL" => 'http://jsonplaceholder.typicode.com/comments', 
	"METHOD" => 'POST', 
	"HEADER" => array(
					'Content-Type: application/json'
				),		
	"OPTIONS" => array(
		'postId' => 5
	) 
]);

echo $client->response;