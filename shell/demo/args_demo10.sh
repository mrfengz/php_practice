#!/bin/sh

# 输入 sh args_demo10.sh param1 param2
echo "filename： $0"

echo "base filename: $(basename $0)"

echo "param1: $1"

echo "param2: ${2}"

## 获取所有参数 ##
# 要注意 ${!i}这个写法
for ((i=0; i<=$#;i++))
do
	echo ${!i}
done

## $* 和 $@ 的区别 ##
var1=$*
var2=$@

echo "var1: $var1"
echo "var2: $var2"

countvar1=1
countvar2=1
for param in "$*"
do
	echo "first loop param$countvar1: $param"
	countvar1=$[ $countvar1+1 ]

done
echo "countvar1: $countvar1"


for param in "$@"
do
	echo "second param$countvar2 param: $param"
	countvar2=$[ $countvar2 + 1]
done
echo "countvar2: $countvar2"

