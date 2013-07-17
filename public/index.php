<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../db/app.db',
    ),
));

$app->get('/', function () {

    $items['items'] = array();
    for($i=0; $i<25; $i++) {
        $items['items'][] = array(
            'id'   => $i,
            'name' => 'w00t'
        );
    }

    return json_encode($items);
});

$app->run();