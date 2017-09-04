## Konfiguracja serwera
***
### Nginx
[Nginx](http://nginx.org) jest darmowym, open-sourcowym, wysokiej wydajności serwerem HTTP. W przeciwieństwie do tradycyjnych serwerów, Nginx nie polega na wątkach przy obsługdze żądań. Zamiast tego używa znacznie bardziej skalowną architekturę sterowania zdarzeniami (asynchronicznie). Architektura ta wykorzystuje małe, ale ważniejsze, przewidywanie ilości pamięci pod obciążeniem.

PHP-FPM (FastCGI Process Manager) jest zwykle stosowany w celu umożliwienia serwerowi Nginx przetwarzania plików PHP. Obecnie, PHP-FPM jest w zestawie z PHP w każdej dystrybucji z rodziny Unix. Nginx + PHP-FPM + Ice zapewnia potężny zestaw narzędzi, które zapewniają maksymalną wydajność w aplikacjach PHP.

#### Konfiguracja wirtualnego hosta
Utwórz plik konfiguracyjny `/etc/nginx/vhosts.d/hello.conf`:
```
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

Jeśli komputer jest publiczy, użyj domeny np. _example.com_ zamiast _hello_ w opcji `server_name`. Jeśli pracujesz na komputerze lokalnym, dodaj nazwę hosta _hello_ do pliku `/etc/hosts`, aby móc uruchomić host w Twojej przeglądarce:
```conf
127.0.0.2       hello
```

***
### Apache
[Apache](http://httpd.apache.org) jest popularnm i dobrze znanym serwerem WWW dostępnym na wielu platformach.

#### Konfiguracja wirtualnego hosta
Utwórz plik konfiguracyjny `/etc/apache2/vhosts.d/hello.conf`:
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

Jeśli komputer jest publiczy, użyj domeny np. _example.com_ zamiast _hello_ w opcji `ServerName`. Jeśli pracujesz na komputerze lokalnym, dodaj nazwę hosta _hello_ do pliku `/etc/hosts`, aby móc uruchomić host w Twojej przeglądarce:
```conf
127.0.0.2       hello
```

Dla przyjaznych adresów URL, musisz zainstalować `mod_rewrite` i utworzyć plik _.htaccess_:
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

Jeśli nie chcesz używać plików _.htaccess_ można przenieść tą konfigurację do głównego pliku konfiguracyjnego serwera Apache.