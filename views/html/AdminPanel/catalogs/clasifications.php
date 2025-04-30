<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<script src="views\js\forms.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary expandable-btn" data-toggle="modal" data-target="#ModalAddClasificacion">
                    <span class="fas fa-plus" style="margin-right:10px;"></span> Agregar Clasificación
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
                <h4 class="modal-title"><span class="fas fa-plus" style="margin-right:10px;"></span>Agregar Clasificación</h4>
            </div>
            <div class="modal-body" id="clasificacionForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo">Código: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="codigo" placeholder="Ingrese código" maxlength="4" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="descripcion">Descripción: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="descripcion" placeholder="Ingrese descripción" maxlength="40" required>
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
                <h4 class="modal-title"><span class="fas fa-pencil" style="margin-right:10px;"></span>Editar Clasificación: <span id="editClasificacionSel"></span></h4>
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
                <button type="button" class="btn btn-success" onclick="saveClasification()">Guardar</button>
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

// =============================================
// FUNCIÓN PARA AGREGAR NUEVA CLASIFICACIÓN
// =============================================
function newClasification() {
    const $btn = $("#ModalAddClasificacion").find(".btn-success");
    let codigo = $("#codigo").val().trim().toUpperCase();
    let descripcion = $("#descripcion").val().trim();

    // Limpiar y validar código (4 caracteres alfanuméricos)
    codigo = codigo.replace(/[^A-Z0-9]/g, '').substring(0, 4);
    $("#codigo").val(codigo);

    // Limpiar y validar descripción (solo letras y espacios)
    descripcion = descripcion.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    $("#descripcion").val(descripcion);

    // Validar campo código
    if (!codigo) {
        $("#codigo").focus(); 
        notify("El campo código es obligatorio", 1500, "error", "top-end");
        return;
    } else if (codigo.length !== 4) {
        $("#codigo").focus(); 
        notify("El código debe tener exactamente 4 caracteres (letras o números)", 1500, "error", "top-end");
        return;
    }
    
    // Validar campo descripción
    if (!descripcion) {
        $("#descripcion").focus(); 
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
        return;
    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(descripcion)) {
        $("#descripcion").focus(); 
        notify("La descripción solo puede contener letras y espacios", 1500, "error", "top-end");
        return;
    }

    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: "ajax.php?mode=newclasification", 
        type: "POST",
        dataType: 'json',
        data: {
            codigo: codigo,
            descripcion: descripcion
        }
    })
    .done(function(respuesta) {
        if(respuesta.codigo === 1) {
            $('#ModalAddClasificacion').modal('hide');
            cleanFormClasifications();
            notify(respuesta.alerta, 1500, "success", "top-end");
            getClasifications();
        } else {
            notify(respuesta.alerta || "Error al guardar", 1500, "error", "top-end");
        }
    })
    .fail(function(xhr, status, error) {
        notify(`Error de conexión: ${error}`, 1500, "error", "top-end");
    })
    .always(function() {
        $btn.prop('disabled', false).html('Guardar');
    });
}

function confirmDeleteClasification(id, descripcion){
    notifyConfirm("¿Estás seguro?", "Se va a desactivar la clasificación: " + descripcion, "warning","deleteClasification('"+id+"')");
}

function deleteClasification(id) {
    $.ajax({  
        url: "ajax.php?mode=deleteclasification",
        type: "POST",
        data: { id: id },
        dataType: 'json',
        success: function(respuesta) {
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    notify(respuesta.alerta, 1500, "success", "top-end");
                    getClasifications();
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

function cleanFormClasifications(){
    $("#codigo, #descripcion").val("");
}

function getRegisterClasification(id, codigo) {
    $("#editClasificacionSel").html(codigo);
    $("#ModalEditClasificacion").modal("show");
    $("#idEdit").val(id);
    
    $.ajax({
        url: "ajax.php?mode=getregister",
        type: "POST",
        data: {
            tabla: "act_c_clasificacion",  // Asegúrate que coincida con tu tabla real
            campoId: "id",
            datoId: id
        },
        error: function(request, status, error) {
            notify('Error al obtener datos: ' + error, 1500, "error", "top-end");
            $("#ModalEditClasificacion").modal("hide");
        },
        success: function(datos) {
            try {
                // Verifica si la respuesta es un objeto JSON válido
                if (typeof datos === 'string') {
                    datos = JSON.parse(datos);
                }
                
                // Manejo de errores del servidor
                if (datos.error) {
                    notify(datos.error, 1500, "error", "top-end");
                    $("#ModalEditClasificacion").modal("hide");
                    return;
                }
                
                // Verifica la estructura de datos esperada
                if (datos.codigo && datos.descripcion) {
                    $("#codigoEdit").val(datos.codigo);
                    $("#descripcionEdit").val(datos.descripcion);
                } else if (datos[1] && datos[1].codigo) {
                    // Compatibilidad con estructura antigua
                    $("#codigoEdit").val(datos[1]["codigo"]);
                    $("#descripcionEdit").val(datos[1]["descripcion"]);
                } else {
                    throw new Error("Estructura de datos no reconocida");
                }
            } catch(e) {
                console.error("Error al procesar respuesta:", datos);
                notify("Error al procesar datos. Ver consola para detalles.", 1500, "error", "top-end");
                $("#ModalEditClasificacion").modal("hide");
            }
        }
    });
}

// =============================================
// FUNCIÓN PARA GUARDAR EDICIÓN DE CLASIFICACIÓN
// =============================================
function saveClasification() {
    var id = $("#idEdit").val();
    let descripcion = $("#descripcionEdit").val().trim();

    // Limpiar y validar descripción (solo letras y espacios)
    descripcion = descripcion.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    $("#descripcionEdit").val(descripcion);

    if (!descripcion) {
        $("#descripcionEdit").focus();
        notify("El campo descripción es obligatorio", 1500, "error", "top-end");
        return;
    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(descripcion)) {
        $("#descripcionEdit").focus();
        notify("La descripción solo puede contener letras y espacios", 1500, "error", "top-end");
        return;
    }

    var btn = $("#ModalEditClasificacion").find(".btn-success");
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: "ajax.php?mode=saveclasifications",
        type: "POST",
        dataType: 'json',
        data: {
            id: id,
            descripcion: descripcion
        },
        success: function(respuesta) {
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    notify(respuesta.alerta, 2000, "success", "top-end");
                    $("#ModalEditClasificacion").modal("hide");
                    getClasifications();
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