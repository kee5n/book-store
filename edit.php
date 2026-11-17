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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= $book['title'] ?></title>
</head>
<body>
    <h1><?= $book['title'] ?></h1>

    <form  action="update.php" method="POST">

        <input type="hidden" name="id" value="<?= $book['id'] ?>">

        <div>
            <p>Pealkiri</p>
            <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>">
        </div>

        <div>
            <p>Hind</p>
            <input type="text" name="price" value="<?= number_format($book['price'], 2) ?>">
        </div>

        <div>
            <p>Kokkuvõte</p>
            <input type="text" name="summary" value="<?= $book['summary'] ?>">
        </div>

        <div>
            <p>Lehekülgede arv</p>
            <input type="text" name="pages" value="<?= $book['pages'] ?>">
        </div>

        <div>
            <p>Lao seis</p>
            <input type="text" name="stock_saldo" value="<?= $book['stock_saldo'] ?>">
        </div>

        <button class="btn-primary" type="submit">Salvesta</button>

    </form>


    <h2>Raamatu autorid</h2>

<?php foreach ($authors as $author): ?>
    <div>
        <?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']) ?>

        <form action="remove_author.php" method="POST">
            <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
            <input type="hidden" name="author_id" value="<?= $author['id'] ?>">
            <button type="submit">Eemalda autor</button>
        </form>
    </div>
<?php endforeach; ?>


<h2>Lisa autor</h2>

<form action="add_author.php" method="POST">
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
                    <?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']) ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>

    <button type="submit">Lisa autor</button>
</form>




</body>
</html>