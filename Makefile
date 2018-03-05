NAME = ws-simple-example

COMPOSER_BIN = php ./composer.phar

.PHONY : init start stop

start :
	./cli/daemonize_ws_example.sh

stop :
	-pkill -f './cli/daemonize_ws_example.sh'


init :
	$(COMPOSER_BIN) self-update
	$(COMPOSER_BIN) update
	chmod +x ./cli/daemonize.sh
	chmod +x ./cli/start_frontend.sh
