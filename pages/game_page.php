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
    header('Location: home_page.php');
    exit();
  }
} else {
  header('Location: home_page.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Partie <?php echo $_GET['id'] ?></title>
  <?php
  if ($result['player1'] != null && $result['player2'] != null) {
    echo "<script src='../scripts/game_page_script.js'></script>";
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
      background-image: url("../images/board.jpg");
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
    <a href="home_page.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Partie <?php echo $_GET['id'] ?></h1>
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
            echo $result['player1'] === $result['active_player'] ? $result['player1'] : $result['player2'];
          } else {
            echo $_SESSION['username'];
          }
          ?>
        </h2>
      </div>
    </div>
    <form class='form' method='post' action='../api/process_action_api.php'>
      <?php
      if ($result['player1'] != null && $result['player2'] != null) {
        echo "<input type='hidden' name='board' id='board' value='{$result['board']}'>";
        if ($_SESSION['is_admin']) {
          echo "<input type='hidden' name='player_number' id='player_number' value='" . ($result['active_player'] === $result['player1'] ? "1" : "2") . "'>";
        } else {
          echo "<input type='hidden' name='player_number' id='player_number' value='" . ($_SESSION['username'] === $result['player1'] ? "1" : "2") . "'>";
        }
        echo "<input type='hidden' id='is_player_turn' value='" . ($_SESSION['username'] === $result['active_player']) . "'>";
        echo "<input type='hidden' id='is_admin' value='{$_SESSION['is_admin']}'>";
        echo "<input type='hidden' id='is_over' value='" . ($result['winner'] != null ? "1" : "0") . "'>";
        echo "<input type='hidden' id='last_move' value='{$result['last_move']}'>";
        if ($result['winner'] != null) {
          echo "<h2>La partie est terminée. Le gagnant est " . $result['winner'] . "</h2>";
        } else if (($result['active_player'] === $_SESSION['username'] || $_SESSION['is_admin'])) {
          echo "<h2>Choisissez la direction pour un deplacement dans une case vide<hr>Choisissez la direction pour un ajout sur le plateau</h2>";
          // Les données utilisées par le script game.js et le formulaire
          echo "<input type='hidden' name='game_id' value='{$_GET['id']}'>";
          echo "<input type='hidden' name='source' id='source' value=''>";
          echo "<input type='hidden' name='destination' id='destination' value=''>";
          echo "<input type='hidden' name='action' id='action' value=''>";
          if ($result['active_player'] === $result['player1']) {
            echo "<input type='hidden' name='opponent' value='{$result['player2']}'>";
            echo "<input type='hidden' name='player' value='{$result['player1']}'>";
          } else {
            echo "<input type='hidden' name='opponent' value='{$result['player1']}'>";
            echo "<input type='hidden' name='player' value='{$result['player2']}'>";
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
      <?php
        } else {
          echo "<h2>En attente du coup de l'adversaire</h2>";
        }
      } else {
        echo "<h2>En attente d'un adversaire pour commencer le jeu</h2>";
      }
      ?>
    </form>
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