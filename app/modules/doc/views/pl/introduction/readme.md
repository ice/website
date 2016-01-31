## [Ice framework](http://www.iceframework.org)
Prosty i szybki framework PHP dostarczany jako rozszerzenie C.

[![Latest Stable Version](https://poser.pugx.org/iceframework/framework/v/stable.svg)](https://packagist.org/packages/iceframework/framework) [![Total Downloads](https://poser.pugx.org/iceframework/framework/downloads.svg)](https://packagist.org/packages/iceframework/framework) [![Latest Unstable Version](https://poser.pugx.org/iceframework/framework/v/unstable.svg)](https://packagist.org/packages/iceframework/framework) [![License](https://poser.pugx.org/iceframework/framework/license.svg)](https://packagist.org/packages/iceframework/framework)

### Stadium
[![Build Status](https://travis-ci.org/ice/framework.svg?branch=dev)](https://travis-ci.org/ice/framework)
[![Dependency Status](https://www.versioneye.com/user/projects/54d4f6963ca0840b19000383/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54d4f6963ca0840b19000383)

##### Jak pomóc?
1. Sforkuj repozytorium [ice/framework](https://github.com/ice/framework).
2. Utwórz nową branżę dla każdej funkcji lub ulepszenia.
3. Wyślij żądanie z dowolnej gałęzi zawsze do **dev** branży*.

*proszę nie wysyłać plików z katalogu `build/` (są generowane automatycznie), tylko te z `ice/`.

### Benchmark
[![RPS](/img/doc/benchmark.jpg){.img-responsive}](/doc/introduction/benchmark)

### Instalacja
Ice jest rozszerzeniem w C, więc musisz pobrać plik binarny dla Twojej platformy lub skompilować z kodu źródłowego.

### Pobierz
* openSUSE pakiet: [php5-ice](http://software.opensuse.org/package/php5-ice), [php7-ice](http://software.opensuse.org/package/php7-ice)
* Windows: [DLLs](http://www.iceframework.org/info/download)
* kod źródłowy [wydania stabilne](https://github.com/ice/framework/releases)

### Kompilacja*
* Kompilacja na Linux/Unix/Mac. Są dwa sposoby kompilacji Ice framework*:
 * kompilacja z katalogu `build/` i C-plików - zalecany dla początkujących oraz dla maszyn produkcyjnych; C-pliki są generowane przez zespół Ice po złaczeniu i przejściu testów
 * zbuduj przez [zephir](https://github.com/ice/zephir) (wygeneruj, skompiluj i zainstaluj rozszerzenie) - zalecany dla deweloperów oraz testowania następnego wydania; w ten sposób można wygenerować C-pliki z Zep-plików, dzięki czemu można uzyskać najnowsze funkcje/poprawki, ale to może być niestabilne; po generowaniu przez zephira pliki będą w katalogu `ext/`
* [budowanie na Windows](http://www.iceframework.org//doc/introduction/windows)

*przed kompilacją przeczytaj proszę [Wymagania](#requirements).

***

#### Kompilacja z `build/`:
Sklonuj i zainstaluj z GitHub:
```sh
git clone --depth=1 https://github.com/ice/framework.git
cd framework/
./install
```

###### albo zainstaluj przez [composer](https://getcomposer.org/):
```sh
composer create-project iceframework/framework --no-dev
```

##### Dodaj rozszerzenie do php.ini:
```ini
extension=ice.so
```

Na koniec zrestartuj serwer www

***

#### Zbuduj przez zephir* (wygeneruj, skompiluj i zainstaluj rozszerzenie):
###### Domyślnie `./vendor/bin/zephir` uruchamia zephir. Aby zbudować Ice musisz uruchomić zephira będąc w katalogu `framework/`:
```sh
cd framework/
./vendor/bin/zephir build
```

*jeśli nie masz zephira musisz skompilować zephir.

##### Kompilacja zephira
###### Możesz zainstalować zephir przez composer. Jeśli masz już ice i jesteś w katalogu `framework/` odpal:
```sh
composer update
```

###### albo sklonuj repozytorium zephir:
```sh
mkdir vendor/phalcon/ && cd $_
git clone https://github.com/phalcon/zephir.git

# Skompiluj json-c:
cd zephir/
./install-json

# Zainstaluj zephir
./install

# Stwórz link symboliczny
mkdir ../../bin && cd $_
ln -s ../phalcon/zephir/bin/zephir
```

###### Uruchom zephir pierwszy raz:
```sh
cd framework/
./vendor/bin/zephir
```

***

#### Wymagania
Możesz budować z C-plików lub zbudować przez zephir

##### Aby zbudować rozszerzenie PHP:
* gcc >= 4.4/clang++ >= 3.x/vc++ 11
* gnu make >= 3.81/Windows nmake
* nagłówki i narzędzia deweloperskie php

##### Aby skompilować zephir-parser
* json-c (z GitHub)
* re2c

Ubuntu:
```sh
sudo apt-get install php5-dev libpcre3-dev gcc make re2c
```

Suse:
```sh
sudo zypper install php5-devel pcre-devel gcc make re2c
```

CentOS/Fedora/RHEL
```sh
sudo yum install php-devel pcre-devel gcc make re2c
```

Windows  
[Instalacja Zephira](https://github.com/phalcon/zephir/blob/master/WINDOWS.md)

### Licencja
Ice jest otwartym oprogramowaniem na nowej licencji BSD. Zobacz plik [LICENSE](http://www.iceframework.org/license), aby uzyskać więcej informacji.

***
Prawa autorskie (c) 2014-2015 Zespół Ice.