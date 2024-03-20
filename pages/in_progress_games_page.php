<?php
// Création ou récupération de la session
session_start();
// Recupération des parties à jouer et à rejoindre
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
  if ($_SESSION['is_admin'] == 1) {
    $query = "SELECT * FROM Game WHERE player1 IS NOT NULL AND player2 IS NOT NULL ORDER BY launch_date DESC LIMIT 5";
    $stmt = $db->prepare($query);
  } else {
    $query = "SELECT * FROM Game WHERE (player1=:username OR player2=:username) AND player1 IS NOT NULL AND player2 IS NOT NULL ORDER BY launch_date DESC LIMIT 5";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
  }
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Fermeture de la connexion à la base de données
  $db = null;
} else {
  header('Location: login_page.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parties en cours</title>
  <link rel="stylesheet" href="../styles/style2.css">
</head>

<body>
  <header>
    <a href="home_page.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Parties en cours</h1>
  </header>
  <section>
    <h2>Liste des parties en cours</h2>
    <?php
    if (empty($result)) {
      echo "<h3>Aucune partie à jouer</h3>";
    } else {
      echo "<table>";
      echo "<tr>";
      echo "<th>Id_Partie</th>";
      echo "<th>Joueur1</th>";
      echo "<th>Joueur2</th>";
      echo "<th>Joueur_actif</th>";
      echo "<th>Gagnant</th>";
      echo "<th>Date de lancement</th>";
      echo "<th>Rejoindre</th>";
      echo "</tr>";
      foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['game_ID']}</td>";
        echo "<td>{$row['player1']}" . (($row['player1'] === $row['launcher']) ? "(créateur)" : "") . "</td>";
        echo "<td>{$row['player2']}" . (($row['player2'] === $row['launcher']) ? "(créateur)" : "") . "</td>";
        echo "<td>{$row['active_player']}</td>";
        echo "<td>{$row['winner']}</td>";
        echo "<td>{$row['launch_date']}</td>";
        echo "<td><button onclick=\"window.location.href='game_page.php?id={$row['game_ID']}#game_canvas'\">Jouer</button></td>";
        echo "</tr>";
      }
      echo "</table>";
    }
    ?>
  </section>
  <aside>
    <button onclick="window.location.href='account_page.php'">Compte</button>
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