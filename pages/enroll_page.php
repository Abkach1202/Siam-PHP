<?php
// Création ou récupération de la session
session_start();
// Redirection vers la page d'accueil
if (isset($_SESSION['username']) && !$_SESSION['is_admin']) {
  header('Location: home_page.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription</title>
  <link rel="stylesheet" href="../styles/style1.css" />
  <script src="../scripts/enroll_page_script.js"></script>
</head>

<body>
  <form method="post" action="../api/enroll_api.php">
    <h2>
      <?php
      if (isset($_SESSION['is_admin'])) {
        echo "Créer un compte";
      } else {
        echo "Inscription";
      }
      ?>
    </h2>
    <?php
    if (isset($_SESSION['is_admin'])) {
      echo "<div id='radio_group'>";
      echo "<input type='radio' id='is_admin' name='is_admin' value='1' required />";
      echo "<label for='is_admin'>Administrateur</label>";
      echo "<input type='radio' id='is_admin' name='is_admin' value='0' checked required />";
      echo "<label for='is_admin'>Joueur</label>";
      echo "</div>";
    }
    ?>
    <div>
      <label for="first_name">Prénom :</label>
      <input type="text" id="first_name" name="first_name" required />
    </div>
    <div>
      <label for="last_name">Nom :</label>
      <input type="text" id="last_name" name="last_name" required />
    </div>
    <div>
      <label for="username">Nom d'utilisateur :</label>
      <input type="text" id="username" name="username" required />
    </div>
    <div>
      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required />
    </div>
    <div>
      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required />
    </div>
    <div>
      <label for="confirm_password">Confirmez le mot de passe :</label>
      <input type="password" id="confirm_password" name="confirm_password" required />
    </div>
    <input type="submit" value="S'inscrire" />
    <?php
    if (isset($_SESSION['is_admin'])) {
      echo "<div>";
      echo "<p>Vous êtes administrateur</p>";
      echo "<button onclick=\"window.location.href='home_page.php'\">Retour à l'accueil</button>";
      echo "</div>";
    } else {
      echo "<div>";
      echo "<p>Déjà un compte ?</p>";
      echo "<button onclick=\"window.location.href='login_page.php'\">Connectez-Vous</button>";
      echo "</div>";
    }
    ?>
  </form>
</body>

</html>