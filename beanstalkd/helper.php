<?php
if (!function_exists('p')) {
    function p($arg)
    {
        echo '<pre>';
        if(empty($arg)) {
            var_dump($arg);
        } else {
            print_r($arg);
        }
    }
}

if (!function_exists('pd')) {
    function pd($arg)
    {
        echo '<pre>';
        if(empty($arg)) {
            var_dump($arg);
        } else {
            print_r($arg);
        }
        die;
    }
}

if (!function_exists('getBeanstalkd')) {
    function getBeanstalkd()
    {
        require_once __DIR__.'/vendor/autoload.php';
        require(__DIR__.'/config.php');
        $conf = $config['beanstalkd'];
        return new Pheanstalk\Pheanstalk($conf['host'], $conf['port']);
    }
}

if(!function_exists('getDbConnection')) {
    function getDbConnection()
    {
        require('config.php');
        $conf = $config['db'];
        try {
            $dsn = "mysql:host={$conf['host']};port={$conf['port']};dbname={$conf['dbname']}";
            $pdo = new PDO($dsn, $conf['username'], $conf['password']);

            return $pdo;
        } catch(\Exception $e) {
            exit('数据库连接失败');
        }
    }
}

if (!function_exists('order_create')) {
    function order_create()
    {
        $conn = getDbConnection();
        $data = [
            'user_id' => mt_rand(1, 9999),
            'total_money' => mt_rand(100, 990000000),
            'created_at' => time(),
        ];

        $stat1 = $conn->prepare('insert into `order`(`user_id`, `total_money`, `created_at`) values(?,?,?)');
        if (false === $stat1->execute(array_values($data))) {
            exit('创建订单失败');
        } else {
            $orderId = $conn->lastInsertId();
            return $orderId;
        }
    }
}


if(!function_exists('update_job_id')) {
    function update_job_id($orderId, $jobId) {
        if(empty($orderId) || empty($jobId) || $orderId <=0 || $jobId <= 0) {
            exit('参数非法');
        }

        $conn = getDbConnection();
        $stat = $conn->prepare('update `order` set `extra_data` = :extra_data where `id` = :id;');
        $stat->bindParam(':id', $orderId);
        $extra_data = json_encode(['job_id' => $jobId]);
        $stat->bindParam(':extra_data', $extra_data);

        return $stat->execute();
    }

}

spl_autoload_register(function($class) {
   $classFile = $class . '.php';
   if (!file_exists($classFile)) {
       exit($classFile . '文件不存在');
   }
   require_once $classFile;
});

