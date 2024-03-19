<?php
// Création ou récupération de la session
session_start();
// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['username'])) {
  header('Location: login_page.php');
  exit();
}

// Definition de la taille du plateau
define("DIM", 5);

if (isset($_POST['action'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // Récupération des données
  $board = json_decode($_POST['board']);
  $cell_src = json_decode($_POST['source']);
  $cell_dst = json_decode($_POST['destination']);
  $piece = "";
  
  // Exécution de l'action
  if ($_POST['action'] == "turn") {
    $board[$cell_src[0]][$cell_src[1]] = $_POST['player_number'] . $_POST['direction'];
    $last_move = json_encode($cell_src);
  } else if ($_POST['action'] == "remove") {
    $board[$cell_src[0]][$cell_src[1]] = "";
    $last_move = json_encode($cell_src);
  } else if ($_POST['action'] == "add") {
    $piece = add_piece($board, $cell_dst[0], $cell_dst[1], $_POST['player_number'], $_POST['direction']);
    $last_move = json_encode($cell_dst);
  } else if ($_POST['action'] == "move") {
    $direction = get_direction($cell_dst[0] - $cell_src[0], $cell_dst[1] - $cell_src[1]);
    $piece = move_piece($board, $cell_src[0], $cell_src[1], $_POST['direction'], $direction);
    $last_move = json_encode($cell_dst);
  }

  // Verification de la victoire et preparation de la requête
  if (is_rock($piece)) {
    $query = "UPDATE Game SET board=:board, last_move=:last_move, winner=:winner, active_player=:active_player WHERE game_ID=:id";
    $stmt = $db->prepare($query);
    $winner_number = get_winner($board, $cell_dst[0], $cell_dst[1], $_POST['direction']);
    $winner = ($winner_number === $_POST['player_number']) ? $_POST['player'] : $_POST['opponent'];
    $stmt->bindValue(':winner', $winner, PDO::PARAM_STR);
  } else {
    $query = "UPDATE Game SET board=:board, last_move=:last_move, active_player=:active_player WHERE game_ID=:id";
    $stmt = $db->prepare($query);
  }

  // Mise à jour de la base de données
  $stmt->bindValue(':board', json_encode($board), PDO::PARAM_STR);
  $stmt->bindValue(':last_move', $last_move, PDO::PARAM_STR);
  $stmt->bindValue(':active_player', $_POST['opponent'], PDO::PARAM_STR);
  $stmt->bindValue(':id', $_POST['game_id'], PDO::PARAM_INT);
  $stmt->execute();
  // Fermeture de la connexion à la base de données
  $db = null;
  // Redirection vers la page de la partie
  header('Location: ../pages/game_page.php?id=' . $_POST['game_id']);
}

// Fonction pour vérifier si les coordonnées sont valides
function valid_coordinates($row, $col)
{
  return 0 <= $row && $row < DIM && 0 <= $col && $col < DIM;
}

// Fonction pour vérifier si la case est vide
function is_empty($case)
{
  return $case == "";
}

// Fonction pour vérifier si la case est une roche
function is_rock($case)
{
  return $case === "00";
}

// Fonction pour récupérer l'incrémentation de la ligne en fonction de la direction
function get_inc_row($direction)
{
  if ($direction % 2 == 0) return $direction - 1;
  else return 0;
}

// Fonction pour récupérer l'incrémentation de la colonne en fonction de la direction
function get_inc_col($direction)
{
  if ($direction % 2 == 0) return 0;
  else return $direction - 2;
}

// Fonction pour calculer la direction à partir de l'incrémentation de la ligne et de la colonne
function get_direction($inc_row, $inc_col)
{
  if ($inc_row == 0) return $inc_col + 2;
  else return $inc_row + 1;
}

// Fonction pour ajouter une pièce dans le plateau
// Elle retourne l'eventuel pièce retirée
function add_piece(&$board, $row, $col, $player, $direction)
{
  $piece = "";
  // Ajoute si les coordonnées sont valides
  if (valid_coordinates($row, $col)) {
    // Si la case n'est pas vide, on deplace la pièce dans la direction
    if (!is_empty($board[$row][$col])) {
      $piece = move_piece($board, $row, $col, $board[$row][$col][1], $direction);
    }
    $board[$row][$col] = strval($player) . strval($direction);
  }
  return $piece;
}

// Fonction pour déplacer une pièce dans le plateau
// Elle retourne l'eventuel pièce retirée
function move_piece(&$board, $row, $col, $piece_direction, $direction)
{
  $inc_row = get_inc_row($direction);
  $inc_col = get_inc_col($direction);
  // Si la case suivante est vide, on deplace la pièce en prenant en compte la direction à destination
  if (is_empty($board[$row + $inc_row][$col + $inc_col])) {
    $board[$row + $inc_row][$col + $inc_col] = $board[$row][$col][0] . $piece_direction;
    $board[$row][$col] = "";
    return "";
  }
  $piece = "";
  // Tant que les coordonnées sont valides et que la case n'est pas vide
  while (valid_coordinates($row, $col) && !is_empty($board[$row][$col])) {
    // On deplace la pièce dans la direction
    $tmp = $board[$row][$col];
    $board[$row][$col] = $piece;
    $piece = $tmp;
    $row += $inc_row;
    $col += $inc_col;
  }
  // Si la dernière case est vide, on ajoute la pièce
  if (valid_coordinates($row, $col)) {
    $board[$row][$col] = $piece;
    $piece = "";
  }
  return $piece;
}

// Fonction pour recuperer le numero du gagnant
function get_winner($board, $row, $col, $direction)
{
  $inc_row = get_inc_row($direction);
  $inc_col = get_inc_col($direction);
  $winner = "";
  while (valid_coordinates($row, $col)) {
    if (!is_rock($board[$row][$col])) {
      $winner = $board[$row][$col][0];
    }
    $row += $inc_row;
    $col += $inc_col;
  }
  return $winner;
}
