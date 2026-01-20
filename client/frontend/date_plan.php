<?php
include_once __DIR__ . "/../../helpers/db_helper.php";
$shop_id = intval($_GET["shop_id"]);
try {
    $shop_detail = get_shop_by_id_for_oder($shop_id);
} catch (Exception $e) {
    echo $e->getMessage();
};

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <title>自分自身のプラン</title>
</head>

<body>
    <!-- Navigation -->
    <?php include_once __DIR__ . "/partials/navigation.php"; ?>

    <!-- Main -->
    <main class="md:pl-20">
        <form action="confirm.php" method="POST">
            <div class="max-w-5xl h-full mx-4 lg:mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 my-10 lg:grid-cols-[1fr_0.5fr]">

                <div class="">
                    <!-- 個人情報入力 -->
                    <div>
                        <h1 class="font-bold text-xl mb-4">連絡先情報</h1>
                        <p class="text-sm text-gray-500 mb-6">個人情報を正しくご記入ください。予約詳細はメールで送信されます</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1" for="firstName">名</label>
                                <input class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="firstName" name="firstName" type="text" placeholder="例：山田" required>
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1" for="lastName">姓</label>
                                <input class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="lastName" name="lastName" type="text" placeholder="例：健太" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium text-gray-700 mb-1" for="mailAddress">メールアドレス</label>
                            <input class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="mailAddress" name="mailAddress" type="email" value="" placeholder="例：〇〇〇〇@gmail.com" required>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium text-gray-700 mb-1" for="telephone">電話番号</label>
                            <input class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="telephone" name="telephone" type="text" value="" pattern="\d{3}-\d{4}-\d{4}" placeholder="例：〇〇〇-〇〇〇〇-〇〇〇〇" required>
                        </div>
                    </div>

                    <!-- 予約情報入力 -->
                    <div class="mt-10">
                        <h1 class="font-bold text-xl mb-4">予約</h1>
                        <p class="text-sm text-gray-500 mb-6">予約情報を正しくご記入ください。予約詳細はメールで送信されます</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-gray-700 mb-1" for="day">予約日</label>
                                <input class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="day" type="date" name="reserve_date" required>
                            </div>
                            <div>
                                <label class="block font-medium text-gray-700 mb-1" for="time">予約時間</label>
                                <select class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="time" name="reserve_time" required>
                                    <option value="">時間を選択</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="19:00">19:00</option>
                                    <option value="20:00">20:00</option>
                                    <option value="21:00">21:00</option>
                                    <option value="22:00">22:00</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium text-gray-700 mb-1" for="planOption">備考・リクエスト</label>
                            <textarea class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="planOption" name="note" type="text" value="" placeholder="例：記念日など"></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="block font-medium text-gray-700 mb-1" for="purpose">デート目的</label>
                            <textarea class="w-full rounded-lg p-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-400" id="purpose" name="purpose" type="email" value="" placeholder="例："></textarea>
                        </div>
                    </div>
                </div>

                <!-- 予約確認 -->
                <div>
                    <div>
                        <h1>ご予約</h1>
                        <div class="flex justify-between items-center border-b border-gray-200">
                            <div>
                                <h1 class="font-medium text-gray-700"><?= htmlspecialchars($shop_detail["name"]) ?></h1>
                                <p class="text-sm text-gray-500">2025年1月1日</p>
                                <p class="text-sm text-gray-500">18:00</p>
                                <p class="font-bold text-pink-600 mt-1" id="price" data-price="<?= htmlentities($shop_detail["price"]) ?>">￥ <?= htmlentities($shop_detail["price"]) ?> 円/人</p>
                            </div>
                            <div class="flex justify-center items-center space-x-1">
                                <button type="button" id="btnDecrease" class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-300 text-gray-600 cursor-pointer hover:bg-gray-200">-</button>
                                <span class="px-2 font-medium" id="quantity">2</span>
                                <button type="button" id="btnIncrease" class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-300 text-gray-600 cursor-pointer hover:bg-gray-200">+</button>
                            </div>
                        </div>
                        <div class="space-y-2 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>小計</span>
                                <span>￥ <span id="subtotal"></span>円</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600">
                                <span>税（10%）</span>
                                <span>￥ <span id="tax"></span>円</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center pt-4 font-bold text-gray-800 text-lg">
                                <span>合計</span>
                                <span>￥ <span id="total_price"></span>円</span>
                            </div>
                        </div>
                        <input type="hidden" name="shop_id" value="<?= $shop_id ?>">
                        <input type="hidden" name="quantity" id="input_quantity">
                        <input type="hidden" name="subtotal" id="input_subtotal">
                        <input type="hidden" name="tax" id="input_tax">
                        <input type="hidden" name="total" id="input_total">
                        <button type="submit" class="w-full mt-6 py-3 bg-pink-600 text-white font-semibold rounded-lg hover:bg-pink-700 transition duration-150 cursor-pointer">続行</button>

                    </div>
                </div>
            </div>
        </form>
    </main>
    <script src="./js/planPage_2.js">
    </script>
</body>

</html>