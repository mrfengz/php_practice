#!/bin/sh

# 多个if-else结构时，可以使用case不同条件的相同处理写在一起，更直观

# 语法
# case variable in
#     pattern1 | pattern2)
#         commands;;
#     pattern3)
#	     commands2;;
#     esac

num=2

case $num in
	1|2)
		echo "num=1 or num=2";;
	3)		
		echo "num=3";;
	4)
		echo "num=4";;
	*)
		echo "default"

esac
