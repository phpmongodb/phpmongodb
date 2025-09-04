<?php

/**
 * @package PHPmongoDB
 * @version 1.0.0
 * @link http://www.phpmongodb.org
 */
defined('PMDDA') or die('Restricted access');

class ServerController extends Controller
{

    public function Execute()
    {



        $return = [];
        $model = new Database();
        if ($this->request->isPost()) {
            $code = trim($this->request->getParam('code'));
            $db = trim($this->request->getParam('db'));

            $return = $model->runShellCommand($db, $code);
        }


        $dbList = $model->listDatabases();

        $layout = $this->request->isAjax() ? 'command' : 'execute';
        $cryptography = new Cryptography();
        $this->display(
            $layout,
            array(
                'databases' => $dbList,
                'code' => isset($code) ? $code : 'db.getCollectionNames()',
                'output' => !empty($return['output']) ? $cryptography->formatMongoOutput($return['output'])  : null,
                //'output' => !empty($return['output']) ? $return['output']  : null,
                'error' => !empty($return['message']) ? $return['message'] : null,
                'db' => isset($db) ? $db : ''
            )
        );
    }

    public function Command()
    {
        $response = NULL;
        $model = new Database();
        if ($this->request->isPost()) {
            $code = trim($this->request->getParam('code'));
            $db = trim($this->request->getParam('db'));
            $args = $this->request->getParam('args');
            if (!is_array($args)) {
                $args = array();
            }

            $response = $model->execute($db, $code, $args);
        }
        $dbList = $model->listDatabases();
        if (!isset($dbList['databases'])) {
            $dbList = Helper::getLoginDatabase();
        }

        $this->display('command', array());
    }
}
