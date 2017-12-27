.PHONY: ${TARGETS}
.DEFAULT_GOAL := help

define say =
    echo "$1"
endef

define say_red =
    echo "\033[31m$1\033[0m"
endef

define say_green =
    echo "\033[32m$1\033[0m"
endef

define say_yellow =
    echo "\033[33m$1\033[0m"
endef

define say_cyan =
    echo "\033[1m\033[36m$1\033[0m\033[21m"
endef

help:
	@$(call say_yellow,"Usage:")
	@$(call say,"  make [command]")
	@$(call say,"")
	@$(call say_yellow,"Available commands:")
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%s\033[0m___%s\n", $$1, $$2}' | column -ts___

install: ## Install project
	@$(call say_cyan,"==\> Install API dependencies")
	@composer install -n
	@php artisan jwt:secret
	@$(call say_cyan,"==\> Install webapp dependencies & build assets")
	@npm --prefix webapp/ install webapp/
	@npm --prefix webapp/ run build
	@make -s db-reset

db-reset: ## Reset the database (both schema & fixtures)
	@$(call say_cyan,"==\> Install Database")
	@php artisan migrate
	@php artisan import:csv

test: ## Launch tests
	@rm -rf ./tests/Fixtures/app/cache/*
	@$(call say_cyan,"==\> Launch unit tests")
	@vendor/bin/phpunit
