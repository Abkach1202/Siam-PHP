<?php
// Création ou récupération de la session
session_start();
// Recuperation des parties à jouer et à rejoindre
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Siam</title>
  <link rel="stylesheet" href="../styles/style2.css">
  <style>
    section {
      align-items: center;
      padding: 20px;
      row-gap: 20px;
    }

    #game_canvas {
      background-image: url("../images/plateauu.jpg");
      background-size: cover;
      border: 2px solid black;
    }

    #piece_canvas {
      background-image: url("../images/plateauu.jpg");
      background-size: cover;
      border: 2px solid black;
    }

    #buttons {
      display: flex;
      flex-direction: column;
      /* Pour aligner les éléments en colonne */
      align-items: flex-end;
      /* Pour aligner les éléments à droite */
      gap: 10px;
      margin-top: 10px;
      width: 30%;
    }

    label {
      
      width: 100%;
    }

    #buttons select,
    #buttons button {
      width: 100%;
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


    <canvas id="game_canvas" width="400" height="400"></canvas>
    <canvas id="piece_canvas" width="400" height="80"></canvas>

    <div id="buttons">
      <label for="direction">Direction</label>
      <select id="directionSelect">
        <option value="haut">Haut</option>
        <option value="bas">Bas</option>
        <option value="gauche">Gauche</option>
        <option value="droite">Droite</option>
      </select>

      <label for="case">Case</label>
      <select id="caseSelect">

      </select>
      <button onclick="validateChoice()">Valider</button>
    </div>

  </section>
  <script src="../scripts/game.js"></script>
  <aside>
    <button onclick="window.location.href='account.php'">Compte</button>
    <div>
      <?php
      echo "<p><strong>Pseudo:</strong> {$_SESSION['username']}</p>";
      echo "<p><strong>Nom:</strong> {$_SESSION['last_name']}</p>";
      echo "<p><strong>Prénom:</strong> {$_SESSION['first_name']}</p>";
      echo "<p><strong>Email:</strong> {$_SESSION['email']}</p>";
      echo "<p><strong>Date d'inscription:</strong> {$_SESSION['registration_date']}</p>";
      echo "<button class='red_buttons' onclick=\"window.location.href='logout.php'\">Deconnexion</button>";
      ?>
    </div>
  </aside>
</body>

</html>