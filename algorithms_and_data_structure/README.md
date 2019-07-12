## 概念

-	ADT: Abstract Data Type
	
	常见的ADT
		List
		Map
		Set
		Stack
		Queue
		Priority Queue
		Graph
		Tree

- 数据结构

	分类
	1. 线性数据结构	数组、列表、队、栈
	2. 非线性数据结构	图、树


	常见数据结构
	Struct
	Array
	Linked List
	Doubly linked list
	Stack
	Queue
	Priority queue
	Set
	Map
	Tree
	Graph
	Heap

    Struct: 学生信息
    Array: 矩阵，数字索引
    Linked list: node, pointer->next element. 有序集
    Doubly linked list: 指向上个节点的指针，和下个节点的指针
    Stack: LIFO(后进先出)。 push:向top压入一个元素，pop:从top弹出一个元素
    Queue: FIFO(先进先出)。 enqueue(加入队尾),dequeue(从队首出列)
    Set: 无序，不重复
    Map: 键值对。键不重复
    Tree: 非线性，层级结构。 
        root， parent node, child node, left node	根节点、父节点、子节点、叶子节点
        sub-tree 子树
        siblings 兄弟节点
        level 层级 root为0
    Graph： 非线性。有限数量的顶点(vertice,vertex)或者节点,边(edges)或者arcs(弧)
        V = {A, B, C, D, E} 	#定点
        E = {AB, BC, CE, EF, DB} #边
    
        有向边 edge A->B 与 B->A不同
    Heap: 特殊的树结构，满足一定的特性。
        最大堆：root值最大，小的为叶子节点
        最小堆：最小的为根节点，其余的为叶子节点
        用于高效计算和排序graph算法问题

## 算法的特征
	特征
	1.Input
	2.Output
	3.Precision： 每一步都被准确定义
	4.Finiteness: 有限步骤
	5.Unambiguous: 每一步都足够清晰，没有模糊的地方
	6.Independent: 独立，不依赖与编程语言	


## 算法复杂度表示方法
    f(n) 	Name
    (1) 	Constant
    logn	Logarithmic
    (n) 	Linear
    nlogn	Log Linear
    n^2 	Quadratic
    n^3 	Cubic
    2^n 	Exponential