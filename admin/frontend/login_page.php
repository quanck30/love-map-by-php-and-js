<?php
session_start();

require_once __DIR__ . "/../../helpers/db_helper.php";
require_once __DIR__ . "/../../helpers/extra_helper.php";
require_once __DIR__ . "/../../helpers/config.php";

if (isset($_SESSION["admin"])) {
    header("location:" . SITE_URL . "/admin/frontend/all_shop.php");
    exit;
}
$user_error = "";
if (isset($_SESSION["user_error"])) {
    $user_error = $_SESSION["user_error"];
    unset($_SESSION["user_error"]);
}
$pass_error = "";
if (isset($_SESSION["pass_error"])) {
    $pass_error = $_SESSION["pass_error"];
    unset($_SESSION["pass_error"]);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ラブマップ管理ページ</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>

    <section class="bg-pink-50 h-screen flex justify-center items-center">
        <div class="w-full lg:w-4/12 px-4 mx-auto pt-6">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-pink-100 border-0">
                <div class="rounded-t mb-0 px-6 py-6">
                    <div class="text-center mb-3">
                        <h6 class="text-pink-500 text-sm font-bold">
                            ラブマップ管理ページへようこそ！
                        </h6>
                    </div>

                    <hr class="mt-6 border-b-1 border-pink-300">
                </div>
                <div class="flex-auto px-4 lg:px-10 py-10 pt-0">

                    <form method="POST" action="../backend/login_action.php">
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-pink-600 text-xs font-bold mb-2" for="username">ユーザ名</label>
                            <input type="text" class="border-0 px-3 py-3 placeholder-pink-300 text-pink-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Username" name="username" required>
                        </div>
                        <?php if ($user_error) : ?>
                            <p class="text-red-500 text-sm mt-1"><?= $user_error ?></p>
                            <?php $user_error = ""; ?>
                        <?php else: ?>
                            <p class="text-sm mt-1">&nbsp;</p>
                        <?php endif ?>
                        <div class="relative w-full mb-3">
                            <label class="block uppercase text-pink-600 text-xs font-bold mb-2" for="password">パスワード</label><input type="password" class="border-0 px-3 py-3 placeholder-pink-300 text-pink-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" placeholder="Password" name="password" required>
                        </div>
                        <?php if ($pass_error) : ?>
                            <p class="text-red-500 text-sm mt-1"><?= $pass_error ?></p>
                            <?php $pass_error = ""; ?>
                        <?php else: ?>
                            <p class="text-sm mt-1">&nbsp;</p>
                        <?php endif ?>
                        <div class="text-center mt-6">
                            <button class="cursor-pointer bg-pink-800 text-white active:bg-pink-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full ease-linear transition-all duration-150" type="submit"> ログイン </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<script>
</script>
</body>

</html>