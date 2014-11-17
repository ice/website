```php
/**
 * Display the specified post
 */
public function showAction()
{
    $params = $this->router->getParams();
    $id = $params['id'];

    if ($post = Posts::findOne($id)) {
        $this->tag->setTitle($post->title);

        $this->view->setVars([
            'post' => $post,
            'user' => $post->getUser(),
            'comments' => $post->getComments(),
        ]);
    } else {
        parent:notFound();
    }
}
```