```php
{% foreach users as key: user %}
    {{ key ~ ": " ~ user.username }}
{% endforeach %}
```
```php
{% for i=0; i<10; i++ %}
    // Loop
{% endfor %}
```
```php
{% while i<10 %}
    // Loop
    {% var i++ %}
{% endwhile %}
```
```php
{% switch i %}
    {% case 1 %}
        // i equals 1
        {% break %}
    {% case 2 %}
        // i equals 2
        {% break %}
    {% default %}
        // Default case
        {% break %}
{% endswitch %}
```