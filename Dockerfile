FROM ubuntu:22.04

ARG UID=1000
ARG GID=1000

# Avoid prompts from apt during installation
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y software-properties-common curl zip unzip git \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update

# Install PHP extensions
RUN apt-get install -y \
        php8.2-cli php8.2-fpm php8.2-mysql php8.2-gd php8.2-curl \
        php8.2-mbstring php8.2-xml php8.2-zip php8.2-bcmath php8.2-intl \
        php8.2-soap php8.2-readline \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create a non-root user to run the application for better security
RUN groupadd --force -g $GID sail
RUN useradd -ms /bin/bash --no-user-group -g $GID -u $UID sail

WORKDIR /var/www/html

# Copy entrypoint and make it executable
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy application files and set ownership
COPY --chown=sail:sail . /var/www/html

# Switch to non-root user for subsequent commands
USER sail

# Install composer dependencies (this will be cached in the image layer)
RUN composer install --no-interaction --no-progress --prefer-dist

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]