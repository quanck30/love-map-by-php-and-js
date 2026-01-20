<?php

/**
 * 2025/11/11
 * DINH BINH QUAN
 */
/**
 * 指定されたエリアが店舗住所に含まれているかを確認する関数
 * 
 * @param string $shopArea 店舗の住所
 * @param string $area 検索対象のエリア
 * @return bool 含まれていればtrue、そうでなければfalse
 */
function areaHandle($shopArea, $area)
{
    if (empty($area)) return true;  // エリアが未入力の場合は全て対象とする

    // 住所にエリア文字列が含まれているかを判定
    return mb_strpos($shopArea, $area) !== false;
}

/**
 * 店舗情報の中にキーワードが含まれているかを確認する関数
 * 
 * @param array $shop 店舗情報（連想配列）
 * @param string $key 検索キーワード
 * @return bool 含まれていればtrue、そうでなければfalse
 */
function keywordHandle($shop, $key)
{
    // キーワードが未入力なら全て対象とする
    if (empty($key)) return true;

    // 店舗データの各項目をループして、部分一致をチェック
    foreach ($shop as $value) {
        if (mb_strpos((string)$value, (string)$key) !== false) return true;
    }
    return false;
}

/**
 * 検索条件（エリア・キーワード）に一致する店舗を抽出し、価格順にソートして返す関数
 * 
 * @param array $shops_data 全店舗データ
 * @param string $keyword 検索キーワード
 * @param string $area 検索エリア
 * @return array 条件に合致した店舗情報（緯度経度付き）
 */
function getSearchResult($shops_data, $keyword, $area)
{

    // キーワードもエリアも未指定なら、全店舗を価格昇順で返す
    if (empty($keyword) && empty($area)) {
        $result = $shops_data;
        usort($result, fn($a, $b) => $a["price"] <=> $b["price"]);
        return  $result;
    }

    $result = [];
    // 各店舗データを走査し、条件に一致する店舗だけを$resultに追加
    foreach ($shops_data as $shop) {
        $isExistsArea = areaHandle($shop["address"], $area);
        $isExistsKeyword = keywordHandle($shop, $keyword);
        if ($isExistsArea && $isExistsKeyword) $result[] = $shop;
    }

    return $result;
};
