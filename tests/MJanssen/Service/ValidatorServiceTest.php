<?php
namespace MJanssen\Service;

use Silex\Application;
use Silex\Provider\ValidatorServiceProvider;

class ValidatorServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test if a default validator works
     */
    public function testDefaultValidation()
    {
        $service = $this->getMockService();
        $service->validate(array('id' => 1, 'name' => '12345'));

        $this->assertFalse($service->hasErrors());
    }

    /**
     * Test if validator class accepts json data
     */
    public function testJsonData()
    {
        $service = $this->getMockService();
        $service->validate(json_encode(array('id' => 1, 'name' => '12345')));

        $this->assertFalse($service->hasErrors());
    }

    /**
     * Test if a assertion is triggered
     */
    public function testNumericValidation()
    {
        $service = $this->getMockService();
        $service->validate(array('id' => 'aa', 'name' => '12345'));

        $this->assertTrue($service->hasErrors());

        foreach ($service->getErrors() as $error) {
            $this->assertEquals('[id]', $error->getPropertyPath());
        }
    }

    /**
     * Test if a none specified parameter is blocked
     */
    public function testInvalidParameter()
    {
        $service = $this->getMockService();
        $service->validate(array('id' => 1, 'name' => '12345', 'foo' => 'baz'));

        $this->assertTrue($service->hasErrors());

        foreach ($service->getErrors() as $error) {
            $this->assertEquals('[foo]', $error->getPropertyPath());
        }
    }

    /**
     * Test if validator class should be set
     * @expectedException RuntimeException
     */
    public function testIfValidatorClassIsset()
    {
        $app = new Application();
        $app->register(new ValidatorServiceProvider);

        $service = new ValidatorService($app['validator']);

        $service->validate(array('id' => 1, 'name' => '12345'));
    }



    /**
     * Get a validator service
     * @return Application
     */
    protected function getMockService()
    {
        $app = new Application();
        $app->register(new ValidatorServiceProvider);

        $service = new ValidatorService($app['validator']);
        $service->setValidatorConstrainClass('MJanssen\Fixtures\Validator\TestValidator');

        return $service;
    }

}