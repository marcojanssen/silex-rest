<?php
namespace MJanssen\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use Mardy\Hmac\Hmac;
use Mardy\Hmac\Headers\Headers;
use Mardy\Hmac\Headers\Values;
use Mardy\Hmac\Config\Config as HmacConfig;
use Mardy\Hmac\Storage\NonPersistent as HmacStorage;
use MJanssen\Validator\HmacValidator;
use MJanssen\Traits\ErrorTrait;

class HmacService
{
    use ErrorTrait;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Mardy\Hmac\Hmac
     */
    protected $hmac;

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

        $headers = new Headers();
        $values = new Values();

        $headerData = $headers->get();
        $values->setKey($headerData['key']);
        $values->setWhen($headerData['when']);
        $values->setUri($headerData['uri']);

        $this->hmac = new Hmac(new HmacConfig, new HmacStorage, $values);
    }

    /**
     * @param $data
     * @param $privateKey
     */
    public function validate($data)
    {
        $validator = new HmacValidator();

        $this->hmac->getConfig()->setKey('testkey');
        $this->hmac->getConfig()->setAlgorithm("sha256");

        $values = $this->hmac->getHeaderValues();

        $this->hmac->getStorage()
                   ->setHmac($values->getKey())
                   ->setTimestamp($values->getWhen())
                   ->setUri($values->getUri());

        $this->setErrors(
            $this->validator->validateValue(
                array('key'     => $values->getKey(),
                      'when'    => $values->getWhen(),
                      'uri'     => $values->getUri()),
                $validator->getConstraints()
            )
        );
    }
}
