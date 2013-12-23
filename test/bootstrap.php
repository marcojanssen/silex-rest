<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
$loader = require_once __DIR__ . "/../vendor/autoload.php";
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
//$loader->add('MJanssen\\', __DIR__);