all: docs/api

docs/api: clean
	phpdoc -q -f helpers/pherl_helper.php -t docs -ti 'Pherl API reference' -o HTML:frames:earthli -dn Pherl

clean:
	-rm -r docs/*
