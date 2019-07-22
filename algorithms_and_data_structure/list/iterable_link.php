<?php
//使用内置的遍历接口，实现链表的遍历

/*
 * current():   获取当前元素
 * next():      获取下一个元素
 * key():       获取当前元素的key
 * rewind():    回到开头的第一个元素
 * valid():     检查当前位置是否有效
 */

//节点
class ListNode
{
    public $data = null;
    public $next = null;

    public function __construct(string $data = null)
    {
        $this->data = $data;
    }
}


class LinkedList
{
    private $_firstNode = null;
    private $_totalNodes = 0;

    //当前节点
    private $_currentNode = null;
    //当前节点的位置
    private $_currentPosition = 0;

    //实现遍历循环
    public function current()
    {
        return $this->_currentNode->data;
    }

    public function next()
    {
        $this->_currentPosition++;
        $this->_currentNode = $this->_currentNode->next;
    }

    public function key()
    {
        return $this->_currentPosition;
    }

    public function rewind()
    {
        $this->_currentPosition;
        $this->_currentNode = $this->_firstNode;
    }

    public function valid()
    {
        return $this->current !== null;
    }

    /**
     *  插入节点，关键是要判断链表是否为空
     * 如果是链表第一个元素，则其节点没有next元素
     * 如果插入的不是第一个元素，则遍历链表，找到最后一个节点，设置该节点的next为新增的节点
     * @param string|null $data
     * @return bool
     */
    public function insert(string $data = null)
    {
        $newNode = new ListNode($data);

        if ($this->_firstNode === null) {   //第一个节点
            $this->_firstNode = &$newNode;
        } else {    //不是第一个节点，则循环，找到最后一个节点(该节点的next为null)
            $currentNode = $this->_firstNode;
            while ($currentNode->next !== null) {
                $currentNode = $currentNode->next;
            }
            //当前节点的next指向新插入的节点
            $currentNode->next = $newNode;
        }

        $this->_totalNodes++;

        return true;
    }

    /**
     * 在头部插入一个节点
     *  如果插入的是首节点，next为空。否则，将firstNode作为当前节点的next
     * @param string|null $data
     * @return bool
     */
    public function insertAtFirst(string $data = null)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode === null) {
            $this->_firstNode = &$newNode;
        } else {
            $currentFirstNode = $this->_firstNode;
            $this->_firstNode = &$newNode;
            $newNode->next = $currentFirstNode;
        }
        $this->_totalNodes++;

        return true;
    }

    public function search(string $data = null)
    {
        if ($this->_totalNodes) {
            $currentNode = $this->_firstNode;
            while ($currentNode->next !== null) {
                if ($this->data === $data) {
                    return $currentNode;
                }
                $currentNode = $currentNode->next;
            }
        }
        return false;
    }

    /**
     * 插入一个节点到指定节点的前边
     *  需要参数新节点、查找的节点
     *  操作需要修改查找节点前的next，指向插入的节点。
     *  将插入节点的next指向查找到的节点
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
                echo $currentNode->data . PHP_EOL;
                if ($currentNode->data === $query) {
                    $newNode->next = $currentNode;
                    $previous->next = $newNode;
                    $this->_totalNodes++;
                    break;
                }
                $previous = $currentNode;
                $currentNode = $currentNode->next;
            }
        }
    }

    /**
     * 在某个节点之后插入新节点
     *     当前节点不为空
     *     当前节点next为newNode,原当前节点的next改为newNode的next
     * @param  string|null $data 节点data
     * @param  string|null $query 要查找的节点数据
     */
    public function insertAfter(string $data = null, string $query = null)
    {
        $newNode = new ListNode($data);

        if ($this->_firstNode) {
            $nextNode = null;
            $currentNode = $this->_firstNode;
            // 当前节点不为空
            while ($currentNode !== null) {
                if ($currentNode->data === $query) {
                    // 下一个节点不为空，则newnode->next设为nextNode
                    if ($nextNode !== null) {
                        $newNode->next = $nextNode;
                    }
                    // 当前节点的next只想newNode
                    $currentNode->next = $newNode;
                    $this->_totalNodes++;
                    break;
                }
                $currentNode = $currentNode->next;
                $nextNode = $currentNode->next;
            }
        }
    }

    /**
     * 删除首节点
     * @return [type] [description]
     */
    public function deleteFirst()
    {
        // 首节点不为空
        if ($this->_firstNode !== null) {
            // 首节点是否有next节点
            if ($this->_firstNode->next !== null) {
                $this->_firstNode = $this->_firstNode->next;
            } else {
                $this->_firstNode = null;
            }
            $this->_totalNodes--;
            return true;
        }
        return false;
    }

    /**
     * 删除最后一个节点
     * @return [type] [description]
     */
    public function deleteLast()
    {
        if ($this->_firstNode) {
            $currentNode = $this->_firstNode;
            if ($currentNode->next === null) {  //删除首节点
                $this->_firstNode = null;
            } else {
                $prevNode = null;
                while ($currentNode->next !== null) {
                    $prevNode = $currentNode;
                    $currentNode = $currentNode->next;
                }
                $prevNode->next = null;
            }
            $this->_totalNodes--;
            return true;
        }
        return false;
    }

    /**
     * 查找并删除指定节点
     *  判断首节点是否为空，如果为空，直接返回
     *  首节点不为空，开始循环判断
     *
     * @param string|null $data
     */
    public function delete(string $data = null)
    {
        if ($this->_firstNode) {
            $currentNode = $this->_firstNode;
            $prevNode = null;
            while($currentNode !== null) {
                if ($currentNode->data === $data) {
                    if ($currentNode->next!== null) {
                        $prevNode->next = $currentNode->next;
                    } else {
                        $prevNode->next = null;
                    }
                    $this->_totalNodes--;
                    break;
                }
                $prevNode = $currentNode;
                $currentNode = $currentNode->next;
            }
        }

        return true;
    }

    /**
     * [1, 2, 3]
     * current = 1 , next=2
     * current->next = null;
     * reversedLink = 1
     *
     * current = 2, next=3
     * current->next = 1
     * reversedLink =  2 - 1
     *
     * current = 3, next = null
     * current->next = 2 - 1
     * reversedLink = 3 - 2 - 1
     *
     * 伪代码
     *  prev = null
     * current = first_node;
     * next = null;
     * while(current!=null){
    next = current->next;
    current->next =prev;
    prev = current;
    current = next;
     * }
     */
    public function reverse()
    {
        if ($this->_firstNode !== null) {
            //下一个节点不为空
            if($this->_firstNode->next !== null) {
                $reversedList = null;
                $next = null;
                $currentNode = $this->_firstNode;
                //当前节点不为空
                while($currentNode != null) {
                    //取下一个节点
                    $next = $currentNode->next;
                    //当前节点的下一个节点置为反转后的link
                    $currentNode->next = $reversedList;
                    //将当前节点放入到l反转后的ink中
                    $reversedList = $currentNode;
                    //进入下一个节点
                    $currentNode = $next;
                }

                $this->_firstNode = $reversedList;
            }
        }
    }

    /**
     * 获取第n个节点
     * 	循环节点，判断当前节点计数值是否与指定的值相同
     * @param int $n
     * @return null
     */
    public function getNthNode(int $n = 0)
    {
        $count = 1;
        if ($this->_firstNode !== null) {
            $currentNode = $this->_firstNode;
            while($currentNode !== null) {
                if ($count === $n) {
                    return $currentNode;
                }
                $count++;
                $currentNode = $currentNode->next;
            }
        }
    }

    public function display()
    {
        echo "Total book titles: " . $this->_totalNodes . PHP_EOL;

        $currentNode = $this->_firstNode;
        while ($currentNode !== null) {
            echo $currentNode->data . "\n";
            $currentNode = $currentNode->next;
        }
    }
}

$bookTitles = new LinkedList();
$bookTitles->insert("Introduction to Algorithm");
$bookTitles->insert("Introduction to PHP and Data Structures");
$bookTitles->insert("Programing Intelligence");
$bookTitles->insertAtFirst("Mediawiki Administative tutorial guide");
$bookTitles->insertBefore("Introduction to Calculus", "Programing Intelligence");
$bookTitles->insertBefore("Introduction to Calculus", "Programing Intelligence");

foreach($bookTitles as $title) {
    echo $title . "\n";
}




