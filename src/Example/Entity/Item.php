<?php
namespace Example\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Groups;
use Swagger\Annotations as SWG;


/**
 * @ORM\Entity(repositoryClass="Spray\PersistenceBundle\Repository\FilterableEntityRepository")
 * @ORM\Table(name="items")
 * @SWG\Model(id="Item")
 */
class Item
{

    /**
     * @Type("integer")
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"list", "detail"})
     * @SWG\Property(name="id",type="integer")
     */
    protected $id;

    /**
     * @Type("string")
     * @ORM\Column(type="string")
     * @Groups({"list", "detail"})
     * @SWG\Property(name="name",type="string")
     */
    protected $name;

    /**
     * @Type("string")
     * @ORM\Column(type="string")
     * @Groups({"list", "detail"})
     * @SWG\Property(name="email",type="string")
     */
    protected $email;

    /**
     * @Type("string")
     * @ORM\Column(type="string")
     * @Groups({"list", "detail"})
     * @SWG\Property(name="phone", type="string")
     */
    protected $phone;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
     * @Groups({"detail"})
     */
    protected $category;
}