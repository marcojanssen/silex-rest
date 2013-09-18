<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use MJ\Doctrine\Service\ExtractorService;
use MJ\Doctrine\Service\HydratorService;
use MJ\Doctrine\Service\PrepareService;
use MJ\Doctrine\Service\RepositoryService;
use MJ\Doctrine\Service\ResolverService;
use MJ\Service\ValidatorService;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Herrera\Wise\WiseServiceProvider;

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

$app['hydrator'] = $app->share(function($app) {
    return new DoctrineHydrator($app['orm.em']);
});

$app['doctrine.extractor'] = $app->share(function($app) {
    return new ExtractorService($app['hydrator'], $app['orm.em']);
});

$app['doctrine.hydrator'] = $app->share(function($app) {
    return new HydratorService($app['hydrator'], $app['orm.em']);
});

$app['doctrine.repository'] = $app->share(function($app) {
    return new RepositoryService($app['orm.em']);
});

$app['doctrine.resolver'] = $app->share(function($app) {
    return new ResolverService($app['orm.em']);
});

$app['doctrine.prepare'] = $app->share(function($app) {
    return new PrepareService($app['hydrator'], $app['orm.em']);
});

$app['service.validator'] = $app->share(function($app) {
    return new ValidatorService($app['validator'], $app['request']);
});

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

return $app;
