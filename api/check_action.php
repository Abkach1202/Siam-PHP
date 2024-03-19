<?php
// Constante DIM pour la taille du plateau
define('DIM', 5);

// Le traitement des requêtes fetch
if (isset($_POST['action'])) {
  // On recupère le plateau, le joueur actuel et le joueur actif
  $board = json_decode($_POST['board']);
  // Si l'action est d'ajouter une pièce
  if ($_POST['action'] == 'add') {
    $valid_cases = array();
    // On parcours les bords du plateau pour garder que celles qui sont valides
    for ($i = 0; $i < DIM; $i++) {
      for ($j = 0; $j < DIM; $j++) {
        if (check_add($board, $i, $j, intval($_POST['direction']))) {
          $valid_cases[] = array($i, $j);
        }
      }
    }
    echo json_encode($valid_cases);
  }
  // Si l'action est de déplacer une pièce
  else if ($_POST['action'] == 'move') {
    $valid_cases = array();
    $row = $_POST['row'];
    $col = $_POST['col'];
    // On regarde dans les 4 directions pour garder que celles qui sont valides
    for ($i = 0; $i < 4; $i++) {
      if (check_move($board, $row, $col, $i)) {
        $valid_cases[] = array($row + get_inc_row($i), $col + get_inc_col($i));
      }
    }
    echo json_encode($valid_cases);
  }
}

// Fonction pour vérifier si les coordonnées sont valides
function valid_coordinates($row, $col)
{
  return 0 <= $row && $row < DIM && 0 <= $col && $col < DIM;
}

// Fonction pour vérifier si la case est vide
function is_empty($case)
{
  return $case === "";
}

// Fonction pour vérifier si la case est une roche
function is_rock($case)
{
  return $case === "00";
}

// Fonction pour récupérer le numéro de joueur
function get_player($case)
{
  return intval($case[0]);
}

// Fonction pour récupérer la direction de la pièce
function get_direction($case)
{
  return intval($case[1]);
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

// Fonction pour verifier si une pièce peut être poussée dans une direction
function check_push($board, $row, $col, $direction)
{
  $inc_row = get_inc_row($direction);
  $inc_col = get_inc_col($direction);
  $inverse_direction = ($direction + 2) % 4;
  $force = 1;
  $rocks = 0;
  // Tant que les coordonnées sont valides et que la case n'est pas vide
  while (valid_coordinates($row, $col) && !is_empty($board[$row][$col])) {
    // On met à jour la force et le nombre de roches
    if (is_rock($board[$row][$col])) {
      $rocks++;
    } else if (get_direction($board[$row][$col]) == $direction) {
      $force++;
    } else if (get_direction($board[$row][$col]) == $inverse_direction) {
      $force--;
    }
    // Faux si la force est négative ou si le nombre de roches dépasse la force
    if ($force <= 0 || $rocks > $force) return false;
    // Prochaine itération
    $row += $inc_row;
    $col += $inc_col;
  }
  return true;
}

// Fonction pour vérifier si la pièce est dans la bonne direction lors de l'ajout
function check_direction($row, $col, $piece_direction)
{
  if ($row === 0 && $piece_direction === 2) return true;
  if ($col === 0 && $piece_direction === 3) return true;
  if ($row === DIM - 1 && $piece_direction === 0) return true;
  if ($col === DIM - 1 && $piece_direction === 1) return true;
  return false;
}

// Fonction pour vérifier si on peut ajouter une pièce dans le plateau
function check_add($board, $row, $col, $direction)
{
  // Faux si la case est hors du plateau
  if (!valid_coordinates($row, $col)) return false;
  // Faux si la case ne fait pas partie du bord du plateau
  if ($row !== 0 && $row !== DIM - 1 && $col !== 0 && $col !== DIM - 1) return false;
  // Faux si il y'a une pièce au bord et que on ne peut pas le pousser
  if (!is_empty($board[$row][$col]) && (!check_direction($row, $col, $direction)
    || !check_push($board, $row, $col, $direction))) return false;
  return true;
}

// Fonction pour vérifier si on peut pousser une pièce dans une direction
function check_move($board, $row, $col, $direction)
{
  // Faux si la case est hors du plateau
  if (!valid_coordinates($row, $col)) return false;
  $piece_direction = get_direction($board[$row][$col]);
  $next_row = $row + get_inc_row($direction);
  $next_col = $col + get_inc_col($direction);
  if (!valid_coordinates($next_row, $next_col)) return false;
  if (!is_empty($board[$next_row][$next_col])) {
    // Faux si le pousseur ne regarde pas la meme direction
    if ($piece_direction !== $direction) return false;
    // Faux si il n'y a pas assez de force pour pousser
    if (!check_push($board, $next_row, $next_col, $direction)) return false;
  }
  return true;
}
