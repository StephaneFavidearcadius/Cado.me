# Cado.me - Run Instructions

## How to Run the Server

1. **Start the PHP built-in server:**
   ```bash
   cd C:\wamp64\www\Cado.me
   C:\wamp64\bin\php\php8.4.15\php.exe -S 127.0.0.1:8080 -t public public\index.php
   ```

2. **Access the app:** http://127.0.0.1:8080

3. **Or via Apache (WAMP):** http://localhost/Cado.me/public/
   - A `.htaccess` at `C:\wamp64\www\` also routes `localhost/*` to Cado.me

## Environment

- PHP 8.4.15 (WAMP)
- MySQL (WAMP) — database: `cado_me`
- `.env` at project root — already configured for WAMP defaults

## Admin Account

- Email: `admin@cado.me`
- Password: `password`

## User Account (with communities)

- Email: `favidestephanearcadius@gmail.com`
- Identifiant: `stephanefavide`
- Has 3 communities: AI MASTERY, AI First, AI Builders
