<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Simplex\Framework;

// setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/routes.php';
// with the routes set up, we can create a Matcher, and let it know about our routes...
// ...then a Matcher can match the URL with our routes
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$resolver = new HttpKernel\Controller\ControllerResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new Simplex\GoogleListener());
$dispatcher->addSubscriber(new Simplex\ContentLengthListener());

$framework = new Framework($matcher, $resolver, $dispatcher);
$response = $framework->handle($request);

$response->send();
