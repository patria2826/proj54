<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
};

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
$qty = isset($_GET['qty']) ? intval($_GET['qty']) : 0;

if (!empty($sid)) {
    // 如果 sid 有設定時
    if (empty($qty)) {
        // 移除
        unset($_SESSION['cart'][$sid]);
    } else {
//        update cart
        $_SESSION['cart'][$sid] = $qty;
    }
}

echo json_encode($_SESSION['cart']);