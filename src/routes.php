<?php

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

// each route has a name (hello), and a Route instance, which declares a route pattern (/is-leap-year/{year})...
// ...and some optional default values for the variables in the pattern (array('year' => null))...
// ...each Route has a _controller element, whose value is a function to call
$routes->add('leap_year', new Routing\Route('/is-leap-year/{year}', array(
	'year' => null, 
	'_controller' => 'Calendar\\Controller\\LeapYearController::indexAction',
)));

return $routes;