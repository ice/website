<?php

namespace App;

use Ice\Config\Ini as Config;
use Ice\I18n;
use Ice\Crypt;
use Ice\Loader;
use Ice\Flash;
use Ice\Filter;
use Ice\Session;
use Ice\Http\Request;
use Ice\Http\Response;
use Ice\Cookies;
use Ice\Mvc\Url;
use Ice\Mvc\Router;
use Ice\Mvc\Dispatcher;
use Ice\Tag;
use Ice\Mvc\App;
use Ice\Mvc\View;
use Ice\Mvc\View\Engine\Sleet;

/**
 * Ice framework website
 *
 * @package     Ice/Website
 * @category    Bootstrap
 */
class Website extends App
{

    /**
     * Initialize the application
     *
     * @return object Website
     */
    public function run()
    {
        // Register an autoloader
        $this->registerLoader();

        // Load the config
        $config = new Config(__DIR__ . '/config.ini');

        // Set environment settings
        $config->set('env', (new Config(__DIR__ . '/env.ini'))->{$config->app->env});
        $this->config = $config;

        // Register modules
        $modules = [
            'frontend' => [
                'namespace' => 'App\Modules\Frontend',
                'path' => __DIR__ . '/modules/frontend/'
            ],
            'doc' => [
                'namespace' => 'App\Modules\Doc',
                'path' => __DIR__ . '/modules/doc/'
            ]
        ];
        $this->setModules($modules);
        $this->setDefaultModule('frontend');

        // Register services
        $this->registerServices();

        return $this;
    }

    /**
     * Register autoloaders
     *
     * @return void
     */
    public function registerLoader()
    {
        (new Loader())
            ->addNamespace('App\Libraries', __DIR__ . '/common/lib')
            ->addNamespace('App\Extensions', __DIR__ . '/common/ext')
            ->register();
    }

    /**
     * Register services in the dependency injector
     *
     * @return void
     */
    public function registerServices()
    {
        $config = $this->config;
        $this->di->config = $config;
        $this->di->crypt = new Crypt($config->crypt->key);
        $this->di->session = new Session();
        $this->di->request = new Request();
        $this->di->cookies = new Cookies($config->cookie->salt);
        $this->di->response = new Response();

        $this->di->i18n = new I18n($config->i18n->toArray());

        // Set the url service
        $this->di->set('url', function () use ($config) {
            $url = new Url();
            $url->setBaseUri($config->app->base_uri);
            $url->setStaticUri($config->app->static_uri);
            return $url;
        });

        $this->di->filter = new Filter();
        $this->di->tag = new Tag();
        $this->di->flash = new Flash();

        // Set the dispatcher service
        $this->di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setSilent(true);
            return $dispatcher;
        });

        $routes = require_once __DIR__ . '/routes.php';

        // Set the router service
        $this->di->set('router', function () use ($routes) {
            $router = new Router();
            $router->setDefaultModule('frontend');
            $router->setSilent(true);
            $router->setRoutes($routes);
            return $router;
        });

        // Set the view service
        $this->di->set('view', function () use ($config) {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/common/views/');

            // Options for Sleet template engine
            $sleet = new Sleet($view, $this->di);
            $sleet->setOptions([
                'compileDir' => __DIR__ . '/common/tmp/sleet/',
                'trimPath' => __DIR__,
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
    }

    /**
     * Overwrite response by display pretty view
     *
     * @param string $method
     * @param string $uri
     * @return object response
     */
    public function handle($method = null, $uri = null)
    {
        $view = $this->di->view;
        $assets['styles'] = [
            $this->di->tag->link(['css/bootstrap.min.css?v=3.3.1']),
            $this->di->tag->link(['css/fonts.css']),
            $this->di->tag->link(['css/app.css'])
        ];

        // Display pretty view if response is Client/Server Error and silet option is true
        $this->di->hook('app.after.handle', function ($response) use ($view, $assets) {
            $status = $response->getStatus();

            if ($response->isClientError() || $response->isServerError()) {
                $view->setVars([
                    'code' => $status,
                    'message' => $response->getMessage($status)
                ]);
                switch ($status) {
                    case 404:
                        $view->setVar('icon', 'road');
                        break;
                    case 508:
                        $view->setVar('icon', 'repeat');
                        break;

                    default:
                        $view->setVar('icon', 'remove');
                        break;
                }
                $response->setBody($view->layout('error', $assets));
            }

            return $response;
        });

        return parent::handle($method, $uri);
    }
}
