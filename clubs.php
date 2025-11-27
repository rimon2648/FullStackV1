<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'db.php';

// Setup Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader);

// Run SQL query
$sql = "SELECT * FROM la_liga_clubs ORDER BY id";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Row count
$num_rows = count($results);

// Render Twig template
echo $twig->render('clubs.html', [
    'num_rows' => $num_rows,
    'results'  => $results
]);
