<?php
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

$app->get('/', 'MJanssen\Controllers\IndexController::getAction');

$app->get('/test', 'MJanssen\Controllers\RestController::testHydrateAction');

$app->get('/{namespace}/{entity}', 'MJanssen\Controllers\RestController::getAction');
$app->post('/{namespace}/{entity}', 'MJanssen\Controllers\RestController::postAction')
    ->before($validation);
$app->get('/{namespace}/{entity}/{id}', 'MJanssen\Controllers\RestController::getAction');
$app->put('/{namespace}/{entity}/{id}', 'MJanssen\Controllers\RestController::putAction')
    ->before($validation);
$app->delete('/{namespace}/{entity}/{id}', 'MJanssen\Controllers\RestController::deleteAction');


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
