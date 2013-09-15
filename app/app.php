<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use MJ\Service\DoctrineExtractorService;
use MJ\Service\DoctrineHydratorService;
use MJ\Service\DoctrineRepositoryService;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../app/templates'),
    'twig.options' => array('cache' => __DIR__.'/../app/cache/twig'),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

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
                "namespace" => "MJ\\Doctrine\\Entities",
                "path" => __DIR__."/../src/MJ/Doctrine/Entities",
                "use_simple_annotation_reader" => false
            )
        ),
    ),
    "orm.default_cache" => "array"
));

$app['hydrator'] = $app->share(function($app) {
    return new DoctrineHydrator($app['orm.em']);
});

$app['doctrine.extractor'] = $app->share(function($app) {
    return new DoctrineExtractorService($app['hydrator']);
});

$app['doctrine.hydrator'] = $app->share(function($app) {
    return new DoctrineHydratorService($app['hydrator']);
});

$app['doctrine.repository'] = $app->share(function($app) {
    return new DoctrineRepositoryService($app['orm.em']);
});

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $app;
