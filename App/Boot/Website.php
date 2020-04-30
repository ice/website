<?php

namespace App\Boot;

use Ice\Config\Ini;
use Ice\I18n;
use Ice\Mvc\Url;
use Ice\Mvc\FastRouter as Router;
use Ice\Mvc\Dispatcher;
use Ice\Mvc\App;
use Ice\Mvc\View;
use Ice\Mvc\View\Engine\Sleet;

/**
 * Ice framework website.
 *
 * @category Boot
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class Website extends App
{
    /**
     * Meta description.
     *
     * @var string
     */
    public $description;

    /**
     * Meta keywords.
     *
     * @var string
     */
    public $keywords;

    /**
     * Initialize the application.
     *
     * @return object App
     */
    public function initialize()
    {
        // Handle the errors by Error class
        $this->di->errors('App\Boot\Error');

        // Load the config
        $config = new Ini(__ROOT__ . '/App/cfg/config.ini');

        // Set environment settings
        $config->set('env', (new Ini(__ROOT__ . '/App/cfg/env.ini'))->{$config->app->env});
        $config->set('assets', new Ini(__ROOT__ . '/App/cfg/assets.ini'));

        // Register modules
        $this->setModules([
            'frontend' => ['namespace' => 'App\Modules\Frontend'],
            'doc' => ['namespace' => 'App\Modules\Doc']
        ]);

        // Register services
        $this->di->config = $config;
        $this->di->crypt->setKey($config->crypt->key);
        $this->di->cookies->setSalt($config->cookie->salt);
        $this->di->i18n = new I18n($config->i18n->toArray());

        // Set the url service
        $this->di->set('url', function () use ($config) {
            $url = new Url();
            $url->setBaseUri($config->app->base_uri);
            $url->setStaticUri($config->app->static_uri);
            return $url;
        });

        // Set the dispatcher service
        $this->di->set('dispatcher', function () use ($config) {
            $dispatcher = new Dispatcher();
            $dispatcher->setSilent($config->env->silent->dispatcher);
            return $dispatcher;
        });

        $routes = include_once __DIR__ . '/routes.php';

        // Set the router service
        $this->di->set('router', function () use ($routes, $config) {
            $router = new Router();
            $router->setDefaultModule('frontend');
            $router->setSilent($config->env->silent->router);
            $router->setRoutes($routes);
            return $router;
        });

        // Set the view service
        $this->di->set('view', function () use ($config) {
            $view = new View();
            $view->setViewsDir(__ROOT__ . '/App/views/');

            // Options for Sleet template engine
            $sleet = new Sleet($view, $this->di);
            $sleet->setOptions([
                'compileDir' => __ROOT__ . '/App/tmp/sleet/',
                'trimPath' => __ROOT__,
                'compile' => $config->env->sleet->compile
            ]);

            // Set template engines
            $view->setEngines([
                '.md' => 'App\Libraries\Markdown',
                '.sleet' => $sleet,
                '.phtml' => 'Ice\Mvc\View\Engine\Php'
            ]);

            return $view;
        });

        return $this;
    }

    /**
     * Overwrite response by display pretty view.
     *
     * @param string $method Request method
     * @param string $uri    Uri
     *
     * @return object response
     */
    public function handle($method = null, $uri = null): \Ice\Http\Response\ResponseInterface
    {
        $di = $this->di;

        $this->di->hook('app.after.handle', function ($response) use ($di) {
            // Display pretty view for some response codes
            if (!$response->isInformational() && !$response->isSuccessful() && !$response->isRedirect()) {
                $code = $response->getStatus();
                $response->setBody(Error::view($di, $code, $response->getMessage($code)));
            }
        });

        return parent::handle($method, $uri);
    }
}
