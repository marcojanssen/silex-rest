<?php
namespace MJ\Doctrine\Service;

class ResolverService
{
    protected $entityManager;

    /**
     * @todo this is a cheap way to find the entity, need to check the class meta data from doctrine
     * @param $entityName
     * @return null|string
     */
    public function resolveEntity($entityName)
    {
        $entityClassName = $this->getEntityClassName($entityName);

        try {
            $entity = new $entityClassName;
        } catch (Exception $e) {}

        return $entityClassName;
    }

    /**
     * @todo this is a cheap way to find the entity, need to check the class meta data from doctrine
     * @param $entityName
     * @return string
     */
    public function getEntityClassName($entityName)
    {
        return sprintf('MJ\Doctrine\Entities\%s', ucfirst($entityName));
    }
}