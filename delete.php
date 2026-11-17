<?php
require('./database.php');

$id = $_POST['id'];

$stmt = $pdo->prepare('UPDATE books SET is_deleted = 1 WHERE id = ?');
$stmt->execute([$id]);

header('Location: index.php');
exit;