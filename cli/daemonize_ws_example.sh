#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

./start_frontend.sh &
php ./run.php &

while true ; do sleep 60; done
