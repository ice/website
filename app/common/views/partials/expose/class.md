```twig
{% use App\Models\Users %}

{% set user = Users::findOne(1) %}
{{ user.username }}
```