<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Herrera\Wise\WiseServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

chdir(dirname(__DIR__));

$loader = require_once 'vendor/autoload.php';

$app = new Application();
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

//$app['controllers']->requireHttps();

$app->mount($app['config']['base.url'].'/{namespace}', new MJanssen\Provider\RestControllerProvider());

$app->error(function (\Exception $e, $code) use ($app) {
    if(404 === $code) {
        return;
    }
    return new JsonResponse(array('application error'));
});

$app->run();