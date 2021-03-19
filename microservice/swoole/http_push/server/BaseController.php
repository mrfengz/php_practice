<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/19
 * Time: 16:35
 */
use Swoole\Http\Request;
use Swoole\Http\Response;

class BaseController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var \Swoole\Server
     */
    protected $server;

    public function __construct($request, $response, $server)
    {
        $this->request = $request;
        $this->response= $response;
        $this->server = $server;
    }
}