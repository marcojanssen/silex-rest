<?php
namespace Example\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 * @SWG\Model(id="Category")
 */
class Category
{
    /**
     * @Type("integer")
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"list", "detail"})
     * @SWG\Property(name="id", type="integer")
     */
    protected $id;

    /**
     * @Type("string")
     * @ORM\Column(type="string")
     * @Groups({"list", "detail"})
     * @SWG\Property(name="name", type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Groups({"list", "detail"})
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }
}