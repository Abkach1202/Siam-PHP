<?php
// Création ou récupération de la session
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login_page.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifications d'infos</title>
  <link rel="stylesheet" href="../styles/style2.css">
</head>

<body>
  <header>
    <a href="home_page.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Modifications d'infos</h1>
  </header>
  <section>
    <h2>Changer les information de compte</h2>
    <div id="forms">
      <form action="../api/set_infos_api.php" method="post" class="form">
        <p>Seul l'ancien mot de passe est obligatoire</p>
        <label for="last_name">Nom:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $_SESSION['last_name'] ?>" />
        <label for="first_name">Prénom:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $_SESSION['first_name'] ?>" />
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $_SESSION['email'] ?>" />
        <label for="last_password">Ancien mot de passe:</label>
        <input type="password" id="last_password" name="last_password" required />
        <label for="new_password">Nouveau mot de passe:</label>
        <input type="password" id="new_password" name="new_password" />
        <button type="submit">Changer</button>
      </form>
      <button class='red_buttons' onclick="window.location.href='account_page.php'">Annuler</button>
    </div>
  </section>
  <aside>
    <div>
      <?php
      echo "<p><strong>Pseudo:</strong> {$_SESSION['username']}</p>";
      echo "<p><strong>Nom:</strong> {$_SESSION['last_name']}</p>";
      echo "<p><strong>Prénom:</strong> {$_SESSION['first_name']}</p>";
      echo "<p><strong>Email:</strong> {$_SESSION['email']}</p>";
      echo "<p><strong>Date d'inscription:</strong> {$_SESSION['registration_date']}</p>";
      ?>
      <button class='red_buttons' onclick="window.location.href='../api/logout_api.php'">Deconnexion</button>
    </div>
    <?php
    if ($_SESSION['is_admin']) {
      echo "<button onclick=\"window.location.href='enroll_page.php'\">Creer un compte joueur</button>";
    }
    ?>
  </aside>
</body>

</html>