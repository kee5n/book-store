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
$books = $stmt->fetchAll();

if (empty($books)) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="et">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Raamatut ei leitud</title>
    </head>
    <body>
        <main class="edit-page">
            <section class="edit-card">
                <h1>Raamatut ei leitud</h1>
                <p class="edit-description">
                    Seda raamatut ei eksisteeri või see pole enam saadaval.
                </p>
                <a href="index.php" class="btn btn-primary">
                    Tagasi raamatute nimekirja
                </a>
            </section>
        </main>
    </body>
    </html>
    <?php
    exit;
}

$book = $books[0];

?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= htmlspecialchars($book['title']) ?> | Raamatu pood</title>
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

        <a href="index.php" class="back-link">← Tagasi raamatute juurde</a>

        <?php if ($isSuccess == 'success') { ?>
            <p class="success-message">✓ Muudatus on salvestatud!</p>
        <?php } ?>

        <section class="book-details">

            <div class="book-visual">
                <img
                    src="<?= htmlspecialchars($book['cover_path']) ?>"
                    alt="<?= htmlspecialchars($book['title']) ?> kaanepilt"
                >
            </div>

            <div class="book-info">

                <span class="eyebrow">
                    <?= htmlspecialchars($book['type']) ?>
                </span>

                <h1><?= htmlspecialchars($book['title']) ?></h1>

                <div class="author">
                    <span class="meta-label">Autorid</span>

                    <?php foreach ($books as $author) { ?>
                        <p>
                            <?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']) ?>
                        </p>
                    <?php } ?>
                </div>

                <div class="price">
                    <?= number_format((float)$book['price'], 2, ',', ' ') ?> €
                </div>

                <div class="book-meta">

                    <div class="meta-item">
                        <span class="meta-label">Ilmumisaasta</span>
                        <strong><?= htmlspecialchars($book['release_date']) ?></strong>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Lehekülgi</span>
                        <strong><?= $book['pages'] ?></strong>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Laoseis</span>
                        <strong><?= $book['stock_saldo'] ?> tk</strong>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Raamatu ID</span>
                        <strong><?= $book['id'] ?></strong>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">Keel</span>
                        <strong><?= htmlspecialchars($book['language']) ?></strong>
                    </div>

                </div>

                <div class="summary">
                    <h2>Raamatust lähemalt</h2>
                    <p><?= htmlspecialchars($book['summary']) ?></p>
                </div>

                <div class="book-action">
                    <a href="edit.php?id=<?= $book['id'] ?>" class="btn btn-primary">
                        Muuda raamatut
                    </a>

                    <form action="delete.php" method="POST"
                          onsubmit="return confirm('Kas soovid selle raamatu kustutada?')">
                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                        <button type="submit" class="btn btn-danger">
                            Kustuta raamat
                        </button>
                    </form>
                </div>

            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>© <?= date('Y') ?> Raamaturiiul · Iga raamat peidab endas uut maailma.</p>
    </footer>

</body>
</html>