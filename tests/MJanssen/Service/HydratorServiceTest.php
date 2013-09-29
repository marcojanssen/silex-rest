<?php
namespace MJanssen\Service;

use JMS\Serializer\SerializerBuilder;
use MJanssen\Fixtures\Entity\Foo;

class HydratorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test hydrate single entity
     */
    public function testHydrateEntity()
    {
        $serializer = SerializerBuilder::create()->build();
        $service    = new HydratorService($serializer);

        $data = array('id' => 1, 'name' => 'foo');

        $result = $service->hydrateEntity(json_encode($data), 'MJanssen\Fixtures\Entity\Foo');

        $this->assertEquals($this->createEntity($data), $result);
    }

    /**
     * Create a mock entity
     * @param $args
     * @return Foo
     */
    protected function createEntity($args)
    {
        $entity = new Foo;
        $entity->id = $args['id'];
        $entity->name = $args['name'];
        return $entity;
    }
}