#!/bin/sh

## for 循环

## 语法
# 1. for-in
# for var in list
#	do
#		commands
# 	done

# 2. c语言的for风格
# for(( i = 0; i <= 10; i++)) 
# do
#	commands
# done

##### demo1 for in 循环默认是通过空格、tab制表符或者换行符分割值的。这个分隔符可以修改
for str in a b c d e 
do
	echo $str
done



#修改分割符
oldIFS=$IFS		#保存旧分割符
IFS=','			#新分割符使用 ','

list="a,b,c,d,e"
list2='a b c d e'
for var in $list
do
	echo $var
done

for var2 in $list2
do
	echo $var2
done

# 还原IFS
IFS=$oldIFS

####### demo2 #########
for(( i=0; i<10; i++))
do
	echo $i
done

