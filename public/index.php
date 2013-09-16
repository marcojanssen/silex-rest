<?php
$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../app/app.php';

$app['controllers']->requireHttps();

require __DIR__.'/../app/config/prod.php';
require __DIR__.'/../app/routes.php';
$app->run();