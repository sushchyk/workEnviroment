alias ping_aliases="echo PONG"

usephp() {
    sudo update-alternatives --set php /usr/bin/php${1} \
    && sudo a2dismod php5.6 \
    && sudo a2dismod php7.0 \
    && sudo a2dismod php7.1 \
    && sudo a2enmod php${1} \
    && sudo service apache2 restart
}
