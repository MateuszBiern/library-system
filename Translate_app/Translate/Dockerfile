FROM node:18-slim

RUN apt-get update && apt-get install -y \
    chromium \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

# Build frontend
WORKDIR /app/frontend/tlumacz
COPY frontend/tlumacz/package*.json ./
RUN npm install
COPY frontend/tlumacz/ ./
RUN npm run build

# Setup backend
WORKDIR /app/backend
COPY backend/package*.json ./
RUN npm install
COPY backend/ ./

RUN mkdir -p public && cp -r /app/frontend/tlumacz/dist/* ./public/
RUN mkdir -p exports

EXPOSE 5000
CMD ["node", "server.js"]
