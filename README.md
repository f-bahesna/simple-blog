```markdown
# Technical Assessment - Blog App

This project contains a simple **PHP Backend** and **Frontend** for managing blog posts.

---

## 📁 Project Structure

```bash

project/
├── backend/
│   ├── public/
│   │   └── api.php          # Backend entry point
│   ├── sql/
│   │   └── schema_and_seed.sql  # Database schema & seed data
│   ├── src/                 # PHP source files
│   ├── .env                 # Environment configuration
│   └── composer.json        # Composer dependencies
└── frontend/
    └── index.php            # Frontend entry point

---

## ⚙️ How to Run the App

### 1️⃣ Install Dependencies
Go to the backend directory and install PHP dependencies:
```bash
cd backend
composer install
````

---

### 2️⃣ Add Configuration & SQL Files

* Place your `.env` file into:

  ```
  backend/.env
  ```
* Place your SQL file into:

  ```
  backend/sql/schema_and_seed.sql
  ```

---

### 3️⃣ Import the Database

Run this command to migrate and seed your database:

```bash
mysql -u root -p < backend/sql/schema_and_seed.sql
```

---

### 4️⃣ Start the Backend Server

Run this command from inside the backend directory:

```bash
php -S localhost:8000 public/api.php
```

Backend runs at → [http://localhost:8000](http://localhost:8000)

---

### 5️⃣ Start the Frontend

In another terminal, go to the frontend directory and run:

```bash
php -S localhost:8888
```

Frontend runs at → [http://localhost:8888](http://localhost:8888)

---

## Summary

| Component   | Port | Command                                |
| ----------- | ---- | -------------------------------------- |
| Backend API | 8000 | `php -S localhost:8000 public/api.php` |
| Frontend UI | 8888 | `php -S localhost:8888`                |

---

## Requirements

* PHP 8.1+
* Composer
* MySQL running locally

---
