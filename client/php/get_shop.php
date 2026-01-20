<?php

/**
 * 2025/11/25
 */

require_once __DIR__ . "/../../helpers/db_helper.php";

function getShops(string $keyword, string $area): array
{
    $shop_data = [];
    try {
        $pdo = getPDO();
        $select = "SELECT 
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
                WHERE 1=1 ";

        $param = [];

        if (!empty($keyword)) {
            $select .= "AND(shops.name LIKE :key1 OR shops.description LIKE :key2)";
            $param["key1"] = "%$keyword%";
            $param["key2"] = "%$keyword%";
        }

        if (!empty($area)) {
            $select .= "AND shops.address LIKE :area";
            $param["area"] = "%$area%";
        }
        $select .= " GROUP BY shops.id ORDER BY price ASC ;";
        $stmt = $pdo->prepare($select);
        $stmt->execute($param);
        while ($row = $stmt->fetch()) {
            $shop_data[] = $row;
        }
        return $shop_data;
    } catch (Exception $e) {
        echo $e->getMessage();
        return [];
    }
};
