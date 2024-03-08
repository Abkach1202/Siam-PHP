<?php
// Création ou récupération de la session
session_start();

// On procède à l'inscription
if (!isset($_SESSION['username']) && isset($_POST['submit']) && isset($_POST['username'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // On remplit la session avec les informations de l'utilisateur
  $_SESSION['username'] = $_POST['username'];
  $_SESSION['first_name'] = $_POST['first_name'];
  $_SESSION['last_name'] = $_POST['last_name'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['is_admin'] = 0;
  $_SESSION['registration_date'] = date('Y-m-d H:i:s');
  // Hashage du mot de passe
  $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  // la requête SQL à executer
  $query = "INSERT INTO User (username, first_name, last_name, email, password, is_admin, registration_date) 
      VALUES (:username, :first_name, :last_name, :email, :password, 0, datetime('now'))";
  // Préparation de la déclaration SQL
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', $_POST['username'], SQLITE3_TEXT);
  $stmt->bindValue(':first_name', $_POST['first_name'], SQLITE3_TEXT);
  $stmt->bindValue(':last_name', $_POST['last_name'], SQLITE3_TEXT);
  $stmt->bindValue(':email', $_POST['email'], SQLITE3_TEXT);
  $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
  // Exécution de la requête
  $stmt->execute();
  // Fermeture de la connexion à la base de données
  $db = null;
}
// Redirection vers la page d'accueil
if (isset($_SESSION['username']) || (isset($_POST['submit']) && isset($_POST['username']))) {
  header('Location: login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Enroll</title>
  <link rel="stylesheet" href="../styles/enroll.css" />
</head>

<body>
  <form action="" method="post">
    <h2>Inscription</h2>
    <div>
      <label for="first_name">Prénom :</label>
      <input type="text" name="first_name" required />
    </div>
    <div>
      <label for="last_name">Nom :</label>
      <input type="text" name="last_name" required />
    </div>
    <div>
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" name="username" required />
    </div>
    <div>
      <label for="email">Email :</label>
      <input type="email" name="email" required />
    </div>
    <div>
      <label for="password">Mot de passe :</label>
      <input type="password" name="password" required />
    </div>
    <div>
      <label for="confirm_password">Confirmez le mot de passe :</label>
      <input type="password" name="confirm_password" required />
    </div>
    <input type="submit" name="submit" value="S'inscrire" />
    <div>
      <p>Déjà un compte ?</p>
      <button onclick="window.location.href='login.php'">
        Connectez-vous
      </button>
    </div>
  </form>
</body>

</html>