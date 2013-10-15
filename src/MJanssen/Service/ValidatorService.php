<?php
namespace MJanssen\Service;

use Symfony\Component\Validator\Validator;
use Wizkunde\Traits\ErrorTrait;

class ValidatorService
{
    use ErrorTrait;

    /**
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator;

    protected $validatorClass;

    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $validatorClassName
     */
    public function setValidatorConstrainClass($validatorClassName)
    {
        try {
            $this->validatorClass = new $validatorClassName;
        } catch (Exception $e) {

        }
    }

    /**
     * @return mixed
     */
    public function getValidatorConstrainClass()
    {
        return $this->validatorClass;
    }

    /**
     * Validates incoming data
     *
     * @param $validatorName
     * @param $data
     */
    public function validate($data)
    {
        if($this->isJson($data)) {
            $data = json_decode($data, true);
        }

        if(!is_object($this->getValidatorConstrainClass())) {
            throw new \RuntimeException('No valid validator class set');
        }

        $this->setErrors(
            $this->validator->validateValue(
                $data,
                $this->getValidatorConstrainClass()->getConstraints()
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
