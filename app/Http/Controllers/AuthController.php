<?php
namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $clientField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
        $client = Client::where($clientField, $request->login)->first();
        if ($client && $client->IsBlocked) {
            return back()->withErrors([
                'login' => 'Ваш аккаунт заблокирован',
            ])->onlyInput('login');
        }
        
        $clientAttempt = Auth::guard('client')->attempt([
            $clientField => $request->login,
            'password' => $request->password,
            'IsBlocked' => false
        ]);

        if ($clientAttempt) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Добро пожаловать!');
        }

        $employeeField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
        $employee = Employee::where($employeeField, $request->login)->first();
        if ($employee && $employee->IsBlocked) {
            return back()->withErrors([
                'login' => 'Ваш аккаунт заблокирован',
            ])->onlyInput('login');
        }
        
        $employeeAttempt = Auth::guard('employee')->attempt([
            $employeeField => $request->login,
            'password' => $request->password,
            'IsBlocked' => false
        ]);

        if ($employeeAttempt) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'login' => 'Неверный логин, email или пароль.',
        ])->onlyInput('login');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'lastname' => 'required|string|max:30',
            'firstname' => 'required|string|max:30',
            'patronymic' => 'nullable|string|max:30',
            'email' => 'required|string|email|max:50|unique:client,email',
            'username' => 'required|string|max:30|unique:client,login',
            'password' => 'required|string|min:6|confirmed',
            'agreement' => 'accepted',
        ]);

        $client = Client::create([
            'Surname' => $request->lastname,
            'Name' => $request->firstname,
            'Patronymic' => $request->patronymic,
            'email' => $request->email,
            'login' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('client')->login($client);

        return redirect()->route('home')->with('success', 'Регистрация успешно завершена!');
    }
    public function logout(Request $request)
    {
        if (Auth::guard('client')->check()) {
            Auth::guard('client')->logout();
        }
        if (Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
}