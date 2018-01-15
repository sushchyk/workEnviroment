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

alias gs="git status"
alias gc="git commit -m $@"
alias nah="git reset HEAD --hard && git clean -fd"
