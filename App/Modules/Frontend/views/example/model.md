```php
// Find the user
$user = Users::findOne([
    'username' => 'ice'
]);

// Get user's posts
$posts = $user->getPosts([
    'status' => Posts::ACTIVE
]);

foreach ($posts as $post) {
    // Display a title of the post
    echo $post->title;
}
```