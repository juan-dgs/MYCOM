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
  