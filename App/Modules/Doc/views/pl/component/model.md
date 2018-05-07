## Model
***
[Ice\Mvc\Model](http://doc.iceframework.org/latest/class/Ice/Mvc/Model.html) pozwala na manipulowanie rekordami / dokumentemi bazy danych jak obiektami.
#### Plik Modelu
Bardzo łatwo jest zacząć używać modelów, wystarczy rozszerzyć `Ice\Mvc\Model` używając tej samej nazwy dla klasy co tabela / kolekcja.
```php
namespace App\Models;

use Ice\Mvc\Model;

class Posts extends Model
{

}
```
Model `App\Models\Posts` będzie mapował tabelę / kolekcję `posts`. Jeśli chcesz ręcznie podać inne źródło mapowania, możesz ustawić atrybut `from`.
```php
class Posts extends Model
{
    protected $from = 'articles'
}
```

#### Znajdź jeden obiekt
Używając metody`findOne` możesz pobrać jeden rekord / dokument i zmapować go do obiektu, aby uzyskać łatwy dostęp do danych.
```php
// Find by id
$post = Posts::findOne(1);

// And access to the data
// In object way
echo $post->title;

// In array way
echo $post['title'];

// By a getter
echo $post->get('title');
```

#### Znajdź wiele obiektów
Poniższy przykład pokazuje jak pobrać wiele elementów z modelu. Użyj metody `find` żeby stworzyć zbiór obiketów.
```php
$posts = Posts::find(['status' => 1], ['limit' => 10]);

foreach ($posts as $post) {
    echo $post->title;
}
```

#### Stwórz (dodaj) nowy obiekt
Żeby dodać nowy rekord / dokumnet użyj metody `create`.
```php
$post = new Posts();
$post->title = 'First post';
$post->create();
```

Możesz przekazać dane w konstruktorze, żeby uniknąć ręcznego przypisywania każdej kolumny.
```php
$post = new Posts([
    'title' => 'Second post'
]);
$post->create();
```

Albo przekazać dane w metodzie `create`.
```php
$post = new Posts();
$post->create([
    'title' => 'Third post'
]);
```

Podaj tablicę, aby przekazać białą listę pól, które będą wzięte podczas tworzenia.
```php
$post = new Posts();
$post->setData([
    'title' => 'Fourth post',
    'description' => 'Some description'
]);

// Only a title will be taken
$post->create(['title']);
```

Albo ustaw prawidłowe pola w atrybucie `fields`.
```php
class Posts extends Model
{
    protected $fields = [
        'title',
    ];
}
```

I użyj zmiennej globalnej `_POST` jako dane.
```php
$post = new Posts();
$post->create($_POST);
```

#### Dodaj reguły [Validation](/doc/component/validation)
Walidacja w metodzie `create` jest wbudowana i domyślnie włączona, wszystko co musisz zrobić to tylko dodać atrybut `rules`.
```php
class Posts extends Model
{
    protected $rules = [
        'title' => 'required',
    ];
}
```

I pobrać wiadomości błędów jeśli nie ma tytułu.
```php
$post = new Posts();

if ($post->create($_POST) !== true) {
    $errors = $post->getMessages();
}
```

#### Aktualizacja

```php
$post = Posts::findOne(1);

$post->title = 'A new title';
$post->update();
```

Do metody `update` możesz przekazać tablicę z danymi tak jak do metody `create`.
```php
$post = Posts::findOne(1);
$post->update([
    'title' => 'Update v2'
]);
```

Jeśli masz podany atrybut `fields`, tylko te pola będą wzięte z danych. Główna różnica do metody `create` to taka, że walidacja nie jest włączona domyślnie. Musisz użyć `setValidation` przed metodą `update`.
```php
$validation = new Validation();
$validation->rules($this->getRules([
    'title',
]));
$this->setValidation($validation);

$this->update($_POST);
```

Metoda `save` pozwala tworzyć / aktualizować rekord w zależności czy już istnieje w powiązanej w modelem tabeli.
```php
$post = new Post();
$post->title = 'A new title';
$post->save();

$post->title = 'Updated title';
$post->save();
```

***
#### Relacje
Podaj relacje w metodzie `initialize`, żeby łatwo pobrać powiązany model.
```php

class Posts extends Model
{
    public function initialize()
    {
        $this->belongsTo('user_id', __NAMESPACE__ . '\Users', $this->getIdKey(), ['alias' => 'User']);
        $this->hasMany($this->getIdKey(), __NAMESPACE__ . '\Coments', 'post_id', ['alias' => 'Coments']);
    }
}
```

```php
$post = Posts::findoOne(1);
$user = $post->getUser();

foreach ($post->getComments() as $comment) {
    echo $comment->content;
}
```

***
#### Serwisy
Serwis jest pośrednikiem pomiędzy modelem a kontrolerem. Możesz wstrzyknąć model do konstruktora serwisu.
```php
namespace App\Services;

use App\Models\Posts;
use Ice\Mvc\Service;

class PostService extends Service
{
    public function __construct(Posts $model)
    {
        $this->setModel($model);
    }

    public function add($data = [])
    {
        // Try to create a new post
        if ($this->create($data) === true) {
            // Return the object if it was created
            return $this->getModel();
        } else {
            // Return an array of error messages
            return $this->getMessages();
        }
    }
}
```

Wtedy w Kontrolerze masz łatwy dostęp do wszystkich metod modelu i serwisu.
```php
namespace App\Modules\Admin\Controllers;

use App\Models\Posts;
use App\Services\PostService;
use Ice\Mvc\Controller;

class PostController extends Controler
{
    protected $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    public function addAction()
    {
        $post = $this->service->add($this->request->getPost()->getData());

        // Try to create a new post from _POST data
        if ($post instanceof Posts) {
            // Redirect to /post page
            $this->response->redirect('post');
        } else {
            // Send errors variable to the view
            $this->view->setVar('errors', $post);
        }
    }

    public function getDetailsAction()
    {
        // Try to find and load a post
        if ($post = $this->service->loadOne($this->dispatcher->getParam('param'))) {
            // Send a post variable to the view
            $this->view->setVar('post', $post);
        } else {
            // Display a not found page
            parent::notFound();
        }
    }
}
```

***
#### Haki
Możliwe jest wywołanie kodu przez dodanie haka.
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

Dostępne haki w modelu:
 * model.before.validate
 * model.after.validate
 * model.before.create
 * model.after.create
 * model.before.update
 * model.after.update