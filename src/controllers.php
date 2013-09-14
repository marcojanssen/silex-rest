<?php
$app->get('/', 'MJ\Controllers\IndexController::getAction');

$app->get('/items', 'MJ\Controllers\ItemsController::getAction');
$app->post('/items', 'MJ\Controllers\ItemsController::postAction');

$app->get('/items/{id}', 'MJ\Controllers\ItemController::getAction');
$app->put('/items/{id}', 'MJ\Controllers\ItemController::putAction');
$app->delete('/items/{id}', 'MJ\Controllers\ItemController::deleteAction');

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
