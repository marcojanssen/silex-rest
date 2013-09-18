<?php
namespace MJanssen\Doctrine\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PropertyAccess\StringUtil;
use Zend\Filter\Callback;
use Zend\Filter\Inflector;
use Zend\Filter\Word\DashToCamelCase;

class ResolverService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    /**
     * @var Inflector
     */
    private $inflector;
    
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
     * 
     * @param $entityName
     * @return string
     */
    public function getEntityClassName($namespace, $name)
    {
        return $this->getInflector()->filter(array(
            'namespace' => $namespace,
            'name'      => $name
        ));
    }
    
    /**
     * Set inflector which is used to form a fully qualified class name
     * 
     * @param Inflector $inflector
     */
    public function setInflector(Inflector $inflector)
    {
        $this->inflector = $inflector;
    }
    
    /**
     * Get inflector which is used to form a fully qualified class name
     * 
     * @return Inflector
     */
    public function getInflector()
    {
        if (null === $this->inflector) {
            $configuration = $this->entityManager->getConfiguration();
            $inflector = new Inflector(':namespace\\:name');
            $inflector->setRules(array(
                ':namespace' => array(
                    new Callback(function($value) use ($configuration) {
                        return $configuration->getEntityNamespace($value);
                    })
                ),
                ':name' => array(
                    new DashToCamelCase(),
                    new Callback(function($value) {
                        return ucfirst(StringUtil::singularify($value));
                    })
                )
            ));
            $this->setInflector($inflector);
        }
        return $this->inflector;
    }
}