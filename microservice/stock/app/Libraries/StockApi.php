<?php
class StockApi
{
    public static function getStockInfo($code)
    {
        $client = \Config\Services::curlrequest();
        $result = $client->get('http://hq.sinajs.cn/list=' . $code)->getBody();
        $res = mb_convert_encoding($result, "utf-8", "gbk");
        $arr = self::formatResult($res);
        return $arr[$code];
    }

    private static function formatResult(string $val) {
        $parts = [];
        foreach (explode(';', $val) as $v) {
            if (!trim($v)) continue;    //分割后，$v可能为带空格的字符串
            $tmp = explode('=', $v);
            $code = substr($tmp[0], -8);    //股票代码信息
            $info = $tmp[1];    //接口返回数据信息
            preg_match('/"(([^"]*)+)"/', $info, $matched);
            if ($matched && $matched[1]) {
                $parts[$code] = explode(',', $matched[1]);
                // $currentPrice = $parts[3];
                // Array
                // (
                //     [0] => úҵ    //股票名称
                //     [1] => 4.700 //开盘价
                //     [2] => 4.690 //收盘价
                //     [3] => 4.700 //当前价格
                //     [4] => 4.760 //当天最高价格
                //     [5] => 4.640 //当天最低价格
                //     [6] => 4.690 //竞买价， “买一”
                //     [7] => 4.700 //竞卖价，“卖一”
                //     [8] => 7373400   //成交股票数，除以100位手数（一手=100股）
                //     [9] => 34699643.000  //成交金额，单位元
                //     [10] => 34600    //买一股数
                //     [11] => 4.690    //买一价格
                //     [12] => 94800    //买二
                //     [13] => 4.680
                //     [14] => 79000    //买三
                //     [15] => 4.670
                //     [16] => 99700    //买四
                //     [17] => 4.660
                //     [18] => 99100    //买五
                //     [19] => 4.650
                //     [20] => 52500    //卖一
                //     [21] => 4.700
                //     [22] => 102000   //卖二
                //     [23] => 4.710
                //     [24] => 19234    //卖三
                //     [25] => 4.720
                //     [26] => 107700   //卖4
                //     [27] => 4.730
                //     [28] => 145000   //卖5
                //     [29] => 4.740
                //     [30] => 2021-02-01   //日期
                //     [31] => 11:30:00
                //     [32] => 00   //unknown
                //     [33] =>      //unknown
                // )
            }

        }
        return $parts;
    }

    public static function getStockName($stockCode)
    {
        $res = self::getStockInfo($stockCode);
        // print_r($res);
        return $res[0];
    }
}