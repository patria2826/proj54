<?php
require __DIR__ . '\__connect_db.php';

$sql = "SELECT * FROM categories";

$stmt = $pdo->query($sql);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

//header('Content-Type: text/plain');
//print_r($result);

include __DIR__ . '/__html_head.php'; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php foreach ($result as $m_items):
                    if ($m_items['parent_sid'] == 0):
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <?= $m_items['name'] ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php foreach ($result as $sub):
                                if($sub['parent_sid']==$m_items['sid']):
                                ?>
                                <a class="dropdown-item" href="#"><?= $sub['name'] ?></a>
                                <?php endif;
                                endforeach; ?>
                            </div>
                        </li>
                    <?php endif;
                endforeach;
                ?>
            </ul>

        </div>
    </div>
</nav>
<?php include __DIR__ . '/__html_foot.php' ?>
