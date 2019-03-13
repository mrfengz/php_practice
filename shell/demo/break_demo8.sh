#!/bin/sh

# break示例

for((i=0; i<10;i++))
do
	if (( $i >= 6 ));then
		break
	fi
	echo $i
done
	
