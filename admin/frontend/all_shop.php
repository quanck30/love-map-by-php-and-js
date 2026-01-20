<?php

/**
 * 2025/12/02
 */

session_start();
require_once __DIR__ . "/../../helpers/config.php";
require_once __DIR__ . "/../../helpers/db_helper.php";
require_once __DIR__ . "/../../helpers/extra_helper.php";
require_once __DIR__ . "/sibar.php";
$error = [];
if (!empty($_SESSION["id_error"])) {
    $error[] = $_SESSION["id_error"];
    unset($_SESSION["id_error"]);
}
if (!empty($_SESSION["delete_error"])) {
    $error[] = $_SESSION["delete_error"];
    unset($_SESSION["delete_error"]);
}
$shops = [];
try {
    $shops = get_all_shops();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ショップ一覧</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen flex flex-col">


    <!-- =======================
        Main Content
    ======================== -->
    <div class="max-w-9xl ml-20 py-8 px-4 sm:px-6 lg:px-8 my-20 lg:my-10 ">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-8 text-center border-b pb-4 border-b-pink-500">
            ショップ一覧表示
        </h1>

        <div class="flex flex-col space-y-6">
            <?php foreach ($shops as $shop) : ?>
                <div class="bg-white rounded-xl overflow-hidden transform transition duration-300 shadow">
                    <div class="md:flex">
                        <!-- Ảnh -->
                        <div class="md:shrink-0 p-4 flex items-center justify-center">
                            <img class="h-40 w-40 object-cover rounded-lg border-2 border-gray-100"
                                src="<?= htmlspecialchars($shop['image']) ?>"
                                alt="Ảnh <?= htmlspecialchars($shop['name']) ?>"
                                onerror="this.onerror=null; this.src='https://placehold.co/160x160/6b7280/ffffff?text=No+Image';">
                        </div>

                        <!-- Thông tin -->
                        <div class="p-6 flex-grow">
                            <div class="uppercase tracking-wide text-lg text-primary font-bold mb-2">
                                <?= htmlspecialchars($shop['name']) ?> - <?= htmlspecialchars($shop['category']) ?>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 flex items-center gap-2">
                                <i class="ri-map-pin-2-fill"></i>
                                アドレス: <?= htmlspecialchars($shop['address']) ?>
                            </p>
                            <div class="mt-2 flex flex-wrap items-center space-x-4 gap-2">
                                <div class="flex items-center text-xl font-extrabold text-secondary">
                                    <span class="text-sm font-medium text-gray-500 mr-1">価格:</span>
                                    <span>¥<?= number_format($shop['price']) ?></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 gap-2">
                                    <i class="ri-user-fill"></i>
                                    作成者: <?= htmlspecialchars($shop['user_name']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Nút Sửa/Xóa -->
                        <div class="p-6 flex items-center justify-end space-x-4 border-t md:border-t-0 md:border-l border-gray-100 bg-gray-50 ">
                            <a href="./edit_shop.php?id=<?= $shop['id'] ?>" class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-primary bg-indigo-500 transition duration-150 hover:bg-indigo-700 gap-1">
                                <i class="ri-edit-2-fill"></i>
                                編集
                            </a>
                            <button class="delete_btn flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-danger bg-red-500 transition duration-150 hover:bg-red-700 gap-1" data-id="<?= $shop["id"] ?>">
                                <i class="ri-delete-bin-6-line"></i>
                                削除
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        const errors = <?= json_encode($error) ?>;
        if (errors.length > 0) {
            alert(errors[0]);
        }
    </script>
    <script src="./js/delete_action.js"></script>
</body>


</html>