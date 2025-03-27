<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<script src="views/js/usersForm.js"></script>

<style>
    .required-field::after {
        content: " *";
        color: #ff0000;
    }
    .tooltip-inner {
        max-width: 200px;
        padding: 8px;
        color: #fff;
        text-align: center;
        background-color: #ff0000;
        border-radius: 4px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modaladdclient">
                    <span class="glyphicon glyphicon-plus"></span> agregar cliente
                </button>

                <div id="contentclients" style="margin-top: 20px;">
                    <!-- aquí se cargará la tabla de clientes -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal para agregar cliente -->
<div id="modaladdclient" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">agregar cliente</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfc">rfc:</label>
                            <input type="text" class="form-control" id="rfc" placeholder="ingrese rfc" maxlength="20">
                        </div>
                        <div class="form-group">
                            <label for="alias" class="required-field" title="campo obligatorio" data-toggle="tooltip">alias:</label>
                            <input type="text" class="form-control" id="alias" placeholder="ingrese alias" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="razon_social" class="required-field" title="campo obligatorio" data-toggle="tooltip">razón social:</label>
                            <input type="text" class="form-control" id="razon_social" placeholder="ingrese razón social" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilio">domicilio:</label>
                            <input type="text" class="form-control" id="domicilio" placeholder="ingrese domicilio" maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="contacto">contacto:</label>
                            <input type="text" class="form-control" id="contacto" placeholder="ingrese contacto" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="telefono">teléfono:</label>
                            <input type="text" class="form-control" id="telefono" placeholder="ingrese teléfono" maxlength="20">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="correo">correo:</label>
                            <input type="email" class="form-control" id="correo" placeholder="ingrese correo" maxlength="100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="newclient()">guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal para editar cliente -->
<div id="modaleditclient" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">editar cliente: <span id="editclientname"></span></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editclientid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rfcedit">rfc:</label>
                            <input type="text" class="form-control" id="rfcedit" placeholder="ingrese rfc" maxlength="20">
                        </div>
                        <div class="form-group">
                            <label for="aliasedit" class="required-field" title="campo obligatorio" data-toggle="tooltip">alias:</label>
                            <input type="text" class="form-control" id="aliasedit" placeholder="ingrese alias" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="razon_socialedit" class="required-field" title="campo obligatorio" data-toggle="tooltip">razón social:</label>
                            <input type="text" class="form-control" id="razon_socialedit" placeholder="ingrese razón social" maxlength="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="domicilioedit">domicilio:</label>
                            <input type="text" class="form-control" id="domicilioedit" placeholder="ingrese domicilio" maxlength="200">
                        </div>
                        <div class="form-group">
                            <label for="contactoedit">contacto:</label>
                            <input type="text" class="form-control" id="contactoedit" placeholder="ingrese contacto" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="telefonoedit">teléfono:</label>
                            <input type="text" class="form-control" id="telefonoedit" placeholder="ingrese teléfono" maxlength="20">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="correoedit">correo:</label>
                            <input type="email" class="form-control" id="correoedit" placeholder="ingrese correo" maxlength="100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveclient()">guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    getclients();
});

// función para cargar los clientes
function getclients(){
    $.ajax({
        url: "ajax.php?mode=getclients",
        type: "POST",
        data: {},
        error: function(request, status, error) {
            notify('error al cargar clientes: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            $("#contentclients").html(datos);
        }
    });
}

// función para abrir modal de edición con datos del cliente
function getclient(id, alias){
    $("#editclientname").html(alias);
    $("#modaleditclient").modal("show");
    
    $.ajax({
        url: "ajax.php?mode=getclient",
        type: "POST",
        data: { id: id },
        error: function(request, status, error) {
            notify('error al cargar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var cliente = JSON.parse(datos);
            $("#editclientid").val(id);
            $("#rfcedit").val(cliente.rfc);
            $("#aliasedit").val(cliente.alias);
            $("#razon_socialedit").val(cliente.razon_social);
            $("#domicilioedit").val(cliente.domicilio);
            $("#contactoedit").val(cliente.contacto);
            $("#telefonoedit").val(cliente.telefono);
            $("#correoedit").val(cliente.correo);
        }
    });
}

// función para guardar un nuevo cliente
function newclient(){
    var rfc = $("#rfc").val();
    var alias = $("#alias").val();
    var razon_social = $("#razon_social").val();
    var domicilio = $("#domicilio").val();
    var contacto = $("#contacto").val();
    var telefono = $("#telefono").val();
    var correo = $("#correo").val();
    
    // validaciones básicas
    if(alias.trim() === "") {
        notify("el campo alias es obligatorio", 1500, "error", "top-end");
        $("#alias").focus();
        return;
    }
    if(razon_social.trim() === "") {
        notify("el campo razón social es obligatorio", 1500, "error", "top-end");
        $("#razon_social").focus();
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
            notify('error al guardar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var respuesta = JSON.parse(datos);
            if(respuesta.codigo == "1") {
                $('#modaladdclient').modal('hide');
                getclients();
                // limpiar formulario
                $("#rfc, #alias, #razon_social, #domicilio, #contacto, #telefono, #correo").val("");
                notify(respuesta.alerta, 1500, "success", "top-end");
            } else {
                notify(respuesta.alerta, 1500, "error", "top-end");
            }
        }
    });
}

// función para guardar cambios en cliente existente
function saveclient(){
    var id = $("#editclientid").val();
    var rfc = $("#rfcedit").val();
    var alias = $("#aliasedit").val();
    var razon_social = $("#razon_socialedit").val();
    var domicilio = $("#domicilioedit").val();
    var contacto = $("#contactoedit").val();
    var telefono = $("#telefonoedit").val();
    var correo = $("#correoedit").val();
    
    // validaciones básicas
    if(alias.trim() === "") {
        notify("el campo alias es obligatorio", 1500, "error", "top-end");
        $("#aliasedit").focus();
        return;
    }
    if(razon_social.trim() === "") {
        notify("el campo razón social es obligatorio", 1500, "error", "top-end");
        $("#razon_socialedit").focus();
        return;
    }
    
    $.ajax({
        url: "ajax.php?mode=saveclient",
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
            notify('error al actualizar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var respuesta = JSON.parse(datos);
            if(respuesta.codigo == "1") {
                $('#modaleditclient').modal('hide');
                getclients();
                notify(respuesta.alerta, 1500, "success", "top-end");
            } else {
                notify(respuesta.alerta, 1500, "error", "top-end");
            }
        }
    });
}

// función para eliminar cliente
function confirmdeleteclient(id, alias){
    notifyconfirm(
        "¿estás seguro?", 
        "se va a eliminar el cliente: " + alias, 
        "warning", 
        "deleteclient('"+id+"')"
    );
}

function deleteclient(id){
    $.ajax({
        url: "ajax.php?mode=deleteclient",
        type: "POST",
        data: { id: id },
        error: function(request, status, error) {
            notify('error al eliminar cliente: '+request+status+error, 1500, "error", "top-end");
        },
        success: function(datos){
            var respuesta = JSON.parse(datos);
            notify(respuesta.alerta, 1500, "success", "top-end");
            getclients();
        }
    });
}
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>