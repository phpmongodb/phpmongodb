<?php

/**
 * @package PHPmongoDB
 * @version 1.0.0
 */
defined('PMDDA') or die('Restricted access');

class File
{

    public $path;
    public $file;
    public $success = true;
    public $message;

    public function __construct($path = NULL, $file = NULL)
    {
        $this->path = $path;
        $this->file = $file;
    }

    public function write($content)
    {

        if (!$handle = fopen($this->path . $this->file, 'a')) {
            $this->success = false;
            $this->message = 'Cannot open file (' . $this->path . $this->file . ')';
            exit;
        }

        if (fwrite($handle, $content) === FALSE) {
            $this->success = false;
            $this->message = 'Cannot write to file (' . $this->path . $this->file . ')';
            exit;
        }
        $this->success = true;
        $this->message = 'Success, wrote (' . $content . ') to file (' . $this->path . $this->file . ')';
        fclose($handle);
    }

    public function download($file = FALSE, $path = FALSE)
    {
        if (!$file && !$path) {
            $file = $this->path . $this->file;
        } else if (!$path) {
            $file = $this->path . $file;
        } else if (!$file) {
            $file = $path . $this->file;
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }

    public function delete($file = FALSE, $path = FALSE)
    {
        if (file_exists($this->path . $this->file)) {
            unlink($this->path . $this->file);
        }
    }

    /* creates a compressed zip file */

    function createZip($files = array(), $destination = '', $overwrite = false, $path = false)
    {
        if (!$path) {
            $path = $this->path;
        }

        // Ensure path ends with a slash
        if (substr($path, -1) !== DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }

        $destination = $path . $destination;

        // Check if destination file exists and overwrite is false
        if (file_exists($destination)) {
            if ($overwrite) {
                unlink($destination); // Delete old zip file
            } else {
                $this->message = "❌ Zip file already exists and overwrite is false";
                return false;
            }
        }

        // Validate input files
        $validFiles = array();
        if (is_array($files)) {
            foreach ($files as $file) {
                $fullPath = $path . $file;
                if (file_exists($fullPath)) {
                    $validFiles[] = $file;
                }
            }
        }

        // Proceed if we have valid files
        if (count($validFiles)) {
            $zip = new ZipArchive();
            $result = $zip->open($destination, ZIPARCHIVE::CREATE);

            if ($result !== true) {
                $this->message = "❌ Unable to create the zip: error code $result";
                return false;
            }

            // Add valid files to the archive
            foreach ($validFiles as $file) {
                $zip->addFile($path . $file, $file);
            }

            $zip->close();

            // Confirm the zip file was created
            if (file_exists($destination)) {
                return true;
            } else {
                $this->message = "❌ Zip file creation failed after close()";
                return false;
            }
        } else {
            $this->message = "❌ No valid files found to zip.";
            return false;
        }
    }
}
