<?php
/**
 * @package PHPmongoDB
 * @version 2.0.0 (MongoDB PHP Library)
 * @author Nanhe Kumar <phpmongodb@gmail.com>
 * @link https://in.linkedin.com/in/nanhekumar/ (Author's Profile)
 */
require_once __DIR__ . '/vendor/autoload.php'; // Composer autoloader
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors',1);
define('PMDDA',TRUE);
require(dirname(__FILE__).'/system/Engine.php');
$engine=new Engine();
$engine->start();
$engine->stop();
?>
