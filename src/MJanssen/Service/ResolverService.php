<?php
namespace MJanssen\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use UnexpectedValueException;
use Symfony\Component\PropertyAccess\StringUtil;


class ResolverService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @todo this is a cheap way to find the entity, need to check the class meta data from doctrine
     * @param $namespaceAlias
     * @param $name
     * @return string
     */
    public function resolveEntity($namespaceAlias, $name)
    {
        $entityClassName = $this->getEntityClassName($namespaceAlias, $name);

        try {
            new $entityClassName;
        } catch (Exception $e) {}

        return $entityClassName;
    }

    /**
     * Get class name of entity
     * @param $namespaceAlias
     * @param $name
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getEntityClassName($namespaceAlias, $name)
    {
        $configuration = $this->entityManager->getConfiguration();
        $namespace = $configuration->getEntityNamespace($namespaceAlias);

        $nameResults = StringUtil::singularify($name);

        if(is_string($nameResults)) {
            return $this->formatClassName($namespace,$nameResults);
        }
        if (is_array($nameResults)) {
            foreach ($nameResults as $nameResult) {
                $className = $this->formatClassName($namespace,$nameResult);

                if (class_exists($className)) {
                    return $className;
                }
            }
        }
        throw new UnexpectedValueException('Entity not found');
    }

    /**
     * format the class namespace
     * @param $namespace
     * @param $name
     * @return string
     */
    private function formatClassName($namespace, $name)
    {
        return sprintf('%s\\%s',
            $namespace,
            ucfirst($name)
        );
    }
}