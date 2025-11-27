<?php
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID.");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM la_liga_clubs WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: list-club.php");
exit;
