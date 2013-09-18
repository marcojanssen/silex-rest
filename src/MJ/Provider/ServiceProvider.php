<?php
namespace MJ\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use MJ\Doctrine\Service\ExtractorService;
use MJ\Doctrine\Service\HydratorService;
use MJ\Doctrine\Service\PrepareService;
use MJ\Doctrine\Service\RepositoryService;
use MJ\Doctrine\Service\ResolverService;
use MJ\Service\ValidatorService;

/**
 * Class ServiceProvider
 * @package MJ\Provider
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
    }
}