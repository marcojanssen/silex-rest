<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Herrera\Wise\WiseServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
            'mode' => 'dev',
            'parameters' => $app
        )
    )
);

$app['config'] = $app['wise']->load('config.yml');

WiseServiceProvider::registerServices($app);

$validation = function (Request $request, Application $app) {
    $app['service.validator']->validate(
        $request->attributes->get('entity'),
        $request->getContent()
    );

    if($app['service.validator']->hasErrors()) {
        foreach ($app['service.validator']->getErrors() as $error) {
            $errorResponse[] = $error->getPropertyPath().' '.$error->getMessage()."\n";
        }

        return new JsonResponse(array('errors' => $errorResponse));
    }
};

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../app/logs/silex_dev.log',
));

$app->register($p = new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../app/cache/profiler',
));
$app->mount('/_profiler', $p);

require __DIR__.'/../app/routes.php';
$app->run();
