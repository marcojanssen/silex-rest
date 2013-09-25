<?php
namespace MJanssen\Provider;

use MJanssen\Filters\PropertyFilter;
use MJanssen\Service\RequestValidatorService;
use Silex\Application;
use Silex\ServiceProviderInterface;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Construction\DoctrineObjectConstructor;
use JMS\Serializer\Construction\UnserializeObjectConstructor;

use MJanssen\Doctrine\Service\ExtractorService;
use MJanssen\Doctrine\Service\HydratorService;
use MJanssen\Doctrine\Service\PrepareService;
use MJanssen\Doctrine\Service\ResolverService;
use MJanssen\Service\ValidatorService;
use MJanssen\Service\HmacService;

/**
 * Class ServiceProvider
 * @package MJanssen\Provider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['serializer'] = $app->share(function($app) {
            $fallbackConstructer = new UnserializeObjectConstructor();
            $doctrineObjectConstructor = new DoctrineObjectConstructor($app['doctrine'], $fallbackConstructer);
            return SerializerBuilder::create()->setCacheDir($app['serializer.cache.dir'])
                                              ->setDebug($app['debug'])
                                              ->setObjectConstructor($doctrineObjectConstructor)
                                              ->build();
        });

        $app['doctrine.extractor'] = $app->share(function($app) {
            return new ExtractorService($app['serializer'], $app['orm.em']);
        });

        $app['doctrine.hydrator'] = $app->share(function($app) {
            return new HydratorService($app['serializer'], $app['orm.em']);
        });

        $app['doctrine.resolver'] = $app->share(function($app) {
            return new ResolverService($app['orm.em']);
        });

        $app['service.validator'] = $app->share(function($app) {
            return new ValidatorService($app['validator'], $app['request']);
        });

        /**
         * Add the HMAC validation service
         */
        $app['service.hmac'] = $app->share(function($app) {
            return new HmacService($app['validator'], $app['request']);
        });

        $app['service.request.validator'] = $app->share(function($app) {
            return new RequestValidatorService($app['service.validator'], $app['request']);
        });
    }
}