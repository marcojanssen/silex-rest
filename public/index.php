<?php
require_once __DIR__.'/../vendor/autoload.php';
ini_set('display_errors',1);

use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;


$app = new Silex\Application();
$app['debug'] = true;

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../db/app.db',
    ),
));

$app->register(new DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => __DIR__."/../src/MJ/Doctrine/Proxies",
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type" => "annotation",
                "namespace" => "MJ\Doctrine\Entities",
                "resources_namespace" => "MJ\Doctrine\Entities",
            )
        ),
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