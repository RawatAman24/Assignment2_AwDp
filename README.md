# TrendyKart – Dynamic E-Commerce Website (PHP & MySQL)
Course: Advanced Web Development in PHP (2010043347) – Assignment 2
Category chosen: Fashion & Clothing

## Setup (XAMPP / WAMP / LAMP)
1. Copy the `trendykart` folder into `htdocs` (XAMPP) or `www` (WAMP).
2. Start Apache and MySQL, open phpMyAdmin → Import → select `database.sql`.
3. Edit `config.php` if your DB user/password differ (default: root / empty) or the folder name differs (`BASE_URL`).
4. Open http://localhost/trendykart/

## Demo logins
| Role     | Login                | Password  |
|----------|----------------------|-----------|
| Customer | demo@example.com     | User@123  |
| Admin    | admin  (/admin/login.php) | Admin@123 |

## Features
Customer: home (slider, featured categories/products, offers, new arrivals), categories, product listing with search, product detail, session cart (add/update/remove), checkout, payment page (COD/UPI/Card demo), order success, profile update, order history + details, About, Contact (saved in DB), login/register (validation + password_hash).
Admin: separate login, dashboard stats, user management (search/view/edit/deactivate/delete), category CRUD with image upload, product CRUD with image upload/status/stock, order list + details + status updates.
Security: PDO prepared statements, password_hash/verify, CSRF tokens, output escaping, session regeneration, image-type checks.
