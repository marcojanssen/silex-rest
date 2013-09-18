<?php
namespace MJanssen\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RestControllerProvider
 * @package MJanssen\Provider
 */
class RestControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return \Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        //$validation = $this->getValidationMiddleware($app);

        $controllers->get('/{entity}', 'MJanssen\Controllers\RestController::getAction');
        $controllers->post('/{entity}', 'MJanssen\Controllers\RestController::postAction');
        $controllers->get('/{entity}/{id}', 'MJanssen\Controllers\RestController::getAction');
        $controllers->put('/{entity}/{id}', 'MJanssen\Controllers\RestController::putAction');
        $controllers->delete('/{entity}/{id}', 'MJanssen\Controllers\RestController::deleteAction');

        return $controllers;
    }

    /**
     * Returns validation middleware for post & put requests
     * @return callable
     */
    private function getValidationMiddleware(Application $app)
    {
        $request = $app['request'];
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

        return $validation;
    }
}