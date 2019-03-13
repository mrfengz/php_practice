##shell笔记

## 变量
	
	分为系统变量和自定义变量
	
	echo $PATH	打印系统变量

自定义变量

	num1=10		#注意等号左右不能加空格.定义时不能使用$
	echo $num1	#使用变量需要加上$

变量赋值

	path=$(pwd)
	files=`ls -la`

	echo $path
	echo $files

## 运算
	
整数运算
	
	1. expr
		num1=$(expr 10 \* 3)
		num2=`expr 10 % 3`

	2. $[]
		num1=1
		num2=10
		num3=$[$num1 + $num2]

浮点运算
	
	variable=$(echo "options; expression"|bc)
	
	浮点运算一般使用bash的bc计算器。具体使用参考 man bc

	num=$(echo "scale=2; 10 / 3"|bc)

## 条件选择
if条件判断

 if else

	if command
	then
		commands
	fi
	

> 简化写法

	if command;then
		commands
	fi

 if-then-else语法

	if command;then
		commands1
	else
		commands2
	fi

条件判断

	1. test expression	#使用man test查看用法
	2. (( expression )) #双括号，注意表达式与双括号之间的**空格**
	3. [ expression ] #方括号。需要注意**空格**。 提供字符串比较的高级特性。没有双括号某些操作符需要转义的问题。

case

-	当if.elif.. elif ...比较多时，可以使用case

-	语法
	
		case variable in
			condition1|condition2)
				commands1;;
			condition3)
				commands2;;
			*)
				echo 'default';;
	
		esac


#### 循环
**for循环**

> 形式1语法

	for var in list
	do
		echo $var
	done

> 形式2语法，C风格

	for(( i=0; i<10; i++))
	do
		echo $i
	done

**while循环**
> 语法

	while test command
	do	
		command2
	done

**until循环**
> 语法

	until ( expression )
	do
		command2
	done
	
	注意：until与while使用相反，如果expression表达式结果为真，即命令退出状态为0，则跳出循环。否则继续执行。

break 和 continue 与php中用法一致，不再赘述

## 命令行参数

根据位置获取参数
	
	$0 		脚本名称
	$1-9		按顺序获取第1-9个参数 

	$# 		所有参数
		for((i=0;i<=$#;i++))
		do
			echo ${!i}	#{}中不能使用$符号，bash使用!代替了$符号
		done
	$*		将所有输入的参数作为一个整体，空格隔开的参数也做为一个不可分割的整体。for循环时空格不被识别为分隔符
	$@		将所有输入参数作为一个整体，但是空格隔开的参数可以分割，用在for循环时，空格被识别为分隔符

单个输入
	
	read
	echo $REPLY	#输入参数被放入REPLY环境变量中

多个输入

	read -p "what's your name?" first last
	echo $first
	echo $last

read等待输入超时

	read -t 5(单位s) -p
	
		if read -t 5 -p "please input your name?" name
		then
			echo "your name is : $name"
		else
			echo "timeout!"
		fi