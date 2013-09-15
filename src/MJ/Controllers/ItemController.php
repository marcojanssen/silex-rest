<?php
namespace MJ\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemController
{
    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function getAction(Request $request, Application $app, $id)
    {
        return new JsonResponse(
            $app['doctrine.extractor']->extractEntity(
                $app['doctrine.repository']->findEntityById('MJ\Doctrine\Entities\Item', $id)
            )
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     */
    public function deleteAction(Request $request, Application $app, $id)
    {
        $app['orm.em']->remove(
            $app['doctrine.repository']->findEntityById('MJ\Doctrine\Entities\Item', $id)
        );
        $app['orm.em']->flush();

        return new JsonResponse(array('item deleted'));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     */
    public function putAction(Request $request, Application $app, $id)
    {
        $item = $app['doctrine.hydrator']->hydrateEntity(
            $request->getContent(),
            $app['doctrine.repository']->findEntityById(
                'MJ\Doctrine\Entities\Item',
                $id
            )
        );

        $app['orm.em']->persist($item);
        $app['orm.em']->flush();

        return new JsonResponse(array('item updated'));
    }
}