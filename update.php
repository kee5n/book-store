<?php
require('./database.php');

$id = $_POST['id'];
$title = $_POST['title'];
$price = $_POST['price'];
$summary = $_POST['summary'];
$pages = $_POST['pages'];
$stock_saldo = $_POST['stock_saldo'];

$stmt = $pdo->prepare(
    'UPDATE books SET 
        title = :title,
        price = :price,
        summary = :summary,
        pages = :pages,
        stock_saldo = :stock_saldo
    WHERE id = :id');

$stmt->execute([
    'title' => $title,
    'id' => $id,
    'price' => $price,
    'summary' => $summary,
    'pages' => $pages,
    'stock_saldo' => $stock_saldo,
]);

header("Location: book.php?id={$id}&update=success");
