<?php $pdo = new PDO(
  'mysql:host=localhost;dbname=veville', // SGBD avec host et bdd
  'root', // user
  '', // password
  array( // tableau d'options
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // Affichage des erreurs en mode warning : nous serons avertis des erreurs SQL
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', // Encodage des noms de tables et colonnes
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Parcours des données en mode tableau ASSOCiatif
  )
);

$order = $_GET['order']; // récupère l'ordre de tri (croissant ou décroissant) depuis l'URL

if ($order == "asc") {
    $order_by = "ORDER BY prix_journalier ASC"; // tri croissant
} elseif ($order == "desc") {
    $order_by = "ORDER BY prix_journalier DESC"; // tri décroissant
}


$query = "SELECT * FROM vehicule" . $order_by; // requête pour sélectionner tous les articles triés par prix
$result = mysqli_query($conn, $query);

// afficher les articles triés
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['prix_journalier'] . "<br>";
}