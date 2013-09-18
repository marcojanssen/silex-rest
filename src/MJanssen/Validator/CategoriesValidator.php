<?php
namespace MJanssen\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class CategoriesValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConstraints()
    {
        $constraint = new Assert\Collection(array(
            'name' => new Assert\Length(array('min' => 5))
        ));

        return $constraint;
    }

}