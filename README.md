# Delivery Service API

## Overview
This project provides an API for a delivery service, including user authentication, location-based delivery representative search, and Firebase push notifications.

## Features
- **User Authentication** (Register, Login, Logout, Verification)
- **Nearest Delivery Representative API** (Calculate distance between user and delivery representatives)
- **Admin Panel** (CRUD operations for users)
- **Push Notifications** (Send notifications to all users via Firebase)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/delivery-service.git
   cd delivery-service
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure the database in `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

6. Start the development server:
   ```bash
   php artisan serve
   ```

## API Endpoints
### Authentication
- **Register:** `POST /api/auth/register`
- **Login:** `POST /api/auth/login`
- **Logout:** `POST /api/auth/logout`
- **Verify Account:** `POST /api/auth/verify`

### User Management (Admin Panel)
- **Get Users:** `GET /api/admin/users`
- **Create User:** `POST /api/admin/users`
- **Update User:** `PUT /api/admin/users/{id}`
- **Delete User:** `DELETE /api/admin/users/{id}`

### Delivery Representatives
- **Get Nearest Deliveries:** `GET /api/user/nearest-deliveries`

### Push Notifications
- **Send Notification to All Users:** `POST /api/admin/send-notification`

## API Endpoints
| Method | Endpoint | Description |
|--------|---------|-------------|
| POST   | `/api/register` | Register a new user |
| POST   | `/api/login` | Authenticate a user |
| POST   | `/api/logout` | Log out the user |
| POST   | `/api/verify` | Verify a user's phone number |
| GET    | `/api/delivery/nearest` | Get nearest delivery representatives |
| POST   | `/api/admin/users` | Create a new user (Admin) |
| GET    | `/api/admin/users` | List all users (Admin) |
| PUT    | `/api/admin/users/{id}` | Update user details (Admin) |
| DELETE | `/api/admin/users/{id}` | Delete a user (Admin) |
| POST   | `/api/admin/notify` | Send push notification to all users |

## Postman Collection
You can import the Postman collection from the following link:
[Download Postman Collection](Delivery.postman_collection.json)

