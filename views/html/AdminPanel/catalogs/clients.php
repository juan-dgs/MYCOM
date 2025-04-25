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
                <button class="btn btn-primary expandable-btn" data-toggle="modal" data-target="#ModalAddClient">
                    <span class="fas fa-plus" style="margin-right:10px;"></span> Nuevo Cliente
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
                <h4 class="modal-title"><span class="fas fa-plus" style="margin-right:10px;"></span>Nuevo Cliente</h4>
            </div>
            <div class="modal-body">
                <form id="formAddClient">
                    <div id="validationAlerts"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alias">Alias <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="alias" name="alias" placeholder="Alias o nombre corto del cliente" oninput="validateNoSpecialChars(this, 'alias')" required>
                                <small id="aliasAlert" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="razon_social">Razón Social <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón social completa" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rfc">RFC</label>
                                <input type="text" class="form-control" id="rfc" name="rfc" placeholder="RFC del cliente" oninput="validateRFC(this.value, 'add')" onblur="validateRFC(this.value, 'add')">
                                <small id="rfcAlert" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="10 digitos" oninput="validatePhoneInput(this)" onblur="validatePhone(this.value, 'add')">
                                <small id="phoneAlert" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="domicilio">Domicilio</label>
                        <input type="text" class="form-control" id="domicilio" name="domicilio" placeholder="Dirección completa">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contacto">Contacto</label>
                                <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Persona de contacto" oninput="validateNoSpecialChars(this, 'contacto')">
                                <small id="contactoAlert" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="correo">Correo Electrónico</label>
                                <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" onblur="validateEmail(this.value, 'add')">
                                <small id="emailAlert" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                    </div>
                </form>
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
                <h4 class="modal-title"> <span class="fas fa-pencil" style="margin-right:10px;"></span>Editar Cliente: <span id="editClientSel"></span></h4>
            </div>
            <div class="modal-body">
                <form id="formEditClient">
                    <input type="hidden" id="idEdit" name="id">
                    <div id="validationAlertsEdit"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="aliasEdit">Alias <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="aliasEdit" name="alias" placeholder="Alias o nombre corto del cliente" oninput="validateNoSpecialChars(this, 'aliasEdit')" required>
                                <small id="aliasAlertEdit" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="razon_socialEdit">Razón Social <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="razon_socialEdit" name="razon_social" placeholder="Razón social completa" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rfcEdit">RFC</label>
                                <input type="text" class="form-control" id="rfcEdit" name="rfc" placeholder="RFC del cliente" oninput="validateRFC(this.value, 'edit')" onblur="validateRFC(this.value, 'edit')">
                                <small id="rfcAlertEdit" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefonoEdit">Teléfono</label>
                                <input type="text" class="form-control" id="telefonoEdit" name="telefono" placeholder="Teléfono de contacto" oninput="validatePhoneInput(this)" onblur="validatePhone(this.value, 'edit')">
                                <small id="phoneAlertEdit" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="domicilioEdit">Domicilio</label>
                        <input type="text" class="form-control" id="domicilioEdit" name="domicilio" placeholder="Dirección completa">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contactoEdit">Contacto</label>
                                <input type="text" class="form-control" id="contactoEdit" name="contacto" placeholder="Persona de contacto" oninput="validateNoSpecialChars(this, 'contactoEdit')">
                                <small id="contactoAlertEdit" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="correoEdit">Correo Electrónico</label>
                                <input type="text" class="form-control" id="correoEdit" name="correo" placeholder="Correo electrónico" onblur="validateEmail(this.value, 'edit')">
                                <small id="emailAlertEdit" class="text-danger" style="display:none;"></small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveClient()">Guardar Cambios</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
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

// Función para validar que no contenga caracteres especiales
function validateNoSpecialChars(inputElement, fieldId) {
    var value = inputElement.value;
    // Permitir letras, números, espacios y algunos caracteres básicos
    var regex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-]*$/;
    
    if (!regex.test(value)) {
        // Eliminar caracteres no permitidos
        inputElement.value = value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-]/g, '');
        
        // Mostrar alerta debajo del campo
        if(fieldId.includes('Edit')) {
            $("#"+fieldId+"AlertEdit").text("No se permiten caracteres especiales").show();
        } else {
            $("#"+fieldId+"Alert").text("No se permiten caracteres especiales").show();
        }
        
        // Mostrar notificación
        notify("No se permiten caracteres especiales en este campo", 1500, "error", "top-end");
        return false;
    } else {
        if(fieldId.includes('Edit')) {
            $("#"+fieldId+"AlertEdit").hide();
        } else {
            $("#"+fieldId+"Alert").hide();
        }
        return true;
    }
}

// Función para validar entrada de teléfono en tiempo real
function validatePhoneInput(inputElement) {
    var value = inputElement.value;
    // Permitir números, espacios, paréntesis y signo + al inicio
    var regex = /^\+?[\d\s\-\(\)]*$/;
    
    if (!regex.test(value)) {
        // Eliminar caracteres no permitidos
        inputElement.value = value.replace(/[^\d\s\-\(\)\+]/g, '');
        
        // Si hay un +, asegurarse que esté al inicio
        if (value.includes('+') && !value.startsWith('+')) {
            inputElement.value = value.replace(/\+/g, '');
        }
        
        // Mostrar alerta
        notify("Solo se permiten números, espacios, guiones, paréntesis y signo + al inicio", 1500, "error", "top-end");
        return false;
    }
    return true;
}

// Función para validar RFC
function validateRFC(rfc, type) {
    if(!rfc || rfc.trim() === "") {
        if(type === 'add') {
            $("#rfcAlert").hide();
        } else {
            $("#rfcAlertEdit").hide();
        }
        return true;
    }
    
    // Eliminar espacios y convertir a mayúsculas
    rfc = rfc.replace(/\s/g, '').toUpperCase();
    
    // Patrón para RFC (versión simple)
    const rfcRegex = /^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{2,3}$/;
    const isValid = rfcRegex.test(rfc);
    
    if(type === 'add') {
        if(!isValid) {
            $("#rfcAlert").text("Formato RFC no válido. Ejemplo: ABC123456XYZ").show();
            return false;
        } else {
            $("#rfcAlert").hide();
            return true;
        }
    } else {
        if(!isValid) {
            $("#rfcAlertEdit").text("Formato RFC no válido. Ejemplo: ABC123456XYZ").show();
            return false;
        } else {
            $("#rfcAlertEdit").hide();
            return true;
        }
    }
}

// Función para validar teléfono
function validatePhone(phone, type) {
    if(!phone || phone.trim() === "") {
        if(type === 'add') {
            $("#phoneAlert").hide();
        } else {
            $("#phoneAlertEdit").hide();
        }
        return true;
    }
    
    // Patrón para teléfono (números, espacios, guiones, paréntesis y signo + al inicio)
    const phoneRegex = /^\+?[\d\s\-\(\)]+$/;
    const isValid = phoneRegex.test(phone);
    
    if(type === 'add') {
        if(!isValid) {
            $("#phoneAlert").text("Solo números, espacios, guiones, paréntesis y signo + al inicio").show();
            return false;
        } else {
            $("#phoneAlert").hide();
            return true;
        }
    } else {
        if(!isValid) {
            $("#phoneAlertEdit").text("Solo números, espacios, guiones, paréntesis y signo + al inicio").show();
            return false;
        } else {
            $("#phoneAlertEdit").hide();
            return true;
        }
    }
}

// Función para validar email
function validateEmail(email, type) {
    if(!email || email.trim() === "") {
        if(type === 'add') {
            $("#emailAlert").hide();
        } else {
            $("#emailAlertEdit").hide();
        }
        return true;
    }
    
    // Patrón básico para email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isValid = emailRegex.test(email);
    
    if(type === 'add') {
        if(!isValid) {
            $("#emailAlert").text("Formato de correo no válido. Ejemplo: usuario@dominio.com").show();
            return false;
        } else {
            $("#emailAlert").hide();
            return true;
        }
    } else {
        if(!isValid) {
            $("#emailAlertEdit").text("Formato de correo no válido. Ejemplo: usuario@dominio.com").show();
            return false;
        } else {
            $("#emailAlertEdit").hide();
            return true;
        }
    }
}

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

    // Validaciones obligatorias
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
    
    // Validaciones de formato (mostrar advertencias pero permitir continuar)
    var hasWarnings = false;
    var warningMessages = [];
    
    if(rfc && !validateRFC(rfc, 'add')) {
        warningMessages.push("RFC con formato incorrecto");
        hasWarnings = true;
    }
    
    if(telefono && !validatePhone(telefono, 'add')) {
        warningMessages.push("Teléfono con caracteres no permitidos");
        hasWarnings = true;
    }
    
    if(correo && !validateEmail(correo, 'add')) {
        warningMessages.push("Correo electrónico con formato incorrecto");
        hasWarnings = true;
    }
    
    if(hasWarnings) {
        notify("Advertencia: " + warningMessages.join(", ") + ". Puedes guardar igualmente, pero revisa los datos.", 3000, "warning", "top-end");
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

function saveClient() {
    var id = $("#idEdit").val();
    var alias = $("#aliasEdit").val().trim();
    var razon_social = $("#razon_socialEdit").val().trim();
    var rfc = $("#rfcEdit").val().trim();
    var domicilio = $("#domicilioEdit").val().trim();
    var contacto = $("#contactoEdit").val().trim();
    var correo = $("#correoEdit").val().trim();
    var telefono = $("#telefonoEdit").val().trim();

    // Validaciones obligatorias
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
    
    // Validaciones de formato (mostrar advertencias pero permitir continuar)
    var hasWarnings = false;
    var warningMessages = [];
    
    if(rfc && !validateRFC(rfc, 'edit')) {
        warningMessages.push("RFC con formato incorrecto");
        hasWarnings = true;
    }
    
    if(telefono && !validatePhone(telefono, 'edit')) {
        warningMessages.push("Teléfono con caracteres no permitidos");
        hasWarnings = true;
    }
    
    if(correo && !validateEmail(correo, 'edit')) {
        warningMessages.push("Correo electrónico con formato incorrecto");
        hasWarnings = true;
    }
    
    if(hasWarnings) {
        notify("Advertencia: " + warningMessages.join(", ") + ". Puedes guardar igualmente, pero revisa los datos.", 3000, "warning", "top-end");
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

function cleanFormClients(){
    $("#rfc, #alias, #razon_social, #domicilio, #contacto, #correo, #telefono").val("");
    $("#rfcAlert, #phoneAlert, #emailAlert").hide();
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

function editClientPhoto(id, currentPhoto) {
    $("#clientIdPhoto").val(id);
    var fotoUrl = currentPhoto ? 'views/images/clients/'+currentPhoto : 'views/images/clients/clientDefault.png';
    $("#previewClientPhoto").attr('src', fotoUrl);
    $("#ModalEditClientPhoto").modal("show");
}

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

</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>