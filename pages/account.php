<?php
// Création ou récupération de la session
session_start();
// Recuperation des parties à jouer et à rejoindre
if (isset($_SESSION['username'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // Exécution  et récupération des parties à jouer
  $query = "SELECT * FROM Game WHERE player1=:username OR player2=:username ORDER BY launch_date DESC";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', $_SESSION['username'], SQLITE3_TEXT);
  $stmt->execute();
  $play_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Fermeture de la connexion à la base de données
  $db = null;
} else {
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
    <div>
      <h2>Information de compte</h2>
      <?php
      echo "<p><strong>Pseudo :</strong> {$_SESSION['username']}</p>";
      echo "<p><strong>Nom :</strong> {$_SESSION['last_name']}</p>";
      echo "<p><strong>Prénom :</strong> {$_SESSION['first_name']}</p>";
      echo "<p><strong>Email :</strong> {$_SESSION['email']}</p>";
      echo "<p><strong>Administrateur :</strong> " . (($_SESSION['is_admin']) ? "Oui" : "Non") . "</p>";
      echo "<p><strong>Mot de passe :</strong> *****</p>";
      echo "<p><strong>Date d'inscription :</strong> {$_SESSION['registration_date']}</p>";
      ?>
    </div>
    <div>
      <h2>Statistiques</h2>
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
      echo "<button class='red_buttons' onclick=\"window.location.href='logout.php'\">Deconnexion</button>";
      ?>
    </div>
  </aside>
</body>

</html>