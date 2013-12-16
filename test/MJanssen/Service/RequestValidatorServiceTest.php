<?php
namespace MJanssen\Service;

use MJanssen\Service\RequestValidatorService;
use Silex\Application;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class RequestValidatorServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test if validation works
     */
    public function testValidation()
    {
        $app = $this->getMockApplication();

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request', array('getContent'));
        $request->expects($this->any())
                ->method('getContent')
                ->will($this->returnValue(json_encode(array('id' => 1, 'name' => 'foobaz'))));
        $request->attributes->set('validator', 'MJanssen\Fixtures\Validator\TestValidator');

        $requestValidator = new RequestValidatorService($app['service.validator'], $request);
        $this->assertNull($requestValidator->validateRequest());
    }

    /**
     * Test if invalid request is validated
     */
    public function testInvalidRequest()
    {
        $app = $this->getMockApplication();

        $request = $this->getMock('Symfony\Component\HttpFoundation\Request', array('getContent'));
        $request->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue(json_encode(array('id' => 'should be numeric', 'name' => 'foo', 'baz' => 'does not exist'))));
        $request->attributes->set('validator', 'MJanssen\Fixtures\Validator\TestValidator');

        $requestValidator = new RequestValidatorService($app['service.validator'], $request);

        $this->assertSame(
            '{"errors":["[id] This value should be of type numeric.\n","[name] This value is too short. It should have 5 characters or more.\n","[baz] This field was not expected.\n"]}',
            $requestValidator->validateRequest()->getContent()
        );
    }

    /**
     * Get a mock silex service
     * @return Application
     */
    protected function getMockApplication()
    {
        $app = new Application();
        $app->register(new ValidatorServiceProvider);

        $app['service.validator'] = new ValidatorService($app['validator']);

        return $app;
    }

}