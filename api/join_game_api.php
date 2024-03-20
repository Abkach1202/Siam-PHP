<?php
// Création ou récupération de la session
session_start();

// Redirection si le joueur n'est pas connecté
if (isset($_POST['id']) && isset($_SESSION['username'])) {
  if ($_POST['redirect'] !== "1") {
    // Connexion à la base de données SQLite
    try {
      $db = new PDO('sqlite:../datas/data_base.db');
    } catch (PDOException $e) {
      echo "Échec de la connexion", $e->getMessage();
      $db = null;
      exit();
    }

    // On verifie que la partie n'est pas pleine pour eviter que 2 joueurs ne rejoignent la partie en meme temps
    $query = "SELECT * FROM Game WHERE game_ID=:id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['player1'] != null && $result['player2'] != null) {
      header('Location: ../pages/waiting_games_page.php');
      exit();
    }

    // Préparation et exécution de la requête
    if ($_POST['player'] === "1") {
      $query = "UPDATE Game SET player1=:username active_player=:username WHERE game_ID=:id";
    } else {
      $query = "UPDATE Game SET player2=:username WHERE game_ID=:id";
    }
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
    $stmt->execute();
    // Fermeture de la connexion à la base de données
    $db = null;
  }
  header('Location: ../pages/game_page.php?id=' . $_POST['id'] . '#game_canvas');
} else {
  header('Location: ../pages/home_page.php');
}
