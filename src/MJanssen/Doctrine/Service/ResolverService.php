<?php
namespace MJanssen\Doctrine\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @param $name
     * @return null|string
     */
    public function resolveEntity($namespace, $name)
    {
        $entityClassName = $this->getEntityClassName($namespace, $name);

        try {
            $entity = new $entityClassName;
        } catch (Exception $e) {}

        return $entityClassName;
    }


    /**
     * Get class name of entity
     * @param $namespace
     * @param $name
     * @return string
     */
    public function getEntityClassName($namespace, $name)
    {
        $configuration = $this->entityManager->getConfiguration();
        $namespace = $configuration->getEntityNamespace($namespace);


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
        return '';
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