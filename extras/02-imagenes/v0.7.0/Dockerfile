FROM alpine:latest
RUN apk update && apk add --update alpine-sdk
RUN mkdir /app
WORKDIR  /app
COPY . /app
RUN mkdir bin
RUN gcc -Wall hello.c -o bin/hello
CMD /app/bin/hello
