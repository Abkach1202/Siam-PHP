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