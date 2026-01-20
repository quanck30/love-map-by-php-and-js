<?php

/**
 * 2025/11/26
 * DINH BINH QUAN
 * 店舗追加処理
 */
session_start();
require_once __DIR__ . "/../../helpers/config.php";
require_once __DIR__ . "/../../helpers/db_helper.php";
require_once __DIR__ . "/../../helpers/extra_helper.php";
require_once __DIR__ . "/../../client/php/geocoding.php";


// POST以外にアクセは無効
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(400);
    echo json_encode(["message" => "リクエストメソッドが無効です"]);
    exit;
}

// 作成者ID（管理者のみ）
$create_id = $_SESSION["admin"]["id"];

// POSTデータの取得
$id = get_post("shop_id") ?: "";
if (!get_shop_by_id($id)) {
    http_response_code(404);
    echo json_encode(["message" => "ショップが未登録"]);
    exit;
}

$name = get_post("name") ?: "";
$price = get_post("price") ?: "";
$address = get_post("address") ?: "";
$area_id = get_post("area_id") ?: "";
$category_id = get_post("category_id") ?: "";
$description = get_post("description") ?: "";

// 画像ファイルを取得
$image = $_FILES["image"] ?: null;

// 画像アップロード失敗
if ($image["error"] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["message" => "ファイルアップが失敗しました"]);
}

// 画像アップロード結果格納用
$up_file_result = [
    "status" => true,
    "message" => null,
    "result" => null,
];

// 保存先バス
$move_file = __DIR__ . "/../../assets/storage/{$image["name"]}";

// アップロード処理
if (move_uploaded_file($image["tmp_name"], $move_file)) {
    $up_file_result["message"] = "ファイルアップに成功しました";
    $up_file_result["result"] = "http://localhost/love-map/assets/storage/{$image["name"]}";
} else {
    $up_file_result["status"] = false;
    $up_file_result["message"] = "ファイルのアップロードに失敗しました";
}

// 成功時：実際のURL  
// 失敗時：プレースホルダー画像  
$shop_image = $up_file_result["result"] ?: null;

// DBに入れる店舗データをまとめる
$shop = [
    "id" => $id,
    "name" => $name,
    "price" => $price,
    "address" => $address,
    "image" => $shop_image,
    "description" => $description,
    "area_id" => intval($area_id),
    "category_id" => intval($category_id),
    "created_id" => intval($create_id)
];
// 住所から緯度経度を取得
$shop = getLatAndLon($shop);


// DB登録処理
if (update_shop($shop)) {
    // 成功時
    http_response_code(200);
    exit;
} else {
    // 変更失敗
    http_response_code(500);
    echo json_encode(["message" => "変更できない"]);
    exit;
}
