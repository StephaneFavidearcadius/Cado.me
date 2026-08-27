# Cado.me Preview Run Doc

## How to reproduce artifacts

This project runs on WAMP (Apache + MySQL). For the preview, we use PHP's built-in dev server.

1. PHP 8.4+ is required (available at `C:\wamp64\bin\php\php8.4.15\php.exe`)
2. `.env` must exist at the project root with WAMP defaults (already in place)
3. `vendor/` must be installed via `composer install` (already in place)
4. MySQL database `cado_me` must exist in WAMP's MySQL (already imported)

## How to run the server

Start PHP's built-in server with the app's entry point as router:

```
C:\wamp64\bin\php\php8.4.15\php.exe -S 127.0.0.1:8080 public/index.php
```

Port: **8080** (avoids conflict with WAMP's Apache on port 80)

### Windows detach (bash/MSYS2)

```bash
/c/wamp64/bin/php/php8.4.15/php.exe -S 127.0.0.1:8080 public/index.php > .freebuff/preview.log 2>&1 &
```

### Windows detach (PowerShell)

```powershell
Start-Process -FilePath 'C:\wamp64\bin\php\php8.4.15\php.exe' -ArgumentList '-S','127.0.0.1:8080','public/index.php' -RedirectStandardOutput '.freebuff\preview.log' -RedirectStandardError '.freebuff\preview.log.err' -WindowStyle Hidden -PassThru
```
