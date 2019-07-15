<?php
/**
 * 向链表中插入节点。
 * 如果是链表第一个元素，则其节点没有next元素
 * 如果插入的不是第一个元素，则遍历链表，找到最后一个节点，设置该节点的next为新增的节点
 */

//节点
class ListNode {
    public $data = null;
    public $next = null;

    public function __construct(string $data = null) {
        $this->data = $data;
    }
}

class LinkedList {
    private $_firstNode = null;
    private $_totalNodes = 0;

    /**
     * 插入节点，关键是要判断链表是否为空
     * @param string|null $data
     * @return bool
     */
    public function insert(string $data = null) {
        $newNode = new ListNode($data);

        if ($this->_firstNode === null) {   //第一个节点
            $this->_firstNode = &$newNode;
        } else {    //不是第一个节点，则循环，找到最后一个节点(该节点的next为null)
            $currentNode = $this->_firstNode;
            while($currentNode->next !== null) {
                $currentNode = $currentNode->next;
            }
            //当前节点的next指向新插入的节点
            $currentNode->next = $newNode;
        }

        $this->_totalNodes++;

        return true;
    }

    public function display() {
        echo "Total book titles: " . $this->_totalNodes . PHP_EOL;

        $currentNode = $this->_firstNode;
        while($currentNode !== null) {
            echo $currentNode->data . "\n";
            $currentNode = $currentNode->next;
        }
    }
}

$bookTitles = new LinkedList();
$bookTitles->insert("Introduction to Algorithm");
$bookTitles->insert("Introduction to PHP and Data Structures");
$bookTitles->insert("Programing Intelligence");
$bookTitles->display();