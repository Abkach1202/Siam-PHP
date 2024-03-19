// Fonction pour vérifier si l'utilisateur existe déjà
async function checkUser() {
  var datas = new FormData();
  datas.append("username", document.getElementById("username").value);
  datas.append("password", document.getElementById("password").value);
  var requestOptions = {
    method: "POST",
    body: datas,
  };
  return fetch("../api/check_login.php", requestOptions)
    .then(response => response.text())
    .then(response => {
      // Si l'utilisateur et le mode de passe ne correspondent pas, on change la couleur de la bordure du champ
      if (response == "false") {
        alert("L'utilisateur et le mot de de passe ne correspondent pas !");
        var input = document.getElementById("username");
        input.style.borderColor = "red";
        input = document.getElementById("password");
        input.style.borderColor = "red";
        return false;
      } else {
        return true;
      }
    })
    .catch(error => {
      console.log(error);
      return false;
    });
}

// Fonction pour réinitialiser la couleur des bordures des champs
function resetBorderColor() {
  var input = document.getElementById("username");
  input.style.borderColor = "";
  input = document.getElementById("password");
  input.style.borderColor = "";
}

// Ecouteur d'événement pour la page
document.addEventListener('DOMContentLoaded', function () {
  // Ajout d'un écouteur d'événement pour le formulaire
  var form = document.forms[0];
  form.addEventListener('submit', function (event) {
    // On empêche le formulaire de se soumettre
    event.preventDefault();
    // On réinitialise la couleur des bordures des champs
    resetBorderColor();
    // On vérifie si l'utilisateur existe déjà et si les mots de passe correspondent
    checkUser()
      .then(result => {
        // Si l'utilisateur existe, on soumet le formulaire
        if (result) {
          form.submit();
        }
      })
      .catch(error => console.error(error));
  });
});
