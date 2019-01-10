<?php
require __DIR__. '/__connect_db.php';
$page_name='product_list';
$per_page = 4;

$cate = isset($_GET['cate']) ? intval($_GET['cate']) : 0;

// 用戶要看第幾頁
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;



// 取得分類資料
$c_sql = "SELECT * FROM categories WHERE parent_sid=0";
$c_stmt = $pdo->query($c_sql);
$cates = $c_stmt->fetchAll(PDO::FETCH_ASSOC);

$where = ' WHERE 1 ';
if(! empty($cate)){
    $where .= " AND category_sid=$cate ";
}

//if(! empty($other)){
//    $where .= " AND other_sid=$other ";
//}

// 取得總筆數
$t_sql = " SELECT COUNT(1) FROM products $where";
$total_rows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];


// 取得商品資料
$p_sql = "SELECT * FROM products $where ";
$p_stmt = $pdo->query($p_sql);








?>
<?php include __DIR__. '/__html_head.php' ?>
<?php include __DIR__. '/__navbar.php' ?>
    <style>
        .product-img {
            width: 100px;
            height: 135px;
            margin: auto;


        }

    </style>
<div class="container">

    <div class="row">

        <div class="col-md-3">
            <div class="btn-group-vertical" role="group" style="width:100%">
                <a class="btn btn-<?= !empty($cate) ? 'outline-' : '' ?>primary" href="product_list.php">所有商品</a>
                <?php foreach($cates as $item): ?>

                    <?php if($cate==$item['sid']): ?><?php // 如果選到了該分類 ?>
                        <a class="btn btn-primary"
                           href="#"><?= $item['name'] ?></a>
                    <?php else: ?>
                        <a class="btn btn-outline-primary"
                           href="?cate=<?= $item['sid'] ?>"><?= $item['name'] ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="col-md-9 d-flex flex-wrap">
            <div class="row">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#"><?= $total_rows ?></a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-12 d-flex flex-wrap">
            <?php while($row = $p_stmt->fetch(PDO::FETCH_ASSOC)): ?>

                <div class="col-md-3">
                    <div class="card">
                        <img class="product-img" src="./imgs/small/<?= $row['book_id'] ?>.jpg" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['bookname'] ?></h5>
                            <p class="card-text"></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
        </div>


    </div>



</div>
<?php include __DIR__. '/__html_foot.php' ?>