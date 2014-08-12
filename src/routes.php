<?php

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
	public function indexAction($year)
	{
		if(is_leap_year($year)){
			return new Response('This is a leap year');
		}

		return new Response('This is not a leap year');		
	}
}


function is_leap_year($year = null){
	if($year === null){
		$year = date('Y');
	}

	return $year % 400 == 0 || ($year % 4 == 0 && $year % 100 != 0);
}

$routes = new Routing\RouteCollection();

// each route has a name (hello), and a Route instance, which declares a route pattern (/is-leap-year/{year})...
// ...and some optional default values for the variables in the pattern (array('year' => null))...
// ...each Route has a _controller element, whose value is a function to call
$routes->add('leap_year', new Routing\Route('/is-leap-year/{year}', array(
	'year' => null, 
	'_controller' => 'LeapYearController::indexAction',
)));

return $routes;