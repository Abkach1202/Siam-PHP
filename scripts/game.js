// Récupérer le canvas et le contexte 2D
const canvas = document.getElementById("game_canvas");
const ctx = canvas.getContext("2d");

// Fonction pour vérifier si l'utilisateur existe déjà
function checkUser(row, col) {
  var datas = new FormData();
  datas.append("row", row);
  datas.append("col", col);
  var requestOptions = {
    method: "POST",
    body: datas,
  };
  return fetch(
    "http://localhost:8080/api/projet-siam/check_login.php",
    requestOptions
  )
    .then((response) => response.text())
    .then((response) => {
      // Si l'utilisateur et le mode de passe ne correspondent pas, on change la couleur de la bordure du champ
      if (response == "false") {
        alert("L'utilisateur et le mot de de passe ne correspondent pas !");
        var input = document.getElementById("game_canvas");
        input.style.borderColor = "green";
        return false;
      } else {
        return true;
      }
    })
    .catch((error) => {
      console.log(error);
      return false;
    });
}

// Ecouteur d'événement pour la page
document.addEventListener("DOMContentLoaded", function () {
  // Gestionnaire d'événements pour détecter les clics sur le canvas
  canvas.addEventListener("click", function (event) {
    // Récupérer les coordonnées du clic
    const mouseX = event.clientX - canvas.getBoundingClientRect().left;
    const mouseY = event.clientY - canvas.getBoundingClientRect().top;

    // Calculer les indices de la case correspondante
    const cellSize = 80; // Taille d'une case
    const row = Math.floor(mouseY / cellSize);
    const col = Math.floor(mouseX / cellSize);

    checkUser(row, col);
})});
