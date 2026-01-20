<?php

/**
 * 2025/12/02
 */

require_once __DIR__ . "/../../helpers/config.php";
require_once __DIR__ . "/../../helpers/db_helper.php";
require_once __DIR__ . "/../../helpers/extra_helper.php";

$redirectSeconds = 3;
$redirectUrl =  SITE_URL . "/admin/frontend/add_shop.php";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>追加完了</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Tự động redirect -->
    <meta http-equiv="refresh" content="<?= $redirectSeconds ?>;url=<?= $redirectUrl ?>">
</head>

<body class="bg-pink-50 flex items-center justify-center h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center max-w-md w-full">
        <h1 class="text-2xl font-bold text-pink-600 mb-4">追加完了！</h1>
        <p class="text-gray-700 mb-6">店舗が正常に追加されました。</p>
        <p class="text-gray-500"><?= $redirectSeconds ?>秒後にトップページに戻ります…</p>
        <a href="<?= $redirectUrl ?>" class="inline-block mt-4 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">今すぐ戻る</a>
    </div>
</body>

</html>