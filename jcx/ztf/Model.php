<?php
namespace ztf;

use Medoo\Medoo;

class Model extends Medoo
{
    protected $conn;


    public function __construct()
    {
        $conf = Config::getAll('database');
        parent::__construct($conf);
    }
}