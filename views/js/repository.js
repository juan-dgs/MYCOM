//javascript para seguridad y validacion de contraseñas

function checkPasswordStrength(idForm,txtId,idBar) {
    if($("#" + txtId).length === 0){
        notify("El campo de contraseña no existe ("+txtId+")",3000,"error","top-end");
        console.log("El campo de contraseña no existe ("+txtId+")");
      }


    let password = $("#"+txtId).val();
    let password2 = $("#"+txtId+"2").val();
    let strengthBar = $("#"+idBar);

    let lengthCheck = password.length >= 8;
    let uppercaseCheck = /[A-Z]/.test(password);
    let lowercaseCheck = /[a-z]/.test(password);
    let specialCheck = /[!@#$%^&*]/.test(password);
    


    if($('#'+ idForm +" .validator").length === 0){
      notify("formulario no exise ("+idForm+")",3000,"error","top-end");
      console.log("El formulario no existe("+idForm+")");
    }

    if(password.length !== 0 || password2.length !== 0){
        $('#'+ idForm +" .validator").collapse("show");
    }else{
        $('#'+ idForm +" .validator").collapse("hide");
    }

    if (lengthCheck) {
      $("#"+ idForm +" .length").html("<span class='fa fa-check'></span> Mínimo 8 caracteres");
      $("#"+ idForm +" .length").addClass("text-success").removeClass("text-danger");
    }else{
      $("#"+ idForm +" .length").html("<span class='fa fa-times'></span> Mínimo 8 caracteres");
      $("#"+ idForm +" .length").addClass("text-danger").removeClass("text-success");
    }
    
    if (uppercaseCheck) {
      $("#"+ idForm +" .uppercase").html("<span class='fa fa-check'></span> Al menos una mayúscula");
      $("#"+ idForm +" .uppercase").addClass("text-success").removeClass("text-danger");
    }else{
      $("#"+ idForm +" .uppercase").html("<span class='fa fa-times'></span> Al menos una mayúscula");
      $("#"+ idForm +" .uppercase").addClass("text-danger").removeClass("text-success");
    }
    
    if (lowercaseCheck) {
      $("#"+ idForm +" .lowercase").html("<span class='fa fa-check'></span> Al menos una minúscula");
      $("#"+ idForm +" .lowercase").addClass("text-success").removeClass("text-danger");
    }else{
      $("#"+ idForm +" .lowercase").html("<span class='fa fa-times'></span> Al menos una minúscula");
      $("#"+ idForm +" .lowercase").addClass("text-danger").removeClass("text-success");
    }
    
    if (specialCheck) {
      $("#"+ idForm +" .special").html("<span class='fa fa-check'></span> Al menos un carácter especial (!@#$%^&*)");
      $("#"+ idForm +" .special").addClass("text-success").removeClass("text-danger");
    }else{
      $("#"+ idForm +" .special").html("<span class='fa fa-times'></span> Al menos un carácter especial (!@#$%^&*)");
      $("#"+ idForm +" .special").addClass("text-danger").removeClass("text-success");
    }

    let strength = (lengthCheck + uppercaseCheck + lowercaseCheck + specialCheck);

    if (strength === 4) {
        strengthBar.attr("class","progress-bar strong");
    } else if (strength === 3) {
        strengthBar.attr("class","progress-bar medium");
    } else {
        strengthBar.attr("class","progress-bar weak");
    }
  
    if (password === password2) {
        $("#"+ idForm +" .equal").html("<span class='fa fa-check'></span> Las contraseñas coinciden");
        $("#"+ idForm +" .equal").addClass("text-success").removeClass("text-danger");
    }else{
        $("#"+ idForm +" .equal").html("<span class='fa fa-times'></span> Las contraseñas no coinciden");
        $("#"+ idForm +" .equal").addClass("text-danger").removeClass("text-success");
    }
    
    return strength;
}

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


//funcion para validar correo
function validateEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar correos
  return regex.test(email); // Retorna true si el correo es válido, false si no lo es
}

/*function checkDomain(email) {
  const domain = email.split('@')[1]; // Extrae el dominio del correo
  return new Promise((resolve, reject) => {
      fetch(`https://${domain}`) // Intenta hacer una solicitud al dominio
          .then(response => {
              if (response.status === 200) {
                  resolve(true); // El dominio existe
              } else {
                  resolve(false); // El dominio no existe
              }
          })
          .catch(() => {
              resolve(false); // El dominio no existe o hubo un error
          });
  });
}*/

//funcion para generar contraseña aleatoria
function generarContraseña() {
  const mayusculas = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  const minusculas = "abcdefghijklmnopqrstuvwxyz";
  const numeros = "0123456789";
  const caracteresEspeciales = "!@#$%^&*"; //!@#$%^&*()_+-=[]{}|;:,.<>?";

  // Combinar todos los caracteres posibles
  const todosLosCaracteres = mayusculas + minusculas + numeros + caracteresEspeciales;

  let contraseña = "";
  for (let i = 0; i < 10; i++) {
      // Seleccionar un carácter aleatorio de la cadena combinada
      const caracterAleatorio = todosLosCaracteres.charAt(Math.floor(Math.random() * todosLosCaracteres.length));
      contraseña += caracterAleatorio;
  }

  return contraseña;
}





/*manejo de fechas */
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