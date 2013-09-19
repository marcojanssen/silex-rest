<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Herrera\Wise\WiseServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../vendor/autoload.php';

error_reporting(-1);
Debug::enable();

$app = new Application();
$app['debug'] = true;
$app['app_path'] = __DIR__.'/..';

$app->register(
    new WiseServiceProvider(),
    array(
        'wise.path' => __DIR__.'/../app/config',
        'cache.path' => __DIR__.'/../app/cache',
        'wise.options' => array(
            'type' => 'yml',
            'config' => array (
                'services' => 'services'
            ),
            'mode' => 'prod',
            'parameters' => $app
        )
    )
);

$app['config'] = $app['wise']->load('config.yml');

WiseServiceProvider::registerServices($app);

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../app/logs/silex_dev.log',
));

$app->register($p = new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../app/cache/profiler',
));

$app->register(new Whoops\Provider\Silex\WhoopsServiceProvider());

$app->mount($app['config']['base.url'].'/_profiler', $p);
$app->mount($app['config']['base.url'].'/{namespace}', new MJanssen\Provider\RestControllerProvider());

$app->error(function (\Exception $e, $code) use ($app) {
    return;
});

$app->run();
