<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use MJanssen\Provider\ServiceRegisterProvider;
use MJanssen\Provider\RoutingServiceProvider;
use Igorw\Silex\ConfigServiceProvider;

//Set all service providers
$app->register(
    new ConfigServiceProvider(__DIR__."/../app/config/services.yml")
);

if(true === $app['debug']) {
    $app->register(
        new ConfigServiceProvider(__DIR__."/../app/config/services_dev.yml")
    );
}

//Register all providers
$app->register(
    new ServiceRegisterProvider()
);

//Configure the service providers
$app->register(
    new ConfigServiceProvider(__DIR__."/../app/config/config.php", array('app_path' => getcwd()))
);

if(true === $app['debug']) {
    $app->register(
        new ConfigServiceProvider(__DIR__."/../app/config/config_dev.php", array('app_path' => getcwd()))
    );
}

if(true !== $cli) {
    //Set all available routes
    $app->register(
        new ConfigServiceProvider(__DIR__."/../app/config/routes.yml", array('baseUrl' => $app['baseUrl']))
    );

    //Register all routes
    $app->register(
        new RoutingServiceProvider()
    );
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));