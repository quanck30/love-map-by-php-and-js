/**
    DINH BINH QUAN
    2025/11/11
*/
-- ユーザの作成
-- DROP USER IF EXISTS  'quan';
CREATE USER IF NOT EXISTS quan IDENTIFIED BY '1907';


-- データベースの作成
-- DROP DATABASE IF EXISTS love_map_DB;
CREATE DATABASE IF NOT EXISTS love_map_DB;


-- ユーザ権限の設定
-- DBを操作するすべての権限を与える
GRANT ALL ON love_map_DB.* TO quan;