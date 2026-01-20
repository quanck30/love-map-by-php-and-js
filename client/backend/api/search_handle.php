<?php

/**
 * 2025/11/11
 * DINH BINH QUAN
 * 検索画面から送信された検索キーワードとエリアを受け取り、
 * 
 */
require_once __DIR__ . "/../../../helpers/db_helper.php";

// ヘッダー設定：Content-Typeをapplication/jsonに設定
header("Content-Type:application/json");
$shops_data = $_SESSION["shops_data"] ?? [];
require_once __DIR__ . "/../../php/get_shop.php";
require_once __DIR__ . "/../../php/geocoding.php";
require_once __DIR__ . "/../../php/sort.php";

$keyword = $_POST['keyword'] ?? '';
$area    = $_POST['area'] ?? '';

try {
    $result = get_shop_by_keyword_and_area($keyword, $area);
    echo json_encode($result, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
} catch (\Throwable $e) {
    // エラーログ
    error_log($e->getMessage());
}
