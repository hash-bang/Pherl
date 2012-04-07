all: docs/api

docs/api: clean
	phpdoc -q -f helpers/pherl.php -t docs/api -ti 'Pherl API reference' -o HTML:frames:earthli -dn Pherl

clean:
	-rm -r docs/api/*