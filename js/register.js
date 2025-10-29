// Gestiona la visualización de mensajes de error en la página de registro. Lee los parámetros de la URL y muestra un mensaje específico según el error ocurrido durante el proceso de registro.
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get("error");
const errorDiv = document.getElementById("error-message");

if (error === "campos_vacios") {
  errorDiv.textContent = "Por favor complete todos los campos";
} else if (error === "contrasena_corta") {
  errorDiv.textContent = "La contraseña debe tener al menos 6 caracteres";
} else if (error === "contrasenas_no_coinciden") {
  errorDiv.textContent = "Las contraseñas no coinciden";
} else if (error === "usuario_existe") {
  errorDiv.textContent = "El usuario ya existe, elige otro";
}