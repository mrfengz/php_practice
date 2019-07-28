<?php
/**
 * 使用PHP标准库的 SplDoublyLinkedList实现双向链表
 *  Add()       在指定index处添加一个节点
 *  Bottom()    从链表beginning处获取一个节点
 *  Count()     返回链表元素个数
 *  Current()   返回当前节点
 *  getIteratorMode()   返回链表的遍历模式
 *  SetIteratorMode()   设置链表的遍历模式 FIFO LIFO 等
 *  Key()       返回当前节点的index
 *  Next()      返回下一个节点
 *  Pop()       从链表的end弹出一个节点
 *  Prev()      移动到前一个节点
 *  Push()      从end处添加一个节点
 *  Rewind()    回到链表的top处
 *  Shift()     从链表的beginning处移出一个节点
 *  Top()       获取链表end的一个节点
 *  Unshift()   从链表的beginning处添加一个节点
 *  Valid()     检查链表中是否有更多的节点
 *
 */

$bookTitles = new SplDoublyLinkedList();
$bookTitles->push("Introduction to Algorithm");
$bookTitles->push("Introduction to PHP and Data Structure");
$bookTitles->push("Programming Intelligence");
$bookTitles->push("Mediawiki Administrative tutorial guide");
$bookTitles->add(1, "Introduction to Calculus");
$bookTitles->add(3, "Introduction to Graph Theory");

for ($bookTitles->rewind(); $bookTitles->valid(); $bookTitles->next()) {
    echo $bookTitles->current() . "\n";
}