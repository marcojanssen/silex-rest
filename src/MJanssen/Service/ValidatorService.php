<?php
namespace MJanssen\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use MJanssen\Traits\ErrorTrait;

class ValidatorService
{
    use ErrorTrait;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator;

    /**
     * @param Request $request
     */
    public function __construct(Validator $validator, Request $request)
    {
        $this->request = $request;
        $this->validator = $validator;
    }

    /**
     * Validates incoming data
     *
     * @param $validatorName
     * @param $data
     */
    public function validate($validatorName, $data)
    {
        $validatorClass = sprintf('MJanssen\Validator\%sValidator', ucfirst($validatorName));
        try {
            $validator = new $validatorClass;
        } catch (Exception $e) {

        }

        if($this->isJson($data)) {
            $data = json_decode($data, true);
        }

        $this->setErrors(
            $this->validator->validateValue(
                $data,
                $validator->getConstraints()
            )
        );
    }

    /**
     * Check if incoming data is JSON
     * @param $string
     * @return bool
     */
    protected function isJson($string)
    {
        if(!is_string($string)) {
            return false;
        }

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}
