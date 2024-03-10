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

  // Exécution  et récupération des parties à jouer
  if ($_SESSION['is_admin'] == 1) {
    $query = "SELECT * FROM Game ORDER BY launch_date DESC LIMIT 5";
  } else {
    $query = "SELECT * FROM Game WHERE player1=:username OR player2=:username ORDER BY launch_date DESC LIMIT 5";
  }
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
  <title>Accueil Siam</title>
  <link rel="stylesheet" href="../styles/style2.css">
</head>

<body>
  <header>
    <a href="home.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Page d'accueil</h1>
  </header>
  <section>
    <div>
      <h2>Présentation</h2>
      <p>Le jeu de Siam est un jeu de société pour deux joueurs. Il a été créé par Bruno Cathala et Bruno Faidutti en 2005. Le jeu se joue sur un plateau de 5x5 cases. Chaque joueur possède 5 éléphants et 1 montagne. Le but du jeu est de pousser la montagne adverse hors du plateau ou de placer un de ses éléphants sur la montagne adverse.</p>
      <button id="rules" onclick="window.location.href='rules.php'">Voir les règles</button>
    </div>
    <div>
      <h2>
        Parties à jouer
        <button class="small_buttons" onclick="window.location.href='joined_games.php'">Voir tout</button>
      </h2>
      <?php
      echo "<table>";
      echo "<tr><th>Partie</th><th>Joueur 1</th><th>Joueur 2</th><th>Joueur actif</th><th>Gagnant</th></tr>";
      foreach ($play_results as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['player1']}" . ($row['player1'] == $row['launcher']) ? "(créateur)" : "" . "</td>";
        echo "<td>{$row['player2']}" . ($row['player2'] == $row['launcher']) ? "(créateur)" : "" . "</td>";
        echo "<td>{$row['active_player']}</td>";
        echo "<td>{$row['winner']}</td>";
        echo "</tr>";
      }
      echo "</table>";
      ?>
    </div>
    <div>
      <h2>
        Parties à rejoindre
        <button class="small_buttons" onclick="window.location.href='available_games.php'">Voir tout</button>
      </h2>
      <?php
      echo "<table>";
      echo "<tr><th>Partie</th><th>Joueur 1</th><th>Joueur 2</th><th>Date de lancement</th></tr>";
      foreach ($join_results as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['player1']}</td>";
        echo "<td>{$row['player2']}</td>";
        echo "<td>{$row['launch_date']}</td>";
        echo "</tr>";
      }
      echo "</table>";
      ?>
    </div>
    <div>
      <h2>Gestion de parties</h2>
      <div id="forms">
        <form method="post">
          <select name="player">
            <option value="player1">Joueur 1</option>
            <option value="player2">Joueur 2</option>
          </select>
          <button type="submit">Creer une partie</button>
        </form>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
          echo "<button class='red_buttons'>Supprimer une partie</button>";
        } ?>
      </div>
    </div>
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