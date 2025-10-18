<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\CommentRequest;

class ProductController extends Controller
{
    // 商品詳細表示
    public function show(Product $product)
    {
        // JSONからコメントを取得
        $commentData = $product->comment_data ?? [];
        $comments = collect($commentData)->map(function ($comment) {
            return (object) [
                'user' => (object) ['name' => $comment['user_name'] ?? 'ユーザー'],
                'comment' => $comment['comment'] ?? '',
                'created_at' => $comment['created_at'] ?? null,
            ];
        });

        // お気に入り数を取得
        $favorite_count = $product->favorites()->count();

        // ログインユーザーがお気に入り登録しているか
        $is_favorited = false;
        if (Auth::check()) {
            $is_favorited = $product->favorites()->where('user_id', Auth::id())->exists();
        }

        return view('products.show', [
            'product' => $product,
            'comments' => $comments,
            'favorite_count' => $favorite_count,
            'is_favorited' => $is_favorited,
        ]);
    }

    // 購入画面表示
    public function purchase(Product $product)
    {
        $user = Auth::user();

        // ユーザーがログインしている場合はデータベースから情報を取得
        if ($user) {
            $profile = [
                'postal_code' => $user->postal_code ?? 'XXX-YYYY',
                'address' => $user->address ?? '住所が登録されていません',
                'building_name' => $user->building_name ?? '',
            ];
        } else {
            // ログインしていない場合はデフォルト値を設定
            $profile = [
                'postal_code' => 'XXX-YYYY',
                'address' => '住所が登録されていません',
                'building_name' => '',
            ];
        }

        $paymentMethod = session()->get('purchase_payment_method', 'コンビニ支払い');

        return view('products.purchase', [
            'product' => $product,
            'profile' => $profile,
            'payment_method' => $paymentMethod,
            'user' => $user,
        ]);
    }

    // 購入完了
    public function completePurchase(PurchaseRequest $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validated();

        session()->put('purchase_payment_method', $validated['payment_method']);

        // コンビニ支払いもカード支払いもStripe決済へ
        if (!empty(config('services.stripe.secret'))) {
            return $this->processStripePayment($request, $product);
        }

        // Stripe未設定の場合は通常処理（開発環境用）
        $product->update([
            'buyer_id' => Auth::id(),
            'is_sold' => true,
        ]);

        $favorite = $product->favorites()->where('user_id', Auth::id())->first();
        if (!$favorite) {
            $product->favorites()->create([
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('purchase', $product)
            ->with('status', '購入が完了しました。');
    }

    // Stripe決済処理（GuzzleでAPI呼び出し）
    private function processStripePayment(Request $request, Product $product)
    {
        $stripeSecret = config('services.stripe.secret');

        if (empty($stripeSecret)) {
            return redirect()->route('purchase', $product)
                ->with('error', 'Stripe設定が完了していません。');
        }

        try {
            $client = new \GuzzleHttp\Client();

            // 支払い方法に応じて決済タイプを設定
            $paymentMethodTypes = $request->payment_method === 'カード支払い'
                ? ['card']
                : ['konbini'];

            $formParams = [
                'line_items[0][price_data][currency]' => 'jpy',
                'line_items[0][price_data][product_data][name]' => $product->name,
                'line_items[0][price_data][unit_amount]' => (int) $product->price,
                'line_items[0][quantity]' => 1,
                'mode' => 'payment',
                'success_url' => route('purchase.success', ['product' => $product->id]),
                'cancel_url' => route('purchase', ['product' => $product->id]),
            ];

            // 支払い方法を追加
            foreach ($paymentMethodTypes as $index => $type) {
                $formParams["payment_method_types[$index]"] = $type;
            }

            $response = $client->post('https://api.stripe.com/v1/checkout/sessions', [
                'auth' => [$stripeSecret, ''],
                'form_params' => $formParams,
            ]);

            $session = json_decode($response->getBody()->getContents(), true);

            return redirect($session['url']);
        } catch (\Exception $e) {
            return redirect()->route('purchase', $product)
                ->with('error', '決済処理に失敗しました。: ' . $e->getMessage());
        }
    }

    // Stripe決済成功後の処理
    public function purchaseSuccess(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 商品を購入済みに更新
        $product->update([
            'buyer_id' => Auth::id(),
            'is_sold' => true,
        ]);

        // お気に入りに追加
        $favorite = $product->favorites()->where('user_id', Auth::id())->first();
        if (!$favorite) {
            $product->favorites()->create([
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('purchase', $product)
            ->with('status', '購入が完了しました。');
    }

    // 新規保存
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'brand'       => ['nullable', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'condition'   => ['nullable', 'string', 'max:255'],
            'categories'  => ['nullable', 'array'],
        ]);

        $product = new Product(
            collect($validated)->only(['name', 'brand', 'price', 'description', 'condition'])->toArray()
        );

        $product->user_id = auth()->id();

        if (session()->has('temp_image')) {
            $tempPath = session('temp_image');
            $newPath = str_replace('temp/', 'items/', $tempPath);

            if (Storage::disk('public')->exists($tempPath)) {
                Storage::disk('public')->move($tempPath, $newPath);
                $product->image_path = $newPath;
            }

            session()->forget('temp_image');
        }

        $product->save();

        return redirect()->route('products.thanks');
    }

    // 更新
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'brand'       => ['nullable', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'condition'   => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:4096'],
        ]);

        $product->fill($validated);

        if ($request->hasFile('image')) {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('items', 'public');
        }

        $product->save();

        return redirect()->route('item.show', $product);
    }
    public function index(Request $request)
    {
        $page = $request->query('page', 'recommend');
        $keyword = $request->query('keyword');

        if ($page === 'mylist') {
            // ログインユーザーのお気に入り商品を取得
            if (Auth::check()) {
                $products = Auth::user()->favorites;

                // 検索キーワードがある場合はフィルタリング
                if ($keyword) {
                    $products = $products->filter(function ($product) use ($keyword) {
                        return stripos($product->name, $keyword) !== false;
                    });
                }
            } else {
                $products = collect();
            }
        } else {
            $page = 'recommend';

            // ログインユーザーが出品した商品を除外
            $query = Auth::check()
                ? Product::where('user_id', '!=', Auth::id())->orWhereNull('user_id')
                : Product::query();

            // 検索キーワードがある場合
            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $products = $query->latest()->get();
        }

        return view('products.top', [
            'tab' => $page,
            'products' => $products,
            'keyword' => $keyword,
        ]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function confirm(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        // 画像を一時的にセッションに保存
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('temp', 'public');
            session(['temp_image' => $imagePath]);
        }

        return view('products.confirm', ['product' => $validated]);
    }

    public function thanks()
    {
        return view('products.thanks');
    }

    public function toggleFavorite(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // お気に入り登録済みかチェック
        $favorite = $product->favorites()->where('user_id', $user->id)->first();

        if ($favorite) {
            // 既にお気に入り登録されている場合は削除
            $favorite->delete();
        } else {
            // お気に入り登録
            $product->favorites()->create([
                'user_id' => $user->id,
            ]);
        }

        return redirect()->back();
    }

    public function storeComment(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // 既存のコメントデータを取得
        $commentData = $product->comment_data ?? [];

        // 新しいコメントを追加
        $commentData[] = [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'comment' => $request->comment,
            'created_at' => now()->toDateTimeString(),
        ];

        // JSONカラムに保存
        $product->comment_data = $commentData;
        $product->save();

        return redirect()->route('item.show', $product)->with('success', 'コメントを投稿しました。');
    }
}
