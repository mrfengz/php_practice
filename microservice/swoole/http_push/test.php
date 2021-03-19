<?php
include __DIR__ . '/../Application/Autoload/Loader.php';
\Application\Autoload\Loader::init(__DIR__ . '/..');

$obj = new \Application\Database\Connection();

foreach ($obj->pdo->query("SHOW tables;")->fetchAll(PDO::FETCH_ASSOC) as $row) {
    print_r($row);
}