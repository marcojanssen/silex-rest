<?php
namespace Example\Controller;

use MJanssen\Controller\RestController;
use MJanssen\Controller\ControllerInterface;
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
class CategoriesController extends RestController implements ControllerInterface
{
    /**
     * @SWG\Api(
     *     path="/{categoriesId}.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="GET", responseClass="FooModel")
     *     )
     * )
     */
    public function getAction(Request $request, Application $app, $id)
    {
        return parent::getAction($request, $app, $id);
    }

    /**
     * @SWG\Api(
     *     path=".{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="GET", responseClass="FooModel")
     *     )
     * )
     */
    public function getCollectionAction(Request $request, Application $app)
    {
        return parent::getCollectionAction($request, $app);
    }

    /**
     * @SWG\Api(
     *     path="/{categoriesId}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="DELETE", responseClass="FooModel")
     *     )
     * )
     */
    public function deleteAction(Request $request, Application $app, $id)
    {
        return parent::deleteAction($request, $app, $id);
    }

    /**
     * @SWG\Api(
     *     path="",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="POST", responseClass="FooModel")
     *     )
     * )
     */
    public function postAction(Request $request, Application $app)
    {
        return parent::postAction($request, $app);
    }

    /**
     * @SWG\Api(
     *     path="/{categoriesId}.{format}",
     *     @SWG\Operations(
     *         @SWG\Operation(httpMethod="PUT", responseClass="FooModel")
     *     )
     * )
     */
    public function putAction(Request $request, Application $app, $id)
    {
        return parent::putAction($request, $app, $id);
    }
}
