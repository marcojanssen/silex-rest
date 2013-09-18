<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Herrera\Wise\WiseServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app['app_path'] = __DIR__.'/..';
$app->register(
    new WiseServiceProvider(),
    array(
        'wise.path' => __DIR__.'/../app/config',
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

//$app['controllers']->requireHttps();

require __DIR__.'/../app/routes.php';
$app->run();