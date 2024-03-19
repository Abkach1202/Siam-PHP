<?php
// Création ou récupération de la session
session_start();
// Si l'utilisateur est connecté et que le formulaire a été soumis
if (isset($_SESSION['username']) && $_SESSION['is_admin'] && isset($_POST['id'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // Préparation et exécution de la requête
  $query = "DELETE FROM Game WHERE game_ID = :game_ID";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':game_ID', intval($_POST['id']), PDO::PARAM_INT);
  $stmt->execute();
  // Fermeture de la connexion à la base de données
  $db = null;
  header("Location: ../pages/delete_games.php");
  exit();
} else {
  header('Location: ../pages/home.php');
  exit();
}
