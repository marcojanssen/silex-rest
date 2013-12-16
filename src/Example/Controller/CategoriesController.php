<?php
namespace Example\Controller;

use MJanssen\Controller\RestController;
use MJanssen\Controller\RestControllerInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

/**
 * Class RestController
 * @package Example\Controller
 *
 * @SWG\Resource(
 *     apiVersion="1.0",
 *     swaggerVersion="1.1",
 *     resourcePath="/categories",
 *     basePath="http://example/api"
 * )
 */
class CategoriesController extends RestController implements RestControllerInterface
{
    /**
     * @SWG\Api(
     *     path="/categories/{categoryId}.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="GET", responseClass="Category")
     *     )
     * )
     * @SWG\ErrorResponse(code="404", reason="Category not found")
     */
    public function getAction(Request $request, Application $app, $id)
    {
        return parent::getAction($request, $app, $id);
    }

    /**
     * @SWG\Api(
     *     path="/categories.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="GET", responseClass="Category")
     *     )
     * )
     */
    public function getCollectionAction(Request $request, Application $app)
    {
        return parent::getCollectionAction($request, $app);
    }

    /**
     * @SWG\Api(
     *     path="/categories/{categoryId}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="DELETE", responseClass="Category")
     *     )
     * )
     * @SWG\ErrorResponse(code="404", reason="Category not found")
     */
    public function deleteAction(Request $request, Application $app, $id)
    {
        return parent::deleteAction($request, $app, $id);
    }

    /**
     * @SWG\Api(
     *     path="/categories",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="POST", responseClass="Category")
     *     )
     * )
     */
    public function postAction(Request $request, Application $app)
    {
        return parent::postAction($request, $app);
    }

    /**
     * @SWG\Api(
     *     path="/categories/{categoryId}.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="PUT", responseClass="Category")
     *     )
     * )
     * @SWG\ErrorResponse(code="404", reason="Category not found")
     */
    public function putAction(Request $request, Application $app, $id)
    {
        return parent::putAction($request, $app, $id);
    }
}
