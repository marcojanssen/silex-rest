<?php
namespace Mjanssen\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="test")
 */
class Test
{
    /**
     * @Type("integer")
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @Groups({"foo"})
     */
    protected $id;

    /**
     * @Type("string")
     * @ORM\Column(type="string")
     * @Groups({"foo"})
     */
    protected $name;

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }
}