## Server configuration
***
### Nginx
[Nginx](http://nginx.org) is a free, open-source, high-performance HTTP. Unlike traditional servers, Nginx doesn't rely on threads to handle requests. Instead it uses a much more scalable event-driven (asynchronous) architecture. This architecture uses small, but more importantly, predictable amounts of memory under load.

The PHP-FPM (FastCGI Process Manager) is usually used to allow Nginx to process PHP files. Nowadays, PHP-FPM is bundled with any Unix PHP distribution. Nginx + PHP-FPM + Ice provides a powerful set of tools that offer maximum performance for your PHP applications.

#### Virtual Host configuration
Create `/etc/nginx/vhosts.d/hello.conf` config file:
```nginx
server {
    listen      80;
    server_name hello;
    set         $root_path '/srv/www/hello/public';
    root        $root_path;

    access_log  /var/log/nginx/access.log;
    error_log   /var/log/nginx/error.log error;

    index index.php;

    try_files $uri $uri/ @rewrite;

    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }

    location ~ \.php {
        fastcgi_index  /index.php;
        fastcgi_pass   127.0.0.1:9000;

        include fastcgi_params;
        fastcgi_split_path_info       ^(.+\.php)(/.+)$;
        fastcgi_param PATH_INFO       $fastcgi_path_info;
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~* ^/(css|fonts|img|js|min)/(.+)$ {
        root $root_path;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

If you host is public use the domain eg. _example.com_ instead of `hello` at the `server_name` option. If you are working on local machine add the _hello_ hostname to the `/etc/hosts` file to be able run host in your browser:
```conf
127.0.0.2       hello
```

***
### Apache
[Apache](http://httpd.apache.org) is a popular and well known web server available on many platforms.

#### Virtual Host configuration
Create `/etc/apache2/vhosts.d/hello.conf` config file:
```apache
<VirtualHost *:80>
  DocumentRoot /srv/www/hello/public
  ServerName hello
  ServerAdmin root@example.com
  <Directory /srv/www/hello/public>
    AllowOverride All
    Order allow,deny
    Allow from all
  </Directory>
</VirtualHost>
```

If you host is public use the domain eg. _example.com_ instead of `hello` at the `ServerName` option. If you are working on local machine add the _hello_ hostname to the `/etc/hosts` file to be able run host in your browser:
```conf
127.0.0.2       hello
```

For the friendly URLs you need to install `mod_rewrite` and create _.htaccess_ file:
```
# /srv/www/hello/public/.htaccess
Options FollowSymLinks
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php?_url=/$1 [QSA,L]
</IfModule>
```

If you do not want to use _.htaccess_ files you can move these configurations to the apache's main configuration file.