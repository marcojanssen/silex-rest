<?php
namespace MJanssen\Service;

use MJanssen\Filters\FilterLoader;
use Spray\PersistenceBundle\Repository\FilterableRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestEntityService
{
    protected $request;
    protected $app;

    /**
     * @param Request $request
     * @param Application $app
     */
    public function __construct(Request $request, Application $app)
    {
        $this->request = $request;
        $this->app = $app;
    }

    /**
     * @param $identifier
     * @return JsonResponse
     */
    public function getAction($identifier)
    {
        $entity = $this->getEntityFromRepository($identifier);
        $this->isValidEntity($entity, $identifier);

        return $this->app['doctrine.extractor']->extractEntity(
            $entity,
            'detail'
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param null $id
     * @return JsonResponse
     */
    public function getCollectionAction()
    {
        $repository = $this->getEntityRepository();

        if($this->isFilterableRepository($repository)) {
            $repository = $this->setFiltersForRepositoryByRequest(
                $repository,
                $this->request
            );
        }

        return $this->app['doctrine.extractor']->extractEntities(
            $repository->findBy(
                array(),
                array('id' => 'ASC')
            ),
            'list'
        );
    }

    /**
     * @param $identifier
     * @return JsonResponse
     */
    public function deleteAction($identifier)
    {
        $entity = $this->getEntityFromRepository($identifier);
        $this->isValidEntity($entity, $identifier);

        $this->app['orm.em']->remove($entity);
        $this->app['orm.em']->flush();

        return array('item removed');
    }

    /**
     * @return JsonResponse
     */
    public function postAction()
    {
        $response = $this->app['service.request.validator']->validateRequest();
        if(null !== $response) {
            return $response;
        }

        $item = $this->app['doctrine.hydrator']->hydrateEntity(
            $this->request->getContent(),
            $this->getEntityName()
        );

        $this->app['orm.em']->persist($item);
        $this->app['orm.em']->flush();

        return array('item posted');
    }

    public function putAction($identifier)
    {
        $response = $this->app['service.request.validator']->validateRequest();
        if(null !== $response) {
            return $response;
        }

        $entity = $this->getEntityFromRepository($identifier);
        $this->isValidEntity($entity, $identifier);

        $item = $this->app['doctrine.hydrator']->hydrateEntity(
            $this->request->getContent(),
            $this->getEntityName()
        );

        $this->app['orm.em']->merge($item);
        $this->app['orm.em']->flush();

        return array('item updated');
    }


    /**
     * @param $id
     * @param string $field
     * @return mixed
     */
    public function getEntityFromRepository($id, $field = 'id')
    {
        $repository = $this->getEntityRepository();

        $entity = $repository->findOneBy(
            array($field => $id)
        );

        return $entity;
    }

    /**
     * @param $entity
     * @param $app
     * @param $id
     */
    public function isValidEntity($entity, $id)
    {
        if(null === $entity) {
            $this->app->abort(404, "$id does not exist.");
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->app['doctrine.resolver']->getEntityClassName(
            $this->request->attributes->get('namespace'),
            $this->request->attributes->get('entity')
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function getEntityRepository()
    {
        return $this->app['orm.em']->getRepository(
            $this->getEntityName()
        );
    }

    /**
     * @param ObjectRepository $repository
     * @return bool
     */
    public function isFilterableRepository(ObjectRepository $repository)
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
    public function setFiltersForRepositoryByRequest(FilterableRepositoryInterface $repository)
    {
        $filterLoader = new FilterLoader();

        foreach ($filterLoader->getPlugins() as $pluginName => $pluginNamespace)
        {
            $filterParams = $this->request->get($pluginName);

            if (null !== $filterParams && is_array($filterParams)){

                $repository->filter(new $pluginNamespace($filterParams));
            }
        }

        return $repository;
    }
}