<?php

/**
 * Class Crawler
 * 1.设置要抓取页面的url
 * 2.获取页面内容
 * 3.解析出所有的url
 * 4.循环抓取所有页面
 */
class Crawler
{
    private $url;
    private $allUrls = [];

    public function __construct($url)
    {
        $this->url = $url;
        $this->crawl($url);
        $this->crawlAll($url);
    }

    private function parse($url)
    {
        $content = @file_get_contents($url);
        // print_r($content);
        $all = [];
        preg_match_all('/http[s]?:\/\/[^\'"]*+/', $content, $all);
        return $all[0];
    }

    private function crawlAll($url)
    {
        $urls = $this->parse($url); //解析当前页面中包含的url
        foreach ($urls as $url) {
            if (!in_array($url, $this->allUrls)) {
                $this->allUrls[] = $url;
                $this->crawl($url); //下载当前页面内容
                // $this->crawlAll($url); //递归下载页面中其他的内容
            }
        }
    }

    private function crawl($url)
    {
        return @file_get_contents($url);
    }
}

$t1 = microtime(true);
// $crawler = new Crawler('https://laravelacademy.org/books/swoole-tutorial');
$url = 'https://www.swoole.com/';
$crawler = new Crawler($url);
$t2 = microtime(true);

echo ($t2 - $t1) . "\n";