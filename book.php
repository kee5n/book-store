<?php

require('./database.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id ]);
$book = $stmt->fetch();

var_dump($book);
// $id = $_GET['id'] ?? '';
// $stmt->execute(['id' => $id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> <?= $book['title']; ?> </title>
</head>
<body>

    <h1>Pealkiri "<?= $book['title']; ?>"</h1>
    <p>Hind <?= $book['price']; ?>€</p>
    <p>Lehtede arv <?= $book['pages']; ?></p>
    <!-- lisa autor -->

    <div>
        <h2>Kokkuvõte</h2>
        <p><?= $book['summary'] ?></p>
    </div>

    <a href="edit.php?id=<?= $book['id']; ?>">Muuda</a>

</body>
</html>