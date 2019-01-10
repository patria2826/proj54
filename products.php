<?php
require __DIR__ . '/__connect_db.php';
$page_name = 'products';
$page_title = '商品';

if (empty($_SERVER['HTTP_REFERER'])) {
    $come_from = './';
} else {
    $come_from = $_SERVER['HTTP_REFERER'];
}

$parameters = [];

$cates = isset($_GET['cate']) ? intval($_GET['cate']) : 0;
if (!empty($cates)) {
    $parameters['cate'] = $cates;
}


//設定第一層分類
$cate_sql = "SELECT * FROM categories WHERE `parent_sid` = 0";
$cate_stmt = $pdo->query($cate_sql);

//篩選每個分類內的商品
$where = " WHERE 1";
if (!empty($cates)) {
    $where .= " AND `category_sid`=$cates";
}
$total = $pdo->query("SELECT COUNT(1) FROM products $where")->fetch(PDO::FETCH_NUM)[0];

//設定每頁幾個產品
$_GET['p'] = isset($_GET['p']) ? intval($_GET['p']) : 1;
$pages = isset($_GET['p']) ? intval($_GET['p']) : 1;
$per_page = 4;
$t_pages = ceil($total / $per_page);

$p_count_sql = sprintf("SELECT * FROM products $where LIMIT %s ,%s", ($pages - 1) * $per_page, $per_page);
$p_count_stmt = $pdo->query($p_count_sql);

print_r($p_count_stmt);
print_r($_GET);
?>
<?php include __DIR__ . '/__html_head.php' ?>
<?php include __DIR__ . '/__navbar.php' ?>
    <style>
        .btn:focus {
            outline: none;
        }
    </style>
    <div class="container d-flex mt-3">
    <div class="col-md-3">
        <div class="btn-group-vertical" style="width: 100%">
            <a class="btn btn-secondary <?php echo empty($cates) ? 'active' : ''; ?>" href="./products.php?p=1">所有商品</a>
            <?php while ($c_result = $cate_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <a class="btn btn-secondary <?php echo ($c_result['sid'] == $_GET['cate']) ? 'active' : ''; ?>"
                   href="./products.php?cate=<?= $c_result['sid'] ?>&p=1"><?= $c_result['name'] ?></a>
            <?php endwhile; ?>
        </div>
    </div>
    <div class="col-md-9 d-flex flex-md-wrap">
        <nav class="w-100" aria-label="...">
            <ul class="pagination">
                <li class="page-item <?= ($pages == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?php
                    $parameters['p'] = $pages - 1;
                    echo http_build_query($parameters) ?>" tabindex="-1">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $t_pages; $i++):
                    $parameters['p'] = $i;
                    ?>
                    <li class="page-item <?= ($_GET['p'] == $i) ? 'active' : '' ?>"><a class="page-link"
                                                                                       href="?<?= http_build_query($parameters) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($pages == $t_pages) ? 'disabled' : '' ?>">
                    <a class="page-link"
                       href="?<?php
                       $parameters['p'] = $pages + 1;
                       echo http_build_query($parameters) ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php if ($p_count_stmt->rowCount() < 1) {
            echo '沒有東東哦 :-(';
        } ?>

        <?php while ($row = $p_count_stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="card mx-1 mb-2 product_item" data-sid="<?= $row['sid'] ?>" style="width: 22%;">
                <img class="card-img-top"
                     src="imgs/small/<?= $row['book_id'] ?>.jpg"
                     alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?= $row['bookname']; ?></h5>
                    <p class="card-text"><i class="fas fa-user-tie"></i> <?= $row['author'] ?></p>

                    <select>
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>

                    <a href="#" class="btn btn-primary add_to_cart_btn"><i class="fas fa-cart-plus"></i></a>
                </div>
            </div>
        <?php endwhile; ?>

    </div>
    <script>
        $('.add_to_cart_btn').click(function () {
            var sid = $(this).closest('.product_item').data('sid');
            var qty = $(this).closest('.product_item').find('select').val();
            console.log(`sid:${sid};;;;qty:${qty}`);
            $.get('cart_add_sess.php', {sid: sid, qty: qty}, function (dataa) {
                // alert("thx");
                cart_count(dataa);
            },'json');
        });
    </script>
    <script>
        var badge_pill = $('.badge-pill');

        function cart_count(obj){
            var k, items=0;
            for(k in obj){
                items += obj[k];
            }
            badge_pill.text(items);
        }

        $.get('cart_add_sess.php', function(data){
            cart_count(data);
        }, 'json');
    </script>
<?php include __DIR__ . '/__html_foot.php' ?>