<?php

return [
    // Routes for doc module
    ['GET', '/{module:doc}/{controller:[a-z]+}/{action:[a-z]+}/{param}'],
    ['GET', '/{module:doc}/{controller:[a-z]+}/{action:[a-z]+[/]?}'],
    ['GET', '/{module:doc}/{controller:[a-z]+[/]?}'],
    ['GET', '/{module:doc+[/]?}'],
    // Routes for default module
    ['GET', '/{controller:[a-z]+}/{action:[a-z]+}/{param}'],
    ['GET', '/{controller:[a-z]+}/{action:[a-z]+[/]?}'],
    ['GET', '/{controller:[a-z]+[/]?}'],
    ['GET', ''],
    ['POST', '/{controller:[a-z]+}/{action:[a-z]+[/]?}'],
];
