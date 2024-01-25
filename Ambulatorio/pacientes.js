function validarFormularios() {
  // Obtener el valor de la fecha del formulario
  var fechaInput = document.getElementsByName("fecha")[0];
  var fechaValue = fechaInput.value;

  // Validar si la fecha está vacía
  if (!fechaValue) {
    alert("Por favor, seleccione una fecha.");
    return false;
  }

  // Obtener la fecha actual en el formato YYYY-MM-DD
  var fechaActual = new Date().toISOString().split("T")[0];

  // Validar si la fecha es anterior al día de hoy
  if (fechaValue < fechaActual) {
    alert(
      "Fecha no válida. Por favor, elija una fecha igual o posterior al día de hoy."
    );
    return false;
  }

  // Obtener el día de la semana
  var fechaSeleccionada = new Date(fechaValue);
  var diaSemana = fechaSeleccionada.getDay();

  // Validar si la fecha es un fin de semana (Sábado o Domingo)
  if (diaSemana === 0 || diaSemana === 6) {
    alert(
      "Por favor, elija un día laborable. Las citas no están disponibles en fin de semana."
    );
    return false;
  }

  // Obtener la fecha actual más 30 días en el formato YYYY-MM-DD
  var fechaLimite = new Date();
  fechaLimite.setDate(fechaLimite.getDate() + 30);
  var fechaLimiteFormato = fechaLimite.toISOString().split("T")[0];

  // Validar si la fecha es más tarde de 30 días en el futuro
  if (fechaValue > fechaLimiteFormato) {
    alert(
      "Tan malo no estarás. Pide una fecha como máximo 30 días en el futuro."
    );
    return false;
  }

  // Si todas las validaciones pasan, el formulario se enviará
  return true;
}
