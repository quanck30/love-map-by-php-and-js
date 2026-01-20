<?php
require_once __DIR__ . "/../../helpers/db_helper.php";

$pdo = getPDO();
function getAllShops(PDO $pdo)
{
    $shops = [];
    try {
        $query = "SELECT 
        shops.id,
        name,
        price,
        address,
        type,   
        image,
        description,
        area,
        ROUND(AVG(rating.stars),1) AS rating,
        COUNT(rating.stars) AS reviews,
        lon,
        lat
        FROM shops
        LEFT JOIN rating ON shops.id = rating.shop_id
        GROUP BY shops.id
        ORDER BY price ASC;
    ";

        $stmt = $pdo->query($query);
        while ($row = $stmt->fetch()) {
            $shops[] = $row;
        };
        return $shops;
    } catch (PDOException $e) {
        // エラーが発生した場合の処理
        die("データの取得に失敗しました：" . htmlspecialchars($e->getMessage()));
    }
}
