<?php
namespace MJanssen\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use MJanssen\Validator\Constraints as HmacAssert;

class HmacValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConstraints()
    {
        $constraint = new Assert\Collection(array(
            'key'  => new HmacAssert\Hmac(array('privateKey' => 'testkey')),
            'when'  => new Assert\Length(array('min' => 1)),
            'uri'   => new Assert\Url()
        ));

        return $constraint;
    }

}