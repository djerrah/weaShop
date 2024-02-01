FROM php:8.2.0-fpm

ENV WORKDIR /var/www
ENV TZ=Europe/Paris
ENV COMPOSER_HOME /var/composer
ENV COMPOSER_MEMORY_LIMIT -1

ARG HOST_USERID
ARG HOST_GROUPID
ARG GIT_ACCESS_TOKEN
ARG GIT_USERNAME
ARG GIT_PASSWORD

VOLUME /var/composer/cache

RUN ln -sfn /dev/null /var/log/lastlog

RUN echo $(cat /etc/passwd)

# dependencies required
RUN apt-get clean
RUN apt-get update && apt-get install -y \
        zlib1g-dev libicu-dev g++ \
        libjpeg62-turbo-dev \
        libzip-dev \
        libpng-dev \
        libwebp-dev \
        libfreetype6-dev \
    	libxml2-dev \
    	git \
        vim \
        wget \
        zip \
        zsh \
    	zip \
    	unzip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-configure gd --with-webp=/usr/include/webp --with-jpeg=/usr/include --with-freetype=/usr/include/freetype2/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# add config files
ADD conf/php.ini /usr/local/etc/php/conf.d/app.ini
ADD conf/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN mkdir -p /var/log/xdebug/ && touch /var/log/xdebug/xdebug.log && chmod 777 /var/log/xdebug/xdebug.log

# add timezone
#RUN rm /etc/localtime && \
#    ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime && \
#    "date" \

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN git config --global url."https://${GIT_ACCESS_TOKEN}@github.com".insteadOf "ssh://git@github.com" && \
    git config --global user.name "${GIT_USERNAME}" && \
    git config --global user.email "${GIT_PASSWORD}"

# Install zsh
RUN sh -c "$(wget -O- https://github.com/deluan/zsh-in-docker/releases/download/v1.1.5/zsh-in-docker.sh)" -- \
    -t https://github.com/denysdovhan/spaceship-prompt \
    -a 'SPACESHIP_PROMPT_ADD_NEWLINE="false"' \
    -a 'SPACESHIP_PROMPT_SEPARATE_LINE="false"' \
    -p git \
    -p ssh-agent \
    -p https://github.com/zsh-users/zsh-autosuggestions \
    -p https://github.com/zsh-users/zsh-completions

USER root

WORKDIR ${WORKDIR}
EXPOSE 9000
CMD ["php-fpm"]
