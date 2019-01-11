<?php
$page_name = 'buy_history';

require __DIR__ . '/__connect_db.php';

if (!isset($_SESSION['user']['id'])) {
    header("Location: index.php");
    exit;
}

$order_sql = "SELECT `sid`, `member_sid`, `amount`, `order_date` 
          FROM `orders` 
          WHERE `member_sid`=? AND `order_date`>'2018-07-01'";
$order_stmt = $pdo->prepare($order_sql);
$order_stmt->execute([
        $_SESSION['user']['id'],
]);

$orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);


$order_sid = [];
foreach ($orders as $each_order){
    $order_sid []=$each_order['sid'];
}


$detail_sql = sprintf("SELECT order_details.*, products.bookname, products.author 
          FROM `order_details` 
          LEFT JOIN `products` ON order_details.product_sid=products.sid
          WHERE order_details.order_sid in (%s)", implode(',', $order_sid));
$detail_stmt = $pdo->query($detail_sql);
$orders_details = $detail_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include __DIR__ . '/__html_head.php' ?>
<?php include __DIR__ . '/__navbar.php' ?>

<div class="container">
    <pre>
    <?php
    //
    print_r($orders);
    print_r($orders_details);
    ?>

</pre>

</div>

<?php include __DIR__ . '/__html_foot.php' ?>
