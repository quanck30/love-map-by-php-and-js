<?php

/**
 * 2025/12/02
 */

require_once __DIR__ . "/../helpers/db_helper.php";
require_once __DIR__ . "/../helpers/config.php";

function get_areas(): array
{
    $areas = [];
    try {
        $pdo = getPDO();
        $select_areas = "SELECT a.id area_id, a.name area, a.slug  FROM areas";
        $stmt = $pdo->prepare($select_areas);
        $stmt->execute();
        while ($result = $stmt->fetch()) {
            $areas[] = $result;
        }
    } catch (Exception $e) {
        throw new Exception("SELECT文が失敗" . $e->getMessage());
    }
    return $areas;
}
