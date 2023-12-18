// Load table
function loadTable() {
  const tableBody = document.querySelector("#tbody");

  fetch("http://localhost/template_php/user/index")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.length > 0) {
        // Crea un fragmento de documento para mejorar el rendimiento al agregar múltiples filas
        const fragment = document.createDocumentFragment();

        data.forEach((item) => {
          // Crea una fila con 5 celdas en su contenido
          const row = document.createElement("tr");

          for (let i = 0; i < 5; i++) {
            const cell = document.createElement("td");
            cell.innerHTML = item[i];
            row.appendChild(cell);
          }

          // Agrega la fila al fragmento
          fragment.appendChild(row);
        });
        // Agrega todas las filas al tbody de la tabla
        tableBody.appendChild(fragment);
      }
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
}

document.addEventListener("DOMContentLoaded", function (e) {
  loadTable();
});
