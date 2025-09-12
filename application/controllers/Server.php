<?php

/**
 * @package PHPmongoDB
 * @version 2.0.0
 * @link https://github.com/phpmongodb/phpmongodb
 */
defined('PMDDA') or die('Restricted access');

class ServerController extends Controller
{
    public function Execute()
    {

        $model = new Database();
        $dbList = $model->listDatabases();
        $this->display(
            'execute',
            array(
                'databases' => $dbList,
                'code' =>  'db.getCollectionNames()',
                'db' => ''

            )
        );
    }

    public function Command()
    {
        $return = [];
        $model = new Database();
        if ($this->request->isPost()) {
            $code = trim($this->request->getParam('code'));
            $db = trim($this->request->getParam('db'));

            $return = $model->runShellCommand($db, $code);
        }
        $formatter = new Formatter();
        $this->display(
            'command',
            array(


                'output' => !empty($return['output']) ? $formatter->formatMongoOutput($return['output'])  : null,
                'error' => !empty($return['message']) ? $return['message'] : null,

            )
        );
    }
}
