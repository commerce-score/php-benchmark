<?php
/*
##########################################################################
#                      PHP Benchmark Performance Script                  #
#                         © 2010 Code24 BV                               #
#                                                                        #
#  Author      : Alessandro Torrisi                                      #
#  Company     : Code24 BV, The Netherlands                              #
#  Date        : July 31, 2010                                           #
#  version     : 1.0                                                     #
#  License     : Creative Commons CC-BY license                          #
#  Website     : http://www.php-benchmark-script.com                     #
#  Extended By : ScaleCommerce GmbH, 2020, https://commerce-score.io     #
#                                                                        #
##########################################################################
*/

// To disable sending results to commerce score set this to false:
define('CALL_COMMERCE_SCORE_API', getenv('CALL_COMMERCE_SCORE_API') ?: true);

define('API_URL', getenv('API_URL') ?: 'https://commerce-score.io/api/v1/benchmark/result');
define('API_AUTHENTICATION', getenv('API_AUTHENTICATION') ?: '');
define('API_KEY', getenv('API_KEY') ?: 'BFLhDyhXEARJMeN3xCECXF9Nq389NMs2');
define('TEST_RUNS', getenv('TEST_RUNS') ?: 3);

define('STRING', 'The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.');
define('UMLAUT_STRING', 'Wörter mit Umlauten: Stoßdämpfer, Steuerprüfung, Käfer, Schätzchen, Äpfel, Röstzwiebel.');


function test_Math($count = 140000)
{
    $time_start = microtime(true);
    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    foreach ($mathFunctions as $key => $function) {
        if (!function_exists($function)) unset($mathFunctions[$key]);
    }
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            $r = call_user_func_array($function, array($i));
        }
    }
    return number_format(microtime(true) - $time_start, 3);
}


function test_Strings($count = 100000)
{
    $time_start = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");
    $string = STRING;
    foreach ($stringFunctions as $key => $function) {
        if (!function_exists($function)) unset($stringFunctions[$key]);
    }
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            $r = call_user_func_array($function, array($string));
        }
    }
    return number_format(microtime(true) - $time_start, 3);
}


function test_Loops($count = 19000000)
{
    $time_start = microtime(true);
    for ($i = 0; $i < $count; ++$i) ;
    $i = 0;
    while ($i < $count) ++$i;
    return number_format(microtime(true) - $time_start, 3);
}


function test_IfElse($count = 9000000)
{
    $time_start = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {
        } elseif ($i == -2) {
        } else if ($i == -3) {
        }
    }
    return number_format(microtime(true) - $time_start, 3);
}

function test_Hashing($count = 100000) {
    $time_start = microtime(true);
    $hashFunctions = array("md5", "sha1", "sha256", "sha512", "crc32");
    $string = STRING;
    foreach ($hashFunctions as $key => $function) {
        if (!function_exists($function)) unset($hashFunctions[$key]);
    }
    for ($i=0; $i < $count; $i++) {
        foreach ($hashFunctions as $function) {
            $r = hash($function, $string, $i % 2 == 0);
        }
    }
    return number_format(microtime(true) - $time_start, 3);
}

function test_Crypto($count = 100000) {
    $time_start = microtime(true);
    $cryptoFunctions = array("crypt");
    $string = STRING;
    foreach ($cryptoFunctions as $key => $function) {
        if (!function_exists($function)) unset($cryptoFunctions[$key]);
    }
    for ($i=0; $i < $count; $i++) {
        foreach ($cryptoFunctions as $function) {
            $r = call_user_func_array($function, array($string, "{$i}"));
        }
    }
    return number_format(microtime(true) - $time_start, 3);
}

function test_MultiByte($count = 60000) {
    $time_start = microtime(true);
    $mbFunctions = array("mb_strlen", "mb_strwidth", "mb_strtoupper", "mb_strtolower", "mb_strrev");
    $string = STRING;
    foreach ($mbFunctions as $key => $function) {
        if (!function_exists($function)) unset($mbFunctions[$key]);
    }
    for ($i=0; $i < $count; $i++) {
        foreach ($mbFunctions as $function) {
            $r = call_user_func_array($function, array($string));
        }
    }
    return number_format(microtime(true) - $time_start, 3);
}

function median($runs) {
    rsort($runs);
    $count = count($runs);
    if ($count % 2 == 1) {
        $mid = ceil($count / 2);
        return $runs[$mid];
    } else {
        $mid1 = $count / 2 - 1;
        $mid2 = $count / 2;
        return ($runs[$mid1] + $runs[$mid2]) / 2;
    }
}

// --------- Get PHP version ---------
// Used in hacks/fixes checks
$phpversion = explode('.', PHP_VERSION);

$dropDead = false;
// No php < 4
if ((int)$phpversion[0] < 4) {
    $dropDead = true;
}
// No php <= 4.3
if ((int)$phpversion[0] == 4 && (int)$phpversion[1] < 3) {
    $dropDead = true;
}
if ($dropDead) {
    print('<<< ERROR >>> Need PHP 4.3+! Current version is ' . PHP_VERSION .PHP_EOL);
    exit(1);
}
if (!defined('PHP_MAJOR_VERSION')) {
    define('PHP_MAJOR_VERSION', (int)$phpversion[0]);
}
if (!defined('PHP_MINOR_VERSION')) {
    define('PHP_MINOR_VERSION', (int)$phpversion[1]);
}
if (!defined('PHP_RELEASE_VERSION')) {
    define('PHP_RELEASE_VERSION', (int)$phpversion[2]);
}
$phpVersion = PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'.'.PHP_RELEASE_VERSION;
// --------- End get PHP version ---------

// --------- Get Platform ---------
$platform = PHP_OS;
$sapi = php_sapi_name();
$os_release = php_uname('r');
// --------- End get Platform ---------

// --------- Get ini settings ---------
if (strtolower($sapi) == "cli") {
    $opcache=intval(ini_get('opcache.enable_cli'));
} else {
    $opcache=intval(ini_get('opcache.enable'));
}
// --------- End get ini settings ---------

// --------- Get CPU info ---------
$cpu = [
    'type' => '',
    'mhz' => '',
    'cores' => '',
    'available' => ''
];
if (file_exists('/proc/cpuinfo')) {
    $cpuInfos = explode("\n", file_get_contents('/proc/cpuinfo'));
    foreach ($cpuInfos as $line) {
        $line = explode(':', $line, 2);
        if (!isset($line[1])) {
            continue;
        }
        $key = trim($line[0]);
        $value = trim($line[1]);

        // What we want are bogomips, MHz, processor, and Model.
        switch ($key) {
            // CPU model
            case 'model name':
            case 'cpu':
            case 'Processor':
                if (empty($cpu['type'])) {
                    $cpu['type'] = $value;
                }
                break;
            // Speed in MHz
            case 'cpu MHz':
                if (empty($cpu['mhz']) || $cpu['mhz'] < (float)$value) {
                    $cpu['mhz'] = (float)$value;
                }
                break;
            case 'Cpu0ClkTck': // Old sun boxes
                if (empty($cpu['mhz'])) {
                    $cpu['mhz'] = (int)hexdec($value) / 1000000.0;
                }
                break;
            case 'bogomips': // twice of MHz usualy on Intel/Amd
            case 'BogoMIPS': // twice of MHz usualy on Intel/Amd
                if (empty($cpu['mhz'])) {
                    $cpu['mhz'] = (float)$value / 2.0;
                }
                if (empty($cpu['mips'])) {
                    $cpu['mips'] = (float)$value / 2.0;
                }
                break;
            // cores
            case 'cpu cores':
                if (empty($cpu['cores'])) {
                    $cpu['cores'] = (int)$value;
                }
                break;
            case 'processor':
            case 'core id':
                if (empty($cpu['available'])) {
                    $cpu['available'] = (int)$value+1;
                } else {
                    if ($cpu['available'] < (int)$value+1) {
                        $cpu['available'] = (int)$value+1;
                    }
                }
                break;
        }
    }
}
else {
    # TODO: implement cpu detection for mac and win
    $cpu['available'] = 0;
    $cpu['type'] = "unknown";
    $cpu['mhz'] = 0;
}
// --------- End get CPU info ---------

$total = 0;
$data = array();
$functions = get_defined_functions();
if(!ini_get('date.timezone')) {
    // Prevent PHP warning in case timezone does not set
    date_default_timezone_set('GMT');
}
$time = date('Y-m-d H:i:s');
$output = "Start: ".$time.
    "\nPHP Version: ".$phpVersion.
    "\nPlatform: ".$platform.
    "\nOS Release: ".$os_release.
    "\nPHP API: ".$sapi.
    "\nOPcache: ".$opcache.
    "\nCPU: ".$cpu['type'].
    "\nCPU Frequency: ".$cpu['mhz'].' MHz'.
    "\nCPU Cores: ".$cpu['available'] ."\n";
$data['base']['time'] = ['label' => 'Start', 'value' => $time];
$data['base']['sapi'] = ['label' => 'PHP API', 'value' => $sapi];
$data['base']['opcache'] = ['label' => 'OPcache', 'value' => $opcache];
$data['base']['php'] = ['label' => 'PHP version', 'value' => $phpVersion];
$data['base']['os_release'] = ['label' => 'OS Release', 'value' => $os_release];
$data['base']['platform'] = ['label' => 'Platform', 'value' => $platform];
$data['base']['cpu_type'] = ['label' => 'CPU Type', 'value' => $cpu['type']];
$data['base']['cpu_frequency'] = ['label' => 'Frequency', 'value' => $cpu['mhz']];
$data['base']['cpu_cores'] = ['label' => 'Cores', 'value' => $cpu['available']];
$data['test'] = array();
foreach ($functions['user'] as $method) {
    if (preg_match('/^test_/', $method)) {
        $ret = [];
        for ($i = 0; $i < TEST_RUNS; $i++) {
            $ret[] = $method();
        }
        $total += $result = median($ret);
        $output .= "$method: " . $result ." sec.\n";
        $data['test'][] = ['label' =>$method, 'value' => $result];
    }
}
$output .= "Total time: ". $total ." sec.";

if (CALL_COMMERCE_SCORE_API === true) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    if (defined('API_KEY') && API_KEY) {
        $headers = [
            'X-AUTH-TOKEN: '.API_KEY,
            'Content-Type: application/json'
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    if (defined('API_AUTHENTICATION') && API_AUTHENTICATION) {
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, API_AUTHENTICATION);
    }
    curl_setopt($curl, CURLOPT_URL, API_URL);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
}

header('Content-Type: text/plain');
$lines = explode("\n", $output);
foreach ($lines as $line) {
    $columns = explode(":", $line, 2);
    echo str_pad($columns[0] . ":", 20);
    echo $columns[1] . "\n";
}
