start:
	docker-compose up -d
	@echo "started on http://127.0.0.1:8741/"
	@echo "PMA on http://127.0.0.1:8080/"
	@echo "Mailcatcher on http://127.0.0.1:8025/"

console:
	docker exec -it www_mariage bash

stop:
	docker-compose stop

wp-watch:
	node_modules/.bin/encore dev --watch

php-stan:
    vendor/bin/phpstan analyse src --memory-limit=-1