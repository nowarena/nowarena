#!/usr/bin/env bash

#[1] => Shopping
#[3] => Services
#[4] => Other
#[5] => Dining
#[6] => Fashion
#[8] => Casual Eats
#[9] => Bar
#[10] => Coffee and Tea
#[11] => News and People
#[12] => Spa and Fitness
#[13] => Tech

declare -a arr=(1 3 4 5 6 9 10 11 12 13)

#echo ${arr[*]}

#wget -q --spider http://dev.nowarena.com//twitter/getfeed

for i in ${arr[@]}; do
    wget http://dev.nowarena.com/read?cats_id=$i 2>&1 >> /tmp/gettweets.log
    echo $?
    echo $i
    printf "\n"
done
exit 0
