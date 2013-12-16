<?php
use Silex\Application;
use Symfony\Component\Debug\Debug;

chdir(dirname(__DIR__));

$loader = require_once 'vendor/autoload.php';

error_reporting(-1);
Debug::enable();

$app = new Application();
$app['debug'] = true;
$cli = false;

require_once('app/bootstrap.php');

$app->run();
