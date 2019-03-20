<?php
include_once '../../error_set.php';
//include_once 'MaleProto.php';
//include_once 'FemaleProto.php';

function __autoload($class) {
    include_once $class . '.php';
}

class Client
{
    //初始化的两个实例
    private $fly1;
    private $fly2;

    //克隆出来的实例
    private $c1Fly;
    private $c2Fly;
    private $updatedCloneFly;

    public function __construct()
    {
        //实例化
        $this->fly1 = new MaleProto();
        $this->fly2 = new FemaleProto();

        //克隆
        $this->c1Fly = clone $this->fly1;

        $this->c2Fly = clone $this->fly2;
        $this->updatedCloneFly = clone $this->fly2;

        //更新克隆对象
        $this->c1Fly->mated = true;
        $this->c2Fly->fecundity = 186;
        $this->updatedCloneFly->eyeColor = 'purple';
        $this->updatedCloneFly->wingBeat = 220;
        $this->updatedCloneFly->unitEyes = 750;
        $this->updatedCloneFly->fecundity = 82;

        //输出提示信息
        $this->showFly($this->c1Fly);
        $this->showFly($this->c2Fly);
        $this->showFly($this->updatedCloneFly);
    }

    public function showFly(IPrototype $fly)
    {
        echo "Eye color: " . $fly->eyeColor . PHP_EOL;
        echo "wing beat/sec: " . $fly->wingBeat . PHP_EOL;
        echo "Eye units: " . $fly->unitEyes . PHP_EOL;
        $gender = $fly::GENDER;
        echo "Gender: $gender\n";

        if ($gender == 'FEMALE') {
            echo "Number of eggs: " . $fly->fecundity . PHP_EOL;
        } else {
            echo "Mated: " . $fly->mated . PHP_EOL;
        }
        echo "\n";
    }
}

new Client();