<?php
/*
 * 双向链表
 * 每个节点都有prev和next两个节点，所以删除或者插入等操作时，需要同时修改prev和next
 * 这里也会记录lastNode,以便实现O(1)复杂度
 *
 * 包含以下操作
 * 1. 在第一个元素前插入节点
 * 2. 在最后一个元素前插入节点
 * 3. 在指定节点前插入节点
 * 4. 在指定节点后插入节点
 * 5. 删除首节点
 * 6. 删除最后一个节点
 * 7. 查找并删除某个节点
 * 8. 展示某节点前的所有节点
 * 9. 展示某节点后的节点
 */

class ListNode
{
    public $data = null;
    public $prev = null;
    public $next = null;

    public function __construct(string $data = null)
    {
        $this->data = $data;
    }
}

class DoublyLinkedList
{
    private $_firstNode = null;
    private $_lastNode = null;
    private $_totalNode = 0;

    /**
     * 开头插入节点
     * @param string|null $data
     */
    public function insertAtFirst(string $data = null)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode !== null) {
            // $newNode->prev = null;
            // $newNode->next = $this->_firstNode;
            // $this->firstNode = $newNode;
            $currentNode = $this->_firstNode;
            $this->_firstNode = &$newNode;
            $newNode->next = $currentNode;  //当前节点的next
            $currentNode->prev = $newNode;  //后边节点的prev指向新节点

        } else {
            $this->_firstNode = &$newNode;
            $this->_lastNode = $newNode;
        }
        $this->_totalNode++;
        return true;
    }
}