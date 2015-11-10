## Validation
***
[Ice\Validation](http://doc.iceframework.org/latest/class/Ice/Validation.html) jest niezależnym komponentem walidacji, który pozwala walidować różne dane tablicowe, np. *$_POST*, *$_GET*, *$_FILES*, itp.

Spróbujmy zwalidować `$data` z kilkoma regułami dla maili:

```php
use Ice\Validation;
use Ice\Validation\Validator\Email;
use Ice\Validation\Validator\Required;
use Ice\Validation\Validator\Same;

$data = [
    'emailAddress' => '',
    'repeatEmailAddress' => 'user@example.com',
];

$validation = new Validation();

$validation->rule('emailAddress', new Required());
$validation->rule('emailAddress', new Email());
$validation->rule('repeatEmailAddress', new Same(['other' => 'emailAddress']));

$validation->validate($data);

if (!$validation->valid()) {
    $messages = $validation->getMessages();
}
```

W zmiennej `messages` powinieneś mieć:
```php
var_dump($messages->all());
```

```code
array(2) {
  ["emailAddress"]=>
  array(1) {
    [0]=>
    string(30) "Field emailAddress is required"
  }
  ["repeatEmailAddress"]=>
  array(1) {
    [0]=>
    string(52) "Field repeatEmailAddress and emailAddress must match"
  }
}
```

#### Dodawaj wiele reguł
Możesz dodać wiele reguł naraz:
```php
$validation->rules([
    'emailAddress' => [
        new Required(),
        new Email()
    ],
    'repeatEmailAddress' => new Same(['other' => 'emailAddress'])
]);
```

#### Sposób tablicowy
Możesz dodać wiele reguł naraz przez sposób tablicowy:
```php
$validation->rules([
    'emailAddress' => [
        'required',
        'email'
    ],
    'repeatEmailAddress' => [
        'same' => [
            'other' => 'emailAddress'
        ]
    ]
]);
```

#### Krótka składnia
To samo w krótkiej składni:
```php
$validation->rules([
    'emailAddress' => 'required|email',
    'repeatEmailAddress' => 'same:emailAddress'
]);
```

#### Ustaw ludzkie etykiety w komunikatach
```php
$validation->setHumanLabels(true);

var_dump($messages->all());
```
```code
array(2) {
  ["emailAddress"]=>
  array(1) {
    [0]=>
    string(31) "Field Email address is required"
  }
  ["repeatEmailAddress"]=>
  array(1) {
    [0]=>
    string(55) "Field Repeat email address and Email address must match"
  }
}
```

#### Ustaw niestandardowe wiadomości i etykiety
```php
// ...
'repeatEmailAddress' => [
    'same' => [
        'other' => 'emailAddress',
        'message' => ':field must be the same as :other',
        'label' => 'Repeat E-mail',
        'labelOther' => 'E-mail'
    ]
]
```
```code
// ...
  ["repeatEmailAddress"]=>
  array(1) {
    [0]=>
    string(40) "Repeat E-mail must be the same as E-mail"
  }
}
```
Możesz również nadpisać domyślne komunikaty i etykiety stosując metody `setDefaultMessages()` i `setLabels()`.

#### Tłumaczenie
Komunikaty i etykiety są domyślnie tłumaczone, więc jeśli masz plik językowy `pl.php` (polskie tłumaczenie):
```php
return [
    'Field :field is required' => 'Pole <em>:field</em> jest wymagane',
    'Field :field and :other must match' => 'Pole <em>:field</em> i <em>:other</em> muszą się zgadzać',
    'emailAddress' => 'Adres email'
    'repeatEmailAddress' => 'Powtórz email'
];
```

Komunikaty:
```code
array(2) {
  ["emailAddress"]=>
  array(1) {
    [0]=>
    string(41) "Pole <em>Adres email</em> jest wymagane"
  }
  ["repeatEmailAddress"]=>
  array(1) {
    [0]=>
    string(80) "Pole <em>Powtórz email</em> i <em>Adres email</em> muszą się zgadzać"
  }
}
```

> Komponent [Ice\I18n](http://doc.iceframework.org/latest/class/Ice/I18n.html) musi zostać dodany do usługi `i18n` w *di*.

#### Filtry
Możesz dodać jakiś filter, aby być pewien, że otrzymasz prawidłową wartość po walidacji:
```php
$data = [
    'username' => 'ice-123_framework'
];

$validation = new Validation();
$validation->setFilters([
    'username' => 'alpha'
]);

$validation->validate($data);

var_dump($validation->getValue('username'));
```

```code
string(12) "iceframework"
```
Przez ten sposób, możesz łatwo zabezpieczyć stringi, usunąć powtórzenie, albo przekonwertować wartość do `int`, `float`, itp.

> Komponent [Ice\Filter](http://doc.iceframework.org/latest/class/Ice/Filter.html) musi zostać dodany do usługi `filter` w *di*.

### Walidacja Modeli
***
W modelach zaimplementowana jest autowalidacja, więc możesz łatwo walidować jakiś pola [Ice\Mvc\Model](http://doc.iceframework.org/latest/class/Ice/Mvc/Model.html) podczas tworzenia. Po prostu podaj właściwość `rules`:
```php
namespace App\Models;

use Ice\Mvc\Model;

class Users extends Model
{

    protected $rules = [
        'username' => 'required|length:4,24|regex:/[a-z][a-z0-9_-]{3,}/i|notIn:admin,index,user,root|unique:users',
        'password' => 'required|length:5,32',
        'email' => 'required|email|unique:users',
    ];
}
```
I przechwyć komunikaty, jeśli pola nie są zgodne:
```php
$user = new Users();

if ($user->create($data) !== true) {
    $messages = $user->getMessages();
}
```

#### Dodatkowa walidacja
Dodaj dodatkową walidację, do pól, które nie będą zapisane, ale muszą przejść:
```php
$extra = new Validation($data);
$extra->rules([
    'repeatPassword' => 'same:password',
    'repeatEmail' => 'same:email',
]);

if ($this->create($data, $extra) !== true) {
    $messages = $user->getMessages();
}
```

> Podczas aktualizowania walidacja nie jest domyślnie używana. Przed `update()` powinieneś uruchomić:
```php
$user->setValidation($validation);
```

#### Prawidłowe pola
Możesz podać prawidłowe pola i tylko one zostaną zapisane:
```php
$user = new Users();
$user->username = 'ice';
$user->password = 'secret';
$user->email = 'user@example.com';

if ($user->create(['username', 'password']) !== true) {
    $messages = $user->getMessages();
}
```
Pole `email` nie zostanie zapisane.

Albo globalnie we właściwości `fields`:
```php
class Users extends Model
{

    protected $fields = [
        'email',
        'username',
        'password'
    ];
}
```
```php
if ($user->create($_POST) !== true) {
    $messages = $user->getMessages();
}
```
Wtedy tylko `email`, `username`, i `password` będzie wzięte z *$_POST*.

#### Haki modelowe
Możesz dodać haki, aby uruchomić kod przed lub po walidacji modelu:
```php
class Users extends Model
{

    public function create($fields = [], Validation $extra = null)
    {
        $this->di->hook('model.after.validate', function ($this) {
            $this->set('password', md5($this->get('password')));
        });

        return parent::create($fields, $extra);
    }
}
```