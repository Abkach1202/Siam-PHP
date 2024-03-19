// Fonction pour vérifier si l'utilisateur existe déjà
async function checkUser() {
  var data = new FormData();
  data.append("username", document.getElementById("username").value);
  var requestOptions = {
    method: "POST",
    body: data,
  };
  return fetch("../api/check_user.php", requestOptions)
    .then(response => response.text())
    .then(response => {
      // Si l'utilisateur existe déjà, on change la couleur de la bordure du champ
      console.log(response);
      if (response == "true") {
        alert("Cet utilisateur existe déjà !");
        var input = document.getElementById("username");
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

// Fonction pour vérifier si les mots de passe correspondent
function checkPasswords() {
  var password1 = document.getElementById("password").value;
  var password2 = document.getElementById("confirm_password").value;
  // Si les mots de passe ne correspondent pas, on change la couleur des bordures des champs
  if (password1 != password2) {
    var input;
    alert("Les mots de passe ne correspondent pas !");
    input = document.getElementById("password");
    input.style.borderColor = "red";
    input = document.getElementById("confirm_password");
    input.style.borderColor = "red";
    return false;
  }
  return true;
}

// Fonction pour réinitialiser la couleur des bordures des champs
function resetBorderColor() {
  var input = document.getElementById("username");
  input.style.borderColor = "";
  input = document.getElementById("password");
  input.style.borderColor = "";
  input = document.getElementById("confirm_password");
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
    Promise.all([checkUser(), checkPasswords()])
      .then(results => {
        // Si tous les résultats sont vrais, on soumet le formulaire
        if (results.every(result => result)) {
          form.submit();
        }
      })
      .catch(error => console.error(error));
  });
});
