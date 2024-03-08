// Récupérer le formulaire
var form = document.querySelector('form');

// Ajouter un gestionnaire d'événements pour l'événement submit
form.addEventListener('submit', function(event) {
  event.preventDefault();
  validateForm();
});

// Définition de la fonction validateForm()
function validateForm() {
  var password = document.getElementById("password").value;
  var confirm_password = document.getElementById("confirm_password").value;

  if (password != confirm_password) {
    alert("Les mots de passe ne correspondent pas");

    var passwordInput = document.getElementById("password");
    passwordInput.style.borderColor = "red";

    var confirm_passwordInput = document.getElementById("confirm_password");
    confirm_passwordInput.style.borderColor = "red";

    return false;
  }
  return true;
}
