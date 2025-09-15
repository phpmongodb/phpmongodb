<?php

use MongoDB\Client;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Exception\Exception;

class Model
{

    protected $client;
    protected $manager;

    public function __construct()
    {
        $session = Application::getInstance('Session');

        try {
            $uri = $session->server ?: 'mongodb://127.0.0.1:27017';
            $options = $session->options ?? [];

            $this->client = new Client($uri, $options);
            $this->manager = new Manager($uri, $options);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function listDatabases1()
    {
        try {
            return $this->client->listDatabases();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function listDatabases()
    {
        try {
            $cursor = $this->client->listDatabases();

            $databases = [];

            foreach ($cursor as $dbInfo) {
                $databases[] = [
                    'name' => $dbInfo->getName(),
                    'sizeOnDisk' => $dbInfo->getSizeOnDisk(),
                    'empty' => $dbInfo->isEmpty(),
                    //'raw' => (array) $dbInfo // In case MongoDB\Model\DatabaseInfo has extra hidden fields
                ];
            }

            return $databases;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }



    public function listCollections($dbName, $withSystemCollections = false)
    {
        $collections = $this->client->selectDatabase($dbName)->listCollections();
        $result = [];

        foreach ($collections as $collectionInfo) {
            $name = $collectionInfo->getName();

            if (!$withSystemCollections && strpos($name, 'system.') === 0) {
                continue;
            }

            $collection = $this->client->selectDatabase($dbName)->selectCollection($name);
            $result[] = $collection;
        }

        return $result;
    }


    public function getMongoInfo()
    {
        try {
            $result = $this->manager->executeCommand('admin', new Command(['buildInfo' => 1]));
            return json_decode(json_encode($result->toArray()[0]), true);  // stdClass → array
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function find($db, $collection, $query = [], $fields = [], $limit = 0, $skip = 0, $format = 'array', $orderBy = ['_id' => 1])
    {
        try {
            $options = [
                'projection' => $fields,
                'limit'      => $limit,
                'skip'       => $skip,
                'sort'       => $orderBy
            ];

            $cursor = $this->client->selectCollection($db, $collection)->find($query, $options);

            $data = iterator_to_array($cursor);

            return [
                'success' => true,
                'data'    => $data,
                'count'   => count($data)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function insert($db, $collection, $document = [], $format = 'array', $options = [])
    {
        try {
            $result = $this->client->selectCollection($db, $collection)->insertOne($document, $options);

            return [
                'success' => true,
                'message' => "Document inserted successfully into '$collection' collection.",
                'insertedId' => (string)$result->getInsertedId()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function serverStatus()
    {
        try {
            $command = new MongoDB\Driver\Command(['serverStatus' => 1]);
            $cursor = $this->manager->executeCommand('admin', $command);

            // Return raw BSONDocument (preserves types)
            $document = current($cursor->toArray());

            return [
                'success' => true,
                'output' => $document
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }





    public function countDocuments($dbName, $collectionName, $query = [])
    {
        $collection = $this->client->selectDatabase($dbName)->selectCollection($collectionName);
        return $collection->countDocuments($query);
    }
}
