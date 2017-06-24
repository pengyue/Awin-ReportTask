<?php

use Awin\ReportTask\App\AppKernel;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../var/bootstrap.php.cache';

$environment = (getenv('APP_ENV') ?: 'prod');
$debug = (strtolower(getenv('APP_DEBUG')) !== '0');

$kernel = new AppKernel($environment, $debug);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
