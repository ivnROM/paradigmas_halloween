// Maneja el envío de votos de forma asíncrona (AJAX) en la página principal. Captura el evento 'submit' de cada formulario de votación, envía los datos a votar.php y actualiza el contador de votos en la página sin recargar.
document.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll(".votar-form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = new FormData(this);

      const card = this.closest(".costume-card");
      const voteCountElement = card.querySelector(".votos-conteo");

      fetch("votar.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          alert(data.message);

          if (data.status === "success") {
            voteCountElement.textContent = "Votos: " + data.nuevos_votos;
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Ocurrió un error inesperado al procesar tu voto.");
        });
    });
  });
});
