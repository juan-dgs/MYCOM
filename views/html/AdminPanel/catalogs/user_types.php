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

                <div id="contentUsersType">
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
                        <input type="text" class="form-control" id="c_tipo_usuarioEdit" placeholder="Ingrese Codigo Del Tipo De Usuario" maxlength="50" disabled>
                    </div>

                    <div class="form-group">
                        <label for="firstName">Descripcion Del Tipo De Usuario:</label>
                        <input type="text" class="form-control" id="descripcionEdit" placeholder="Ingrese descripcion del tipo de Usuario" maxlength="50">                 
                        <ul class="list-unstyled">
                        </ul>

                    </div>
                    
            </div>         
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="SaveUserType()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
        getusertype();
});
      function newUserType() {
    $("#ModalAddUserType").modal("show");
    var c_tipo_usuario =$("#c_tipo_usuario").val();
    var descripcion =$("#descripcion").val();

    if(c_tipo_usuario == ""){
    $("#codigo").focus();
    notify("El campo tipo de usuario es obligatorios",1500,"error","top-end");
    
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
                       /* $("#ModalAddUserType").modal("hide");
                        cleanFormUsersTypes();*/
                        getusertype();
                        notify(respuesta["alerta"], 1500, "success", "top-end");
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                    }
                }
            });
          }
  }

  function getusertype() {
    $.ajax({
        url: "ajax.php?mode=getuserstype",
        type: "POST",
        data: {},
        error: function(request, status, error) {
            notify('Error al cargar clientes: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            $("#contentUsersType").html(datos);
            
            var arrayOrder = [];         //[14, 'asc'], [0, 'asc'], [3, 'asc'], [5, 'asc']
                var arrayExport = ['excel']; //'excel'
                datatablebase("tablaTipoDeUsuarios", false, 400, true, true, arrayOrder, arrayExport);
                //datatablebase(tableid, ffoot, scroll, order, search, arrayOrder, arrayExport)
        }
    });
}

var _ID = "";
  function GetRegisterUserTypes(id,codigo,descripcion){
      $("#edittype").html(codigo);
        $("#ModalEditUserType").modal("show");
        var tablaEdit="users_types";
         var datoId="id";
         _ID=id;
        
    $.ajax({
      url: "ajax.php?mode=getregister",
      type: "POST",
      data: {
        tabla:tablaEdit,
        campoId:datoId,
        datoId:id
      },
      error : function(request, status, error) {
        notify('Error inesperado, consulte a soporte.'+request+status+error,1500,"error","top-end");
      },
      beforeSend: function() {
      },
       success: function(datos){
        var respuesta = JSON.parse(datos);
          $("#c_tipo_usuarioEdit").val(respuesta[1]["codigo"]);
            $("#descripcionEdit").val(respuesta[1]["descripcion"]);
      }
    });
  
}

  function SaveUserType(){
    var descripcion = $("#descripcionEdit").val();
    var id = _ID;                                                                                                                                                                           
  
    if(descripcion == ""){
    $("#descripcionEdit").focus(); 
    notify("El campo descripcion es obligatorios",1500,"error","top-end");

    }else{

    $.ajax({
                url: "ajax.php?mode=saveusertype",
                type: "POST",
                data: {
                    descripcion: descripcion,
                    id:id
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
                        $("#ModalEditUserType").modal("hide");
                        getusertype();
                        notify(respuesta["alerta"], 1500, "success", "top-end");
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                    }
                }
            }); 
          }
  }

  function confirmDeleteUserTypes(id,c_tipo_usuario,descripcion){
    if(c_tipo_usuario=="SPUS"){
      notify("No se puede eliminar un usuario de tipo super usuario",1500,"error","top-end");
      return;
    }else{
        notifyConfirm("Estas seguro?","se va a eliminar el tipo y la descripcion de usuario "+ descripcion,"warning","deleteUserType('"+id+"')");
    }
  }

  function deleteUserType(id){
    $.ajax({  
    url: "ajax.php?mode=deleteusertype",
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
       if (respuesta["codigo"] == "1") {
        notify(respuesta["alerta"],1500,"success","top-end");
        getusertype();
        } else {
        notify(respuesta["alerta"],2500,"error","top-end");
        }
    }
  });
  }

 

  function cleanFormUsersTypes(){
    $("#c_tipo_usuario").val("");
    $("#descripcion").val("");
    $("#c_tipo_usuarioEdit").val("");
    $("#descripcionEdit").val("");
  }
    
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>