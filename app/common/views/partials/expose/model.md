```php
$users = Users::find(
    [
        "status" => 1,
        "age" => [">" => 21]
    ],
    [
        "limit" => 20
    ]
);

foreach ($users as $user) {
    echo $user->username;
}
```