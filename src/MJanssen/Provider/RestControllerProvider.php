<?php
namespace MJanssen\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MJanssen\Traits\ErrorTrait;

/**
 * Class RestControllerProvider
 * @package MJanssen\Provider
 */
class RestControllerProvider implements ControllerProviderInterface
{
    use ErrorTrait;

    /**
     * @param Application $app
     * @return \Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $validation = $this->getValidationMiddleware($app);
        $hmacValidation = $this->getHmacMiddleware($app);

        $controllers->get('/{entity}', 'MJanssen\Controllers\RestController::getAction');
        $controllers->post('/{entity}', 'MJanssen\Controllers\RestController::postAction')
                    ->before($validation)
                    ->before($hmacValidation);
        $controllers->get('/{entity}/{id}', 'MJanssen\Controllers\RestController::getAction');
        $controllers->put('/{entity}/{id}', 'MJanssen\Controllers\RestController::putAction')
                    ->before($validation)
                    ->before($hmacValidation);
        $controllers->delete('/{entity}/{id}', 'MJanssen\Controllers\RestController::deleteAction')
                    ->before($hmacValidation);

        return $controllers;
    }

    /**
     * Returns validation middleware for post & put requests
     * @return callable
     */
    private function getValidationMiddleware(Application $app)
    {
        $validation = function (Request $request, Application $app) {
            $app['service.validator']->validate(
                $app['request']->attributes->get('entity'),
                $app['request']->getContent()
            );

            if($app['service.validator']->hasErrors()) {
                $this->setErrors($app['service.validator']->getErrors());
                return new JsonResponse(array('errors' => $this->getErrorResponse()));
            }
        };

        return $validation;
    }

    /**
     * Returns hmac middleware for post, put and delete requests
     * @return callable
     */
    private function getHmacMiddleware(Application $app)
    {
        $hmacValidation = function (Request $request, Application $app) {
            $app['service.hmac']->validate(
                $app['request']->attributes->get('entity'),
                $app['request']->getContent()
            );

            if($app['service.hmac']->hasErrors()) {
                $this->setErrors($app['service.hmac']->getErrors());
                return new JsonResponse(array('errors' => $this->getErrorResponse()));
            }
        };

        return $hmacValidation;
    }
}