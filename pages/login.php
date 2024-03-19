<?php
// Création ou récupération de la session
session_start();
// On procède à la récupération des données
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
  $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
  $stmt->execute();
  // Récupération des résultats et remplissage de la session
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
// Redirection vers la page d'accueil
if (isset($_SESSION['username']) || isset($_POST['username'])) {
  header('Location: home.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../styles/style1.css" />
  <script src="../scripts/login.js"></script>
</head>

<body>
  <form method="post">
    <h2>Connexion</h2>
    <div>
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" id="username" name="username" required />
    </div>
    <div>
      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required />
    </div>
    <input type="submit" name="submitted" value="Se connecter" />
    <div>
      <p>Vous n'avez pas de compte ?</p>
      <button onclick="window.location.href='enroll.php'">
        Créer un compte
      </button>
    </div>
  </form>
</body>

</html>