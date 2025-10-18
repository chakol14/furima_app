<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AddressRequest;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'ログイン情報が登録されていません。'])->withInput();
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showVerificationNotice()
    {
        return view('auth.verify');
    }

    public function showUserSettings(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        return view('users.settings', ['user' => $user]);
    }

    public function updateUserSettings(AddressRequest $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $user->update($request->validated());

        return redirect()->route('user.settings')->with('success', 'プロフィールを更新しました');
    }

    public function showProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $tab = $request->query('tab', 'buy');
        if (!in_array($tab, ['buy', 'sell'], true)) {
            $tab = 'buy';
        }

        $sellProducts = $user->products()
            ->latest()
            ->get();
        $purchasedProducts = Product::where('buyer_id', $user->id)
            ->latest()
            ->get();

        return view('users.profile', [
            'user' => $user,
            'tab' => $tab,
            'sellProducts' => $sellProducts,
            'purchasedProducts' => $purchasedProducts,
        ]);
    }

    public function showAddressEdit(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        return view('users.address-edit', [
            'user' => $user,
            'product' => $product,
        ]);
    }

    public function updateAddress(AddressRequest $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $user->update($request->validated());

        return redirect()
            ->route('purchase', $product)
            ->with('success', '住所を更新しました');
    }
}
