<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/12/7
 * Time: 19:17
 */
namespace ztf\exceptions;

use Throwable;

class NotFoundException extends \Exception
{
    public function __construct($message = "", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}