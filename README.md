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
You can disable sending data by editing the following line:
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
docker run --rm scalecommerce/php-bench:7.3.15

# via ssh or cli
curl -Lso- https://raw.githubusercontent.com/ScaleCommerce/php-benchmark/master/benchmark.php | php
```