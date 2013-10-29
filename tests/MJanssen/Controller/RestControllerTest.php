<?php
namespace MJanssen\Controller;

use MJanssen\Fixtures\Controller\TestController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class RestControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if the get action works
     */
    public function testGetAction()
    {
        $response = $this->getTestController()->getAction(
            $this->getMockRequest(),
            $this->getMockApplication(),
            1
        );

        $this->assertEquals($response->getContent(), json_encode(array('getAction')));

    }

    /**
     * Test if the getCollection action works
     */
    public function testGetCollectionAction()
    {
        $response = $this->getTestController()->getCollectionAction(
            $this->getMockRequest(),
            $this->getMockApplication()
        );

        $this->assertEquals($response->getContent(), json_encode(array('getCollectionAction')));
    }

    /**
     * Test if the delete action works
     */
    public function testDeleteAction()
    {
        $response = $this->getTestController()->deleteAction(
            $this->getMockRequest(),
            $this->getMockApplication(),
            1
        );

        $this->assertEquals($response->getContent(), json_encode(array('deleteAction')));
    }

    /**
     * Test if the put action works
     */
    public function testPutAction()
    {
        $response = $this->getTestController()->putAction(
            $this->getMockRequest(),
            $this->getMockApplication(),
            1
        );

        $this->assertEquals($response->getContent(), json_encode(array('putAction')));
    }

    /**
     * Test if the post action works
     */
    public function testPostAction()
    {
        $response = $this->getTestController()->postAction(
            $this->getMockRequest(),
            $this->getMockApplication()
        );

        $this->assertEquals($response->getContent(), json_encode(array('postAction')));
    }

    /**
     * Test if the resolve action works
     * @expectedException RuntimeException
     */
    public function testResolveAction()
    {
        $this->assertEquals($this->executeResolveActionController('FOO'), 'fooAction');
    }

    /**
     * Test if a invalid method triggers an exception
     */
    public function testInvalidResolveAction()
    {
        $this->assertEquals($this->executeResolveActionController('GET'), json_encode(array('getCollectionAction')));
        $this->assertEquals($this->executeResolveActionController('GET', 1), json_encode(array('getAction')));
        $this->assertEquals($this->executeResolveActionController('POST'), json_encode(array('postAction')));
        $this->assertEquals($this->executeResolveActionController('DELETE', 1), json_encode(array('deleteAction')));
        $this->assertEquals($this->executeResolveActionController('PUT', 1), json_encode(array('putAction')));
    }

    protected function executeResolveActionController($method, $identifier = null)
    {
        return $this->getTestController()->resolveAction(
            $this->getMockRequest($method),
            $this->getMockApplication(),
            $identifier
        )->getContent();
    }

    /**
     * @return TestController
     */
    protected function getTestController()
    {
        return new TestController();
    }

    /**
     * @return Request
     */
    protected function getMockRequest($method = '')
    {
        $request = new Request;

        if(!empty($method)) {
            $request->setMethod($method);
        }

        return $request;
    }

    /**
     * @return Application
     */
    protected function getMockApplication()
    {
        $app = new Application();

        $serviceRestEntity = $this->getMock('MJanssen\Service\RestEntityService', array(), array($this->getMockRequest(), $app));

        $serviceRestEntity->expects($this->any())
                          ->method('getAction')
                          ->will($this->returnValue(array('getAction')));

        $serviceRestEntity->expects($this->any())
                          ->method('getCollectionAction')
                          ->will($this->returnValue(array('getCollectionAction')));

        $serviceRestEntity->expects($this->any())
                          ->method('deleteAction')
                          ->will($this->returnValue(array('deleteAction')));

        $serviceRestEntity->expects($this->any())
                          ->method('putAction')
                          ->will($this->returnValue(array('putAction')));

        $serviceRestEntity->expects($this->any())
                          ->method('postAction')
                          ->will($this->returnValue(array('postAction')));

        $app['service.rest.entity'] = $serviceRestEntity;

        return $app;
    }

}