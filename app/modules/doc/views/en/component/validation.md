## Validation
***
Allows to validate any array data eg. *$_POST*, *$_GET*, *$_FILES*, etc.

Let's try to validate `data` with some email rules:

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

In the `messages` variable you should have:
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

##### Add multiple rules
You can add multiple rules at once:
```php
$validation->rules([
    'emailAddress' => [
        new Required(),
        new Email()
    ],
    'repeatEmailAddress' => new Same(['other' => 'emailAddress'])
]);
```

##### Array way
You can add multiple rules at once in the array way:
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

##### Short syntax
The same in the short syntax:
```php
$validation->rules([
    'emailAddress' => 'required|email',
    'repeatEmailAddress' => 'same:emailAddress'
]);
```

##### Set the human labels in the messages
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

##### Set the custom messages and custom labels
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
Also you can overwrite default messages and labels by `setDefaultMessages` and `setLabels` methods.

##### Translation
The messages and labels are translated by default, so if you have in the `pl.php` lang file (Polish language):
```php
return [
    'Field :field is required' => 'Pole <em>:field</em> jest wymagane',
    'Field :field and :other must match' => 'Pole <em>:field</em> i <em>:other</em> muszą się zgadzać',
    'emailAddress' => 'Adres email'
    'repeatEmailAddress' => 'Powtórz email'
];
```

Validation will return
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

##### Filters
You can add `Ice\Filter` to be sure to retreive valid format after validation:
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
In this way you can simply escape string, remove repeats, or cast values to `int`, `float`, etc.

### Validating Models
***
In the models there is implemented the autovalidation, so you can simply validate some model's fields during creating. Just specify `rules` attribute:
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
And fetch messages if fields are not valid:
```php
$user = new Users();

if ($user->create($data) !== true) {
    $messages = $user->getMessages();
}
```

##### Extra validation
Add extra validation for fields that won't be save but must pass:
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

> During updating the validation is not being used by default. Before `update` you should run:
```php
$user->setValidation($validation);
```

##### Valid fields
You can specify valid fields and only them will be saved:
```php
$user = new Users();
$user->username = 'ice';
$user->password = 'secret';
$user->email = 'user@example.com';

if ($user->create(['username', 'password']) !== true) {
    $messages = $user->getMessages();
}
```
The `email` won't be saved.

Or globally in the `fields` attribute:
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
Then only `email`, `username`, and `password` will be taken from the *$_POST*.

##### Model hooks
You can add hooks to run some code before or after validation:
```php
class Users extends Model
{

    public function create($fields = [], Validation $extra = null)
    {
        $this->di->hook('model.after.validate', function ($this) {
            $this->set('password', md5($this->get('password')));
        });

        parent::create($fields, $extra);
    }
}
```