<?php
$regex = array();
$regex['string'] = '^[a-z\-]+$';
$regex['id']     = '^[\d]+$';

return array(
    'config.routes' => array(
        array(
            'pattern' => '%baseUrl%/api/api-docs',
            'controller' => 'MJanssen\Controllers\ApiDocsController::getAction',
            'method' => array(
                'get'
            )
        ),
        array(
            'pattern' => '%baseUrl%/api/api-docs/resources/{resource}.json',
            'controller' => 'MJanssen\Controllers\ApiDocsController::getResourceAction',
            'method' => array(
                'get'
            ),
            'assert' => array(
                array('resource' => $regex['string'])
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/items/{id}',
            'controller' => 'MJanssen\Controllers\ItemsController::resolveAction',
            'method' => array(
                'get', 'put', 'delete'
            ),
            'assert' => array(
                array('id' => $regex['id'])
            ),
            'value' => array(
                array('namespace' => 'core'),
                array('entity'    => 'items')
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/items',
            'controller' => 'MJanssen\Controllers\ItemsController::resolveAction',
            'method' => array(
                'get', 'post'
            ),
            'value' => array(
                array('namespace' => 'core'),
                array('entity'    => 'items')
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/categories/{id}',
            'controller' => 'MJanssen\Controllers\CategoriesController::resolveAction',
            'method' => array(
                'get', 'put', 'delete'
            ),
            'assert' => array(
                array('id' => $regex['id'])
            ),
            'value' => array(
                array('namespace' => 'core'),
                array('entity'    => 'categories')
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/categories',
            'controller' => 'MJanssen\Controllers\CategoriesController::resolveAction',
            'method' => array(
                'get', 'post'
            ),
            'value' => array(
                array('namespace' => 'core'),
                array('entity'    => 'categories')
            )
        )
    )
);