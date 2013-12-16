<?php
namespace MJanssen\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

interface RestControllerInterface
{
    public function getAction(Request $request, Application $app, $id);

    public function getCollectionAction(Request $request, Application $app);

    public function deleteAction(Request $request, Application $app, $id);

    public function postAction(Request $request, Application $app);

    public function putAction(Request $request, Application $app, $id);
}