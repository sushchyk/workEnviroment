usephp() {
    sudo update-alternatives --set php /usr/bin/php${1}
    sudo a2dismod php5.6
    sudo a2dismod php7.0
    sudo a2dismod php7.1
    sudo a2enmod php${1}
    sudo service apache2 restart
}

pu() {
   if [ -f vendor/bin/phpunit ]; then
     vendor/bin/phpunit "$@"
   else
     phpunit "$@"
   fi
}

puf() {
   if [ -f vendor/bin/phpunit ]; then
     vendor/bin/phpunit --filter="$@"
   else
     phpunit --filter="$@"
   fi
}

alias c="composer $@"

alias pa="php artisan $@"
alias pat="php artisan tinker"
alias pamt="php artisan make:test $1"
alias lcr="echo > storage/logs/laravel.log"

alias gs="git status"
alias gc="git commit -m $@"
alias nah="git reset HEAD --hard && git clean -fd"
