<?php

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;


class Framework implements HttpKernelInterface
{
	protected $matcher;
	protected $resolver;
	protected $dispatcher;

	public function __construct(UrlMatcherInterface $matcher, ControllerResolverInterface $resolver, EventDispatcher $dispatcher)
	{
		$this->matcher = $matcher;
		$this->resolver = $resolver;
		$this->dispatcher = $dispatcher;
	}

	public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
	{
		try{
			// instead of extracting the array that is returned from $matcher->match(...), we will set it as an 'bolt-on' to the Request
			// we could retrieve a value with $request->attributes->get('_route');
			$request->attributes->add($this->matcher->match($request->getPathInfo()));
		
			// $controller becomes array('className', 'classMethod'), for call_user_func_array to accept
			$controller = $this->resolver->getController($request);
			
			// call_user_func_array requires an array of arguments...
			// based on the relevant controller, we get all arguments on the URL, and also include the Request
			$arguments = $this->resolver->getArguments($request, $controller);
		
			// we now simply call the class method, and pass to it the URL arguments
			$response = call_user_func_array($controller, $arguments);
		} catch (ResourceNotFoundException $e){
			$response = new Response('Page not found', 404);
		} catch (Exception $e){
			$response = new Response($e->getMessage(), 500);
		}

		// dispatch a response event prior to returning the response
		$this->dispatcher->dispatch('response', new ResponseEvent($response, $request));		

		return $response;
	}
}
