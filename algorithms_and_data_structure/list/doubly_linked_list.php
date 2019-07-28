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
     * @return bool
     */
    public function insertAtFirst(string $data = null)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode !== null) {
            $currentFirstNode = $this->_firstNode;
            $this->_firstNode = &$newNode;
            $newNode->next = $currentFirstNode;  //当前节点的next
            $currentFirstNode->prev = $newNode;  //后边节点的prev指向新节点

        } else {
            $this->_firstNode = &$newNode;
            $this->_lastNode = $newNode;
        }
        $this->_totalNode++;
        return true;
    }

    /**
     * 在最后插入
     * 判断链表是否为空，为空的话 ，首位节点都是当前插入的节点
     * 不为空的话，则
     *  1. 创建一个新节点
     *  2. 使新节点成为最后一个节点
     *  3. 使之前的最后一个节点成为新节点的prev
     *  4. 使旧的最后一个节点的next成为当前最后节点的next
     *
     * @param string|null $data
     * @return bool
     */
    public function insertAtLast(string $data = null)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode === null) {
            $this->_firstNode = &$newNode;
            $this->_lastNode = $newNode;
        } else {
            //当前节点：旧最后的节点
            $currentNode = $this->_lastNode;
            //旧最后节点->next: 新节点
            $currentNode->next = &$newNode;
            //新(最后)节点->perv: 旧最后节点的prev
            $newNode->prev = $currentNode;
            //todo 是不是少了一步，让之前的最后一个节点的next成为新节点的next
            //让新节点成为最后节点
            $this->_lastNode = $newNode;
        }
        $this->_totalNode++;
        return true;
    }

    /**
     * 在某节点前插入新节点
     *  A B节点，B节点前插入
     *  A->next = A1
     *  A1->prev = A
     *  A1->next = B
     *  B->prev = A1
     *
     * @param string|null $data
     * @param string|null $query
     */
    public function insertBefore(string $data = null, string $query = null)
    {
        $newNode = new ListNode($data);

        if ($this->_firstNode) {
            $previous = null;
            $currentNode = $this->_firstNode;
            while ($currentNode !== null) {
                if ($currentNode->data === $query) {
                    $newNode->next = $currentNode;
                    if ($previous !== null) {
                        $previous->next = $newNode;
                        $newNode->prev = $previous;
                    } else {
                        $newNode->prev = null;
                    }
                    if ($currentNode === $this->_firstNode) {
                        $this->_firstNode = $newNode;
                    }

                    $currentNode->prev = $newNode;
                    $this->_totalNode++;
                    break;
                }
                $previous = $currentNode;
                $currentNode = $currentNode->next;
            }
        }
    }


    /**
     * 在某个节点后插入新节点
     *
     * A B节点，在A后边插入。要判断B是不是最后一个节点
     * A->next = A1
     * A1->prev = A
     * A1->next = B
     * B->next = A1
     * @param string|null $data
     * @param string|null $query
     */
    public function insertAfter(string $data = null, string $query = null)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode) {
            $nextNode = null;
            $currentNode = $this->_firstNode;
            while ($currentNode !== null) {
                if ($currentNode->data === $query) {
                    if ($nextNode !== null) {
                        $newNode->next = $nextNode;
                        $nextNode->prev = $newNode;
                    }
                    //如果实在最后一个节点后插入，则把新节点变为最后一个节点
                    if ($currentNode === $this->_lastNode) {
                        $this->_lastNode = $newNode;
                    }

                    $currentNode->next = $newNode;
                    $newNode->prev = $currentNode;
                    $this->_totalNode++;
                    break;
                }
                $currentNode = $currentNode->next;
                $nextNode = $currentNode->next;
            }
        }
    }

    /**
     * 删除首节点
     *  判断是首节点的next节点是否存在
     * @return bool
     */
    public function deleteFirst()
    {
        if ($this->_firstNode) {
            $current = $this->_firstNode;
            if ($current->next !== null) {
                $this->_firstNode = $current->next;
                $this->_firstNode->prev = null;
            } else {
                $this->_firstNode = null;
            }
            $this->_totalNode--;
            return true;
        }
        return false;
    }

    /**
     * 删除尾节点
     * @return bool
     */
    public function deleteLast()
    {
        if ($this->_lastNode !== null) {
            if ($this->_lastNode->prev !== null) {
                $this->_lastNode = $this->_lastNode->prev;
                $this->_lastNode->next = null;
            } else {
                $this->_firstNode = null;
                $this->_lastNode = null;
           }
           $this->_totalNode--;
            return true;
        }
        return false;
    }

    /**
     * 查找并删除某节点
     *  查找到节点，并获取前一个和后一个节点，将前一个节点的next设置为后一个节点，后一个节点的prev设置为前一个节点
     * @param string|null $query
     */
    public function delete(string $query = null)
    {
        if ($this->_firstNode !== null) {
            $previous = null;
            $currentNode = $this->_firstNode;
            while($currentNode !== null) {
                if ($currentNode->data === $query) {
                    if ($currentNode->next !== null) {
                        $previous->next = $currentNode->next;
                        $currentNode->next->prev = $previous;
                    } else {
                        $currentNode->next = null;
                    }
                    $this->_totalNode--;
                    break;
                }
                $previous = $currentNode;
                $currentNode = $currentNode->next;
            }
        }
    }

    /**
     * 从首节点开始展示链表
     */
    public function displayForward()
    {
        echo "Total book titles: " . $this->_totalNode . "\n";
        $currentNode = $this->_firstNode;
        // print_r($currentNode);
        while($currentNode !== null) {
            echo $currentNode->data . "\n";
            $currentNode = $currentNode->next;
        }
    }

    /**
     * 从链表末尾开始展示链表
     */
    public function displayBackward()
    {
        echo "Total book titles: " . $this->_totalNode . "\n";
        $currentNode = $this->_lastNode;
        while($currentNode !== null) {
            // print_r($currentNode);
            // echo PHP_EOL;
            echo $currentNode->data . "\n";
            $currentNode = $currentNode->prev;
        }
    }
}

$linkedList = new DoublyLinkedList();
$linkedList->insertAtFirst("first Node");
$linkedList->insertAtLast("Last Node");
$linkedList->insertAfter("After Node", "Last Node");
$linkedList->insertBefore("before Node", "first Node");
$linkedList->displayForward();
$linkedList->displayBackward();