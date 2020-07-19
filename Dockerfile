FROM alpine:latest

ENV CC=/usr/bin/clang
ENV CXX=/usr/bin/clang++

ARG WEBSITE_DOMAIN_NAME
ENV WEBSITE_DOMAIN_NAME ENV ${WEBSITE_DOMAIN_NAME:-biorg.cis.fiu.org}

ENV CFLAGS '-std=c11 -pedantic -pipe -fPIC -fstack-protector -O3 -Wall -Wextra -Wpointer-arith -Wconditional-uninitialized -Wno-unused-parameter -Wno-deprecated-declarations -mtune=native'

ENV CXXFLAGS '-std=c++14 -pedantic -pipe -fPIC -fstack-protector -O3 -Wall -Wextra -Wpointer-arith -Wconditional-uninitialized -Wno-unused-parameter -Wno-deprecated-declarations -mtune=native'

ENV LDFLAGS '-static -s -L/usr/include -L/usr/lib'

WORKDIR /srv/http/backend

ADD . /srv/http/backend

RUN sed -i -e 's/v[[:digit:]]\..*\//edge\//g' /etc/apk/repositories && \
  apk --no-cache upgrade --update && \
  apk --no-cache add -t .runtime-deps \
    argon2 \
    composer \
    musl \
    php7 \
    php7-ctype \
    php7-fpm \
    php7-json \
    php7-mbstring \
    php7-phalcon \
    php7-pecl-apcu \
    php7-pecl-psr \
    php7-pgsql \
    re2c && \
  ln -sf /srv/http/backend/etc/php7/php-fpm.d/www.conf /etc/php7/php-fpm.d/www.conf && \
  rm -rf /tmp/* && \
  rm -rf /var/cache/apk/* /root/.npm /root/.composer

EXPOSE 9000

VOLUME [ "/srv/http/backend" ]

STOPSIGNAL SIGQUIT

CMD [ "php-fpm7", "-R", "-F", "-O" ]
