FROM php:7.4-cli

# Instala las dependencias necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instala zip, unzip y git
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl

# Copia el script PHP al contenedor
COPY . /usr/src/myapp

# Establece el directorio de trabajo
WORKDIR /usr/src/myapp

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala las dependencias de Composer
RUN composer require vlucas/phpdotenv guzzlehttp/guzzle

# Comando para ejecutar el script PHP
CMD ["php", "fetch_jobs.php"]
