<?php
$page_name = 'buy_history';

require __DIR__ . '/__connect_db.php';

if (!isset($_SESSION['user']['id'])) {
    header("Location: index.php");
    exit;
}