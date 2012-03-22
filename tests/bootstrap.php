<?php
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    realpath(dirname(__FILE__) . '/../vendor/ajgarlag/zf1/library'),
    realpath(dirname(__FILE__) . '/../vendor/doctrine/common/lib'),
    realpath(dirname(__FILE__) . '/../vendor/doctrine/dbal/lib'),
    realpath(dirname(__FILE__) . '/../vendor/guilhermeblanco/ZF1-Doctrine2-ServiceLayer/src/library'),
    get_include_path(),
)));

ini_set('date.timezone', 'GMT');

require_once 'Zend/Loader/Autoloader.php';
$zfa = Zend_Loader_Autoloader::getInstance();
Zend_Registry::set('Zend_Locale', new Zend_Locale('en'));

$zfa->registerNamespace('Ajgl_');
$zfa->registerNamespace('Ajgl\\');

require_once "Doctrine/Common/ClassLoader.php";
$autoloader = array(new \Doctrine\Common\ClassLoader('Doctrine'), 'loadClass');
$zfa->pushAutoloader($autoloader, 'Doctrine\\');

$zfa->registerNamespace('Bisna');

