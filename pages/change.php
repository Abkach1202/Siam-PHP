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
    $first_name = (isset($_POST['first_name']) && $_POST['first_name'] != "") ? $_POST['first_name'] : $_SESSION['first_name'];
    $last_name = (isset($_POST['last_name']) && $_POST['last_name'] != "") ? $_POST['last_name'] : $_SESSION['last_name'];
    $email = (isset($_POST['email']) && $_POST['email'] != "") ? $_POST['email'] : $_SESSION['email'];
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
} else if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Votre compte</title>
  <link rel="stylesheet" href="../styles/style2.css">
</head>

<body>
  <header>
    <a href="home.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Votre compte</h1>
  </header>
  <section>
    <h2>Changer les information de compte</h2>
    <div id="forms">
      <form action="change.php" method="post" class="form">
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
      <button class='red_buttons' onclick="window.location.href='account.php'">Annuler</button>
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
      <button class='red_buttons' onclick="window.location.href='../api/logout.php'">Deconnexion</button>
    </div>
  </aside>
</body>

</html>