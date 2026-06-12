# Library System

Aplikacja do zarządzania biblioteką zbudowana na React + PHP + MySQL + Nginx w Dockerze.

## Wymagania

- Docker Desktop (https://www.docker.com/products/docker-desktop/)

## Uruchomienie

bash
# 1. Pobranie z Gituba
git clone [https://github.com/MateuszBiern/library-system.git](https://github.com/MateuszBiern/library-system.git)

# 2. Wejście do folderu
cd library-system

# 3. Zabezpieczenie końcówek linii
git config core.autocrlf false
git rm --cached -r .
git reset --hard

# 4. Odpalenie całości
docker compose up --build

4. Otwórz przeglądarkę i wejdź na:
   http://localhost

## Uwagi

- Pierwsze uruchomienie może potrwać kilka minut (pobieranie obrazów + budowanie frontendu)
- Baza danych jest automatycznie inicjalizowana z pliku library.sql
- Aplikacja działa na porcie 80
