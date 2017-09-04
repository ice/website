<?php

require_once __DIR__ . '/../root.php';

(new Ice\Loader())
    ->addNamespace('App', __ROOT__ . '/App')
    ->addNamespace('Tests', __ROOT__ . '/tests')
    ->register();
