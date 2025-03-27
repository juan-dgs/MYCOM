<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<script src="views/js/usersForm.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary" data-toggle="modal" data-target="#ModalAddClient">
                    <span class="glyphicon glyphicon-plus"></span> Agregar Cliente
                </button>

                <div id="contentClients" style="margin-top: 20px;">
                    <!-- Aquí se cargará la tabla de clientes -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar Cliente -->
<div id="ModalAddClient" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar Cliente</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc">RFC:</label>
                            <input type="text" class="form-control" id="rfc" placeholder="Ingrese RFC" maxlength="20" required>
                        </div>
                        <div class="form-group">
                            <label for="alias">Alias:</label>
                            <input type="text" class="form-control" id="alias" placeholder="Ingrese Alias" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="razon_social">Razón Social:</label>
                            <input type="text" class="form-control" id="razon_social" placeholder="Ingrese Razón Social" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilio">Domicilio:</label>
                            <input type="text" class="form-control" id="domicilio" placeholder="Ingrese Domicilio" maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="contacto">Contacto:</label>
                            <input type="text" class="form-control" id="contacto" placeholder="Ingrese Contacto" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" placeholder="Ingrese Teléfono" maxlength="20">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" class="form-control" id="correo" placeholder="Ingrese Correo" maxlength="100">
                        </div>
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

<!-- Modal para Editar Cliente -->
<div id="ModalEditClient" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Cliente: <span id="editClientName"></span></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editClientId">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfcEdit">RFC:</label>
                            <input type="text" class="form-control" id="rfcEdit" placeholder="Ingrese RFC" maxlength="20" required>
                        </div>
                        <div class="form-group">
                            <label for="aliasEdit">Alias:</label>
                            <input type="text" class="form-control" id="aliasEdit" placeholder="Ingrese Alias" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="razon_socialEdit">Razón Social:</label>
                            <input type="text" class="form-control" id="razon_socialEdit" placeholder="Ingrese Razón Social" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilioEdit">Domicilio:</label>
                            <input type="text" class="form-control" id="domicilioEdit" placeholder="Ingrese Domicilio" maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="contactoEdit">Contacto:</label>
                            <input type="text" class="form-control" id="contactoEdit" placeholder="Ingrese Contacto" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="telefonoEdit">Teléfono:</label>
                            <input type="text" class="form-control" id="telefonoEdit" placeholder="Ingrese Teléfono" maxlength="20">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="correoEdit">Correo:</label>
                            <input type="email" class="form-control" id="correoEdit" placeholder="Ingrese Correo" maxlength="100">
                        </div>
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

// Función para cargar los clientes
function getClients(){
    $.ajax({
        url: "ajax.php?mode=getclients",
        type: "POST",
        data: {},
        error: function(request, status, error) {
            notify('Error al cargar clientes: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            $("#contentClients").html(datos);
        }
    });
}

// Función para abrir modal de edición con datos del cliente
function GetClient(id, alias){
    $("#editClientName").html(alias);
    $("#ModalEditClient").modal("show");
    
    $.ajax({
        url: "ajax.php?mode=getclients",
        type: "POST",
        data: { id: id },
        error: function(request, status, error) {
            notify('Error al cargar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var cliente = JSON.parse(datos);
            $("#editClientId").val(id);
            $("#rfcEdit").val(cliente.rfc);
            $("#aliasEdit").val(cliente.alias);
            $("#razon_socialEdit").val(cliente.razon_social);
            $("#domicilioEdit").val(cliente.domicilio);
            $("#contactoEdit").val(cliente.contacto);
            $("#telefonoEdit").val(cliente.telefono);
            $("#correoEdit").val(cliente.correo);
        }
    });
}

// Función para guardar un nuevo cliente
function newClient(){
    var rfc = $("#rfc").val();
    var alias = $("#alias").val();
    var razon_social = $("#razon_social").val();
    var domicilio = $("#domicilio").val();
    var contacto = $("#contacto").val();
    var telefono = $("#telefono").val();
    var correo = $("#correo").val();
    
    // Validaciones básicas
    if(alias == "") {
        notify("El campo Alias es obligatorio", 1500, "error", "top-end");
        return;
    }
    else if(razon_social == "") {
        notify("El campo Razón Social es obligatorio", 1500, "error", "top-end");
        return;
    }
    
    $.ajax({
        url: "ajax.php?mode=newclient",
        type: "POST",
        data: {
            rfc: rfc,
            alias: alias,
            razon_social: razon_social,
            domicilio: domicilio,
            contacto: contacto,
            telefono: telefono,
            correo: correo
        },
        error: function(request, status, error) {
            notify('Error al guardar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var respuesta = JSON.parse(datos);
            if(respuesta.codigo == "1") {
                $('#ModalAddClient').modal('hide');
                getClients();
                // Limpiar formulario
                $("#rfc, #alias, #razon_social, #domicilio, #contacto, #telefono, #correo").val("");
                notify(respuesta.alerta, 1500, "success", "top-end");
            } else {
                notify(respuesta.alerta, 1500, "error", "top-end");
            }
        }
    });
}



// Función para guardar cambios en cliente existente
function saveClient(){
    var id = $("#editClientId").val();
    var rfc = $("#rfcEdit").val();
    var alias = $("#aliasEdit").val();
    var razon_social = $("#razon_socialEdit").val();
    var domicilio = $("#domicilioEdit").val();
    var contacto = $("#contactoEdit").val();
    var telefono = $("#telefonoEdit").val();
    var correo = $("#correoEdit").val();
    
    // Validaciones básicas
    if(rfc == "" || alias == "" || razon_social == "") {
        notify("Los campos RFC, Alias y Razón Social son obligatorios", 1500, "error", "top-end");
        return;
    }
    
    $.ajax({
        url: "ajax.php?mode=saveClient",
        type: "POST",
        data: {
            id: id,
            rfc: rfc,
            alias: alias,
            razon_social: razon_social,
            domicilio: domicilio,
            contacto: contacto,
            telefono: telefono,
            correo: correo
        },
        error: function(request, status, error) {
            notify('Error al actualizar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var respuesta = JSON.parse(datos);
            if(respuesta.codigo == "1") {
                $('#ModalEditClient').modal('hide');
                getClients();
                notify(respuesta.alerta, 1500, "success", "top-end");
            } else {
                notify(respuesta.alerta, 1500, "error", "top-end");
            }
        }
    });
}

// Función para eliminar cliente
function confirmDeleteClient(id, alias){
    notifyConfirm(
        "¿Estás seguro?", 
        "Se va a eliminar el cliente: " + alias, 
        "warning", 
        "deleteClient('"+id+"')"
    );
}

function deleteClient(id){
    $.ajax({
        url: "ajax.php?mode=deleteClient",
        type: "POST",
        data: { id: id },
        error: function(request, status, error) {
            notify('Error al eliminar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var respuesta = JSON.parse(datos);
            notify(respuesta.alerta, 1500, "success", "top-end");
            getClients();
        }
    });
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>