<?php
class CrawlerV1
{
    private $url = '';
    private $toVisit = [];
    private $loaded = false;


    public function __construct($url)
    {
        $this->url = $url;
    }

    public function visitOneDegree()
    {
        $this->loadPageUrls($this->url);
        $this->visitAll();
    }

    private function visitAll()
    {
        foreach ($this->toVisit as $url) {
            $this->visit($url);
        }
    }

    private function loadPageUrls($url)
    {
        $content = $this->visit($url);
        // $pattern = '#((http|ftp)[s]?://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';
        $pattern = '/http[s]?:\/\/[^\'"]*+/i';
        preg_match_all($pattern, $content, $matched);
        foreach ($matched[0] as $url) {
            if (in_array($url, $this->toVisit)) continue;
            $this->toVisit[] = $url;
        }
    }

    private function visit($url)
    {
        // return @file_get_contents($url);
        Co\run(function()use($url){
            $urlInfo = parse_url($url);
            print_r($urlInfo);
            // $ip = \Swoole\Coroutine\System::dnsLookup($urlInfo['host'], 3);
            $cli = new Swoole\Coroutine\Http\Client($urlInfo['host'], 443);
            // $cli = new Swoole\Coroutine\Http\Client($ip, 443);
            $cli->setHeaders([
                'Host' => $urlInfo['host'],
                'User-Agent' => 'Chrome/49.0.2587.3',
                'Accept' => 'text/html,application/xhtml+xml,application/xml',
                'Accept-Encoding' => 'gzip',
            ]);

            $cli->get($urlInfo['path'] == '/' ? '/index.php' : $urlInfo['path']);
            $content = $cli->body;
            $cli->close();
            echo $content
            ;
            return $content;
        });
    }
}


$t1 = microtime(true);
// $crawler = new Crawler('https://laravelacademy.org/books/swoole-tutorial');
$url = 'https://www.baidu.com/';
$crawler = new CrawlerV1($url);
$crawler->visitOneDegree();
$t2 = microtime(true);

echo ($t2 - $t1) . "\n";