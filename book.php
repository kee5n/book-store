<?php

require('./database.php');

$id = $_GET['id'];
$isSuccess = $_GET['update'];

// $stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
// $stmt->execute(['id' => $id ]);
// $book = $stmt->fetch();

$stmt = $pdo->prepare('
    SELECT
        books.*,
        authors.first_name,
        authors.last_name
    FROM books
    LEFT JOIN book_authors
        ON books.id = book_authors.book_id
    LEFT JOIN authors
        ON book_authors.author_id = authors.id
    WHERE books.id = :id
');

$stmt->execute(['id' => $id]);
$book = $stmt->fetch();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> <?= $book['title']; ?> </title>
</head>

<style>
.book-visual {
  position: relative;
  display: inline-block;
}

.book-visual img {
  width: 300px;
  display: block;
}

.book-cover {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  background: rgba(0, 0, 0, 0.7);
  padding: 10px;
  opacity: 0;
  transition: 0.3s;
}

.cover-placeholder {
    padding: 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #f8f0dc;
    background: linear-gradient(145deg, #66513a, #30251d);
    border: 7px double #c5a36e;
}

.book-visual:hover .book-cover {
  opacity: 1;
}
</style>
<body>

    <?php if ( $isSuccess == 'success') { ?>

        <span>Muudatus on salvestatud</span>

    <?php } ?>

    <header class="site-header">
        <a href="index.php" class="logo">Raamatu pood</a>

        <nav>
            <a href="index.php">Avaleht</a>
            <a href="index.php">Raamatud</a>
        </nav>
    </header>

    <main class="book-page">

        <a href="index.php" class="back-link"> < </a>
    
        <section class="book-details">

            <div class="book-visual">
                <img src="<?= $book['cover_path'] ?>">
                <div class="book-cover cover-placeholder">  
                    <span class="cover-decoration">Raamat</span>
                    <h2><?= $book['title'] ?></h2>
                    <p><?= trim($book['first_name'] . ' ' . $book['last_name']) ?></p>
                </div>
            </div>

            <div class="book-info">

                <span class="eyebrow" ></span>
                <h1><?= $book['title'] ?></h1>
                <p class="author">Autor: <?= $book['first_name'] . ' ' . $book['last_name'] ?></p>

                <div class="price">
                    <?= number_format((float)$book['price'], 2, ',', ' ') ?> €
                </div>

                <div class="book-meta">
                    <div class="meta-item">
                        <span class="meta-label">Lehekülgi</span>
                        <strong><?= $book['pages'] ?></strong>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Kood</span>
                        <strong><?= $book['id'] ?></strong>
                    </div>
                </div>

                <div class="summary">
                        <h2>Raamatust lähemalt</h2>
                        <p><?= $book['summary'] ?></p>
                </div>

                <div class="book-action">
                    <a href="edit.php?id=<?= $book['id'] ?>" class="btn btn-primary">Muuda</a>
                    <a href="index.php" class="btn btn-secondary">Tagasi</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>© <?= date('Y') ?> Raamaturiiul · Iga raamat peidab endas uut maailma.</p>
    </footer>
</body>
</html>