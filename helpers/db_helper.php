<?php

/**
 * 2025/11/12
 * DINH BINH QUAN
 * データベース接続
 * 他のファイルでも使用可能
 */


/**
 * データベース接続用の PDO インスタンスを取得する
 * 同一リクエスト内では同じ PDO を使い回す（Singleton的）
 * 接続情報は内部で管理
 * @return PDO
 */
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo !== null) return $pdo;
    //  MySQLへの接続情報
    $host = "localhost";
    $dbname = "love_map_DB";
    $user = "quan";
    $pass = "1907";

    try {
        // PDO オブジェクトを作成（オプション付き）
        // PDO::ATTR_ERRMODE => 例外を投げるモード
        // PDO::ATTR_EMULATE_PREPARES => 本物のプリペアドステートメントを使用
        // PDO::ATTR_DEFAULT_FETCH_MODE => デフォルトで連想配列で取得
        $pdo =  new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $pdo;
    } catch (PDOException $e) {
        // 【die() とは？】
        // プログラムを終了させて、メッセージを画面に表示します

        // 【htmlspecialchars($e->getMessage()) とは？】
        // $e->getMessage() でエラーメッセージを取得
        // htmlspecialchars() は特殊文字をエスケープ（< > & などを安全な文字に変換）
        // XSS（クロスサイトスクリプティング）攻撃を防ぐためのセキュリティ対策です
        die("データベースに接続できませんでした。エラー：" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
}

/**
 * 管理者ユーザー情報を取得する
 *
 * @param string $username ユーザー名
 * @return array|false ユーザー情報（存在しない場合は false）
 */
function select_admin(string $username)
{
    try {
        $pdo = getPDO();
        $sql = "SELECT USER.id, USER.user_name, R.role_name
            FROM users USER 
            JOIN roles R ON USER.role_id = R.role_id 
            WHERE user_name = :user_name LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":user_name", $username, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        return $admin ?: false;
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

/**
 * パスワードを検証する
 *
 * @param array $user ユーザー情報
 * @param string $password 入力されたパスワード
 * @return bool パスワードが正しければ true
 */
function select_password(array $user, string $password): bool
{
    try {
        $pdo = getPDO();
        $sql = "SELECT U.user_name, U.password FROM users U WHERE U.user_name = :user";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":user", $user["user_name"], PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row) return false;
        return password_verify($password, $row["password"]);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

/**
 * 店舗一覧を取得する
 *
 * @param int $currentPage ページネーション用オフセット（-1 の場合は全件）
 * @return array 店舗データ配列
 */
function get_all_shops(int $currentPage = -1): array
{
    $data = [];
    try {
        $pdo = getPDO();
        $select = "SELECT 
                        s.id,
                        s.name,
                        s.price,
                        s.address,
                        s.image,
                        s.description,
                        s.lon,
                        s.lat,
                        s.created_at,
                        u.user_name,
                        ROUND(AVG(rating.stars),1) AS rating,
                        COUNT(rating.stars) AS reviews,
                        c.name AS category, 
                        a.name AS area
                        FROM shops s 
                        INNER JOIN users u ON s.created_id = u.id
                        LEFT JOIN rating ON s.id = rating.shop_id
                        LEFT JOIN categories c ON s.category_id = c.id
                        LEFT JOIN areas a ON s.area_id = a.id
                        GROUP BY s.id
                        ORDER BY s.price ASC
                        LIMIT 20";
        if ($currentPage !== -1) {
            $offset = intval($currentPage);
            $select .= " OFFSET $offset";
        }
        $stmt = $pdo->prepare($select);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $data[] = $row;
        };
    } catch (Exception $e) {
        throw new Exception("エラー：" . $e->getMessage());
    }
    return $data;
}


/**
 * 店舗IDから店舗詳細情報を取得する
 *
 * ・評価の平均値、レビュー数、星別集計を含む
 *
 * @param int $shop_id 店舗ID
 * @return array 店舗詳細情報（存在しない場合は空配列）
 */
function get_shop_by_id(int $shop_id): array
{
    $shop = [];
    try {
        $pdo = getPDO();
        $query = "SELECT 
                    s.id,
                    s.name,
                    s.price,
                    s.address,
                    s.image,
                    s.description,
                    s.lon,
                    s.lat,
                    s.area_id,
                    s.category_id,
                    s.created_at,
                    ROUND(AVG(r.stars),1) AS rating,
                    COUNT(r.stars) AS reviews,
                    JSON_OBJECT(
                                '5', SUM(CASE WHEN r.stars = 5 THEN 1 ELSE 0 END),
                                '4', SUM(CASE WHEN r.stars = 4 THEN 1 ELSE 0 END),
                                '3', SUM(CASE WHEN r.stars = 3 THEN 1 ELSE 0 END),
                                '2', SUM(CASE WHEN r.stars = 2 THEN 1 ELSE 0 END),
                                '1', SUM(CASE WHEN r.stars = 1 THEN 1 ELSE 0 END)
                    ) AS stars,
                    c.name AS category, 
                    a.name AS area
                    FROM shops s 
                    LEFT JOIN rating r ON s.id = r.shop_id
                    LEFT JOIN categories c ON s.category_id = c.id
                    LEFT JOIN areas a ON s.area_id = a.id
                    WHERE s.id = :shop_id
                    GROUP BY s.id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->execute();
        $shop = $stmt->fetch();
        if (!empty($shop)) $shop["stars"] = json_decode($shop["stars"], true);
        return $shop ?: [];
    } catch (Exception $e) {
        throw new Exception("エラー：　" . $e->getMessage());
        return $shop;
    }
}

/**
 * 店舗IDから店舗詳細情報を取得する
 *
 * ・評価の平均値、レビュー数、星別集計を含む
 *
 * @param int $shop_id 店舗ID
 * @return array 店舗詳細情報（存在しない場合は空配列）
 */
function get_shop_by_id_for_oder(int $shop_id): array
{
    $shop = [];
    try {
        $pdo = getPDO();
        $query = "SELECT 
                    s.name,
                    s.price,
                    s.address,
                    s.description
                    FROM shops s 
                    WHERE s.id = :shop_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':shop_id', $shop_id, PDO::PARAM_INT);
        $stmt->execute();
        $shop = $stmt->fetch();
        return $shop ?: [];
    } catch (Exception $e) {
        throw new Exception("エラー：　" . $e->getMessage());
        return $shop;
    }
}
/**
 * キーワードとエリアで店舗を検索する
 *
 * @param string $keyword 検索キーワード
 * @param string $area エリア名
 * @return array 検索結果の店舗一覧
 */
function get_shop_by_keyword_and_area(string $keyword, string $area): array
{
    $shop_data = [];
    try {
        $pdo = getPDO();
        $select = "SELECT 
                        s.id,
                        s.name,
                        s.price,
                        s.address,
                        s.image,
                        s.description,
                        s.lon,
                        s.lat,
                        s.created_at,
                        ROUND(AVG(rating.stars),1) AS rating,
                        COUNT(rating.stars) AS reviews,
                        c.name AS category, 
                        a.name AS area
                    FROM shops s 
                    LEFT JOIN rating ON s.id = rating.shop_id
                    LEFT JOIN categories c ON s.category_id = c.id
                    LEFT JOIN areas a ON s.area_id = a.id
                    WHERE 1=1";

        $param = [];

        if (!empty($keyword)) {
            $select .= " AND(s.name LIKE :key1 OR s.description LIKE :key2)";
            $param["key1"] = "%$keyword%";
            $param["key2"] = "%$keyword%";
        }

        if (!empty($area)) {
            $select .= " AND a.name = :area";
            $param["area"] = $area;
        }
        $select .= " GROUP BY s.id ORDER BY s.price ASC ;";
        $stmt = $pdo->prepare($select);
        $stmt->execute($param);
        while ($row = $stmt->fetch()) {
            $shop_data[] = $row;
        }
        return $shop_data;
    } catch (Exception $e) {
        throw new Exception("エラー: " . $e->getMessage());
        return [];
    }
};
/**
 * エリア一覧を取得する
 *
 * @return array エリア一覧
 */
function get_areas(): array
{
    $areas = [];
    try {
        $pdo = getPDO();
        $select_area = "SELECT * FROM areas";
        $stmt = $pdo->prepare($select_area);
        $stmt->execute();
        while ($area = $stmt->fetch()) {
            $areas[] = $area;
        }
    } catch (Exception $e) {
        throw new Exception("エラー：　" . $e->getMessage());
    }
    return $areas;
}

/**
 * カテゴリ一覧を取得する
 *
 * @return array カテゴリ一覧
 */
function get_categories(): array
{
    $categories = [];
    try {
        $pdo = getPDO();
        $select_category = "SELECT * FROM categories";
        $stmt = $pdo->prepare($select_category);
        $stmt->execute();
        while ($category = $stmt->fetch()) {
            $categories[] = $category;
        }
    } catch (Exception $e) {
        throw new Exception("エラー： " . $e->getMessage());
    }
    return $categories;
}

/**
 * 店舗名がすでに存在するかを確認する
 *
 * ・同じ名前の店舗が登録されているかをチェックする
 * ・重複登録防止用
 *
 * @param string $name 店舗名
 * @return bool 存在する場合 true、存在しない場合 false
 */
function shop_is_exists(string $name): bool
{
    try {
        $pdo = getPDO();
        $select = "SELECT COUNT(*) count FROM shops WHERE name = :name";
        $stmt = $pdo->prepare($select);
        $stmt->execute([":name" => $name]);
        $row = $stmt->fetch();
        return $row["count"] > 0;
    } catch (Exception $e) {
        throw new Exception("エラー: " . $e->getMessage());
    }
    return false;
}

/**
 * 店舗情報を新規登録する
 *
 * @param array $shop 店舗情報
 * @return bool 登録成功時 true、失敗時 false
 */
function insert_shop(array $shop): bool
{
    $pdo = null;
    try {
        if (shop_is_exists($shop["name"])) return false;
        $pdo = getPDO();
        if (!$pdo->inTransaction()) $pdo->beginTransaction();
        $insert = "INSERT INTO shops(
                                     name,
                                     price,
                                     address,
                                     image,
                                     description,
                                     lon,
                                     lat,
                                     area_id,
                                     category_id,
                                     created_id) 
                            VALUES ( :name,
                                     :price,
                                     :address,
                                     :image,
                                     :description,
                                     :lon,
                                     :lat,
                                     :area_id,
                                     :category_id,
                                     :created_id)";
        $stmt = $pdo->prepare($insert);
        $stmt->execute([
            "name" => $shop["name"],
            "price" => $shop["price"],
            "address" => $shop["address"],
            "image" => $shop["image"],
            "description" => $shop["description"],
            "lon" => $shop["lon"],
            "lat" => $shop["lat"],
            "area_id" => $shop["area_id"],
            "category_id" => $shop["category_id"],
            "created_id" => $shop["created_id"]
        ]);
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        if ($pdo->inTransaction())  $pdo->rollBack();
        throw new Exception("エラー： " . $e->getMessage());
    }
    return false;
}


/**
 * 店舗情報を更新する
 *
 * @param array $shop 更新する店舗情報
 * @return bool 更新成功時 true
 */
function update_shop($shop): bool
{
    $pdo = null;
    try {
        $pdo = getPDO();
        if (!$pdo->inTransaction()) $pdo->beginTransaction();
        $update = "UPDATE  shops
                    SET name = :name,
                        price = :price,
                        address = :address,
                        image = :image,
                        description = :description,
                        lon = :lon,
                        lat = :lat,
                        area_id = :area_id,
                        category_id = :category_id,
                        created_id = :created_id
                        
                    WHERE id = :id";
        $stmt = $pdo->prepare($update);
        $stmt->execute([
            "id" => $shop["id"],
            "name" => $shop["name"],
            "price" => $shop["price"],
            "address" => $shop["address"],
            "image" => $shop["image"],
            "description" => $shop["description"],
            "lon" => $shop["lon"],
            "lat" => $shop["lat"],
            "area_id" => $shop["area_id"],
            "category_id" => $shop["category_id"],
            "created_id" => $shop["created_id"]
        ]);
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        if ($pdo->inTransaction())  $pdo->rollBack();
        throw new Exception("エラー： " . $e->getMessage());
    }
    return false;
}

/**
 * 店舗情報を削除する
 *
 * @param int $id 店舗ID
 * @return bool 削除成功時 true
 */
function delete_shop(int $id): bool
{
    try {
        $pdo = getPDO();
        if (!$pdo->inTransaction()) $pdo->beginTransaction();

        $delete = "DELETE FROM shops WHERE shops.id = :id";
        $stmt = $pdo->prepare($delete);
        $stmt->bindValue("id", $id);
        $stmt->execute();
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}
