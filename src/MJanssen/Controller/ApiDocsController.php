<?php
namespace MJanssen\Controller;

use InvalidArgumentException;
use Silex\Application;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ApiDocsController
 * @package MJanssen\Controller
 */
class ApiDocsController
{
    protected $finder;

    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function getAction(Request $request, Application $app)
    {
        return new Response(
            $this->findFile('api-docs.json', $app['app.path']),
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
            $this->findFile($request->attributes->get('resource').'.json', $app['app.path']),
            200,
            array(
                "Content-Type" => "application/json",
                "Access-Control-Allow-Methods" => "GET, PUT, POST, DELETE, OPTIONS",
                "Access-Control-Allow-Origin" => "*"
            )
        );
    }

    /**
     * @param Finder $finder
     */
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return Finder
     */
    public function getFinder()
    {
        if(null === $this->finder) {
            $this->setFinder(
                new Finder()
            );
        }

        return $this->finder;
    }

    /**
     * @param $fileName
     * @param $appPath
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function findFile($fileName, $appPath)
    {
        $finder = $this->getFinder();
        $finder->files()->in($appPath.'/api-docs')->name($fileName);

        if(1 === count($finder)) {

            //todo: this has to be easier :)
            foreach($finder as $file) {
                return $file->getContents();
            }
        }

        throw new InvalidArgumentException('Unknown api doc resource requested');
    }
}
