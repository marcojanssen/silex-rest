<?php
return array(
    'config.providers' => array(
        'monolog' => array(
            'class' => 'Silex\Provider\MonologServiceProvider'
        ),
        'whoops' => array(
            'class' => 'Whoops\Provider\Silex\WhoopsServiceProvider'
        )
    )
);