<?php

require('./database.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();


$stmt = $pdo->prepare('
    SELECT
        authors.id AS id,
        authors.first_name,
        authors.last_name
    FROM book_authors
    JOIN authors
        ON book_authors.author_id = authors.id
    WHERE book_authors.book_id = :id
    ORDER BY authors.last_name, authors.first_name
    ');

$stmt->execute(['id' => $id]);
$authors = $stmt->fetchAll();

$stmt = $pdo->prepare('
    SELECT id, first_name, last_name
    FROM authors
    ORDER BY last_name, first_name
');

$stmt->execute();
$allAuthors = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= htmlspecialchars($book['title']) ?> – Muuda raamatut</title>
</head>
<body>

<header class="site-header">
    <a href="index.php" class="logo">Raamatu pood</a>
    <nav>
        <a href="index.php">Raamatud</a>
    </nav>
</header>

<main class="edit-page">

    <a href="book.php?id=<?= $book['id'] ?>" class="back-link">
        ← Tagasi raamatu juurde
    </a>

    <section class="edit-card">
        <p class="eyebrow">Raamatupood</p>
        <h1>Muuda raamatut</h1>
        <p class="edit-description">
            Muuda raamatu andmeid ja halda selle autoreid.
        </p>

        <form action="update.php" method="POST" class="edit-form">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">

            <div class="form-group">
                <label for="title">Pealkiri</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars($book['title']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="price">Hind (€)</label>
                <input
                    type="text"
                    id="price"
                    name="price"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars((string)$book['price']) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="summary">Kokkuvõte</label>
                <textarea
                    id="summary"
                    name="summary"
                    rows="5"
                ><?= htmlspecialchars($book['summary'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="pages">Lehekülgede arv</label>
                    <input
                        type="text"
                        id="pages"
                        name="pages"
                        min="0"
                        value="<?= $book['pages'] ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="stock_saldo">Lao seis</label>
                    <input
                        type="text"
                        id="stock_saldo"
                        name="stock_saldo"
                        min="0"
                        value="<?= $book['stock_saldo'] ?>"
                    >
                </div>
            </div>

            <button class="btn btn-primary" type="submit">
                Salvesta muudatused
            </button>
        </form>
    </section>

    <section class="edit-card authors-card">
        <p class="eyebrow">Autorid</p>
        <h2>Raamatu autorid</h2>

        <div class="author-list">
            <?php foreach ($authors as $author): ?>
                <div class="author-row">
                    <span>
                        <?= htmlspecialchars(
                            $author['first_name'] . ' ' . $author['last_name']
                        ) ?>
                    </span>

                    <form action="remove_author.php" method="POST">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <input type="hidden" name="author_id" value="<?= $author['id'] ?>">

                        <button type="submit" class="btn btn-danger">
                            Eemalda
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>

            <?php if (empty($authors)): ?>
                <p class="edit-description">Sellele raamatule pole veel autoreid lisatud.</p>
            <?php endif; ?>
        </div>

        <div class="add-author">
            <h3>Lisa autor</h3>

            <form action="add_author.php" method="POST" class="add-author-form">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">

                <select name="author_id" required>
                    <option value="">Vali autor</option>

                    <?php foreach ($allAuthors as $author): ?>
                        <?php
                        $alreadyAdded = false;

                        foreach ($authors as $bookAuthor) {
                            if ($bookAuthor['id'] == $author['id']) {
                                $alreadyAdded = true;
                                break;
                            }
                        }
                        ?>

                        <?php if (!$alreadyAdded): ?>
                            <option value="<?= $author['id'] ?>">
                                <?= htmlspecialchars(
                                    $author['first_name'] . ' ' . $author['last_name']
                                ) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary">
                    Lisa autor
                </button>
            </form>
        </div>
    </section>

</main>

<footer class="site-footer">
    Book Store
</footer>

</body>
</html>
