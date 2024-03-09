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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parties à jouer</title>
  <link rel="stylesheet" href="../styles/home.css">
</head>

<body>
  <header>
    <a href="home.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Parties à jouer</h1>
  </header>
  <section>
    <h2 class="titles">Liste des parties à jouer</h2>
    <?php
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
    if (isset($_SESSION['username'])) {
      foreach ($play_results as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['player1']}" . ($row['player1'] == $row['launcher']) ? "(créateur)" : "" . "</td>";
        echo "<td>{$row['player2']}" . ($row['player2'] == $row['launcher']) ? "(créateur)" : "" . "</td>";
        echo "<td>{$row['active_player']}</td>";
        echo "<td>{$row['winner']}</td>";
        echo "<td>{$row['launch_date']}</td>";
        echo "<td><button onclick=\"window.location.href='game.php?id={$row['id']}'\">Rejoindre</button></td>";
        echo "</tr>";
      }
    }
    echo "</table>";
    ?>
  </section>
  <aside>
    <button><?php if (isset($_SESSION['username'])) echo "Compte";
            else echo "Se Connecter"; ?></button>
    <div>
      <?php if (isset($_SESSION['username'])) {
        echo "<p><strong>Pseudo:</strong> {$_SESSION['username']}</p>";
        echo "<p><strong>Nom:</strong> {$_SESSION['last_name']}</p>";
        echo "<p><strong>Prénom:</strong> {$_SESSION['first_name']}</p>";
        echo "<p><strong>Email:</strong> {$_SESSION['email']}</p>";
        echo "<p><strong>Date d'inscription:</strong> {$_SESSION['registration_date']}</p>";
        echo "<button class='red_buttons' onclick=\"window.location.href='logout.php'\">Deconnexion</button>";
      } ?>
    </div>
  </aside>
</body>

</html>