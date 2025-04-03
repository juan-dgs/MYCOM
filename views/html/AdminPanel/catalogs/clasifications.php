<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<script src="views/js/repository.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddClasificacion">
                    <span class="glyphicon glyphicon-plus"></span> Agregar Clasificación
                </button>

                <div id="contentClasificaciones">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Clasificación -->
<div id="ModalAddClasificacion" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Clasificación</h4>
            </div>
            <div class="modal-body" id="clasificacionForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo">Código: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="codigo" placeholder="Ingrese código" maxlength="20" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descripcion">Descripción: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="descripcion" placeholder="Ingrese descripción" maxlength="100" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted"><small>Los campos marcados con <span class="text-danger">*</span> son obligatorios</small></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="newClasification()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Clasificación -->
<div id="ModalEditClasificacion" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Clasificación: <span id="editClasificacionSel"></span></h4>
            </div>
            <div class="modal-body" id="clasificacionFormEdit">
                <input type="hidden" id="idEdit">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigoEdit">Código: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="codigoEdit" placeholder="Ingrese código" maxlength="20" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descripcionEdit">Descripción: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="descripcionEdit" placeholder="Ingrese descripción" maxlength="100" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted"><small>Los campos marcados con <span class="text-danger">*</span> son obligatorios</small></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveClasificacion()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    getClasifications();
});

function getClasifications() {
    $.ajax({
        url: "ajax.php?mode=getclasifications",
        type: "POST",
        data: {},
        error: function(request, status, error) {
            notify('Error al cargar clasificaciones: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            $("#contentClasificaciones").html(datos);
            
            var arrayOrder = [[1, 'asc']]; // Ordenar por código por defecto
            var arrayExport = ['excel'];
            datatablebase("tablaClasificaciones", false, 400, true, true, arrayOrder, arrayExport);
        }
    });
}

function newClasification() {
    var codigo = $("#codigo").val().trim();
    var descripcion = $("#descripcion").val().trim();

    // Validaciones
    if(codigo === "") {
        $("#codigo").focus(); 
        notify("El campo código es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    if(descripcion === "") {
        $("#descripcion").focus(); 
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
        return;
    }

    var btn = $("#ModalAddClasificacion").find(".btn-success");
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: "ajax.php?mode=newclasification",
        type: "POST",
        dataType: 'json',
        data: {
            codigo: codigo,
            descripcion: descripcion
        },
        success: function(respuesta) {
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    $('#ModalAddClasificacion').modal('hide');
                    cleanFormClasificaciones();
                    notify(respuesta.alerta, 1500, "success", "top-end");
                    getClasificaciones();
                } else {
                    notify(respuesta.alerta || "Error al guardar", 1500, "error", "top-end");
                }
            } else {
                notify("Respuesta del servidor no válida", 1500, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            notify("Error de conexión: " + error, 1500, "error", "top-end");
        },
        complete: function() {
            btn.prop('disabled', false).html('Guardar');
        }
    });
}

function confirmDeleteClasificacion(id, descripcion){
    notifyConfirm("¿Estás seguro?", "Se va a desactivar la clasificación: " + descripcion, "warning","deleteClasificacion('"+id+"')");
}

function deleteClasificacion(id) {
    $.ajax({  
        url: "ajax.php?mode=deleteclasificacion",
        type: "POST",
        data: { id: id },
        dataType: 'json',
        success: function(respuesta) {
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    notify(respuesta.alerta, 1500, "success", "top-end");
                    getClasificaciones();
                } else {
                    notify(respuesta.alerta || "Error al eliminar clasificación", 1500, "error", "top-end");
                }
            } else {
                notify("Respuesta del servidor no válida", 1500, "error", "top-end");
                console.error("Respuesta inesperada:", respuesta);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición AJAX:", {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            notify('Error al comunicarse con el servidor: ' + error, 1500, "error", "top-end");
        }
    });
}

function cleanFormClasificaciones(){
    $("#codigo, #descripcion").val("");
}

function getClasification(id, codigo) {
    $("#editClasificacionSel").html(codigo);
    $("#ModalEditClasificacion").modal("show");
    $("#idEdit").val(id);
    
    $.ajax({
        url: "ajax.php?mode=getregister",
        type: "POST",
        data: {
            tabla: "clasificaciones",
            campoId: "id",
            datoId: id
        },
        error: function(request, status, error) {
            notify('Error al obtener datos: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            try {
                var respuesta = JSON.parse(datos);
                if (respuesta.error) {
                    notify(respuesta.error, 1500, "error", "top-end");
                    $("#ModalEditClasificacion").modal("hide");
                } else {
                    $("#codigoEdit").val(respuesta[1]["codigo"]);
                    $("#descripcionEdit").val(respuesta[1]["descripcion"]);
                }
            } catch(e) {
                notify("Error al procesar datos de la clasificación: " + e.message, 1500, "error", "top-end");
            }
        }
    });
}

function saveClasificacion() {
    var id = $("#idEdit").val();
    var codigo = $("#codigoEdit").val().trim();
    var descripcion = $("#descripcionEdit").val().trim();

    if(codigo === "") {
        $("#codigoEdit").focus();
        notify("El campo código es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    if(descripcion === "") {
        $("#descripcionEdit").focus();
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
        return;
    }

    var btn = $("#ModalEditClasificacion").find(".btn-success");
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: "ajax.php?mode=saveclasificacion",
        type: "POST",
        dataType: 'json',
        data: {
            id: id,
            codigo: codigo,
            descripcion: descripcion
        },
        success: function(respuesta) {
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    notify(respuesta.alerta, 2000, "success", "top-end");
                    $("#ModalEditClasificacion").modal("hide");
                    getClasificaciones();
                } else {
                    notify(respuesta.alerta, 2500, "error", "top-end");
                }
            } else {
                notify("Respuesta del servidor no válida", 1500, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            notify("Error de conexión: " + error, 1500, "error", "top-end");
        },
        complete: function() {
            btn.prop('disabled', false).html('Guardar');
        }
    });
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>