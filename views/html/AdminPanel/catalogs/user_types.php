<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');

?>

<script src="views\js\forms.js"></script>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary expandable-btn" data-toggle="modal" data-target="#ModalAddUserType">
                    <span class="fas fa-plus" style="margin-right:10px;"></span> Nuevo Tipo
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
                <h4 class="modal-title"><span class="fas fa-plus" style="margin-right:10px;"></span>Agregar Tipo De Usuario</h4>
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
                <h4 class="modal-title"><span class="fas fa-pencil" style="margin-right:10px;"></span>Editar Tipo De Usuario</h4>
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

 // Función para agregar nuevo tipo de usuario (con validaciones reforzadas)
function newUserType() {
    var c_tipo_usuario = $("#c_tipo_usuario").val().toUpperCase();
    var descripcion = $("#descripcion").val();

    // Validación adicional para asegurar que no haya números
    c_tipo_usuario = c_tipo_usuario.replace(/[^A-Z]/g, '');
    descripcion = descripcion.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');

    // Validar campo código
    if (!c_tipo_usuario) {
        $("#c_tipo_usuario").focus();
        notify("El campo tipo de usuario es obligatorio", 1500, "error", "top-end");
        return;
    } else if (!/^[A-Z]{1,4}$/.test(c_tipo_usuario)) {
        notify("El código debe tener entre 1 y 4 letras (no se permiten números)", 2000, "error", "top-end");
        $("#c_tipo_usuario").focus();
        return;
    }

    // Validar campo descripción
    if (!descripcion) {
        $("#descripcion").focus();
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
        return;
    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,30}$/.test(descripcion)) {
        notify("La descripción debe tener entre 1 y 30 caracteres (solo letras y espacios)", 2000, "error", "top-end");
        $("#descripcion").focus();
        return;
    }

    // Si pasa las validaciones, hacer la petición AJAX
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
        success: function (datos) {
            var respuesta = JSON.parse(datos);
            if (respuesta["codigo"] == "1") {
                cleanFormUsersTypes();
                getusertype();
                notify(respuesta["alerta"], 1500, "success", "top-end");
            } else {
                notify(respuesta["alerta"], 1500, "error", "top-end");
            }
        }
    });
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

// Función para guardar edición de tipo de usuario (con validaciones reforzadas)
function SaveUserType() {
    var descripcion = $("#descripcionEdit").val();
    var id = _ID;

    // Validación adicional para asegurar que no haya números
    descripcion = descripcion.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');

    // Validar campo descripción
    if (!descripcion) {
        $("#descripcionEdit").focus();
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
        return;
    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,30}$/.test(descripcion)) {
        notify("La descripción debe tener entre 1 y 30 caracteres (solo letras y espacios, sin números)", 2000, "error", "top-end");
        $("#descripcionEdit").focus();
        return;
    }

    // Si pasa las validaciones, hacer la petición AJAX
    $.ajax({
        url: "ajax.php?mode=saveusertype",
        type: "POST",
        data: {
            descripcion: descripcion,
            id: id
        },
        error: function (request, status, error) {
            notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
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