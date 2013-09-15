<?php
namespace MJ\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemsController
{
    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function getAction(Request $request, Application $app)
    {
        return new JsonResponse(
            $app['doctrine.extractor']->extractEntities(
                $app['doctrine.repository']->findEntitiesByCriteria(
                    'MJ\Doctrine\Entities\Item',
                    array(),
                    array('id' => 'ASC'),
                    (int) $this->getPaginatorParameter($request, 'limit', 25),
                    (int) $this->getPaginatorParameter($request, 'start', 0)
                )
            )
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