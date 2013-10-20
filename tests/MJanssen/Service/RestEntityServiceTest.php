<?php
namespace MJanssen\Service;

use Silex\Application;
use MJanssen\Fixtures\Entity\Test;

class RestEntityServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $getOutput = array('id' => 1, 'name' => 'foobaz');

    /**
     * Test get action
     */
    public function testGetAction()
    {
        $service = $this->getService();
        $response = $service->getAction(1);

        $this->assertEquals(
            $response->getContent(),
            json_encode($this->getOutput)
        );
    }

    /**
     * Test get collection action
     */
    public function testGetCollectionAction()
    {
        $service = $this->getService();
        $response = $service->getCollectionAction();

        $this->assertEquals(
            $response->getContent(),
            json_encode(
                array($this->getOutput, $this->getOutput)
            )
        );
    }

    /**
     * Test delete action
     */
    public function testDeleteAction()
    {
        $service = $this->getService();
        $response = $service->deleteAction(1);

        $this->assertEquals(
            $response->getContent(),
            json_encode(array('item removed'))
        );
    }

    /**
     * @return RestEntityService
     */
    protected function getService()
    {
        return new RestEntityService(
            $this->getMockRequest(),
            $this->getMockApplication()
        );

    }

    /**
     * @return mixed
     */
    protected function getMockRequest()
    {
        return $this->getMock('Symfony\Component\HttpFoundation\Request', array('getContent'));
    }

    /**
     * Get a mock silex service
     * @return Application
     */
    protected function getMockApplication()
    {
        $app = new Application();

        $em = $this->getMock('\Doctrine\ORM\EntityManager', array('getRepository', 'persist', 'flush', 'remove'), array(), '', false);

        $em->expects($this->any())
           ->method('getRepository')
           ->will($this->returnValue($this->getEntityRepository()));

        $app['orm.em'] = $em;

        $app['doctrine.resolver'] = $this->getResolverServiceMock();
        $app['doctrine.extractor'] = $this->getExtractorServiceMock();

        return $app;
    }

    /**
     * Create a mock entity
     * @param $args
     * @return Foo
     */
    protected function createEntity($args)
    {
        $entity = new Test;
        $entity->id = $args['id'];
        $entity->name = $args['name'];
        return $entity;
    }

    /**
     * @return mixed
     */
    protected function getEntityRepository()
    {
        $entity = $this->createEntity(array('id' => 1, 'name' => 'foobaz'));

        $repository = $this->getMock('\Doctrine\ORM\EntityRepository', array('findOneBy', 'findBy'), array(), '', false);

        $repository->expects($this->any())
                   ->method('findOneBy')
                   ->will($this->returnValue($entity));

        $repository->expects($this->any())
                   ->method('findBy')
                   ->will($this->returnValue(array($entity,$entity)));

        return $repository;
    }

    /**
     * @return mixed
     */
    protected function getResolverServiceMock()
    {
        $service = $this->getMock('MJanssen\Service\ResolverService', array('getEntityClassName'), array(), '', false);

        $service->expects($this->any())
                ->method('getEntityClassName')
                ->will($this->returnValue('MJanssen\Fixtures\Entity\Test'));

        return $service;
    }

    /**
     * @return mixed
     */
    protected function getExtractorServiceMock()
    {
        $service = $this->getMock('MJanssen\Service\ExtractorService', array('extractEntity', 'extractEntities'), array(), '', false);

        $service->expects($this->any())
                ->method('extractEntity')
                ->will($this->returnValue($this->getOutput));

        $service->expects($this->any())
                ->method('extractEntities')
                ->will($this->returnValue(array($this->getOutput, $this->getOutput)));

        return $service;
    }
}