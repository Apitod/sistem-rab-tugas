<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memverifikasi role user.
 *
 * Penggunaan di routes/web.php:
 *   Route::middleware(['auth', 'role:kaprodi'])->group(...)
 *   Route::middleware(['auth', 'role:kaprodi,wd_keuangan'])->group(...) // multiple roles
 *
 * Registrasi alias 'role' di bootstrap/app.php:
 *   $middleware->alias(['role' => CheckRole::class]);
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Cek apakah role user cocok dengan parameter middleware.
     * Support multiple role: middleware('role:kaprodi,wd_keuangan')
     * → user dengan SALAH SATU role tersebut diizinkan akses.
     *
     * @param  Request  $request
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string   ...$roles  Satu atau lebih role yang diizinkan
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        // User belum login — biarkan middleware auth handle ini
        // Tapi defensive check tetap diperlukan
        if (! $user) {
            return redirect('/')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user ada dalam daftar role yang diizinkan
        if (! in_array($user->role, $roles)) {
            return redirect('/')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
