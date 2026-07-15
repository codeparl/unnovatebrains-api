# Auth testing + deployment notes (Unnovate Brains live chat)

## 1) SQL
Run the updated schema once on the DB:

```bash
php database/migrate.php
```


## 2) Create initial users
Create at least one admin/agent row (example):

```sql
INSERT INTO users (name, email, password, role)
VALUES (
  'Admin',
  'admin@example.com',
  '<password_hash_here>',
  'admin'
);
```
Generate password hash with PHP:

```bash
php -r "echo password_hash('password', PASSWORD_DEFAULT);"
```

## 3) Login (public)
```bash
curl -sS -X POST 'https://api.unnovatebrains.com/api/auth/login' \
  -H 'Content-Type: application/json' \
  -d '{"email":"admin@example.com","password":"password"}'
```
Response should include `token`.

Store token:

```bash
TOKEN='...'
```

## 4) Me (protected)
```bash
curl -sS 'https://api.unnovatebrains.com/api/auth/me' \
  -H "Authorization: Bearer $TOKEN"
```

## 5) Protected chat routes
Example:
```bash
curl -sS 'https://api.unnovatebrains.com/api/chat/conversations' \
  -H "Authorization: Bearer $TOKEN"
```

For reply:
```bash
curl -sS -X POST 'https://api.unnovatebrains.com/api/chat/reply' \
  -H 'Content-Type: application/json' \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"conversation_id":1,"message":"Hello"}'
```

## 6) Logout
```bash
curl -sS -X POST 'https://api.unnovatebrains.com/api/auth/logout' \
  -H "Authorization: Bearer $TOKEN"
```
After logout, protected endpoints must return:

```json
{"message":"Unauthenticated"}
```

## 7) GitHub Actions / VPS deployment
- Ensure `.env` on VPS contains at least:
  - `DB_HOST`
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`
- Run `php database/migrate.php` after pulling changes (migration step).

- PHP dependencies are already handled via `composer install` in your existing workflow.

