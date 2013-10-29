<?php
namespace MJanssen\Controller;


use Silex\Application;
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
        return new JsonResponse(
            $app['service.rest.entity']->getAction($id)
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
        return new JsonResponse(
            $app['service.rest.entity']->getCollectionAction()
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
        return new JsonResponse(
            $app['service.rest.entity']->deleteAction($id)
        );

    }

    /**
     * @param Request $request
     * @param Application $app
     * @internal param $id
     * @return JsonResponse
     */
    public function postAction(Request $request, Application $app)
    {
        return new JsonResponse(
            $app['service.rest.entity']->postAction()
        );

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $id
     * @return JsonResponse
     */
    public function putAction(Request $request, Application $app, $id)
    {
        return new JsonResponse(
            $app['service.rest.entity']->putAction($id)
        );
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
            return $this->postAction($request, $app);
        }

        if('PUT' === $method) {
            return $this->putAction($request, $app, $id);
        }

        if('DELETE' === $method) {
            return $this->deleteAction($request, $app, $id);
        }

        throw new RuntimeException('Invalid method specified');
    }
}
