<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;

class LeapYearController
{
	public function indexAction($year)
	{
		$leapYear = new LeapYear();
		if($leapYear->isLeapYear($year)){
			$response = new Response('This is a leap year'.rand());
		}else{
			$response = new Response('This is not a leap year'.rand());	
		}

		// uncomment to cache the http for 10 seconds. Proved by the rand() number displayed
		//$response->setTtl(10);

		return $response;	
	}
}