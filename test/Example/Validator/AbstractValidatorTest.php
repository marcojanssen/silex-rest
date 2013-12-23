<?php
namespace Example\Validator;

abstract class AbstractValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getRequiredFields
     */
    public function testRequiredFields($field)
    {
        $validator = new $this->validator();
        $constraints = $validator->getConstraints();

        $this->assertArrayHasKey($field, $constraints->fields);
        $this->assertInstanceOf('Symfony\Component\Validator\Constraints\Required', $constraints->fields[$field]);
    }
} 