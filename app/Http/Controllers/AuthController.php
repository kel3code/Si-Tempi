<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    private static function em(string $u): string { return strtolower(trim($u)) . '@sitempi.local'; } // username disimpan sebagai email internal
    function login(Request $r) {
        $r->validate(['user' => 'required', 'pw' => 'required']);
        if (!Auth::attempt(['email' => self::em($r->user), 'password' => $r->pw], true)) return response()->json(['message' => 'Username atau password salah'], 422);
        $r->session()->regenerate(); return ['ok' => true];
    }
    function register(Request $r) {
        if (User::count() > 0 && getenv('ALLOW_REGISTER') !== 'true') return response()->json(['message' => 'Pendaftaran ditutup. Hubungi pengelola untuk membuat akun.'], 403);
        $d = $r->validate(['nama' => 'required|max:100', 'user' => ['required', 'max:50', 'regex:/^[\w.@+-]+$/'], 'pw' => 'required|min:4']);
        if (User::where('email', self::em($d['user']))->exists()) return response()->json(['message' => 'Username sudah dipakai.'], 422);
        Auth::login(User::create(['name' => $d['nama'], 'email' => self::em($d['user']), 'password' => $d['pw']]), true);
        $r->session()->regenerate(); return ['ok' => true];
    }
    function logout(Request $r) { Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken(); return ['ok' => true]; }
}
