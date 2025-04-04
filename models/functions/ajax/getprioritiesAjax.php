<?php
$db = new Conexion();

// Obtener todas las prioridades activas
$dt_priorities = findtablaq("SELECT id, codigo, descripcion, color_hex, hr_min, hr_max, icono 
                            FROM act_c_prioridades 
                            WHERE activo = 1 
                            ORDER BY codigo", "id");

$HTML = '';
if($dt_priorities !== false && is_array($dt_priorities)) {
    $HTML .= '<table id="tablaPrioridades" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Color</th>
                        <th>Horas (Min-Max)</th>
                        <th>Ícono</th>
                        <th class="text-center" style="min-width:160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>';

    foreach($dt_priorities as $id => $array) {
        // Verificar que el elemento sea un array antes de acceder a sus índices
        if(is_array($array)) {
            $color_style = "background-color: ".htmlspecialchars($array['color_hex'])."; color: white; padding: 2px 8px; border-radius: 3px;";
            $icono = !empty($array['icono']) ? '<i class="'.htmlspecialchars($array['icono']).'"></i>' : 'N/A';
            
            $HTML .= '<tr>
                        <td>'.htmlspecialchars($array['codigo']).'</td>
                        <td>'.htmlspecialchars($array['descripcion']).'</td>
                        <td><span style="'.$color_style.'">'.htmlspecialchars($array['color_hex']).'</span></td>
                        <td>'.htmlspecialchars($array['hr_min']).' - '.htmlspecialchars($array['hr_max']).'</td>
                        <td>'.$icono.'</td>
                        <td class="text-center" style="vertical-align: middle; min-width:160px;">
                            <span class="fa fa-edit btn-icon" title="Editar Prioridad" 
                                  onclick="GetRegisterPriority(\''.htmlspecialchars($array['id']).'\')"></span>
                            <span class="fa fa-trash btn-icon" style="color:darkred; margin-left:10px;" title="Eliminar Prioridad" 
                                  onclick="confirmDeletePriority(\''.htmlspecialchars($array['id']).'\', \''.htmlspecialchars($array['codigo']).'\', \''.htmlspecialchars($array['descripcion']).'\')"></span>
                        </td>
                    </tr>';
        }
    }

    $HTML .= '</tbody></table>';
} else {
    $HTML = '<div class="alert alert-info">No hay prioridades registradas.</div>';
}

echo $HTML;
?>