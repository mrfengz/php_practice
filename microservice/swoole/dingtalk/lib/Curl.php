<?php
class Curl
{
    public static function httpRequest($url, $data = [], $method = 'get', $header = [])
    {
        // 初始化
        $curl = curl_init();
        // 访问的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        // 只获取页面内容，但不输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 验证是否是https请求
        if (substr($url, 0, 5) == 'https') {
            // https请求，不验证证书
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            // https请求，不验证HOST
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($method == 'POST') {
            // 设置请求方式为POST请求
            curl_setopt($curl, CURLOPT_POST, true);
            // POST请求数据
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        // 执行CURL请求
        $result = curl_exec($curl);
        // 关闭curl，释放资源
        curl_close($curl);
        return $result;
    }
}