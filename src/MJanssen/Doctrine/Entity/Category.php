<?php
namespace MJanssen\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @Type("integer")
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"list", "detail"})
     */
    private $id;

    /**
     * @Type("string")
     * @ORM\Column(type="string")
     * @Groups({"list", "detail"})
     */
    private $name;

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