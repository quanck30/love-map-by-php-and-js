/**
    DINH BINH QUAN
    2025/11/11
*/

-- 使用するDBに移動する
USE love_map_DB;

DROP TABLE IF EXISTS rating;
DROP TABLE IF EXISTS shops;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS areas;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
-- テーブルの作成を行う
/* ROLES */
CREATE TABLE IF NOT EXISTS roles(
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE
);
/* USERS */
CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255),
    password VARCHAR(255),
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);
/* AREA */
CREATE TABLE IF NOT EXISTS areas(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50)
);
/* CATEGORY */
CREATE TABLE IF NOT EXISTS categories(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);

/* SHOP */
CREATE TABLE IF NOT EXISTS shops(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    price INT,
    address VARCHAR(255),
    image VARCHAR(255),
    description TEXT,
    lon VARCHAR(255),
    lat VARCHAR(255),
    area_id INT NULL,
    category_id INT NULL,
    created_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (area_id) REFERENCES areas(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (created_id) REFERENCES users(id)
);

/* SHOP RATING */
CREATE TABLE IF NOT EXISTS rating(
    id INT AUTO_INCREMENT PRIMARY KEY,
    shop_id INT NOT NULL,
    -- user_id INT NOT NULL,
    stars TINYINT NOT NULL CHECK(stars BETWEEN 1 AND 5),
    -- comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE
    -- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


/* INDEX */ 
CREATE INDEX idx_shops_price ON shops(price);
CREATE INDEX idx_shops_area ON shops(area_id);
CREATE INDEX idx_shops_category ON shops(category_id);
CREATE INDEX idx_shops_area_category ON shops(area_id, category_id, price);





source insertData.sql;

COMMIT;