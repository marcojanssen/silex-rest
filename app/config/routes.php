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
                'resource' => $regex['string']
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/items/{id}',
            'controller' => 'MJanssen\Controllers\ItemsController::resolveAction',
            'method' => array(
                'get', 'put', 'delete'
            ),
            'assert' => array(
                'id' => $regex['id']
            ),
            'value' => array(
                'namespace' => 'core',
                'entity'    => 'items'
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/items',
            'controller' => 'MJanssen\Controllers\ItemsController::resolveAction',
            'method' => array(
                'get', 'post'
            ),
            'value' => array(
                'namespace' => 'core',
                'entity'    => 'items'
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/categories/{id}',
            'controller' => 'MJanssen\Controllers\CategoriesController::resolveAction',
            'method' => array(
                'get', 'put', 'delete'
            ),
            'assert' => array(
                'id' => $regex['id']
            ),
            'value' => array(
                'namespace' => 'core',
                'entity'    => 'categories'
            )
        ),
        array(
            'pattern' => '%baseUrl%/core/categories',
            'controller' => 'MJanssen\Controllers\CategoriesController::resolveAction',
            'method' => array(
                'get', 'post'
            ),
            'value' => array(
                'namespace' => 'core',
                'entity'    => 'categories'
            )
        )
    )
);