<?php
namespace MJanssen\Doctrine\Service;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use MJanssen\DoctrineModule\Stdlib\Hydrator\Strategy\ExtractRecursiveByValue;

class ExtractorService
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
     * @param $entities
     * @return array
     */
    public function extractEntities($entities, $extractAssociations = false)
    {
        $extractedItems = array();

        foreach($entities AS $entity) {
            $extractedItems[] = $this->extractEntity($entity, $extractAssociations);
        }

        return $extractedItems;

    }

    /**
     * @param $entity
     * @return array
     */
    public function extractEntity($entity, $extractAssociations = false)
    {
        if(true === $extractAssociations) {
            $this->setStrategyAssociations($entity);
            return $this->hydrator->extract($entity);
        }

        if(false === $extractAssociations) {
            return $this->convertAssociationsToEmptyArray(
                $this->hydrator->extract($entity)
            );
        }

    }

    /**
     * @param $entity
     */
    protected function setStrategyAssociations($entity)
    {
        $metadata = $this->entityManager->getClassMetadata(get_class($entity));
        $associations = $metadata->getAssociationNames();
        foreach ($associations as $association) {
            $this->hydrator->addStrategy($association, new ExtractRecursiveByValue($this->entityManager));
        }
    }

    /**
     * Converts Doctrine Collection to empty array
     * @param array $extractedResults
     * @return array
     */
    protected function convertAssociationsToEmptyArray(array $extractedResults)
    {
        foreach($extractedResults AS $key => $value) {
            if($value instanceof Collection) {
                $extractedResults[$key] = array();
            }
        }

        return $extractedResults;
    }
}