#!/bin/sh

flag=0
while (( $flag <= 10 ))
do
	if (( $flag == 5 ))
	then
		flag=$[$flag+1]
		continue
	fi
	echo "outFlag=$flag"

	for (( innerFlag=11; innerFlag < 20; innerFlag++ ))
	do
		if (( $innerFlag == 16 ));then
			flag=$[$flag+1]
			continue 2
		fi
		echo "innerFlag=$innerFlag"
	done
done
