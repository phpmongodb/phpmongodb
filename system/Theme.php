<?php

/**
 * @package PHPmongoDB
 * @version 2.0.0
 */
defined('PMDDA') or die('Restricted access');

class Theme
{

    private static $themePath = '/application/themes/';
    private static $themeUri;
    private static $homeUri;

    public static function getThemePath()
    {
        return self::$themePath . Config::$theme . '/';
    }

    public static function absolutePath()
    {
        return getcwd() . '/' . self::getThemePath();
    }

    public static function relativePath()
    {
        return realpath(self::absolutePath());
    }

    private static function __setThemeUri()
    {
        if (!isset(self::$homeUri)) {
            self::__setHomeUri();
        }

        self::$themeUri = self::$homeUri . self::getThemePath();
    }

    public static function __setHomeUri()
    {
        self::$homeUri = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
            self::$homeUri .= "s";
        }
        self::$homeUri .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            self::$homeUri .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            self::$homeUri .= $_SERVER["SERVER_NAME"];
        }
        self::$homeUri .= str_replace('/index.php', '', $_SERVER['PHP_SELF']);
    }


    public static function getPath()
    {
        if (!isset(self::$themeUri)) {
            self::__setThemeUri();
        }
        return self::$themeUri;
    }
    public static function getVersion($file)
    {
        $file = getcwd() . $file;
        if (file_exists($file)) {
            return filemtime($file);
        }
        return FALSE;
    }

    public static function URL($load = 'Index/Index', $queryString = array())
    {
        if (!self::$homeUri) {
            self::__setThemeUri();
        }
        $url = self::__setThemeUri() . 'index.php?load=' . $load;
        if (is_array($queryString)) {
            foreach ($queryString as $k => $v) {
                if (is_string($v))
                    $url .= '&' . $k . '=' . urlencode($v);
                else
                    $url .= '&' . $k . '=' . $v;
            }
        }
        return $url;
    }

    public static function getHome()
    {
        if (!isset(self::$homeUri)) {
            self::__setHomeUri();
        }
        return self::$homeUri;
    }

    public static function currentURL($start = FALSE)
    {
        $url = self::$homeUri . '/index.php';
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = explode('&', $_SERVER['QUERY_STRING']);
            if (is_array($queryString)) {
                foreach ($queryString as $value) {
                    if ($start) {
                        $rercord = explode('=', $value);
                        if ($rercord[0] == 'start') {
                            continue;
                        }
                    }
                    $url .= (strpos($url, '?') !== false ? '&' : '?') . $value;
                }
            }
        }

        return $url;
    }

    public static function paginationURL($url, $start)
    {
        $url .= (strpos($url, '?') !== false ? '&' : '?') . 'start=' . $start . '&theme=true';
        return $url;
    }


    public static function pagination($total = 0, $split = 10)
    {
        $url = self::currentURL(TRUE);
        $start = (isset($_GET['start']) ? intval($_GET['start']) : 0);

        $current = floor($start / $split); // Current page
        $end = ceil($total / $split);     // Total pages

        if ($total > $split) {
            $page = max(0, $current - 3); // Start page range
            $last = min($end, $page + 7); // End page range

            echo '<div x-data class="mt-6 flex items-center justify-center">';
            echo '<nav class="inline-flex rounded-md shadow-sm border border-gray-200 bg-white" aria-label="Pagination">';

            // Prev Button
            if ($current > 0) {
                echo '<a href="' . self::paginationURL($url, ($current - 1) * $split) . '" 
                    class="px-3 py-2 border-r border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Prev
                  </a>';
            }

            // Page Numbers
            for (; $page < $last; $page++) {
                $isActive = ($current == $page);
                echo '<a href="' . (!$isActive ? self::paginationURL($url, $page * $split) : 'javascript:void(0)') . '" 
                    class="px-3 py-2 text-sm font-medium border-r border-gray-200 '
                    . ($isActive ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-50') . '">
                    ' . ($page + 1) . '
                  </a>';
            }

            // Next Button
            if (($current + 1) * $split < $total) {
                echo '<a href="' . self::paginationURL($url, ($current + 1) * $split) . '" 
                    class="px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    Next
                  </a>';
            }

            echo '</nav>';
            echo '</div>';
        }
    }
}
