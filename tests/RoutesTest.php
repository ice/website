<?php

namespace Tests;

use PHPUnit_Framework_TestCase as PHPUnit;
use Ice\Di;
use Ice\Mvc\Router;
use App\Routes;

class RoutesTest extends PHPUnit
{

    /**
     * Test route matching for universal routes and GET method
     *
     * @dataProvider GETrouteProvider
     */
    public function testUniversalGET($pattern, $expected)
    {
        $di = new Di();
        $router = new Router();
        $routes = require __DIR__ . '/../app/routes.php';
        $router->setRoutes($routes);
        $router->setDefaultModule('frontend');
        $return = $router->handle('GET', $pattern);

        $this->assertEquals('GET', $router->getMethod());

        if (is_array($return)) {
            $this->assertEquals($expected, [$router->getModule(), $router->getHandler(), $router->getAction(), $router->getParams()], $pattern);
        } else {
            $this->assertEquals($expected, null, "The route wasn't matched by any route");
        }
    }

    /**
     * Test route matching for universal routes and POST method
     *
     * @dataProvider POSTrouteProvider
     */
    public function testUniversalPOST($pattern, $expected)
    {
        $di = new Di();
        $router = new Router();
        $routes = require __DIR__ . '/../app/routes.php';
        $router->setRoutes($routes);
        $router->setDefaultModule('frontend');
        $return = $router->handle('POST', $pattern);

        $this->assertEquals('POST', $router->getMethod());

        if (is_array($return)) {
            $this->assertEquals($expected, [$router->getModule(), $router->getHandler(), $router->getAction(), $router->getParams()], $pattern);
        } else {
            $this->assertEquals($expected, null, "The route wasn't matched by any route");
        }
    }

    /**
     * Routes provider for GET method
     * [pattern, expected route: [module, handler, action, [params]]]
     *
     * @return array
     */
    public function GETrouteProvider()
    {
        return [
            ['', ['frontend', 'index', 'index', []]],
            ['/index', ['frontend', 'index', 'index', []]],
            ['/index/index', ['frontend', 'index', 'index', []]],
            ['/index/test', ['frontend', 'index', 'test', []]],
            ['/info', ['frontend', 'info', 'index', []]],
            ['/info/', ['frontend', 'info', 'index', []]],
            ['/info/download', ['frontend', 'info', 'download', []]],

            ['/lang/set/en-gb', ['frontend', 'lang', 'set', ['param' => 'en-gb']]],

            ['/doc', ['doc', 'index', 'index', []]],
            ['/doc/index', ['doc', 'index', 'index', []]],
            ['/doc/index/index', ['doc', 'index', 'index', []]],
            ['/doc/index/test', ['doc', 'index', 'test', []]],
            
            ['/doc/install', ['doc', 'install', 'index', []]],
            ['/doc/install/requirements', ['doc', 'install', 'requirements', []]],
            ['/doc/install/requirements/php', ['doc', 'install', 'requirements', ['param' => 'php']]],
        ];
    }

    /**
     * Routes provider for POST method
     * [pattern, expected route: [module, handler, action, [params]]]
     *
     * @return array
     */
    public function POSTrouteProvider()
    {
        return [
            ['/info/contact', ['frontend', 'info', 'contact', []]],
        ];
    }

}
