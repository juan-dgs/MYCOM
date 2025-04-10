<?php
$db = new Conexion();

$dt_feriados = findtablaq("SELECT 
                            fecha, 
                            nombre, 
                            es_recurrente,
                            fh_registro
                          FROM core_feriados 
                          WHERE activo = 1 
                          ORDER BY fecha", id: "fecha");

$HTML = '';
if ($dt_feriados != false) {
    $HTML .= '<table id="tablaDiasFeriados" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Recurrente</th>
                        <th>Registro</th>
                        <th class="text-center" style="min-width: 160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($dt_feriados as $fecha => $array) {
        $fecha_formateada = date("d/m/Y", strtotime($fecha));
        $recurrente = $dt_feriados[$fecha]['es_recurrente'] == 1 ? 'Sí' : 'No';
        $registro_formateado = date("d/m/Y H:i", strtotime($dt_feriados[$fecha]['fh_registro']));
        
        $HTML .= '<tr>
                    <td>'.$fecha_formateada.'</td>
                    <td>'.$dt_feriados[$fecha]['nombre'].'</td>
                    <td>'.$recurrente.'</td>
                    <td>'.$registro_formateado.'</td>
                    <td class="text-center" style="vertical-align: middle;min-width: 160px;">
                        <span class="fa fa-edit btn-icon" title="Editar"
                         onclick="getRegisterHoliday(\''.$fecha.'\')">
                        </span> 
                        <span class="fa fa-trash btn-icon" style="color: darkred; margin-left: 10px;" title="Eliminar"
                         onclick="confirmDeleteHoliday(\''.$fecha.'\', \''.$dt_feriados[$fecha]['nombre'].'\')">
                        </span>
                    </td>
                </tr>';
    }
    $HTML .= '</tbody>
            </table>';
} else {
    $HTML = '<div class="alert alert-info">No hay días feriados registrados.</div>';
}

echo $HTML;
?>