<?php
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    realpath(dirname(__FILE__) . '/../vendor/ajgarlag/zf1/library'),
    get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';
$zfa = Zend_Loader_Autoloader::getInstance();

$zfa->registerNamespace('Ajgl_');

