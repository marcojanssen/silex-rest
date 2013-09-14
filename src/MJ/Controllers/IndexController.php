<?php
namespace MJ\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;

class IndexController
{

    public function indexAction(Request $request, Application $app)
    {
        $limit = $request->query->get('limit');
        $start = $request->query->get('start');

        if(empty($limit)) {
            $limit = 25;
        }

        if(empty($start)) {
            $start = 0;
        }

        $serializer = SerializerBuilder::create()->build();
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
    }

}