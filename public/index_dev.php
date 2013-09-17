<?php
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../vendor/autoload.php';

error_reporting(-1);
Debug::enable();

$app = require __DIR__.'/../app/app.php';
require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/routes.php';
$app->run();
