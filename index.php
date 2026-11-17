<?php

require('./database.php');

$stmt = $pdo->query('SELECT * FROM books WHERE is_deleted = 0');

// while ($row = $stmt->fetch())
// {
//     echo $row['title'] . "<br>\n";
// }

$search = trim($_GET['search'] ?? '');

$sql = 'SELECT * FROM books WHERE is_deleted = 0';

if ($search !== '') {
    $sql .= ' AND title LIKE ?';
}

$stmt = $pdo->prepare($sql);

if ($search !== '') {
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt->execute();
}

$books = $stmt->fetchAll();

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
        <a href="add-author.php">Lisa autor</a>
    </nav>
</header>

<main class="book-page">

    <h1>Meie raamatud</h1>
    <p>Leia endale järgmine hea lugemine!</p>

    <form action="index.php" method="GET" class="search-form">
        <input
            type="search"
            name="search"
            placeholder="Otsi raamatu pealkirja..."
            value="<?= htmlspecialchars($search) ?>"
        >

        <button type="submit" class="btn btn-primary">Otsi</button>

        <a href="index.php" class="btn btn-secondary">Kõik raamatud</a>
    </form>

    <div class="books-list">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <h2>
                    <a href="book.php?id=<?= $book['id'] ?>">
                        <?= htmlspecialchars($book['title']) ?>
                    </a>
                </h2>

                <p>Hind: <?= number_format((float)$book['price'], 2) ?> €</p>

                <a
                    href="book.php?id=<?= $book['id'] ?>"
                    class="btn btn-primary"
                >
                    Vaata raamatut
                </a>
            </div>
        <?php endforeach; ?>

        <?php if (empty($books)): ?>
            <p>Raamatuid ei leitud.</p>
        <?php endif; ?>
    </div>


</main>

<footer class="site-footer">
    <p>© <?= date('Y') ?> Raamatu pood</p>
</footer>

</body>
</html>