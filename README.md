# Library System
LINK: https://librarymb.gt.tc
Aplikacja do zarządzania biblioteką zbudowana na React + PHP + MySQL + Nginx w Dockerze.

## Wymagania

- Docker Desktop (https://www.docker.com/products/docker-desktop/)

## Uruchomienie

```bash
# Library System

## Wymagania
- Docker Desktop (https://www.docker.com/products/docker-desktop/)
- Git

## Instalacja

1. Sklonuj repozytorium:
git clone https://github.com/MateuszBiern/library-system.git

2. Uruchom bibliotekę:
cd library-system/Library_app
docker compose up --build

Biblioteka dostępna pod: http://localhost

3. Uruchom tłumacz (w nowym oknie terminala):
cd library-system/Translate_app/Translate
docker compose up --build

Tłumacz dostępny pod: http://localhost:5000

## Jak używać tłumacza

1. Wejdź na http://localhost:5000
2. Podaj Book ID (musi istnieć w bibliotece)
3. W polu Base URL wklej link do książki np:
   https://www.fanmtl.com/novel/ke423463_1.html
4. Podaj zakres rozdziałów (From/To)
5. Kliknij Start
6. Po zakończeniu skopiuj plik JSON z exports/ do Library_app/backend/bibliotekaPHP/mojprojekt/
7. Wejdź na http://localhost/api/import_json aby zaimportować rozdziały do biblioteki
```
## Uwagi

- ADMIN LOGIN : 1@op.pl 123, USER LOGIN: 2@op.pl 123(2-9 @op.pl)
- Pierwsze uruchomienie może potrwać kilka minut (pobieranie obrazów + budowanie frontendu)
- Baza danych jest automatycznie inicjalizowana z pliku library.sql
- Aplikacja działa na porcie 80
