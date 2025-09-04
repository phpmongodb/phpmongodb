<?php

/**
 * @package PHPmongoDB
 * @version 1.0.0
 */
defined('PMDDA') or die('Restricted access');

class Cryptography
{

    protected $data;

    public function decode($data, $format = 'array')
    {
        if ($format == 'json') {
            return $this->decodeArray($data);
        } else {
            return $this->decodeCursor($data);
        }
    }

    public function decodeArray($data)
    {
        if (!is_array($data))
            return false;
        foreach ($data as $document) {

            $this->data['document'][] = $document;
            $this->data['json'][] = $this->highlight($this->arrayToJSON($document));
            $this->data['array'][] = $this->highlight($this->arrayToString($document));
        }
        return $this->data;
    }

    public function decodeCursor($cursor)
    {
        foreach ($cursor as $document) {
            // $idValue = (string) iterator_to_array($document)['_id'];
            $idObject = iterator_to_array($document)['_id'];
            $idValue = (string) $idObject;
            $idClass = is_object($idObject) ? get_class($idObject) : 'string';
            $index = $idValue . '-' . $idClass;

            $this->data['document'][$index] = $document;
            $this->data['jsonv2'][$index] = $this->formatMongoOutput($document);
            $this->data['json'][$index] = $this->arrayToJSON($document);
            $this->data['array'][$index] = $this->highlight($this->arrayToString($document));
        }

        return $this->data;
    }


    public function highlight($string)
    {
        $string = highlight_string("<?php " . $string, true);
        $find = array('<span style="color: #0000BB">&lt;?php&nbsp;</span>', '&lt;?php&nbsp;');
        $string = str_replace($find, '', $string);
        return $string;
    }

    public function arrayToString($array, $tab = "")
    {
        // BSONDocument ya BSONArray ko PHP associative array me convert karna
        if ($array instanceof \MongoDB\Model\BSONDocument || $array instanceof \MongoDB\Model\BSONArray) {
            $array = json_decode(json_encode($array), true);
        }
        $string = 'array(';
        foreach ($array as $key => $value) {
            $string .= "\n\t" . $tab;
            if (gettype($value) === 'array') {
                $string .= "\"$key\"" . '=>' . $this->arrayToString($value, "$tab\t");
            } else if (is_object($value)) {
                $string .= "\"$key\"" . '=>' . $this->objectToString($value);
            } else if (is_numeric($value)) {
                $string .= "\"$key\"" . '=>' . $value;
            } else {
                $string .= "\"$key\"" . '=>' . "\"$value\"";
            }
            $string .= ',';
        }
        return $string .= "\n" . $tab . ')';
    }

    public function objectToString($object)
    {
        switch (get_class($object)) {
            case "MongoId":
                $string = 'new MongoId("' . $object->__toString() . '")';
                break;
            case "MongoDB\BSON\ObjectId":
                $string = 'new ObjectId("' . $object->__toString() . '")';
                break;
            case "MongoInt32":
                $string = 'new MongoInt32(' . $object->__toString() . ')';
                break;
            case "MongoInt64":
                $string = 'new MongoInt64(' . $object->__toString() . ')';
                break;
            case "MongoDate":
                $string = 'new MongoDate(' . $object->sec . ', ' . $object->usec . ')';
                break;
            case "MongoRegex":
                $string = 'new MongoRegex(\'/' . $object->regex . '/' . $object->flags . '\')';
                break;
            case "MongoTimestamp":
                $string = 'new MongoTimestamp(' . $object->sec . ', ' . $object->inc . ')';
                break;
            case "MongoMinKey":
                $string = 'new MongoMinKey()';
                break;
            case "MongoMaxKey":
                $string = 'new MongoMaxKey()';
                break;
            case "MongoCode":
                //$string = 'new MongoCode("' . addcslashes($object->code, '"') . '", ' . var_export($object->scope, true) . ')';
                break;
            default:
                if (method_exists($object, "__toString")) {
                    return $object->__toString();
                }
        }
        return isset($string) ? $string : FALSE;
    }

    function arrayToJSON($array, $tab = "")
    {
        // BSONDocument ya BSONArray ko PHP associative array me convert karna
        if ($array instanceof \MongoDB\Model\BSONDocument || $array instanceof \MongoDB\Model\BSONArray) {
            $array = json_decode(json_encode($array), true);
        }

        if (!is_array($array)) {
            return false;
        }
        $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));
        if ($associative) {

            $construct = array();
            foreach ($array as $key => $value) {

                if (is_numeric($key)) {
                    $key = "key_$key";
                }
                $key = '"' . addslashes($key) . '"';

                if (is_array($value)) {
                    /*
                    //if you want to show like ObjectId('68b43228c3830c119a021978')
                    if (isset($value['$oid'])) {
                        $value = "ObjectId('" . $value['$oid'] . "')";
                    } else {
                        $value = $this->arrayToJSON($value, "$tab\t");
                    }
                     */
                    $value = $this->arrayToJSON($value, "$tab\t");
                } else if (is_object($value)) {
                    $value = $this->objectToJSON($value);
                } else if (is_double($value)) {
                    $value = "NumberLong(" . addslashes($value) . ")";
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = '"' . addslashes($value) . '"';
                }

                $construct[] = "\n\t$tab" . "$key: $value";
            }


            $result = "{" . implode(",", $construct) . "\n$tab}";
        } else { // If the array is a vector (not associative):
            $construct = array();
            foreach ($array as $value) {

                if (is_array($value)) {
                    $value = $this->arrayToJSON($value, "$tab\t");
                } else if (is_object($value)) {
                    $value = $this->objectToJSON($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "'" . addslashes($value) . "'";
                }


                $construct[] = $value;
            }


            $result = "[ " . implode(", ", $construct) . " ]";
        }

        return $result;
    }

    public function objectToJSON($object)
    {

        switch (get_class($object)) {
            case "MongoId":
                $json = 'ObjectId("' . $object->__toString() . '")';
                break;
            case "MongoDB\BSON\ObjectId":
                $json = 'ObjectId("' . $object->__toString() . '")';
                break;
            case "MongoInt32":
                $json = 'NumberInt(' . $object->__toString() . ')';
                break;
            case "MongoInt64":
                $json = 'NumberLong(' . $object->__toString() . ')';
                break;
            case "MongoDate":
                $timezone = @date_default_timezone_get();
                date_default_timezone_set("UTC");
                $json = "ISODate(\"" . date("Y-m-d", $object->sec) . "T" . date("H:i:s.", $object->sec) . ($object->usec / 1000) . "Z\")";
                date_default_timezone_set($timezone);
                break;
            case "MongoTimestamp":
                $json = call_user_func($jsonService, array(
                    "t" => $object->inc * 1000,
                    "i" => $object->sec
                ));
                break;
            case "MongoMinKey":
                $json = call_user_func($jsonService, array('$minKey' => 1));
                break;
            case "MongoMaxKey":
                $json = call_user_func($jsonService, array('$minKey' => 1));
                break;
            case "MongoCode":
                $json = $object->__toString();
                break;
            default:
                if (method_exists($object, "__toString")) {
                    return $object->__toString();
                }
        }
        return $json;
    }

    public function stringToArray($string)
    {
        $string = " return " . $string . ";";
        if (function_exists("token_get_all")) {
            $php = "<?php\n" . $string . "\n?>";
            $tokens = token_get_all($php);
            foreach ($tokens as $token) {
                $type = $token[0];
                if (is_long($type)) {
                    if (in_array($type, array(
                        T_OPEN_TAG,
                        T_RETURN,
                        T_WHITESPACE,
                        T_ARRAY,
                        T_LNUMBER,
                        T_DNUMBER,
                        T_CONSTANT_ENCAPSED_STRING,
                        T_DOUBLE_ARROW,
                        T_CLOSE_TAG,
                        T_NEW,
                        T_DOUBLE_COLON
                    ))) {
                        continue;
                    }

                    if ($type == T_STRING) {
                        $keyword = strtolower($token[1]);
                        if (in_array($keyword, array(
                            "mongoid",
                            "MongoDB\BSON\ObjectId",
                            "mongocode",
                            "mongodate",
                            "mongoregex",
                            "mongobindata",
                            "mongoint32",
                            "mongoint64",
                            "mongodbref",
                            "mongominkey",
                            "mongomaxkey",
                            "mongotimestamp",
                            "true",
                            "false",
                            "null",
                            "__set_state"
                        ))) {
                            continue;
                        }
                    }
                    exit("For your security, we stoped data parsing at '(" . token_name($type) . ") " . $token[1] . "'.");
                }
            }
        }


        $array = @eval($string);
        if (error_get_last())
            return false;
        return $array;
    }

    public function executeAND($query)
    {
        $key = array_search('$and', $query);

        if (!$key)
            return array_values($query);
        if ($query[$key - 2] == '=') {
            $left = array($query[$key - 3] => $query[$key - 1]);
        } else {
            $left = array($query[$key - 3] => array($query[$key - 2] => $query[$key - 1]));
        }
        if ($query[$key + 2] == '=') {
            $right = array($query[$key + 1] => $query[$key + 3]);
        } else {
            $right = array($query[$key + 1] => array($query[$key + 2] => $query[$key + 3]));
        }
        $and = array('$and' => array($left, $right));
        for ($i = $key - 3; $i <= $key + 3; $i++) {
            unset($query[$i]);
        }
        $query[$key + 3] = $and;
        ksort($query);
        return $this->executeAND(array_values($query));
    }

    public function executeOR($query)
    {
        $key = array_search('$or', $query);

        if (!$key)
            return array_values($query);
        if (!is_array($query[$key - 1])) {
            if ($query[$key - 2] == '=') {
                $left = array($query[$key - 3] => is_numeric($query[$key - 1]) ? doubleval($query[$key - 1]) : $query[$key - 1]);
            } else {
                $left = array($query[$key - 3] => array($query[$key - 2] => $query[$key - 1]));
            }
            for ($i = $key - 3; $i < $key; $i++) {
                unset($query[$i]);
            }
        } else {
            $left = $query[$key - 1];
            unset($query[$key - 1]);
        }
        if (!is_array($query[$key + 1])) {
            if ($query[$key + 2] == '=') {
                $right = array($query[$key + 1] => is_numeric($query[$key + 3]) ? doubleval($query[$key + 3]) : $query[$key + 3]);
            } else {
                $right = array($query[$key + 1] => array($query[$key + 2] => $query[$key + 3]));
            }

            for ($i = $key + 1; $i <= $key + 3; $i++) {
                unset($query[$i]);
            }
        } else {
            $right = $query[$key + 1];
            unset($query[$key + 1]);
        }
        $query[$key] = array('$or' => array($left, $right));;
        ksort($query);
        return $this->executeOR(array_values($query));
    }

    public function executeValue($query, $key)
    {
        if ($query[$key - 1] == '=') {
            if (is_numeric($query[$key])) {
                $query = array('$or' => array(array($query[$key - 2] => doubleval($query[$key])), array($query[$key - 2] => $query[$key])));
            } else {
                $query = array($query[$key - 2] => $query[$key]);
            }
        } else {
            if (is_numeric($query[$key])) {
                $query = array('$or' => array(array($query[$key - 2] => array($query[$key - 1] => doubleval($query[$key]))), array($query[$key - 2] => array($query[$key - 1] => $query[$key]))));
            } else {
                $query = array('$or' => array(array($query[$key - 2] => array($query[$key - 1] => doubleval($query[$key]))), array($query[$key - 2] => array($query[$key - 1] => $query[$key]))));
            }
        }
        return $query;
    }


    public function mixedToJson($data = NULL, $highlight = FALSE)
    {
        if (is_array($data)) {
            $json = $this->arrayToJSON($data);
        } elseif (is_object($data)) {
            $json = $this->objectToJSON($data);
        } else if (is_bool($data)) {
            $json = $data ? 'true' : 'false';
        } else {
            $json = $data;
        }
        if ($highlight)
            $json = $this->highlight($json);
        return $json;
    }

    function formatMongoOutput($documents)
    {
        if ($documents instanceof MongoDB\Model\BSONDocument) {
            $documents = [$documents];
        }

        $output = "[\n";

        foreach ($documents as $doc) {
            if ($doc instanceof MongoDB\Model\BSONDocument) {
                $assoc = $doc->getArrayCopy();
                $output .= "  {\n";
                foreach ($assoc as $key => $value) {
                    if ($value instanceof MongoDB\BSON\ObjectId) {
                        $formattedValue = "ObjectId('" . $value->__toString() . "')";
                    } elseif (is_string($value)) {
                        $formattedValue = "'" . $value . "'";
                    } elseif (is_array($value)) {
                        $formattedValue = json_encode($value);
                    } elseif (is_bool($value)) {
                        $formattedValue = $value ? 'true' : 'false';
                    } elseif (is_null($value)) {
                        $formattedValue = 'null';
                    } else {
                        $formattedValue = $value;
                    }

                    $output .= "    $key: $formattedValue,\n";
                }
                $output = rtrim($output, ",\n") . "\n";
                $output .= "  },\n";
            } else {
                // Handle string/int/bool/etc.
                if (is_string($doc)) {
                    $formattedValue = "'" . $doc . "'";
                } elseif (is_bool($doc)) {
                    $formattedValue = $doc ? 'true' : 'false';
                } elseif (is_null($doc)) {
                    $formattedValue = 'null';
                } else {
                    $formattedValue = $doc;
                }
                $output .= "  $formattedValue,\n";
            }
        }

        $output = rtrim($output, ",\n") . "\n";
        $output .= "]";

        return $output;
    }



    public function debug($a)
    {
        echo "<pre>";
        print_r($a);
        echo "<pre>";
    }
}
