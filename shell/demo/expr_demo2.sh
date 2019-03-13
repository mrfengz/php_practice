#!/bin/sh
#注意运算符左右需要加空格
num=$(expr 10 + 2)
echo $(expr 100 % 6)
echo $num
#乘号需要转义，否则会报错
echo $(expr 12 \* 5)

#将计算结果赋给变量
num1=$(expr 10 % 3)

#将计算结果赋给变量
num2=`expr 10 % 6`

echo 'num1: ' $num1

echo 'num2:' $num2



#$[]方括号
num3=10
num4=2
echo "num3+num4=$[$num3+$num4]"
echo "num3*num4=$[$num3*$num4]"



#浮点数运算  bc
# variable=$(echo "options;epression"|bc)

num=$(echo "scale=2;10 / 3"|bc)
echo $num;
