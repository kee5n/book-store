<?php

require('./database.php');

$stmt = $pdo->query('SELECT id, title FROM books');
// while ($row = $stmt->fetch())
// {
//     echo $row['title'] . "<br>\n";
// }

?>

<?php
require('./database.php');

$stmt = $pdo->query('SELECT id, title, price FROM books');
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raamatu pood</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header">
    <a href="index.php" class="logo">Raamatu pood</a>

    <nav>
        <a href="index.php">Avaleht</a>
        <a href="index.php">Raamatud</a>
    </nav>
</header>

<main class="book-page">

    <h1>Meie raamatud</h1>
    <p>Leia endale järgmine hea lugemine!</p>

    <div class="book-list">

        <?php while ($book = $stmt->fetch()) { ?>

            <div class="book-card">

                <h2>
                    <a href="book.php?id=<?= $book['id'] ?>">
                        <?= htmlspecialchars($book['title']) ?>
                    </a>
                </h2>

                <p class="price">
                    <?= number_format($book['price'], 2) ?> €
                </p>

                <a href="book.php?id=<?= $book['id'] ?>" class="btn btn-primary">
                    Vaata raamatut
                </a>

            </div>

        <?php } ?>

    </div>

</main>

<footer class="site-footer">
    <p>© <?= date('Y') ?> Raamatu pood</p>
</footer>

</body>
</html>