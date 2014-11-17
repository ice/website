```php
{% if this.auth.loggedIn() %}
    // User is logged in, continue on
    {{ this.auth.getUser().username }}
{% endif %}
```
```php
{% if this.auth.loggedIn('admin') %}
    // Admin privileges
{% else %}
    // No access
{% endif %}
```