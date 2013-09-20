<?php
namespace MJanssen\Controllers;

use MJanssen\Filters\FilterLoader;
use Silex\Application;
use Spray\PersistenceBundle\Repository\FilterableRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class RestController
 * @package MJanssen\Controllers
 */
class RestController
{

    /**
     * @param Request $request
     * @param Application $app
     * @param null $id
     * @return JsonResponse
     */
    public function getAction(Request $request, Application $app, $id = null)
    {
        $repository = $app['orm.em']->getRepository($this->getEntityName($request,$app));

        if(null !== $id) {
            return new JsonResponse(
                $app['doctrine.extractor']->extractEntity(
                    $repository->findOneBy(
                        array('id' => $id)
                    ),
                    true
                )
            );
        }

        return new JsonResponse(
            $app['doctrine.extractor']->extractEntities(
                $this->setFiltersForRepositoryByRequest(
                    $repository,
                    $request
                )
            )
        );
    }


    /**
     * @param Request $request
     * @param Application $app
     * @param $id
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
     * @internal param $id
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
     * @param $id
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
        return $app['doctrine.resolver']->getEntityClassName(
            $request->attributes->get('namespace'),
            $request->attributes->get('entity')
        );
    }

    /**
     * @param $repository
     * @param Request $request
     * @return FilterableRepositoryInterface
     */
    public function setFiltersForRepositoryByRequest($repository, Request $request)
    {
        $filterLoader = new FilterLoader();

        if($repository instanceof FilterableRepositoryInterface) {

            foreach ($filterLoader->getPlugins() as $pluginName => $pluginNamespace)
            {
                $filterParams = $request->get($pluginName);

                if (null !== $filterParams && is_array($filterParams)){

                    $repository->filter(new $pluginNamespace($filterParams));
                }
            }
        }

        return $repository;
    }
}
