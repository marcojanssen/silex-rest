<?php
namespace Example\Controller;

/**
 * Default rest controller test case
 * Class ItemsControllerTest
 * @package Example\Controller
 */
class ItemsControllerTest extends \MJanssen\Controller\ControllerTest
{
    /**
     * Test if the get action works
     */
    public function testGetAction()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doGetAction());
    }

    /**
     * Test if the getCollection action works
     */
    public function testGetCollectionAction()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doGetCollectionAction());
    }

    /**
     * Test if the delete action works
     */
    public function testDeleteAction()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doDeleteAction());
    }

    /**
     * Test if the put action works
     */
    public function testPutAction()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doPutAction());
    }

    /**
     * Test if the post action works
     */
    public function testPostAction()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doPostAction());
    }

    /**
     * Test if every method returns a response
     */
    public function testResolveAction()
    {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doResolveAction('GET'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doResolveAction('GET', 1));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doResolveAction('POST'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doResolveAction('PUT'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->doResolveAction('DELETE'));
    }

    /**
     * @return ItemsController
     */
    protected function getTestController()
    {
        return new ItemsController();
    }
} 