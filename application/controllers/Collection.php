<?php

/**
 * @package PHPmongoDB
 * @version 2.0.0
 * @link https://github.com/phpmongodb/phpmongodb
 */
defined('PMDDA') or die('Restricted access');
/*
 * Controller
 */

use MongoDB\BSON\ObjectId;

class CollectionController extends Controller
{

    protected $db = NULL;
    protected $collection = NULL;
    protected $url = NULL;
    protected $model = FALSE;

    public function getModel()
    {
        if (!($this->model instanceof Collection)) {
            return $this->model = new Collection();
        } else {
            return $this->model;
        }
    }

    public function setDB()
    {
        $this->db = urldecode($this->request->getParam('db'));
    }

    public function setCollection()
    {
        $this->collection = urldecode($this->request->getParam('collection'));
    }

    public function Index()
    {

        $this->setDB();
        if ($this->isValidDB($this->db)) {
            $model = $this->getModel();
            $collections = $model->listCollections($this->db, TRUE);
            $collectionList = array();
            foreach ($collections as $collection) {
                $collectionList[] = array('name' => $collection->getCollectionName(), 'count' => $collection->count());
            }
            //$this->debug($collectionList);
            $data = array(
                'collectionList' => $collectionList,
            );
            //$this->debug($data);
            $this->application->view = 'Collection';
            $this->display('index', $data);
        } else {
            $this->gotoDatabse();
        }
    }

    public function Insert()
    {
        $this->isReadonly();
        $this->setDB();
        $this->setCollection();
        if (empty($this->db) || empty($this->collection)) {
            $this->gotoDatabse();
        }
        $this->application->view = 'Collection';
        $data = array('isAjax' => $this->request->isAjax());
        $this->display('insert', $data);
    }

    public function Indexes()
    {
        $this->setDB();
        $this->setCollection();
        if (empty($this->db) || empty($this->collection)) {
            $this->gotoDatabse();
        }
        $this->application->view = 'Collection';
        $response = $this->getModel()->listIndexes($this->db, $this->collection);
        if ($response['success']) {
            $data['indexes'] = $response['indexes'];
        } else {
            $data['error']  = $response['message'];
        }
        $data['formatter'] = new Formatter();
        $this->display('indexes', $data);
    }

    public function DeleteIndexes()
    {
        $this->isReadonly();
        $this->setDB();
        $this->setCollection();
        $name = trim($this->request->getParam('name'));
        if (empty($this->db) || empty($this->collection) || empty($name)) {
            $this->gotoDatabse();
        }
        $model = $this->getModel();
        $response = $model->dropIndex($this->db, $this->collection, $name);
        if ($response['success']) {
            $this->message->sucess = I18n::t('I_D');
        } else {
            $this->message->error = $response['message'];
        }
        $this->request->redirect(Theme::URL('Collection/Indexes', array('db' => $this->db, 'collection' => $this->collection)));
    }

    public function CreateIndexes()
    {
        $this->isReadonly();
        $this->setDB();
        $this->setCollection();

        $fields = $this->request->getParam('fields');
        $orders = $this->request->getParam('orders');
        $name = $this->request->getParam('name');
        $unique = $this->request->getParam('unique');
        $drop = $this->request->getParam('drop');
        $ttl = $this->request->getParam('expireAfterSeconds');
        $sparse = $this->request->getParam('sparse');
        $hidden = $this->request->getParam('hidden');
        $background = $this->request->getParam('background');
        $partialFilter = $this->request->getParam('partialFilterExpression');
        $collation = $this->request->getParam('collation');

        $options = [];

        // Index key
        for ($i = 0; $i < count($orders); $i++) {
            $key[$fields[$i]] = (int) $orders[$i];
        }

        if (!empty($name)) {
            $options['name'] = $name;
        }

        if (!empty($unique)) {
            $options['unique'] = true;
        }

        if (!empty($drop)) {
            $options['dropDups'] = true; // Deprecated in MongoDB 3.0+, but keeping for legacy
        }

        if (!empty($ttl)) {
            $options['expireAfterSeconds'] = (int) $ttl;
        }

        if (!empty($sparse)) {
            $options['sparse'] = true;
        }

        if (!empty($hidden)) {
            $options['hidden'] = true;
        }

        if (!empty($background)) {
            $options['background'] = true;
        }

        if (!empty($partialFilter)) {
            $decoded = json_decode($partialFilter, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $options['partialFilterExpression'] = $decoded;
            }
        }

        if (!empty($collation)) {
            $decoded = json_decode($collation, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $options['collation'] = $decoded;
            }
        }

        // Create index
        $response = $this->getModel()->createIndex($this->db, $this->collection, $key, $options);

        // Redirect back to Indexes page
        $this->request->redirect(Theme::URL('Collection/Indexes', [
            'db' => $this->db,
            'collection' => $this->collection
        ]));
    }

    protected function getQuery($query = array())
    {
        $formatter = new Formatter();
        for ($ind = 0; $ind < count($query); $ind += 3) {
            if (empty($query[$ind])) {
                unset($query[$ind]);
                unset($query[$ind + 1]);
                unset($query[$ind + 2]);
                $query = array_values($query);
                if (isset($query[$ind]) && in_array($query[$ind], array('$or', '$and', '$ne', '$gt', '$gte', '$lt', '$lte'))) {
                    unset($query[$ind]);
                    $query = array_values($query);
                }
                $ind -= 3;
            }
        }
        if (count($query) == 0) {
            return $query;
        } else if (count($query) == 3) {
            $query = $formatter->executeValue($query, 2);
            return $query;
        }
        $query = $formatter->executeAND($query);
        $query = $formatter->executeOR($query);

        return $query[0];
    }

    public function getSort($orderBy, $orders)
    {
        $sort = false;
        if ($orderBy) {
            $total = count($orderBy);
            for ($i = 0; $i < $total; $i++) {
                if (!isset($orderBy[$i]) || empty($orderBy[$i])) {
                    continue;
                }
                $sort[$orderBy[$i]] = (int) $orders[$i];
            }
        } else {
            //default sort order by DESC
            $sort['_id'] = -1;
        }
        return $sort;
    }

    public function Record()
    {
        $this->setDB();
        $this->setCollection();
        $formatter = new Formatter();
        if ($this->validation($this->db, $this->collection)) {
            $record = array();
            $skip = $this->request->getParam('start', 0);
            $limit = $this->request->getParam('limit', 10);
            $type = strtolower($this->request->getParam('type', 'array'));
            $query = array();
            $fields = array();

            if ($this->request->getParam('search', false) && $this->request->getParam('query', false)) {
                switch ($type) {
                    case 'fieldvalue':
                        $query = $this->getQuery($this->request->getParam('query'));
                        break;
                    case 'array':
                        $query = $formatter->stringToArray($this->request->getParam('query'));
                        break;
                    case 'json':
                        $query = $this->request->getParam('query');
                        break;
                    default:
                        $query = array();
                        break;
                }
            }
            if (!$this->isError()) {
                $ordeBy = $this->getSort($this->request->getParam('order_by', false), $this->request->getParam('orders', false));
                $cursor = $this->getModel()->find($this->db, $this->collection, $query, $fields, $limit, $skip, $type, $ordeBy);
                if ($type == 'json') {
                    $total = $this->getModel()->totalRecord($this->db, $this->collection, $query, $type);
                } else {

                    $total = $this->getModel()->countDocuments($this->db, $this->collection, $query);
                }

                $record = $formatter->decode($cursor, $type);
            }
            $this->application->view = 'Collection';
            $format = array('json', 'array', 'document', 'jsonv2');
            $this->display('record', array('record' => $record, 'format' => $format, 'total' => $total));
        } else {
            $this->request->redirect($this->url);
        }
    }

    public function EditRecord()
    {
        $this->isReadonly();
        $this->setDB();
        $this->setCollection();
        $id = $this->request->getParam('id');
        $idType = $this->request->getParam('id_type');
        $format = $this->request->getParam('format');
        $formatter = new Formatter();
        $model = $this->getModel();
        if ($this->request->isPost()) {

            if ($this->request->getParam('format') == 'array') {
                $data = $formatter->stringToArray($this->request->getParam('data'));
                if (is_array($data)) {
                    $response = $model->updateById($this->db, $this->collection, $id, $data, 'array', $idType);
                } else {
                    $response['errmsg'] = I18n::t('INVALID_DATA');
                }
            } else if ($this->request->getParam('format') == 'json') {
                $data = $this->request->getParam('data');
                if (Helper::validateJson($data)) {
                    $response = $model->updateById($this->db, $this->collection, $id,  $data, 'json', $idType);
                } else {
                    $response['errmsg'] = I18n::t('INVALID_JSON') . ' ';
                }
            }
            if ($response['success']) {
                $this->message->sucess = I18n::t('U_S');
            } else {
                $this->message->error = $response['errmsg'];
            }
        }

        if (!empty($this->db) && !empty($this->collection) && !empty($id) && !empty($idType)) {

            $cursor = $model->findById($this->db, $this->collection, $id, $idType);

            if ($cursor) {
                unset($cursor['_id']);
                $record['json'] = $formatter->arrayToJSON($cursor);
                $record['array'] = $formatter->arrayToString($cursor);
                $this->application->view = 'Collection';
                $this->display('edit', array('record' => $record, 'format' => $format, 'id' => $id));
            } else {
                $this->message->error = I18n::t('INVALID_ID');
            }
        } else {
            $this->url = "index.php";
            $this->request->redirect($this->url);
        }
    }

    public function DeleteRecords()
    {
        if ($this->request->isAjax()) {
            $this->isReadonly();
            $this->setDB();
            $this->setCollection();
            if ($this->request->getParam('type') == 'multiple') {
                $ids = $this->request->getParam('ids');
                foreach (array_unique($ids) as $v) {
                    list($id, $idType) =  explode('-', $v);
                    $response = $this->getModel()->removeById($this->db, $this->collection, $id, $idType);
                    if ($response['success']) {
                        $this->message->sucess = I18n::t('R_S_D');
                    } else {
                        $this->message->error = I18n::t('INVALID_ID');
                    }
                }
            }
            exit(json_encode($response));
        } else {
            $this->url = Theme::URL('Collection/Record', array('db' => $this->db, 'collection' => $this->collection));
            $this->request->redirect($this->url);
        }
    }

    public function DeleteRecord()
    {
        $this->isReadonly();
        $this->setDB();
        $this->setCollection();
        $id = $this->request->getParam('id');
        $idType = $this->request->getParam('id_type');
        if (!empty($this->db) && !empty($this->collection) && !empty($id)) {
            $response = $this->getModel()->removeById($this->db, $this->collection, $id, $idType);
            if ($response['success']) {
                $this->message->sucess = I18n::t('R_S_D');
            } else {
                $this->message->error = I18n::t('INVALID_ID');
            }
            $this->url = Theme::URL('Collection/Record', array('db' => $this->db, 'collection' => $this->collection));
        } else {
            $this->url = "index.php";
        }
        $this->request->redirect($this->url);
    }

    public function SaveRecord()
    {
        $this->setDB();
        $this->setCollection();
        if ($this->validation($this->db, $this->collection)) {

            switch (strtolower($this->request->getParam('type'))) {
                case 'fieldvalue':
                    $document = array_combine($this->request->getParam('fields'), $this->request->getParam('values'));
                    $this->insertRecord($document);
                    break;
                case 'array':
                    $formatter = new Formatter();
                    $document = $formatter->stringToArray($this->request->getParam('data'));
                    $this->insertRecord($document);
                    break;
                case 'json':
                    $document = json_decode($this->request->getParam('data'), true);
                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($document)) {
                        $this->message->error = I18n::t('Invalid JSON : ') . json_last_error_msg();
                        break;
                    }

                    // Check if empty
                    if (count($document) === 0) {
                        $this->message->error = I18n::t('E_D_P');
                        break;
                    }
                    $this->insertRecord($document);
                    break;
            }
        }
        $this->request->redirect(Theme::URL('Collection/Record', array('db' => $this->db, 'collection' => $this->collection)));
    }

    private function insertRecord($document, $format = 'array')
    {
        unset($document['']);
        if (count($document) > 0) {

            $response = $this->getModel()->insert($this->db, $this->collection, $document);
            if ($response['success']) {
                $this->message->sucess = count($document) . I18n::t('R_I');
            } else {
                $this->message->error = $response['message'];
            }
        } else {
            $this->message->error = I18n::t('E_F_N_A_V');
        }
    }

    private function validation($db = NULL, $collection = NULL)
    {
        if (!$this->isValidDB($db)) {
            return false;
        }
        if (!$this->isValidCollection($collection)) {
            return false;
        }
        return true;
    }

    private function isValidDB($db = NULL)
    {
        if (empty($db) || !isset($db) || !$this->isValidName($db)) {
            $this->message->error = I18n::t('I_D_N');
            $this->setURL('db');
            return false;
        }
        return true;
    }

    private function isValidCollection($collection = NULL)
    {
        if (empty($collection) || !isset($collection)) {
            $this->message->error = I18n::t('E_C_N');
            $this->setURL('collection');
            return false;
        } else if (!$this->isValidName($collection)) {
            $this->message->error = I18n::t('Y_C_N_U_C_F_C_N');
            $this->setURL('collection');
            return false;
        } else {
            return true;
        }
    }

    private function setURL($type = NULL)
    {
        switch ($type) {
            case 'db':
            case 'database':
                $this->url = Theme::URL('Database/Indexes');
                break;
            case 'collection':
                $this->url = (empty($this->db) ? Theme::URL('Database/Indexes') : Theme::URL('Collection/Index', array('db' => $this->db)));
                break;
            default:
                $this->url = "index.php";
                break;
        }
    }

    public function CreateCollection()
    {
        $this->setDB();
        if (!empty($this->db)) {
            $this->setCollection();
            $capped = (bool)$this->request->getPost('capped', FALSE);
            $size = (int)$this->request->getPost('size', 0);
            $max = (int)$this->request->getPost('max', 0);
            if (!empty($this->collection)) {
                $this->getModel()->createCollection($this->db, $this->collection, $capped, $size, $max);
                $this->message->sucess = I18n::t('C_C', $this->collection);
            } else {
                $this->message->error = I18n::t('E_C_N');
            }
            $this->url = Theme::URL('Collection/Index', array('db' => $this->db));
        }
        $this->request->redirect($this->url);
    }

    public function RenameCollection()
    {
        $this->setDB();
        $this->setCollection();
        $oldCollection = urldecode($this->request->getParam('old_collection'));
        if ($this->validation($this->db, $this->collection)) {
            if ($this->isValidCollection($oldCollection)) {
                $response = $this->getModel()->renameCollection($oldCollection, $this->collection, $this->db);
                if ($response['success']) {
                    $this->message->sucess = I18n::t('C_R_S');
                } else {
                    $this->message->error = $response['message'];
                }
                $this->url = Theme::URL('Collection/Index', array('db' => $this->db));
            }
        }
        $this->request->redirect($this->url);
    }

    public function DropCollection()
    {
        $this->setDB();
        $this->setCollection();
        if ($this->validation($this->db, $this->collection)) {
            $response = $this->getModel()->dropCollection($this->db, $this->collection);
            if ($response['ok'] == '1') {
                $this->message->sucess = I18n::t('C_D', $this->collection);
            } else {
                $this->message->error = $response['errmsg'];
            }
            $this->url = Theme::URL('Collection/Index', array('db' => $this->db));
        }
        $this->request->redirect($this->url);
    }

    public function Remove()
    {
        $this->isReadonly();
        $this->setDB();
        $this->setCollection();
        if ($this->validation($this->db, $this->collection)) {
            $response = $this->getModel()->removeCollection($this->db, $this->collection);
            $this->message->sucess = I18n::t('C_R', $this->collection);
            $this->url = Theme::URL('Collection/Index', array('db' => $this->db));
        }
        $this->request->redirect($this->url);
    }

    protected function quickExport()
    {
        $cursor = $this->getModel()->find($this->db, $this->collection);

        $file = new File(sys_get_temp_dir(), $this->collection . '.json');
        $file->delete();

        $formatter = new Formatter();

        foreach ($cursor as $document) {
            $json = $formatter->arrayToJSON($document);
            $json = str_replace(array("\n", "\t"), '', $json);
            $file->write($json . "\n");
        }

        if ($file->success) {
            $file->download();
        } else {
            $this->message->error = $file->message;
        }
    }


    protected function customExport()
    {
        $fields = [];
        $query = [];
        $limit = $this->request->getParam('limit');
        $skip = $this->request->getParam('skip');
        $limit = empty($limit) ? 0 : (int) $limit;
        $skip = empty($skip) ? 0 : (int) $skip;

        $fileName = $this->request->getParam('file_name');
        $fileName = (empty($fileName) ? $this->collection : $fileName) . '.json';

        $cursor = $this->getModel()->find($this->db, $this->collection, $query, $fields, $limit, $skip);

        // Handle both cursor and array
        if (is_array($cursor)) {
            $documents = $cursor;
        } elseif ($cursor instanceof \MongoDB\Driver\Cursor || $cursor instanceof \Traversable) {
            $documents = iterator_to_array($cursor, false);
        } else {
            $this->message->error = "Unable to retrieve documents.";
            return false;
        }
        $path = sys_get_temp_dir();
        $file = new File($path, $fileName);
        $file->delete();

        foreach ($documents as $doc) {
            // Try BSON toJSON if possible
            if (function_exists('MongoDB\BSON\toJSON')) {
                $json = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($doc));
            } else {
                $formatter = new Formatter();
                $json = $formatter->arrayToJSON($doc);
            }

            $file->write($json . "\n");
        }

        // Save to file
        if ($this->request->getParam('text_or_save') == 'save') {
            if ($file->success) {
                if ($this->request->getParam('compression') == 'none') {
                    $file->download();
                } else {
                    $compressFile = $this->createCompress($fileName, $file);
                    if ($compressFile) {
                        $file->download($compressFile);
                    } else {
                        $this->message->error = $file->message;
                        return false;
                    }
                }
            } else {
                $this->message->error = $file->message;
                return false;
            }
        } else {
            return file_get_contents($path . '/' . $fileName);
        }
    }


    protected function createCompress($fileName, File $file)
    {
        if ($this->request->getParam('compression') == 'zip') {
            $response = $file->createZip(array($fileName), $this->collection . '.zip', TRUE);
            if ($response) {
                return $this->collection . '.zip';
            } else {
                return false;
            }
        }
    }

    public function Export()
    {
        $this->setDB();
        $this->setCollection();
        $record = false;
        if ($this->request->isPost()) {
            switch ($this->request->getParam('quick_or_custom')) {
                case 'quick':
                    $this->quickExport();
                    break;
                case 'custom':
                    $record = $this->customExport();
                    break;
            }
        }
        if (!empty($this->db) || !empty($this->collection)) {
            $this->application->view = 'Collection';
            $this->display('export', array('record' => $record));
        } else {
            $this->gotoDatabse();
        }
    }



    public function Import()
    {
        $this->isReadonly();
        $this->setDB();
        $this->setCollection();

        if ($this->request->isPost()) {
            if ($_FILES['import_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['import_file']['tmp_name'])) {
                $handle = @fopen($_FILES['import_file']['tmp_name'], "r");
                if ($handle) {
                    while (($record = fgets($handle)) !== false) {
                        $decoded = json_decode(trim($record), true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                            // Convert $oid to ObjectId
                            if (isset($decoded['_id']['$oid'])) {
                                $decoded['_id'] = new ObjectId($decoded['_id']['$oid']);
                            }

                            $response = $this->getModel()->insert($this->db, $this->collection, $decoded);
                            if ($response['success']) {
                                $this->message->sucess = I18n::t('A_D_I_S');
                            } else {
                                $this->message->error = $response['message'];
                            }
                        } else {
                            $this->message->error = "Invalid JSON: " . json_last_error_msg();
                        }
                    }

                    if (!feof($handle)) {
                        $this->message->error = I18n::t('E_U_F');
                    }

                    fclose($handle);
                }
            }
        }

        $this->application->view = 'Collection';
        $this->display('import');
    }



    public function Search()
    {
        $this->setDB();
        $this->setCollection();
        $this->display('search');
    }

    /**
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     * @access protected
     */
    protected function gotoDatabse()
    {
        $this->request->redirect(Theme::URL('Database/Index'));
    }
}
