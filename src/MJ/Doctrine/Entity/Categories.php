<?php
namespace MJ\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Categories
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Items", mappedBy="category", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Collection $items
     */
    public function addItems(Collection $items)
    {
        foreach ($items as $item) {
            $item->setCategory($this);
            $this->items->add($item);
        }
    }

    /**
     * @param Collection $items
     */
    public function removeItems(Collection $items)
    {
        foreach ($items as $item) {
            $item->setCategory(null);
            $this->items->removeElement($item);
        }
    }
}