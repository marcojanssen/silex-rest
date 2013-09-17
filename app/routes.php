<?php
$app->get('/', 'MJ\Controllers\IndexController::getAction');

$app->get('/test', 'MJ\Controllers\RestController::testHydrateAction');

$app->get('/{namespace}/{entity}', 'MJ\Controllers\RestController::getAction');
$app->post('/{namespace}/{entity}', 'MJ\Controllers\RestController::postAction')
    ->before($validation);
$app->get('/{namespace}/{entity}/{id}', 'MJ\Controllers\RestController::getAction');
$app->put('/{namespace}/{entity}/{id}', 'MJ\Controllers\RestController::putAction')
    ->before($validation);
$app->delete('/{namespace}/{entity}/{id}', 'MJ\Controllers\RestController::deleteAction');


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
