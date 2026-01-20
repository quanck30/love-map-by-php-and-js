<?php

/**
 * 2025/12/02
 * DINH BINH QUAN
 * 店舗削除処理
 */
session_start();
require_once __DIR__ . "/../../helpers/config.php";
require_once __DIR__ . "/../../helpers/db_helper.php";
$shop_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


if (!is_int($shop_id)) {
    http_response_code(400);
    echo json_encode(["message" => "ID不明！"]);
    exit;
}

try {
    $result = delete_shop($shop_id);
    if ($result) {
        http_response_code(204);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "削除不能"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "サーバーエラー: " . $e->getMessage()]);
}
exit;
