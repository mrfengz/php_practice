<?php
require_once 'WebController.php';

try {
    $controller = new WebController();

    $response = $controller->execute();

    echo $response;
} catch(\Exception $e) {
    echo strval($e);
}
