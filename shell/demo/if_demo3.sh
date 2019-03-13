#!/bin/sh

# if command;  #退出状态码：正常执行返回0。 command状态为0，则执行then后边的语句
# then
#   commands
# else/elif
#	commands
# fi

###test  使用test命令进行判断###
#可以用于字符串、数字和文件判断比较
num1=100
num2=200

if test $num1 -eq $num2
then
	echo num1等于num2
else
	echo num1不等于num2
fi

# 文件是否存在判断
# -f 判断文件是否存在
# -d 判断是否存在且为目录
if test -f expr_demo2.sh
then
	echo 'expr_demo2.sh文件存在'
else
	echo 'expr_demo2.sh文件不存在'
fi



num1=100
num2=60
### 使用(( expression ))进行条件判断 ###
#个别操作符需要转义
if (( num1 \> num2 ));then
	echo "num1>num2"
else
	echo "num1<num2"
fi


### 使用[[ expression ]],需要注意空格 ###
#提供了一些test没有的字符串操作
var1=test
var2=Test
if [[ $var1 < $var2 ]];then
	echo "var1 < var2"
else
	echo "var1 > var2"
fi
