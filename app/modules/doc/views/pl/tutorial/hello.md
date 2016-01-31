## Poradnik 1: aplikacja [hello](https://github.com/ice/hello)
***
_Hello_ jest bardzo prostym przykładem aplikacji napisanej w Ice framework.

***
### Sprawdzenie instalacji
Zakładamy że masz już zainstalowanego Ice. Sprawdź wynik funkcji `phpinfo()` w poszukiwaniu sekcji zawierającej "Ice" lub uruchom poniższy kod:
```php
<?php print_r(get_loaded_extensions()); ?>
```

Rozszerzenie Ice powinno pojawić się jako część wyniku:
> Array ( [0] => Core [1] => date [2] => ereg [3] => libxml [4] => pcre [5] => hash [6] => SPL [7] => Reflection [8] => session [9] => standard [10] => SimpleXML [11] => filter [12] => xml [13] => mysqlnd [14] => cgi-fcgi [15] => ctype [16] => curl [17] => dom [18] => fcache [19] => fileinfo [20] => gd [21] => **ice** [22] => iconv [23] => json [24] => mbstring [25] => mcrypt [26] => mysql [27] => mysqli [28] => openssl [29] => PDO [30] => pdo_mysql [31] => pdo_sqlite [32] => phalcon [33] => zlib [34] => sqlite3 [35] => tokenizer [36] => xmlreader [37] => xmlwriter [38] => Phar [39] => mhash )

***
### Tworzenie projektu
Najlepszym sposobem na skorzystanie z tego poradnika jest jego śledzenie krok po kroku. Możesz uzyskać kompletny kod:
```sh
git clone https://github.com/ice/hello
```

***
### Struktura plików
Na potrzeby tego poradnika i jako punkt startowy, proponujemy następującą strukturę:
```
hello/
  App/
    Controller/
    Model/
    View/
  public/
    css/
    img/
    js/
```

Zauważ, że nie potrzebujesz żadnych folderów związanych z Ice. Framework jest dostępny w pamięci, gotowy do użycia!

***
### Konfiguracja serwera
Umówmy się, że wirtualny host nazywa się _hello_. Ustaw `DocumentRoot` dla vhostu na folder `hello/public/`. Ten krok zapewni ukrycie wewnętrznych folderów projektu od widoku publicznego, eliminując różnego typu zagrożenia bezpieczeństwa.

W tym poradniku użyjemy przyjaznych URLi. Przyjazne URLe są lepsze dla SEO, jak również łatwe do zapamiętania dla użytkowników. Więcej informacji na stronie [konfiguracja serwera](/doc/introduction/server).

*Jeśli pracujesz na Apache, dodaj plik _.htaccess_ do folderu `public/`:
```
# hello/public/.htaccess
Options FollowSymLinks
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?_url=/$1 [QSA,L]
</IfModule>
```
Ten zestaw reguł sprawdzi czy żądany plik istnieje i jeśli istnieje, czy nie musi być przepisany przez moduł serwera.

***
### index.php
Pierwszym plikiem, który musisz utworzyć jest `public/index.php`. Plik ten definiuje stałą `__ROOT__`, która zawiera pełną ścieżkę do `DocumentRoot`, ładuje plik bootstrap, przechwytuje żądanie MVC i wyświetla odpowiedź HTTP:
```php
<?php

defined('__ROOT__') or
    /**
     * Full path to the docroot
     */
    define('__ROOT__', dirname(__DIR__));

// Load the bootstrap which return the MVC application
$app = require_once __ROOT__ . '/App/Bootstrap.php';

try {
    // Handle a MVC request and display the HTTP response body
    echo $app->handle();
} catch (Exception $e) {
    // Dispaly the excepton's message
    echo $e->getMessage();
}
?>
```

***
### Bootstrap.php
Drugim plikiem jest `App/Bootstrap.php`. Ten plik jest bardzo ważny; ponieważ służy jako baza Twojej aplikacji, dając Ci kontrolę nad wszystkimi jego aspektami. W tym pliku możesz zaimplementować inicjalizację komponentów, jak również zachowań aplikacji.
```php
<?php

namespace App;

/**
 * Register the psr-4 auto loader. You will be able to use:
 * App\Controller, App\Model, App\Library, etc.
 */
(new \Ice\Loader())
    ->addNamespace(__NAMESPACE__, __DIR__)
    ->register();

// Create a dependency injector container
$di = new \Ice\Di();

// Set some services
$di->request = new \Ice\Http\Request();
$di->response = new \Ice\Http\Response();
$di->tag = new \Ice\Tag();

$di->set('dispatcher', function () {
    $dispatcher = new \Ice\Mvc\Dispatcher();
    $dispatcher->setNamespace(__NAMESPACE__);

    return $dispatcher;
});

$di->set('router', function () {
    $router = new \Ice\Mvc\Router();
    $router->setRoutes([
        // The universal routes
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+}/{id:\d+}/{param}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+}/{id:\d+}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+}/{param}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+[/]?}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{id:\d+}'],
        [['GET', 'POST'], '/{controller:[a-z]+[/]?}'],
        [['GET', 'POST'], ''],
    ]);

    return $router;
});

$di->set('view', function () {
    $view = new \Ice\Mvc\View();
    $view->setViewsDir(__DIR__ . '/View/');

    return $view;
});

// Create and return a MVC application
return new \Ice\Mvc\App($di);
?>
```

#### Autoloader
Pierwszą częścią, którą znajdziemy w naszym pliku bootstrap jest rejestracja autoloadera. Autoloader ten będzie użyty do załadowania klas w aplikacji jako kontrolery i modele.
```php
(new \Ice\Loader())
    ->addNamespace(__NAMESPACE__, __DIR__)
    ->register();
```
W tym przypadku `__NAMESPACE__` oznacza _App_ a `__DIR__` oznacza bieżący katalog.

#### Wstrzykiwanie zależności
Pojemnik usług jest miejscem, gdzie globalnie przechowujemy usługi naszej aplikacji, które używamy do funkcjonowania. Za każdym razem, gdy framework wymaga składnika, zwróci się do pojemnika za pomocą uzgodnionej nazwy dla tej usługi.
```php
$di = new \Ice\Di();
```

#### Usługi
Usługi są to elementy, które składają się na aplikację. Zarejestrujmy kilka wymaganych usług:

_Request_ kontroluje bieżące żądanie HTTP:
```php
$di->request = new \Ice\Http\Request();
```

_Response_ zapewnia prosty interfejs dla odpowiedzi HTTP:
```php
$di->response = new \Ice\Http\Response();
```

_Tag_ pomaga w generowaniu linków, formularzy, itp.:
```php
$di->tag = new \Ice\Tag();
```

_Dispatcher_ ładuje określony moduł, tworzy instancję kontrolera z akcją i parametrami:
```php
$di->set('dispatcher', function () {
    $dispatcher = new \Ice\Mvc\Dispatcher();
    $dispatcher->setNamespace(__NAMESPACE__);

    return $dispatcher;
});
```
_Router_ bierze URI i rozkładania go na parametry w celu określenia który moduł, kontroler i akcja tego kontrolera powinny odebrać żądanie:
```php
$di->set('router', function () {
    $router = new \Ice\Mvc\Router();
    $router->setRoutes([
        // The universal routes
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+}/{id:\d+}/{param}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+}/{id:\d+}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+}/{param}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{action:[a-z]+[/]?}'],
        [['GET', 'POST'], '/{controller:[a-z]+}/{id:\d+}'],
        [['GET', 'POST'], '/{controller:[a-z]+[/]?}'],
        [['GET', 'POST'], ''],
    ]);

    return $router;
});
```

_View_ jest usługą wskazującą katalog, w którym framework znajdzie pliki widoków:
```php
$di->set('view', function () {
    $view = new \Ice\Mvc\View();
    $view->setViewsDir(__DIR__ . '/View/');

    return $view;
});
```

#### Stwórz aplikację MVC
W ostatniej części tego pliku, znajdziemy `Ice\Mvc\App`. Jego celem jest inicjacja środowiska żądania, rozpoznanie przychodzące trasy, a następnie odpalenie odpowiednich akcji; zwraca odpowiedź, gdy proces jest zakończony:
```php
return new \Ice\Mvc\App($di);
```

Jak widać, plik bootstrap jest bardzo krótki i nie trzeba dołączać żadnych dodatkowych plików. Postawiliśmy sobie elastyczną aplikację MVC w około 50 linii kodu.

*Od Ice 1.1.0 usługi są predefiniowane, więc plik bootstrap może być prostszy:
```php
namespace App;

// Create a dependency injector container
$di = new \Ice\Di();

// Register App namespace for App\Controller, App\Model, App\Library, etc.
$di->loader
    ->addNamespace(__NAMESPACE__, __DIR__)
    ->register();

// Set some service's settings
$di->dispatcher
    ->setNamespace(__NAMESPACE__);

$di->router
    ->setRoutes([
        ['GET', '/{controller:[a-z]+}/{action:[a-z]+[/]?}'],
        ['GET', '/{controller:[a-z]+[/]?}'],
        ['GET', ''],
    ]);

$di->view
    ->setViewsDir(__DIR__ . '/View/');

// Create and return a MVC application
return new \Ice\Mvc\App($di);
```

***
### Tworzenie kontrolera
Domyślnie Ice szuka kontrolera o nazwie _Index_. To jest punkt wyjścia, gdy kontroler lub akcja nie zostały przekazane w żądaniu. Kontroler index `App/Controler/IndexController.php` wygląda tak:
```php
<?php

namespace App\Controller;

use Ice\Mvc\Controller;

/**
 * Default controller
 *
 * @package     Ice/Hello
 * @category    Controller
 */
class IndexController extends Controller
{

    /**
     * Default action
     */
    public function indexAction()
    {
        
    }
}
?>
```
Klasa kontrolera musi mieć przyrostek _Controller_, a akcja kontrolera musi mieć przyrostek _Action_.

***
### Wysyłanie danych wyjściowych do widoku
Najpierw Ice będzie szukać szablonu `App/Views/layouts/index.phtml` w katalogu szablonów:
```php
<?php echo $this->getContent() ?>
```

Następnie widoku o tej samej nazwie co ostatnio wykonanej akcji wewnątrz katalogu o nazwie jak ostatnio wykonanego kontrolera. W naszym przypadku `App/Views/index/index.phtml`:
```
hello world
```

Jeśli masz dostęp do aplikacji z poziomu przeglądarki, powinieneś zobaczyć coś takiego:

![Hello](/img/doc/hello.jpg){.img-responsive}

***
### Połączenie z bazą danych
Przed utworzeniem naszego pierwszego modelu, musimy utworzyć tabelę bazy danych poza Ice aby ją odwzorowywać. Prostą tabelę do przechowywania zarejestrowanych użytkowników można zdefiniować tak:
```sql
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
);
```

Aby móc korzystać z połączenia z bazą danych, a następnie uzyskać dostęp do danych za pośrednictwem naszych modeli, musimy określić ją w naszym bootstrapie. Połączenie z bazą danych jest kolejną usługą, którą ma nasza aplikacja, która może być używana przez różne komponenty:
```php
$di->set('db', function () {
    $driver = new \Ice\Db\Driver\Pdo('mysql:host=localhost;port=3306;dbname=demo_hello', 'demo', 'demo');
    
    return new \Ice\Db($driver);
});
```

#### Tworzenie modelu
Model powinien być zlokalizowany w katalogu `App/Model/` mianowicie `App/Model/Users.php`. Model ten mapuje tabelę _users_:
```php
<?php

namespace App\Model;

use Ice\Mvc\Model;

/**
 * Users model
 *
 * @package     Ice/Hello
 * @category    Model
 */
class Users extends Model
{

}
?>
```

#### Kontroler User
Odbieranie danych z formularza i przechowywanie ich w tabeli jest kolejnym krokiem.
```php
<?php

namespace App\Controller;

use App\Model\Users;
use Ice\Validation;

/**
 * User controller
 *
 * @package     Ice/Hello
 * @category    Controller
 */
class UserController extends IndexController
{

    /**
     * Display sign up form
     */
    public function getSignupAction()
    {
        
    }

    /**
     * Sign up new user
     */
    public function postSignupAction()
    {
        $post = $this->request->getPost()->all();

        $validation = new Validation();
        $validation->rules([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $valid = $validation->validate($post);

        if (!$valid) {
            echo 'Warning! Please correct the errors:<br />';
            foreach ($validation->getMessages() as $message) {
                echo $message[0] . '<br />';
            }
        } else {
            $user = new Users();
            $user->setFields(['name', 'email']);

            if ($user->create($post)) {
                echo "Thanks for registering!";
            }
        }

        $this->view->setContent(false);
    }

    /**
     * Display all users
     */
    public function indexAction()
    {
        $this->view->setVar('users', Users::find());
    }
}
?>
```

Otwórz `hello/user` w przeglądarce. Ten adres URL uruchamia kontroler _User_ i akcję _Index_ (znajdź wszystkich użytkowników i wyślij ich do widoku):
```php
$this->view->setVar('users', Users::find());
```

Powinieneś zobaczyć:

![No users](/img/doc/hello2.jpg){.img-responsive}

Widok `App/View/user/index.phtml` wyświetla wszystkich użytkowników (jeśli są) i link do rejestracji:
```php
<?php if (count($users)):?>
    <?php foreach ($users as $user): ?>
        <?php echo $user->id . '. ' . $user->name . ': ' . $user->email ?><br>
    <?php endforeach ?>
<?php else: ?>
    No users found.<br>
<?php endif ?>
<?php echo $this->tag->linkTo(['user/signup', 'Sign up']) ?>
```

Po kliknięciu w _Sign up_ i powinieneś zobaczyć formularz:

![Sign up](/img/doc/hello3.jpg){.img-responsive}

Widok `App/View/user/signup.phtml` z definicją formularza:
```php
<h2>Sign up</h2><hr />
<?php echo $this->tag->form([false]) ?>
<p>Name: <?php echo $this->tag->textField(['name']) ?></p>
<p>Email: <?php echo $this->tag->textField(['email']) ?></p>
<p><?php echo $this->tag->button(['submit', 'Sign up']) ?></p>
<?php echo $this->tag->endTag('form') ?>
```

Więc spróbuj wysłać formularz, nie wypełniaj pól, po prostu kliknij _Sign up_ przycisk:

![Validation error](/img/doc/hello4.jpg){.img-responsive}

Powinieneś zobaczyć powyższe błędy ponieważ walidacja w `postSignupAction` nie przechodzi:
```php
$post = $this->request->getPost()->all();

$validation = new Validation();
$validation->rules([
    'name' => 'required',
    'email' => 'required|email|unique:users',
]);

$valid = $validation->validate($post);

if (!$valid) {
    echo 'Warning! Please correct the errors:<br />';
    foreach ($validation->getMessages() as $message) {
        echo $message[0] . '<br />';
    }
```

Wróć, wypełnij _Name_, _Email_ i kiliknij _Sign up_.
```php
} else {
    $user = new Users();
    $user->setFields(['name', 'email']);

    if ($user->create($post)) {
        echo "Thanks for registering!";
    }
}
```
Powinieneś zobaczyć _Thanks for registering!_.

Upewnij się, czy użytkownik został zarejestrowany, przejdź do `hello/user`, aby zobaczyć wszystkich użytkowników. Twoja przeglądarka wyświetli coś w tym stylu:

![All users](/img/doc/hello5.jpg){.img-responsive}

***
### Wnioski
Jest to bardzo prosty przykład, jak widać łatwo zacząć budowanie aplikacji za pomocą Ice!

***
### Przykładowe aplikacje
Spróbuj również:
* [website](https://github.com/ice/website) - Kod źródłowy tej strony
* [base](https://github.com/ice/base) - Bazowa aplikacja napisana w Ice