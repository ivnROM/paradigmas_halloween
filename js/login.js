// Gestiona la visualización de mensajes de error en la página de login. Lee los parámetros de la URL y muestra un mensaje correspondiente si el login falla.
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get("error");
const errorDiv = document.getElementById("error-message");
if (error === "campos_vacios") {
  errorDiv.textContent = "Por favor complete todos los campos";
} else if (error === "credenciales_invalidas") {
  errorDiv.textContent = "Usuario o contraseña incorrectos";
}