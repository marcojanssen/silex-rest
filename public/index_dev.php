<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Herrera\Wise\WiseServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Symfony\Component\Debug\Debug;

chdir(dirname(__DIR__));

$loader = require_once 'vendor/autoload.php';

error_reporting(-1);
Debug::enable();

$app = new Application();
$app['debug'] = true;
$app['app_path'] = getcwd();

$app->register(
    new WiseServiceProvider(),
    array(
        'wise.path' => 'app/config',
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

$sluggableListener = new Gedmo\Sluggable\SluggableListener;
$app['db.event_manager']->addEventSubscriber($sluggableListener);

$softdeletableListener = new Gedmo\SoftDeleteable\SoftDeleteableListener();
$app['db.event_manager']->addEventSubscriber($softdeletableListener);

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => $app['config']['monolog']['logfile'],
));

$app->register($p = new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => $app['config']['profiler']['cache_dir'],
));

$app->register(new Whoops\Provider\Silex\WhoopsServiceProvider());

$app->mount($app['config']['base.url'].'/_profiler', $p);
$app->mount($app['config']['base.url'].'/{namespace}', new MJanssen\Provider\RestControllerProvider());

$app->error(function (\Exception $e, $code) use ($app) {
    return;
});

$app->run();
