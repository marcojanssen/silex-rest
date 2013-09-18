<?php
namespace MJanssen\Doctrine\Service;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\ORM\EntityManager;

/**
 * Prepare incoming data for hydration
 * Class PrepareService
 * @package MJanssen\Doctrine\Service
 */
class PrepareService
{
    protected $entityManager;
    protected $hydrator;

    /**
     * @param EntityManager $entityManager
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
    public function prepareEntity($data, $entityName)
    {
        if($this->isJson($data)) {
            $data = json_decode($data, true);
        }

        $data = $this->prepareAssociations($data, $entityName);

        return $data;
    }

    /**
     * @param $entityName
     */
    public function getMetaData($entityName)
    {
        return $this->entityManager->getClassMetadata(get_class($entityName));
    }

    /**
     * @param $data
     * @param $entityName
     * @return mixed
     */
    protected function prepareAssociations($data, $entityName)
    {
        $metaData = $this->getMetaData($entityName);
        $associationMappings = $metaData->getAssociationMappings();

        foreach($associationMappings as $associationMapping) {
            $fieldName = $associationMapping['fieldName'];
            $entityAssociation = new $associationMapping['targetEntity'];
            $metaDataAssociation = $this->getMetaData($entityAssociation);
            $identifier = $metaDataAssociation->getIdentifier()[0];

            if(isset($data[$fieldName])) {
                for($index = 0; $index < count($data[$fieldName]); $index++) {

                    if(! isset($data[$fieldName][$index][$identifier])) {
                        $entity = new $associationMapping['targetEntity'];
                        $data[$fieldName][$index] = $this->hydrator->hydrate($data[$fieldName][$index], $entity);
                    }
                }

                //$this->prepareAssociations($associationMapping['targetEntity']);

            }
        }

        return $data;
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