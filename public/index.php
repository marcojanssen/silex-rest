<?php
require_once __DIR__.'/../vendor/autoload.php';
ini_set('display_errors',1);

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../db/app.db',
    ),
));

$app->get('/', function (Request $request) use ($app) {

    $limit = $request->query->get('limit');
    $start = $request->query->get('start');

    if(empty($limit)) {
        $limit = 25;
    }

    if(empty($start)) {
        $start = 0;
    }

    $sql = "SELECT * FROM items LIMIT ?,?";
    $items = $app['db']->fetchAll($sql, array((int) $start, (int) $limit));

    return json_encode($items);
});

$app->run();