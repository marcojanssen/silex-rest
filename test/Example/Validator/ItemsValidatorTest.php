<?php
namespace Example\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class ItemsValidatorTest extends AbstractValidatorTest
{
    protected $validator = 'Example\Validator\ItemsValidator';

    public function testAssertions()
    {
        $validator = new $this->validator();
        $constraints = $validator->getConstraints();

        $this->assertInstanceOf('Symfony\Component\Validator\Constraints\Length', $constraints->fields['name']->constraints[0]);
        $this->assertSame(5, $constraints->fields['name']->constraints[0]->min);
    }

    /**
     * Dataprovider
     * @return array
     */
    public function getRequiredFields()
    {
        return array(
            array('name'),
            array('email'),
            array('phone')
        );
    }
} 