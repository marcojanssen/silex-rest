<?php
namespace MJanssen\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use Mardy\Hmac\Hmac;
use Mardy\Hmac\Headers\Headers;
use Mardy\Hmac\Headers\Values;
use Mardy\Hmac\Config\Config as HmacConfig;
use Mardy\Hmac\Storage\NonPersistent as HmacStorage;
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
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $headers = new Headers();
        $values = new Values();

        foreach($headers->get() as $headerKey => $headerValue) {
            $values->setKey($headerKey, $headerValue);
        }

        $this->hmac = new Hmac(new HmacConfig, new HmacStorage, $values);
    }

    /**
     * @param $data
     * @param $privateKey
     */
    public function validate($data, $privateKey)
    {
        $this->hmac->getConfig()->setKey($privateKey);

        $this->hmac->getConfig()->setAlgorithm("sha256");

        $values = $this->hmac->getHeaderValues();

        $this->hmac->getStorage()
                   ->setHmac($values['key'])
                   ->setTimestamp($values['when'])
                   ->setUri($values['uri']);

        if(!$this->hmac->check()) {
            $this->setError($this->hmac->getError());
        }
    }
}
