<?php
require('./database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
    $authorId = filter_input(INPUT_POST, 'author_id', FILTER_VALIDATE_INT);

    if (!$bookId || !$authorId) {
        exit('Raamatu või autori ID puudub või on vigane!');
    }

    $stmt = $pdo->prepare('
        DELETE FROM book_authors
        WHERE book_id = :book_id
          AND author_id = :author_id
    ');

    $stmt->execute([
        'book_id' => $bookId,
        'author_id' => $authorId
    ]);

    if ($stmt->rowCount() > 0) {
        header('Location: edit.php?id=' . $bookId);
        exit;
    }

    header('Location: edit.php?id=' . $bookId . '&error=author_not_removed');
    exit;
}

header('Location: book.php?id={$id}');
exit;
