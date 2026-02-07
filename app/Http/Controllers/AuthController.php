<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function loginApi(Request $request)
{
    return response()->json([
        'status' => true,
        'message' => 'API login berhasil (contoh endpoint)',
    ]);
}

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required', // Bisa email atau NIS
            'password' => 'required'
        ]);

        // Cek apakah input adalah email atau NIS
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nis';
        
        if (Auth::attempt([$field => $request->login, 'password' => $request->password])) {
            $request->session()->regenerate();
            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'login' => 'Email/NIS atau password salah.',
        ])->withInput($request->only('login'));
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'bendahara':
                return redirect()->route('bendahara.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}