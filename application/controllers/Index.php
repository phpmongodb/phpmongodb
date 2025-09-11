<?php

/**
 * @package PHPmongoDB
 * @version 2.0.0
 * @link https://github.com/phpmongodb/phpmongodb
 */
defined('PMDDA') or die('Restricted access');
class IndexController extends Controller
{
    public function Index()
    {
        $data = array(
            'phpversion' => phpversion(),
            'webserver' => $this->request->serverSoftware(),
            'mongoinfo' =>  $this->getModel()->getMongoInfo(),
        );

        $this->display('index', $data);
    }
    public function SetLanguage()
    {
        $this->isReadonly();
        $language = $this->request->getParam('language');
        $languages = Config::$language;
        //$this->debug($languages);
        if (array_key_exists($language, $languages)) {
            $session = new Session();
            $session->language = $language;
        } else {
            $this->message->error = I18n::t('LAN_NOT_AVA');
        }
        $this->request->redirect($_SERVER['HTTP_REFERER']);
    }
    public function Status()
    {
        $model = new Model();

        $return = $model->serverStatus();

        if ($return['success']) {
            $formatter = new Formatter();
            $data = array(
                'status' =>  $formatter->formatMongoOutput($return['output'])

            );
        } else {
            $this->message->error = $return['message'];
        }

        $this->display('status', $data);
    }
}
