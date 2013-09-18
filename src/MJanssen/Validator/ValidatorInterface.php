<?php
namespace MJanssen\Validator;

interface ValidatorInterface
{
    /**
     * Get validation constraints
     * @return mixed
     */
    public function getConstraints();
}