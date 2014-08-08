<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use MJanssen\Provider\ServiceRegisterProvider;
use MJanssen\Provider\RoutingServiceProvider;
use Igorw\Silex\ConfigServiceProvider;

//Set all service providers
$app->register(
    new ConfigServiceProvider(__DIR__."/../app/config/services.php")
);

if(true === $app['debug']) {
    $app->register(
        new ConfigServiceProvider(__DIR__."/../app/config/services_dev.php")
    );
}

//Register all providers
$app->register(
    new ServiceRegisterProvider()
);

//Configure the service providers
$app->register(
    new ConfigServiceProvider(__DIR__."/../app/config/config.php", array('app.path' => getcwd()))
);

if(true === $app['debug']) {
    $app->register(
        new ConfigServiceProvider(__DIR__."/../app/config/config_dev.php", array('app.path' => getcwd()))
    );
}

if(true !== $cli) {
    //Set all available routes
    $app->register(
        new ConfigServiceProvider(__DIR__."/../app/config/routes.php", array('baseUrl' => $app['baseUrl']))
    );

    //Register all routes
    $app->register(
        new RoutingServiceProvider()
    );
}

$app['validator.mapping.class_metadata_factory'] = new Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory(
    new Symfony\Component\Validator\Mapping\Loader\YamlFileLoader(__DIR__.'/../app/config/validation.yml')
);

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));