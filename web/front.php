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
	// instead of extracting the array that is returned from $matcher->match(...), we will set it as an 'bolt-on' to the Request
	// we could retrieve a value with $request->attributes->get('_route');
	$request->attributes->add($matcher->match($request->getPathInfo()));
	
	// after adding the value to each of our routes, _controller has a callable function as it's value, which returns a Response...
	// ...so we can pass its value to call_user_func, and pass in the $request to that function too
	$response = call_user_func($request->attributes->get('_controller'), $request);
} catch (Routing\Exception\ResourceNotFoundException $e){
	$response = new Response('Page not found', 404);
} catch (Exception $e){
	$response = new Response('An error occurred', 500);
}

$response->send();