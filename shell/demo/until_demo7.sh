#!/bin/sh

# until 与 while作用刚好相反，当条件不成立时，会执行，条件成立时，结束。

# 循环输出直到flag>10

#赋值语句等号两侧不能留空格，否则出错
flag=1
# 注意这个条件写的时候 一定要在表达式两侧加空格，否则语法报错
until (( $flag > 10 ))
do
	echo $flag
	flag=$[$flag+2]
done

