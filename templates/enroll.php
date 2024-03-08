<?php
// Connexion à la base de données SQLite
$db = new SQLite3('path/to/your/database.db');

// Vérifier la connexion
if (!$db) {
  die("La connexion à la base de données a échoué");
}

// Récupérer les données du formulaire
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Vérifier si les mots de passe correspondent
if ($password != $confirm_password) {
  echo "Les mots de passe ne correspondent pas";
  exit;
}

// Hasher le mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Préparer la requête SQL
$query = "INSERT INTO User (First_name, Last_name, Username, Email, Password, Is_admin, Registration_date) 
      VALUES (:first_name, :last_name, :username, :email, :password, 0, datetime('now'))";

// Préparer la déclaration SQL
$stmt = $db->prepare($query);
$stmt->bindValue(':first_name', $first_name, SQLITE3_TEXT);
$stmt->bindValue(':last_name', $last_name, SQLITE3_TEXT);
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);

// Exécuter la requête
$result = $stmt->execute();

if ($result) {
  echo "Inscription réussie!";
} else {
  echo "Erreur lors de l'inscription";
}

// Fermer la connexion à la base de données
$db->close();
?>
