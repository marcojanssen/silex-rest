<?php
namespace MJanssen\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class HmacValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConstraints()
    {
        $constraint = new Assert\Collection(array(
            'key'  => new Assert\EqualTo(array('value'   => 'testkey')),
            'when'  => new Assert\Length(array('min' => 1)),
            'uri'  => new Assert\Length(array('min' => 1))
        ));

        return $constraint;
    }

}