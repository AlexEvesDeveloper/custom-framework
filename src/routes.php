<?php

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;

$routes = new Routing\RouteCollection();

// each route has a name (hello), and a Route instance, which declares a route pattern (/hello/{name})...
// ...and some optional default values for the variables in the pattern (array('name' => 'World'))...
// ...each Route has a _controller element, whose value is a function to call
$routes->add('hello', new Routing\Route('/hello/{name}', array(
	'name' => 'World',
	'_controller' => function ($request){
		return new Response(sprintf("Hello %s", $request->get('name', 'World')));
	}
)));

$routes->add('bye', new Routing\Route('/bye', array(
	'_controller' => function ($request){
		return new Response("Goodbye");
	}
)));

return $routes;