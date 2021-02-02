<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/2
 * Time: 16:32
 */

class WebController
{
    private $request;
    private $requestUri;

    public function __construct(\Swoole\Http\Request $request)
    {
        $this->request = $request;
        $this->requestUri = $this->request->server['request_uri'];
        echo $this->requestUri . "\n";
    }

    public function login()
    {
        $postData = $this->request->post;

    }

    // public function __call($method)
    // {
    //     return $this->method($this->request);
    // }


}