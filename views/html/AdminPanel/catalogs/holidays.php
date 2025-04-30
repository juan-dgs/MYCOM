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
                <button class="btn btn-primary expandable-btn" data-toggle="modal" data-target="#ModalAddHoliday">
                    <span class="fas fa-plus" style="margin-right:10px;"></span> Nuevo Día Feriado
                </button>

                <div id="contentHolidays">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar feriado -->
<div id="ModalAddHoliday" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="fas fa-plus" style="margin-right:10px;"></span>Agregar Día Feriado</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Ingrese el nombre del feriado" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="es_recurrente"> Feriado recurrente (anual)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="newHoliday()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar feriado -->
<div id="ModalEditHoliday" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="fas fa-pencil" style="margin-right:10px;"></span>Editar Día Feriado</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idEdit">
                <input type="hidden" id="fecha_original"> 
                
                <div class="form-group">
                    <label for="fechaEdit">Fecha:</label>
                    <input type="date" class="form-control" id="fechaEdit" required>
                </div>

                <div class="form-group">
                    <label for="nombreEdit">Nombre:</label>
                    <input type="text" class="form-control" id="nombreEdit" placeholder="Ingrese el nombre del feriado" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="es_recurrenteEdit"> Feriado recurrente (anual)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="updateHoliday()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    getHolidays();
});

// Modificar las funciones de guardado para incluir validación
function newHoliday() {
    var fecha = $("#fecha").val();
    var nombre = $("#nombre").val().trim(); // Limpiar espacios al inicio/fin
    var es_recurrente = $("#es_recurrente").is(":checked") ? 1 : 0;

    // Validar nombre
    nombre = nombre.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]/g, '');
    $("#nombre").val(nombre); // Actualizar campo con valor limpio

    if(fecha == ""){
        $("#fecha").focus();
        notify("El campo fecha es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    if(nombre == ""){
        $("#nombre").focus();
        notify("El campo nombre es obligatorio", 1500, "error", "top-end");
        return;
    }

    if(!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]+$/.test(nombre)){
        $("#nombre").focus();
        notify("El nombre solo puede contener letras, números y espacios", 1500, "error", "top-end");
        return;
    }

    $.ajax({
        url: "ajax.php?mode=newholiday",
        type: "POST",
        data: {
            fecha: fecha,
            nombre: nombre,
            es_recurrente: es_recurrente
        },
        success: function(datos) {
            var respuesta = JSON.parse(datos);
            if (respuesta["codigo"] == "1") {
                $("#ModalAddHoliday").modal("hide");
                cleanFormHoliday();
                getHolidays();
                notify(respuesta["alerta"], 1500, "success", "top-end");
            } else {
                notify(respuesta["alerta"], 1500, "error", "top-end");
            }
        }
    });
}

function getHolidays() {
    $.ajax({
        url: "ajax.php?mode=getholidays",
        type: "POST",
        data: {},
        error: function(request, status, error) {
            notify('Error al cargar días feriados: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            $("#contentHolidays").html(datos);
            
            var arrayOrder = [[0, 'asc']]; // Ordenar por fecha ascendente
            var arrayExport = ['excel'];
            datatablebase("tablaDiasFeriados", false, 400, true, true, arrayOrder, arrayExport);
        }
    });
}

function getRegisterHoliday(fecha) {
    $.ajax({
        url: "ajax.php?mode=getregister",
        type: "POST",
        data: { 
            tabla: "core_feriados", 
            campoId: "fecha",
            datoId: fecha
        },
        success: function(datos) {
            try {
                var respuesta = JSON.parse(datos);
                console.log("Datos recibidos para edición:", respuesta); // Debug
                
                $("#ModalEditHoliday").modal("show");
                $("#idEdit").val(respuesta.id);
                $("#fecha_original").val(respuesta.fecha); // Asegurar esto
                $("#fechaEdit").val(respuesta.fecha);
                $("#nombreEdit").val(respuesta.nombre);
                $("#es_recurrenteEdit").prop("checked", respuesta.es_recurrente == 1);
            } catch (e) {
                console.error("Error al parsear respuesta:", e, datos);
                notify("Error al cargar datos para edición", 2000, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en getRegisterHoliday:", status, error);
        }
    });
}

function updateHoliday() {
    var id = $("#idEdit").val();
    var fecha_original = $("#fecha_original").val();
    var fecha = $("#fechaEdit").val();
    var nombre = $("#nombreEdit").val().trim(); // Limpiar espacios al inicio/fin
    var es_recurrente = $("#es_recurrenteEdit").is(":checked") ? 1 : 0;

    // Validar nombre
    nombre = nombre.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]/g, '');
    $("#nombreEdit").val(nombre); // Actualizar campo con valor limpio

    // Validaciones básicas
    if (!fecha) {
        notify("La fecha es requerida", 1500, "error", "top-end");
        return;
    }
    
    if (!nombre) {
        $("#nombreEdit").focus();
        notify("El campo nombre es obligatorio", 1500, "error", "top-end");
        return;
    }

    if(!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]+$/.test(nombre)){
        $("#nombreEdit").focus();
        notify("El nombre solo puede contener letras, números y espacios", 1500, "error", "top-end");
        return;
    }

    $.ajax({
        url: "ajax.php?mode=saveholiday",
        type: "POST",
        dataType: "json",
        data: {
            id: id,
            fecha_original: fecha_original,
            fecha: fecha,
            nombre: nombre,
            es_recurrente: es_recurrente
        },
        success: function(response) {
            if (typeof response === 'object' && response !== null) {
                if (response.success) {
                    notify(response.message, 1500, "success", "top-end");
                    $("#ModalEditHoliday").modal("hide");
                    getHolidays();
                } else {
                    notify(response.message, 2000, "error", "top-end");
                }
            } else {
                console.error("Respuesta inválida:", response);
                notify("Error al procesar la respuesta", 2000, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición:", status, error);
            notify("Error al conectar con el servidor", 2000, "error", "top-end");
        }
    });
}

function confirmDeleteHoliday(fecha, nombre) {
    notifyConfirm(
        "¿Estás seguro?",
        "Se va a eliminar el día feriado: " + nombre,
        "warning",
        "deleteHoliday('" + fecha + "')"
    );
}

function deleteHoliday(fecha) {
    $.ajax({
        url: "ajax.php?mode=deleteholiday",
        type: "POST",
        data: { fecha: fecha },
        success: function(datos) {
            var respuesta = JSON.parse(datos);
            if (respuesta["codigo"] == "1") {
                notify(respuesta["alerta"], 1500, "success", "top-end");
                getHolidays();
                cleanFormHoliday();
            } else {
                notify(respuesta["alerta"], 2500, "error", "top-end");
            }
        }
    });
}

function cleanFormHoliday() {
    $("#fecha").val("");
    $("#nombre").val("");
    $("#es_recurrente").prop("checked", false);
    
    $("#fechaEdit").val("");
    $("#nombreEdit").val("");
    $("#es_recurrenteEdit").prop("checked", false);
    $("#fecha_original").val("");
    $("#idEdit").val("");
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>