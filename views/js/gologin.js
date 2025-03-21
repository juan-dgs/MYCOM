$(document).ready(function() {
    $('#UserName,#Password').keypress(function(e){
      if(e.keyCode==13){
        goLogin();
      }
    });

  });

function goLogin() {
  var user=$("#UserName").val();
  var pass=$("#Password").val();
  var session = 0;

  if (user==""||pass=="") {
    if (user=="") {
      var alerta  = '<p><b>Error:</b> Ingresar Usuario.</p>';
      alertify.error(alerta);

      $("#UserName").focus();
    } else if (pass=="") {
      var alerta = '<p><b>Error:</b> Ingresar Contraseña.</p>';
      alertify.error(alerta);

      $("#Password").focus();
    }
  }else{
   var ruta = "ajax.php?mode=gologin";
    $.ajax({
        url: ruta,
        type: "POST",
        data: {
          user:user,
          session:session,
          pass:pass
        },
        success: function(datos) {
          console.log(datos);
          try {
            var respuesta = JSON.parse(datos);

            if (respuesta["CODE"] == "1") {
                location.replace('panel');
            }else {
                alertify.error(respuesta["ERROR"]);
            }

          }
          catch (error) {
            if(error instanceof SyntaxError) {
                let mensaje = error.message;
                console.log('ERROR EN LA SINTAXIS:', mensaje);
            } else {
                throw error; // si es otro error, que lo siga lanzando
            }
          }

        }
    });
  }
}

function enterLogin(e){
 if (e.keyCode==13) {
   goLogin();
 }
}
