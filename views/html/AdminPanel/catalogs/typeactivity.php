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
                <button class="btn btn-primary expandable-btn" data-toggle="modal" data-target="#ModalAddActivityType">
                    <span class="fas fa-plus" style="margin-right:10px;"></span> Nuevo Tipo
                </button>

                <div id="contentActivityType">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ModalAddActivityType" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Tipo de Actividad</h4>
            </div>
            <div class="modal-body" id="activityForm">
                <div class="form-group">
                    <label for="codigo">Código del Tipo de Actividad:</label>
                    <input type="text" class="form-control" id="codigo" placeholder="Ingrese Código" maxlength="50">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción del Tipo de Actividad:</label>
                    <input type="text" class="form-control" id="descripcion" placeholder="Ingrese descripción" maxlength="50">                 
                </div>

                <div class="form-group">
                    <label for="pre">Prefijo:</label>
                    <input type="text" class="form-control" id="pre" placeholder="Ingrese prefijo" maxlength="10">                 
                </div>
            </div>         
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="newActivityType()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="ModalEditActivityType" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Tipo de Actividad</h4>
            </div>
            <div class="modal-body" id="codigoedit">
                <div class="form-group">
                    <label for="codigoEdit">Código del Tipo de Actividad:</label>
                    <input type="text" class="form-control" id="codigoEdit" disabled>
                </div>

                <div class="form-group">
                    <label for="descripcionEdit">Descripción del Tipo de Actividad:</label>
                    <input type="text" class="form-control" id="descripcionEdit">                 
                </div>

                <div class="form-group">
                    <label for="preEdit">Prefijo:</label>
                    <input type="text" class="form-control" id="preEdit" disabled>                 
                </div>
            </div>         
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="SaveActivityType()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    getActivityTypes();
    
    // Convertir a mayúsculas y validar prefijo (1 letra)
    $("#pre").on('input', function() {
        var pre = $(this).val().toUpperCase();
        if(pre.length > 1) {
            pre = pre.substring(0,1);
        }
        $(this).val(pre.replace(/[^A-Z]/g, ''));
    });
    
    // Convertir a mayúsculas y validar código (4 caracteres)
    $("#codigo").on('input', function() {
        var codigo = $(this).val().toUpperCase();
        if(codigo.length > 4) {
            codigo = codigo.substring(0,4);
        }
        $(this).val(codigo);
    });

    // Convertir a mayúsculas al perder foco (por si acaso)
    $("#pre, #codigo").on('blur', function() {
        $(this).val($(this).val().toUpperCase());
    });
});

function newActivityType() {
    var codigo = $("#codigo").val().toUpperCase();
    var descripcion = $("#descripcion").val();
    var pre = $("#pre").val().toUpperCase();

    // Resto de validaciones igual...
    if(codigo == "") {
        $("#codigo").focus();
        notify("El campo código es obligatorio", 1500, "error", "top-end");
    } else if(codigo.length != 4) {
        $("#codigo").focus();
        notify("El código debe tener exactamente 4 caracteres", 1500, "error", "top-end");
    } else if(descripcion == "") {
        $("#descripcion").focus();
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
    } else if(pre == "") {
        $("#pre").focus();
        notify("El campo prefijo es obligatorio", 1500, "error", "top-end");
    } else if(pre.length != 1 || !/^[A-Z]$/.test(pre)) {
        $("#pre").focus();
        notify("El prefijo debe ser exactamente 1 letra mayúscula", 1500, "error", "top-end");
    } else { 
        $.ajax({
            url: "ajax.php?mode=newactivitytype",
            type: "POST",
            data: {
                codigo: codigo,
                descripcion: descripcion,
                pre: pre
            },
            error: function(request, status, error) {
                notify('Error inesperado: ' + error, 1500, "error", "top-end");
            },
            success: function(datos) {
                var respuesta = JSON.parse(datos);
                if(respuesta["codigo"] == "1") {
                    getActivityTypes();
                    notify(respuesta["alerta"], 1500, "success", "top-end");
                    $("#ModalAddActivityType").modal("hide");
                    cleanFormActivityTypes();
                } else {
                    notify(respuesta["alerta"], 1500, "error", "top-end");
                }
            }
        });
    }
}


function getActivityTypes() {
    $.ajax({
        url: "ajax.php?mode=getactivitytypes",
        type: "POST",
        data: {},
        error: function(request, status, error) {
            notify('Error al cargar tipos de actividad: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            $("#contentActivityType").html(datos);
            var arrayOrder = [];
            var arrayExport = ['excel'];
            datatablebase("tablaTiposActividad", false, 400, true, true, arrayOrder, arrayExport);
        }
    });
}

var _ID = "";
function GetRegisterActivityType(id, codigo, descripcion) {
    $("#ModalEditActivityType").modal("show");
    _ID = id;
    
    $.ajax({
        url: "ajax.php?mode=getregister",
        type: "POST",
        data: {
            tabla: "act_c_tipos",
            campoId: "id",
            datoId: id
        },
        error: function(request, status, error) {
            notify('Error inesperado: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            var respuesta = JSON.parse(datos);
            $("#codigoEdit").val(respuesta[1]["codigo"]);
            $("#descripcionEdit").val(respuesta[1]["descripcion"]);
            $("#preEdit").val(respuesta[1]["pre"]);
        }
    });
}

function SaveActivityType() {
    var descripcion = $("#descripcionEdit").val();
    var id = _ID;
    
    if(descripcion == "") {
        $("#descripcionEdit").focus();
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
    } else {
        $.ajax({
            url: "ajax.php?mode=saveactivitytype",
            type: "POST",
            data: {
                descripcion: descripcion,
                id: id
            },
            error: function(request, status, error) {
                notify('Error inesperado: ' + error, 1500, "error", "top-end");
            },
            success: function(datos) {
                var respuesta = JSON.parse(datos);
                if(respuesta["codigo"] == "1") {
                    $("#ModalEditActivityType").modal("hide");
                    getActivityTypes();
                    notify(respuesta["alerta"], 1500, "success", "top-end");
                } else {
                    notify(respuesta["alerta"], 1500, "error", "top-end");
                }
            }
        });
    }
}

function confirmDeleteActivityType(id, codigo, descripcion) {
    notifyConfirm("¿Estás seguro?","Se va a eliminar el tipo de actividad: "+descripcion,"warning","deleteActivityType('"+id+"')");
}

function deleteActivityType(id) {
    $.ajax({
        url: "ajax.php?mode=deleteactivitytype",
        type: "POST",
        data: { id: id },
        error: function(request, status, error) {
            notify('Error inesperado: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            var respuesta = JSON.parse(datos);
            if(respuesta["codigo"] == "1") {
                notify(respuesta["alerta"], 1500, "success", "top-end");
                getActivityTypes();
            } else {
                notify(respuesta["alerta"], 2500, "error", "top-end");
            }
        }
    });
}

function cleanFormActivityTypes() {
    $("#codigo").val("");
    $("#descripcion").val("");
    $("#pre").val("");
    $("#codigoEdit").val("");
    $("#descripcionEdit").val("");
    $("#preEdit").val("");
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>