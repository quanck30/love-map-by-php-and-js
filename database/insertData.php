<?php

/**
 * 2025/11/25
 */

require_once __DIR__ . "/../helpers/db_helper.php";

/**
 * 店舗情報をデータベースに登録する関数
 * @param array $data 登録する店舗情報（連想配列）
 *     - name        : 店舗名
 *     - price       : 価格
 *     - address     : 住所
 *     - type        : 種類
 *     - image       : 画像パス
 *     - description : 説明文
 *     - area        : エリア
 *     - lon         : 経度
 *     - lat         : 緯度
 *
 * @return bool 登録が成功した場合は true、失敗した場合は false を返す
 */
function insertShop(array $data): bool
{

    try {
        $pdo = getPDO();
        $checkQuery = "SELECT COUNT(*) FROM shops WHERE name = :name OR address = :address";
        $checkShop = $pdo->prepare($checkQuery);
        $checkShop->execute(["name" => $data["name"], "address" => $data["address"]]);
        $count = $checkShop->fetchColumn();
        if ($count > 0) throw new Exception("店舗が既に存在している", 1);


        if (!$pdo->inTransaction()) $pdo->beginTransaction();

        $insert = "INSERT INTO 
                shops(  
                    name,
                    price,
                    address,
                    image,
                    description,
                    lon,
                    lat,
                    area_id,
                    category_id,
                    created_id
                ) VALUES (
                    :name, 
                    :price,
                    :address,
                    :image,
                    :description,
                    :lon,
                    :lat,
                    :area_id,
                    :category_id,
                    :created_id
                )";
        $stmt = $pdo->prepare($insert);
        $stmt->execute([
            "name" => $data["name"],
            "price" => $data["price"],
            "address" => $data["address"],
            "image" => $data["image"],
            "description" => $data["description"],
            "lon" => $data["lon"],
            "lat" => $data["lat"],
            "area_id" => $data["area_id"],
            "category_id" => $data["category_id"],
            "created_id" => $data["created_id"],
        ]);
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        if ($pdo->inTransaction())  $pdo->rollBack();
        throw new Exception("エラー：" . $e->getMessage());
        return false;
    }
};


function insertArea(array $area): bool
{

    try {
        $pdo = getPDO();
        $checkQuery = "SELECT COUNT(*) FROM areas WHERE name = :name";
        $check = $pdo->prepare($checkQuery);
        $check->execute(["name" => $area["name"]]);
        $count = $check->fetchColumn();
        if ($count > 0) throw new Exception("エリアが既に存在している", 1);

        if (!$pdo->inTransaction()) $pdo->beginTransaction();
        $insert = "INSERT INTO areas(name,slug) VALUES (:name, :slug)";
        $stmt = $pdo->prepare($insert);
        $stmt->execute([
            "name" => $area["name"],
            "slug" => $area["slug"],
        ]);
        $pdo->commit();
        return true;
    } catch (\Throwable $th) {
        return false;
    }
}


function insertRating(int $shop_id, int $star): bool
{

    try {
        $pdo = getPDO();
        if (!$pdo->inTransaction()) $pdo->beginTransaction();
        $insert = "INSERT INTO rating(shop_id, stars) VALUES (:shop_id, :stars)";
        $stmt = $pdo->prepare($insert);
        $stmt->execute([
            ":shop_id" => $shop_id,
            ":stars" => $star
        ]);
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        throw new Exception("エラー：　" . $e->getMessage());
        return false;
    }
}
