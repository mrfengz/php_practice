<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/18
 * Time: 14:19
 */
namespace Application\Database;

use PDO;

class Connection
{
    public $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=stock', 'august', 'fz123456', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }
}