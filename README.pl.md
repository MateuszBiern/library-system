🇬🇧 English version: [README.en.md](README.md)
# Library System 

 **Demo online:** [librarymb.gt.tc](https://librarymb.gt.tc)

###  Dane do logowania (Wersja Demo)
* **Administrator:** `1@op.pl` / hasło: `123`
* **Użytkownicy:** od `2@op.pl` do `9@op.pl` / hasło: `123`

Aplikacja webowa typu Full-stack do zarządzania i czytania książek online, zintegrowana z autonomicznym modułem do automatycznego tłumaczenia rozdziałów z języka angielskiego na polski.

---

##  Opis projektu

**Library System** to kompleksowa platforma do czytania książek webowych (tzw. *light novels*), składająca się z dwóch niezależnych aplikacji działających w środowisku Docker:

*  **Biblioteka** – główna aplikacja webowa umożliwiająca przeglądanie, czytanie, wyszukiwanie oraz ocenianie książek.
*  **Tłumacz** – autonomiczne narzędzie do automatycznego pobierania (web scrapingu) i tłumaczenia rozdziałów z serwisu fanmtl.com.

---

##  Technologie

###  Frontend (Biblioteka)
* **React 18** + **TypeScript** – implementacja responsywnego interfejsu użytkownika (UI) w architekturze SPA ze statycznym typowaniem, co minimalizuje błędy w czasie działania (runtime errors).
* **Vite** – nowoczesny i wysoce zoptymalizowany bundler zapewniający błyskawiczny proces HMR (Hot Module Replacement) oraz wydajne budowanie paczek produkcyjnych.
* **React Router** – zaawansowana obsługa nawigacji po stronie klienta (Client-Side Routing) bez przeładowywania strony.
* **CSS Modules** – pełna enkapsulacja arkuszy stylów, gwarantująca brak konfliktów nazw klas globalnych i ułatwiająca skalowanie kodu.

###  Backend (Biblioteka)
* **PHP 8.2** + **PHP-FPM** – wydajna realizacja logiki biznesowej aplikacji oraz wystawianie bezstanowego API (RESTful API) dla frontendu.
* **MySQL 8.0** – główny system zarządzania relacyjną bazą danych, zapewniający integralność i spójność informacji o użytkownikach i zasobach.
* **PDO / MySQLi** – bezpieczna warstwa abstrakcji bazy danych z wykorzystaniem bindowania parametrów (Prepared Statements) w celu ochrony przed atakami SQL Injection.
* **Nginx** – wysokowydajny serwer HTTP pełniący rolę *reverse proxy* dla zapytań do PHP-FPM.

###  Moduł Tłumacza (Mikroserwis)
* **Node.js** + **Express** – asynchroniczne, oparte na zdarzeniach środowisko uruchomieniowe do obsługi zadań w tle i API modułu scrapującego.
* **Puppeteer** – automatyzacja silnika Chromium (headless browser) pozwalająca na precyzyjny web scraping dynamicznie renderowanego drzewa DOM na stronach źródłowych.
* **Google Translate API** – integracja z zewnętrznym dostawcą usług uczenia maszynowego do zautomatyzowanej translacji tekstu (EN → PL).
* **React** + **Vite** – dedykowany interfejs administracyjny do zarządzania procesami scrapowania i monitorowania logów w czasie rzeczywistym.

###  DevOps / Infrastruktura
* **Docker** + **Docker Compose** – pełna konteneryzacja zapewniająca hermetyczne i reprodukowalne środowisko uruchomieniowe (Infrastructure as Code) spójne na każdym systemie operacyjnym.
* **Multi-stage builds** – wielostopniowe budowanie obrazów Docker pozwalające na drastyczną redukcję wagi finalnych kontenerów oraz zwiększenie bezpieczeństwa (oddzielenie środowiska *build* od *production*).
* **Nginx** – statyczne serwowanie zbudowanych plików frontendu z maksymalną wydajnością.

---

##  Architektura projektu

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

##  Funkcjonalności

###  Biblioteka
* Przeglądanie katalogu książek wraz z okładkami, opisami i tagami.
* System oceniania książek w skali 1-5 gwiazdek.
* Czytnik rozdziałów z płynną nawigacją (poprzedni/następny).
* Zaawansowane wyszukiwanie pozycji.
* Dynamiczny ranking najwyżej ocenianych tytułów oraz system rekomendacji na podstawie ocen.
* System rejestracji i logowania użytkowników.
* **Panel Administratora:** zarządzanie bazą (dodawanie, usuwanie, edycja tytułu, opisu, okładki).
* Importowanie nowych rozdziałów bezpośrednio z plików JSON.

###  Tłumacz
* Web scraping treści z serwisu `fanmtl.com` przy użyciu Puppeteer.
* Automatyczne dzielenie tekstu na paczki i translacja EN→PL via Google Translate.
* Obsługa zakresów rozdziałów (np. rozdział 1-50).
* *Retry mechanism* – automatyczne ponawianie prób przy błędach sieciowych.
* Eksport gotowych struktur do zunifikowanych plików JSON.
* Podgląd logów systemowych w czasie rzeczywistym.

---

##  Jak to działa?

1. Tłumacz pobiera treść rozdziałów ze strony `fanmtl.com` za pomocą ukrytej przeglądarki Chromium (Puppeteer).
2. Tekst jest procesowany i tłumaczony przez Google Translate API.
3. Przetłumaczone rozdziały trafiają do pliku JSON w katalogu `exports/`.
4. Plik JSON jest kopiowany do katalogu `mojprojekt/` w aplikacji biblioteki.
5. Endpoint `import_json.php` importuje rozdziały do bazy danych MySQL.
6. Treść staje się natychmiast dostępna dla czytelników w aplikacji biblioteki.

---

##  Instalacja i uruchomienie

### Wymagania wstępne
* Zainstalowany [Docker Desktop](https://www.docker.com/products/docker-desktop/)
* Zainstalowany [Git](https://git-scm.com/)

### 1. Klonowanie repozytorium
```bash
git clone https://github.com/MateuszBiern/library-system.git
```

### 2. Uruchomienie Biblioteki
```bash
cd library-system/Library_app
docker compose up --build
```
*Biblioteka będzie dostępna pod adresem:* **[http://localhost](http://localhost)**

### 3. Uruchomienie Tłumacza
Otwórz **nowe okno terminala** i wykonaj:
```bash
cd library-system/Translate_app/Translate
docker compose up --build
```
*Tłumacz będzie dostępny pod adresem:* **[http://localhost:5000](http://localhost:5000)**

---

##  Procedura inicjalizacji nowej książki i importu rozdziałów

Proces dodawania nowej pozycji wymaga synchronizacji między aplikacją główną a modułem tłumaczącym. Poniżej znajduje się instrukcja krok po kroku:

1. **Inicjalizacja rekordu w bazie:** Zaloguj się do aplikacji głównej jako administrator i utwórz nową pozycję poprzez Panel Administratora.
2. **Identyfikacja zasobu:** Skopiuj wygenerowany przez system identyfikator książki (**Book ID**). Będzie on pełnił rolę klucza powiązania dla importowanych rozdziałów.
3. **Konfiguracja zadania scrapującego:** Przejdź do interfejsu modułu Tłumacza (`http://localhost:5000`) i zdefiniuj parametry procesu:
   * **Book ID:** Docelowy identyfikator utworzonej wcześniej książki.
   * **Base URL:** Bezpośredni adres URL do pierwszego docelowego rozdziału w serwisie źródłowym (np. `https://www.fanmtl.com/novel/ke423463_1.html`).
   * **From/To chapter:** Zakres iteracji dla skryptu pobierającego (np. od 1 do 50).
4. **Uruchomienie procesu:** Zainicjuj zadanie przyciskiem **Start**. Czas wykonania jest zależny od zdefiniowanego zakresu oraz limitów narzucanych przez API Google Translate.
5. **Transfer artefaktów:** Po zakończeniu procesu translacji, skopiuj wygenerowany plik z danymi (`.json`) z przestrzeni roboczej Tłumacza (`exports/`) do katalogu docelowego w backendzie Biblioteki: `Library_app/backend/bibliotekaPHP/mojprojekt/`.
6. **Zasilenie bazy danych (Seeding):** Wywołaj endpoint synchronizacyjny przechodząc pod adres `http://localhost/api/import_json`. Skrypt przeparsuje plik JSON i trwale zapisze przetłumaczone rozdziały w relacyjnej bazie danych MySQL.

---

##  Screenshots

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

##  Ważne uwagi
* Pierwsze uruchomienie może potrwać kilka minut (pobieranie obrazów Docker + budowanie frontendu).
* Baza danych jest automatycznie inicjalizowana przy pierwszym uruchomieniu.
* Tłumaczenie dużej liczby rozdziałów na raz może potrwać długo ze względu na limity API Google Translate.
