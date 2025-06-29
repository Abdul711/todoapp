# 📝 PHP Todo App – Custom MVC Framework

A lightweight Todo List application built from scratch using modern **OOP**, **custom MVC architecture**, and **SOLID design principles** — no frameworks like Laravel or Symfony involved.

> 💼 Perfect as a portfolio project to showcase clean PHP architecture, design patterns, and scalable structure.

---

## 🚀 Features

- ✅ Custom-built MVC framework (Laravel-inspired)
- ✅ PSR-4 Autoloading using `spl_autoload_register()`
- ✅ Repository & Service Layer (Design Patterns)
- ✅ Secure PDO database interaction
- ✅ Environment configuration via `.env`
- ✅ Custom router with support for dynamic parameters
- ✅ SOLID principle applied across components

---

## 🗂️ Project Structure

todo-app/
├── app/
│ ├── Config/ # Database, Env
│ ├── Controllers/ # TaskController
│ ├── Core/ # Router, Request, View, Controller base
│ ├── Repositories/ # TaskRepository
│ ├── Services/ # TaskService
├── views/ # Blade-style templates
├── routes/
│ └── web.php
├── public/
│ └── index.php # Front Controller
├── bootstrap/
│ └── autoload.php # PSR-4 Autoloader
├── .env # Environment configuration
└── README.md



---

## 💻 Installation

1. **Clone the repo**
   ```bash
   git clone https://github.com/yourusername/todo-app.git
cd todo-app
Create the database
CREATE DATABASE todo_app;
Update .env file
DB_HOST=localhost
DB_NAME=todo_app
DB_USER=root
DB_PASS=
APP_TIMEZONE=Asia/Karachi
Run the app

Place the app in htdocs (XAMPP/WAMP) and visit:
http://localhost/todo-app
🧠 Concepts Demonstrated
Concept	Description
MVC Architecture	Clean separation of concerns
SOLID Principles	Applied across service, repository, and controller layers
PSR-4 Autoloading	Fully custom loader using spl_autoload_register()
Dependency Injection	Controllers receive services, services receive repositories
Front Controller Pattern	All requests flow through index.php
Custom Routing	Basic Laravel-style routing with dynamic segments
👨‍💻 Author
Abdul Samad – PHP & Laravel Developer
📍 Karachi, Pakistan

🧩 Future Enhancements
User authentication system (login/register)
Pagination, search, and filter tasks
REST API version
Admin dashboard module
Blade-style templating (`<?= $tasks ?>`, `@foreach`, etc.)
CSRF token implementation for forms
🤝 License
This project is open-source and free to use under the MIT License.

