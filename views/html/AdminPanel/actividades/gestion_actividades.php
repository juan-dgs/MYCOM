<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');

?>

<style>
    .circular{
        display: inline-block;
    border-radius: 150px;
    -webkit-border-radius: 150px;
    -moz-border-radius: 150px;
    box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.37);
    -webkit-box-shadow: 1px 1px 8px rgba(0, 0, 0, 0.32);
    -moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
    }

  
    .avance{
        border-radius: 10px;
        height: 40px;
        position: relative;
        width: 100%;
        border: solid #07070726;
    }

    .avance b{
        position: absolute;
        top: 18px;
        left: 10px;
        font-size: 15px;
        font-weight: bold;
    }

</style>

<button class="btn btn-primary" type="button" onclick="newActividad();"><i class="fa fa-plus"></i></button>

<div id="contentActs">
</div>

<div id="modalFormularioActiviades" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titleFormularioActiviades"></h4>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_tipo_actividad">Tipo de Actividad:</label>
                            <select  id="c_tipo_actividad"></select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_clasifica_act">Clasificación:</label>
                            <select  id="c_clasifica_act"></select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_tipo_actividad">Prioridad:</label>
                            <select  id="c_prioridad"></select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_tipo_actividad">Cliente:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text" id="id_cliente"></div>
                                </div>
                                <input type="text" class="form-control" placeholder="Buscar Cliente" id="searchCliente">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="searchUsuarioP">Usuario Responsable:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div title="Usuario Responsable: Luis Grardoa Saucedo" class="circular" style="background: url(views/images/profile/userDefault.png);background-size: cover;width: 60px;height: 60px;border: solid 2px #fff;margin-top: -13px; "></div>
                                </div>
                                <input type="text" class="form-control" placeholder="Buscar Usuario" id="searchUsuarioP">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="searchUsuarioI">Usuarios Involucrados:</label>
                            <div class="input-group">                               
                                <input type="text" class="form-control" placeholder="Buscar Usuario" id="searchUsuarioI">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div title="Usuario Responsable: Luis Grardoa Saucedo" class="circular" style="background: url(views/images/profile/userDefault.png);  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff; "></div>
                            <div title="Usuario Responsable: Luis Grardoa Saucedo" class="circular" style="background: url(views/images/profile/userDefault.png);  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff; "></div>
                            <div title="Usuario Responsable: Luis Grardoa Saucedo" class="circular" style="background: url(views/images/profile/userDefault.png);  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff; "></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>             
                            <textarea id="descripcion" class="form-control" col="5"></textarea>               
                        </div>
                    </div>

                    <div class="col-xs-12 col-sms-4">
                        <div class="form-group">
                            <label for="comentario">Comentario:</label>             
                            <textarea id="comentario" class="form-control" col="5"></textarea>               
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="notas">Notas:</label>             
                            <textarea id="notas" class="form-control" col="5"></textarea>               
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="dispositivo">Dispositivo:</label>             
                            <div class="form-inline">
                                <label for="serie">Serie:</label>
                                <input type="text" id="serie" class="form-control" placeholder="serie">
                            </div>
                            <div class="form-inline">
                                <label for="mac">MAC:</label>
                                <input type="text" id="mac" class="form-control" placeholder="mac">
                            </div>
                            <div class="form-inline">
                                <label for="otro">Otro:</label>
                                <input type="text" id="otro" class="form-control" placeholder="otro">
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="otro">Fecha Inicio:</label>
                            <input type="date" id="f_plan_i" class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="otro">Fecha Fin:</label>
                            <input type="date" id="f_plan_f" class="form-control">
                        </div>
                    </div>


                </div>

               
               
                    
            </div>         
            <div class="modal-footer" id="btnsFormularioActiviades">                
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    getActividades();

});

    function getActividades(){
        $.ajax({
            url: "ajax.php?mode=getactividades",
            type: "POST",
            data: {},
            error : function(request, status, error) {
                notify('Error inesperado, consulte a soporte.'+request+status+error,1500,"error","top-end");
            },
            beforeSend: function() {
            },
            success: function(datos){
                $("#contentActs").html(datos);
            }
        });
    }

    function newActividad(){
        $("#modalFormularioActiviades").modal();
        $("#titleFormularioActiviades").html("Captura nueva actividad");
        $("#btnsFormularioActiviades").html('<button type="button" class="btn btn-success" onclick="saveActividad()">Guardar</button>'+
                                            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
    }
</script>


<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>
