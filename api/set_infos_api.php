<?php
// Création ou récupération de la session
session_start();
// Recuperation des parties à jouer et à rejoindre
if (isset($_SESSION['username']) && isset($_POST['last_password'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // Récuperation du mot de passe
  $query = "SELECT password FROM User WHERE username=:username";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // On fait le changement si le mot de passe est correct
  if (password_verify($_POST['last_password'], $result['password'])) {
    // Les nouvelles informations
    $first_name = (isset($_POST['first_name']) && $_POST['first_name'] != "") ? htmlentities($_POST['first_name']) : $_SESSION['first_name'];
    $last_name = (isset($_POST['last_name']) && $_POST['last_name'] != "") ? htmlentities($_POST['last_name']) : $_SESSION['last_name'];
    $email = (isset($_POST['email']) && $_POST['email'] != "") ? htmlentities($_POST['email']) : $_SESSION['email'];
    $new_password = (isset($_POST['new_password']) && $_POST['new_password'] != "") ? $_POST['new_password'] : $_POST['last_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    // Préparation et exécution de la requête
    $query = "UPDATE User SET first_name=:first_name, last_name=:last_name, email=:email, password=:password WHERE username=:username";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();
    // Mise à jour de la session
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;
    $_SESSION['email'] = $email;
  }
  // Fermeture de la connexion à la base de données
  $db = null;
}
// Redirection
header('Location: ../pages/account_page.php');