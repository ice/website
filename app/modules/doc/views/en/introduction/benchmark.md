## Benchmark
***
### Environment
There is no special settings/optimizations to boost performance of some frameworks and no cache!  
The testing environment is as follows:

#### Hardware
* CPU: i7-4702MQ up to 3.2 GHz 4 cores 8 threads
* Main Memory: 12GB 1600 MHz DDR3
* Hard Drive: SSD 120GB Read 540MB/s Write 480MB/s

#### Software
* Operating System: openSUSE Tubmleweed 20151128 64-bit
* Kernel: 4.3.0
* Web Server: Nginx 1.8.0
* PHP-FPM: 5.6.15

#### Script
[PHP Framework Benchmark](https://github.com/kenjis/php-framework-benchmark)

### Results
##### Requests per second

![RPS](/img/doc/benchmark.jpg?v3)

##### Peak memory

![Memory](/img/doc/benchmark2.jpg?v3)

##### Execution time

![Time](/img/doc/benchmark3.jpg?v3)

##### Included files

![Files](/img/doc/benchmark4.jpg?v3)

### Table (29 Nov 2015)
|framework          |requests per second|relative|peak memory|relative|files|relative|
|-------------------|------------------:|-------:|----------:|-------:|----:|-------:|
|phalcon-2.0        |           4,991.30|   87.06|       0.29|    1.04|    5|    1.25|
|ice-1.0            |           6,139.83|  107.10|       0.28|    1.00|    4|    1.00|
|tipsy-0.10         |           1,469.09|   25.63|       0.59|    2.11|   18|    4.50|
|fatfree-3.5        |             900.31|   15.70|       0.95|    3.39|    9|    2.25|
|slim-2.6           |             766.89|   13.38|       1.06|    3.79|   24|    6.00|
|ci-3.0             |             556.66|    9.71|       1.18|    4.21|   26|    6.50|
|yii-2.0            |             308.58|    5.38|       2.50|    8.93|   49|   12.25|
|silex-1.3          |             287.22|    5.01|       2.06|    7.36|   64|   16.00|
|fuel-2.0-dev       |             152.04|    2.65|       3.32|   11.86|  128|   32.00|
|cake-3.1           |             162.30|    2.83|       4.08|   14.57|   84|   21.00|
|symfony-2.7        |              96.14|    1.68|       7.90|   28.21|  106|   26.50|
|laravel-5.1        |              86.77|    1.51|       7.59|   27.11|   39|    9.75|
|zf-2.5             |              57.33|    1.00|       6.39|   22.82|  204|   51.00|