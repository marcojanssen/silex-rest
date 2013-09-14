<?php
namespace MJ\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function getAction(Request $request, Application $app)
    {
        return new Response('Welcome to the API');
    }

}