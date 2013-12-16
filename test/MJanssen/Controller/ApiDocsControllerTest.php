<?php
namespace MJanssen\Controller;

use MJanssen\Controller\ApiDocsController;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Tests\Iterator\MockSplFileInfo;

class ApiDocsControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if the index can be fetched
     */
    public function testGetAction()
    {
        $apiDocsController = new ApiDocsController();

        $apiDocsController->setFinder(
            $this->getMockFinder()
        );

        $response = $apiDocsController->getAction(
            $this->getMockRequest(),
            $this->getMockApplication()
        );

        $this->assertTrue(($response instanceof Response));
        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * Test if a specific resource can be fetched
     */
    public function testGetResourceAction()
    {
        $apiDocsController = new ApiDocsController();

        $apiDocsController->setFinder(
            $this->getMockFinder()
        );

        $response = $apiDocsController->getResourceAction(
            $this->getMockRequest(),
            $this->getMockApplication()
        );

        $this->assertTrue(($response instanceof Response));
        $this->assertEquals($response->getStatusCode(), 200);
    }

    /**
     * Check if default Finder is Symfony Finder component
     */
    public function testFinder()
    {
        $apiDocsController = new ApiDocsController();
        $this->assertTrue(($apiDocsController->getFinder() instanceof Finder));
    }

    /**
     * @return Application
     */
    protected function getMockFinder()
    {
        $finder = $this->getMock('Symfony\Component\Finder\Finder', array('files', 'in', 'name', 'getIterator'));

        $finder->expects($this->any())
               ->method('files')
               ->will($this->returnValue($finder));

        $finder->expects($this->any())
               ->method('in')
               ->will($this->returnValue($finder));

        $finder->expects($this->any())
               ->method('name')
               ->will($this->returnValue($finder));

        $iterator = new \ArrayIterator(
            array('file' => new MockSplFileInfo(array()))
        );

        $finder->expects($this->any())
               ->method('getIterator')
               ->will($this->returnValue($iterator));

        return $finder;
    }

    /**
     * @return Application
     */
    protected function getMockRequest()
    {
        $request = new Request;
        $request->attributes->set('resource', 'foo');

        return $request;
    }

    /**
     * @return Application
     */
    protected function getMockApplication()
    {
        $app = new Application();
        $app['app.path'] = '';

        return $app;
    }

}