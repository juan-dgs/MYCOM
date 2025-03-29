<?php
    include(HTML.'AdminPanel/masterPanel/head.php');
    include(HTML.'AdminPanel/masterPanel/navbar.php');
    include(HTML.'AdminPanel/masterPanel/menu.php');
    include(HTML.'AdminPanel/masterPanel/breadcrumb.php');


    $qTipo = 'SELECT codigo,descripcion FROM act_c_tipos WHERE activo=1;';
    $dt_tipo=findtablaq($qTipo,"codigo");

    $qClasifica = 'SELECT codigo,descripcion FROM act_c_clasificacion WHERE activo = 1;';
    $dt_clasifica=findtablaq($qClasifica,"codigo");

    $qPrio = 'SELECT codigo,descripcion,hr_min,hr_max FROM act_c_prioridades WHERE activo = 1;';
    $dt_prio=findtablaq($qPrio,"codigo");

    $qCliente= 'SELECT id,concat(alias," - ",razon_social) as nombre FROM act_c_clientes WHERE activo = 1;';
    $dt_cliente=findtablaq($qCliente,"id");


    $qUsuarios= 'SELECT id,concat(nombre," ",apellido_p," ",apellido_m) as nombre,usuario,c_tipo_usuario as tipo,dir_foto as foto FROM users WHERE activo =1;';
    $dt_usuarios=findtablaq($qUsuarios,"id");

?>



  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <script src="<?php echo ADMINLTE; ?>plugins/select2/js/select2.full.min.js"></script>


<style>
 

  
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

    .novisible{
        display:none;
    }

</style>



<button class="btn btn-primary" type="button" onclick="newActividad();" style="    position: absolute;    z-index: 1000;"><i class="fa fa-plus"></i>Nueva Actividad</button>

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
                            <select class="form-control select2ModalActividades" id="c_tipo_actividad">
                            <?php     foreach ($dt_tipo as $codigo => $array) {
                              echo "<option value='".$dt_tipo[$codigo]["codigo"]."'>".$dt_tipo[$codigo]["descripcion"]."</option>";
                          } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_clasifica_act">Clasificación:</label>
                            <select class="form-control select2ModalActividades" id="c_clasifica_act">
                            <option value="">Seleciona Clasificación</option>
                            <?php     foreach ($dt_clasifica as $codigo => $array) {
                              echo "<option value='".$dt_clasifica[$codigo]["codigo"]."'>".$dt_clasifica[$codigo]["descripcion"]."</option>";
                          } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_tipo_actividad">Prioridad:</label>
                            <select class="form-control select2ModalActividades" id="c_prioridad">
                            <option value="">Seleciona Prioridad</option>
                            <?php     foreach ($dt_prio as $codigo => $array) {
                              echo "<option value='".$dt_prio[$codigo]["codigo"]."' title='SLA max ".$dt_prio[$codigo]["hr_max"]." hrs - min ".$dt_prio[$codigo]["hr_min"]." hrs'>".$dt_prio[$codigo]["descripcion"]."</option>";
                          } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_cliente">Cliente:</label>
                            <select class="form-control select2ModalActividades" id="c_cliente">
                                <option value="">Seleciona Cliente Responsable</option>
                                <?php     foreach ($dt_cliente as $codigo => $array) {
                                echo "<option value='".$dt_cliente[$codigo]["id"]."'>".$dt_cliente[$codigo]["nombre"]."</option>";
                            } ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group" style="position:relative;">
                            <label for="c_usuario_responsable">Usuario Responsable:</label>
                            <select class="form-control select2ModalActividades" id="c_usuario_responsable">
                            <option value="">Seleciona Usuarios Responsable</option>
                                <?php     foreach ($dt_usuarios as $codigo => $array) {
                                echo "<option value='".$dt_usuarios[$codigo]["id"]."' foto='".($dt_usuarios[$codigo]["foto"]!=''?$dt_usuarios[$codigo]["foto"]:'userDefault.png')."' usuario='".$dt_usuarios[$codigo]["usuario"]."' tipo='".$dt_usuarios[$codigo]["tipo"]."' title='".$dt_usuarios[$codigo]["usuario"]." ".$dt_usuarios[$codigo]["tipo"]."'>".$dt_usuarios[$codigo]["nombre"]."</option>";
                            } ?>
                            </select>
                            <div id="c_usuario_responsable_foto" class="circular novisible" style="background-image: url(views/images/profile/userDefault.png);background-size:  cover; width: 60px;height: 60px;border: solid 2px #fff;position: absolute;top: 0;right: 16px; "></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_usuarios_ivolucrados">Usuarios Involucrados:</label>
                            <select class="form-control select2ModalActividades" id="c_usuarios_ivolucrados" name="ivolucrados[]" multiple="multiple">>
                                <?php     foreach ($dt_usuarios as $codigo => $array) {
                                echo "<option value='".$dt_usuarios[$codigo]["id"]."' foto='".($dt_usuarios[$codigo]["foto"]!=''?$dt_usuarios[$codigo]["foto"]:'userDefault.png')."' usuario='".$dt_usuarios[$codigo]["usuario"]."' tipo='".$dt_usuarios[$codigo]["tipo"]."' title='".$dt_usuarios[$codigo]["usuario"]." ".$dt_usuarios[$codigo]["tipo"]."'>".$dt_usuarios[$codigo]["nombre"]."</option>";
                            } ?>
                            </select>
                            <div id="c_usuario_involucrados_fotos"></div>
                            
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

    $('.select2ModalActividades').select2({
      theme: 'bootstrap4',
      dropdownParent: $('#modalFormularioActiviades')
    });
    
    
    setTimeout(defineFnCUR(), 3000);
});

function defineFnCUR(){
        $(".select2ModalActividades").change(function(){
           if($(this).attr("id")=='c_usuario_responsable'){
            $('#c_usuarios_ivolucrados option').prop("disabled",false);
            
            if($(this).val()==''){
                $("#c_usuario_responsable_foto").addClass('novisible').css('background-image','');
            }else{
                $("#c_usuario_responsable_foto").removeClass('novisible').css('background-image','url("views/images/profile/'+$('option:selected',this).attr("foto")+'")');
                $('#c_usuarios_ivolucrados option[value="' + $(this).val() + '"]').prop("disabled",true);

                if($('#c_usuarios_ivolucrados').val().indexOf($(this).val())>=0){
                    $('#c_usuarios_ivolucrados').select2().val($(this).val()).trigger("change");
                }

            }
                fotosInvolucrados($('#c_usuarios_ivolucrados').val());

           }else if($(this).attr("id")=='c_usuarios_ivolucrados'){
                fotosInvolucrados($(this).val());
           }
        });
    }

    function fotosInvolucrados(arr){
        var fotos ='';
        if(arr.length>0){
            $.each(arr, function( index, value ) {
                fotos += '<div title="Usuario Involucrado: '+$('#c_usuarios_ivolucrados option[value="' + value + '"]').html() +'" class="circular" style="background-image: url(views/images/profile/'+$('#c_usuarios_ivolucrados option[value="' + value + '"]').attr("foto")+');  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff; "></div>';
            });
        }
      

        $("#c_usuario_involucrados_fotos").html(fotos);
    }


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

                var arrayOrder = [];         //[14, 'asc'], [0, 'asc'], [3, 'asc'], [5, 'asc']
                var arrayExport = ['excel']; //'excel'
                datatablebase("tablaActividades", false, 500, true, true, arrayOrder, arrayExport);
                //datatablebase(tableid, ffoot, scroll, order, search, arrayOrder, arrayExport)
            }
        });
    }

    function newActividad(){
        $("#modalFormularioActiviades").modal();
        $("#titleFormularioActiviades").html("<i class='fa fa-plus'></i> Captura nueva actividad");
        $("#btnsFormularioActiviades").html('<button type="button" class="btn btn-success" onclick="saveActividad()">Guardar</button>'+
                                            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
    }
</script>


<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>
