# Laravel — Spotify API + Blade

انسخ هذه المجلدات داخل مشروع Laravel 11 بعد إنشائه.

```bash
composer create-project laravel/laravel spotify-api
cd spotify-api
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

انسخ `app` و `database` و `routes` و `resources/views` من هذا المجلد.

في `bootstrap/app.php` أضف alias للصلاحيات:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
    $middleware->statefulApi();
})
```

في `User` model تأكد من استخدام `Laravel\Sanctum\HasApiTokens`.

```bash
php artisan migrate --seed
php artisan serve
```

حسابات التجربة (من Seeder):

- `admin@spotify.test` / `Admin@123` (admin)
- `editor@spotify.test` / `Editor@123` (editor)
- `user@spotify.test` / `User@123` (listener)

REST API (Sanctum token):

- `POST /api/login`
- `POST /api/logout` (Bearer token)
- `GET /api/user`
- `GET /api/dashboard`
- `GET|POST /api/tracks`
- `GET|PUT|DELETE /api/tracks/{id}`
- `GET /api/artists`

Vue Dashboard يتصل بهذه الـ API عبر `VITE_API_URL`.
