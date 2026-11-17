<?php
require('./database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add-author.php');
    exit;
}

$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');

if ($firstName === '' || $lastName === '') {
    header('Location: add_author_page.php?error=required');
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO authors (first_name, last_name) VALUES (?, ?)'
);

$stmt->execute([$firstName, $lastName]);

header('Location: add-author.php?success=1');
exit;