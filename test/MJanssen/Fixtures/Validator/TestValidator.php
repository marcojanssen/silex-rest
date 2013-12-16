<?php
namespace MJanssen\Fixtures\Validator;

use MJanssen\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TestValidator implements ValidatorInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConstraints()
    {
        $constraint = new Assert\Collection(array(
            'id' => new Assert\Type(array('type' => 'numeric')),
            'name' => new Assert\Length(array('min' => 5))
        ));

        return $constraint;
    }

}