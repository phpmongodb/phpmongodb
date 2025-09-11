<?php

/**
 * @package PHPmongoDB
 * @version 2.0.0
 * @link https://github.com/phpmongodb/phpmongodb
 * @author Nanhe Kumar <nanhe.kumar@gmail.com>
 */
defined('PMDDA') or die('Restricted access');

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\Int32;
use MongoDB\BSON\Int64;

class Collection extends Model
{
    public function createCollection($db, $collection, $capped = false, $size = 0, $max = 0)
    {
        try {
            $options = [];

            if ($capped) {
                $options['capped'] = true;

                // Set minimum required size if not provided
                $options['size'] = ($size >= 1) ? $size : 1024;

                if ($max > 0) {
                    $options['max'] = $max;
                }
            }

            $this->client->selectDatabase($db)->createCollection($collection, $options);
            return true;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function renameCollection($oldCollection, $newCollection, $dbFrom, $dbTo = null)
    {
        try {
            if (!$dbTo) {
                $dbTo = $dbFrom;
            }

            // Validate new collection name
            if (!preg_match('/^[a-zA-Z0-9._-]+$/', $newCollection)) {
                return [
                    'success' => false,
                    'message' => "Invalid collection name '$newCollection'. Only alphanumeric, dot, dash and underscore are allowed."
                ];
            }

            $command = new MongoDB\Driver\Command([
                'renameCollection' => "$dbFrom.$oldCollection",
                'to' => "$dbTo.$newCollection",
                'dropTarget' => false
            ]);

            $this->manager->executeCommand('admin', $command);

            return [
                'success' => true,
                'message' => "Collection '$oldCollection' in DB '$dbFrom' renamed to '$newCollection' in DB '$dbTo'"
            ];
        } catch (MongoDB\Driver\Exception\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }



    public function removeCollection($db, $collection, $criteria = [])
    {
        try {
            return $this->client->selectDatabase($db)->selectCollection($collection)->deleteMany($criteria);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function dropCollection($db, $collection)
    {
        try {
            return $this->client->selectDatabase($db)->selectCollection($collection)->drop();
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function totalRecord($db, $collection, $query = false, $format = 'array')
    {
        try {
            $coll = $this->client->selectDatabase($db)->selectCollection($collection);
            if ($format === 'json') {
                return $coll->countDocuments(json_decode($query, true));
            } elseif (is_array($query)) {
                return $coll->countDocuments($query);
            } else {
                return $coll->countDocuments();
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function getIndexes($db,  $collection)
    {

        try {
            return ['success' => true, 'indexes' => iterator_to_array(
                $this->client->selectDatabase($db)->selectCollection($collection)->listIndexes()
            )];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function listIndexes($db,  $collection)
    {
        try {

            $cursor = $this->client->selectDatabase($db)->selectCollection($collection)->listIndexes();
            $indexes = [];
            foreach ($cursor as $indexInfo) {
                $reflection = new ReflectionClass($indexInfo);
                $props = $reflection->getProperties();
                $indexProps = [];
                foreach ($props as $prop) {
                    $prop->setAccessible(true);
                    $indexProps[$prop->getName()] = $prop->getValue($indexInfo);
                }
                $indexes[] = $indexProps['info'];
            }
            return [
                'success' => true,
                'indexes' => $indexes
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }



    public function dropIndex($db, $collection, $name)
    {
        try {
            // Ensure $name is a string
            if (!is_string($name)) {
                throw new Exception("Index name must be a string.");
            }

            $result = $this->client->selectDatabase($db)->selectCollection($collection)->dropIndex($name);

            return ['success' => true, 'message' => $result];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function createIndex($db,  $collection, $key, $options = [])
    {
        try {
            return $this->client->selectDatabase($db)->selectCollection($collection)->createIndex($key, $options);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    protected function getQueryForId($id, $idType = 'object',  $format = 'array')
    {
        /*
        if ($format === 'json') {
            return match ($idType) {
                'MongoId' => '{"_id": {"$oid": "' . $id . '"}}',
                'MongoDB\BSON\ObjectId' =>  '{"_id": {"$oid": "' . $id . '"}}',
                'MongoDate' => '{"_id": {"$date": "' . $id . '"}}',
                'integer' => '{"_id": {"$numberInt": "' . $id . '"}}',
                'double' => '{"_id": {"$numberLong": "' . $id . '"}}',
                default => '{"_id": "' . $id . '"}',
            };
        }
        */

        return match ($idType) {
            'MongoId' => ['_id' => new ObjectId($id)],
            'MongoDB\BSON\ObjectId' => ['_id' => new ObjectId($id)],
            'MongoDate' => ['_id' => new UTCDateTime((int) $id)],
            'integer' => ['_id' => new Int32((int) $id)],
            'double' => ['_id' => new Int64((int) $id)],
            default => ['_id' => $id],
        };
    }

    public function removeById($db, $collection, $id, $idType = 'object')
    {
        try {
            $query = $this->getQueryForId($id, $idType);
            $result = $this->client->selectDatabase($db)->selectCollection($collection)->deleteOne($query);

            if ($result->isAcknowledged() && $result->getDeletedCount() > 0) {
                return ['success' => true, 'message' => 'Document deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'No document found with the given ID'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function findById($db, $collection, $id,  $idType = 'object')
    {
        try {
            $query = $this->getQueryForId($id, $idType);
            $bson = $this->client->selectDatabase($db)->selectCollection($collection)->findOne($query);
            if ($bson instanceof \MongoDB\Model\BSONDocument || $bson instanceof \MongoDB\Model\BSONArray) {
                return json_decode(json_encode($bson), true);
            } else {
                return false;
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function updateById($db, $collection, $id, $data, $format = 'array', $idType = 'object')
    {
        try {
            $filter = $this->getQueryForId($id, $idType, $format);
            $coll = $this->client->selectDatabase($db)->selectCollection($collection);

            if ($format === 'json') {
                $update = ['$set' => json_decode($data, true)];
            } else if ($format === 'array') {
                $update = ['$set' => $data];
            } else {
                return [
                    'success' => false,
                    'message' => 'Unsupported format: ' . $format
                ];
            }

            $result = $coll->updateOne($filter, $update);

            return [
                'success' => true,
                'message' => 'Document updated successfully',
                'matchedCount' => $result->getMatchedCount(),
                'modifiedCount' => $result->getModifiedCount()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
