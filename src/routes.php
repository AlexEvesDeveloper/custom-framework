<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

// each route has a name (hello), and a Route instance, which declares a route pattern (/hello/{name})...
// ...and some optional default values for the variables in the pattern (array('name' => 'World'))
$routes->add('hello', new Routing\Route('/hello/{name}', array('name' => 'World')));
$routes->add('bye', new Routing\Route('/bye'));

return $routes;