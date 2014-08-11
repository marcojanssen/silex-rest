<?php
return array(
    //application settings
    'baseUrl' => '',

    //db settings
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'charset' => 'UTF8',
        'master' => array(
            'user'     => 'root',
            'password' => 'root',
            'host'     => 'localhost',
            'dbname'   => 'silexrest'
        ),
        'slaves' => array(
            array(
                'user'     => 'root',
                'password' => 'root',
                'host'     => 'localhost',
                'dbname'   => 'silexrest'
            )
        ),
        'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection'
    ),

    'orm.proxies_dir'   => '%app.path%/app/cache/Doctrine/Proxies',
    'orm.default_cache' => 'xcache', #should be set to apc/xcache on production
    'orm.em.options'    => array(
        'mappings' => array(
            array(
                'type'      => 'annotation',
                'namespace' => 'Example\Entity',
                'alias'     => 'core',
                'path'      => '%app.path%/src/Example/Entity',
                'use_simple_annotation_reader' => false
            )
        )
    ),

    //paths for cache & logs
    'app.path'   => '%app.path%',
    'cache.path' => '%app.path%/app/cache',
    'log.path'   => '%app.path%/app/logs',

    'serializer.cache.path' => '%app.path%/app/cache/serializer'
);
