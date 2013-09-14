<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;

$app->get('/', function (Request $request) use ($app) {
    $limit = $request->query->get('limit');
    $start = $request->query->get('start');

    if(empty($limit)) {
        $limit = 25;
    }

    if(empty($start)) {
        $start = 0;
    }

    $serializer = SerializerBuilder::create()->setCacheDir(__DIR__.'/../app/cache/serializer')->build();
    $items = $serializer->serialize(
        $app['orm.em']->getRepository('MJ\Doctrine\Entities\Item')->findBy(
            array(),
            array('id' => 'ASC'),
            (int) $limit,
            (int) $start
        ),
        'json'
    );

    return new Response($items);
});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
