<?php
namespace MJanssen\Service;

use JMS\Serializer\Serializer;

class HydratorService
{
    protected $serializer;

    /**
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param $data
     * @param $entity
     * @return object
     */
    public function hydrateEntity($data, $entityName)
    {
        return $this->serializer->deserialize($data, $entityName, 'json');
    }
}