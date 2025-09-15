<?php
/**
 * @package PHPmongoDB
 * @version 2.0.0 (MongoDB PHP Library)
 * @author Nanhe Kumar <phpmongodb@gmail.com>
 * @link https://in.linkedin.com/in/nanhekumar/ (Author's Profile)
 */
defined('PMDDA') or die('Restricted access');

class Engine {

    protected $system;

    public function __construct() {
        $this->environmentDetection();
        $this->load();
    }

    public function start() {
        $this->system = new System();
        $this->system->start();
    }

    public function stop() {
        if ($this->system->isTheme()) {
            $this->system->getTheme();
        } else {
            $this->system->getView();
        }
    }

    public function environmentDetection() {

        if (!version_compare(PHP_VERSION, "7.4", ">=")) {
            exit("This application requires PHP version 7.4 or higher.");
        }

        
        if (!extension_loaded("mongodb")) {
            exit("To make things right, you must install the MongoDB extension. <a href=\"https://www.php.net/manual/en/mongodb.installation.php\" target=\"_blank\"> Click here for installation guide</a>.");
        }
    }

    public function load() {
        self::loadConfig();
        spl_autoload_register('self::autoloadSystem');
        spl_autoload_register('self::autoloadController');
        spl_autoload_register('self::autoloadModel');
    }

    public static function loadConfig() {
        $fileWithPath = getcwd() . '/config.php';
        self::includes($fileWithPath);
    }

    public static function autoloadSystem($class) {
        $fileWithPath = dirname(__FILE__) . '/' . $class . '.php';
        self::includes($fileWithPath);
    }

    public static function autoloadController($class) {
        $fileWithPath = getcwd() . '/application/controllers/' . str_replace('Controller', '', $class) . '.php';
        self::includes($fileWithPath);
    }

    public static function autoloadModel($class) {
        $fileWithPath = getcwd() . '/application/models/' . $class . '.php';
        self::includes($fileWithPath);
    }

    public static function includes($fileWithPath) {
        if (is_readable($fileWithPath)) {
            require_once($fileWithPath);
        }
    }

}
?>
