FROM nginx:alpine

#install package
RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    zip \
    npm \
    supervisor \
    libzip-dev \
    oniguruma-dev \
    autoconf \
    gcc \
    g++ \
    make \
    openssl\
    php82 \
    php82-fpm \
    php82-pdo \
    php82-pdo_mysql \
    php82-mysqli \
    php82-opcache \
    php82-session \
    php82-ctype \
    php82-curl \
    php82-phar \
    php82-iconv \
    php82-mbstring \
    php82-tokenizer \
    php82-xml \
    php82-simplexml \
    php82-gd \
    php82-xmlwriter \
    php82-xmlreader \
    php82-fileinfo \
    php82-zip \
    php82-dom \
    php82-pecl-imagick \
    && ln -s /usr/bin/php82 /usr/bin/php


#COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

CMD ["/bin/sh", "-c", "php-fpm82 -D && nginx -g 'daemon off;'"]

#daftar php82-fpm ke user & grup nginx
RUN sed -i 's/^user = .*/user = nginx/' /etc/php82/php-fpm.d/www.conf && \
    sed -i 's/^group = .*/group = nginx/' /etc/php82/php-fpm.d/www.conf

RUN printf "upload_max_filesize = 10M\npost_max_size = 10M" > /etc/php82/php.ini    

EXPOSE 80