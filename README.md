# 🚀 Structify

> Interactive Learning Platform for Data Structures, Algorithms, Discrete Mathematics, and Object-Oriented Programming.

Structify adalah platform pembelajaran berbasis **Web** dan **Mobile** yang dirancang untuk membantu mahasiswa, pelajar, dan pengembang memahami konsep-konsep fundamental ilmu komputer melalui pendekatan **visual, interaktif, dan terstruktur**.

Platform ini menggabungkan materi pembelajaran, visualisasi algoritma, latihan soal, serta forum diskusi dalam satu ekosistem pembelajaran yang terintegrasi.

---

## ✨ Key Features

### 📚 Interactive Cheatsheets

Pelajari konsep dengan materi yang terstruktur dan mudah dipahami.

* Dual Language Support (**Python & C++**)
* Syntax Highlighting
* Code Examples & Explanations
* Topic-Based Navigation

### 🎬 Algorithm & Data Structure Visualization

Visualisasi langkah demi langkah untuk membantu memahami bagaimana algoritma dan struktur data bekerja secara internal.

* Array Operations
* Linked List Manipulation
* Stack & Queue Simulation
* Tree Traversal
* Graph Exploration
* Sorting & Searching Animation

### 🎯 Quiz & Assessment System

Uji pemahaman materi melalui kuis interaktif.

* Multiple Choice Questions
* Timer-Based Assessment
* Score History
* Learning Progress Tracker
* Topic-Based Evaluation

### 💬 Community Discussion Forum

Belajar bersama melalui diskusi komunitas.

* Create Discussions
* Question & Answer
* Topic Tagging
* Upvote System
* Knowledge Sharing

### 🔐 Authentication & Role Management

Manajemen pengguna dengan kontrol akses yang aman.

| Role      | Access                           |
| --------- | -------------------------------- |
| User      | Learning, Quiz, Forum            |
| Admin     | Content Management, Analytics    |
| Developer | System Maintenance & Development |

---

## 🏛️ Learning Tracks

Structify menyediakan empat jalur pembelajaran utama:

### 🗂️ Data Structures

* Array
* Linked List
* Stack
* Queue
* Hash Table
* Tree (BST, AVL)
* Heap
* Graph

### ⚙️ Algorithms & Programming

* Searching Algorithms
* Sorting Algorithms
* Recursion
* Divide & Conquer
* Greedy Algorithms
* Backtracking
* Dynamic Programming

### 🔢 Discrete Mathematics

* Propositional Logic
* Sets
* Relations & Functions
* Graph Theory
* Tree Theory
* Combinatorics

### 🧱 Object-Oriented Programming

* Classes & Objects
* Encapsulation
* Inheritance
* Polymorphism
* Abstraction
* Design Principles

---

## 🏗️ System Architecture

```text
┌───────────────────────────────┐
│       React Web App           │
└──────────────┬────────────────┘
               │ REST API
┌──────────────▼────────────────┐
│        Laravel Backend        │
│ Authentication • Quiz • Forum │
└──────────────┬────────────────┘
               │
     ┌─────────▼─────────┐
     │  Python Engine    │
     │ DS & Algorithm    │
     │ Visualization     │
     └─────────┬─────────┘
               │
      ┌────────▼────────┐
      │     MySQL DB    │
      └─────────────────┘
```

---

## 🛠️ Tech Stack

### Frontend

* React.js (Vite)
* Tailwind CSS
* Flutter (Mobile)

### Backend

* Laravel 10
* PHP 8.2
* Laravel Sanctum

### Engine

* Python

### Database

* MySQL
* Eloquent ORM

### Developer Tools

* Prism.js / Shiki
* REST API
* JSON State Engine

---

## 📂 Project Structure

```text
structify/
│
├── frontend-web/
│   ├── src/
│   └── public/
│
├── mobile-app/
│   └── lib/
│
├── backend/
│   ├── app/
│   ├── routes/
│   └── database/
│
├── python-engine/
│   └── algorithms/
│
└── docs/
```

---

## ⚙️ Local Development Setup

### 1. Clone Repository

```bash
git clone https://github.com/username/structify.git
cd structify
```

### 2. Backend Setup (Laravel)

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

### 3. Frontend Setup (React)

```bash
cd frontend-web

npm install

npm run dev
```

### 4. Mobile Setup (Flutter)

```bash
cd mobile-app

flutter pub get

flutter run
```

### 5. Python Engine

```bash
cd python-engine

pip install -r requirements.txt

python main.py
```

---

## 🎯 Project Goals

Structify bertujuan untuk:

* Membantu mahasiswa memahami konsep ilmu komputer secara visual.
* Menyediakan media pembelajaran yang lebih interaktif dibanding buku teks tradisional.
* Menyatukan teori, praktik, dan evaluasi dalam satu platform.
* Mendorong pembelajaran mandiri dan kolaboratif.

---

## 🤝 Contributing

Kontribusi sangat terbuka untuk siapa saja.

1. Fork repository
2. Create a feature branch
3. Commit your changes
4. Push to your branch
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Author

**Lionel Jevon**


Building a better way to learn Computer Science.
