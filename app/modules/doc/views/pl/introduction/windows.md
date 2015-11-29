## Budowanie na Windows
***
Postępuj zgodnie z poniższymi instrukcjami, aby zbudować Ice w systemie Windows. Podręcznik ten jest oparty na [Build your own PHP on Windows](https://wiki.php.net/internals/windows/stepbystepbuild) i pokazuje jak zbudować używając *Visual C++ 11.0* (Visual Studio 2012) dla *PHP 5.5 or 5.6*.

#### Wymagania
1. Jeśli kompilujesz *PHP 5.5 lub 5.6* zainstaluj [Visual Studio 2012 Express for Windows Desktop](https://www.microsoft.com/en-us/download/details.aspx?id=34673).
* Aby skompilować *PHP 7.0+* potrzebujesz _Visual C++ 14.0_ (Visual Studio 2015) i przebudować Ice przez _zephir_.
2. Pobierz kod źródłowy PHP z [Wydania stabilne](http://windows.php.net/download/) (kliknij _Download source code_).
3. Pobierz narzędzia binarne z [php-sdk](http://windows.php.net/downloads/php-sdk/) nazwa archiwum narzędzi binarnych to np. _php-sdk-binary-tools-20110915.zip_ (pobierz ostatnią wersję) i biblioteki od których PHP zależy np. _deps-5.6-vc11-x64.7z_
4. Pobierz [Ice framework](https://github.com/ice/framework/releases).

#### Przygotowanie budowy
> Używaj _VS2012 x64 Cross Tools Command Prompt_. Wszystkie polecenia w dalszej części tego poradnika powinny być uruchamiane w tym właśnie wierszu poleceń.

1. Utwórz katalog budowy `c:\php-sdk`.
2. Rozpakuj archiwum _narzędzia binarne_ do tego katalogu, powinien zawierać trzy podkatalogi: `bin/`, `script/` i `share/`.
3. Otwórz okno wiersza polecenia, przejdź do katalogu budowy i uruchom skrypt wsadowy _buildtree_, który utworzy pożądaną strukturę katalogów:
```sh
cd c:\php-sdk\
bin\phpsdk_buildtree.bat phpdev
```
4. Skrypt buildtree nie został zaktualizowany do nowszej wersji _VC++_, więc skopiuj `C:\php-sdk\phpdev\vc9` do `C:\php-sdk\phpdev\vc11`.
5. Rozpakuj _kod źródłowy PHP_ do `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src` jeśli _x64_ to twoja architektura i _5.6.16_ jest numerem wersji.
6. W tym samym katalogu, do którego rozpakowano źródła PHP jest katalog `deps/`. Tu trzeba będzie rozpakować biblioteki wymagane do budowy PHP, które zostały pobrane w poprzednim kroku (np. _deps-5.6-vc11-x64.7z_).
7. Rozpakuj _kod źródłowy Ice_ i skopiuj zawartość katalogu `ext/` do katalogu `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src\ext\ice`.

#### Kompilacja
1. Otwórz okno wiersza polecenia, przejdź do katalogu budowy i skonfiguruj zmienne środowiska budowy:
```sh
cd c:\php-sdk\
bin\phpsdk_setvars.bat
```
2. Zmień katalog na miejsce kodu źródłowego PHP i uruchom _buildconf_.
```sh
cd C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src
buildconf
```
3. Stwórz swoją komendę konfiguracyjną i zbuduj PHP:
```sh
configure --disable-all --disable-zts --enable-cli --enable-ice=shared
nmake
```

* Plik _php\_ice.dll_ jest teraz w `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src\x64\Release`.  Jeśli kompilowałeś bez opcji _--disable-zts_ plik _php\_ice.dll_ będzie w `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src\x64\Release_TS`.

#### Rekompilacja
Rekompilacja po wykonaniu pewnych zmian:
```sh
nmake clean
buildconf --force
configure --disable-all --disable-zts --enable-cli --enable-ice=shared
nmake
```