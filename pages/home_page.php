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

  // Exécution et récupération des parties en cours
  if ($_SESSION['is_admin'] == 1) {
    $query = "SELECT * FROM Game WHERE player1 IS NOT NULL AND player2 IS NOT NULL AND winner IS NULL
    ORDER BY launch_date DESC LIMIT 5";
    $stmt = $db->prepare($query);
  } else {
    $query = "SELECT * FROM Game WHERE (player1=:username OR player2=:username) AND player1 IS NOT NULL
    AND player2 IS NOT NULL AND winner IS NULL ORDER BY launch_date DESC LIMIT 5";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
  }
  $stmt->execute();
  $in_progress_games = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Exécution et récupération des parties terminée
  if ($_SESSION['is_admin'] == 1) {
    $query = "SELECT * FROM Game WHERE winner IS NOT NULL
    ORDER BY launch_date DESC LIMIT 5";
    $stmt = $db->prepare($query);
  } else {
    $query = "SELECT * FROM Game WHERE (player1=:username OR player2=:username) AND winner IS NOT NULL ORDER BY launch_date DESC LIMIT 5";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
  }
  $stmt->execute();
  $finished_games = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  // Exécution et récupération des parties à rejoindre
  $query = "SELECT * FROM Game WHERE player1 IS NULL OR player2 IS NULL ORDER BY launch_date DESC LIMIT 5";
  $stmt = $db->query($query);
  $waiting_games = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
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
  <title>Accueil Siam</title>
  <link rel="stylesheet" href="../styles/style2.css">
</head>

<body>
  <header>
    <a href="home_page.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Page d'accueil</h1>
  </header>
  <section>
    <div>
      <h2>Présentation</h2>
      <p>Le jeu de Siam est un jeu de société pour deux joueurs. Il a été créé par Bruno Cathala et Bruno Faidutti en 2005. Le jeu se joue sur un plateau de 5x5 cases. Chaque joueur possède 5 éléphants et 1 montagne. Le but du jeu est de pousser la montagne adverse hors du plateau ou de placer un de ses éléphants sur la montagne adverse.</p>
      <button id="rules" onclick="window.location.href='rules_page.php'">Voir les règles</button>
    </div>
    <div>
      <h2>
        Parties en cours
        <button class="small_buttons" onclick="window.location.href='in_progress_games_page.php'">Voir tout</button>
      </h2>
      <?php
      if (empty($in_progress_games)) {
        echo "<p>Aucune partie en cours</p>";
      } else {
        echo "<table>";
        echo "<tr><th>Partie</th><th>Joueur 1</th><th>Joueur 2</th><th>Joueur actif</th><th>Gagnant</th><th>Rejoindre</th></tr>";
        foreach ($in_progress_games as $row) {
          echo "<tr>";
          echo "<td>{$row['game_ID']}</td>";
          echo "<td>{$row['player1']}" . (($row['player1'] === $row['launcher']) ? "(créateur)" : "") . "</td>";
          echo "<td>{$row['player2']}" . (($row['player2'] === $row['launcher']) ? "(créateur)" : "") . "</td>";
          echo "<td>{$row['active_player']}</td>";
          echo "<td>{$row['winner']}</td>";
          echo "<td><button onclick=\"window.location.href='game_page.php?id={$row['game_ID']}#game_canvas'\">Jouer</button></td>";
          echo "</tr>";
        }
        echo "</table>";
      }
      ?>
    </div>
    <div>
      <h2>
        Parties à rejoindre
        <button class="small_buttons" onclick="window.location.href='waiting_games_page.php'">Voir tout</button>
      </h2>
      <?php
      if (empty($waiting_games)) {
        echo "<p>Aucune partie à rejoindre</p>";
      } else {
        echo "<table>";
        echo "<tr><th>Partie</th><th>Joueur 1</th><th>Joueur 2</th><th>Date de lancement</th><th>Rejoindre</th></tr>";
        foreach ($waiting_games as $row) {
          echo "<tr>";
          echo "<td>{$row['game_ID']}</td>";
          echo "<td>{$row['player1']}</td>";
          echo "<td>{$row['player2']}</td>";
          echo "<td>{$row['launch_date']}</td>";
          echo "<td>";
          echo "<form action='../api/join_game_api.php' method='post'>";
          echo "<input type='hidden' name='id' value='{$row['game_ID']}'>";
          echo "<input type='hidden' name='player' value='" . (($row['player1'] === null) ? 1 : 2) . "'>";
          echo "<input type='hidden' name='redirect' value='" . ($row['launcher'] === $_SESSION['username']) . "'>";
          echo "<button type='submit'>Rejoindre</button>";
          echo "</form>";
          echo "</td>";

          echo "</tr>";
        }
        echo "</table>";
      }
      ?>
    </div>
    <div>
      <h2>
        Parties terminées
        <button class="small_buttons" onclick="window.location.href='finished_games_page.php'">Voir tout</button>
      </h2>
      <?php
      if (empty($finished_games)) {
        echo "<p>Aucune partie terminée</p>";
      } else {
        echo "<table>";
        echo "<tr><th>Partie</th><th>Joueur 1</th><th>Joueur 2</th><th>Joueur actif</th><th>Gagnant</th><th>Rejoindre</th></tr>";
        foreach ($finished_games as $row) {
          echo "<tr>";
          echo "<td>{$row['game_ID']}</td>";
          echo "<td>{$row['player1']}" . (($row['player1'] === $row['launcher']) ? "(créateur)" : "") . "</td>";
          echo "<td>{$row['player2']}" . (($row['player2'] === $row['launcher']) ? "(créateur)" : "") . "</td>";
          echo "<td>{$row['active_player']}</td>";
          echo "<td>{$row['winner']}</td>";
          echo "<td><button onclick=\"window.location.href='game_page.php?id={$row['game_ID']}#game_canvas'\">Voir</button></td>";
          echo "</tr>";
        }
        echo "</table>";
      }
      ?>
    </div>
    <div>
      <h2>Gestion de parties</h2>
      <div id="forms">
        <form method="post" action="../api/create_game_api.php" class="form">
          <label for="player">Choisissez votre animal</label>
          <select name="player">
            <option value="1">Éléphants</option>
            <option value="2">Rhinocéros</option>
          </select>
          <button type="submit">Creer une partie</button>
        </form>
        <?php if ($_SESSION['is_admin']) {
          echo "<button class='red_buttons' onclick='window.location.href=\"delete_games_page.php\"'>Supprimer une partie</button>";
        } ?>
      </div>
    </div>
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