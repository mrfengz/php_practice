#!/bin/sh

## 单个输入 ##
echo -n "yes or no(y/n)?"
read
echo $REPLY

## 多个输入 ## 

read -p "what's is your name?" first last
echo "firstname: $first"
echo "lastname: $last"


## 超时输入等待设置 ##
if read -t 5 -p "please input your name: " name
then
	echo "your name is: $name"
else
	echo "Sorry, timeout!"
fi
