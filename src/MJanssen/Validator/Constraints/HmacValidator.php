<?php

namespace MJanssen\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\ExecutionContextInterface;
use Mardy\Hmac\Hmac as MardyHmac;
use Mardy\Hmac\Headers\Headers;
use Mardy\Hmac\Headers\Values;
use Mardy\Hmac\Config\Config as HmacConfig;
use Mardy\Hmac\Storage\NonPersistent as HmacStorage;

class HmacValidator extends ConstraintValidator
{
    protected $key = 'testkey';

    protected $headers = null;
    protected $values = null;
    protected $hmac = null;

    public function initialize(ExecutionContextInterface $context)
    {
        parent::initialize($context);

        $this->headers = new Headers();
        $this->values = new Values();

        $headerData = $this->headers->get();

        $this->values->setKey($headerData['key']);
        $this->values->setWhen($headerData['when']);
        $this->values->setUri($headerData['uri']);

        $this->hmac = new MardyHmac(new HmacConfig, new HmacStorage, $this->values);
        $this->hmac->getConfig()->setAlgorithm("sha256");
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $this->values->getKey() || '' === $this->values->getKey()) {
            return;
        }

        $this->hmac->getConfig()->setKey($constraint->privateKey);

        $this->hmac->getStorage()
            ->setHmac($this->values->getKey())
            ->setTimestamp($this->values->getWhen())
            ->setUri($this->values->getUri());


        if($this->hmac->check() === false) {
            $this->context->addViolation($this->hmac->getError(), array(), $this->values->getKey());
        }
    }
}
