# Usa una imagen base de Python 3.12
FROM python:3.12-slim

# Establece el directorio de trabajo en el contenedor
WORKDIR /app

# Instala las dependencias del sistema
RUN apt-get update && apt-get install -y \
    wget \
    unzip \
    gnupg \
    libnss3 \
    libgconf-2-4 \
    libx11-xcb1 \
    libxcomposite1 \
    libxcursor1 \
    libxdamage1 \
    libxext6 \
    libxi6 \
    libxtst6 \
    libpangocairo-1.0-0 \
    libpango-1.0-0 \
    libatk1.0-0 \
    libcups2 \
    libxrandr2 \
    libasound2 \
    libgtk-3-0 \
    xdg-utils \
    --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Añadir la llave GPG de Google Chrome y el repositorio
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - && \
    sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google-chrome.list' && \
    apt-get update && \
    apt-get install -y google-chrome-stable

# Instala las dependencias de Python
COPY requirements.txt /app/
RUN pip install --upgrade pip
RUN pip install -r /app/requirements.txt

# Copia los archivos de tu aplicación al contenedor
COPY . /app

# Establecer variables de entorno necesarias para Chrome
ENV DISPLAY=:99
ENV LANG=C.UTF-8
ENV LC_ALL=C.UTF-8

# Comando para ejecutar tu scraper
CMD ["python", "/app/scrapper.py"]