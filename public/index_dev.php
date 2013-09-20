<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Herrera\Wise\WiseServiceProvider;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
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

$conn = $app['orm.em']->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'string');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => $app['config']['monolog']['logfile'],
));

$app->register(new Whoops\Provider\Silex\WhoopsServiceProvider());

$app->mount($app['config']['base.url'].'/{namespace}', new MJanssen\Provider\RestControllerProvider());

$app->run();
