<?php
namespace MJ\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class RestController
 * @package MJ\Controllers
 */
class RestController
{

    public function testHydrateAction(Request $request, Application $app)
    {
        $data = array(
            'name' => 'Wazzup',
            'items' => array(
                array(
                    'id' => null,
                    'name' => 'bla',
                    'email' => 'bla',
                    'phone' => 'bla'
                )
            )
        );

        $item = $app['doctrine.hydrator']->hydrateEntity(
            $data,
            new \MJ\Doctrine\Entity\Categories(),
            true
        );

        $app['orm.em']->persist($item);
        $app['orm.em']->flush();

        return new Response('aight');

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param null $id
     * @return JsonResponse
     */
    public function getAction(Request $request, Application $app, $id = null)
    {

        if(null !== $id) {
            return new JsonResponse(
                $app['doctrine.extractor']->extractEntity(
                    $app['doctrine.repository']->findEntityById(
                        $this->getEntityName($request, $app),
                        $id
                    ),
                    true
                )

            );
        }

        return new JsonResponse(
            $app['doctrine.extractor']->extractEntities(
                $app['doctrine.repository']->findEntitiesByCriteria(
                    $this->getEntityName($request, $app),
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
     * @param Application $app
     * @return JsonResponse
     */
    public function deleteAction(Request $request, Application $app, $id)
    {
        $app['orm.em']->remove(
            $app['doctrine.repository']->findEntityById(
                $this->getEntityName($request, $app),
                $id
            )
        );
        $app['orm.em']->flush();

        return new JsonResponse(array('item removed'));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return JsonResponse
     */
    public function postAction(Request $request, Application $app)
    {
        $item = $app['doctrine.hydrator']->hydrateEntity(
            $request->getContent(),
            $this->getEntity($request, $app)
        );

        $app['orm.em']->persist($item);
        $app['orm.em']->flush();

        return new JsonResponse(array('item posted'));
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
                $this->getEntityName($request, $app),
                $id
            )
        );

        $app['orm.em']->persist($item);
        $app['orm.em']->flush();

        return new JsonResponse(array('item updated'));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    protected function getEntity(Request $request, Application $app)
    {
        $entityName = $this->getEntityName($request, $app);
        return new $entityName;
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    protected function getEntityName(Request $request, Application $app)
    {
        return $app['doctrine.resolver']->resolveEntity(
            $request->attributes->get('section')
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
