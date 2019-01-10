<?php
//require '__connect_db.php';
require __DIR__. '/__connect_db.php';


$sql = "SELECT * FROM categories";

$stmt = $pdo->query($sql);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dict = []; //

foreach($data as $item){
    $dict[$item['sid']] = $item;
}

$cates = []; // 選單資料

// 選取第一層的項目
foreach($dict as $item){
    if($item['parent_sid']==0){
        $cates[$item['sid']] = $item;
    }
}

// 第二層的項目放在第一層底下
foreach($dict as $item){
    if($item['parent_sid']!=0){ // 只抓第二層的項目

        // 拿到該項目的第一層項目 $cates[$item['parent_sid']]
        // 設定 'children' attribute, 把它當成陣列
        $cates[$item['parent_sid']]['children'][] = $item;
    }
}

//header('Content-Type: text/plain');
//print_r($data);
//echo "\n";
//print_r($dict);
//print_r($cates);



//exit;
?>
<?php include __DIR__. '/__html_head.php' ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <?php foreach($cates as $m_item):
               ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= $m_item['name'] ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php foreach($m_item['children'] as $sub){
                       ?>
                        <a class="dropdown-item" href="#"><?= $sub['name'] ?></a>
                    <?php
                        } ?>
                </div>
            </li>
            <?php
            endforeach; ?>
        </ul>
    </div>
    </div>
</nav>
<?php include __DIR__. '/__html_foot.php' ?>








