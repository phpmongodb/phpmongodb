<?php

class Helper
{

    public static function getLoginDatabase()
    {
        $session = Application::getInstance('Session');
        $model = new Model();
        return array(
            'databases' => array(
                array(
                    'name' => isset($session->options['db']) ? $session->options['db'] : '',
                    'noOfCollecton' => isset($session->options['db']) ? count($model->listCollections($session->options['db'], TRUE)) : NULL,
                ),
            ),
        );
    }
    public static function  validateJson($string)
    {
        json_decode($string);
        if (json_last_error() === JSON_ERROR_NONE) {
            return true;
        } else {
            return false;
            //return json_last_error_msg(); // returns the error message
        }
    }
}
