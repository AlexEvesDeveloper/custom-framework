<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

// setup
$request = Request::createFromGlobals();
$response = new Response();
$resolver = new HttpKernel\Controller\ControllerResolver();
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

	// $controller becomes array('className', 'classMethod'), for call_user_func_array to accept
	$controller = $resolver->getController($request);
	
	// call_user_func_array requires an array of arguments...
	// based on the relevant controller, we get all arguments on the URL, and also include the Request
	$arguments = $resolver->getArguments($request, $controller);

	// we now simply call the class method, and pass to it the URL arguments
	$response = call_user_func_array($controller, $arguments);
} catch (Routing\Exception\ResourceNotFoundException $e){
	$response = new Response('Page not found', 404);
} catch (Exception $e){
	$response = new Response($e->getMessage(), 500);
}

$response->send();