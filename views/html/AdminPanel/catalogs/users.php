<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');


?>
<script src="views/js/usersForm.js"></script>


<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddUser">
                    <span class="glyphicon glyphicon-plus"></span> Agregar Usuario
                </button>

                <div id="contentUsers">
                </div>
            </div>
        </div>
    </div>
  </div>

 <div id="ModalAddUser" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Usuario</h4>
            </div>
            <div class="modal-body" id="userForm">
                    <div class="form-group">
                        <label for="firstName">Nombre:</label>
                        <input type="text" class="form-control" id="nombres" placeholder="Ingrese nombres" maxlength="50">                 
                        <ul class="list-unstyled">
                        </ul>

                    </div>
                    <div class="form-group">
                        <label for="area">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="apellido_p" placeholder="Ingrese Apellido Paterno" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="puesto">Apellido Materno:</label>
                        <input type="text" class="form-control" id="apellido_m" placeholder="Ingrese Apellido Materno" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="codigo">Usuario:</label>
                        <input type="text" class="form-control" id="usuario" placeholder="Ingrese Nombre De Usuario" maxlength="50">
                      </div>


                    <div class="form-group" > 
                      <div style="position: relative;">
                        <label for="codigo">Contraseña:</label>
                        <input type="password" class="form-control" id="clave" placeholder="Ingrese Contraseña"  maxlength="50">
                        <button type="button" id="mostrarContraseña" class="btn-icon btn-password-eye"><i class="fa fa-eye-slash"></i></button>
                        <button type="button" class="btn btn-default btn-sm" id="generarContraseña" style="position: absolute;    right: 0;    top: -4px;">Generar Contraseña</button>
                      </div>
                        <div class="collapse validator">
                        <div style="position: relative;">
                        <label for="codigo">Confirma Contraseña:</label>
                          <input type="password" class="form-control" id="clave2" placeholder="Confirma Contraseña"  maxlength="50">
                          <button type="button" id="mostrarContraseña2" class="btn-icon btn-password-eye"><i class="fa fa-eye-slash"></i></button>
                        </div>
                      <div class="progress mb-2">
                          <div id="password-strength-bar" class="progress-bar"></div>
                      </div>

                      <ul class="list-unstyled">
                          <li  class="text-danger length"><span class='fa fa-times'></span> Mínimo 8 caracteres</li>
                          <li  class="text-danger uppercase"><span class='fa fa-times'></span> Al menos una mayúscula</li>
                          <li  class="text-danger lowercase"><span class='fa fa-times'></span> Al menos una minúscula</li>
                          <li  class="text-danger special"><span class='fa fa-times'></span> Al menos un carácter especial (!@#$%^&*)</li>
                          <li class="text-danger equal"><span class='fa fa-times'></span> Las contraseñas coinciden</li>

                      </ul>
                    </div>
              </div>
              <div class="form-group">
                <label for="codigo">Correo:</label>
                <input type="text" class="form-control" id="correo" placeholder="Ingrese Correo" maxlength="50">
              </div>                      
    
<?php

$qTipoUsuario = 'SELECT codigo,descripcion
                  FROM users_types';

$dt_tiposUsuario=findtablaq($qTipoUsuario,"codigo");

 ?>

                    <div class="form-group">
                        <label for="codigo">Tipo Usuario:</label>
                        <select class="form-control" id="c_tipo_usuario">
                          <?php     foreach ($dt_tiposUsuario as $codigo => $array) {
                              echo "<option value='".$dt_tiposUsuario[$codigo]["codigo"]."'>".$dt_tiposUsuario[$codigo]["descripcion"]."</option>";
                          } ?>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="newUser()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="ModalEditUser" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Usuario: <span id="editNombreSel"></span></h4>
            </div>
            <div class="modal-body" id="userFormEdit">
                    <div class="form-group">
                        <label for="firstName">Nombre:</label>
                        <input type="text" class="form-control" id="nombresEdit" placeholder="Ingrese nombres" maxlength="50">                 
                        <ul class="list-unstyled">
                        </ul>

                    </div>
                    <div class="form-group">
                        <label for="area">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="apellido_pEdit" placeholder="Ingrese Apellido Paterno" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="puesto">Apellido Materno:</label>
                        <input type="text" class="form-control" id="apellido_mEdit" placeholder="Ingrese Apellido Materno" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="codigo">Usuario:</label>
                        <input type="text" class="form-control" id="usuarioEdit" placeholder="Ingrese Nombre De Usuario" maxlength="50">
                      </div>
                 
                    <div class="form-group">
                        <label for="codigo">Correo:</label>
                        <input type="text" class="form-control" id="correoEdit" placeholder="Ingrese Correo" maxlength="50">
                    </div>

                    <div class="form-group">
                        <label for="codigo">Tipo Usuario:</label>
                        <select class="form-control" id="c_tipo_usuarioEdit">
                          <?php     foreach ($dt_tiposUsuario as $codigo => $array) {
                              echo "<option value='".$dt_tiposUsuario[$codigo]["codigo"]."'>".$dt_tiposUsuario[$codigo]["descripcion"]."</option>";
                          } ?>
                        </select>
                    </div>
                    </div>

                    <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveUser()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="ModalChangePass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Contraseña Del Usuario <span id="nombrechangepass"></span></h4>
            </div>
            <div class="modal-body" >
                    <div class="form-group" id="ChangePass" > 
                      <div style="position: relative;">
                        <label for="codigo">Contraseña:</label>
                        <input type="password" class="form-control" id="claveChange" placeholder="Ingrese Contraseña"  maxlength="50">
                        <button type="button" id="mostrarContraseñaChange" class="btn-icon btn-password-eye"><i class="fa fa-eye-slash"></i></button>
                        <button type="button" class="btn btn-default btn-sm" id="generarContraseñaChange" style="position: absolute;    right: 0;    top: -4px;">Generar Contraseña</button>
                      </div>
                        <div class="collapse validator">
                        <div style="position: relative;">
                        <label for="codigo">Confirma Contraseña:</label>
                          <input type="password" class="form-control" id="claveChange2" placeholder="Confirma Contraseña"  maxlength="50">
                          <button type="button" id="mostrarContraseñaChange2" class="btn-icon btn-password-eye"><i class="fa fa-eye-slash"></i></button>
                        </div>
                      <div class="progress mb-2">
                          <div id="password-strength-barChange" class="progress-bar"></div>
                      </div>

                      <ul class="list-unstyled">
                          <li class="text-danger length"><span class='fa fa-times'></span> Mínimo 8 caracteres</li>
                          <li class="text-danger uppercase"><span class='fa fa-times'></span> Al menos una mayúscula</li>
                          <li class="text-danger lowercase"><span class='fa fa-times'></span> Al menos una minúscula</li>
                          <li class="text-danger special"><span class='fa fa-times'></span> Al menos un carácter especial (!@#$%^&*)</li>
                          <li class="text-danger equal"><span class='fa fa-times'></span> Las contraseñas coinciden</li>

                      </ul>
                        </div>
                      </div>
              </div>

                      <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="savePass()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                      </div>
        </div>
    </div>
</div>

<script>
  $(document).ready(function(){
        getUsers();

  });

  function getUsers(){
    $.ajax({
      url: "ajax.php?mode=getUsers",
      type: "POST",
      data: {},
      error : function(request, status, error) {
        notify('Error inesperado, consulte a soporte.'+request+status+error,1500,"error","top-end");
      },
      beforeSend: function() {
      },
       success: function(datos){
          $("#contentUsers").html(datos);
      }
    });
  }

  function newUser() {
    var nombres =$("#nombres").val();
    var apellido_p =$("#apellido_p").val();
    var apellido_m =$("#apellido_m").val();
    var usuario =$("#usuario").val();
    var clave =$("#clave").val();
    var correo =$("#correo").val();
    var c_tipo_usuario = $("#c_tipo_usuario").val();
  

    if(clave != $("#clave2").val()){
      notify("Las contraseñas no coinciden",1500,"error","top-end");
     }else if(checkPasswordStrength('userForm','clave','password-strength-bar')<3){
      notify("La contraseña no cumple con los requisitos minimos",1500,"error","top-end");
    }else if(nombres == "") {
    $("#nombres").focus(); 
    notify("El campo nombres es obligatorios",1500,"error","top-end");

    }else if(apellido_p == ""){
    $("#apellido_p").focus(); 
    notify("El campo apellido paterno es obligatorios",1500,"error","top-end");

    }else if(apellido_m == ""){
    $("#apellido_m").focus(); 
    notify("El campo apellido materno es obligatorios",1500,"error","top-end");

    }else if(usuario == ""){
    $("#usuario").focus(); 
    notify("El campo usuario es obligatorios",1500,"error","top-end");

  } else if (correo == "") {
          $("#correo").focus();
          notify("El campo correo es obligatorio", 1500, "error", "top-end");
      } else if (!validateEmail(correo)) {
          $("#correo").focus();
          notify("El formato del correo no es válido", 1500, "error", "top-end");

      }else{ 
            $.ajax({
                url: "ajax.php?mode=newuser",
                type: "POST",
                data: {
                    nombres: nombres,
                    apellido_p: apellido_p,
                    apellido_m: apellido_m,
                    usuario: usuario,
                    clave: clave,
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
                    var respuesta = JSON.parse(datos);
                    if (respuesta["codigo"] == "1") {
                        $('#ModalAddUser').modal('hide');
                        getUsers();
                        cleanFormUsers();
                        notify(respuesta["alerta"], 1500, "success", "top-end");
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                    }
                }
            }); 
          }
  }

  

  function confirmDeleteUser(id,user,c_tipo){
    if(c_tipo=="SPUS"){
      notify("No se puede eliminar un usuario de tipo super usuario",1500,"error","top-end");
      return;
    }else{
        notifyConfirm("Estas seguro?","se va a eliminar el usuario "+ user,"warning","deleteUser('"+id+"')");
    }
  }

  function deleteUser(id){
    $.ajax({  
    url: "ajax.php?mode=deleteUser",
    type: "POST",
    data: {
      id:id
    },
    error : function(request, status, error) {
      notify('Error inesperado, consulte a soporte.'+request+status+error,1500,"error","top-end");
    },
    beforeSend: function() {
    },
     success: function(datos){
       var respuesta = JSON.parse(datos);
        notify(respuesta["alerta"],1500,"success","top-end");
        getUsers();
    }
  });
  }

  function cleanFormUsers(){
    $("#nombres").val("");
    $("#apellido_p").val("");
    $("#apellido_m").val("");
    $("#usuario").val("");
    $("#clave").val("");
    $("#correo").val("");
    $("#c_tipo_usuario").val("SPUS");
    checkPasswordStrength('userForm','clave','password-strength-bar');
  }

var _ID=0;
  function GetUser(id,nombre){
      $("#editNombreSel").html(nombre);
      $("#ModalEditUser").modal("show");
      var tabla= "users";
      var campoId= "id";
       _ID=id;
    $.ajax({
      url: "ajax.php?mode=getregister",
      type: "POST",
      data: {
        tabla:tabla,
        campoId:campoId,
        datoId:id
      },
      error : function(request, status, error) {
        notify('Error inesperado, consulte a soporte.'+request+status+error,1500,"error","top-end");
      },
      beforeSend: function() {
      },
       success: function(datos){
        var respuesta = JSON.parse(datos);
          //console.log(respuesta);
          $("#nombresEdit").val(respuesta[id]["nombre"]);
          $("#apellido_pEdit").val(respuesta[id]["apellido_p"]);
          $("#apellido_mEdit").val(respuesta[id]["apellido_m"]);
          $("#usuarioEdit").val(respuesta[id]["usuario"]);
          $("#correoEdit").val(respuesta[id]["correo"]);
          $("#c_tipo_usuarioEdit").val(respuesta[id]["c_tipo_usuario"]);


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

  function ChangePass(id,nombre){
    $("#nombrechangepass").html(nombre);
    $("#ModalChangePass").modal("show");
     _ID=id;
  }

  function savePass(){
var clave =$("#claveChange").val();

if(clave != $("#claveChange2").val()){
      notify("Las contraseñas no coinciden",1500,"error","top-end");
     }else if(checkPasswordStrength('ChangePass','claveChange','password-strength-barChange')<3){
      notify("La contraseña no cumple con los requisitos minimos",1500,"error","top-end");
    }else{    
    $.ajax({
                url: "ajax.php?mode=changepass",
                type: "POST",
                data: {
                    id: _ID,
                    clave: clave
                },
                error: function (request, status, error) {
                    notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
                },
                beforeSend: function () {
                    // Código antes de enviar la solicitud
                },      
                success: function (datos) {
                  //console.log(datos);
                    var respuesta = JSON.parse(datos);
                    if (respuesta["codigo"] == "1") {
                        $('#ModalChangePass').modal('hide');                        
                        notify(respuesta["alerta"], 1500, "success", "top-end");
                        $("#claveChange").val("");
                        $("#claveChange2").val("");
                        checkPasswordStrength('ChangePass','claveChange','password-strength-barChange');
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                    }
                }
            }); 
          }
  }

</script>
<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>