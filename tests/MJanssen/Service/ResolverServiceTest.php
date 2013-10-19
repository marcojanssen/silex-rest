<?php
namespace MJanssen\Service;

class ResolverServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if a entity class name can be found
     */
    public function testGetEntityClassName()
    {
        $config = $this->getConfigMock();
        $config->expects($this->any())
               ->method('getEntityNamespace')
               ->will($this->returnValue('test'));
        $em = $this->getEntityManagerMock($config);

        $resolverService = new ResolverService($em);
        $className = $resolverService->getEntityClassName('test', 'Test');

        $this->assertEquals('test\Test', $className);
    }

    /**
     * Test if requested entity is returned
     */
    public function testResolveEntity()
    {
        $config = $this->getConfigMock();
        $config->expects($this->any())
            ->method('getEntityNamespace')
            ->will($this->returnValue('MJanssen\Fixtures\Entity'));
        $em = $this->getEntityManagerMock($config);

        $resolverService = new ResolverService($em);
        $resolvedEntity = $resolverService->getEntityClassName('test', 'Test');

        $this->assertEquals('MJanssen\Fixtures\Entity\Test', $resolvedEntity);
    }

    /**
     * @return mixed
     */
    protected function getConfigMock()
    {
        return $this->getMock('\Doctrine\ORM\Configuration', array('getEntityNamespace'), array(), '', false);
    }

    /**
     * @param $config
     * @return mixed
     */
    protected function getEntityManagerMock($config)
    {
        $em = $this->getMock('\Doctrine\ORM\EntityManager', array('getConfiguration'), array(), '', false);
        $em->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue($config));

        return $em;
    }
}