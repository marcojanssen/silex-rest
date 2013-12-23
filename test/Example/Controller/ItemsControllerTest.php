<?php
namespace Example\Controller;

/**
 * Default rest controller test case
 * Class ItemsControllerTest
 * @package Example\Controller
 */
class ItemsControllerTest extends \MJanssen\Controller\RestControllerTest
{
    protected function getTestController()
    {
        return new CategoriesController();
    }
} 