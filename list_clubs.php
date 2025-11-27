<?php
require 'db.php';
require_once __DIR__ . '/vendor/autoload.php';

// Twig setup
$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader);

// Build filters
$where = [];
$params = [];

$name = $_GET['name'] ?? '';
$city = $_GET['city'] ?? '';

if (!empty($name)) {
    $where[] = "name LIKE :name";
    $params[':name'] = "%$name%";
}

if (!empty($city)) {
    $where[] = "city LIKE :city";
    $params[':city'] = "%$city%";
}

$sql = "SELECT * FROM la_liga_clubs";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$clubs = $stmt->fetchAll();

// Render twig template
echo $twig->render('list_club.html.twig', [
    'clubs' => $clubs,
    'name' => $name,
    'city' => $city
]);
