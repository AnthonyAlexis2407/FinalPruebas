<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isAdmin()) {
                // Si ya hay una tienda activa en la sesión (por el proceso de cambio de tienda), la mantenemos
                if (!$request->session()->has('active_store_id')) {
                    session(['active_store_id' => $user->store_id]);
                }
            } else {
                // Los cajeros siempre se limitan a su tienda asignada
                session(['active_store_id' => $user->store_id]);
            }

            return redirect()->intended(route('products.index'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegister()
    {
        $stores = \App\Models\Store::all();
        return view('auth.register', compact('stores'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
            'store_id' => 'required_if:role,cashier|nullable|exists:stores,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Laravel 11+ hashes automatically via 'hashed' cast
            'role' => $validated['role'],
            'store_id' => $validated['store_id'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario registrado correctamente.');
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        // Administrators can see ALL users across all stores.
        // Cashiers only see users of their store + Admins (handled by global scope).
        $users = ($user && $user->isAdmin())
            ? User::withoutGlobalScopes()->get()
            : User::all();

        return view('auth.index', compact('users'));
    }

    public function destroy(Request $request, User $user)
    {
        /** @var User $authUser */
        $authUser = $request->user();

        $request->validate([
            'admin_password' => 'required',
        ]);

        if (!Hash::check($request->admin_password, $authUser->password)) {
            return back()->withErrors(['admin_password' => 'La contraseña de administrador es incorrecta.'])
                ->with('target_user_id', $user->id)
                ->with('target_user_name', $user->name);
        }

        if ($authUser->id == $user->id) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propia cuenta.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
