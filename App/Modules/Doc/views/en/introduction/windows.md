## Build on Windows
***
Follow these instructions to build Ice in your Windows system. This guide is based on [Build your own PHP on Windows](https://wiki.php.net/internals/windows/stepbystepbuild_sdk_2) and shows how to build using **Visual C++ 15.0** (Visual Studio 2017) for **PHP 7.2+**.

#### Requirements
1. Get the _Visual C++ 15.0_ compiler, it's in [Visual Studio 2017 Community](https://visualstudio.microsoft.com/vs/community/).
2. Get the PHP source from [Stable releases](http://windows.php.net/download/) (click the _Download source code_).
3. Get the latest binary tools from [php-sdk-binary-tools](https://github.com/Microsoft/php-sdk-binary-tools).
4. Get the [Ice framework](https://github.com/ice/framework/releases).

#### Prepare build
> Use the **x64 Native Tools Command Prompt**. All commands in the rest of this document should be run in the appropriate command prompt.

1. Create the build directory `c:\php-sdk`.
2. Unpack the _binary tools_ archive into this directory.
3. Open the command prompt, enter the build directory and invoke the starter script:
```
cd c:\php-sdk\
phpsdk-vc15-x64.bat
```
4. Run the _buildtree_ batch script which will create the desired directory structure:
```sh
bin\phpsdk_buildtree.bat phpdev
```
* The buildtree script hasn't been updated for newer versions of _VC++_, so copy `C:\php-sdk\phpdev\vc14` to `C:\php-sdk\phpdev\vc15`.
5. Extract the _PHP source_ code to `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src` if _x64_ is your architecture and _7.2.11_ is the version number.
6. Use the PHP SDK tools to fetch the suitable dependencies automatically by calling:
```
cd phpdev\vc15\x64\php-7.2.11-src
c:\php-sdk\bin\phpsdk_deps.bat -u
```
7. Extract _Ice source_ code, copy & rename the `build\php7\` directory to `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src\ext\ice` directory.

#### Compilation
1. Open the command prompt, enter the build directory and set up the build environment variables:
```sh
cd c:\php-sdk\
phpsdk-vc15-x64.bat
```
2. Change directory to the location of your PHP source code and run _buildconf_.
```sh
cd C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src
buildconf
```
3. Create your configure command and build PHP:
```sh
configure --disable-all --disable-zts --enable-cli --enable-ice=shared
nmake
```

* The _php\_ice.dll_ file is now under `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src\x64\Release`.  If you compiled without _--disable-zts_ the _php\_ice.dll_ file will be under `C:\php-sdk\phpdev\vc15\x64\php-7.2.11-src\x64\Release_TS`.

#### Recompile (with Thread Safe option)
Recompile after you have done some changes:
1. Clean up old compiled binaries, reconfigure and build PHP:
```sh
nmake clean
buildconf --force
configure --disable-all --enable-cli --enable-ice=shared
nmake
```