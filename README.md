🟦 E-Commerce Mini API (Laravel + JWT Auth)

Simplified Laravel E-Commerce backend with JWT Authentication, Products Management, Orders, and Cart System.

📌 Features

🔐 JWT Authentication (Register, Login, Logout, Me)

🛍 Products CRUD (Create, Read, Update, Delete)

📦 Cart system (auto-created for each user)

🧾 Orders (validates stock, reduces stock, clears cart)

🚨 Out-of-stock rule

📊 Clean JSON API ready for frontend use

🏗 Tech Stack

Laravel 11

PHP 8+

tymon/jwt-auth

MySQL

📁 Project Structure
app/
  Http/
    Controllers/
      Api/
        AuthController.php
        ProductController.php
        OrderController.php
        CartController.php
database/
  migrations/
routes/
  api.php

⚙️ Installation & Setup
1️⃣ Clone the project
git clone https://github.com/mugamaldev/ecommerce-api.git
cd ecommerce-mini

2️⃣ Install dependencies
composer install

3️⃣ Create .env
cp .env.example .env

4️⃣ Generate key
php artisan key:generate

5️⃣ Configure DB in .env
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=

6️⃣ Run migrations
php artisan migrate

7️⃣ Install JWT package

Already installed in project, just run:

php artisan jwt:secret

8️⃣ Run API server
php artisan serve --port=8000

🧪 API Endpoints
🔐 Auth
Method	Endpoint	Description
POST	/api/auth/register	Create new user
POST	/api/auth/login	Login + get token
POST	/api/auth/logout	Logout
GET	/api/auth/me	Get user info
🛍 Products
Method	Endpoint	Description
GET	/api/products	List all
POST	/api/products	Create
PUT	/api/products/{id}	Update
DELETE	/api/products/{id}	Delete

Rule:
stock = 0 → status = out_of_stock

🧾 Orders
Method	Endpoint	Description
POST	/api/orders	Create order

Order Flow:

Validate stock

Decrease stock

Clear cart

Return order summary

📈 DB Diagram (Simple)
users
  id, name, email, password

products
  id, name, price, stock

carts
  id, user_id

cart_items
  id, cart_id, product_id, quantity

orders
  id, user_id, total, address, phone

order_items
  id, order_id, product_id, quantity, price

✔ Done. This README is production-ready.
