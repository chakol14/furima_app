<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => '腕時計',
                'brand' => 'COACHTech',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
                'image_url' => 'images/watch.jpg',
            ],
            [
                'name' => 'HDD',
                'brand' => 'StoragePro',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れ無し',
                'image_url' => 'images/HDD.jpg',
            ],
            // 以下テンプレ：コピーして追加可能
            [
                'name' => '玉ねぎ3束',
                'brand' => 'FreshFarm',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束セット',
                'condition' => 'やや傷や汚れあり',
                'image_url' => 'images/onion.jpg',
            ],
            [
                'name' => '革靴',
                'brand' => 'LeatherWorks',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'condition' => '状態がわるい',
                'image_url' => 'images/shoes.jpg',
            ],
            [
                'name' => 'ノートPC',
                'brand' => 'TechPlus',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'condition' => '目立った傷や汚れなし',
                'image_url' => 'images/laptop.jpg',
            ],
            [
                'name' => 'マイク',
                'brand' => 'SoundMaster',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'condition' => 'やや傷や汚れあり',
                'image_url' => 'images/microphone.jpg',
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => 'UrbanStyle',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'image_url' => 'images/bag.jpg',
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'DailyUse',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
                'image_url' => 'images/tumbler.jpg',
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'BrewArt',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'condition' => '良好',
                'image_url' => 'images/coffee_mill.jpg',
            ],
            [
                'name' => 'メイクセット',
                'brand' => 'BeautyBox',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷は無し',
                'image_url' => 'images/makeup.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
