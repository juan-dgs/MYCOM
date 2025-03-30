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

  <link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/summernote/summernote-bs4.min.css">
  <script src="<?php echo ADMINLTE; ?>plugins/summernote/summernote-bs4.min.js"></script>

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

    .btn-group.note-insert {
        display: none;
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
            <div class="modal-body"  id="form-actividades">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_tipo_actividad">Tipo de Actividad:</label>
                            <select class="form-control select2ModalActividades" id="c_tipo_actividad" required>
                            <option value="">Seleciona Tipo de Actividad</option>
                            <?php     foreach ($dt_tipo as $codigo => $array) {
                              echo "<option value='".$dt_tipo[$codigo]["codigo"]."'>".$dt_tipo[$codigo]["descripcion"]."</option>";
                          } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_clasifica_act">Clasificación:</label>
                            <select class="form-control select2ModalActividades" id="c_clasifica_act" required>
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
                            <select class="form-control select2ModalActividades" id="c_prioridad" required>
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
                            <select class="form-control select2ModalActividades" id="c_cliente" required>
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
                            <textarea id="descripcion" required></textarea>               
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="comentarios" id="comentarioContC" onclick='collapse("comentarioCont");'><i class="fa fa-angle-down"></i>Comentario:</label> 
                            <div id="comentarioCont" class="collapse">
                                <textarea id="comentarios" class="form-control" ></textarea>               
                            </div>            
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="notas" id="notasContC"  onclick='collapse("notasCont");'><i class="fa fa-angle-down"></i>Notas:</label> 
                            <div id="notasCont" class="collapse">            
                                <textarea id="notas" class="form-control" ></textarea>
                            </div>               
                        </div>
                    </div>
                            
                    <div class="col-xs-12 col-sm-6">
                        <label id="rangoContC"  onclick='collapse("rangoCont");'><i class="fa fa-angle-down"></i>Rango de Fechas Plan:</label> 
                        <div id="rangoCont" class="collapse">            
                            <div class="form-group">
                                <label for="f_plan_i">Fecha Inicio:</label>
                                <input type="date" id="f_plan_i" class="form-control">
                            </div>
                    
                            <div class="form-group">
                                <label for="f_plan_f">Fecha Fin:</label>
                                <input type="date" id="f_plan_f" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="dispositivo" id="rangoDisC"  onclick='collapse("rangoDis");'><i class="fa fa-angle-down"></i>Dispositivo:</label> 
                            <div id="rangoDis" class="collapse">
                                <input type="text" id="serie" class="form-control" placeholder="Numero de Serie">
                                <input type="text" id="mac" class="form-control" placeholder="Mac Adress">
                                <input type="text" id="otro" class="form-control" placeholder="Otro">
                            </div>
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
    var select2Act;
$(document).ready(function(){
    getActividades();

    select2Act = $('.select2ModalActividades').select2({
      theme: 'bootstrap4',
      dropdownParent: $('#modalFormularioActiviades')
    });
    
    $('#descripcion').summernote({
        height: 100
    });
    $('#comentarios').summernote({
        height: 100
    });
    $('#notas').summernote({
        height: 100
    });

    setTimeout(defineFnCUR(), 3000);
});

function collapse(id){
    if($("#"+id).hasClass("show")){
        $("#"+id+"C i").attr("class","fa fa-angle-down");
    }else{
        $("#"+id+"C i").attr("class","fa fa-angle-up");
    }

    $("#"+id).collapse('toggle');
}

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
                datatablebase("tablaActividades", false, 400, true, true, arrayOrder, arrayExport);
                //datatablebase(tableid, ffoot, scroll, order, search, arrayOrder, arrayExport)
            }
        });
    }

    function newActividad(){
        $("#modalFormularioActiviades").modal();
        $("#titleFormularioActiviades").html("<i class='fa fa-plus'></i> Captura nueva actividad");
        $("#btnsFormularioActiviades").html('<button type="button" class="btn btn-success" onclick="saveNewActividad()">Guardar</button>'+
                                            '<button type="button" class="btn btn-default" onclick="limpiaFormulario(\'form-actividades\')">Limpiar</button>'+
                                            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
    }

    function validaFormulario(idForm){
        $(".validaFormError").remove();
        var valida = true;
        $("#"+idForm+" [required]").each(function(){
            var labelCampo = $(this).siblings("label");
            if($(this).val()==''){
                labelCampo.append("<b class='validaFormError' style='color:#ff00007d;'>*[Campo Requerido]</b>")
                valida = false;
            }else{
                labelCampo.children(".validaFormError").remove();
            }
        });    

        return valida;
    }

    function limpiaFormulario(idForm){
        $("#"+idForm+" .collapse").collapse('hide');
        select2Act.val(null).trigger("change");

        
        $("#"+idForm+" input,#"+idForm+" select,#"+idForm+" textarea").each(function(){
            $(this).val('');
        });

        $('#descripcion').summernote('code','');
        $('#comentarios').summernote('code','');
        $('#notas').summernote('code','');

    }

    function saveNewActividad(){
        if(validaFormulario("form-actividades")){
            var tipo = $('#c_tipo_actividad').val();
            var clasificacion = $('#c_clasifica_act').val();
            var prioridad = $('#c_prioridad').val();
            var cliente = $('#c_cliente').val();
            var u_responsable = $('#c_usuario_responsable').val();
            var u_involucrados = $('#c_usuarios_ivolucrados').val();
            var descripcion = $('#descripcion').val();
            var comentarios = $('#comentarios').val();
            var notas = $('#notas').val();
            var fi = $('#f_plan_i').val();
            var ff = $('#f_plan_f').val();
            var dispositivo = '';
            dispositivo +=($('#serie').val()!=''?'<b>Serie:</b>'+$('#serie').val()+'<br>':'');
            dispositivo +=($('#mac').val()!=''?'<b>Mac:</b>'+$('#otro').val()+'<br>':'');
            dispositivo +=($('#otro').val()!=''?'<b>'+$('#serie').val()+'</b><br>':'');


            $.ajax({
            url: "ajax.php?mode=newactividad",
            type: "POST",
            data: {tipo:tipo,
                    clasificacion:clasificacion,
                    prioridad:prioridad,
                    cliente:cliente,
                    u_responsable:u_responsable,
                    u_involucrados:u_involucrados,
                    descripcion:descripcion,
                    comentarios:comentarios,
                    notas:notas,
                    fi:fi,
                    ff:ff,
                    dispositivo:dispositivo},                    
            error : function(request, status, error) {
                notify('Error inesperado, consulte a soporte.'+request+status+error,1500,"error","top-end");
            },
            beforeSend: function() {
            },
            success: function(datos){
                console.log(datos);
                var respuesta = JSON.parse(datos);
                if(respuesta.codigo == "1") {
                    $('#modalFormularioActiviades').modal('hide');
                    getActividades();
                    notify(respuesta.alerta, 1500, "success", "top-end");
                } else {
                    notify(respuesta.alerta, 1500, "error", "top-end");
                }
            }
        });
        }else{
            notify("ERROR: Llene correctamente el formulario.",1500,"error","top-end");
        }
    }

    


</script>


<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>
