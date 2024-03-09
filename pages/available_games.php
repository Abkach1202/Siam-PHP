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
  // Exécution  et récupération des parties à rejoindre
  $query = "SELECT * FROM Game WHERE player1 IS NULL OR player2 IS NULL ORDER BY launch_date DESC LIMIT 5";
  $stmt = $db->query($query);
  $join_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <title>Parties à rejoindre</title>
  <link rel="stylesheet" href="../styles/style2.css">
</head>

<body>
  <header>
    <a href="home.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Parties à rejoindre</h1>
  </header>
  <section>
    <h2>Liste des parties à rejoindre</h2>
    <?php
    echo "<table>";
    echo "<tr>";
    echo "<th>Id_Partie</th>";
    echo "<th>Joueur1</th>";
    echo "<th>Joueur2</th>";
    echo "<th>Date de lancement</th>";
    echo "<th>Rejoindre</th>";
    echo "</tr>";
    foreach ($join_results as $row) {
      echo "<tr>";
      echo "<td>{$row['id']}</td>";
      echo "<td>{$row['player1']}" . (($row['player1'] == $row['launcher']) ? "(créateur)" : "") . "</td>";
      echo "<td>{$row['player2']}" . (($row['player2'] == $row['launcher']) ? "(créateur)" : "") . "</td>";
      echo "<td>{$row['launch_date']}</td>";
      echo "<td><button onclick=\"window.location.href='game.php?id={$row['id']}'\">Rejoindre</button></td>";
      echo "</tr>";
    }
    echo "</table>";
    ?>
  </section>
  <aside>
    <button onclick="window.location.href='account.php'">Compte</button>
    <div>
      <?php
      echo "<p><strong>Pseudo:</strong> {$_SESSION['username']}</p>";
      echo "<p><strong>Nom:</strong> {$_SESSION['last_name']}</p>";
      echo "<p><strong>Prénom:</strong> {$_SESSION['first_name']}</p>";
      echo "<p><strong>Email:</strong> {$_SESSION['email']}</p>";
      echo "<p><strong>Date d'inscription:</strong> {$_SESSION['registration_date']}</p>";
      ?>
      <button class='red_buttons' onclick="window.location.href='logout.php'">Deconnexion</button>
    </div>
  </aside>
</body>

</html>