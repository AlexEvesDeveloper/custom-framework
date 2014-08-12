<?php

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface
{
	public function onResponse(ResponseEvent $event)
	{
		$response = $event->getResponse();

		if($response->isRedirection()
		   || ($response->headers->has('Content-Type') && strpos($response->headers->get('Content-Type'), 'html') === false)
		   || ($event->getRequest()->getRequestFormat() !== 'html')
		  ){
			return;
		}

		// response is not a redirect, request content type is html, and requested format is html, so continue with this event
		$response->setContent($response->getContent().'GA CODE');
	}

	// interface method
	public static function getSubscribedEvents()
	{
		return array('response' => 'onResponse');
	}
}
