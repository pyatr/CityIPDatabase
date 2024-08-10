FROM ubuntu:latest
#https://bobcares.com/blog/debian_frontendnoninteractive-docker/
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y --fix-missing
RUN apt-get upgrade -y
RUN apt-get install -y php
RUN apt-get install -y php-dev
RUN apt-get install -y php-mysql
RUN apt-get install -y php-curl
RUN apt-get install -y php-json
RUN apt-get install -y php-common
RUN apt-get install -y php-mbstring
RUN apt-get install -y php-fileinfo
RUN apt-get install -y php-gd
RUN apt-get install -y php-zip
RUN apt-get install -y libapache2-mod-php
RUN apt-get install -y nano
RUN apt-get install -y npm
RUN apt-get install -y composer
RUN apt-get install -y lsof
RUN apt-get install -y mysql-client
RUN apt-get install -y mysql-server
RUN apt-get install -y unrar

COPY apache2.conf /etc/apache2/apache2.conf
COPY server.conf /etc/apache2/sites-available/server.conf
COPY startdb.sh /
COPY loaddatabase.sh /
COPY dump.rar /

RUN sh loaddatabase.sh

RUN a2dissite 000-default.conf
RUN a2ensite server.conf
#Для CORS и связи сайта с сервером
RUN a2enmod headers
RUN a2enmod rewrite

CMD apachectl -D FOREGROUND

EXPOSE 8000