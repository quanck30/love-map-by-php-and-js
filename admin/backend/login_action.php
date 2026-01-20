<?php
session_start();

require_once __DIR__ . "/../../helpers/db_helper.php";
require_once __DIR__ . "/../../helpers/extra_helper.php";
require_once __DIR__ . "/../../helpers/config.php";
function backToLogin()
{
    header("location:" . SITE_URL . "/admin/frontend/login_page.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    backToLogin();
}

$username = "";
$password = "";
$error = "";

$username = get_post("username") ?: "";
$password = get_post("password") ?: "";
if (!$username || !$password) backToLogin();

if (!($admin = select_admin($username))) {
    $user_error = "ユーザー名が正しくありません";
    $_SESSION["user_error"] = $user_error;
    backToLogin();
}
$admin = select_admin($username);
if (!select_password($admin, $password)) {
    $pass_error = "パスワードが正しくありません";
    $_SESSION["pass_error"] = $pass_error;
    backToLogin();
}
session_regenerate_id(true);
$_SESSION["admin"] = $admin;
header("location:" . SITE_URL . "/admin/frontend/all_shop.php");
