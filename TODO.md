# TODO - Auth system for Unnovate Brains live chat

- [x] Implement authentication DB schema (users, api_tokens)
  - [x] Append auth tables into `database/schema.sql` (per approved decision)


- [x] Create auth module files under `app/Auth/`

  - [x] `Controllers/AuthController.php`
  - [x] `Models/User.php`
  - [x] `Models/ApiToken.php`
  - [x] `Services/AuthService.php` (prepared PDO statements, password_verify, random_bytes, hash_equals)
  - [x] `Middleware/AuthMiddleware.php` (Bearer token validation, attach authenticated user)


- [x] Protect chat routes + add auth endpoints
  - [x] Update `routes/api.php` to add:
    - [x] POST /api/auth/login
    - [x] POST /api/auth/logout
    - [x] GET /api/auth/me
  - [x] Add middleware protection to:
    - [x] GET /api/chat/conversations
    - [x] GET /api/chat/conversation/{id}
    - [x] POST /api/chat/reply
    - [x] POST /api/chat/close/{id}


- [ ] Add curl testing commands
- [ ] Deployment notes for GitHub Actions / VPS

