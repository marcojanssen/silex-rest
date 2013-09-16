<?php
namespace MJ\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;

class ValidatorService
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator;

    protected $errors = array();

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
        $validatorClass = sprintf('MJ\Validator\%sValidator', ucfirst($validatorName));
        try {
            $validator = new $validatorClass;
        } catch (Exception $e) {

        }

        if($this->isJson($data)) {
            $data = json_decode($data, true);
        }

        $this->errors = $this->validator->validateValue(
            $data,
            $validator->getConstraints()
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
