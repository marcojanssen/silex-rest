<?php
namespace MJ\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemsController
{
    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function getAction(Request $request, Application $app)
    {
        return new Response(
            $app['serializer']->serialize(
                $this->findItems($request, $app),
                'json'
            )
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    private function findItems(Request $request, Application $app)
    {
        return $app['orm.em']->getRepository('MJ\Doctrine\Entities\Item')->findBy(
            array(),
            array('id' => 'ASC'),
            (int) $this->getPaginatorParameter($request, 'limit', 25),
            (int) $this->getPaginatorParameter($request, 'start', 0)
        );
    }

    /**
     * @param Request $request
     * @param $type
     * @param int $defaultValue
     * @return int|mixed
     */
    private function getPaginatorParameter(Request $request, $type, $defaultValue = 25)
    {
        $parameter = $request->query->get($type);

        if(empty($parameter)) {
            $parameter = $defaultValue;
        }

        return $parameter;
    }



}