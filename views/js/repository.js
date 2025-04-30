// Este archivo contiene funciones para manejar la interfaz de usuario y la validación de formularios en aplicación web.
//funcion para notificaciones
function notify(text,time,status,position) {
    Swal.fire({
        position: position,
        icon: status,
        title: text,
        showConfirmButton: false,
        timer: time
      });
}

//funcion para notificaciones con confirmacion
function notifyConfirm(question,text,status,fn){

    Swal.fire({
        title: question,
        text: text,
        icon: status,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {    
          eval(fn);
        }
      });
}



//manejo de fechas //
// Esta función formatea una fecha en el formato YYYY-MM-DD. Si no se proporciona una fecha, se utiliza la fecha actual.
// La función también puede sumar días a una fecha dada y devolver la nueva fecha formateada.
function formatDate(date = new Date()) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Meses van de 0-11
  const day = String(date.getDate()).padStart(2, '0');

  return `${year}-${month}-${day}`;
}

function sumarDias(fecha, dias) {
  const nuevaFecha = new Date(fecha); // Clonar la fecha original para no modificarla
  nuevaFecha.setDate(nuevaFecha.getDate() + dias);

  const year = nuevaFecha.getFullYear();
  const month = String(nuevaFecha.getMonth() + 1).padStart(2, '0'); // Meses van de 0-11
  const day = String(nuevaFecha.getDate()).padStart(2, '0');

  return `${year}-${month}-${day}`;
}

function formatearNumero(valor, decimales = 2, separadorMiles = true) {
  // Convertir a número y validar
  const numero = Number(valor);
  
  // Manejar casos especiales
  if (isNaN(numero)) return 'NaN';
  if (!isFinite(numero)) return numero > 0 ? '∞' : '-∞';
  
  // Redondear con precisión (manejo correcto de negativos)
  const factor = Math.pow(10, decimales);
  const redondeado = Math.round((Math.abs(numero) * factor)) / factor * Math.sign(numero);
  
  // Opciones de formato
  const opciones = {
      minimumFractionDigits: decimales,
      maximumFractionDigits: decimales,
      useGrouping: separadorMiles
  }
  
  return redondeado.toLocaleString(undefined, opciones);

}