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
			return new Response('This is a leap year');
		}

		return new Response('This is not a leap year');		
	}
}