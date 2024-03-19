<?php
// Création ou récupération de la session
session_start();
// Redirection vers la page d'accueil
if (isset($_SESSION['username'])) {
  header('Location: home_page.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Se connecter</title>
  <link rel="stylesheet" href="../styles/style1.css" />
  <script src="../scripts/login_page_script.js"></script>
</head>

<body>
  <form method="post" action="../api/login_api.php">
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
      <button onclick="window.location.href='enroll_page.php'">
        Créer un compte
      </button>
    </div>
  </form>
</body>

</html>