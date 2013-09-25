<?php
namespace MJanssen\Doctrine\Service;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use Doctrine\ORM\EntityManager;

class ExtractorService
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
     * @param $entities
     * @return array
     */
    public function extractEntities($entities, $group)
    {
        $extractedItems = array();

        foreach($entities AS $entity) {
            $extractedItems[] = $this->extractEntity($entity, $group);
        }

        return $extractedItems;

    }

    /**
     * @param $entity
     * @return array
     */
    public function extractEntity($entity, $group)
    {
        $serializedContext = SerializationContext::create()->setGroups(array($group));
        return json_decode($this->serializer->serialize($entity, 'json', $serializedContext));
    }
}