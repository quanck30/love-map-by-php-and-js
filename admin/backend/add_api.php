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

/**
 * 力画面へリダイレクトするヘルパー関数
 * 不正アクセスやエラー時に使用
 */
function backToAddPage()
{
    header("location:" . SITE_URL . "/admin/frontend/add_shop.php");
    exit;
}

// POST以外にアクセは無効
if ($_SERVER["REQUEST_METHOD"] !== "POST") backToAddPage();

// 作成者ID（管理者のみ）
$create_id = $_SESSION["admin"]["id"];

// POSTデータの取得
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
    $_SESSION["image_error"] = "ファイルアップが失敗しました";
    backToAddPage();
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
$shop_image = $up_file_result["result"] ?: "https://placehold.co/300x200?text=" . $name;

// DBに入れる店舗データをまとめる
$shop = [
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
if (insert_shop($shop)) {
    // 成功時
    header("location:" . SITE_URL . "/admin/frontend/add_succes.php");
    exit;
} else {
    // 登録失敗：エラーメッセージを保存して入力画面へ戻す
    $_SESSION["insert_error"] = "保存は失敗した！";
    backToAddPage();
}
