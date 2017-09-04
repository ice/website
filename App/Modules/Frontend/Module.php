<?php

namespace App\Modules\Frontend;

use Ice\Loader;
use Ice\Di;
use Ice\Mvc\ModuleInterface;

/**
 * Frontend module.
 *
 * @category Module
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class Module implements ModuleInterface
{

    /**
     * Register a specific autoloader for the module
     *
     * @return void
     */
    public function registerAutoloaders()
    {
        (new Loader())
            ->addNamespace(__NAMESPACE__ . '\Controllers', __DIR__ . '/controllers/')
            ->register();
    }

    /**
     * Register specific services for the module
     *
     * @param object $di Dependency injector
     * @return void
     */
    public function registerServices(Di $di)
    {
        // Set default namespace
        $di->dispatcher->setDefaultNamespace(__NAMESPACE__ . '\Controllers');

        // Overwrite views dirs
        $di->view->setViewsDir(__DIR__ . '/views/');
        $di->view->setPartialsDir('../../../views/partials/');
        $di->view->setLayoutsDir('../../../views/layouts/');
        $di->view->setLayout('frontend');
    }
}
