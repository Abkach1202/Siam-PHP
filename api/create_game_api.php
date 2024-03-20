<?php
// Création ou récupération de la session
session_start();

// Si l'utilisateur est connecté et que le formulaire a été soumis
if (isset($_SESSION['username']) && isset($_POST['player'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  $board = array(
    array("", "", "", "", ""),
    array("", "", "", "", ""),
    array("", "00", "00", "00", ""),
    array("", "", "", "", ""),
    array("", "", "", "", "")
  );
  // Préparation et exécution de la requête
  $query = "INSERT INTO Game (board, player1, player2, active_player, launcher, launch_date)
  VALUES (:board, :player1, :player2, :active_player, :launcher, datetime('now'))";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':board', json_encode($board), PDO::PARAM_STR);
  if ($_POST['player'] === "1") {
    $stmt->bindValue(':player1', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->bindValue(':player2', null, PDO::PARAM_NULL);
    $stmt->bindValue(':active_player', $_SESSION['username'], PDO::PARAM_STR);
  } else {
    $stmt->bindValue(':player1', null, PDO::PARAM_NULL);
    $stmt->bindValue(':player2', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->bindValue(':active_player', null, PDO::PARAM_NULL);
  }
  $stmt->bindValue(':launcher', $_SESSION['username'], PDO::PARAM_STR);
  $stmt->execute();
  // Récupération de l'identifiant de la partie
  $game_id = $db->lastInsertId();
  // Fermeture de la connexion à la base de données
  $db = null;
} 
header('Location: ../pages/home_page.php');
