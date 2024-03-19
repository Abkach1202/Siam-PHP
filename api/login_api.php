<?php
// Création ou récupération de la session
session_start();

// On procède à la connexion si l'utilisateur a soumis le formulaire
if (!isset($_SESSION['username']) && isset($_POST['username'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // Préparation et exécution de la requête
  $query = "SELECT * FROM User WHERE Username = :username";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', htmlentities($_POST['username']), PDO::PARAM_STR);
  $stmt->execute();
  // Remplissage de la session
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $_SESSION['username'] = $result['username'];
  $_SESSION['first_name'] = $result['first_name'];
  $_SESSION['last_name'] = $result['last_name'];
  $_SESSION['email'] = $result['email'];
  $_SESSION['is_admin'] = $result['is_admin'];
  $_SESSION['registration_date'] = $result['registration_date'];
  // Fermeture de la connexion à la base de données
  $db = null;
}
// Redirection
header('Location: ../pages/home_page.php');
