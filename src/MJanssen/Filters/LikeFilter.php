<?php

namespace MJanssen\Filters;


use Doctrine\ORM\QueryBuilder;
use Spray\PersistenceBundle\EntityFilter\EntityFilterInterface;

class LikeFilter implements EntityFilterInterface
{

    private $arguments = array();

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Filter the QueryBuilder: Perform your actions here
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     */
    public function filter(QueryBuilder $qb)
    {
        foreach($this->arguments as $property => $value){
            $qb->andWhere(sprintf('%s.%s LIKE :incoming_%2$s', $qb->getRootAliases()[0], $property));
            $qb->setParameter('incoming_' . $property, '%' . $value . '%');
        }
    }

    /**
     * Get the name of the filter
     *
     * @return string
     */
    public function getName()
    {
        return "MJanssen_Filters_LikeFilter";
    }
}