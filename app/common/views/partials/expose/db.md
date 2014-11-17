```php
$users = $this->db->find(
    'users',
    [
        "status" => 1,
        "age" => [">" => 21]
    ],
    [
        "limit" => 20
    ]
);

foreach ($users as $user) {
    echo $user['username'];
}
```