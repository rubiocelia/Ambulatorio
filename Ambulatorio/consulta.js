function agregarMedicacion() {
  // Validación del campo de diagnóstico
  var diagnostico = document.getElementsByName("diagnostico")[0].value;
  if (diagnostico.trim() === "") {
    alert("Por favor, ingrese un diagnóstico.");
    return false;
  }

  // Validación del campo de cantidad
  var cantidad = document.getElementsByName("cantidad")[0].value;
  if (cantidad.trim() === "") {
    //trim se usa para eliminar los espacios en blanco
    alert("Por favor, ingrese una cantidad.");
    return false;
  }

  // Validación del campo de frecuencia
  var frecuencia = document.getElementsByName("frecuencia")[0].value;
  if (frecuencia.trim() === "") {
    alert("Por favor, ingrese una frecuencia.");
    return false;
  }

  // Validación del campo de número de días
  var dias = document.getElementsByName("dias")[0].value;
  if (dias.trim() === "" || isNaN(dias)) {
    alert("Por favor, ingrese un número de días válido.");
    return false;
  }

  // Validación del campo de PDF
  var archivoPDF = document.getElementsByName("archivoPDF")[0].value;
  if (archivoPDF.trim() !== "") {
    var extension = archivoPDF.split(".").pop().toLowerCase();
    if (extension !== "pdf") {
      alert("Por favor, seleccione un archivo PDF válido.");
      return false;
    }
  }

  return true;
}

function validarDerivacion() {
  // Validación del campo de especialista
  var especialista = document.getElementsByName("especialista")[0].value;
  if (especialista.trim() === "") {
    alert("Por favor, seleccione un especialista.");
    return false;
  }

  // Validación del campo de fecha
  var fecha = document.getElementsByName("fecha")[0].value;
  if (fecha.trim() === "") {
    alert("Por favor, seleccione una fecha.");
    return false;
  }

  // Obtener el día de la semana
  var fechaSeleccionada = new Date(fecha);
  var diaSemana = fechaSeleccionada.getDay();

  // Validar si la fecha es un fin de semana (Sábado o Domingo)
  if (diaSemana === 0 || diaSemana === 6) {
    alert(
      "Por favor, elija un día laborable. Las citas no están disponibles en fin de semana."
    );
    return false;
  }

  // Verificación de la fecha dentro de los próximos tres meses
  var fechaSeleccionada = new Date(fecha);
  var fechaActual = new Date();
  var tresMesesDespues = new Date();
  tresMesesDespues.setMonth(tresMesesDespues.getMonth() + 3);

  if (fechaSeleccionada < fechaActual || fechaSeleccionada > tresMesesDespues) {
    alert("La fecha de la cita debe estar dentro de los próximos tres meses.");
    return false;
  }

  return true;
}
