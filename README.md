# Blog Management System API

A Laravel 12 RESTful API for a blog management system with roles and permissions. Built with Sanctum, Docker, and OpenAPI (Swagger) annotations. 

Author: Temirlan Ibragimov

---

## 📦 Requirements

* Docker
* Docker Compose

---

## 🚀 Quick Start

1. **Clone the repository:**

   ```bash
   git clone https://github.com/your/repo.git
   cd your-repo
   ```

2. **Build and start containers:**

   ```bash
   docker compose up -d --build
   ```

3. **Install dependencies:**

   ```bash
   docker compose exec php composer install
   ```

4. **Copy environment file and generate key:**

   ```bash
   cp src/.env.example src/.env
   docker compose exec php php artisan key:generate
   ```

5. **Run migrations and seeders:**

   ```bash
   docker compose exec php php artisan migrate:fresh --seed
   ```

6. **Access your application:**

   Visit [http://localhost:8000](http://localhost:8000)

---

## 🧪 Running Tests

```bash
php artisan test
```

Or inside the container:

```bash
docker compose exec php php artisan test
```

---

## 🔐 Authentication

The API uses Laravel Sanctum.

* Register: `POST /api/register`
* Login: `POST /api/login`
* Logout: `POST /api/logout`
* Get current user: `GET /api/me`

Pass token using `Authorization: Bearer {token}` header.

---

## 📚 API Endpoints

| Endpoint          | Method | Auth | Description         |
| ----------------- | ------ | ---- | ------------------- |
| `/api/posts`      | GET    | ✅    | List all posts      |
| `/api/posts`      | POST   | ✅    | Create new post     |
| `/api/categories` | GET    | ✅    | List all categories |
| `/api/categories` | POST   | ✅    | Create category     |
| `/api/tags`       | GET    | ✅    | List all tags       |

More endpoints are available. Swagger/OpenAPI documentation is provided. [SwaggerHub](https://app.swaggerhub.com/apis/FLASHTIMA/BMS/1.0.0)

---

## 🛡️ Roles and Permissions

This project uses [spatie/laravel-permission](https://github.com/spatie/laravel-permission).

### Roles

* `Admin`: full access
* `Editor`: can publish and edit posts
* `Author`: can edit posts
* `Reader`: read-only access

### Permissions

* `manage_posts`
* `publish_posts`
* `edit_posts`
* `delete_posts`
* `manage_categories`
* `manage_users`

These are seeded via:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Or:

```bash
docker compose exec php php artisan migrate:fresh --seed
```

---

## 🛠️ Artisan Shortcuts

Run any artisan command via:

```bash
docker compose exec php php artisan <command>
```

---

## 📂 Folder Structure

* `src/` — Laravel project root
* `dockerfiles/` — Docker configurations
* `nginx/` — Nginx configuration
* `env/` — `.env` files for containers

---

## 📬 Contact

If you have any questions or suggestions, feel free to reach out:

**Email:** `flashtima@gmail.com`
