COMPOSER ?= composer
PHPUNIT_OPTS =


composer:
	$(COMPOSER) install


fix:
	vendor/bin/php-cs-fixer fix src
	vendor/bin/php-cs-fixer fix tests


fix-diff:
	./vendor/bin/php-cs-fixer fix src --diff --dry-run -v
	./vendor/bin/php-cs-fixer fix tests --diff --dry-run -v


test: composer
	vendor/bin/phpunit -c .


lint: lint-php phpcs fix-diff lint-composer lint-eol
	@echo All good.


lint-eol:
	@echo "\n==> Validating unix style line endings of files:files"
	@! grep -lIUr --color '^M' src/ composer.json composer.lock || ( echo '[ERROR] Above files have CRLF line endings' && exit 1 )
	@echo All files have valid line endings


lint-composer:
	@echo "\n==> Validating composer.json and composer.lock:"
	$(COMPOSER) validate --strict


lint-php:
	@echo "\n==> Validating all php files:"
	@find src tests -type f -name \*.php | while read file; do php -l "$$file" || exit 1; done


phpcs:
	vendor/bin/phpcs


coverage: composer
	vendor/bin/phpunit -c .


outdated:
	$(COMPOSER) outdated


ci: composer lint test outdated
	@echo "All quality checks passed"


.PHONY: test composer coverage phpcs php-cs lint lint-php ci
