<?php
namespace MJanssen\Controllers;

use InvalidArgumentException;
use Silex\Application;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiDocsController
 * @package MJanssen\Controllers
 */
class ApiDocsController
{

    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function getAction(Request $request, Application $app)
    {
        return new Response(
            $this->findFile('api-docs.json', $app['app_path']),
            200,
            array(
                "Content-Type" => "application/json",
                "Access-Control-Allow-Methods" => "GET, PUT, POST, DELETE, OPTIONS",
                "Access-Control-Allow-Origin" => "*"
            )
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function getResourceAction(Request $request, Application $app)
    {
        return new Response(
            $this->findFile($request->attributes->get('resource').'.json', $app['app_path']),
            200,
            array(
                "Content-Type" => "application/json",
                "Access-Control-Allow-Methods" => "GET, PUT, POST, DELETE, OPTIONS",
                "Access-Control-Allow-Origin" => "*"
            )
        );
    }

    /**
     * @param $fileName
     * @param $appPath
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function findFile($fileName, $appPath)
    {
        $finder = new Finder();
        $finder->files()->in($appPath.'/docs')->name($fileName);

        if(1 === count($finder)) {

            //todo: this has to be easier :)
            foreach($finder as $file) {
                return $file->getContents();
            }
        }

        throw new InvalidArgumentException('Unknown api doc resource requested');
    }
}
