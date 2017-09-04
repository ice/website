```twig
{# Check whether an user is logged in #}
{% if this.auth.loggedIn() %}
    {# Get logged in user's data #}
    {{ this.auth.getUser().username }}
{% endif %}
```
```twig
{# Check whether an user is the admin #}
{% if this.auth.loggedIn('admin') %}
    {# Admin privileges #}
{% else %}
    {# No access #}
{% endif %}
```