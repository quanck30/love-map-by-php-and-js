<?php

/**
 * 2025/11/05
 * DINH BINH QUAN
 */


/**
 * ジオコーディング（住所から緯度・経度を取得）
 * 指定された店舗情報（住所を含む配列）をもとに、
 * 緯度（lat）と経度（lon）を取得して配列に追加して返す関数。
 * @param array $shop 店舗情報（少なくとも 'address' キーを含む配列）
 * @return array 緯度・経度を追加した店舗情報配列（取得できなければ null）
 */
function getLatAndLon($shop)

{
    try {
        // 住所をURLエンコードしてAPIリクエストURLを作成
        $url = "https://msearch.gsi.go.jp/address-search/AddressSearch?q=" . urlencode($shop["address"]);

        // APIからレスポンスを取得
        $response = file_get_contents($url);
        if ($response === false) throw new Exception("APIリクエストに失敗しました。");

        // JSONデータを連想配列に変換
        $data = json_decode($response, true);
        if (empty($data)) throw new Exception("住所に一致するデータが見つかりません。");

        /**
         *   データが取得できた場合（空でない場合）
         */
        // GeoJSON形式の座標データを取得（[経度, 緯度]の順番）
        $coordinates = $data[0]['geometry']['coordinates'];

        // 経度・緯度を配列に追加
        $shop["lon"] = $coordinates[0];     //  経度
        $shop["lat"] = $coordinates[1];     //  緯度 
    } catch (Exception $e) {
        // 取得できなかった場合はnullをセット
        $shop["lon"] = null;
        $shop["lat"] = null;
    }
    // 常に$shopを返す（nullを含む場合も）
    return $shop;
}
