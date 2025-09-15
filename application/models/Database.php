<?php

/**
 * @package PHPmongoDB
 * @version 2.0.0
 * @link https://github.com/phpmongodb/phpmongodb
 * @author Nanhe Kumar <nanhe.kumar@gmail.com>
 */

use MongoDB\Client;
use MongoDB\Database as MongoDatabase;
use MongoDB\Driver\Exception\Exception as MongoDBException;

class Database extends Model
{


    public function createDB($name)
    {
        try {
            // Select the database and collection
            $collection = $this->client->selectDatabase($name)->createCollection('default');

            // Insert metadata document
            $this->client->selectCollection($name, 'default')->insertOne([
                '_phpmongodb_init' => true,
                'note' => 'This document was inserted by phpMongoDB to initialize the database.',
                'created_by' => 'phpmongodb',
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);

            return ['success' => true, 'message' => "Database '$name' created with default collection and metadata document."];
        } catch (MongoDB\Exception\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }




    public function dropDatabase($db)
    {
        try {
            $this->client->selectDatabase($db)->drop();
            return [
                'success' => true,
                'message' => "Database '$db' dropped successfully."
            ];
        } catch (MongoDBException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function renameDatabaseOld($oldDB,  $newDB)
    {
        try {
            $response = $this->copyDatabase($oldDB, $newDB);

            if (isset($response['ok']) && $response['ok'] == 1) {
                try {
                    $this->client->selectDatabase($oldDB)->drop();
                    return true;
                } catch (MongoDBException $e) {
                    return $e->getMessage();
                }
            } else {
                return $response['errmsg'] ?? 'Report Bug Error Code: PMD-RD-32';
            }
        } catch (MongoDBException $e) {
            return $e->getMessage();
        }
    }
    public function renameDatabase($oldDb, $newDb)
    {
        try {
            $client = $this->client;

            if ($this->isDbExist($newDb)) {
                return [
                    'success' => false,
                    'message' => "Database '$newDb' already exists."
                ];
            }


            // Step 1: List all collections in old DB
            $collections = $client->selectDatabase($oldDb)->listCollections();

            foreach ($collections as $collectionInfo) {
                $collectionName = $collectionInfo->getName();

                // Step 2: Fetch all documents from old collection
                $documents = $client->selectCollection($oldDb, $collectionName)->find();

                // Step 3: Insert documents into new collection in new DB
                $newCollection = $client->selectCollection($newDb, $collectionName);
                $batch = [];

                foreach ($documents as $doc) {
                    $batch[] = $doc;
                    if (count($batch) >= 1000) {
                        $newCollection->insertMany($batch);
                        $batch = [];
                    }
                }
                if (!empty($batch)) {
                    $newCollection->insertMany($batch);
                }

                // Optional: Drop old collection (comment out if unsure)
                // $client->selectCollection($oldDb, $collectionName)->drop();
            }

            // Optional: Drop old database completely (comment out if unsure)
            $client->selectDatabase($oldDb)->drop();

            return [
                'success' => true,
                'message' => I18n::t('D_R_S', $oldDb, $newDb)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    protected function getCommandMap()
    {
        return [
            'getCollectionNames' => function ($db) {
                $collections = $this->client->selectDatabase($db)->listCollections();
                $names = [];
                foreach ($collections as $collection) {
                    $names[] = $collection->getName();
                }
                return $names;
            },
            'stats' => function ($db) {
                return $this->client->selectDatabase($db)->command(['dbStats' => 1])->toArray();
            },
            'serverStatus' => function ($db) {
                return $this->client->selectDatabase('admin')->command(['serverStatus' => 1])->toArray();
            },
            'collections' => function ($db) {
                return $this->client->selectDatabase($db)->command(['listCollections' => 1])->toArray();
            },
            'buildInfo' => function ($db) {
                return $this->client->selectDatabase('admin')->command(['buildInfo' => 1])->toArray();
            },
            'find' => function ($db, $collection, $args = []) {
                $coll = $this->client->selectCollection($db, $collection);
                $filter = is_array($args) ? $args : [];
                return $coll->find($filter)->toArray();
            }
        ];
    }
    public function runShellCommand($db, $userCommand)
    {
        $commandMap = $this->getCommandMap();
        $matches = [];

        // Match: db.collection.command({...}) OR db.collection.command()
        if (preg_match('/^db\.([a-zA-Z0-9_]+)\.([a-zA-Z0-9_]+)\((.*)\)$/', $userCommand, $matches)) {
            $collection = $matches[1];
            $commandName = $matches[2];
            $argsRaw = trim($matches[3]);

            if (!isset($commandMap[$commandName])) {
                return ['success' => false, 'message' => "Unsupported command: db.$collection.$commandName()"];
            }

            // Convert Ruby-style => to JSON-style :
            $argsRaw = str_replace('=>', ':', $argsRaw);
            $argsRaw = rtrim($argsRaw, ','); // Remove trailing comma if any

            // ✅ If arguments are empty like in db.test.find()
            if ($argsRaw === '') {
                $json = [];
            } else {
                $json = json_decode($argsRaw, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ['success' => false, 'message' => "Invalid JSON arguments: " . json_last_error_msg()];
                }
            }

            // ✅ Call command with db, collection, args
            return [
                'success' => true,
                'output' => ($commandMap[$commandName])($db, $collection, $json)
            ];
        }

        // Match: db.command()
        if (preg_match('/^db\.([a-zA-Z0-9_]+)\(\)$/', $userCommand, $matches)) {
            $commandName = $matches[1];
            if (!isset($commandMap[$commandName])) {
                return ['success' => false, 'message' => "Unsupported command: db.$commandName()"];
            }

            return ['success' => true, 'output' => ($commandMap[$commandName])($db)];
        }

        return ['success' => false, 'message' => "Invalid or unsupported command syntax"];
    }


    public function runShellCommandWorking($db, $userCommand, $args = [])
    {
        $commandMap = $this->getCommandMap();
        $matches = [];


        if (preg_match('/^db\.([a-zA-Z0-9_]+)\.([a-zA-Z0-9_]+)\s*\(?\s*\)?$/', $userCommand, $matches)) {
            $collection = $matches[1];
            $commandName = $matches[2];

            if (isset($commandMap[$commandName])) {
                $result = ($commandMap[$commandName])($db, $collection, $args);

                return ['success' => true, 'output' => $result];
            } else {
                return ['success' => false, 'message' => "Unsupported command: db.$collection.$commandName()"];
            }
        }

        // Fallback for db.command()
        if (preg_match('/^db\.([a-zA-Z0-9_]+)\s*\(\s*\)$/', $userCommand, $matches)) {
            $commandName = $matches[1];

            if (isset($commandMap[$commandName])) {
                return ['success' => true, 'output' => ($commandMap[$commandName])($db)];
            } else {
                return ['success' => false, 'message' => "Unsupported command: db.$commandName()"];
            }
        }

        return ['success' => false, 'message' => "Invalid or unsupported command syntax"];
    }




    public function repair($db)
    {
        // 'repairDatabase' is not supported in MongoDB newer versions (4.x+)
        return "repairDatabase command is no longer supported in newer MongoDB versions.";
    }

    public function execute($db,  $code, $args = [])
    {
        // MongoDB 4.x+ removed db.eval() which was used via execute.
        return "execute (db.eval) is deprecated in MongoDB and not supported in the modern PHP driver.";
    }

    public function isDbExist($db)
    {
        try {
            // List all database names
            $databases = $this->client->listDatabases();

            foreach ($databases as $databaseInfo) {
                if ($databaseInfo->getName() === $db) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            // Optional: handle/log error
            return false;
        }
    }
}
