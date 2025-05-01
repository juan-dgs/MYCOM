<?php
$db = new Conexion();

// Modificamos la consulta para asegurar el formato de fecha
$sql = "SELECT 
        id,
        DATE_FORMAT(fecha, '%Y-%m-%d') as fecha, 
        nombre, 
        es_recurrente,
        DATE_FORMAT(fh_registro, '%Y-%m-%d %H:%i:%s') as fh_registro
      FROM core_feriados 
      WHERE activo = 1 
      ORDER BY fecha";

$result = $db->query($sql);
$dt_feriados = array();

if($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dt_feriados[$row['fecha']] = $row;
    }
}

$HTML = '';
if (!empty($dt_feriados)) {
    $HTML .= '<table id="tablaDiasFeriados" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Recurrente</th>
                        <th class="text-center" style="min-width: 160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($dt_feriados as $fecha => $feriado) {
        $fecha_formateada = date("d/m/Y", strtotime($feriado['fecha']));
        $recurrente = $feriado['es_recurrente'] == 1 ? 'Sí' : 'No';
        
        $HTML .= '<tr>
                    <td>'.$fecha_formateada.'</td>
                    <td>'.$feriado['nombre'].'</td>
                    <td>'.$recurrente.'</td>
                    <td class="text-center" style="vertical-align: middle;min-width: 160px;">
                        <span class="fa fa-edit btn-icon" title="Editar"
                         onclick="getRegisterHoliday(\''.$feriado['fecha'].'\')">
                        </span> 
                        <span class="fa fa-trash btn-icon" style="color: darkred; margin-left: 10px;" title="Eliminar"
                         onclick="confirmDeleteHoliday(\''.$feriado['fecha'].'\', \''.$feriado['nombre'].'\')">
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