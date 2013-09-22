<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Herrera\Wise\WiseServiceProvider;
use Silex\Application;
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

WiseServiceProvider::registerServices($app);

$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../app/config/config.yml"));
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../app/config/config_dev.yml"));

$sluggableListener = new Gedmo\Sluggable\SluggableListener;
$app['db.event_manager']->addEventSubscriber($sluggableListener);

$softdeletableListener = new Gedmo\SoftDeleteable\SoftDeleteableListener();
$app['db.event_manager']->addEventSubscriber($softdeletableListener);

$conn = $app['orm.em']->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'string');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app->mount($app['baseUrl'].'/{namespace}', new MJanssen\Provider\RestControllerProvider());

$app->run();
