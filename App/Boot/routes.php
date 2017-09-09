<?php

return [
    ['*', '/{module:doc}[/{controller:[a-z-]+}[/{action:[a-z-]+}[/{param}]]]'],
    ['*', '/{controller:[a-z-]+}[/{action:[a-z-]+}[/{param}]]'],
    ['GET', '/{action:license|changelog}', ['controller' => 'info']],
    ['GET', ''],
];
