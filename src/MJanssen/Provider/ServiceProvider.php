<?php
namespace MJanssen\Provider;

use MJanssen\Filters\PropertyFilter;
use Silex\Application;
use Silex\ServiceProviderInterface;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
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
        $app['hydrator'] = $app->share(function($app) {
            return new DoctrineHydrator($app['orm.em']);
        });

        $app['doctrine.extractor'] = $app->share(function($app) {
            return new ExtractorService($app['hydrator'], $app['orm.em']);
        });

        $app['doctrine.hydrator'] = $app->share(function($app) {
            return new HydratorService($app['hydrator'], $app['orm.em']);
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
    }
}