<?php
include_once "UniversalConnect.php";

class CreateTable
{
    private $tableMaster;
    private $hookup;

    public function __construct()
    {
        $this->tableMaster = "proxyLog";
        $this->hookup = UniversalConnect::doConnect();

        $drop = "Drop table if exists $this->tableMaster";

        if ($this->hookup->query($drop) === true) {
            printf("Olc table %s has been dropped.<br>", $this->tableMaster);
        }

        $sql = "create table $this->mableMaster (uname varchar(15), pw varchar(120))";
        if (true === $this->hookup->query($sql)) {
            echo "Table $this->tableMaster has been created successfully.<br>";
        }

        $this->hookup->close();
    }
}

$worker = new CreateTable();