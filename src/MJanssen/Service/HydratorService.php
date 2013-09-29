<?php
namespace MJanssen\Service;

use JMS\Serializer\Serializer;
use Doctrine\ORM\EntityManager;

class HydratorService
{
    protected $hydrator;
    protected $entityManager;

    /**
     * @param Serializer $serializer
     * @param EntityManager $entityManager
     */
    public function __construct(Serializer $serializer, EntityManager $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
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