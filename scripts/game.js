// Attendre que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function () {
  // Definition des constantes
  const CELL_SIZE = 80;
  const THICKNESS = 3;
  const BOARD_DIM = 5;

  // Récupération des éléments du DOM
  const game_canvas = document.getElementById("game_canvas");
  const player_canvas = document.getElementById("player_canvas");
  const opponent_canvas = document.getElementById("opponent_canvas");
  const cancel_button = document.getElementById("cancel");
  const turn_button = document.getElementById("turn");
  const remove_button = document.getElementById("remove");
  const board = JSON.parse(document.getElementById("board").value);
  const current_player = document.getElementById("current_player").value;
  const active_player = document.getElementById("active_player").value;
  const player_number = document.getElementById("player_number").value;
  const is_admin = document.getElementById("is_admin").value;
  const is_over = document.getElementById("is_over").value;
  const form = document.forms[0];

  // Récupération des éléments modifiables
  var direction = document.getElementById("direction");
  var destination = document.getElementById("destination");
  var source = document.getElementById("source");
  var action = document.getElementById("action");

  // Définition des variables globales
  var destinations = [];
  var clickedCell = [];

  // Fonction pour savoir si le joueur a le droit de jouer
  function isPlayerTurn() {
    return is_over === "0" && (current_player === active_player || is_admin === "1");
  }

  // Fonction pour compter le nombre de pièces d'un joueur sur le plateau
  function countPlayerPieces(player) {
    var count = 0;
    for (let i = 0; i < BOARD_DIM; i++)
      for (let j = 0; j < BOARD_DIM; j++)
        if (board[i][j] !== "" && board[i][j][0] === player) count++;
    return count;
  }

  // Fonction pour dessiner une case du canvas
  function drawCase(row, col, content, ctx) {
    if (content === "") return;
    var image = new Image();
    image.src = "../images/" + content + ".gif";
    image.onload = function () {
      ctx.drawImage(image, col * CELL_SIZE, row * CELL_SIZE, CELL_SIZE, CELL_SIZE);
    }
  }

  // Fonction pour dessiner le plateau de jeu
  function drawGameBoard() {
    for (let i = 0; i < BOARD_DIM; i++)
      for (let j = 0; j < BOARD_DIM; j++)
        drawCase(i, j, board[i][j], game_canvas.getContext("2d"));
  }

  // Fonction pour dessiner le plateau des joueurs
  function drawPlayerBoard(ctx, player, direction) {
    for (let i = 0; i < BOARD_DIM - countPlayerPieces(player); i++)
      drawCase(0, i, player + direction, ctx);
  }

  // Fonction pour colorier une case du canvas
  function colorCase(row, col, color, ctx) {
    // Dessin des bordures
    ctx.fillStyle = color;
    ctx.fillRect(col * CELL_SIZE, row * CELL_SIZE, CELL_SIZE, THICKNESS);
    ctx.fillRect((col + 1) * CELL_SIZE - THICKNESS, row * CELL_SIZE, THICKNESS, CELL_SIZE);
    ctx.fillRect(col * CELL_SIZE, (row + 1) * CELL_SIZE - THICKNESS, CELL_SIZE, THICKNESS);
    ctx.fillRect(col * CELL_SIZE, row * CELL_SIZE, THICKNESS, CELL_SIZE);
  }

  // Fonction pour enlever les couleurs des cases
  function removeColor(row, col, ctx) {
    // Dessin des bordures
    ctx.clearRect(col * CELL_SIZE, row * CELL_SIZE, CELL_SIZE, THICKNESS);
    ctx.clearRect((col + 1) * CELL_SIZE - THICKNESS, row * CELL_SIZE, THICKNESS, CELL_SIZE);
    ctx.clearRect(col * CELL_SIZE, (row + 1) * CELL_SIZE - THICKNESS, CELL_SIZE, THICKNESS);
    ctx.clearRect(col * CELL_SIZE, row * CELL_SIZE, THICKNESS, CELL_SIZE);
  }

  // Fonction pour colorier le dernier coup joué
  function colorLastMove() {
    var last_move = document.getElementById("last_move").value;
    if (last_move === "") return;
    last_move = JSON.parse(last_move);
    colorCase(last_move[0], last_move[1], "#FF0000", game_canvas.getContext("2d"));
  }

  // Fonction pour colorier les cases cliquables sur les plateaux
  function colorSources() {
    // Colorier sur le plateau de jeu
    for (let i = 0; i < BOARD_DIM; i++)
      for (let j = 0; j < BOARD_DIM; j++)
        if (board[i][j] !== "" && board[i][j][0] === player_number)
          colorCase(i, j, "#22BB22", game_canvas.getContext("2d"));
    // Colorier sur le plateau du joueur
    for (let i = 0; i < BOARD_DIM - countPlayerPieces(player_number); i++)
      colorCase(0, i, "#22BB22", player_canvas.getContext("2d"));
    colorLastMove();
  }
  
  // Fonction pour enlever les couleurs des cases cliquables sur les plateaux
  function removeColorSources() {
    // Enleve les couleurs sur le plateau de jeu
    for (let i = 0; i < BOARD_DIM; i++)
      for (let j = 0; j < BOARD_DIM; j++)
        if (board[i][j] !== "" && board[i][j][0] === player_number)
          removeColor(i, j, game_canvas.getContext("2d"));
    // Enleve les couleurs sur le plateau du joueur
    for (let i = 0; i < BOARD_DIM - countPlayerPieces(player_number); i++)
      removeColor(0, i, player_canvas.getContext("2d"));
  }

  // Fonction pour envoyer une requête au serveur
  async function fetchCases(datas) {
    var requestOptions = {
      method: "POST",
      body: datas,
    };
    const response = await fetch("http://localhost:8080/api/check_action.php", requestOptions);
    return response.json();
  }

  // Fonction pour afficher les cases valides pour un deplacement
  async function displayValidMoves(row, col) {
    // Préparation des données à envoyer
    var datas = new FormData();
    datas.append("row", row);
    datas.append("col", col);
    datas.append("action", "move");
    datas.append("board", JSON.stringify(board));
    // Envoi de la requête et récupération des données
    destinations = await fetchCases(datas)
      .then((data) => {
        data.forEach(x => {
          colorCase(x[0], x[1], "#22BB22", game_canvas.getContext("2d"));
        });
        return data;
      });
  }

  // Fonction pour afficher les cases valides pour un ajout
  async function displayValidAdd() {
    // Préparation des données à envoyer
    var datas = new FormData();
    datas.append("action", "add");
    datas.append("board", JSON.stringify(board));
    datas.append("direction", direction.value);
    // Envoi de la requête et récupération des données
    destinations = await fetchCases(datas)
      .then((data) => {
        data.forEach(x => {
          colorCase(x[0], x[1], "#22BB22", game_canvas.getContext("2d"));
        });
        return data;
      });
  }

  // Fonction pour gerer le clic sur le canvas du jeu
  function clickCell(event) {
    // Vérifier si c'est le tour du joueur
    if (!isPlayerTurn()) return;
    const mouseX = event.clientX - this.getBoundingClientRect().left;
    const mouseY = event.clientY - this.getBoundingClientRect().top;
    const row = Math.floor(mouseY / CELL_SIZE);
    const col = Math.floor(mouseX / CELL_SIZE);

    // Si aucune destination n'est définie, on affiche les déplacements possibles
    if (destinations.length === 0 && board[row][col] !== "" && board[row][col][0] === player_number) {
      removeColorSources();
      displayValidMoves(row, col);
      direction.value = board[row][col][1];
      clickedCell = [row, col];
      cancel_button.disabled = false;
      turn_button.disabled = false;
      if (row === 0 || row === BOARD_DIM - 1 || col === 0 || col === BOARD_DIM - 1)
        remove_button.disabled = false;
    }
    // Sinon, on vérifie si la case cliquée est une destination et on soumet le formulaire
    else if (destinations.length !== 0 && destinations.some(x => x[0] === row && x[1] === col)) {
      destination.value = JSON.stringify([row, col]);
      if (clickedCell.length !== 0) {
        source.value = JSON.stringify(clickedCell);
        action.value = "move";
      } else {
        action.value = "add";
      }
      console.log(action, source, destination);
      form.submit();
    }
  }

  // Fonction pour gerer le clic sur le canvas du joueur
  function clickPlayer(event) {
    // Si des destinations sont déjà définies ou si ce n'est pas le tour du joueur, on ne fait rien
    if (destinations.length !== 0 || !isPlayerTurn()) return;
    const mouseX = event.clientX - this.getBoundingClientRect().left;
    const col = Math.floor(mouseX / CELL_SIZE);
    // On vérifie si on doit considérer le clic
    if (col < BOARD_DIM - countPlayerPieces(player_number)) {
      removeColorSources();
      displayValidAdd();
      cancel_button.disabled = false;
    }
  }

  // Fonction pour tourner une pièce
  function turnPiece(event) {
    event.preventDefault();
    // On ne fait rien si la direction est la même
    if (board[clickedCell[0]][clickedCell[1]][1] === direction) {
      alert("La pièce est déjà dans cette direction");
      return;
    }
    source.value = JSON.stringify(clickedCell);
    action.value = "turn";
    form.submit();
  }

  // Fonction pour enlever une pièce
  function removePiece(event) {
    event.preventDefault();
    source.value = JSON.stringify(clickedCell);
    action.value = "remove";
    form.submit();
  }

  // Fonction pour annuler la sélection
  function cancelSelection() {
    destinations.forEach(element => {
      removeColor(element[0], element[1], game_canvas.getContext("2d"));
    });
    destinations = [];
    clickedCell = [];
    colorSources();
    cancel_button.disabled = true;
  }

  // Fonction pour gérer le changement de direction
  function direction_changed() {
    if (!cancel_button.disabled && clickedCell.length === 0) {
      destinations.forEach(x => {
        removeColor(x[0], x[1], game_canvas.getContext("2d"));
      });
      destinations = [];
      displayValidAdd();
    }
  }

  // Dessin du plateau de jeu
  drawGameBoard();
  // Dessin des plateau des joueur
  drawPlayerBoard(player_canvas.getContext("2d"), player_number, "0");
  drawPlayerBoard(opponent_canvas.getContext("2d"), String(player_number % 2 + 1), "2");
  // Mise en évicence des cases cliquables
  if (isPlayerTurn()) colorSources();
  else colorLastMove();

  // Gestionnaire d'événements pour les clics
  game_canvas.addEventListener("click", clickCell);
  player_canvas.addEventListener("click", clickPlayer);
  cancel_button.addEventListener("click", cancelSelection);
  turn_button.addEventListener("click", turnPiece);
  remove_button.addEventListener("click", removePiece);
  direction.addEventListener("change", direction_changed);
});