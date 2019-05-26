<?php
include_once "ISubject.php";
include_once "RealSubject.php";
include_once "UniversalConnect.php";

/**
 * 代理进行登录检测，转发请求给真实主题
 * Class Proxy
 */
class Proxy implements ISubject
{
    private $tableMaster;
    private $hookup;
    private $logGood;
    private $realSubject;

    public function login($uNow, $pNow)
    {
        //验证用户名和密码
        $uname = $uNow;
        $pw = md5($pNow);

        $this->logGood = false;
        $this->tableMaster = 'proxyLog';
        $this->hookup = UniversalConnect::doConnect();

        $sql = "select pw from $this->tableMaster where uname='$uname'";
        if ($result = $this->hookup->query($sql)) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if ($row['pw'] == $pw) {
                $this->logGood = true;
            }
            $result->close();
        } elseif (($result = $this->hookup->query($sql)) === flase) {
            printf("Failed: %s<br>", $this->hookup->error);
            exit();
        }

        $this->hookup->close();

        if ($this->logGood) {
            $this->request();
        } else {
            echo "Username and/or Password not on record.";
        }
    }

    //代理转发请求
    public function request()
    {
        $this->realSubject = new RealSubject();
        $this->realSubject->request();
    }
}