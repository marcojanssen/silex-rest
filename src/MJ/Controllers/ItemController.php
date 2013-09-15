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
                $this->findItem($app, $id)
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
        $app['orm.em']->remove($this->findItem($app, $id));
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
        $item = $app['hydrator']->hydrate(
            json_decode($request->getContent(), true),
            $this->findItem($app, $id)
        );

        $app['orm.em']->persist($item);
        $app['orm.em']->flush();

        return new JsonResponse(array('item updated'));
    }

    /**
     * Find item in database
     *
     * @param Application $app
     * @param $id
     * @return mixed
     */
    private function findItem(Application $app, $id)
    {
        return $app['orm.em']->getRepository('MJ\Doctrine\Entities\Item')->findOneBy(
            array('id' => (int) $id)
        );

        if (null === $item) {
            $app->abort(404, "Item not found");
        }
    }

}