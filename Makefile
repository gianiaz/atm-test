
default: header
	@echo ""
	@echo "  ${boldunderline}COMANDI DISPONIBILI${normal}  "
	@echo ""
	@echo "      ${bold}code-style-fix${normal} - Code style fix con php-cs-fixer"
	@echo ""
	@echo "      ${bold}case-study-0${normal} - Case study 0"
	@echo ""

shell:
	@docker-compose run fpm /bin/sh

case-study-0:
	@docker-compose run fpm /usr/local/bin/php -f ./withdraw.php 60

case-study-1:
	@docker-compose run fpm /usr/local/bin/php -f ./withdraw.php 1000

case-study-2:
	@docker-compose run fpm /usr/local/bin/php -f ./withdraw.php 0

code-style-fix:
	@docker-compose run -T fpm ./vendor/bin/php-cs-fixer fix --verbose --ansi

docker-build-php:
	@docker build -t gianiaz/test-code . -f docker/Dockerfile


header:
	@echo ""
	@echo "          _             _                   __   ___  ________  ___"
	@echo "   ____ _(_)___ _____  (_)___ _____       _/_/  /   |/_  __/  |/  /"
	@echo "  / __ \`/ / __ \`/ __ \/ / __ \`/_  /     _/_/   / /| | / / / /|_/ /"
	@echo " / /_/ / / /_/ / / / / / /_/ / / /_   _/_/    / ___ |/ / / /  / /"
	@echo " \__, /_/\__,_/_/ /_/_/\__,_/ /___/  /_/     /_/  |_/_/ /_/  /_/"
	@echo "/____/"



bold := "\\033[1m"
normal := "\\033[0m"
boldunderline := "\\033[1m\\033[4m"

.SILENT:
