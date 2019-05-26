<?php
include_once "IConnectInfo.php";
class UniversalConnect implements IConnectInfo
{
    private static $server = IConnectInfo::HOST;
    private static $currentDB = IConnectInfo::DBNAME;
    private static $user = IConnectInfo::UNAME;
    private static $pass = IConnectInfo::PW;
    private static $hookup;

    public static function doConnect()
    {
        // TODO: Implement doConnect() method.
        self::$hookup = mysqli_connect(self::$server, self::$user, self::$pass, self::$currentDB);
        if (self::$hookup) {
            echo "Success connection to mysql";
        } elseif(mysqli_connect_error()) {
            echo "Connection failed: " . mysqli_connect_error();
        }

        return self::$hookup;
    }
}