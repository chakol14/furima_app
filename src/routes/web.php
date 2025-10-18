<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

// 商品一覧（おすすめ）
Route::get('/', [ProductController::class, 'index'])->name('top.index');

// マイリスト（?page=mylist で表示）
Route::get('/mylist', function () {
    return redirect()->route('top.index', ['page' => 'mylist']);
})->name('top.mylist');

// 商品詳細
Route::get('/item/{product}', [ProductController::class, 'show'])->name('item.show');

// ログイン・ログアウト
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 会員登録
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// 簡易メール認証・ユーザー設定
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 商品出品
    Route::get('/sell', [ProductController::class, 'create'])->name('products.create');
    Route::post('/sell/confirm', [ProductController::class, 'confirm'])->name('products.confirm');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/sell/thanks', [ProductController::class, 'thanks'])->name('products.thanks');

    // お気に入り登録
    Route::post('/item/{product}/favorite', [ProductController::class, 'toggleFavorite'])->name('item.favorite');

    // コメント投稿
    Route::post('/item/{product}/comments', [ProductController::class, 'storeComment'])->name('item.comment');

    // 商品購入ページ
    Route::get('/purchase/{product}', [ProductController::class, 'purchase'])->name('purchase');
    Route::post('/purchase/{product}', [ProductController::class, 'completePurchase'])->name('purchase.complete');
    Route::get('/purchase/{product}/success', [ProductController::class, 'purchaseSuccess'])->name('purchase.success');

    // 簡易メール認証・ユーザー設定
    Route::get('/verify/email', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/mypage/profile', [AuthController::class, 'showUserSettings'])->name('user.settings');
    Route::post('/mypage/profile', [AuthController::class, 'updateUserSettings'])->name('user.settings.update');
    Route::get('/mypage', [AuthController::class, 'showProfile'])->name('user.profile');

    // 住所変更専用
    Route::get('/purchase/address/{product}', [AuthController::class, 'showAddressEdit'])->name('address.edit');
    Route::post('/purchase/address/{product}', [AuthController::class, 'updateAddress'])->name('address.update');
});
