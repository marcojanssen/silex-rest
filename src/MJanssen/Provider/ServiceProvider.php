<?php
namespace MJanssen\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Construction\DoctrineObjectConstructor;
use JMS\Serializer\Construction\UnserializeObjectConstructor;
use MJanssen\Service\ExtractorService;
use MJanssen\Service\HydratorService;
use MJanssen\Service\ResolverService;
use MJanssen\Filters\PropertyFilter;
use MJanssen\Service\RequestValidatorService;
use MJanssen\Service\ValidatorService;

/**
 * Class ServiceProvider
 * @package MJanssen\Provider
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
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

            $createSerializer = SerializerBuilder::create();

            if(isset($app['serializer.cache.path'])) {
                $createSerializer->setCacheDir($app['serializer.cache.path']);
            }

            if(isset($app['debug'])) {
                $createSerializer->setDebug($app['debug']);
            }

            if(isset($app['doctrine'])) {
                $fallbackConstructor       = new UnserializeObjectConstructor();
                $doctrineObjectConstructor = new DoctrineObjectConstructor($app['doctrine'], $fallbackConstructor);
                $createSerializer->setObjectConstructor($doctrineObjectConstructor);
            }

            return $createSerializer->build();
        });

        $app['doctrine.extractor'] = $app->share(function($app) {
            return new ExtractorService($app['serializer']);
        });

        $app['doctrine.hydrator'] = $app->share(function($app) {
            return new HydratorService($app['serializer']);
        });

        $app['doctrine.resolver'] = $app->share(function($app) {
            return new ResolverService($app['orm.em']);
        });

        $app['service.validator'] = $app->share(function($app) {
            return new ValidatorService($app['validator'], $app['request']);
        });

        $app['service.request.validator'] = $app->share(function($app) {
            return new RequestValidatorService($app['service.validator'], $app['request']);
        });
    }
}
