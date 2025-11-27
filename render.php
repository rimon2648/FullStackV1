<?php
// point to Composer autoload file
require_once __DIR__ . '/vendor/autoload.php';

// Setup Twig environment
$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader);

// Example data
$people = [
    ['FirstName' => 'Alix', 'Surname' => 'Bergeret'],
    ['FirstName' => 'Hiran', 'Surname' => 'Patel'],
];

// Render the template
echo $twig->render('template.html', [
    'a_variable' => 'Alix',
    'another_variable' => 'Bergeret',
    'people' => $people
]);
