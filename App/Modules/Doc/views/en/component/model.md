## Model
***
[Ice\Mvc\Model](http://doc.iceframework.org/latest/class/Ice/Mvc/Model.html) allows you to manipulate database records / documents as objects.
#### Model file
It's very easy to start using the models, just extends the `Ice\Mvc\Model` using the same class name as your table / collection.
```php
namespace App\Models;

use Ice\Mvc\Model;

class Posts extends Model
{

}
```
The model `App\Models\Posts` will map to the table / collection `posts`. If you want to manually specify another name for the mapped source, you can set `from` attribute.
```php
class Posts extends Model
{
    protected $from = 'articles'
}
```

#### Find one object
By using `findOne` method you can select one record / document and map it to an object to get easy access to the data.
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

#### Find many objects
The following examples will show you how to query multiple elements from a model. Use `find` method to create a set of objects.
```php
$posts = Posts::find(['status' => 1], ['limit' => 10]);

foreach ($posts as $post) {
    echo $post->title;
}
```

#### Create (insert) a new object
To insert a new record / document use `create` method.
```php
$post = new Posts();
$post->title = 'First post';
$post->create();
```

You can set the data in the constructor to avoid manually assigning each column.
```php
$post = new Posts([
    'title' => 'Second post'
]);
$post->create();
```

Or set the data in the create method.
```php
$post = new Posts();
$post->create([
    'title' => 'Third post'
]);
```

Put an array to set a whitelist of fields that will be taken during creation.
```php
$post = new Posts();
$post->setData([
    'title' => 'Fourth post',
    'description' => 'Some description'
]);

// Only a title will be taken
$post->create(['title']);
```

Or set the valid fields in the `fields` attribute.
```php
class Posts extends Model
{
    protected $fields = [
        'title',
    ];
}
```

And use the `_POST` global variable as the data.
```php
$post = new Posts();
$post->create($_POST);
```

#### Add a [Validation](/doc/component/validation) rules
A validation on `create` method is built-in and enabled by default, all you have to do is to add `rules` attribute.
```php
class Posts extends Model
{
    protected $rules = [
        'title' => 'required',
    ];
}
```

And fetch the error messages if a title is missing.
```php
$post = new Posts();

if ($post->create($_POST) !== true) {
    $errors = $post->getMessages();
}
```

#### Update

```php
$post = Posts::findOne(1);

$post->title = 'A new title';
$post->update();
```

To the `update` method you can pass an array of data like to the `create` method.
```php
$post = Posts::findOne(1);
$post->update([
    'title' => 'Update v2'
]);
```

If you have the `fields` attribute specified, only that fields will be taken from the data. The main difference to the `create` method is that the validation is not enabled by default. You have to use `setValidation` before the update method.
```php
$validation = new Validation();
$validation->rules($this->getRules([
    'title',
]));
$this->setValidation($validation);

$this->update($_POST);
```

The `save` method allows you to create / update record according to whether they already exist in the table associated with a model.
```php
$post = new Post();
$post->title = 'A new title';
$post->save();

$post->title = 'Updated title';
$post->save();
```

***
#### Relations
Specify the relations in the `initialize` method to easily get related model.
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
#### Services
Service is intermediary between Model and Controller. You can inject model into service constructor.
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

Then in the Controller you have access to all model and service methods.
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
#### Hooks
It's possible to invoke some code by adding a hook.
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

Available hooks in the model:
 * model.before.validate
 * model.after.validate
 * model.before.create
 * model.after.create
 * model.before.update
 * model.after.update