<?php

/**
 * @package PHPmongoDB
 * @version 1.0.0
 * @link http://www.phpmongodb.org
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
            $collection = $this->client->selectDatabase($name)->createCollection('default');
            return ['success' => true, 'message' => "Database '$name' created with collection 'default'"];
        } catch (MongoDB\Exception\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }



    public function dropDatabase($db)
    {
        try {
            $this->client->selectDatabase($db)->drop();
            return true;
        } catch (MongoDBException $e) {
            return $e->getMessage();
        }
    }

    public function renameDatabase($oldDB,  $newDB)
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
        $databases = $this->listDatabases();
        $dbList = array_column($databases, 'name');
        $session = Application::getInstance('Session');
        $tmpDbList = $session->databases ?? [];

        $allDbs = array_merge($dbList, $tmpDbList);
        return in_array($db, $allDbs, true);
    }
}
