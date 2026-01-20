<?php

/**
 * 2025/11/25
 */
require_once __DIR__ . "/../../../database/insertData.php";

$ratings = [];

for ($i = 1; $i <= 13; $i++) {

    for ($j = 0; $j < 200; $j++) {
        $ratings[] = [
            "shop_id" => $i,
            "star" => rand(1, 5)
        ];
    }
}

var_dump($ratings);
foreach ($ratings as $shop) {
    try {
        $result = insertRating($shop["shop_id"], $shop["star"]);
        echo $result;
    } catch (Exception $e) {
        echo  $e->getMessage();
    }
}
