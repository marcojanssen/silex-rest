<?php
namespace MJanssen\Doctrine\Service;

use Doctrine\ORM\EntityManager;

class RepositoryService
{
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Find a single entity by id
     *
     * @param $entityName
     * @param $id
     * @param string $idField
     * @return null|object
     */
    public function findEntityById($entityName, $id, $idField = 'id')
    {
        return $this->entityManager->getRepository($entityName)->findOneBy(
            array($idField => $id)
        );
    }

    /**
     * Finds multiple elements according to search criteria
     *
     * @todo: split this into a paginator class
     * @param $entityName
     * @param array $findBy
     * @param array $sort
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function findEntitiesByCriteria($entityName, $findBy = array(), $sort = array('id' => 'ASC'), $limit = 0, $start = 25)
    {
        return $this->entityManager->getRepository($entityName)->findBy(
            $findBy,
            $sort,
            (int) $limit,
            (int) $start
        );
    }
}