<?php
// var_dump(phpversion('swoole'));die;  //v 4.6.0

Co::set(['hook_flags' => SWOOLE_HOOK_TCP]);

$dbConfig = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'user' => 'august',
    'password' => 'fz123456',
    'db' => 'example_db'
];

$conn = mysqli_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['db']) or exit(mysqli_connect_error());

$time1 = microtime(true);
Co\run(function() use($conn) {
    for($i=0; $i<100000;$i++) {
        // print_r($conn->query("show tables")->fetch_all(MYSQLI_ASSOC));
        $conn->query("INSERT INTO test2(name, val) VALUE('test', $i)");
    }
});

$conn->close();

$time2 = microtime(true);

echo ($time2 - $time1) . "\n";


