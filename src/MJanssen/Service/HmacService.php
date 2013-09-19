<?php
namespace MJanssen\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use MJanssen\Validator\HmacValidator;
use MJanssen\Traits\ErrorTrait;
use Mardy\Hmac\Headers\Headers;

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
    }

    /**
     * @param $data
     * @param $privateKey
     */
    public function validate($data)
    {
        $validator = new HmacValidator();
        $headers = new Headers();

        $this->setErrors(
            $this->validator->validateValue(
                $headers->get(),
                $validator->getConstraints()
            )
        );
    }
}
