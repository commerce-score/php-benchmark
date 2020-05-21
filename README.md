# php-benchmark
This benchmark measures php runtime performance on your server. The result will heavily depend on your cpu. The script does not benchmark network or storage. It's absolutely safe to run, because only one cpu core will be used for a couple of seconds.

## Sending results to commerce-score.io
By default this script will send anonymized results to commerce.score.io. We use this data to calculate median values per cpu type and php version. You can see the current values here: https://commerce-score.io/en/benchmark.

Only the following data will be transmitted:
```
test runtimes
php version
php api
os release
cpu type
cpu frequency
cpu numer of cores
```
Sample of anonymized data:
```
Start:               2020-03-06 23:46:34
PHP Version:         7.4.3
Platform:            Linux
OS Release:          4.15.0-88-generic
PHP API:             cli
OPcache:             0
CPU:                 Intel(R) Xeon(R) E-2176G CPU @ 3.70GHz
CPU Frequency:       4390.742 MHz
CPU Cores:           12
test_math:           0.187 sec.
test_strings:        0.140 sec.
test_loops:          0.151 sec.
test_ifelse:         0.097 sec.
test_hashing:        0.092 sec.
test_crypto:         0.318 sec.
test_multibyte:      0.162 sec.
Total time:          1.147 sec.
```
The script will not collect any other data. You can disable sending data by editing the following line:
```
define('CALL_COMMERCE_SCORE_API', getenv('CALL_COMMERCE_SCORE_API') ?: false);
```
or by setting the environment variable:
```
CALL_COMMERCE_SCORE_API=false
```

## how to run the script
```
# via docker
docker run --rm commerce-score/php-benchmark:7.3.18

# via ssh or cli
curl -Lso- https://raw.githubusercontent.com/commerce-score/php-benchmark/master/benchmark.php | php
```