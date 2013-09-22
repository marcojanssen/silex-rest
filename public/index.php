<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Marcojanssen\Provider\ServiceRegisterProvider;
use Igorw\Silex\ConfigServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

chdir(dirname(__DIR__));

$loader = require_once 'vendor/autoload.php';

$app = new Application();

//Set all service providers
$app->register(
    new ConfigServiceProvider(__DIR__."/../app/config/services.yml")
);

//Register all providers
$app->register(
    new ServiceRegisterProvider()
);

//Configure the service providers
$app->register(
    new ConfigServiceProvider(__DIR__."/../app/config/config.yml", array('app_path' => getcwd()))
);

$sluggableListener = new Gedmo\Sluggable\SluggableListener;
$app['db.event_manager']->addEventSubscriber($sluggableListener);

$softdeletableListener = new Gedmo\SoftDeleteable\SoftDeleteableListener();
$app['db.event_manager']->addEventSubscriber($softdeletableListener);

$conn = $app['orm.em']->getConnection();
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'string');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

//$app['controllers']->requireHttps();

$app->mount($app['baseUrl'].'/{namespace}', new MJanssen\Provider\RestControllerProvider());

$app->error(function (\Exception $e, $code) use ($app) {
    if(404 === $code) {
        return;
    }
    return new JsonResponse(array('application error'));
});

$app->run();