<?php
namespace MJanssen\DoctrineModule\Stdlib\Hydrator\Strategy;

use Doctrine\Common\Collections\Collection;
use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Implements a strategy that recursively extracts any other entities
 * in collections.
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @author  Marco Janssen
 */
class HydrateRecursiveByValue extends AbstractHydrateObjectStrategy
{
    /**
     * Stores a hash of each object seen to be used for loop detection.
     *
     * @var array
     */
    protected $entitiesSeen = [];

    /**
     * A strategy to apply after the first level of recursion, as a
     * way to sensibly limit recursion for common cases (e.g. by
     * specifying ExtractId as a fallback strategy, any further
     * recursion is halted and only ids are returned).
     *
     * @var StrategyInterface
     */
    protected $fallback;

    /**
     * The class of hydrator to use for recursively hydrating.
     *
     * @var string
     */
    protected $hydratorClass = 'DoctrineModule\Stdlib\Hydrator\DoctrineObject';

    /**
     * {@inheritdoc}
     */
    protected function getIdentifier($object, $metadata)
    {
        return $this->getIdentifierByValue($object, $metadata);
    }

    /**
     * Get the fallback strategy for use after the first recursion.
     *
     * @return StrategyInterface
     */
    public function getFallback()
    {
        return $this->fallback ?: $this;
    }

    /**
     * Set the fallback strategy.
     *
     * @param StrategyInterface $fallback
     *
     * @return ExtractRecursiveByValue $this
     */
    public function setFallback(StrategyInterface $fallback)
    {
        $this->fallback = $fallback;

        return $this;
    }

    /**
     * Get the hydrator class.
     *
     * @return string
     */
    public function getHydratorClass()
    {
        return $this->hydratorClass;
    }

    /**
     * Set the hydrator class.
     *
     * @param string $class
     *
     * @return ExtractRecursiveByValue $this
     */
    public function setHydratorClass($class)
    {
        $this->hydratorClass = $class;

        return $this;
    }

    /**
     * Get a hydrator for the next level.
     *
     * @param mixed $object
     *
     * @return Hydrator
     */
    protected function getHydrator($object)
    {
        $metadata    = $this->getMetadata($object);
        $class       = $this->hydratorClass;
        $hydrator    = new $class($this->objectManager, get_class($object));

        // Add a strategy to all association fields
        $fallback = $this->getFallback();
        foreach ($metadata->getAssociationNames() as $field) {
            $hydrator->addStrategy($field, $fallback);
        }

        return $hydrator;
    }

    /**
     * Check if an object has been seen before, to avoid loops.
     *
     * @param mixed $object
     *
     * @return boolean
     */
    protected function hasSeen($object)
    {
        return in_array(spl_object_hash($object), $this->entitiesSeen);
    }

    /**
     * Record an object sighting, to avoid loops.
     *
     * @param mixed $object
     *
     * @return ExtractRecursiveByValue $this
     */
    protected function saw($object)
    {
        $this->entitiesSeen[] = spl_object_hash($object);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function hydrateObject($object)
    {
        if ($this->hasSeen($object)) {
            $metadata = $this->getMetadata($object);
            return $this->getIdentifier($object, $metadata);
        } else {
            $this->saw($object);

            return $this->getHydrator($object)->hydrate($object);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function hydrateCollection(Collection $collection)
    {
        if ($collection->isEmpty()) {
            return [];
        }

        $results     = [];
        $object      = $collection->first();
        $hydrator    = $this->getHydrator($object);
        $metadata    = $this->getMetadata($object);
        $identifiers = $metadata->getIdentifier();

        foreach ($collection as $object) {
            if ($this->hasSeen($object)) {
                $results[] = $this->getIdentifier($object, $identifiers);
            } else {
                $this->saw($object);
                $results[] = $hydrator->hydrate($object);
            }
        }

        return $results;
    }
}