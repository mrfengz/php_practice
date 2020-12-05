<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2020/4/17
 * Time: 19:50
 */
use Elasticsearch\ClientBuilder;

require dirname(__DIR__) .'/vendor/autoload.php';

class EsSearch
{
    private static $instance;

    /**
     * @var \Elasticsearch\Client
     */
    private $esClient;

    private function __construct()
    {
        $this->initEsClient();
    }

    private function initEsClient()
    {
        if (empty($this->esClient)) {
            $this->esClient = ClientBuilder::create()->setHosts(['http://192.168.1.231:9200', 'http://192.168.1.231:9300'])->build();
        }
        if (!$this->esClient || !$this->esClient instanceof \Elasticsearch\Client) {
            throw new \Exception('初始化es客户端失败');
        }

    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function createIndex($index, $id, $body)
    {
        $params = [
            'index' => $index,
            'id'    => $id,
            'body'  => [
                'settings' => [
                    'refresh_inteval' => -1
                ]
            ]
        ];

        $response = $this->esClient->index($params);
        print_r($response);
        if (empty($response) || empty($response['_shards']['successful'])) {
            return false;
        }
        return true;
    }

    public function create($index)
    {
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 2,
                    'number_of_replicas' => 0,
                    'refresh_inteval' => -1,
                ]
            ]
        ];
    }
}