<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');

?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddUserType">
                    <span class="glyphicon glyphicon-plus"></span> Agregar Tipo De Usuario
                </button>

                <div id="contentUsers">
                </div>
            </div>
        </div>
    </div>
  </div>

 <div id="ModalAddUserType" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Tipo De Usuario</h4>
            </div>
            <div class="modal-body" id="userForm">

            <div class="form-group">
                        <label for="area">Codigo Del Tipo De Usuario:</label>
                        <input type="text" class="form-control" id="c_tipo_usuario" placeholder="Ingrese Codigo Del Tipo De Usuario" maxlength="50">
                    </div>

                    <div class="form-group">
                        <label for="firstName">Descripcion Del Tipo De Usuario:</label>
                        <input type="text" class="form-control" id="descripcion" placeholder="Ingrese nombre Del Tipo De Usuario" maxlength="50">                 
                        <ul class="list-unstyled">
                        </ul>

                    </div>
                    
            </div>         
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="newUserType()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="ModalEditUserType" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Tipo De Usuario</h4>
            </div>
            <div class="modal-body" id="codigoedit">

            <div class="form-group">
                        <label for="area">Codigo Del Tipo De Usuario:</label>
                        <input type="text" class="form-control" id="c_tipo_usuario" placeholder="Ingrese Codigo Del Tipo De Usuario" maxlength="50">
                    </div>

                    <div class="form-group">
                        <label for="firstName">Descripcion Del Tipo De Usuario:</label>
                        <input type="text" class="form-control" id="descripcion" placeholder="Ingrese descripcion del tipo de Usuario" maxlength="50">                 
                        <ul class="list-unstyled">
                        </ul>

                    </div>
                    
            </div>         
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="newUserType()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
        getusertypes();
});
      function newUserType() {
    var c_tipo_usuario =$("#c_tipo_usuario").val();
    var descripcion =$("#descripcion").val();

    if(c_tipo_usuario == ""){
    $("#codigo").focus();
    notify("El campo tipo de uduario es obligatorios",1500,"error","top-end");
    
    }else if(descripcion == ""){
    $("#descripcion").focus();
    notify("El campo descripcion es obligatorios",1500,"error","top-end");

      }else{ 
            $.ajax({
                url: "ajax.php?mode=newusertype",
                type: "POST",
                data: {
                    c_tipo_usuario: c_tipo_usuario,
                    descripcion: descripcion
                },
                error: function (request, status, error) {
                    notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
                },
                beforeSend: function () {
                    // Código antes de enviar la solicitud
                },
                success: function (datos) {
                    var respuesta = JSON.parse(datos);
                    if (respuesta["codigo"] == "1") {
                        $('#ModalAddUser_type').modal('hide');
                        notify(respuesta["alerta"], 1500, "success", "top-end");
                        getusertypes();
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                    }
                }
            });
          }
  }

  function getusertypes()
  {
    $.ajax({
        url: "ajax.php?mode=getuserstype",
        type: "POST",
        data: {},
        error: function (request, status, error) {
            notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
        },
        beforeSend: function () {
            // Código antes de enviar la solicitud
        },
        success: function (datos) {
            $("#contentUsers").html(datos);
        }
    });
  }
    
  function confirmDeleteUserTypes(id,codigo,descripcion){
    if(c_tipo == "1"){
        notifyConfirm("Estas seguro?","se va a eliminar el tipo de usuario "+ user,"warning","deleteUser('"+id+"')");
    }
  }

  function GetUserTypes(id,codigo,descripcion){
      $("#edittype").html(codigo);
      $("#ModalEditUserType").modal("show");
        var tablaEdit="users_types";
      var campoIdEdit= "codigo";
      var campoId= "id";
      datoId="id";
       _ID=id;
    $.ajax({
      url: "ajax.php?mode=getregister",
      type: "POST",
      data: {
        tabla:tablaEdit,
        campoIdEdit:campoIdEdit,
        campoId:campoId,
        id:id
      },
      error : function(request, status, error) {
        notify('Error inesperado, consulte a soporte.'+request+status+error,1500,"error","top-end");
      },
      beforeSend: function() {
      },
       success: function(datos){
        var respuesta = JSON.parse(datos);
          //console.log(respuesta);
          $("#c_tipo_usuarioEdit").val(respuesta[id]["c_tipo_usuario"]);
            $("#descripcionEdit").val(respuesta[id]["descripcion"]);
      }
    });
  
}

  function saveUser(){
    var nombres =$("#nombresEdit").val();
    var apellido_p =$("#apellido_pEdit").val();
    var apellido_m =$("#apellido_mEdit").val();
    var usuario =$("#usuarioEdit").val();
    var correo =$("#correoEdit").val();
    var c_tipo_usuario = $("#c_tipo_usuarioEdit").val();
  
    if(nombres == "") {
    $("#nombresEdit").focus(); 
    notify("El campo nombres es obligatorios",1500,"error","top-end");

    }else if(apellido_p == ""){
    $("#apellido_pEdit").focus(); 
    notify("El campo apellido paterno es obligatorios",1500,"error","top-end");

    }else if(apellido_m == ""){
    $("#apellido_mEdit").focus(); 
    notify("El campo apellido materno es obligatorios",1500,"error","top-end");

    }else if(usuario == ""){
    $("#usuarioEdit").focus(); 
    notify("El campo usuario es obligatorios",1500,"error","top-end");

  } else if (correo == "") {
          $("#correoEdit").focus();
          notify("El campo correo es obligatorio", 1500, "error", "top-end");
      } else if (!validateEmail(correo)) {
          $("#correoEdit").focus();
          notify("El formato del correo no es válido", 1500, "error", "top-end");
      }else{

    $.ajax({
                url: "ajax.php?mode=saveuser",
                type: "POST",
                data: {
                    id: _ID,
                    nombres: nombres,
                    apellido_p: apellido_p,
                    apellido_m: apellido_m,
                    usuario: usuario,
                    correo: correo,
                    c_tipo_usuario: c_tipo_usuario
                },
                error: function (request, status, error) {
                    notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
                },
                beforeSend: function () {
                    // Código antes de enviar la solicitud
                },      
                success: function (datos) {
                  console.log(datos);
                    var respuesta = JSON.parse(datos);
                    if (respuesta["codigo"] == "1") {
                        $('#ModalEditUser').modal('hide');
                        getUsers();
                        notify(respuesta["alerta"], 1500, "success", "top-end");
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                    }
                }
            }); 
          }
  }

    
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>
