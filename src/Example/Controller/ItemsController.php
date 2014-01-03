<?php
namespace Example\Controller;

use MJanssen\Controller\RestController;
use MJanssen\Controller\RestControllerInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class RestController
 * @package Example\Controller
 *
 * @SWG\Resource(
 *     apiVersion="1.0",
 *     swaggerVersion="1.2",
 *     resourcePath="/items",
 *     basePath="http://example/api"
 * )
 */
class ItemsController extends RestController implements RestControllerInterface
{
    /**
     * @SWG\Api(
     *     path="/items/{itemId}.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="GET", responseClass="Item")
     *     )
     * )
     * @SWG\ErrorResponse(code="404", reason="Item not found")
     */
    public function getAction(Request $request, Application $app, $id)
    {
        return new JsonResponse(
            $this->get($app, $id)
        );
    }

    /**
     * @SWG\Api(
     *     path="/items.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="GET", responseClass="Item")
     *     )
     * )
     */
    public function getCollectionAction(Request $request, Application $app)
    {
        return new JsonResponse(
            $this->getCollection($app)
        );
    }

    /**
     * @SWG\Api(
     *     path="/items/{itemId}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="DELETE", responseClass="Item")
     *     )
     * )
     * @SWG\ErrorResponse(code="404", reason="Item not found")
     */
    public function deleteAction(Request $request, Application $app, $id)
    {
        if($this->delete($app, $id)) {
            return new Response('',204);
        }
    }

    /**
     * @SWG\Api(
     *     path="/items",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="POST", responseClass="Item")
     *     )
     * )
     */
    public function postAction(Request $request, Application $app)
    {
        return new JsonResponse(
            $this->post($app)
        );
    }

    /**
     * @SWG\Api(
     *     path="/items/{itemId}.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="PUT", responseClass="Item")
     *     )
     * )
     * @SWG\ErrorResponse(code="404", reason="Item not found")
     */
    public function putAction(Request $request, Application $app, $id)
    {
        return new JsonResponse(
            $this->put($app, $id)
        );
    }
}
