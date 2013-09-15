<?php
namespace MJ\Doctrine\Service;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\ORM\EntityManager;
use MJ\DoctrineModule\Stdlib\Hydrator\Strategy\HydrateRecursiveByValue;

class HydratorService
{
    protected $hydrator;
    protected $entityManager;

    /**
     * @param DoctrineHydrator $hydrator
     */
    public function __construct(DoctrineHydrator $hydrator, EntityManager $entityManager)
    {
        $this->hydrator = $hydrator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $data
     * @param $entity
     * @return object
     */
    public function hydrateEntity($data, $entity, $hydrateAssociations = false)
    {
        if($this->isJson($data)) {
            $data = json_decode($data, true);
        }

        if(true === $hydrateAssociations) {
            $this->setStrategyAssociations($entity);
        }

        return $this->hydrator->hydrate($data, $entity);
    }

    /**
     * @param $entity
     */
    protected function setStrategyAssociations($entity)
    {
        $metadata = $this->entityManager->getClassMetadata(get_class($entity));
        $associations = $metadata->getAssociationNames();
        foreach ($associations as $association) {
            $this->hydrator->addStrategy($association, new HydrateRecursiveByValue($this->entityManager));
        }
    }

    /**
     * Check if incoming data is JSON
     * @param $string
     * @return bool
     */
    protected function isJson($string)
    {
        if(!is_string($string)) {
            return false;
        }

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}