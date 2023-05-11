FROM --platform=linux/amd64 webdevops/php-nginx-dev:8.1

RUN wget -O "/usr/local/bin/go-replace" "https://github.com/webdevops/goreplace/releases/download/1.1.2/gr-arm64-linux" \
    && chmod +x "/usr/local/bin/go-replace" \
    && "/usr/local/bin/go-replace" --version

WORKDIR /app
ENV COMPOSER_MEMORY_LIMIT=-1

RUN apt-get update
RUN apt install -y iputils-ping \
    nano \
    vim \
    htop