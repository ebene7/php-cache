<?php

require __DIR__ . '/../vendor/autoload.php';

use E7\Cache\Simple\ArrayCache;
use E7\Cache\Simple\FileCache;
use E7\Cache\Frontend\Passthru;
use E7\Cache\Frontend\Callback;
use E7\Cache\Frontend\Object;

//--

class Foo {
    public function __invoke() {
        return __FUNCTION__ . PHP_EOL . call_user_func_array([$this, 'bar'], func_get_args());
    }

    public function bar($test = 42) {
        return __FUNCTION__ . "#$test#" . time();
    }
}
$foo = new Foo();



//$cache = new ArrayCache();
$cache = new FileCache();
//$front = new Passthru($cache, $foo, ['default_ttl' => 3]);
$front = new Passthru($cache, 'date', ['default_ttl' => 3]);

$front->getCache()->clear();

//for ($i=0; $i<10; $i++) {
//    echo call_user_func($front, 'H:i:s fo') . PHP_EOL;
//
////    echo $front('Y-m-D H:i:s') . PHP_EOL;
//    sleep(1);
//
//}

    print_r($front);








