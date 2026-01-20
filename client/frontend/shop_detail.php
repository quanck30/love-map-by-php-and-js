<?php
include_once __DIR__ . "/../../helpers/db_helper.php";
$shop_id = intval($_GET["shop_id"]);
try {
    $shop_detail = get_shop_by_id($shop_id);
} catch (Exception $e) {
    echo $e->getMessage();
}
$rating = isset($shop_detail["rating"]) ? $shop_detail["rating"] : 0;
$reviews = isset($shop_detail["reviews"]) ? $shop_detail["reviews"] : 0;
$rating_floor = floor($rating);

$shop_stars = [
    "5" => $shop_detail["stars"]["5"] ?? 0,
    "4" => $shop_detail["stars"]["4"] ?? 0,
    "3" => $shop_detail["stars"]["3"] ?? 0,
    "2" => $shop_detail["stars"]["2"] ?? 0,
    "1" => $shop_detail["stars"]["1"] ?? 0
];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/loading_animation.css">
    <!-- Leaflet+ OpenStreetMap -->
    <link
        href="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.css"
        rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@3.6.1/dist/maplibre-gl.js"></script>

    <!-- Animate.css  -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title><?= htmlspecialchars($shop_detail["name"]) ?></title>
</head>

<body>

    <?php include_once __DIR__ . "/partials/navigation.php"; ?>
    <main class="w-full h-screen grid grid-cols-1 gap-x-3 gap-y-0 md:ml-16 lg:grid-cols-[1fr_0.5fr]">
        <!-- Navigation
        <div class="border-b md:border-b-0 md:border-r border-gray-300 pt-4 w-full md:w-20 flex justify-center md:block">
            <div class="flex md:flex-col items-center justify-center text-2xl gap-5 py-3">
                <a href="./index.php"><i class="ri-home-heart-fill"></i></a>
                <a href="./search_page.php"><i class="ri-menu-search-line"></i></a>
                <a href="../../admin/frontend/login_page.php"><i class="ri-user-heart-fill"></i></a>
            </div>
        </div> -->
        <!-- メイン -->
        <div class="search_main-container w-full h-full overflow-y-auto flex flex-col items-center px-3 md:px-6 lg:px-20">
            <div class="w-full max-w-6xl bg-white rounded-xl border border-gray-200">

                <div class="relative">
                    <img
                        src="<?= htmlspecialchars($shop_detail["image"]) ?>"
                        alt="<?= htmlspecialchars($shop_detail["name"]) . "の写真" ?>"
                        class="w-full h-48 sm:h-64 object-cover">
                    <h1 class="absolute bottom-0 left-0 bg-pink-500 text-white text-xl sm:text-3xl font-extrabold px-4 md:px-6 py-2 rounded-tr-xl">
                        <?= htmlspecialchars($shop_detail["name"]) ?>
                    </h1>
                </div>

                <div class="p-6 sm:p-8 space-y-6">

                    <div class="flex flex-wrap items-center justify-between border-b pb-4 border-gray-100 gap-2">
                        <span class="text-xl font-semibold text-pink-600 bg-pink-100 px-4 py-1 rounded-lg">
                            <?= htmlspecialchars($shop_detail["name"]) ?>
                        </span>
                        <div class="flex items-center mt-2 sm:mt-0">
                            <span class="text-2xl font-bold text-gray-800 mr-2"><?= htmlspecialchars($rating) ?></span>
                            <?php
                            for ($i = 1; $i <= 5; $i++):
                            ?>
                                <i class="<?= $i <= $rating_floor ? 'ri-star-fill' : 'ri-star-line'; ?> text-pink-500"></i>
                            <?php endfor; ?>
                            <span class="text-sm text-gray-500 ml-2">(<?= htmlspecialchars($reviews) ?> reviews)</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">説明 </h3>
                        <p class="text-gray-600 italic break-words">
                            <?= htmlspecialchars($shop_detail["description"]) ?>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg gap-5">
                            <i class="ri-map-pin-2-fill "></i>
                            <div>
                                <p class="font-semibold text-sm text-gray-500">アドレス:</p>
                                <p class="text-gray-700"><?= htmlspecialchars($shop_detail["address"]) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg gap-5">
                            <i class="ri-sparkling-2-line"></i>
                            <div>
                                <p class="font-semibold text-sm text-gray-500">価格:</p>
                                <p class="text-gray-700">¥ <?= htmlspecialchars($shop_detail["price"] * 2) . " 円/組" ?></p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg gap-5">
                            <i class="ri-earth-fill"></i>
                            <div>
                                <p class="font-semibold text-sm text-gray-500">エリア</p>
                                <p class="text-gray-700"><?= htmlspecialchars($shop_detail["area"]) ?: " " ?></p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg gap-5">
                            <i class="ri-briefcase-2-fill"></i>
                            <div>
                                <p class="font-semibold text-sm text-gray-500">タイプ:</p>
                                <p class="text-gray-700"><?= htmlspecialchars($shop_detail["category"]) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-6 justify-end">
                        <!-- Denwa button -->
                        <a href="tel:<?= htmlspecialchars($shop_detail["phone"] ?? "") ?>"
                            class="w-full text-center sm:w-auto bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            電話ですぐに予約
                        </a>

                        <!-- Web button -->
                        <a href="<?= "./date_plan.php?shop_id=" . $shop_detail["id"] ?>" target="_blank"
                            class="w-full text-center sm:w-auto bg-orange-400 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            デートプランを追加
                        </a>
                        <!-- Map 表示 -->
                        <button
                            data-shop="<?= htmlspecialchars(json_encode($shop_detail, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>"
                            class="map-btn lg:hidden cursor-pointer w-full text-center sm:w-auto bg-blue-400 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition">
                            地図表示
                        </button>
                    </div>
                </div>

            </div>


            <div class="font-sans bg-white p-4 sm:p-8 max-w-4xl mx-auto">

                <div class="lg:flex lg:space-x-12 space-y-10 lg:space-y-0">
                    <div class="lg:w-1/3 mb-8 lg:mb-0">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">カスタマーレビュー</h2>

                        <div class="flex items-center mb-6">
                            <div class="flex mr-2">
                                <?php
                                for ($i = 1; $i <= 5; $i++):
                                ?>
                                    <i class="<?= $i <= $rating_floor ? 'ri-star-fill' : 'ri-star-line'; ?> text-pink-500"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-sm text-gray-500"><?= htmlspecialchars($reviews) ?> 件のレビューに基づく</span>
                        </div>

                        <div class="space-y-2">
                            <?php foreach ($shop_stars as $star => $value): ?>
                                <?php
                                $percent = $reviews > 0 ? ($value / $reviews) * 100 : 0;
                                $percent = round($percent, 0);
                                ?>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-700 w-6"><?= htmlspecialchars($star) ?></span>
                                    <div class="flex text-pink-500 mr-2">
                                        <i class="ri-star-fill"></i>
                                    </div>
                                    <div class="flex-grow bg-gray-200 rounded-full h-2.5 mr-3">
                                        <div class="bg-pink-500 h-2.5 rounded-full" style="width: <?= htmlspecialchars($percent) ?>%"></div>
                                    </div>
                                    <span class="text-gray-700 font-semibold w-8 text-right"><?= htmlspecialchars($percent) ?>%</span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-base font-semibold text-gray-800 mb-2">感想を共有する</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                このデートスポットを訪れたことがある方は、他のユーザーと感想を共有してください
                            </p>
                            <button class="cmt-btn w-full sm:w-auto px-6 py-2 border border-gray-400 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-150 cursor-pointer">
                                レビューを書く
                            </button>
                        </div>
                    </div>

                    <div class="lg:w-2/3 space-y-8">
                        <div class="border-b border-gray-200 pb-8 last:border-b-0">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-300 mr-3">
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">佐藤 美咲</p>
                                    <div class="flex text-pink-500">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700">
                                理想のデートスポットです。先週末に訪れましたが、雰囲気がとても素敵で、長い時間でも退屈せずに楽しめました。素敵な思い出をたくさん作ることができました。
                            </p>
                        </div>



                        <div class="border-b border-gray-200 pb-8 last:border-b-0">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-300 mr-3">
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">佐藤 美咲</p>
                                    <div class="flex text-pink-500">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700">
                                理想のデートスポットです。先週末に訪れましたが、雰囲気がとても素敵で、長い時間でも退屈せずに楽しめました。素敵な思い出をたくさん作ることができました。
                            </p>
                        </div>
                    </div>
                </div>
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
            renderDetailMap
        } from "./js/renderMap.js";
        const shop = <?= json_encode($shop_detail, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        renderDetailMap(shop);
    </script>
    <script src="./js/shop_detail_map.js"></script>
    <script src="./js/commentHandle.js"></script>
</body>

</html>