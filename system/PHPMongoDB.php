<?php

/**
 * @package PHPmongoDB
 * @version 2.0.0 (MongoDB PHP Library)
 * @author Nanhe Kumar <phpmongodb@gmail.com>
 * @link https://in.linkedin.com/in/nanhekumar/ (Author's Profile)
 */
defined('PMDDA') or die('Restricted access');


use MongoDB\Client;
use MongoDB\Exception\Exception;

class PHPMongoDB {

    protected static $instance = null;
    protected $client;
    protected $exception;

    /**
     * @param string $uri [optional]
     * @param string $dbName [optional]
     * @return PHPMongoDB
     */
    public static function getInstance($uri = 'mongodb://127.0.0.1:27017', $dbName = 'test') {
        if (is_null(self::$instance)) {
            self::$instance = new self($uri, $dbName);
        }

        return self::$instance;
    }

    /**
     * @return MongoDB\Database|false
     */
    public function getConnection() {
        return $this->client ?: false;
    }

    /**
     * @return string|false
     */
    public function getExceptionMessage() {
        return $this->exception instanceof Exception
            ? $this->exception->getMessage()
            : false;
    }

    /**
     * Private constructor for Singleton
     */
    private function __construct($uri, $dbName) {
        try {
            $this->client = (new Client($uri))->selectDatabase($dbName);
        } catch (Exception $e) {
            $this->exception = $e;
            $this->client = false;
        }
    }
}
