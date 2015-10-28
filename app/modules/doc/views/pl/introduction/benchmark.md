## Benchmark
***
### Środowisko
Sprzęt użyty do testowania jest następujący:

#### Sprzęt
* Procesor: i7-4702MQ up to 3.2 GHz 4 rdzenie 8 wątków
* Pamięć: 12GB 1600 MHz DDR3
* Dysk: SSD 120GB odczyt 540MB/s zapis 480MB/s

#### Oprogramowanie
* System operacyjny: openSUSE Tubmleweed 20151017 64-bit
* Kernel: 4.2.1
* Serwer www: Nginx 1.8.0
* PHP-FPM: 5.6.14

#### Skrypt
[PHP Framework Benchmark](https://github.com/kenjis/php-framework-benchmark)

### Wyniki
##### Żądania na sekundę

![RPS](/img/doc/benchmark.jpg)

##### Użyta pamięć

![Memory](/img/doc/benchmark2.jpg)

##### Czas wykonywania

![Time](/img/doc/benchmark3.jpg)

##### Dołączonych plików

![Files](/img/doc/benchmark4.jpg)

### Table
|framework          |requests per second|relative|peak memory|relative|files|relative|
|-------------------|------------------:|-------:|----------:|-------:|----:|-------:|
|phalcon-2.0        |           3,966.57|   56.58|       0.29|    1.04|    5|    1.25|
|ice-1.0            |           4,543.69|   64.82|       0.28|    1.00|    4|    1.00|
|fatfree-3.5        |             753.43|   10.75|       0.95|    3.39|    9|    2.25|
|slim-2.6           |             648.14|    9.25|       1.06|    3.79|   24|    6.00|
|ci-3.0             |             449.43|    6.41|       1.18|    4.21|   26|    6.50|
|yii-2.0            |             286.09|    4.08|       2.50|    8.93|   49|   12.25|
|silex-1.3          |             254.76|    3.63|       2.13|    7.61|   64|   16.00|
|fuel-2.0-dev       |             115.81|    1.65|       3.32|   11.86|  128|   32.00|
|cake-3.1           |             134.09|    1.91|       4.08|   14.57|   84|   21.00|
|symfony-2.7        |              70.10|    1.00|       7.89|   28.18|  106|   26.50|
|laravel-5.1        |              81.44|    1.16|       7.59|   27.11|   39|    9.75|
|zf-2.5             |              87.08|    1.24|       6.39|   22.82|  204|   51.00|