# Laravel User Management & Blog API Service

A Laravel-based REST API service for user management and blog posts with authentication using Laravel Sanctum.

## Features

- User registration and authentication
- User CRUD operations (Create, Read, Update, Delete)
- Blog post CRUD operations (Create, Read, Update, Delete)
- API token-based authentication using Laravel Sanctum
- Input validation and error handling
- JSON API responses
- Blog post status management (draft/published)
- Automatic slug generation
- User ownership validation for blog posts

## API Endpoints

### Public Endpoints (No Authentication Required)

#### 1. User Registration
```
POST /api/users
```
**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

#### 2. User Login
```
POST /api/login
```
**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```
**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abc123..."
    }
}
```

### Protected Endpoints (Authentication Required)

Include the token in the Authorization header:
```
Authorization: Bearer {token}
```

#### 3. Get Current User
```
GET /api/me
```

#### 4. Get All Users
```
GET /api/users
```

#### 5. Get Specific User
```
GET /api/users/{id}
```

#### 6. Update User
```
PUT /api/users/{id}
PATCH /api/users/{id}
```
**Request Body:**
```json
{
    "name": "Updated Name",
    "email": "updated@example.com",
    "password": "newpassword123"
}
```

#### 7. Delete User
```
DELETE /api/users/{id}
```

#### 8. Logout
```
POST /api/logout
```

### Blog Post Endpoints (Authentication Required)

#### 9. Get All Blog Posts
```
GET /api/blog-posts
```
**Query Parameters:**
- `status` - Filter by status (draft/published)
- `user_id` - Filter by user ID
- `my_posts` - Set to `true` to get only current user's posts

#### 10. Create Blog Post
```
POST /api/blog-posts
```
**Request Body:**
```json
{
    "title": "My First Blog Post",
    "content": "This is the content of my blog post...",
    "status": "draft",
    "slug": "my-first-blog-post"
}
```

#### 11. Get Specific Blog Post
```
GET /api/blog-posts/{id}
```

#### 12. Update Blog Post
```
PUT /api/blog-posts/{id}
PATCH /api/blog-posts/{id}
```
**Request Body:**
```json
{
    "title": "Updated Blog Post Title",
    "content": "Updated content...",
    "status": "published"
}
```

#### 13. Delete Blog Post
```
DELETE /api/blog-posts/{id}
```

## Authentication

This API uses Laravel Sanctum for token-based authentication. To access protected endpoints:

1. First, login using the `/api/login` endpoint to get a token
2. Include the token in the Authorization header for subsequent requests:
   ```
   Authorization: Bearer {your-token}
   ```

## Error Responses

All endpoints return consistent error responses:

```json
{
    "success": false,
    "errors": {
        "field": ["Error message"]
    }
}
```

Or for general errors:

```json
{
    "success": false,
    "message": "Error message"
}
```

## Setup Instructions

1. **Install Dependencies:**
   ```bash
   composer install
   ```

2. **Environment Configuration:**
   Create a `.env` file with the following database configuration:
   ```
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

3. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

4. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

5. **Start the Development Server:**
   ```bash
   php artisan serve
   ```

## Testing the API

You can test the API using tools like Postman, curl, or any HTTP client.

### Example curl commands:

**Register a new user:**
```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name":"John Doe","email":"john@example.com","password":"password123"}'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'
```

**Get all users (with authentication):**
```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer {your-token}"
```

**Create a blog post:**
```bash
curl -X POST http://localhost:8000/api/blog-posts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {your-token}" \
  -d '{"title":"My First Post","content":"This is my first blog post content","status":"draft"}'
```

**Get all blog posts:**
```bash
curl -X GET http://localhost:8000/api/blog-posts \
  -H "Authorization: Bearer {your-token}"
```

**Get only published posts:**
```bash
curl -X GET "http://localhost:8000/api/blog-posts?status=published" \
  -H "Authorization: Bearer {your-token}"
```

**Get current user's posts:**
```bash
curl -X GET "http://localhost:8000/api/blog-posts?my_posts=true" \
  -H "Authorization: Bearer {your-token}"
```

## Security Features

- Password hashing using Laravel's Hash facade
- Token-based authentication with Sanctum
- Input validation and sanitization
- CSRF protection (disabled for API routes)
- Rate limiting (can be configured)

## File Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php    # Authentication logic
│   ├── UserController.php    # User CRUD operations
│   └── BlogPostController.php # Blog post CRUD operations
├── Models/
│   ├── User.php             # User model with Sanctum traits
│   └── BlogPost.php         # Blog post model
routes/
└── api.php                  # API route definitions
```

## Requirements

- PHP 8.0 or higher
- Laravel 8.x
- SQLite (or MySQL/PostgreSQL)
- Composer
