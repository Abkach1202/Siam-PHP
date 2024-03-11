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
  <title>régles du Siam</title>
  <link rel="stylesheet" href="../styles/style2.css">
</head>

<body>
  <header>
    <a href="home.php"><img src="../images/siam.jpeg" alt="image du jeu Siam"></a>
    <h1>Un jeu de société pour deux joueurs</h1>
    <h1>Page d'accueil</h1>
  </header>
  <section>
    <h2>Siam</h2>
    <h3>MATERIEL</h3>
    <ul>
      <li>Un plateau de jeu en bois</li>
      <li>5 éléphants</li>
      <li>5 rhinocéros</li>
      <li>3 blocs de rochers</li>
      <li>5 feutrines à appliquer sous le plateau, 1 au centre et 1 sous chaque angle</li>
      <li>La règle de jeu</li>
    </ul>
    <p>Nous sommes au Royaume de SIAM, jadis véritable paradis terrestre, terre d'immensité où éléphants et rhinocéros vivaient en paix depuis des siècles. Un jour la terre se mit à trembler et SIAM fut alors réduite à trois régions entourées de gigantesques montagnes. Depuis éléphants et rhinocéros n'ont plus assez d'espace pour vivre ; ces deux espèces d'une force incroyable vont alors se livrer à une lutte sans merci pour régner sans partage sur deux territoires ...</p>
    <h3>BUT DU JEU</h3>
    <p>Après avoir choisi votre animal, être le premier à sortir une région montagneuse (bloc de rochers) à l'extérieur du plateau.</p>
    <h3>COMMENT JOUER</h3>
    <p>Chaque joueur choisit son animal. Les joueurs joueront à tour de rôle.</p>
    <p>Au début du jeu les animaux sont disposés à l'extérieur du plateau et les blocs de rochers au centre du plateau(fig.1). Les éléphants blancs, animaux sacrés dans le royaume de SIAM commenceront à jouer. Les joueurs ne pourront jouer à chaque tour de jeu qu'un seul de leur animal et ne faire qu'une des 5 actions suivantes :</p>
    <ul>
      <li>Entrer un de ses animaux sur le plateau</li>
      <li>Se déplacer sur une case libre</li>
      <li>Changer l'orientation de son animal sans changer de case</li>
      <li>Sortir un de ses animaux disposés sur une case extérieure</li>
      <li>Se déplacer en poussant d'autres pièces disposées sur le plateau</li>
    </ul>
    <h4>Entrer un de ses animaux sur le plateau</h4>
    <p>Vous devez entrer un de vos animaux par l'une des cases extérieures, cases surlignées en rouge à la fig. 2. Deux cas peuvent se présenter :</p>
    <ul>
      <li>la case est libre et dans ce cas vous pouvez placer votre animal en l'orientant dans la direction de votre choix</li>
      <li>la case est occupée et vous pouvez sous certaines conditions rentrer en effectuant un poussée (voir le chapitre « se déplacer en poussant »).</li>
    </ul>
    <h4>Se déplacer sur une case libre</h4>
    <p>Vous ne pouvez vous déplacer que d'une seule case et de façon orthogonale (déplacement en diagonale interdit). L'orientation de votre animal n'importe pas sur la direction de votre déplacement. Tout en vous déplaçant, vous pouvez à votre guise changer l'orientation de votre animal.</p>
    <h4>Changer l'orientation de son animal sans chanter de case</h4>
    <p>Vous pouvez changer l'orientation de votre animal sur sa case, ce coup compte comme un tour de jeu.</p>
    <h4>Sortir un de ses animaux disposé sur une case extérieure</h4>
    <p>Vous pouvez sortir du plateau et à tout moment un de vos animaux disposé sur une case extérieure (cases surlignées en rouge à la fig. 2), ce coup compte comme un tour de jeu. L'animal sorti pourra être réutilisé et revenir sur le plateau à tout moment.</p>
    <h4>Se déplacer en poussant d'autres pièces disposées sur le plateau</h4>
    <p>Lorsque la case ou vous voulez vous rendre est occupée par une pièce (éléphant, rhinocéros ou rochers), vous pouvez sous certaines conditions effectuer une poussée :</p>
    <ul>
      <li>Vous ne pouvez pousser que dans une seule direction : vers l'avant de votre animal.(fig. 3a, 3b)</li>
      <li>Un animal peut pousser un rocher, deux animaux orientés dans la bonne direction peuvent pousser deux rochers, et trois animaux orientés dans la bonne direction peuvent pousser trois rochers(fig. 4a, 4b, 4c).</li>
      <li>Un animal ne peut pousser un autre animal qui lui fait face (peu importe à qui appartient l'animal). En effet, rhinos et éléphants ont la même force de poussée ; pour pouvoir pousser, il faut qu'il y ait donc une majorité d'animaux qui poussent dans la même direction.</li>
    </ul>
    <h4>Précision :</h4> 
    <p>Un de vos animaux peut empêcher votre poussée, un animal adverse peut aider votre poussée(fig. 5a, 5b, 5c).</p>
    <ul>
      <li>Un animal peut pousser autant d'animaux que possible si ceux-ci ne sont pas orientés dans la direction opposée(fig. 6a, 6b).</li>
      <li>Vous pouvez pousser en entrant une pièce sur le plateau(fig. 7a, 7b).</li>
      <li>Pour résoudre des situations de poussée plus compliquées, il suffit de regarder les animaux qui se neutralisent et de voir si ceux qui restent sont en nombre suffisant pour pousser des rochers(fig. 8a, 8b, 8c).</li>
      <li>Lorsqu'un rocher est expulsé la partie est terminée mais attention le gagnant est le joueur qui est le plus proche du rocher et dans le même sens de poussée(fig. 9a, 9b, 9c).</li>
      <li>Un animal expulsé hors du plateau n'est pas éliminé ; il est récupéré et peut être joué à tout moment.</li>
      <li>Pendant une poussée, aucun animal ne peut changer d'orientation.</li>
    </ul>
    <h3>VARIANTES</h3>
    <ul>
      <li>Aucun des 2 joueurs ne pourra jouer un de ses animaux sur les cases indiquées par une croix à la fig. 10 lors de leurs deux premiers coups.</li>
      <li>Vous pouvez limiter à une fois par joueur la sortie d'un animal disposé sur une case extérieure(fig. 2).</li>
    </ul>
    <img src="../images/rules_siam.jpg" alt="régles du jeu Siam">
    <p>AUTHOR : Didier Dhorbait - <a href="http://www.ferti-games.com">www.ferti-games.com</a></p>
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
      echo "<button class='red_buttons' onclick=\"window.location.href='logout.php'\">Deconnexion</button>";
      ?>
    </div>
  </aside>
</body>

</html>