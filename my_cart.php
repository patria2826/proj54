<?php
require __DIR__ . '/__connect_db.php';
$page_name = 'cart';
$page_title = '購物車';
include __DIR__ . '/__html_head.php';
include __DIR__ . '/__navbar.php';


if (!empty($_SESSION['cart'])) {
    $keys = array_keys($_SESSION['cart']);
    $sql = sprintf("SELECT * FROM `products` WHERE `sid` IN ('%s')", implode("','", $keys));

//    echo $sql;

    $stmt = $pdo->query($sql);

    $cart_data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['qty'] = $_SESSION['cart'][$row['sid']]; //自定義$row['qty']，將session中的qty塞進來
        $cart_data[$row['sid']] = $row; //用table中的sid(PK)作為$cart_data的key，對應的資料內容為table中該列的內容
    }

//    檢查用
//    foreach ($keys as $k) {
//        print_r($cart_data[$k]);
//    }

}

?>

    <div class="container cart_area">
        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">商品名稱</th>
                    <th scope="col">商品圖片</th>
                    <th scope="col">價格</th>
                    <th scope="col">數量</th>
                    <th scope="col">小計</th>
                    <th scope="col">移除</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach ($keys as $k) {
                    $item = $cart_data[$k];
                    $total += $item['price'] * $item['qty'];
                    ?>
                    <tr class="text-center product_item" data-sid="<?= $item['sid'] ?>"
                        id="product_<?= $item['sid'] ?>">
                        <th scope="row"><?= $item['bookname'] ?></th>
                        <td><img src="./imgs/small/<?= $item['book_id'] ?>.jpg" alt=""></td>
                        <td><span class="item_price" data-price="<?= $item['price'] ?>"><?= $item['price'] ?></span>元
                        </td>
                        <td>
                            <select class="item_qty">
                                <?php for ($i = 1; $i < 100; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $item['qty'] ? 'selected="selected"' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td><span class="subTotal price" data-price="<?= $item['price'] * $item['qty'] ?>"></span>元</td>
                        <td><a href="javascript: remove_item(<?= $item['sid'] ?>)"><i class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div class="alert alert-success total_area" role="alert">
                總計： <span class="price" id="total_price" data-price="<?= $total ?>"></span> 元
            </div>
        <?php else: ?>
            <div class="alert alert-secondary" role="alert">
                購物車中沒有商品 :)
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['user']) && count($_SESSION['cart'])!=0): ?>
            <a href="buy.php" class="btn btn-info">購買</a>
        <?php else: ?>
            <?php if (count($_SESSION['cart'])!=0): ?>
                <a href="login.php" class="btn btn-warning">請先登入</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script>
        function remove_item(sid) {
            $.get('cart_sess.php', {sid: sid}, function (data) {
                // location.reload();

                $(`#product_${sid}`).remove();
                cart_count(data);
                calTotal();
                upateAllPrice();

                if ($('.product_item').length == 0) {
                    $('.cart_area').append(`<div class="alert alert-secondary" role="alert">
                購物車中沒有商品 :)
            </div>`);
                    $('table').remove();
                    $('.total_area').remove();

                }
            }, 'json');
        }

        var dallorCommas = function (n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        };

        var upateAllPrice = function () {
            $('.price').each(function () {
                $(this).text(dallorCommas(parseInt($(this).data('price'))));
            })
        }
        upateAllPrice();

        var calTotal = function () {
            var t_price = $('#total_price');
            var total = 0;
            $('.product_item').each(function () {
                var price = $(this).find('.item_price').data('price');
                var qty = $(this).find('.item_qty').val();

                total += price * qty;
            });
            t_price.data('price', total);
        };

        $('.item_qty').on('change', function () {
            var this_item = $(this).closest('.product_item');
            var sid = this_item.data('sid');
            var qty = $(this).val();
            var price = this_item.find('.item_price').data('price');
            $.get('cart_sess.php', {sid: sid, qty: qty}, function (data) {
                this_item.find('.subTotal').data('price', price * qty);
                this_item.find('.subTotal').attr('data-price', price * qty);

                cart_count(data);
                calTotal();

                upateAllPrice();
            }, 'json');
        })

    </script>

<?php include __DIR__ . '/__html_foot.php';