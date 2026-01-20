<?php

/**
 * 2025/10/29
 * DINH BINH QUAN
 */
require_once __DIR__ . "/../../../database/insertData.php";
// 仮想エリアデータ
$areas = [
    ["name" => "梅田", "slug" => "umeda"],
    ["name" => "難波", "slug" => "namba"],
    ["name" => "心斎橋", "slug" => "shinsaibashi"],
    ["name" => "天王寺", "slug" => "tennoji"],
    ["name" => "新大阪", "slug" => "shin_osaka"],
    ["name" => "本町", "slug" => "honmachi"],
    ["name" => "福島", "slug" => "fukushima"],
    ["name" => "京橋", "slug" => "kyobashi"],
    ["name" => "阿倍野", "slug" => "abeno"],
    ["name" => "北浜", "slug" => "kitahama"],
    ["name" => "天満橋", "slug" => "temmabashi"],
    ["name" => "大正", "slug" => "taisho"],
    ["name" => "西中島南方", "slug" => "nishinakajima"],
    ["name" => "中津", "slug" => "nakatsu"],
    ["name" => "日本橋", "slug" => "nipponbashi"],
    ["name" => "玉造", "slug" => "tamatsukuri"],
    ["name" => "淀屋橋", "slug" => "yodoyabashi"],
    ["name" => "堺", "slug" => "sakai"],
    ["name" => "豊中", "slug" => "toyonaka"],
    ["name" => "吹田", "slug" => "suita"],
    ["name" => "東大阪", "slug" => "higashiosaka"],
    ["name" => "八尾", "slug" => "yao"],
    ["name" => "枚方", "slug" => "hirakata"],
    ["name" => "高槻", "slug" => "takatsuki"],
    ["name" => "茨木", "slug" => "ibaraki"],
    ["name" => "岸和田", "slug" => "kishiwada"],
    ["name" => "泉佐野", "slug" => "izumisano"],
];

foreach ($areas as $area) {
    $result = insertArea($area);
    echo $result;
}
