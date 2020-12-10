## 使用mysql写一个队列，可以多生产者，多消费者，同时避免重复消费
    1. 示例为简单的demo, php producer.php 生成多条消息。
    2. php consume.php 为消费者，消耗队列任务。
    3. manager.php为消费者管理任务，使用了后台异步执行的方式，开启多个后台任务进行消费。

## 原理
    使用了getmypid()获取进程id，队列有个pid字段，更新未处理的消息的pid为当前进程，更新状态为“处理中”，其他进程则不会锁定对应任务

## 不足
    1. 在windows下模拟的，运行php manager.php时，发现消费进程数量比设置的数量少1个，暂未找到原因。
    2. 未处理任务失败情况下的释放（思路： 通过ps aux|grep 'xxx'）可以获取正在运行的进程，然后进程id不在运行的进程id中的其他“处理中”的任务进行释放。

```sql
    CREATE TABLE `trade_message` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `trade_id` bigint(20) unsigned NOT NULL,
        `create_time` int(10) unsigned NOT NULL DEFAULT '0',
        `update_time` int(10) unsigned NOT NULL DEFAULT '0',
        `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: 待处理; 2:处理中; 3:处理成功; 4:处理失败',
        `pid` mediumint(9) NOT NULL COMMENT '消费者id',
        `message` varchar(255) NOT NULL DEFAULT '',
        `data` varchar(255) NOT NULL DEFAULT '',
        `run_count` smallint(5) unsigned NOT NULL COMMENT '运行次数',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
```
