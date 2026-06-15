🇵🇱 Polish version: [README.pl.md](README.pl.md) 
# Library System

**Online Demo:** https://librarymb.gt.tc

## Login Credentials

- **Administrator:** `1@op.pl` / password: `123`
- **Users:** from `2@op.pl` to `9@op.pl` / password: `123`

A full-stack web application for managing and reading books online, integrated with an autonomous module for automatic translation of chapters from English into Polish.

---

## Project Description

**Library System** is a comprehensive platform for reading web novels (so-called *light novels*), consisting of two independent applications running in a Docker environment:

- **Library** – the main web application enabling browsing, reading, searching, and rating books.
- **Translator** – an autonomous tool for automatic web scraping and translating chapters from fanmtl.com.

---

## Technologies

### Frontend (Library)
- **React 18** + **TypeScript** – implementation of a responsive SPA user interface with static typing to reduce runtime errors.
- **Vite** – modern and highly optimized bundler providing fast Hot Module Replacement (HMR) and efficient production builds.
- **React Router** – advanced client-side routing without page reloads.
- **CSS Modules** – full encapsulation of styles, preventing global class name conflicts and improving scalability.

### Backend (Library)
- **PHP 8.2** + **PHP-FPM** – efficient execution of business logic and providing a stateless RESTful API for the frontend.
- **MySQL 8.0** – main relational database system ensuring data integrity and consistency.
- **PDO / MySQLi** – secure database abstraction layer using prepared statements to prevent SQL Injection attacks.
- **Nginx** – high-performance HTTP server acting as a reverse proxy for PHP-FPM requests.

### Translator Module (Microservice)
- **Node.js** + **Express** – asynchronous, event-driven runtime for background tasks and scraping API.
- **Puppeteer** – Chromium automation (headless browser) enabling precise web scraping of dynamically rendered pages.
- **Google Translate API** – integration with external machine learning service for automated translation (EN → PL).
- **React** + **Vite** – dedicated admin panel for managing scraping processes and real-time monitoring.

### DevOps / Infrastructure
- **Docker** + **Docker Compose** – full containerization ensuring a reproducible environment (Infrastructure as Code).
- **Multi-stage builds** – optimized Docker images with separation of build and production stages.
- **Nginx** – static frontend hosting with maximum performance.

---

## Project Architecture
```text
library-system/
├── Library_app/ # Main library application
│ ├── frontend/biblioteka/ # React + TypeScript (SPA)
│ ├── backend/bibliotekaPHP/# PHP REST API
│ ├── library.sql # MySQL schema and initial data
│ ├── Dockerfile # Multi-stage build (frontend + backend)
│ ├── docker-compose.yml # Container orchestration
│ └── nginx.conf # Nginx configuration
│
└── Translate_app/Translate/ # Translator application
├── frontend/tlumacz/ # Control panel (React)
├── backend/ # Node.js + Express + Puppeteer
└── Dockerfile # Frontend and Node environment build
```
---

## Features

### Library
- Browse a catalog of books with covers, descriptions, and tags
- 1–5 star rating system
- Chapter reader with smooth navigation (previous/next)
- Advanced search system
- Dynamic ranking of top-rated books and recommendation system
- User registration and login system
- **Admin panel:** manage database (add/edit/delete books, titles, covers, descriptions)
- Import chapters directly from JSON files

### Translator
- Web scraping from `fanmtl.com` using Puppeteer
- Automatic text chunking and translation (EN → PL)
- Chapter range support (e.g. chapters 1–50)
- Retry mechanism for network failures
- Export of structured JSON files
- Real-time system logs

---

## How It Works

1. The Translator fetches chapters from `fanmtl.com` using a headless Chromium browser (Puppeteer).
2. The text is processed and translated using the Google Translate API.
3. Translated chapters are saved as JSON in the `exports/` directory.
4. The JSON file is copied into the Library backend directory.
5. The `import_json.php` endpoint imports chapters into the MySQL database.
6. Content becomes immediately available in the Library application.

---

## Installation and Setup

### Prerequisites
- Installed Docker Desktop
- Installed Git

---

### 1. Clone the repository
```bash
git clone https://github.com/MateuszBiern/library-system.git
```

### 2. Run Library
```bash
cd library-system/Library_app
docker compose up --build
```
*Library will be available at: **[http://localhost](http://localhost)**

### 3. Run Translator
Open **a new terminal** and run:
```bash
cd library-system/Translate_app/Translate
docker compose up --build
```
*Translator will be available at:**[http://localhost:5000](http://localhost:5000)**

---

## Book Initialization & Chapter Import Workflow

The process of adding a new book requires synchronization between the main application and the translation module. Below is a step-by-step guide:

1. **Database record initialization:** Log in to the main application as an administrator and create a new book via the Admin Panel.

2. **Resource identification:** Copy the generated **Book ID**. It will serve as the linking key for imported chapters.

3. **Scraping task configuration:** Open the Translator interface (`http://localhost:5000`) and define the following parameters:
   - **Book ID:** Target ID of the previously created book  
   - **Base URL:** Direct URL to the first chapter in the source website (e.g. `https://www.fanmtl.com/novel/ke423463_1.html`)  
   - **From/To chapter:** Chapter range for the scraping process (e.g. 1–50)
4. **Run the process:** Start the task using the **Start** button. Execution time depends on the selected range and Google Translate API limits.
5. **Transfer artifacts:** After translation is completed, copy the generated `.json` file from the Translator workspace (`exports/`) to: Library_app/backend/bibliotekaPHP/mojprojekt/
6. **Database seeding:** Call the synchronization endpoint by visiting: http://localhost/api/import_json  The script will parse the JSON file and permanently store the translated chapters in the MySQL database.
---

##  Screenshots

### Home Page
![main](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/main.png)

### Recommendations & Recently Added
![recommendations](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/recommendation.png)
![last added](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/last_added.png)

### Search
![search](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/search.png)

### Book Page & Reader
![book page](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/book_page.png)
![book chapter](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/book_chapter.png)
![book pagination](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/book_pagination.png)

### Login Panel
![login](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/login.png)

### Admin Panel (Add & Edit)
![add book](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/add_book.png)
![edit books](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/edit_books.png)
![edit book details](https://raw.githubusercontent.com/MateuszBiern/library-system/main/docs/edit_book_details.png)---

## Important Notes

- The first startup may take a few minutes (downloading Docker images + building the frontend).
- The database is automatically initialized on the first run.
- Translating a large number of chapters at once may take a long time due to Google Translate API limits.

