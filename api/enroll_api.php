<?php
// Création ou récupération de la session
session_start();
// On procède à l'inscription
if ((!isset($_SESSION['username']) || $_SESSION['is_admin']) && isset($_POST['username'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // On remplit la session avec les informations de l'utilisateur
  $_SESSION['username'] = htmlentities($_POST['username']);
  $_SESSION['first_name'] = htmlentities($_POST['first_name']);
  $_SESSION['last_name'] = htmlentities($_POST['last_name']);
  $_SESSION['email'] = htmlentities($_POST['email']);
  $_SESSION['is_admin'] = $_SESSION['is_admin'] ? intval($_POST['is_admin']) : 0;
  $_SESSION['registration_date'] = date('Y-m-d H:i:s');
  // Hashage du mot de passe
  $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  // Préparation et exécution de la requête
  $query = "INSERT INTO User (username, first_name, last_name, email, password, is_admin, registration_date) 
      VALUES (:username, :first_name, :last_name, :email, :password, 0, datetime('now'))";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', htmlentities($_POST['username']), PDO::PARAM_STR);
  $stmt->bindValue(':first_name', htmlentities($_POST['first_name']), PDO::PARAM_STR);
  $stmt->bindValue(':last_name', htmlentities($_POST['last_name']), PDO::PARAM_STR);
  $stmt->bindValue(':email', htmlentities($_POST['email']), PDO::PARAM_STR);
  $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
  $stmt->execute();
  // Fermeture de la connexion à la base de données
  $db = null;
}
// Redirection
header('Location: ../pages/home_page.php');
