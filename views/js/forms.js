//userform.js
// Este archivo contiene funciones para validar formularios, generar contraseñas y manejar eventos relacionados con la seguridad de las contraseñas.

document.addEventListener("DOMContentLoaded", function () {
    const camposSinMayusculas = ["usuario", "correo","usuarioEdit", "correoEdit"]; // Campos que convierten a minúsculas
  
    const regexSoloLetras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/; // Solo letras y espacios
    const regexCorreoClave = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s@.!?#$%&*+-/=^_`{|}~]*$/; // Letras, números, espacios y caracteres especiales
  
    const configCampos = {
        nombres: { regex: regexSoloLetras, maxLength: 45 }, // Solo letras y espacios
        apellido_p: { regex: regexSoloLetras, maxLength: 45 }, // Solo letras y espacios
        apellido_m: { regex: regexSoloLetras, maxLength: 45 }, // Solo letras y espacios
        usuario: { regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s._]*$/, maxLength: 20 }, // Letras, números y espacios
        correo: { regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s@._]*$/, maxLength: 100 }, // Letras, números, espacios, "@" y "."
        clave: { regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s@.!?#$%&*+-/=^_`{|}~]*$/, maxLength: 100 }, // Letras, números, espacios y caracteres especiales
        clave2: { regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s@.!?#$%&*+-/=^_`{|}~]*$/, maxLength: 100 }, // Letras, números, espacios y caracteres especiales
        nombresEdit: { regex: regexSoloLetras, maxLength: 45 }, // Solo letras y espacios
        apellido_pEdit: { regex: regexSoloLetras, maxLength: 45 }, // Solo letras y espacios
        apellido_mEdit: { regex: regexSoloLetras, maxLength: 45 }, // Solo letras y espacios
        usuarioEdit: { regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s._]*$/, maxLength: 20 }, // Letras, números y espacios
        correoEdit: { regex: /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s@._]*$/, maxLength: 100 }, // Letras, números, espacios, "@" y "."
    };
  
    // Aplicar conversión a minúsculas solo a los campos correspondientes
    camposSinMayusculas.forEach(campo => {
        const input = document.getElementById(campo);
        if (input) {
            input.addEventListener("input", function () {
                input.value = input.value.toLowerCase(); // Convierte a minúsculas
            });
        }
    });
  
    // Validar todos los campos
    for (const [campo, config] of Object.entries(configCampos)) {
        const input = document.getElementById(campo);
        if (input) {
            // Validar caracteres permitidos
            input.addEventListener("input", function () {
                if (!config.regex.test(input.value)) {
                    input.value = input.value.substr(0,input.value.length-1); 
                    notify(`Este campo solo permite: ${config.regex === regexSoloLetras ? "letras y espacios" : "letras, números y caracteres especiales"}`, 1500, "warning", "top-end");
                }
            });
  
            // Validar longitud máxima
            input.addEventListener("input", function () {
                if (input.value.length > config.maxLength) {
                    input.value = input.value.slice(0, config.maxLength); // Trunca el texto
                    notify(`Este campo no puede exceder ${config.maxLength} caracteres`, 1500, "warning", "top-end");
                }
            });
        }
    }
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    const campoClave = document.getElementById("clave");
    const campoClave2 = document.getElementById("clave2");
    const botonGenerarContraseña = document.getElementById("generarContraseña");
    const botonMostrarContraseña = document.getElementById("mostrarContraseña");
    const botonMostrarContraseña2 = document.getElementById("mostrarContraseña2");
  
    let contraseñaGenerada =false; // Para rastrear si la contraseña fue generada automáticamente
  
    // Botón para generar contraseña
    if (botonGenerarContraseña) {
        botonGenerarContraseña.addEventListener("click", function () {
            const contraseña = generarContraseña();
            if (campoClave) {
                campoClave.value = contraseña; // Rellenar el campo de contraseña
                campoClave2.value = contraseña; // Rellenar el campo de contraseña
                contraseñaGenerada = true; // Marcar que la contraseña fue generada
                campoClave.type = "text"; // Mostrar la contraseña generada
                campoClave2.type = "text"; // Mostrar la contraseña generada
                botonMostrarContraseña.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                checkPasswordStrength('userForm','clave','password-strength-bar');
                notify("Contraseña generada correctamente", 1500, "success", "top-end");
            }
        });
    }
  
    // Botón para mostrar/ocultar contraseña
    if (botonMostrarContraseña) {
        botonMostrarContraseña.addEventListener("click", function () {
            if (campoClave) {
                // Alternar entre tipo "password" y "text"
                if (campoClave.type === "password") {
                    campoClave.type = "text";
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                } else {
                    campoClave.type = "password";
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado
                }
            }
        });
    }
    if (botonMostrarContraseña2) {
        botonMostrarContraseña2.addEventListener("click", function () {
            if (campoClave2) {
                // Alternar entre tipo "password" y "text"
                if (campoClave2.type === "password") {
                    campoClave2.type = "text";
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                } else {
                    campoClave2.type = "password";
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado
                }
            }
        });
    }
  
    // Restablecer el tipo de campo si el usuario escribe manualmente
    if (campoClave) {
        campoClave.addEventListener("input", function () {
                if(campoClave.value === ""){
                    campoClave2.value = "";
                    campoClave.type = "password"; // Ocultar la contraseña si el usuario escribe
                    campoClave2.type = "password"; // Ocultar la contraseña si el usuario escribe
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado    
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado    
                }
                checkPasswordStrength('userForm','clave','password-strength-bar');
            
        });
    }
    if (campoClave2) {
        campoClave2.addEventListener("input", function () {
                if(campoClave2.value === ""){
                    campoClave.value = "";
                    campoClave2.type = "password"; // Ocultar la contraseña si el usuario escribe
                    campoClave.type = "password"; // Ocultar la contraseña si el usuario escribe
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado    
                }
                checkPasswordStrength('userForm','clave','password-strength-bar');
            
        });
    }
  });
  
  
  document.addEventListener("DOMContentLoaded", function () {
    const campoClave = document.getElementById("claveChange"); 
    const campoClave2 = document.getElementById("claveChange2");
    const botonGenerarContraseña = document.getElementById("generarContraseñaChange");
    const botonMostrarContraseña = document.getElementById("mostrarContraseñaChange");
    const botonMostrarContraseña2 = document.getElementById("mostrarContraseñaChange2");
  
    let contraseñaGenerada =false; // Para rastrear si la contraseña fue generada automáticamente
  
    // Botón para generar contraseña
    if (botonGenerarContraseña) {
        botonGenerarContraseña.addEventListener("click", function () {
            const contraseña = generarContraseña();
            if (campoClave) {
                campoClave.value = contraseña; // Rellenar el campo de contraseña
                campoClave2.value = contraseña; // Rellenar el campo de contraseña
                contraseñaGenerada = true; // Marcar que la contraseña fue generada
                campoClave.type = "text"; // Mostrar la contraseña generada
                campoClave2.type = "text"; // Mostrar la contraseña generada
                botonMostrarContraseña.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                checkPasswordStrength('ChangePass','claveChange','password-strength-barChange'); 
                notify("Contraseña generada correctamente", 1500, "success", "top-end");
            }
        });
    }
  
    // Botón para mostrar/ocultar contraseña
    if (botonMostrarContraseña) {
        botonMostrarContraseña.addEventListener("click", function () {
            if (campoClave) {
                // Alternar entre tipo "password" y "text"
                if (campoClave.type === "password") {
                    campoClave.type = "text";
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                } else {
                    campoClave.type = "password";
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado
                }
            }
        });
    }
    if (botonMostrarContraseña2) {
        botonMostrarContraseña2.addEventListener("click", function () {
            if (campoClave2) {
                // Alternar entre tipo "password" y "text"
                if (campoClave2.type === "password") {
                    campoClave2.type = "text";
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye'></i>"; // Cambiar a ojito abierto
                } else {
                    campoClave2.type = "password";
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado
                }
            }
        });
    }
  
    // Restablecer el tipo de campo si el usuario escribe manualmente
    if (campoClave) {
        campoClave.addEventListener("input", function () {
                if(campoClave.value === ""){
                    campoClave2.value = "";
                    campoClave.type = "password"; // Ocultar la contraseña si el usuario escribe
                    campoClave2.type = "password"; // Ocultar la contraseña si el usuario escribe
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado    
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado    
                }
                checkPasswordStrength('ChangePass','claveChange','password-strength-barChange');
            
        });
    }
    if (campoClave2) {
        campoClave2.addEventListener("input", function () {
                if(campoClave2.value === ""){
                    campoClave.value = "";
                    campoClave2.type = "password"; // Ocultar la contraseña si el usuario escribe
                    campoClave.type = "password"; // Ocultar la contraseña si el usuario escribe
                    botonMostrarContraseña2.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado
                    botonMostrarContraseña.innerHTML = "<i class='fa fa-eye-slash'></i>"; // Cambiar a ojito cerrado    
                }
                checkPasswordStrength('ChangePass','claveChange','password-strength-barChange');
            
        });
    }
  });
  
  
  
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
  


  //tipos de usuario
  
  document.addEventListener("DOMContentLoaded", function () {
    // Configuración de validación para los campos
    const configCampos = {
        // Campo código - solo letras, sin espacios, máximo 4 caracteres
        c_tipo_usuario: { 
            regex: /^[a-zA-Z]*$/, // Solo letras (sin números)
            maxLength: 4,
            noSpaces: true,
            toUpperCase: true // Convertir a mayúsculas automáticamente
        },
        // Campo descripción - solo letras y espacios, máximo 30 caracteres
        descripcion: { 
            regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/, // Solo letras y espacios
            maxLength: 30
        },
        // Versiones de edición de los campos
        c_tipo_usuarioEdit: { 
            regex: /^[a-zA-Z]*$/,
            maxLength: 4,
            noSpaces: true,
            toUpperCase: true
        },
        descripcionEdit: { 
            regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/,
            maxLength: 30
        }
    };
  
    // Aplicar validaciones a todos los campos configurados
    for (const [campo, config] of Object.entries(configCampos)) {
        const input = document.getElementById(campo);
        if (input) {
            // Manejar el evento de pegar (Ctrl+V)
            input.addEventListener('paste', function(e) {
                e.preventDefault(); // Cancelar el pegado original
                // Obtener texto pegado y limpiarlo
                const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
                let textoLimpio = textoPegado.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                
                if (config.noSpaces) {
                    textoLimpio = textoLimpio.replace(/\s/g, '');
                }
                
                if (config.toUpperCase) {
                    textoLimpio = textoLimpio.toUpperCase();
                }
                
                // Insertar el texto limpio
                document.execCommand('insertText', false, textoLimpio);
            });
  
            // Validar caracteres permitidos y longitud máxima
            input.addEventListener("input", function () {
                // Eliminar cualquier número o carácter no permitido
                let valor = input.value;
                valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                
                // Aplicar configuraciones específicas
                if (config.noSpaces) {
                    valor = valor.replace(/\s/g, '');
                }
                
                if (config.toUpperCase) {
                    valor = valor.toUpperCase();
                }
                
                // Limitar longitud máxima
                if (valor.length > config.maxLength) {
                    valor = valor.substring(0, config.maxLength);
                    notify(`Máximo ${config.maxLength} caracteres permitidos`, 1500, "warning", "top-end");
                }
                
                // Actualizar el valor del campo
                input.value = valor;
            });
  
            // Validar al perder el foco
            input.addEventListener("blur", function () {
                if (!config.regex.test(input.value)) {
                    notify(`Solo se permiten ${config.noSpaces ? 'letras (sin números ni espacios)' : 'letras y espacios (sin números)'}`, 1500, "warning", "top-end");
                }
            });
        }
    }
  });




//holidays

  document.addEventListener("DOMContentLoaded", function() {
    // Configuración de validación para los campos de nombre
    const nombreFields = ['nombre', 'nombreEdit'];
    
    nombreFields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        
        if (input) {
            // Manejar el evento de pegar (Ctrl+V)
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
                const textoLimpio = textoPegado.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]/g, '');
                document.execCommand('insertText', false, textoLimpio);
            });

            // Validar mientras se escribe
            input.addEventListener('input', function() {
                // Eliminar caracteres no permitidos
                let valor = input.value;
                const valorOriginal = valor;
                valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]/g, '');
                
                // Si se eliminaron caracteres, mostrar notificación
                if (valor !== valorOriginal) {
                    notify("Solo se permiten letras, números y espacios", 1500, "warning", "top-end");
                }
                
                // Limitar a 50 caracteres
                if (valor.length > 50) {
                    valor = valor.substring(0, 50);
                    notify("Máximo 50 caracteres permitidos", 1500, "warning", "top-end");
                }
                
                input.value = valor;
            });

            // Validar al perder el foco
            input.addEventListener('blur', function() {
                if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]*$/.test(input.value)) {
                    notify("El nombre solo puede contener letras, números y espacios", 1500, "warning", "top-end");
                }
            });
        }
    });
});


//clasificacion

document.addEventListener("DOMContentLoaded", function() {
    // =============================================
    // VALIDACIÓN PARA EL CAMPO CÓDIGO (4 caracteres: letras o números)
    // =============================================
    const codigoFields = ['codigo', 'codigoEdit'];
    
    codigoFields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        
        if (input) {
            // Manejar el evento de pegar (Ctrl+V)
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
                const textoLimpio = textoPegado.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().substring(0, 4);
                document.execCommand('insertText', false, textoLimpio);
            });

            // Validar mientras se escribe
            input.addEventListener('input', function() {
                // Eliminar caracteres no permitidos y limitar a 4 caracteres
                let valor = input.value;
                valor = valor.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
                if (valor.length > 4) {
                    valor = valor.substring(0, 4);
                    notify("Máximo 4 caracteres permitidos", 1500, "warning", "top-end");
                }
                input.value = valor;
            });

            // Validar al perder el foco
            input.addEventListener('blur', function() {
                if (!/^[A-Z0-9]{4}$/.test(input.value)) {
                    notify("El código debe tener exactamente 4 caracteres (letras o números)", 1500, "warning", "top-end");
                }
            });
        }
    });

    // =============================================
    // VALIDACIÓN PARA EL CAMPO DESCRIPCIÓN (solo letras y espacios)
    // =============================================
    const descripcionFields = ['descripcion', 'descripcionEdit'];
    
    descripcionFields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        
        if (input) {
            // Manejar el evento de pegar
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
                const textoLimpio = textoPegado.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                document.execCommand('insertText', false, textoLimpio);
            });

            // Validar mientras se escribe
            input.addEventListener('input', function() {
                // Eliminar caracteres no permitidos
                let valor = input.value;
                valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                
                // Limitar a la longitud máxima
                const maxLength = parseInt(input.getAttribute('maxlength')) || 50;
                if (valor.length > maxLength) {
                    valor = valor.substring(0, maxLength);
                    notify(`Máximo ${maxLength} caracteres permitidos`, 1500, "warning", "top-end");
                }
                
                input.value = valor;
            });

            // Validar al perder el foco
            input.addEventListener('blur', function() {
                if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(input.value)) {
                    notify("La descripción solo puede contener letras y espacios", 1500, "warning", "top-end");
                }
            });
        }
    });
});


//tipo de actividad

document.addEventListener("DOMContentLoaded", function() {
    // Validación para el campo Código (4 letras mayúsculas)
    const codigoInput = document.getElementById("codigo");
    if (codigoInput) {
        codigoInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
            const textoLimpio = textoPegado.replace(/[^A-Za-z]/g, '').toUpperCase().substring(0, 4);
            document.execCommand('insertText', false, textoLimpio);
        });

        codigoInput.addEventListener('input', function() {
            let valor = this.value.toUpperCase();
            valor = valor.replace(/[^A-Z]/g, '');
            if (valor.length > 4) {
                valor = valor.substring(0, 4);
                notify("Máximo 4 letras permitidas", 1500, "warning", "top-end");
            }
            this.value = valor;
        });
    }

    // Validación para el campo Descripción (solo letras y espacios)
    const descripcionInput = document.getElementById("descripcion");
    if (descripcionInput) {
        descripcionInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
            const textoLimpio = textoPegado.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').substring(0, 50);
            document.execCommand('insertText', false, textoLimpio);
        });

        descripcionInput.addEventListener('input', function() {
            let valor = this.value;
            valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            if (valor.length > 50) {
                valor = valor.substring(0, 50);
                notify("Máximo 50 caracteres permitidos", 1500, "warning", "top-end");
            }
            this.value = valor;
        });
    }

    // Validación para el campo Prefijo (1 letra mayúscula)
    const preInput = document.getElementById("pre");
    if (preInput) {
        preInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
            const textoLimpio = textoPegado.replace(/[^A-Za-z]/g, '').toUpperCase().substring(0, 1);
            document.execCommand('insertText', false, textoLimpio);
        });

        preInput.addEventListener('input', function() {
            let valor = this.value.toUpperCase();
            valor = valor.replace(/[^A-Z]/g, '');
            if (valor.length > 1) {
                valor = valor.substring(0, 1);
                notify("Solo 1 letra permitida", 1500, "warning", "top-end");
            }
            this.value = valor;
        });
    }

    // Validaciones para los campos de edición
    const descripcionEditInput = document.getElementById("descripcionEdit");
    if (descripcionEditInput) {
        descripcionEditInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const textoPegado = (e.clipboardData || window.clipboardData).getData('text');
            const textoLimpio = textoPegado.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').substring(0, 50);
            document.execCommand('insertText', false, textoLimpio);
        });

        descripcionEditInput.addEventListener('input', function() {
            let valor = this.value;
            valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            if (valor.length > 50) {
                valor = valor.substring(0, 50);
                notify("Máximo 50 caracteres permitidos", 1500, "warning", "top-end");
            }
            this.value = valor;
        });
    }
});