<?php

/**
 * 2025/12/03
 */
if (!isset($_SESSION["admin"])) {
    header("Location: ../../admin/frontend/login_page.php");
    exit;
}

$activePage = basename($_SERVER['PHP_SELF']);
?>
<aside class="fixed lg:left-0 top-0 w-full bg-white lg:w-25 lg:h-full lg:border-r border-pink-300 shadow-lg flex lg:flex-col lg:justify-start justify-center items-center py-5 lg:py-10 space-y-6">
    <!-- Home / Client search page -->
    <a href="../../client/frontend/search_page.php" class="text-pink-900 font-bold text-lg px-4 lg:py-2 mx-2 my-0 lg:my-2 lg:mx-0 rounded transition hover:bg-pink-500">
        <i class="ri-home-heart-fill text-2xl"></i>
    </a>

    <!-- All Shops -->
    <a href="./all_shop.php" class="text-pink-900 font-bold text-lg px-4 lg:py-2 rounded transition mx-2 my-0 lg:my-2 lg:mx-0
        <?= $activePage === 'all_shop.php' ? 'bg-pink-500 text-white' : 'hover:text-white hover:bg-pink-500' ?>">
        一覧
    </a>

    <!-- Add Shop -->
    <a href="./add_shop.php" class="text-pink-900 font-bold text-lg px-4 lg:py-2 rounded transition
        <?= $activePage === 'add_shop.php' ? 'bg-pink-500 text-white' : 'hover:text-white hover:bg-pink-500' ?>">
        登録
    </a>

</aside>