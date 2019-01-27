## beanstalk取消超时未支付订单

### beanstalk的特性
    1. 延时任务队列
    2. 任务重发
    3. 任务预留
    4. 持久化等
    
 ### 运行
    1. 下载安装beanstalkd服务器，然后运行composer install
    2. 在数据库创建一个order表
           CREATE TABLE `order` (
             `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
             `user_id` int(10) unsigned NOT NULL DEFAULT '0',
             `total_money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '单位：分',
             `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:待支付;1:已支付; 9:已取消',
             `created_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
             `extra_data` varchar(255) NOT NULL DEFAULT '' COMMENT '额外信息：此处存放job的id',
             PRIMARY KEY (`id`)
           ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
           
           然后修改config.php中的数据库配置，以及beanstalkd的配置
           
    3. 运行php -f order_monitor.php
    4. 访问order_list.php, 可以创建订单，模拟支付，超时未支付订单会自动取消
 