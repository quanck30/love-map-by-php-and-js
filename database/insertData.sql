USE love_map_DB;

INSERT INTO areas (name, slug) VALUES
('梅田','umeda'),
('難波','namba'),
('心斎橋','shinsaibashi'),
('天王寺','tennoji'),
('新大阪','shin_osaka'),
('本町','honmachi'),
('福島','fukushima'),
('京橋','kyobashi'),
('阿倍野','abeno'),
('北浜','kitahama'),
('天満橋','temmabashi'),
('大正','taisho'),
('西中島南方','nishinakajima'),
('中津','nakatsu'),
('日本橋','nipponbashi'),
('玉造','tamatsukuri'),
('淀屋橋','yodoyabashi'),
('堺','sakai'),
('豊中','toyonaka'),
('吹田','suita'),
('東大阪','higashiosaka'),
('八尾','yao'),
('枚方','hirakata'),
('高槻','takatsuki'),
('茨木','ibaraki'),
('岸和田','kishiwada'),
('泉佐野','izumisano');

INSERT INTO categories (name) VALUES
('たこ焼き'),
('喫茶店'),
('串カツ'),
('カフェ'),
('ラーメン'),
('ベーカリー'),
('とんかつ'),
('ワインバー'),
('レストラン'),
('海鮮丼'),
('パティスリー'),
('珈琲豆販売'),
('お好み焼き');




INSERT INTO roles(role_name) 
VALUES ("admin"),("manager"),("user");


COMMIT;