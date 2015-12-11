## Pagination
***
[Ice\Pagination](http://doc.iceframework.org/latest/class/Ice/Pagination.html) dostarcza komponent do stronicowania danych na wiele stron.

Możesz prosto stronicować tablicę `array` albo obiekt [Ice\Arr](http://doc.iceframework.org/latest/class/Ice/Arr.html) (który jest używany podczas wyszukiwania modeli). W kontrolerze:
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

W widoku:
```twig
{# Loop over current page items #}
{% foreach pagination.items as post %}
    {{ post.title }}
{% endforeach %}
{# Display pagination links, eg. Previous 1 [2] 3 4 5 6 Next #}
{{ pagination.minimal() }}
```

#### Używanie tras
Możesz użyć trasy `/2` zamiast parametrów `?page=2` i ustawić `limit` pozycji na stronie:
```php
$pagination = new Pagination([
    "data" => Posts::find(),
    "limit" => 20,
    "page" => $this->dispatcher->getParam('page', 'int', 1, true),
    "query" => false
]);
```

Ale musisz podać trasy w bootstrapie:
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

#### Szablony
W widokach możesz łatwo wyświetlić różne szablony
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

> Jeśli masz usługę `i18n` w *di*, to będzie używana do przetłumaczenia _Previous_, _Next_, itp. słów.

##### Właściwości paginacji
Możesz uzyskać dostęp do właściwości `first`, `previous`, `current`, `next`, `last`, `pages`, `total`, `items` i wyświetlić swoje własne info stronicowania:
```twig
{{ _t('Page: %d/%d (%d items)', [pagination.current, pagination.pages, pagination.total]) }}
{# Page: 2/6 (54 items) #}
```