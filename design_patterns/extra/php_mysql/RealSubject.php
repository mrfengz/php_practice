<?php
include_once "ISubject.php";

class RealSubject implements ISubject
{
    public function request()
    {
        $practice = <<<REQUEST
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="proxy.css">
    <title>Document</title>
</head>
<body>
    <header>PHP Tip Sheet: </header>
    <span class="subhead">
        <ol>
        <li>面向接口编程，而不是面向实现</li>
        <li>优先组合，而不是继承</li>
        <li>类单一职责</li>
        <li>Encapsulate  your objects</li>
        </ol>
    </span>
</body>
</html>

REQUEST;

    }
}
?>

