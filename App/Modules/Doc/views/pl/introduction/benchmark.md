## Benchmark
***
### Środowisko
Nie ma żadnych specjalnych ustawień/optymalizacji w celu zwiększenia wydajności niektórych frameworków i nie ma cachowania!  
Środowisko testowe jest następujące:

#### Sprzęt
* Procesor: i7-5820K do 3.6GHz 6 rdzeni 16 wątków
* Pamięć: 16GB 2133MHz DDR4
* Dysk: NVMe SSD 256GB odczyt 2200MB/s zapis 900MB/s

#### Oprogramowanie
* System operacyjny: openSUSE Tubmleweed 20160329 64-bit
* Kernel: 4.5.0
* Serwer www: Nginx 1.8.1
* PHP-FPM: 5.6.19

#### Skrypt
[PHP Framework Benchmark](https://github.com/kenjis/php-framework-benchmark)

### Wyniki
##### Żądania na sekundę

![RPS](/img/doc/benchmark.jpg?v=31032016)

##### Użyta pamięć

![Memory](/img/doc/benchmark2.jpg?v=31032016)

##### Czas wykonywania

![Time](/img/doc/benchmark3.jpg?v=31032016)

##### Dołączonych plików

![Files](/img/doc/benchmark4.jpg?v=31032016)

### Table (31 Marz 2016)
|framework          |requests per second|relative|peak memory|relative|files|relative| 
|-------------------|------------------:|-------:|----------:|-------:|----:|-------:| 
|phalcon-2.0        |              7,120|   53.19|       0.29|    1.05|    5|    1.25| 
|ice-1.1            |              8,062|   60.22|       0.28|    1.02|    4|    1.00| 
|tipsy-0.10         |              2,426|   18.12|       0.59|    2.14|   18|    4.50| 
|fatfree-3.5        |              1,521|   11.36|       0.95|    3.45|    9|    2.25| 
|slim-2.6           |              1,257|    9.39|       1.06|    3.85|   24|    6.00| 
|ci-3.0             |                894|    6.68|       1.18|    4.28|   26|    6.50| 
|yii-2.0            |                540|    4.03|       2.49|    9.03|   49|   12.25| 
|silex-1.3          |                507|    3.79|       2.06|    7.47|   64|   16.00| 
|fuel-1.8-dev       |                534|    3.99|       1.98|    7.18|   45|   11.25| 
|cake-3.1           |                259|    1.94|       4.02|   14.59|   84|   21.00| 
|symfony-3.0        |                134|    1.00|       7.69|   27.90|  214|   53.50| 
|laravel-5.1        |                148|    1.11|       7.32|   26.56|   39|    9.75| 
|zf-2.5             |                158|    1.18|       6.34|   23.00|  204|   51.00|