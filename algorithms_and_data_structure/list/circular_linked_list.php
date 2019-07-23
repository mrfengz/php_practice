<?php
//环形链表：最后一个节点指向首个节点
//判断是否是尾节点： $current->next == $this->_firstNode

class CircularLinkedList
{
    private $_firstNode = null;
    private $_totalNode = 0;

    public function insertAtEnd(string $data = null)
    {
        $newNode = new ListNode($data);
        //首节点为空
        if ($this->_firstNode === null) {
            $this->_firstNode = &$newNode;
        } else {
            //循环列表，如果当前节点的下一个元素为首节点，则判定为最后一个节点
            $currentNode = $this->_firstNode;
            while ($currentNode->next != $this->_firstNode) {
                $currentNode = $currentNode->next;
            }
            //末尾节点的next为新插入的节点
            $currentNode->next = $newNode;
        }
        //新节点的下一个节点为首节点，形成闭合
        $newNode->next = $this->_firstNode;
        $this->_totalNode++;
        return true;
    }

    /**
     * 展示环形链表
     */
    public function display()
    {
        echo "Total book titles: " . $this->_totalNode . "\n";
        $currentNode = $this->_firstNode;
        while($currentNode->next !== $this->_firstNode) {
            echo $currentNode->data . "\n";
            $currentNode = $currentNode->next;
        }

        //输出尾节点
        if ($currentNode) {
            echo $currentNode->data . "\n";
        }
    }
}