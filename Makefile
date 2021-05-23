start:
	docker-compose up -d
	@echo "started on http://127.0.0.1:8741/"
	@echo "PMA on http://127.0.0.1:8080/"

console:
	docker exec -it www_mariage bash
	cd project/

stop:
	docker-compose stop

wp-watch: #WebPack Encore Watch
	node_modules/.bin/encore dev --watch
