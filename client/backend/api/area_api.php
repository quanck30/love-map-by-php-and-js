<?php

/**
 * 2025/11/25
 */
require_once __DIR__ . "/../../../helpers/db_helper.php";
try {
    $areas = get_areas();
} catch (\Throwable $th) {
    $areas = [];
} finally {
    echo json_encode($areas, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}
