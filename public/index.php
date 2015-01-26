<?php

defined('__ROOT__') or
    /**
     * Full path to the docroot
     */
    define('__ROOT__', dirname(__DIR__));

if (!function_exists('__')) {
    /**
     * Translation function
     * @see Ice\I18n translate()
     */
    function __($string, array $values = null, $lang = null)
    {
        return \Ice\I18n::fetch()->translate($string, $values, $lang);
    }

}

// Register App namespace
(new Ice\Loader())
    ->addNamespace('App', __ROOT__ . '/app')
    ->register();

// Include composer's autolader
include_once __ROOT__ . '/vendor/autoload.php';

// Initialize website, handle a MVC request and display the HTTP response body
echo (new App\Website((new Ice\Di())->errors('App\Error')))->run()->handle();
