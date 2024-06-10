stan:
	php vendor/bin/phpstan analyse -l max src tests

unit:
	php vendor/bin/phpunit tests