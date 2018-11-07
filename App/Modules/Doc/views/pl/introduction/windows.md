## Budowanie na Windows
***
Postępuj zgodnie z poniższymi instrukcjami, aby zbudować Ice w systemie Windows. Podręcznik ten jest oparty na [Build your own PHP on Windows](https://wiki.php.net/internals/windows/stepbystepbuild_sdk_2) i pokazuje jak zbudować używając **Visual C++ 15.0** (Visual Studio 2017) dla **PHP 7.2+**.

#### Wymagania
1. Pobierz _Visual C++ 15.0_ kompilator, jest w [Visual Studio 2017 Community](https://visualstudio.microsoft.com/vs/community/).
2. Pobierz kod źródłowy PHP z [Wydania stabilne](http://windows.php.net/download/) (kliknij _Download source code_).
3. Pobierz narzędzia binarne z [php-sdk-binary-tools](https://github.com/Microsoft/php-sdk-binary-tools).
4. Pobierz [Ice framework](https://github.com/ice/framework/releases).

#### Przygotowanie budowy
> Używaj **x64 Native Tools Command Prompt**. Wszystkie polecenia w dalszej części tego poradnika powinny być uruchamiane w tym właśnie wierszu poleceń.

1. Utwórz katalog budowy `c:\php-sdk`.
2. Rozpakuj archiwum _narzędzia binarne_ do tego katalogu.
3. Otwórz okno wiersza polecenia, przejdź do katalogu budowy i uruchom skrypt startowy:
```sh
cd c:\php-sdk\
phpsdk-vc15-x64.bat
```
4. Uruchom skrypt wsadowy _buildtree_, który utworzy pożądaną strukturę katalogów:
```sh
bin\phpsdk_buildtree.bat phpdev
```
* Skrypt buildtree nie został zaktualizowany do nowszej wersji _VC++_, więc skopiuj `C:\php-sdk\phpdev\vc14` do `C:\php-sdk\phpdev\vc15`.
5. Rozpakuj _kod źródłowy PHP_ do `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src` jeśli _x64_ to twoja architektura i _7.2.11_ jest numerem wersji.
6. Użyj PHP SDK tools, żeby automatycznie pobrać odpowiednie zależności:
```
cd phpdev\vc15\x64\php-7.2.11-src
c:\php-sdk\bin\phpsdk_deps.bat -u
```
7. Rozpakuj _kod źródłowy Ice_, skopiuj i zamień katalog `build\php7\` do `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src\ext\ice`.

#### Kompilacja
1. Otwórz okno wiersza polecenia, przejdź do katalogu budowy i skonfiguruj zmienne środowiska budowy:
```sh
cd c:\php-sdk\
phpsdk-vc15-x64.bat
```
2. Zmień katalog na miejsce kodu źródłowego PHP i uruchom _buildconf_.
```sh
cd C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src
buildconf
```
3. Stwórz swoją komendę konfiguracyjną i zbuduj PHP:
```sh
configure --disable-all --disable-zts --enable-cli --enable-ice=shared
nmake
```

* Plik _php\_ice.dll_ jest teraz w `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src\x64\Release`.  Jeśli kompilowałeś bez opcji _--disable-zts_ plik _php\_ice.dll_ będzie w `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src\x64\Release_TS`.

#### Rekompilacja (z opcją bezpieczeństwa wątków)
Rekompilacja po wykonaniu pewnych zmian:
1. Oczyścić ze starych plików binarnych, zrekonfiguruj i zbuduj PHP:
```sh
nmake clean
buildconf --force
configure --disable-all --enable-cli --enable-ice=shared
nmake
```