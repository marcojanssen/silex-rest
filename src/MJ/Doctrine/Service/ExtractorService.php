<?php
namespace MJ\Doctrine\Service;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ExtractorService
{
    protected $hydrator;

    /**
     * @param DoctrineHydrator $hydrator
     */
    public function __construct(DoctrineHydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @param $entities
     * @return array
     */
    public function extractEntities($entities)
    {
        $extractedItems = array();

        foreach($entities AS $entity) {
            $extractedItems[] = $this->extractEntity($entity);
        }

        return $extractedItems;

    }

    /**
     * @param $entity
     * @return array
     */
    public function extractEntity($entity)
    {
        return $this->hydrator->extract($entity);
    }
}