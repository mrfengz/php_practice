#!/bin/sh

# while循环

flag=0

while test $flag -le 10
do
	echo $flag
	flag=$[$flag+1]
done

flag=3
while (( $flag <= 10 ))
do
	echo $flag
	flag=$[$flag+1]
done


flag=5
while [ $flag -le 10 ]
do
	echo $flag;
	flag=$[$flag+1]
done
