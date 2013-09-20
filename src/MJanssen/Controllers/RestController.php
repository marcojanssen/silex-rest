<?php
namespace MJanssen\Controllers;

use Doctrine\Common\Persistence\ObjectRepository;
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
        $repository = $this->getEntityRepository($request, $app);

        return new JsonResponse(
            $app['doctrine.extractor']->extractEntity(
                $repository->findOneBy(
                    array('id' => $id)
                ),
                true
            )
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param null $id
     * @return JsonResponse
     */
    public function getCollectionAction(Request $request, Application $app)
    {
        $repository = $this->getEntityRepository($request, $app);

        if($this->isFilterableRepository($repository)) {
            $repository = $this->setFiltersForRepositoryByRequest(
                $repository,
                $request
            );
        }

        return new JsonResponse(
            $app['doctrine.extractor']->extractEntities(
                $repository->findBy(
                    array(),
                    array('id' => 'ASC')
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
        $repository = $this->getEntityRepository($request, $app);

        $app['orm.em']->remove(
            $repository->findOneBy(
                array('id' => $id)
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
        $repository = $this->getEntityRepository($request, $app);

        $item = $app['doctrine.hydrator']->hydrateEntity(
            $request->getContent(),
            $repository->findOneBy(
                array('id' => $id)
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
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    protected function getEntityRepository(Request $request, Application $app)
    {
        return $app['orm.em']->getRepository(
            $this->getEntityName($request,$app)
        );
    }

    /**
     * @param ObjectRepository $repository
     * @return bool
     */
    protected function isFilterableRepository(ObjectRepository $repository)
    {
        if($repository instanceof FilterableRepositoryInterface) {
            return true;
        }

        return false;
    }

    /**
     * @param $repository
     * @param Request $request
     * @return FilterableRepositoryInterface
     */
    protected function setFiltersForRepositoryByRequest(FilterableRepositoryInterface $repository, Request $request)
    {
        $filterLoader = new FilterLoader();

        foreach ($filterLoader->getPlugins() as $pluginName => $pluginNamespace)
        {
            $filterParams = $request->get($pluginName);

            if (null !== $filterParams && is_array($filterParams)){

                $repository->filter(new $pluginNamespace($filterParams));
            }
        }

        return $repository;
    }
}
