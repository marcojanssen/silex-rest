<?php
namespace Example\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class CategoriesValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAssertions()
    {
        $validator = new CategoriesValidator();
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
            array('name')
        );
    }

    /**
     * @dataProvider getRequiredFields
     */
    public function testRequiredFields($field)
    {
        $validator = new CategoriesValidator();
        $constraints = $validator->getConstraints();

        $this->assertInstanceOf('Symfony\Component\Validator\Constraints\Required', $constraints->fields[$field]);
    }

} 