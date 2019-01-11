<?php
$page_name = 'buy';

require __DIR__ . '/__connect_db.php';

if (!isset($_SESSION['user']['id']) || count($_SESSION['cart']) == 0) {
    header("Location: index.php");
    exit;
}



//同購物車頁面
$keys = array_keys($_SESSION['cart']);
$sql = sprintf("SELECT * FROM `products` WHERE `sid` IN ('%s')", implode("','", $keys));
$stmt = $pdo->query($sql);
$cart_data = [];

$total_price = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row['qty'] = $_SESSION['cart'][$row['sid']]; //自定義$row['qty']，將session中的qty塞進來
    $cart_data[$row['sid']] = $row; //用table中的sid(PK)作為$cart_data的key，對應的資料內容為table中該列的內容
    $total_price += $row['qty'] * $row['price'];
}
//同購物車頁面

$orders_sql = "INSERT INTO `orders`(`member_sid`, `amount`, `order_date`) VALUES (?,?, NOW())";
$orders_stmt = $pdo->prepare($orders_sql);
$orders_stmt->execute([
    $_SESSION['user']['id'],
    $total_price
]);

$order_sid = $pdo->lastInsertId();

$order_detail_sql = "INSERT INTO `order_details`(`order_sid`, `product_sid`, `price`, `quantity`) VALUES (?,?,?,?)";
$order_detail_stmt = $pdo->prepare($order_detail_sql);

foreach ($keys as $p_id) {
    $order_detail_stmt->execute([
        $order_sid,
        $p_id,
        $cart_data[$p_id]['price'],
        $cart_data[$p_id]['qty']
    ]);
}

unset($_SESSION['cart']); // 清除購物車內容

include __DIR__ . '/__html_head.php';
include __DIR__ . '/__navbar.php';
?>

    <div class="container">
        <div class="alert alert-success">感謝您的購買</div>
    </div>

<?php include __DIR__ . '/__html_foot.php'; ?>