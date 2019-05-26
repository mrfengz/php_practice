<?php
interface IConnectInfo
{
    const HOST = 'localhost';
    const UNAME = 'root';
    const PW = 'root';
    const DBNAME = 'test';

    public static function doConnect();
}