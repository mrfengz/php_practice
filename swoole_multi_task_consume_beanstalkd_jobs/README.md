## swoole+beanstalkd模拟生产者-消费者功能 
    利用swoole创建多个task进程，去不断消费生产者放入beanstalk中的jobs
  
## 环境
    本实验是在swoole-v4.1.13+beanstalkd-v1.10下进行的。
    需要composer安装pheanstalk扩展
    
## 流程
    1. 创建定时任务 */1 * * * * /srv/www/multi_consumer/test.sh
    2. 启动swoole_server `php multi_task_consumer.php`
    3. 向队列中添加job `php produce_tasks.php`
    4. 观察multi_task_log.txt
    5. 可以多次运行 `php produce_tasks.php`查看multi_task_log.txt内容
    6. 可以通过php beanstalkd_cmd.php tubeStats tubeName查看tube中的job信息
## 遇到的问题
    1. 一开始没有投递任务时没有使用try{}catch(){}结构，运行一段时间后mutli_task_consumer.php报错，错误为socket timeout。
    后来经过网上查询，跟一个配置有关 。
        经过查询，有3种解决办法
            1）修改配置 ini_set('default_socket_timeout', 86400 * 30);
            2) 通过使用try()catch(){} ，在socket断开后重连
            3）通过使用linux定时任务向队列中添加一个ping任务包，然后在消费时过滤掉该任务包。
       
        第一种不建议使用，可能会在某个时刻到期而停止消费，导致队列中任务挤压得不到处理。
   
   2. swoole的task结束后，无法出发onFinish事件通知worker进程， 这点很奇怪。如果通过for()投递任务，而不是从beanstalkd中取任务，则能正常触发该事件。
   如有人遇到并解决了此问题，请告知。    
        
    
    