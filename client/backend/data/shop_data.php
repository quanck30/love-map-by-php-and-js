<?php

/**
 * 2025/10/29
 * DINH BINH QUAN
 */
require_once __DIR__ . "/../../../database/insertData.php";
require_once __DIR__ . "/../../php/geocoding.php";

// 仮想ショップデータ
$shops_data = [
  [
    'name' => 'たこ焼き道頓堀 くくる',
    'price' => 700,
    'address' => '大阪市中央区道頓堀1-7-21',
    'image' => 'https://placehold.co/300x200?text=Takoyaki+Kukuru',
    'description' => '道頓堀で有名なたこ焼き店。大きくて熱々のたこ焼きに新鮮なタコと濃厚なソース。',
    'area_id' => 2,
    'category_id' => 1,
    'created_id' => 1,
  ],
  [
    'name' => '喫茶店 バニラ',
    'price' => 550,
    'address' => '大阪市北区梅田3-1-1',
    'image' => 'https://placehold.co/300x200?text=Kissaten+Vanilla',
    'description' => 'クラシックな雰囲気の喫茶店。アイスコーヒーやタマゴサンドが人気。',
    'area_id' => 1,
    'category_id' => 2,
    'created_id' => 1,
  ],
  [
    'name' => '串カツ だるま 新世界総本店',
    'price' => 1000,
    'address' => '大阪市浪速区恵美須東1-6-8',
    'image' => 'https://placehold.co/300x200?text=Kushikatsu+Daruma',
    'description' => '大阪名物の串カツ。通天閣近くで一度だけソースに浸すルールが有名。',
    'area_id' => 4,
    'category_id' => 3,
    'created_id' => 1,
  ],
  [
    'name' => '北浜レトロ',
    'price' => 1500,
    'address' => '大阪市中央区北浜1-1-10',
    'image' => 'https://placehold.co/300x200?text=Kitahama+Retro+Cafe',
    'description' => '歴史ある建物のカフェ。スコーンやアフタヌーンティーが人気。',
    'area_id' => 10,
    'category_id' => 4,
    'created_id' => 1,
  ],
  [
    'name' => 'ラーメン 横綱 新大阪店',
    'price' => 950,
    'address' => '大阪市淀川区宮原3-4-20',
    'image' => 'https://placehold.co/300x200?text=Ramen+Yokozuna',
    'description' => '豚骨スープが特徴のラーメン店。新大阪駅近くで旅行者にも便利。',
    'area_id' => 5,
    'category_id' => 5,
    'created_id' => 1,
  ],
  [
    'name' => '大阪ベーカリー 福島店',
    'price' => 800,
    'address' => '大阪市福島区福島5-6-10',
    'image' => 'https://placehold.co/300x200?text=Bakery+Fukushima',
    'description' => '自家製パンが人気のベーカリー。クロワッサンやケーキが豊富。',
    'area_id' => 7,
    'category_id' => 6,
    'created_id' => 1,
  ],
  [
    'name' => 'とんかつ 豚太郎',
    'price' => 1800,
    'address' => '大阪市西区江戸堀1-2-5',
    'image' => 'https://placehold.co/300x200?text=Tonkatsu+Butatarou',
    'description' => '選りすぐりの豚肉を使ったとんかつ専門店。ランチセットもお手頃。',
    'area_id' => 6,
    'category_id' => 7,
    'created_id' => 1,
  ],
  [
    'name' => '京橋ワイン酒場',
    'price' => 2500,
    'address' => '大阪市都島区東野田町2-3-8',
    'image' => 'https://placehold.co/300x200?text=Kyobashi+Wine+Bar',
    'description' => '京橋駅近くの小さなワインバー。タパスとワインが楽しめる。',
    'area_id' => 8,
    'category_id' => 8,
    'created_id' => 1,
  ],
  [
    'name' => 'あべのハルカス ダイニング',
    'price' => 3000,
    'address' => '大阪市阿倍野区阿倍野筋1-1-43',
    'image' => 'https://placehold.co/300x200?text=Abeno+Harukas+Dining',
    'description' => 'あべのハルカス内の高級ダイニング。和食から洋食まで多彩な料理。',
    'area_id' => 9,
    'category_id' => 9,
    'created_id' => 1,
  ],
  [
    'name' => '淀屋橋 海鮮丼',
    'price' => 1300,
    'address' => '大阪市中央区今橋2-4-12',
    'image' => 'https://placehold.co/300x200?text=Yodoyabashi+Kaisen',
    'description' => '新鮮な海鮮丼のランチが人気。市場直送の素材を使用。',
    'area_id' => 17,
    'category_id' => 10,
    'created_id' => 1,
  ],
  [
    'name' => '堺市 ケーキ屋 ポピー',
    'price' => 1200,
    'address' => '堺市堺区中瓦町2-3-5',
    'image' => 'https://placehold.co/300x200?text=Sakai+Patisserie',
    'description' => '堺市の人気ケーキ店。誕生日ケーキやムースが好評。',
    'area_id' => 18,
    'category_id' => 11,
    'created_id' => 1,
  ],
  [
    'name' => '豊中市 珈琲工房',
    'price' => 500,
    'address' => '豊中市本町1-2-6',
    'image' => 'https://placehold.co/300x200?text=Toyonaka+Coffee',
    'description' => '自家焙煎コーヒー専門店。豆の購入やカフェ利用も可能。',
    'area_id' => 19,
    'category_id' => 12,
    'created_id' => 1,
  ],
  [
    'name' => '東大阪 お好み焼き さくら',
    'price' => 1100,
    'address' => '東大阪市足代2-5-9',
    'image' => 'https://placehold.co/300x200?text=Okonomiyaki+Sakura',
    'description' => '地元で人気のお好み焼き店。自分で焼いて楽しめる。',
    'area_id' => 21,
    'category_id' => 13,
    'created_id' => 1,
  ],
];


$count = 0;

foreach ($shops_data as $shop) {
  try {
    $shop_with_latlon = getLatAndLon($shop);
    if ($shop_with_latlon === null) {
      throw new Exception("Geocoding失敗");
    }

    $result = insertShop($shop_with_latlon);
    if ($result) {
      $count++;
      echo "成功: " . $shop["name"] . "<br>";
    } else {
      echo "失敗: " . $shop["name"] . "<br>";
    }
  } catch (Exception $e) {
    echo "エラー: " . $shop["name"] . " => " . $e->getMessage() . "<br>";
  }
}

echo $count;
