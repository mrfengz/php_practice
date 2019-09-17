<?php
require_once 'StackInterface.php';
class Books implements StackInterface
{
    private $limit;

    private $stack;

    public function __construct(int $limit = 20)
    {
        $this->limit = $limit;
        $this->stack = [];
    }

    public function pop(): string
    {
        if($this->isEmpty()) {
            throw new UnderflowException('Stack 为空');
        }

        return array_pop($this->stack);
    }

    public function push(string $item)
    {
        if (count($this->stack) > $this->limit) {
            throw new OverflowException('Stack 已满');
        }
        array_push($this->stack, $item);
    }

    public function top():string
    {
        return end($this->stack);
    }

    public function isEmpty():bool
    {
        return empty($this->stack);
    }
}


try {
    $programingBooks = new Books(10);
    $programingBooks->push("Introduction to PHP7");
    $programingBooks->push("Mastering Javascript");
    $programingBooks->push("Mysqlbench tutorial");
    echo $programingBooks->pop() . PHP_EOL;
    echo $programingBooks->top() . PHP_EOL;
} catch(\Exception $e) {
    echo $e->getMessage();
}