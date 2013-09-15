<?php
$app->get('/', 'MJ\Controllers\IndexController::getAction');

$app->get('/test', 'MJ\Controllers\RestController::testHydrateAction');

$app->get('/{section}', 'MJ\Controllers\RestController::getAction');
$app->post('/{section}', 'MJ\Controllers\RestController::postAction');
$app->get('/{section}/{id}', 'MJ\Controllers\RestController::getAction');
$app->put('/{section}/{id}', 'MJ\Controllers\RestController::putAction');
$app->delete('/{section}/{id}', 'MJ\Controllers\RestController::deleteAction');


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
