<?php
namespace MJanssen\Controller;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityNotFoundException;
use MJanssen\Filters\FilterLoader;
use Silex\Application;
use Spray\PersistenceBundle\Repository\FilterableRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Exception\RuntimeException;

/**
 * Class RestController
 * @package MJanssen\Controller
 */
abstract class RestController
{
    /**
     * @param Request $request
     * @param Application $app
     * @param null $id
     * @return JsonResponse
     */
    public function getAction(Request $request, Application $app, $id)
    {
        $entity = $this->getEntityFromRepository($request, $app, $id);
        $this->isValidEntity($entity, $app, $id);

        return new JsonResponse(
            $app['doctrine.extractor']->extractEntity(
                $entity,
                'detail'
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
                ),
                'list'
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
        $entity = $this->getEntityFromRepository($request, $app, $id);
        $this->isValidEntity($entity, $app, $id);

        $app['orm.em']->remove($entity);
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
        $response = $app['service.request.validator']->validateRequest();
        if(null !== $response) {
            return $response;
        }

        $item = $app['doctrine.hydrator']->hydrateEntity(
            $request->getContent(),
            $this->getEntityName($request, $app)
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
        $response = $app['service.request.validator']->validateRequest();
        if(null !== $response) {
            return $response;
        }

        $entity = $this->getEntityFromRepository($request, $app, $id);
        $this->isValidEntity($entity, $app, $id);

        $item = $app['doctrine.hydrator']->hydrateEntity(
            $request->getContent(),
            $this->getEntityName($request, $app)
        );

        $app['orm.em']->merge($item);
        $app['orm.em']->flush();

        return new JsonResponse(array('item updated'));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @internal param $id
     * @return JsonResponse
     */
    public function resolveAction(Request $request, Application $app, $id = null)
    {
        $method = $request->getMethod();

        if('GET' === $method) {
            if(null === $id) {
                return $this->getCollectionAction($request, $app);
            }

            return $this->getAction($request, $app, $id);
        }

        if('POST' === $method) {
            return $this->postAction($request, $app, $id);
        }

        if('PUT' === $method) {
            return $this->putAction($request, $app, $id);
        }

        if('DELETE' === $method) {
            return $this->putAction($request, $app, $id);
        }

        throw new RuntimeException('Invalid method specified');
    }

    /**
     * @param $id
     * @param string $field
     * @return mixed
     */
    protected function getEntityFromRepository(Request $request, Application $app, $id, $field = 'id')
    {
        $repository = $this->getEntityRepository($request, $app);

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
    protected function isValidEntity($entity, $app, $id)
    {
        if(null === $entity) {
            $app->abort(404, "$id does not exist.");
        }
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
