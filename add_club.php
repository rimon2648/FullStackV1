<?php
require 'db.php';
require_once __DIR__ . '/vendor/autoload.php';

session_start();

// Set up Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader);

// Ensure CSRF token exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf = $_SESSION['csrf_token'];
$errors = [];
$success = false;

// Default "old" values for re-populating form after errors
$old = [
    'name' => '',
    'city' => '',
    'stadium' => '',
    'capacity' => '',
    'founded_year' => '',
    'manager' => '',
    'notes' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF validation
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid security token. Please refresh the page.";
    }

    // Fill "old" values
    foreach ($old as $field => $value) {
        $old[$field] = trim($_POST[$field] ?? '');
    }

    // Validation
    if ($old['name'] === '') {
        $errors[] = "Club name is required.";
    }

    if (!$errors) {
        $sql = "INSERT INTO la_liga_clubs 
                (name, city, stadium, capacity, founded_year, manager, notes, created_at, updated_at)
                VALUES 
                (:name, :city, :stadium, :capacity, :founded_year, :manager, :notes, NOW(), NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $old['name'],
            ':city' => $old['city'],
            ':stadium' => $old['stadium'],
            ':capacity' => ($old['capacity'] === '' ? null : (int)$old['capacity']),
            ':founded_year' => ($old['founded_year'] === '' ? null : (int)$old['founded_year']),
            ':manager' => $old['manager'],
            ':notes' => $old['notes']
        ]);

        $success = true;

        // Clear old values after success
        $old = [
            'name' => '',
            'city' => '',
            'stadium' => '',
            'capacity' => '',
            'founded_year' => '',
            'manager' => '',
            'notes' => ''
        ];
    }
}

// Render Twig template
echo $twig->render('add_club.twig', [
    'csrf' => $csrf,
    'errors' => $errors,
    'success' => $success,
    'old' => $old
]);
