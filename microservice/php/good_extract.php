<?php

// Redis 应用
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('august');

// 1. 统计全站访问次数
// lavarel 中间件
class SiteVisits
{
    public function handler(Request $request, Closure $next)
    {
        Redis::incr('site_total_visits');
        return $next($request);
    }

    public function register()
    {
        $events = $this->getEvents();
        // 事件可以有多个不同的监听者
        foreach ($events as $event => $listeners) {
            foreach (array_unqiue($listeners) as $listener) {
                Event::listen($listener);
            }
        }

        // 事件关注者进行关注处理
        foreach ($this->subscribe as $subscriber) {
            Event::subscribe($subscriber);
        }
    }
}

// 2.使用有序集合，统计热门文章
// zadd key score val1 score2 val2
// zrevrange key 0 9 获取浏览次数前十的文章
$popularArticlesKey = 'popular_articles';
$redis->zIncrBy($popularArticlesKey, 1, 1); //给member增加value
$redis->zIncrBy($popularArticlesKey, 2, 5);
$redis->zIncrBy($popularArticlesKey, 3, 15);
$redis->zIncrBy($popularArticlesKey, 4, 8);
var_dump($redis->zRevRange($popularArticlesKey, 0, 3));

// 3 缓存

// 4 消息队列
// 消息+队列+worker处理进程
// 生产者--消息-->队列<---worker进程
// 适用于异步耗时操作，邮件发送、文件上传
// 业务临时的高并发操作，比如秒杀、消息推送

// 4.1 消息队列优先级 + 负载均衡（多开几个进程）
// 启动worker设置不同的队列的优先级，优先处理优先级较高的队列中的任务
// 为了防止高优先级队列一直繁忙，导致低优先级无法处理，可以再开几个进程，优先处理低优先级任务
// foreach(explode(',', $queue) as $queueName) {
//     if ($data = $redis->rpop($queueName) && static::$callback[$queueName]) {
//         return static::$callback[$queueName]($data);
//     }
// }


// 4.2 延迟队列

// 4.3 任务失败重试
// try_times
// $response = Http::post(xxx)->timeout(4);
//for ($i = 0; $i < $try_times; $i++) {
// if ($response->failed()) {
//      重试
// } else {
//         break;
// return ;
    // }
//}
// 任务重试后仍然失败，发邮件或其他处理
// Mail::to();