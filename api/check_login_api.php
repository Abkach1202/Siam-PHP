<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
  // Connexion à la base de données SQLite
  try {
    $db = new PDO('sqlite:../datas/data_base.db');
  } catch (PDOException $e) {
    echo "Échec de la connexion", $e->getMessage();
    $db = null;
    exit();
  }
  // Préparation et exécution de la requête
  $query = "SELECT * FROM User WHERE username = :username";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':username', $_POST['username'], PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  // Renvoi de la réponse
  if ($user != FALSE && password_verify($_POST['password'], $user['password'])) {
    echo "true";
  } else {
    echo "false";
  }
  // Fermeture de la connexion à la base de données
  $db = null;
}
?>