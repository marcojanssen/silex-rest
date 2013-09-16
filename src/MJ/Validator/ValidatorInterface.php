<?php
namespace MJ\Validator;

interface ValidatorInterface
{
    /**
     * Get validation constraints
     * @return mixed
     */
    public function getConstraints();
}