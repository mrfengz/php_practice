<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/1
 * Time: 16:20
 */

include_once 'Curl.php';

class Robot
{
    CONST API_URL = 'https://oapi.dingtalk.com/robot/send?access_token=';

    private $accessToken;
    private $secret = 'SECfddd7b40620a4c1f8308e3fbd5f55e10531db2fce879129586edb3df41c5530b';

    public function __construct(array $config)
    {
        if (empty($config['access_token'])) {
            throw new \InvalidArgumentException("参数必须有access_token", 1);
        }
        $this->accessToken = $config['access_token'];
        if ($config['secret']) $this->secret = $config['secret'];
    }
    /**
     * 发送钉钉通知消息
     * author: august 2021/2/1
     * @param string $message   文本
     * @param array $atMobiles @手机号，须与钉钉用户手机号一致
     * @param bool $isAtAll 是否@所有人
     * @return bool|string
     */
    function sendText(string $message, array $atMobiles = [], $isAtAll = false)
    {
        $url = $this->addSign();
        $data = [
            'msgtype' => 'text',
            'text' => [
                'content' => $message,
            ],
            'at' => [
                'atMobiles' => $atMobiles,
                'isAtAll' => $isAtAll,
            ]
        ];
        $dataString = json_encode($data);
        $result = Curl::httpRequest($url, $dataString, 'POST', array ('Content-Type: application/json;charset=utf-8'));
        return $result;
    }

    /**
     * 如果配置了签名，请求url中带上签名信息
     * author: august 2021/2/1
     * @return string
     */
    private function addSign()
    {
        $url = self::API_URL . $this->accessToken;
        if (!$this->secret) return $url;

        $timestamp = round(microtime(true) * 1000);
        $sign = urlencode(base64_encode(hash_hmac('sha256', $timestamp . "\n" . $this->secret, $this->secret, true)));
        $url .= '&timestamp=' . $timestamp . '&sign=' .$sign;
        return $url;
    }
}