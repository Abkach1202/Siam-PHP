function validateForm() {
  var password = document.getElementById("password").value;
  var confirm_password = document.getElementById("confirm_password").value;

  if (password != confirm_password) {
    alert("Les mots de passe ne correspondent pas");
    return false;
  }
  return true;
}
