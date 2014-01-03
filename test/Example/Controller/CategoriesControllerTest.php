<?php
namespace Example\Controller;

/**
 * Default rest controller test case
 * Class CategoriesControllerTest
 * @package Example\Controller
 */
class CategoriesControllerTest extends \MJanssen\Controller\ControllerTest
{
    protected function getTestController()
    {
        return new CategoriesController();
    }
} 