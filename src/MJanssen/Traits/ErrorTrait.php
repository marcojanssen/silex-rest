<?php

namespace MJanssen\Traits;

trait ErrorTrait
{
    protected $errors = array();

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
    public function setErrors($errors = array())
    {
        if(!is_array($errors)) {
            throw new \Exception('setErrors: supplied argument must be an array', 500);
        }

        $this->errors = $errors;
    }

    /**
     * @param array $errorInformation
     *
     * @throws \Exception
     */
    public function setError($errorInformation = array())
    {
        if(!is_array($errorInformation)) {
            throw new \Exception('setError: supplied argument must be an array', 500);
        }

        $this->errors[] = $errorInformation;
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

}