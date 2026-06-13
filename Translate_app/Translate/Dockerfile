FROM node:18-slim

# Instalujemy Chromium – to automatycznie zainstaluje KAŻDĄ potrzebną bibliotekę Linuxa
RUN apt-get update && apt-get install -y \
    chromium \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Informujemy Puppeteera, żeby nie pobierał własnego Chroma, tylko użył tego systemowego
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

WORKDIR /app/backend

# Kopiowanie i instalacja zależności backendu
COPY backend/package*.json ./
RUN npm install

# Kopiowanie reszty plików projektu do kontenera
COPY . /app

# Przerzucenie plików zbudowanego Reacta
RUN mkdir -p public && cp -r /app/frontend/tlumacz/dist/* ./public/

# Tworzenie folderu na eksportowane pliki JSON
RUN mkdir -p exports

EXPOSE 5000

CMD ["node", "server.js"]