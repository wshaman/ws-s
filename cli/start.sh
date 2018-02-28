#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

php -S 0.0.0.0:9008 -t ${DIR}/../web
