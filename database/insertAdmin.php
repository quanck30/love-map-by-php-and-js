<?php
require_once __DIR__ . "/../helpers/db_helper.php";
$username = "admin";
$password = "quan1907";
$role_id = 1;
$pdo = getPDO();

try {
    $pdo->beginTransaction();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(user_name, password, role_id) VALUES (:username, :password, :role_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->bindValue(":password", $hashedPassword, PDO::PARAM_STR);
    $stmt->bindValue(":role_id", $role_id, PDO::PARAM_STR);

    $stmt->execute();

    $pdo->commit();
    echo "Insert Admin 成功!";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "エラー: " . $e->getMessage();
}
