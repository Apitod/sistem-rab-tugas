# Alur Middleware Sistem RAB Jurusan

## Tabel Role → Prefix → Middleware Stack

| Role          | Route Prefix | Middleware Stack                           |
|---------------|--------------|--------------------------------------------|
| pengusul      | /pengusul    | auth, CheckRole:pengusul                   |
| kaprodi       | /kaprodi     | auth, CheckRole:kaprodi                    |
| wd_keuangan   | /wd          | auth, CheckRole:wd_keuangan                |
| dekan         | /dekan       | auth, CheckRole:dekan                      |
| tata_usaha    | /tu          | auth, CheckRole:tata_usaha                 |
| shared        | /            | auth                                       |

---

## ASCII Diagram: Pipeline Request per Role

```
HTTP Request (misal: GET /pengusul/rab)
            |
            v
    +---------------+
    |  web middleware|  Enkripsi cookie, session, CSRF
    |  global group  |
    +---------------+
            |
            v
    +---------------+
    |  auth          |  Cek session login (Breeze)
    |  middleware    |  Jika belum login → redirect /login
    +---------------+
            |
            v
    +--------------------+
    |  CheckRole         |  Baca auth()->user()->role
    |  middleware        |  Bandingkan dengan role yang diijinkan
    |  (:pengusul)       |  Jika tidak sesuai → redirect / + error
    +--------------------+
            |
            v
    +--------------------+
    |  Controller        |  Pengusul\RabController@index
    +--------------------+
            |
            v
    +--------------------+
    |  Service Layer     |  RabWorkflowService::getByUser(...)
    +--------------------+
            |
            v
    +--------------------+
    |  Model / Database  |  RabProposal::query()->...
    +--------------------+
            |
            v
    HTTP Response (Blade view / redirect)
```

Untuk role lain, hanya parameter `CheckRole` yang berbeda:

```
kaprodi    → CheckRole:kaprodi
wd_keuangan→ CheckRole:wd_keuangan
dekan      → CheckRole:dekan
tata_usaha → CheckRole:tata_usaha
```

---

## Cara Daftarkan CheckRole di bootstrap/app.php

Laravel 13 **tidak menggunakan `app/Http/Kernel.php`**. Middleware alias didaftarkan di `bootstrap/app.php` melalui callback `withMiddleware`:

```php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
```

**Penting:**
- Alias `'role'` → bisa dipakai di route sebagai `middleware('role:kaprodi')`.
- Dalam `routes/web.php` proyek ini, `CheckRole` juga dipakai langsung via class FQCN + parameter string, contoh: `CheckRole::class . ':pengusul'` — keduanya sah di Laravel 13.
- Jangan daftarkan middleware di tempat lain (tidak ada `Kernel.php`).

---

## Scenario: Akses Ditolak

```
GET /kaprodi/rab oleh user role=pengusul
        |
        v
    auth → OK (sudah login)
        |
        v
    CheckRole:kaprodi
        |
    auth()->user()->role = 'pengusul'
    in_array('pengusul', ['kaprodi']) = false
        |
        v
    redirect('/') dengan flash error:
    "Akses ditolak: peran tidak sesuai."
```

---

## CheckRole Middleware: Lokasi File

```
app/Http/Middleware/CheckRole.php
```

Kode:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            return redirect('/')->with('error', 'Akses ditolak: peran tidak sesuai.');
        }
        return $next($request);
    }
}
```
