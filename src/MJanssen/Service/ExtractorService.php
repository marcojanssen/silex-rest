<?php
namespace MJanssen\Service;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;

class ExtractorService
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
        return json_decode($this->serializer->serialize($entity, 'json', $serializedContext), true);
    }
}