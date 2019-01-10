<?php require __DIR__. '/__connect_db.php';
$page_name='cart';

if(! empty($_SESSION['cart'])){
    // 購物車裡要有商品

    // 取得關聯式陣列的 key
    $keys = array_keys($_SESSION['cart']);

    //$sql = "SELECT * FROM `products` WHERE `sid` IN ('18', '20', '7')";
    $sql = sprintf("SELECT * FROM `products` WHERE `sid` IN ('%s')",
        implode("','", $keys)
    );

    $stmt = $pdo->query($sql);

    $cart_data = []; // 存放商品資料和數量
    while($r = $stmt->fetch(PDO::FETCH_ASSOC)){

        // 把商品的數量, 設定給該項目的 qty 屬性
        $r['qty'] = $_SESSION['cart'][$r['sid']];

        $cart_data[$r['sid']] = $r;
    }

//    header('Content-Type: text/plain');
//    // 依照用戶加入的順序去取得商品資料
//    foreach($keys as $k){
//        print_r( $cart_data[$k] );
//    }


}


?>
<?php include __DIR__. '/__html_head.php' ?>
<?php include __DIR__. '/__navbar.php' ?>
<div class="container">
    <?php if(empty($cart_data)): ?>
        <div class="alert alert-danger" role="alert">
            購物車裡沒有商品
        </div>
    <?php else: ?>

        <table class="table table-striped table-bordered">
            <thead>
            <tr class="table-success">
                <th scope="col"><i class="fas fa-trash-alt"></i></th>
                <th scope="col">封面</th>
                <th scope="col">商品名稱</th>
                <th scope="col">單價</th>
                <th scope="col">數量</th>
                <th scope="col">小計</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;

            foreach($keys as $k){
                $i = $cart_data[$k];
                $total += $i['qty'] * $i['price'];
                ?>
                <tr class="product-item" data-sid="<?= $i['sid'] ?>">
                    <td><a href="javascript: remove_item(<?= $i['sid'] ?>)"><i class="fas fa-trash-alt"></i></a></td>
                    <td><img src="./imgs/small/<?= $i['book_id'] ?>.jpg" alt=""></td>
                    <td><?= $i['bookname'] ?></td>
                    <td class="price product-price" data-price="<?= $i['price'] ?>"></td>
                    <td data-qty="<?= $i['qty'] ?>">
                        <select class="form-control item-qty" style="display: inline-block; width: 50%">
                            <?php for($k=1;$k<=20;$k++){ ?>
                                <option value="<?=$k?>" <?= $k==$i['qty'] ? 'selected="selected"' : '' ?>>
                                    <?=$k?>
                                </option>
                            <?php } ?>
                        </select>

                    </td>
                    <td class="price sub-total" data-price="<?= $i['price']*$i['qty'] ?>"></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <div class="alert alert-primary" role="alert">
            總計: <span id="total-price" class="price" data-price="<?= $total ?>"></span>
        </div>
    <?php endif; ?>
    <script>
        var badge_pill = $('.badge-pill');

        function cart_count(obj){
            var k, items=0;
            for(k in obj){
                items += obj[k];
            }
            badge_pill.text(items);
        }

        $.get('cart_sess.php', function(data){
            cart_count(data);
        }, 'json');
    </script>
    <script>
        var dallorCommas = function(n){
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        };

        var remove_item = function(sid){
            $.get('add_to_cart.php', {sid:sid}, function(data){
                location.reload();
            }, 'json');
        };

        var updateAllPrice = function() {
            $('.price').each(function () {
                var p = $(this).attr('data-price');
                p = dallorCommas(p);
                $(this).text('$ ' + p + '元');
            });
        };
        updateAllPrice();

        var calTotalPrice = function(){
            var t_price = $('#total-price');
            var total = 0;

            $('.product-item').each(function(){
                var price = $(this).find('.product-price').attr('data-price');
                var qty = $(this).find('select').val();

                total += price*qty;
            });

            t_price.attr('data-price', total);
        };

        $('.item-qty').on('change', function(){
           var item_tr = $(this).closest('tr');
           var sid = item_tr.attr('data-sid');
           var qty = $(this).val();
           var price = item_tr.find('.product-price').attr('data-price');

            $.get('cart_sess.php', {sid:sid, qty:qty}, function(data){
                //location.reload(); // 刷新頁面
                cart_count(data);
                item_tr.find('.sub-total').attr('data-price', price*qty); // 變更該項目的小計
                calTotalPrice(); // 計算總共多少錢

                updateAllPrice(); // 依據資料變更呈現
            }, 'json');

        });
    </script>
</div>
<?php include __DIR__. '/__html_foot.php' ?>