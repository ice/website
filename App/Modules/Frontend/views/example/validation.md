```php
$validation = new Ice\Validation();

$validation->rules([
    'fullName' => 'required',
    'email' => 'required|email',
    'repeatEmail' => 'same:email',
    'content' => 'required|length:10,5000',
]);

$validation->setFilters([
    'fullName' => 'string'
    'email' => 'email'
    'content' => 'repeats|escape'
]);

$data = $this->request->getPost()->getData();
$validation->validate($data);

if (!$validation->valid()) {
    $messages = $validation->getMessages();
}
```