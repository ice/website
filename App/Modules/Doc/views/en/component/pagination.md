## Pagination
***
[Ice\Pagination](http://doc.iceframework.org/latest/class/Ice/Pagination.html) provide the multi-page pagination component.

You can simply paginate on `array` or [Ice\Arr](http://doc.iceframework.org/latest/class/Ice/Arr.html) object (which is used during finding models). In the controller:
```php
namespace App\Modules\Frontend\Controllers;

use App\Models\Posts;
use Ice\Pagination;

class PostController extends IndexController
{

    public function indexAction()
    {
        $pagination = new Pagination([
            "data" => Posts::find(),
            // Set the current page from _GET
            "page" => $this->request->getQuery('page', 'int', 1, true)
        ]);

        $this->view->setVars([
            'pagination' => $pagination->calculate()
        ]);
    }
}
```

In the view:
```twig
{# Loop over current page items #}
{% foreach pagination.items as post %}
    {{ post.title }}
{% endforeach %}
{# Display pagination links, eg. Previous 1 [2] 3 4 5 6 Next #}
{{ pagination.minimal() }}
```

#### Using routes
You can use route `/2` instead of query `?page=2` and set the `limit` items per page:
```php
$pagination = new Pagination([
    "data" => Posts::find(),
    "limit" => 20,
    "page" => $this->dispatcher->getParam('page', 'int', 1, true),
    "query" => false
]);
```

But you need to specify routes in the bootstrap file:
```php
di->set('router', function () {
    $router = new Ice\Mvc\Router();

    $router->setRoutes([
        ['GET', '/{controller:post}/{page:\d+}'],
        // ...
    ]);

    return $router;
});
```

#### Templates
In the views you can display different templates:
```twig
{{ pagination.minimal() }}
{# Previous 1 [2] 3 4 5 6 Next #}
```

```twig
{{ pagination.basic() }}
{# First Previous 1 [2] 3 4 5 6 Next Last #}
```

```twig
{{ pagination.floating() }}
{# First Previous 1 2 3 ... 23 24 25 26 [27] 28 29 30 31 ... 48 49 50 Next Last#}
```

> If you have the `i18n` service in the *di*, it'll be used to translate _Previous_, _Next_, etc. words.

##### Pagination properties
You can access to `first`, `previous`, `current`, `next`, `last`, `pages`, `total`, `items` properties and display own pagination info:
```twig
{{ _t('Page: %d/%d (%d items)', [pagination.current, pagination.pages, pagination.total]) }}
{# Page: 2/6 (54 items) #}
```