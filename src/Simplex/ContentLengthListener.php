<?php

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentLengthListener implements EventSubscriberInterface
{
	public function onResponse(ResponseEvent $event)
	{
		$response = $event->getResponse();
		$headers = $response->headers;
		if(!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')){
			$headers->set('Content-Length', strlen($response->getContent()));
		}
	}

        // interface method
        public static function getSubscribedEvents()
        {
        		// non negative, to execute last, to ensure the content length is correct
                return array('response' => array('onResponse', -255));
        }
}
