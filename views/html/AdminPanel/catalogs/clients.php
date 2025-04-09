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
        <!-- Contenido del modal existente... -->
    </div>
</div>

<!-- Modal Editar Cliente -->
<div id="ModalEditClient" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Contenido del modal existente... -->
    </div>
</div>

<!-- Modal para editar foto de cliente -->
<div id="ModalEditClientPhoto" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Foto de Cliente</h4>
            </div>
            <div class="modal-body">
                <form id="formEditClientPhoto" enctype="multipart/form-data">
                    <input type="hidden" id="clientIdPhoto" name="id">
                    <div class="form-group text-center">
                        <img id="previewClientPhoto" src="" style="width:150px;height:150px;border-radius:50%;object-fit:cover;margin-bottom:15px;">
                        <input type="file" class="form-control" id="fotoCliente" name="foto" accept="image/*">
                        <p class="help-block">Formatos permitidos: JPG, PNG, GIF (Máx. 2MB)</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveClientPhoto()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    getClients();
    
    // Previsualización de foto
    $("#fotoCliente").change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewClientPhoto').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
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

// Función para abrir el modal de edición de foto de cliente
function editClientPhoto(id, currentPhoto) {
    $("#clientIdPhoto").val(id);
    var fotoUrl = currentPhoto ? 'views/images/clients/'+currentPhoto : 'views/images/clients/clientDefault.png';
    $("#previewClientPhoto").attr('src', fotoUrl);
    $("#ModalEditClientPhoto").modal("show");
}

// Función para guardar la foto del cliente
function saveClientPhoto() {
    var formData = new FormData($("#formEditClientPhoto")[0]);
    
    if(!$("#fotoCliente").val()) {
        notify("Debes seleccionar una imagen", 1500, "error", "top-end");
        return;
    }
    
    var btn = $("#ModalEditClientPhoto").find(".btn-success");
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    
    $.ajax({
        url: "ajax.php?mode=updateclientphoto",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        error: function(request, status, error) {
            notify('Error al actualizar foto: ' + error, 1500, "error", "top-end");
            btn.prop('disabled', false).html('Guardar');
        },
        success: function(datos) {
            try {
                var respuesta = JSON.parse(datos);
                if(respuesta.codigo == 1) {
                    notify(respuesta.alerta, 1500, "success", "top-end");
                    $("#ModalEditClientPhoto").modal("hide");
                    getClients(); // Recargar la tabla para mostrar la nueva foto
                } else {
                    notify(respuesta.alerta, 1500, "error", "top-end");
                }
            } catch(e) {
                notify("Error al procesar respuesta del servidor", 1500, "error", "top-end");
            }
            btn.prop('disabled', false).html('Guardar');
        }
    });
}

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

// Función para abrir el modal de edición de foto de cliente
function editClientPhoto(id, currentPhoto) {
    $("#clientIdPhoto").val(id);
    var fotoUrl = currentPhoto ? 'views/images/clients/'+currentPhoto : 'views/images/clients/clientDefault.png';
    $("#previewClientPhoto").attr('src', fotoUrl);
    $("#ModalEditClientPhoto").modal("show");
}

// Función para previsualizar la imagen seleccionada
$("#fotoCliente").change(function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#previewClientPhoto').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    }
});

// Función para guardar la foto del cliente
function saveClientPhoto() {
    var formData = new FormData($("#formEditClientPhoto")[0]);
    
    if(!$("#fotoCliente").val()) {
        notify("Debes seleccionar una imagen", 1500, "error", "top-end");
        return;
    }
    
    $.ajax({
        url: "ajax.php?mode=updateclientphoto",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        error: function(request, status, error) {
            notify('Error al actualizar foto: ' + error, 1500, "error", "top-end");
        },
        success: function(datos) {
            var respuesta = JSON.parse(datos);
            if(respuesta.codigo == 1) {
                notify(respuesta.alerta, 1500, "success", "top-end");
                $("#ModalEditClientPhoto").modal("hide");
                getClients(); // Recargar la tabla para mostrar la nueva foto
            } else {
                notify(respuesta.alerta, 1500, "error", "top-end");
            }
        }
    });
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>