<?php
// Création ou récupération de la session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil Siam</title>
  <link rel="stylesheet" href="../styles/home.css">
</head>

<body>
  <header>
    <h1>Projet Siam</h1>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Page d'accueil</h1>
  </header>
  <section>
    <div>
      <h2 class="titles">Présentation</h2>
      <p>Le jeu de Siam est un jeu de société pour deux joueurs. Il a été créé par Bruno Cathala et Bruno Faidutti en 2005. Le jeu se joue sur un plateau de 5x5 cases. Chaque joueur possède 5 éléphants et 1 montagne. Le but du jeu est de pousser la montagne adverse hors du plateau ou de placer un de ses éléphants sur la montagne adverse.</p>
      <button id="rules">Voir les règles</button>
    </div>
    <div>
      <h2 class="titles">Parties à jouer<button class="viewAll">Voir tout</button></h2>
    </div>
    <div>
      <h2 class="titles">Parties à rejoindre<button class="viewAll">Voir tout</button></h2>
    </div>
    <div>
      <h2 class="titles">Gestion des parties</h2>
      <div id="manageGames">
        <form>
          <select name="player">
            <option value="player1">Joueur 1</option>
            <option value="player2">Joueur 2</option>
          </select>
          <button type="submit">Creer une partie</button>
        </form>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
          echo "<button class='redButton'>Supprimer une partie</button>";
        } ?>
      </div>
    </div>
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
        echo "<button class='redButton'>Deconnexion</button>";
      } ?>
    </div>
  </aside>
</body>

</html>