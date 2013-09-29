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

    'orm.proxies_dir'   => '%app_path%/app/cache/Doctrine/Proxies',
    'orm.default_cache' => 'apc', #should be set to apc/xcache on production
    'orm.em.options'    => array(
        'mappings' => array(
            array(
                'type'      => 'annotation',
                'namespace' => 'Example\Entity',
                'alias'     => 'core',
                'path'      => '%app_path%/src/Example/Entity',
                'use_simple_annotation_reader' => false
            )
        )
    ),

    //paths for cache & logs
    'app_path'   => '%app_path%',
    'cache.path' => '%app_path%/app/cache',
    'log.path'   => '%app_path%/app/logs',

    'serializer.cache.dir' => '%app_path%/app/cache/serializer'
);