<?php

/**
 * 2025/11/10
 * DINH BINH QUAN
 */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit; // 処理を終了させる 
}
require_once __DIR__ . "/../php/get_shop.php";
require_once __DIR__ . "/../php/sort.php";
require_once __DIR__ . "/../../helpers/db_helper.php";

$shops_data = [];
$keyword = filter_input(INPUT_POST, "keyword") ?? '';
$area    = filter_input(INPUT_POST, "area") ?? '';

try {
    if ($keyword === "" && $area === "") $shops_data = get_all_shops();
    else $shops_data = get_shop_by_keyword_and_area($keyword, $area);
} catch (\PDOException $e) {
    echo $e->getMessage();
    // エラーが発生した場合の処理
    header("location:index.php");
    exit;
}
$currentPage = 1;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- MapLibre GL JS: -->
    <link
        href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css"
        rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.js"></script>


    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


    <!-- SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>ラブマップ</title>
</head>

<body>

    <?php include_once __DIR__ . "/partials/navigation.php"; ?>
    <main class="w-full h-screen grid grid-cols-1 gap-x-3 gap-y-0 md:ml-16 lg:grid-cols-[1fr_0.5fr]">

        <!-- メイン -->
        <div class="search_main-container overflow-y-auto py-5 px-6 sm:px-10 md:px-12">
            <!-- 検索フィルター -->
            <div>
                <h2 id="search_keyword" class="font-bold text-3xl/10"></h2>
                <p class="text-stone-600">大切な人と過ごす、特別な場所を見つけよう。</p>

                <!-- 検索バー -->
                <form
                    id="result_form"
                    class="w-full flex flex-col gap-6 my-6 p-6 bg-white shadow-sm rounded-2xl border border-gray-200">
                    <!-- キーワード -->
                    <div class="w-full flex flex-col gap-3">
                        <label for="result_keyword" class="text-sm text-pink-600 font-medium">
                            キーワード検索
                        </label>

                        <div class="flex items-center gap-3 border border-pink-300 p-3 rounded-xl bg-gray-50 focus-within:border-pink-400 transition">
                            <i class="ri-search-line text-gray-500"></i>
                            <input
                                name="keyword"
                                id="result_keyword"
                                type="text"
                                placeholder="キーワードを入力してください"
                                class="w-full bg-transparent outline-none text-gray-700" />
                        </div>

                        <!-- エリア検索 -->
                        <label for="area_input" class="text-sm text-pink-600 font-medium">
                            エリア
                        </label>
                        <div id="area_wrapper" class="relative w-full">
                            <input
                                name="area"
                                id="area_input"
                                type="text"
                                placeholder="場所を入力"
                                class="p-3 border border-pink-300 rounded-xl w-full focus:outline-none focus:border-pink-400 bg-gray-50 transition" />
                        </div>
                    </div>

                    <!-- ボタン -->
                    <div class="flex justify-end w-full gap-4 pt-2">
                        <button
                            id="clear-btn"
                            type="reset"
                            class="py-2 px-5 bg-gray-100 rounded-xl text-gray-500 hover:bg-gray-200 transition">
                            リセット
                        </button>

                        <button
                            id="result_keyword-btn"
                            type="submit"
                            class="py-2 px-5 bg-pink-600 rounded-xl text-white font-semibold hover:bg-pink-500 transition">
                            検索
                        </button>
                    </div>
                </form>


                <!-- 順番表示 -->
                <div class="flex justify-between items-center w-full my-6">

                    <!-- ソートボタン -->
                    <div class="flex gap-5 items-center">
                        <div class="relative inline-flex border border-gray-300 rounded-2xl overflow-hidden text-lg font-medium text-gray-700">
                            <!-- 選択スライダー -->
                            <div id="sort_slider" class="absolute h-full w-1/2 bg-pink-200 rounded-2xl z-0 transition-transform duration-300 ease-in-out"></div>

                            <!-- ボタン -->
                            <div id="sort_price" class="relative z-10 cursor-pointer px-5 py-2 text-center transition-all duration-200 ease-in-out hover:text-pink-600">
                                価格順
                            </div>
                            <div id="sort_rating" class="relative z-10 cursor-pointer px-5 py-2 text-center transition-all duration-200 ease-in-out hover:text-pink-600">
                                評価順
                            </div>
                        </div>
                    </div>

                    <!-- 昇順/降順トグル -->
                    <div id="sort_toggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-2xl text-gray-700 cursor-pointer select-none hover:bg-gray-100 transition">
                        昇順
                        <span class="sort_arrow text-pink-500 text-xl transition-transform duration-300">
                            <i class="ri-arrow-down-circle-fill"></i>
                        </span>
                    </div>
                </div>

            </div>

            <!-- 検索結果表示 -->
            <div id="shop_container" class="border-t border-stone-200 pt-5 space-y-8">
            </div>

        </div>



        <!-- マップ表示 -->
        <div class="search_map hidden lg:block w-full h-full">
            <div id="map_container" class="w-full h-full">
            </div>
        </div>
    </main>



    <script type="module">
        import {
            initMap
        } from "./js/renderMap.js";
        import {
            renderShop
        } from "./js/renderShop.js";
        const shopsData = <?= json_encode($shops_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>

        const sessionStorage = window.sessionStorage;
        sessionStorage.setItem("shopsData", JSON.stringify(shopsData));
        // Init map lần đầu
        initMap(shopsData);
        renderShop(JSON.parse(sessionStorage.getItem("shopsData")));
    </script>
    <script type="module" src="./js/formHandle.js"></script>
    <script type="module" src="./js/areaHandle.js"></script>
    <script type="module" src="./js/sort_handle.js"></script>

</body>

</html>