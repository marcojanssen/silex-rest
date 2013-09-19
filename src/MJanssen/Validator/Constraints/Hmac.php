<?php

namespace MJanssen\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Hmac extends Constraint
{
    /**
     * @var private key that we know for this application
     */
    public $privateKey;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->privateKey) {
            throw new MissingOptionsException(sprintf('Option "privateKey" must be given for constraint %s', __CLASS__), array());
        }
    }

    public function getDefaultOption()
    {
        return 'privateKey';
    }
}