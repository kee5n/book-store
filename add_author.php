
<?php
require('./database.php');

$bookId = $_POST['book_id'];
$authorId = $_POST['author_id'];

// Kontrollime, et seos pole juba olemas
$stmt = $pdo->prepare('
    SELECT COUNT(*)
    FROM book_authors
    WHERE book_id = :book_id AND author_id = :author_id
');
$stmt->execute([
    'book_id' => $bookId,
    'author_id' => $authorId
]);

if ($stmt->fetchColumn() == 0) {
    $stmt = $pdo->prepare('
        INSERT INTO book_authors (book_id, author_id)
        VALUES (:book_id, :author_id)
    ');

    $stmt->execute([
        'book_id' => $bookId,
        'author_id' => $authorId
    ]);
}

echo "<script>window.location.href = 'edit.php?id="
    . (int)$bookId . "';</script>";
exit;
?>
