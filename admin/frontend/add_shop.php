<?php
session_start();
require_once __DIR__ . "/../../helpers/db_helper.php";
require_once __DIR__ . "/../../helpers/extra_helper.php";
require_once __DIR__ . "/../../helpers/config.php";
require_once __DIR__ . "/sibar.php";
$areas = [];
$categories = [];
try {
    $areas = get_areas();
    $categories = get_categories();
} catch (\Throwable $th) {
}
if (isset($_SESSION["image_error"])) {
    $image_error = $_SESSION["image_error"];
    unset($_SESSION["image_error"]);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ショップ登録フォーム</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body >


    <!-- =======================
        メイン
    ======================== -->
    <div class="w-full max-w-2xl border border-pink-50 p-8 rounded-lg shadow-lg mx-auto mt-20 lg:mt-10  ">
        <h2 class="text-2xl font-bold text-pink-600 mb-6 text-center">ショップ情報を登録</h2>

        <form action="../backend/add_api.php" method="POST" enctype="multipart/form-data" class="space-y-4">

            <!-- Tên shop -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">ショップ名</label>
                <input type="text" name="name" placeholder="ショップ名を入力" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-pink-400 outline-none">
            </div>

            <!-- Giá -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">価格 (円/人)</label>
                <input type="number" name="price" placeholder="価格を入力" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-pink-400 outline-none">
            </div>

            <!-- Zip code -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">郵便番号</label>
                <input
                    type="text"
                    name="postal_code"
                    placeholder="例: 123-4567"
                    pattern="\d{3}-\d{4}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-pink-400 outline-none">
                <p class="text-xs text-gray-500 mt-1">※ 123-4567 の形式で入力してください</p>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">住所</label>
                <input
                    type="text"
                    name="address"
                    placeholder="住所を入力"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-pink-400 outline-none">
            </div>
            <!-- Area -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">エリア</label>
                <select
                    id="area_id"
                    name="area_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-pink-400 outline-none">
                    <option value="">選択してください</option>
                    <?php foreach ($areas as $area) : ?>
                        <option value="<?= $area["id"] ?>"><?= $area["name"] . "-" . $area["id"] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">ショップタイプ</label>
                <select
                    id="category_id"
                    name="category_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-pink-400 outline-none">
                    <option value="">選択してください</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= htmlspecialchars($category["id"]) ?>"><?= htmlspecialchars($category["name"]) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">画像</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full text-sm text-gray-600 file:bg-pink-200 file:text-pink-700 file:border-none file:rounded file:px-3 file:py-2">
             
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-pink-600 mb-1">説明</label>
                <textarea
                    name="description"
                    rows="4"
                    placeholder="ショップの説明を入力"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-pink-400"></textarea>
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit"
                    class="bg-pink-600 text-white px-6 py-2 rounded font-bold hover:bg-pink-500 transition-colors">
                    登録する
                </button>
            </div>

        </form>
    </div>

    <script>
    </script>

</body>

</html>