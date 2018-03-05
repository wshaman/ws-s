# Websocket usage example

*This is just a prove of concept, no practical use is mentioned.*

* PHP ws server 
* PHP client
* JS browser-based client

To run this project:
* Get all the dependencies first:
```
php ./composer.phar self-update
php ./composer.phar update

```
* Next, run WS server and PHP internal server for frontend:
```
php ./cli/run.php
./cli/start_frontend.sh
```

## Using Makefile
All this commands can be replaced with make calls:
`make init && make start`

*Note: all these scripts use `./composer.phar` file. Feel free to set it to path in your system*

## Running

* After FE server is running point your browser to:
```
http://127.0.0.1:9008/?user_id=1&task_id=12
http://127.0.0.1:9008/?user_id=1&task_id=17
http://127.0.0.1:9008/?user_id=16&task_id=17
```
*Note: there are predefined params. You can use index2.html and index3.html*

* And send some messages from php client:
```
php ./cli/client.php
```
