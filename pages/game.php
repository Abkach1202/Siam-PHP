<?php
// Création ou récupération de la session
session_start();

// Redirection si l'utilisateur n'est pas connecté
if (isset($_GET['id']) && isset($_SESSION['username'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // Préparation et exécution de la requête
  $query = "SELECT * FROM Game WHERE game_ID=:id";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $stmt->execute();
  // Récupération des résultats
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  // Fermeture de la connexion à la base de données
  $db = null;
  // Redirection si l'utilisateur n'est pas autorisé à accéder à la partie
  if (!$_SESSION['is_admin'] && $result['player1'] != $_SESSION['username'] && $result['player2'] != $_SESSION['username']) {
    header('Location: home.php');
    exit();
  }
} else {
  header('Location: home.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Siam</title>
  <?php
  if ($result['player1'] != null && $result['player2'] != null) {
    echo "<script src='../scripts/game.js'></script>";
  }
  ?>
  <link rel="stylesheet" href="../styles/style2.css">
  <style>
    section {
      display: flex;
      flex-direction: row;
      justify-content: space-around;
      align-items: center
    }

    canvas {
      background-image: url("../images/plateau.jpg");
      background-size: cover;
      border: 2px solid black;
    }

    #game {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px;
      row-gap: 20px;
    }

    .player {
      display: flex;
      flex-direction: column;
      align-items: center;
      row-gap: 10px;
    }

    h2 {
      margin: 0;
    }
  </style>
</head>

<body>
  <header>
    <a href="home.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Page d'accueil</h1>
  </header>
  <section>
    <div id="game">
      <div class="player">
        <h2>
          <?php
          if ($_SESSION['is_admin']) {
            echo $result['player1'] == $result['active_player'] ? $result['player2'] : $result['player1'];
          } else {
            echo $result['player1'] == $_SESSION['username'] ? $result['player2'] : $result['player1'];
          }
          ?>
        </h2>
        <canvas id="opponent_canvas" width="400" height="80"></canvas>
      </div>
      <canvas id="game_canvas" width="400" height="400"></canvas>
      <div class="player">
        <canvas id="player_canvas" width="400" height="80"></canvas>
        <button type="button" class="red_buttons" id="cancel" disabled>Annuler la selection</button>
        <h2>
          <?php
          if ($_SESSION['is_admin']) {
            echo $result['player1'] == $result['active_player'] ? $result['player1'] : $result['player2'];
          } else {
            echo $_SESSION['username'];
          }
          ?>
        </h2>
      </div>
    </div>
    <?php
    if ($result['player1'] != null && $result['player2'] != null) {
      echo "<form class='form' method='post' action='../api/process_action.php'>";
      // Les données utilisées par le script game.js
      echo "<input type='hidden' id='current_player' value='{$_SESSION['username']}'>";
      echo "<input type='hidden' id='active_player' value='{$result['active_player']}'>";
      echo "<input type='hidden' id='last_move' value='{$result['last_move']}'>";
      echo "<input type='hidden' id='is_admin' value='{$_SESSION['is_admin']}'>";
      echo "<input type='hidden' id='is_over' value='" . ($result['winner'] != null ? "1" : "0") . "'>";
      // Les données pour le formulaire POST
      echo "<input type='hidden' name='game_id' value='{$_GET['id']}'>";
      echo "<input type='hidden' name='board' id='board' value='{$result['board']}'>";
      echo "<input type='hidden' name='source' id='source' value=''>";
      echo "<input type='hidden' name='destination' id='destination' value=''>";
      echo "<input type='hidden' name='action' id='action' value=''>";
      if ($result['player1'] === $_SESSION['username'] || ($_SESSION['is_admin'] && $result['active_player'] === $result['player1'])) {
        echo "<input type='hidden' name='player_number' id='player_number' value='1'>";
        echo "<input type='hidden' name='opponent' value='{$result['player2']}'>";
        echo "<input type='hidden' name='player' value='{$result['player1']}'>";
      } else {
        echo "<input type='hidden' name='player_number' id='player_number' value='2'>";
        echo "<input type='hidden' name='opponent' value='{$result['player1']}'>";
        echo "<input type='hidden' name='player' value='{$result['player2']}'>";
      }
      if ($result['winner'] != null) {
        echo "<h2>La partie est terminée. Le gagnant est " . $result['winner'] . "</h2>";
      } else if ($result['active_player'] === $_SESSION['username'] || $_SESSION['is_admin']) {
        echo "<h2>C'est à vous de jouer<br>Le choix de la direction compte lors d'un deplacement dans une case vide ou d'un ajout sur le plateau</h2>";
      } else {
        echo "<h2>En attente du coup de l'adversaire</h2>";
      }
    ?>
      <label for="direction">Direction de l'animal à la destination</label>
      <select name="direction" id="direction">
        <option value="0">Haut</option>
        <option value="2">Bas</option>
        <option value="1">Gauche</option>
        <option value="3">Droite</option>
      </select>
      <button type="submit" id="turn" disabled>Tourner</button>
      <button type="submit" id="remove" disabled>Retirer</button>
      </form>
    <?php
    } else {
      echo "<h1>En attente d'un adversaire pour commencer le jeu</h1>";
    }
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
      <button class='red_buttons' onclick="window.location.href='../api/logout.php'">Deconnexion</button>
    </div>
  </aside>
</body>

</html>