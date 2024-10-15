<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // View Login
    public function index(): View
    {
        $title = 'Login | IJAMU';
        return view('login', compact('title'));
    }

    // Login Store
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerateToken();

            return redirect()->route('dashboard')->with('success', "Login berhasil!");
        }

        return back()->with('error', 'Email atau password salah');
    }

    // Logout
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->flush();

        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logout Berhasil!');;
    }

    public function profile(): View
    {
        $user = User::findOrFail(Auth::user()->id);
        $imagePath = $user->image_path ? asset('storage/images/' . $user->image_path) : null;

        $data = [
            'title' => 'Profile',
            'user' => $user,
            'imagePath' => $imagePath,
        ];
        return view('dashboard.profile', $data);
    }

    public function edit_profile(Request $request): RedirectResponse
    {
        $rules = [
            'no_telepon' => ['required', 'string', 'max:13', function ($attribute, $value, $fail) {
                if (!preg_match('/^08[0-9]{9,12}$/', $value)) {
                    $fail('Format nomor telepon tidak valid. Harap gunakan format 08xxxxxxxxxx.');
                }
            }],
        ];

        $messages = [
            'no_telepon.required' => 'Nomor telepon harus diisi.',
            'no_telepon.string' => 'Nomor telepon harus berupa teks.',
            'no_telepon.max' => 'Nomor telepon tidak boleh lebih dari 13 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        $user = User::findOrFail(Auth::user()->id);

        $user->update(['no_telepon' => $validated['no_telepon']]);

        return back()
            ->with('success', 'No Telepon berhasil disimpan.');
    }

    // Google Auth Redirect
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    // Google Auth Callback
    public function callback(): RedirectResponse
    {
        try {
            $user = Socialite::driver('google')->user();

            // Cek hanya email unsoed
            $domain = ['@unsoed.ac.id', '@mhs.unsoed.ac.id'];
            $email_domain = substr($user->email, strpos($user->email, '@'));

            if (!in_array($email_domain, $domain)) {
                return redirect()->route('login')->with('error', 'Email tidak valid.');
            }

            $finduser = User::where('email', $user->email)->first();

            if ($finduser) {
                $finduser->update([
                    'gauth_id' => $user->id,
                    'gauth_type' => 'google',
                ]);
            } else {
                $finduser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'gauth_id' => $user->id,
                    'gauth_type' => 'google',
                    'password' => Hash::make('123123')
                ]);
            }

            Auth::login($finduser);
            
            return redirect()->route('dashboard')->with('success', 'Login Berhasil');
        } catch (\Exception $e) {

            return redirect()->route('login')->with('error', 'Error.');
        }
    }
}
