<?php
namespace MJ\Doctrine\Service;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class HydratorService
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
     * @param $data
     * @param $entity
     * @return object
     */
    public function hydrateEntity($data, $entity)
    {
        if($this->isJson($data)) {
            $data = json_decode($data, true);
        }

        return $this->hydrator->hydrate($data, $entity);
    }

    /**
     * Check if incoming data is JSON
     * @param $string
     * @return bool
     */
    private function isJson($string)
    {
        if(!is_string($string)) {
            return false;
        }

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}