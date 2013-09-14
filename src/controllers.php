<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function (Request $request) use ($app) {
    $limit = $request->query->get('limit');
    $start = $request->query->get('start');

    if(empty($limit)) {
        $limit = 25;
    }

    if(empty($start)) {
        $start = 0;
    }

    $sql = "SELECT * FROM items LIMIT ?,?";
    $items = $app['db']->fetchAll($sql, array((int) $start, (int) $limit));

    return new JsonResponse($items);
})
->bind('homepage')
;

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
