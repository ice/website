## Build on Windows
***
Follow these instructions to build Ice in your Windows system. This guide is based on [Build your own PHP on Windows](https://wiki.php.net/internals/windows/stepbystepbuild) and shows how to build using *Visual C++ 11.0* (Visual Studio 2012) for *PHP 5.5 or 5.6*.

#### Requirements
1. If compiling *PHP 5.5 or 5.6* install [Visual Studio 2012 Express for Windows Desktop](https://www.microsoft.com/en-us/download/details.aspx?id=34673).
* To compile *PHP 7.0+* you need _Visual C++ 14.0_ (Visual Studio 2015).
2. Get the PHP source from [Stable releases](http://windows.php.net/download/) (click the _Download source code_).
3. Get the binary tools from [php-sdk](http://windows.php.net/downloads/php-sdk/) the binary tools archives are named eg. _php-sdk-binary-tools-20110915.zip_ (get the latest one) and the libraries on which PHP depends eg. _deps-5.6-vc11-x64.7z_
4. Get the [Ice framework](https://github.com/ice/framework/releases).

#### Prepare build
> Use the _VS2012 x64 Cross Tools Command Prompt_. All commands in the rest of this document should be run in the appropriate command prompt.

1. Create the build directory `c:\php-sdk`.
2. Unpack the _binary tools_ archive into this directory, it should contain three sub-directories: `bin\`, `script\` and `share\`.
3. Open the command prompt, enter the build directory and run the _buildtree_ batch script which will create the desired directory structure:
```sh
cd c:\php-sdk\
bin\phpsdk_buildtree.bat phpdev
```
4. The buildtree script hasn't been updated for newer versions of _VC++_, so copy `C:\php-sdk\phpdev\vc9` to `C:\php-sdk\phpdev\vc11`.
5. Extract the _PHP source_ code to `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src` if _x64_ is your architecture and _5.6.16_ is the version number.
6. In the same directory where you extracted the PHP source there is a `deps\` directory. Here you will need to extract the libraries required to build PHP, which you downloaded in the previous step (eg. _deps-5.6-vc11-x64.7z_).
7. Extract _Ice source_ code and copy & rename the `build\PHP5\` directory to `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src\ext\ice` directory.

#### Compilation
1. Open the command prompt, enter the build directory and set up the build environment variables:
```sh
cd c:\php-sdk\
bin\phpsdk_setvars.bat
```
2. Change directory to the location of your PHP source code and run _buildconf_.
```sh
cd C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src
buildconf
```
3. Create your configure command and build PHP:
```sh
configure --disable-all --disable-zts --enable-cli --enable-ice=shared
nmake
```

* The _php\_ice.dll_ file is now under `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src\x64\Release`.  If you compiled without _--disable-zts_ the _php\_ice.dll_ file will be under `C:\php-sdk\phpdev\vc11\x64\php-5.6.16-src\x64\Release_TS`.

#### Recompile
Recompile after you have done some changes:
1. Clean up old compiled binaries:
```sh
nmake clean
buildconf --force
```

2. Create your configure command and build PHP:
```sh
configure --disable-all --disable-zts --enable-cli --enable-ice=shared
nmake
```