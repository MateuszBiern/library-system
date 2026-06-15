# Library System 📚

🌐 **Demo online:** [librarymb.gt.tc](https://librarymb.gt.tc)

### 🔑 Dane do logowania (Wersja Demo)
* **Administrator:** `1@op.pl` / hasło: `123`
* **Użytkownicy:** od `2@op.pl` do `9@op.pl` / hasło: `123`

Aplikacja webowa typu Full-stack do zarządzania i czytania książek online, zintegrowana z autonomicznym modułem do automatycznego tłumaczenia rozdziałów z języka angielskiego na polski.

---

## 📝 Opis projektu

**Library System** to kompleksowa platforma do czytania książek webowych (tzw. *light novels*), składająca się z dwóch niezależnych aplikacji działających w środowisku Docker:

* 📖 **Biblioteka** – główna aplikacja webowa umożliwiająca przeglądanie, czytanie, wyszukiwanie oraz ocenianie książek.
* 🤖 **Tłumacz** – autonomiczne narzędzie do automatycznego pobierania (web scrapingu) i tłumaczenia rozdziałów z serwisu fanmtl.com.

---

## 🛠️ Technologie

### Frontend (Biblioteka)
* **React 18** + **TypeScript** – budowa interfejsu użytkownika
* **Vite** – szybki bundler i środowisko deweloperskie
* **React Router** – routing po stronie klienta (SPA)
* **CSS Modules** – izolowane stylowanie komponentów

### Backend (Biblioteka)
* **PHP 8.2** + **PHP-FPM** – logika serwerowa i REST API
* **MySQL 8.0** – relacyjna baza danych
* **PDO / MySQLi** – bezpieczna komunikacja z bazą danych
* **Nginx** – serwer HTTP oraz reverse proxy dla PHP-FPM

### Tłumacz
* **Node.js** + **Express** – serwer HTTP i API backendowe
* **Puppeteer** – headless browser do zaawansowanego scrapingu treści
* **Google Translate API** – automatyczne tłumaczenie tekstu (EN → PL)
* **React** + **Vite** – intuicyjny panel sterowania procesem tłumaczenia

### DevOps / Infrastruktura
* **Docker** + **Docker Compose** – pełna konteneryzacja środowiska
* **Multi-stage builds** – optymalizacja rozmiaru obrazów produkcyjnych
* **Nginx** – serwowanie plików statycznych frontendu

---

## 📐 Architektura projektu

```text
library-system/
├── Library_app/              # Główna aplikacja biblioteki
│   ├── frontend/biblioteka/  # React + TypeScript (SPA)
│   ├── backend/bibliotekaPHP/# PHP REST API
│   ├── library.sql           # Schema i dane startowe bazy MySQL
│   ├── Dockerfile            # Multi-stage build (Frontend + Backend)
│   ├── docker-compose.yml    # Orkiestracja kontenerów bazy i serwera
│   └── nginx.conf            # Konfiguracja serwera Nginx
│
└── Translate_app/Translate/  # Aplikacja tłumacza
    ├── frontend/tlumacz/     # Panel sterowania (React)
    ├── backend/              # Node.js + Express + Puppeteer
    └── Dockerfile            # Budowanie frontendu i środowiska Node
```

---

## 🌟 Funkcjonalności

### 📖 Biblioteka
* Przeglądanie katalogu książek wraz z okładkami, opisami i tagami.
* System oceniania książek w skali 1-5 gwiazdek.
* Czytnik rozdziałów z płynną nawigacją (poprzedni/następny).
* Zaawansowane wyszukiwanie pozycji.
* Dynamiczny ranking najwyżej ocenianych tytułów oraz system rekomendacji na podstawie ocen.
* System rejestracji i logowania użytkowników.
* **Panel Administratora:** zarządzanie bazą (dodawanie, usuwanie, edycja tytułu, opisu, okładki).
* Importowanie nowych rozdziałów bezpośrednio z plików JSON.

### 🤖 Tłumacz
* Web scraping treści z serwisu `fanmtl.com` przy użyciu Puppeteer.
* Automatyczne dzielenie tekstu na paczki i translacja EN→PL via Google Translate.
* Obsługa zakresów rozdziałów (np. rozdział 1-50).
* *Retry mechanism* – automatyczne ponawianie prób przy błędach sieciowych.
* Eksport gotowych struktur do zunifikowanych plików JSON.
* Podgląd logów systemowych w czasie rzeczywistym.

---

## 🔄 Jak to działa?

1. Tłumacz pobiera treść rozdziałów ze strony `fanmtl.com` za pomocą ukrytej przeglądarki Chromium (Puppeteer).
2. Tekst jest procesowany i tłumaczony przez Google Translate API.
3. Przetłumaczone rozdziały trafiają do pliku JSON w katalogu `exports/`.
4. Plik JSON jest kopiowany do katalogu `mojprojekt/` w aplikacji biblioteki.
5. Endpoint `import_json.php` importuje rozdziały do bazy danych MySQL.
6. Treść staje się natychmiast dostępna dla czytelników w aplikacji biblioteki.

---

## 🚀 Instalacja i uruchomienie

### Wymagania wstępne
* Zainstalowany [Docker Desktop](https://www.docker.com/products/docker-desktop/)
* Zainstalowany [Git](https://git-scm.com/)

### 1. Klonowanie repozytorium
```bash
git clone [https://github.com/MateuszBiern/library-system.git](https://github.com/MateuszBiern/library-system.git)
```

### 2. Uruchomienie Biblioteki
```bash
cd library-system/Library_app
docker compose up --build -d
```
*Biblioteka będzie dostępna pod adresem:* **[http://localhost](http://localhost)**

### 3. Uruchomienie Tłumacza
Otwórz **nowe okno terminala** i wykonaj:
```bash
cd library-system/Translate_app/Translate
docker compose up --build -d
```
*Tłumacz będzie dostępny pod adresem:* **[http://localhost:5000](http://localhost:5000)**

---

## ➕ Jak dodać nową książkę?

1. Zaloguj się do biblioteki jako administrator i dodaj książkę przez panel admina.
2. **Zapamiętaj ID książki**.
3. Wejdź na `http://localhost:5000` (Panel Tłumacza).
4. Podaj dane w formularzu:
   * **Book ID:** ID książki dodanej w bibliotece.
   * **Base URL:** link do książki, np. `https://www.fanmtl.com/novel/ke423463_1.html`.
   * **From/To chapter:** zakres rozdziałów do pobrania.
5. Kliknij **Start** i poczekaj na zakończenie procesu.
6. Skopiuj wygenerowany plik JSON z folderu `exports/` do lokalizacji `Library_app/backend/bibliotekaPHP/mojprojekt/`.
7. Wejdź w przeglądarce na `http://localhost/api/import_json`, aby zaimportować rozdziały do bazy.

---

## 📸 Screenshots

### Strona główna
![main](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/main.png)

### Rekomendacje i ostatnio dodane
![recommendations](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/recommendation.png)
![last added](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/last_added.png)

### Wyszukiwarka
![search](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/search.png)

### Strona książki i czytnik
![book page](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/book_page.png)
![book chapter](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/book_chapter.png)
![book pagination](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/book_pagination.png)

### Panel logowania
![login](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/login.png)

### Panel Admina (Dodawanie i edycja)
![add book](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/add_book.png)
![edit books](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/edit_books.png)
![edit book details](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/edit_book_details.png)

---

## 💡 Ważne uwagi
* Pierwsze uruchomienie może potrwać kilka minut (pobieranie obrazów Docker + budowanie frontendu).
* Baza danych jest automatycznie inicjalizowana przy pierwszym uruchomieniu.
* Tłumaczenie dużej liczby rozdziałów na raz może potrwać długo ze względu na limity API Google Translate.
