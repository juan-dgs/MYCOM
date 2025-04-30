<?php
include(HTML.'AdminPanel/masterPanel/head.php');
include(HTML.'AdminPanel/masterPanel/navbar.php');
include(HTML.'AdminPanel/masterPanel/menu.php');
include(HTML.'AdminPanel/masterPanel/breadcrumb.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            <!-- <h3 class="panel-title">Horario Laboral</h3> -->
                <div class="panel-options">
                    <!-- <span id="lastSaved" class="text-muted small" style="margin-right: 10px;"></span> -->
                </div>
            </div>
            <div class="panel-body">
                <form id="scheduleForm">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Domingo</th>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miércoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                    <th>Sábado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php 
                                    $dias = $_DIASSEM;
                                    unset($dias[0]);
                                    foreach($dias as $dia): ?>
                                    <td>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="laborable" data-dia="<?php echo $dia; ?>" id="laborable_<?php echo $dia; ?>"> Día laborable
                                            </label>
                                        </div>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <tr>
                                    <?php foreach($dias as $dia): ?>
                                    <td>
                                        <div class="form-group">
                                            <label for="entrada_<?php echo $dia; ?>">Entrada</label>
                                            <input type="time" class="form-control entrada" id="entrada_<?php echo $dia; ?>" data-dia="<?php echo $dia; ?>">
                                        </div>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <tr>
                                    <?php foreach($dias as $dia): ?>
                                    <td>
                                        <div class="form-group">
                                            <label for="salida_<?php echo $dia; ?>">Salida</label>
                                            <input type="time" class="form-control salida" id="salida_<?php echo $dia; ?>" data-dia="<?php echo $dia; ?>">
                                        </div>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                
                                <tr>
                                    <?php foreach($dias as $dia): ?>
                                    <td>
                                        <div class="form-group">
                                            <label for="comida_<?php echo $dia; ?>">Tiempo de comida (hrs)</label>
                                            <input type="number" class="form-control comida" id="comida_<?php echo $dia; ?>" data-dia="<?php echo $dia; ?>" min="0" max="240" value="60">
                                        </div>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <?php foreach($dias as $dia): ?>
                                    <td class="text-center">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-lg btn-primary expandable-btn" id="guardar_<?php echo $dia; ?>" onclick="guardarHorario('<?php echo $dia; ?>')">
                                                <i class="fas fa-save" style="margin-right:10px; font-size: large;"></i> Guardar</button>
                                        </div>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    cargarHorario();
    
    // Validación para evitar horas negativas en tiempo de comida
    $('.comida').on('input change', function() {
        let valor = parseFloat($(this).val());
        if (valor < 0) {
            $(this).val(0);
            notify("No se permiten horas negativas en tiempo de comida", 1500, "error", "top-end");
        }
    });
});

var _DIASSEM=['','Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

function cargarHorario() {
    $.ajax({
        url: "ajax.php?mode=gethorario",
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log(response);
            if(response.codigo == 1) {
                response.data.forEach(function(diaData) {
                    var dia = _DIASSEM[diaData.dia_semana];
                    
                    $('#laborable_' + dia).prop('checked', diaData.es_laboral == 1);
                    $('#entrada_' + dia).val(diaData.hora_inicio || '');
                    $('#salida_' + dia).val(diaData.hora_fin || '');
                    $('#comida_' + dia).val(diaData.hr_comida || '60');
                    
                    if(diaData.es_laboral==0){
                        toggleCamposDia(dia, diaData.es_laboral == 1);
                    }
                });
                updateLastSaved();
            } else {
                notify(response.alerta, 1500, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            notify("Error al cargar el horario. Por favor recarga la página.", 1500, "error", "top-end");
        }
    });
}

function guardarHorario(diaCambiado) {
    // Validar tiempo de comida no negativo
    let tiempoComida = parseFloat($('#comida_' + diaCambiado).val());
    if (tiempoComida < 0) {
        $('#comida_' + diaCambiado).val(0);
        notify("No se permiten horas negativas en tiempo de comida", 1500, "error", "top-end");
        return;
    }
    
    if($('#laborable_' + diaCambiado).is(':checked')) {
        var entrada = $('#entrada_' + diaCambiado).val();
        var salida = $('#salida_' + diaCambiado).val();
        
        if(entrada && salida && entrada >= salida) {
            notify("La hora de entrada debe ser anterior a la de salida (" + diaCambiado + ")", 1500, "error", "top-end");
            return;
        }
    }
    
    var horario = {
        dia_semana: diaCambiado,
        hora_inicio: $('#entrada_' + diaCambiado).val(),
        hora_fin: $('#salida_' + diaCambiado).val(),
        hr_comida: $('#comida_' + diaCambiado).val(),
        es_laboral: $('#laborable_' + diaCambiado).is(':checked') ? 1 : 0
    };

    $.ajax({
        url: "ajax.php?mode=guardarhorario",
        type: "POST",
        data: {horario: JSON.stringify(horario)},
        dataType: "json",
        success: function(response) {
            console.log(response.alerta);
            if(response.codigo == 1) {
                notify("Horario de " + diaCambiado + " actualizado correctamente", 1500, "success", "top-end");
                updateLastSaved();
            } else {
                notify(response.alerta, 1500, "error", "top-end");
            }
        },
        error: function(xhr, status, error) {
            notify("Error al guardar el horario: " + error, 1500, "error", "top-end");
            console.error("Error al guardar horario:", status, error);
        }
    });
}

function toggleCamposDia(dia, esLaborable) {
    var entrada = $('#entrada_' + dia);
    var salida = $('#salida_' + dia);
    var comida = $('#comida_' + dia);
    
    if(esLaborable) {
        entrada.prop('disabled', false);
        salida.prop('disabled', false);
        comida.prop('disabled', false);
        entrada.val('08:00');
        salida.val('17:00');
        comida.val('0.0');
    } else {
        entrada.prop('disabled', true);
        salida.prop('disabled', true);
        comida.prop('disabled', true);
        entrada.val('');
        salida.val('');
        comida.val('');
    }
}

function updateLastSaved() {
    var now = new Date();
    var timeString = now.toLocaleTimeString();
    $('#lastSaved').html('Última actualización: ' + timeString);
}

$('.laborable').change(function() {
    var dia = $(this).data('dia');
    var esLaborable = $(this).is(':checked');
    toggleCamposDia(dia, esLaborable);
});
</script>

<?php include(HTML.'AdminPanel/masterPanel/foot.php'); ?>