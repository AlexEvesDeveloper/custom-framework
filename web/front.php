<?php

require_once __DIR__.'/../src/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// setup
$request = Request::createFromGlobals();
$response = new Response();

// valid URL paths, and their corresponding script names to execute (excluding .php)
$paths = array(
	'/hello' => 'hello',
	'/bye' => 'bye'
);

// everything beyond 'front.php', including the slash (/)
$path = $request->getPathInfo();

// does the path in URL match any specified in our $paths array?
if(isset($paths[$path])){
	// match
	// prepare a buffer to store the PHP output from the script
	ob_start();

	// extract any GET parameters into variable names, i.e 'hello?name=Alex' creates $name == alex
	extract($request->query->all(), EXTR_SKIP);

	// execute the requested page script
	include sprintf(__DIR__.'/../src/pages/%s.php', $paths[$path]);

	// send the buffered PHP output from the script, as the Response content. Clean from the buffer afterwards
	$response->setContent(ob_get_clean());
}else{
	// no match
	$response->setStatusCode(404);
	$response->setContent('Page not found');
}

$response->send();