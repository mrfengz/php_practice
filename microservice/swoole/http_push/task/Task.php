<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/3/19
 * Time: 17:31
 */
namespace task;

use Application\Autoload\Loader;

class Task
{
    private $data = [];
    private $loadedClass = [];

    public function __construct($data)
    {
        $this->data = $data;
        Loader::init(__DIR__ . '/..');
    }

    private function getLoadedClass($class)
    {
        if (!isset($this->loadedClass[$class])) {
            $this->loadedClass[$class] = new $class;
        }
        return $this->loadedClass[$class];
    }

    public function run()
    {
        $class = $this->getLoadedClass($this->data['taskClass']);
        $taskAction = $this->data['taskAction'];
        return $class->$taskAction($this->data);
    }
}