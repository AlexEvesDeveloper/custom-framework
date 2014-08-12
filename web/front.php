<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

// setup
$request = Request::createFromGlobals();
$response = new Response();

$routes = include __DIR__.'/../src/routes.php';
// with the routes set up, we can create a Matcher, and let it know about our routes...
// ...then a Matcher can match the URL with our routes
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try{
	// the $matcher->match returns an array. It will always contain at least one element: _route => hello
	// if a route is defined to have additional parameters (hello/{name}), and a URL of hello/Alex is in the Request...
	// ...then the returned array will also contain an element with a key of 'name' and a value of 'Alex'
	// once again, by passing this array to extract(), we convert the keys to variable names, so $name can be used in the template
	extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
	ob_start();
	include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

	// send the buffered PHP output from the script, as the Response content. Clean from the buffer afterwards
	$response->setContent(ob_get_clean());
} catch (Routing\Exception\ResourceNotFoundException $e){
	$response = new Response('Page not found', 404);
} catch (Exception $e){
	$response = new Response('An error occurred', 500);
}

$response->send();