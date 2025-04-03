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
                <button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddClient">
                    <span class="glyphicon glyphicon-plus"></span> Agregar Cliente
                </button>

                <div id="contentClients">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Cliente -->
<div id="ModalAddClient" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Cliente</h4>
            </div>
            <div class="modal-body" id="clientForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc">RFC:</label>
                            <input type="text" class="form-control" id="rfc" placeholder="Ingrese RFC (Opcional)" maxlength="13">
                        </div>
                        <div class="form-group">
                            <label for="alias">Alias: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="alias" placeholder="Ingrese alias comercial" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="razon_social">Razón Social: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="razon_social" placeholder="Ingrese razón social" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilio">Domicilio:</label>
                            <input type="text" class="form-control" id="domicilio" placeholder="Ingrese domicilio fiscal (Opcional)" maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="contacto">Contacto:</label>
                            <input type="text" class="form-control" id="contacto" placeholder="Persona de contacto (Opcional)" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="text" class="form-control" id="correo" placeholder="Ingrese correo electrónico (Opcional)" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" placeholder="Ingrese teléfono (Opcional)" maxlength="15">
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
                <button type="button" class="btn btn-success" onclick="newClient()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente -->
<div id="ModalEditClient" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Cliente: <span id="editClientSel"></span></h4>
            </div>
            <div class="modal-body" id="clientFormEdit">
                <input type="hidden" id="idEdit">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfcEdit">RFC:</label>
                            <input type="text" class="form-control" id="rfcEdit" placeholder="Ingrese RFC (Opcional)" maxlength="13">
                        </div>
                        <div class="form-group">
                            <label for="aliasEdit">Alias: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="aliasEdit" placeholder="Ingrese alias comercial" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="razon_socialEdit">Razón Social: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control required" id="razon_socialEdit" placeholder="Ingrese razón social" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilioEdit">Domicilio:</label>
                            <input type="text" class="form-control" id="domicilioEdit" placeholder="Ingrese domicilio fiscal (Opcional)" maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="contactoEdit">Contacto:</label>
                            <input type="text" class="form-control" id="contactoEdit" placeholder="Persona de contacto (Opcional)" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="correoEdit">Correo:</label>
                            <input type="text" class="form-control" id="correoEdit" placeholder="Ingrese correo electrónico (Opcional)" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="telefonoEdit">Teléfono:</label>
                            <input type="text" class="form-control" id="telefonoEdit" placeholder="Ingrese teléfono (Opcional)" maxlength="15">
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
                <button type="button" class="btn btn-success" onclick="saveClient()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    getClients();
});

function getClients() {
    $.ajax({
        url: "ajax.php?mode=getclients",
        type: "POST",
        data: {},
        error: function(request, status, error) {
            notify('Error al cargar clientes: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            $("#contentClients").html(datos);
            
            var arrayOrder = [[3, 'asc']]; // Ordenar por Razón Social por defecto
            var arrayExport = ['excel'];
            datatablebase("tablaClientes", false, 400, true, true, arrayOrder, arrayExport);
        }
    });
}

function newClient() {
    var rfc = $("#rfc").val().trim();
    var alias = $("#alias").val().trim();
    var razon_social = $("#razon_social").val().trim();
    var domicilio = $("#domicilio").val().trim();
    var contacto = $("#contacto").val().trim();
    var correo = $("#correo").val().trim();
    var telefono = $("#telefono").val().trim();

    // Validaciones
    if(alias === "") {
        $("#alias").focus(); 
        notify("El campo alias es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    if(razon_social === "") {
        $("#razon_social").focus(); 
        notify("El campo razón social es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    if(correo !== "" && !validateEmail(correo)) {
        $("#correo").focus();
        notify("El formato del correo no es válido", 1500, "error", "top-end");
        return;
    }

    var btn = $("#ModalAddClient").find(".btn-success");
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: "ajax.php?mode=newclient",
        type: "POST",
        dataType: 'json',
        data: {
            rfc: rfc,
            alias: alias,
            razon_social: razon_social,
            domicilio: domicilio,
            contacto: contacto,
            correo: correo,
            telefono: telefono
        },
        success: function(respuesta) {
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    $('#ModalAddClient').modal('hide');
                    cleanFormClients();
                    notify(respuesta.alerta, 1500, "success", "top-end");
                    getClients();
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

function confirmDeleteClient(id, razon_social){
    notifyConfirm("¿Estás seguro?", "Se va a desactivar el cliente: " + razon_social, "warning","deleteClient('"+id+"')");
    }

function deleteClient(id) {
    
    $.ajax({  
        url: "ajax.php?mode=deleteclient",
        type: "POST",
        data: { id: id },
        dataType: 'json',
        success: function(respuesta) {
            
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    notify(respuesta.alerta, 1500, "success", "top-end");
                    getClients();
                    
                } else {
                    notify(respuesta.alerta || "Error al eliminar cliente", 1500, "error", "top-end");
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

function cleanFormClients(){
    $("#rfc, #alias, #razon_social, #domicilio, #contacto, #correo, #telefono").val("");
}

function getClient(id, rfc) {
    $("#editClientSel").html(rfc);
    $("#ModalEditClient").modal("show");
    $("#idEdit").val(id);
    
    $.ajax({
        url: "ajax.php?mode=getregister",
        type: "POST",
        data: {
            tabla: "act_c_clientes",
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
                    $("#ModalEditClient").modal("hide");
                } else {
                    $("#rfcEdit").val(respuesta[1]["rfc"]);
                    $("#aliasEdit").val(respuesta[1]["alias"]);
                    $("#razon_socialEdit").val(respuesta[1]["razon_social"]);
                    $("#domicilioEdit").val(respuesta[1]["domicilio"]);
                    $("#contactoEdit").val(respuesta[1]["contacto"]);
                    $("#correoEdit").val(respuesta[1]["correo"]);
                    $("#telefonoEdit").val(respuesta[1]["telefono"]);
                }
            } catch(e) {
                notify("Error al procesar datos del cliente: " + e.message, 1500, "error", "top-end");
            }
        }
    });
}

function saveClient() {
    var id = $("#idEdit").val();
    var alias = $("#aliasEdit").val().trim();
    var razon_social = $("#razon_socialEdit").val().trim();
    var rfc = $("#rfcEdit").val().trim();
    var domicilio = $("#domicilioEdit").val().trim();
    var contacto = $("#contactoEdit").val().trim();
    var correo = $("#correoEdit").val().trim();
    var telefono = $("#telefonoEdit").val().trim();

    if(alias === "") {
        $("#aliasEdit").focus();
        notify("El campo alias es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    if(razon_social === "") {
        $("#razon_socialEdit").focus();
        notify("El campo razón social es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    if(correo !== "" && !validateEmail(correo)) {
        $("#correoEdit").focus();
        notify("El formato del correo no es válido", 1500, "error", "top-end");
        return;
    }

    var btn = $("#ModalEditClient").find(".btn-success");
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: "ajax.php?mode=saveclient",
        type: "POST",
        dataType: 'json',
        data: {
            id: id,
            alias: alias,
            razon_social: razon_social,
            rfc: rfc,
            domicilio: domicilio,
            contacto: contacto,
            correo: correo,
            telefono: telefono
        },
        success: function(respuesta) {
            if(respuesta && typeof respuesta.codigo !== 'undefined') {
                if(respuesta.codigo == 1) {
                    notify(respuesta.alerta, 2000, "success", "top-end");
                    $("#ModalEditClient").modal("hide");
                    getClients();
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

function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>