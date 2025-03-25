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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titleFormularioActiviades"></h4>
            </div>
            <div class="modal-body" >
                <div class="form-group">
                    <label for="area">Codigo Del Tipo De Usuario:</label>
                    <input type="text" class="form-control" id="c_tipo_usuario" placeholder="Ingrese Codigo Del Tipo De Usuario" maxlength="50">
                </div>
                c_tipo_actividad                
                c_clasifica_act
                c_prioridad
                
                id_cliente
                id_usuario_resp
                *involucrados*

                descripcion
                comentario
                notas
                dispositivo

                f_plan_i
                f_plan_f


                 
                    
            </div>         
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveActividad()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
    }
</script>


<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>
