FROM nginx:1.10-alpine

RUN apk upgrade --update && apk --no-cache add vim

RUN rm -rf /etc/nginx/conf.d/*

ADD ./conf/.bashrc /root/.bashrc
ADD ./conf/.bashrc /var/www/.bashrc

COPY ./conf/conf.d /etc/nginx/conf.d

VOLUME [ "/var/www" , "/var/log/nginx"]