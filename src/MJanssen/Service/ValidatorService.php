<?php
namespace MJanssen\Service;

use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidatorService
{
    protected $errors = array();

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
     * Check if errors exist
     * @return bool
     */
    public function hasErrors()
    {
        if (count($this->errors) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Returns the errors
     *
     * @return array
     */
    public function getErrors()
    {
        if ($this->hasErrors()) {
            return $this->errors;
        } else {
            return;
        }
    }

    /**
     * @param array $errors
     *
     * @throws \Exception
     */
    public function setErrors(ConstraintViolationList $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Return the errors in a response ready format
     *
     * @return array with errors
     */
    public function getErrorResponse()
    {
        $errorResponse = array();

        foreach ($this->getErrors() as $error) {
            $errorResponse[] = $error->getPropertyPath().' '.$error->getMessage()."\n";
        }

        return $errorResponse;
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
