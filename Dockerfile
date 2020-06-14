FROM alpine:latest as builder

ENV CC=/usr/bin/clang
ENV CXX=/usr/bin/clang++

ARG WEBSITE_DOMAIN_NAME
ENV WEBSITE_DOMAIN_NAME ENV ${WEBSITE_DOMAIN_NAME:-biorg.cis.fiu.org}

ENV CFLAGS '-std=c11 -pedantic -pipe -fPIC -fstack-protector -O3 -Wall -Wextra -Wpointer-arith -Wconditional-uninitialized -Wno-unused-parameter -Wno-deprecated-declarations -mtune=native'

ENV CXXFLAGS '-std=c++14 -pedantic -pipe -fPIC -fstack-protector -O3 -Wall -Wextra -Wpointer-arith -Wconditional-uninitialized -Wno-unused-parameter -Wno-deprecated-declarations -mtune=native'

ENV LDFLAGS '-static -s -L/usr/include -L/usr/lib'

WORKDIR /tmp

ADD ./frontend /tmp/frontend
ADD ./backend /srv/http/backend

RUN sed -i -e 's/v[[:digit:]]\..*\//edge\//g' /etc/apk/repositories && \
  apk update && \
  apk add --no-cache -t .build-deps \
    composer \
    nodejs \
    npm \
    php7 \
    php7-phalcon \
    php7-pecl-psr \
    php7-pgsql \
    php7-ctype && \
  mkdir -p /srv/http/frontend/public

RUN cd frontend && \
  npm install --cache /tmp/npm-cache --no-fund && \
  npx ng build --aot \
    --build-optimizer \
    --common-chunk \
    --cross-origin anonymous \
    --extract-css \
    --optimization \
    --output-path=/srv/http/frontend/public \
    --prod \
    --subresource-integrity \
    --vendor-chunk && \
  cd /srv/http/backend && \
  composer update --no-dev --no-autoloader --no-suggest --no-cache

RUN rm -rf /tmp/* && \
  apk del .build-deps && \
  rm -rf /var/cache/apk/* /root/.npm /root/.composer

FROM alpine:latest

WORKDIR /srv/http

COPY --from=builder /srv/http/frontend /srv/http/frontend
COPY --from=builder /srv/http/backend /srv/http/backend

RUN sed -i -e 's/v[[:digit:]]\..*\//edge\//g' /etc/apk/repositories && \
  apk add -t .runtime-deps \
    php7 \
    php7-phalcon \
    php7-pecl-psr \
    php7-pgsql \
    php7-pdo \
    php7-ctype

VOLUME [ "/srv/http" ]

CMD ["/bin/sh"]
