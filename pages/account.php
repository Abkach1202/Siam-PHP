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
  $query = "SELECT * FROM Game WHERE player1=:username OR player2=:username";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', $_SESSION['username'], SQLITE3_TEXT);
  $stmt->execute();
  $all_games = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <style>
    section>div {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-auto-rows: auto;
    }
  </style>
</head>

<body>
  <header>
    <a href="home.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Votre compte</h1>
  </header>
  <section>
    <h2>Information de compte</h2>
    <div>
      <?php
      echo "<p><strong>Pseudo :</strong> {$_SESSION['username']}</p>";
      echo "<p><strong>Nom :</strong> {$_SESSION['last_name']}</p>";
      echo "<p><strong>Prénom :</strong> {$_SESSION['first_name']}</p>";
      echo "<p><strong>Email :</strong> {$_SESSION['email']}</p>";
      echo "<p><strong>Administrateur :</strong> " . (($_SESSION['is_admin']) ? "Oui" : "Non") . "</p>";
      echo "<p><strong>Mot de passe :</strong> *****</p>";
      echo "<p><strong>Date d'inscription :</strong> {$_SESSION['registration_date']}</p>";
      ?>
      <button onclick="window.location.href='change.php'">Modifier les informations</button>
    </div>
    <h2>Statistiques</h2> 
    <div>
      <?php
      echo "<p><strong>Parties jouées :</strong> " . count($all_games) . "</p>";
      $victories = 0;
      $not_finished = 0;
      foreach ($all_games as $game) {
        if ($game['winner'] == $_SESSION['username']) {
          $victories++;
        }
        if ($game['is_over'] == 0) {
          $not_finished++;
        }
      }
      echo "<p><strong>Parties gagnées :</strong> $victories</p>";
      echo "<p><strong>Parties perdues :</strong> " . (count($all_games) - $victories) . "</p>";
      echo "<p><strong>Parties en cours :</strong> $not_finished</p>";
      ?>
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