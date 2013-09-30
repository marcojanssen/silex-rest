<?php
namespace MJanssen\Service;

use JMS\Serializer\SerializerBuilder;
use MJanssen\Fixtures\Entity\Foo;

class ExtractorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test extract single entity
     */
    public function testExtractEntity()
    {
        $serializer = SerializerBuilder::create()->build();
        $service    = new ExtractorService($serializer);

        $data = array('id' => 1, 'name' => 'foo');
        $entity = $this->createEntity($data);

        $result = $service->extractEntity($entity, 'foo');

        $this->assertEquals($data, $result);
    }

    /**
     * Test extracting multiple entities
     */
    public function testExtractEntities()
    {
        $serializer = SerializerBuilder::create()->build();
        $service    = new ExtractorService($serializer);

        $data = array(
            array('id' => 1, 'name' => 'foo'),
            array('id' => 2, 'name' => 'baz')
        );
        $entities = array(
            $this->createEntity($data[0]),
            $this->createEntity($data[1])
        );

        $result = $service->extractEntities($entities, 'foo');

        $this->assertEquals($data, $result);
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