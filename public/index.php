<?php

/**
 * Set the PHP error reporting level.
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 */
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../root.php';
require_once __DIR__ . '/../autoload.php';

// Initialize website, handle a MVC request and display the HTTP response body
echo (new App\Boot\Website((new Ice\Di())->errors('App\Boot\Error')))
    ->initialize()
    ->handle();
