FROM ubuntu:latest
#https://bobcares.com/blog/debian_frontendnoninteractive-docker/
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update -y --fix-missing && apt-get upgrade -y && apt-get install -y php && apt-get install -y php-dev && apt-get install -y php-mysql && apt-get install -y php-curl && apt-get install -y php-json && apt-get install -y php-common && apt-get install -y php-mbstring && apt-get install -y php-fileinfo && apt-get install -y php-gd && apt-get install -y php-zip && apt-get install -y libapache2-mod-php && apt-get install -y nano && apt-get install -y npm && apt-get install -y sudo && apt-get install -y composer && apt-get install -y lsof && apt-get install -y mysql-client && apt-get install -y mysql-server && apt-get install -y unrar

COPY apache2.conf /etc/apache2/apache2.conf
COPY server.conf /etc/apache2/sites-available/server.conf
COPY startdb.sh /var/www/html/get-city-by-ip/startdb.sh
COPY loaddatabase.sh /
COPY dump.rar /

RUN a2dissite 000-default.conf && a2ensite server.conf && a2enmod headers && a2enmod rewrite

CMD apachectl -D FOREGROUND

EXPOSE 8000

WORKDIR /
ARG CACHEBUST=1
RUN sh loaddatabase.sh
