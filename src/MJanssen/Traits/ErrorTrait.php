<?php

namespace MJanssen\Traits;

use Symfony\Component\Validator\ConstraintViolationListInterface;

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
    public function setErrors(ConstraintViolationListInterface $errors)
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

}