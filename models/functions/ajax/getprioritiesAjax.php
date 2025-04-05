<?php
// En el caso para mode=getpriorities
$db = new Conexion();

// Verificar si mostrar inactivos
$mostrar_inactivos = isset($_POST['inactivos']) && $_POST['inactivos'] == '1';

// Obtener prioridades (activas o todas)
$where = $mostrar_inactivos ? "" : "WHERE activo = 1";
$dt_priorities = findtablaq("SELECT id, codigo, descripcion, color_hex, hr_min, hr_max, icono, activo 
                            FROM act_c_prioridades 
                            $where
                            ORDER BY activo DESC ,codigo;", "id");

$HTML = '';
if($dt_priorities !== false && is_array($dt_priorities)) {
    $HTML .= '<div class="row mb-3">
                <div class="col-md-12 text-right">
                    <div class="dropdown dropleft d-inline-block">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filtros
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="min-width: 200px; padding: 10px;">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="mostrarInactivos" '.($mostrar_inactivos ? 'checked' : '').'>
                                <label class="form-check-label" for="mostrarInactivos">Mostrar todos los registros</label>
                            </div>
                        </div>
                    </div>
                </div>
              </div>';

    $HTML .= '<table id="tablaPrioridades" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th class="text-center">Color</th>
                        <th class="text-center">Horas (Min-Max)</th>
                        <th class="text-center">Ícono</th>
                        <th class="text-center" style="min-width:160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>';

    foreach($dt_priorities as $id => $array) {
        if(is_array($array)) {
            $color_style = "background-color: ".htmlspecialchars($array['color_hex'])."; color: white; padding: 2px 8px; border-radius: 3px;";
            $icono = !empty($array['icono']) ? '<i class="'.htmlspecialchars($array['icono']).' fa-lg"></i>' : 'N/A';
            
            $is_inactive = ($array['activo'] == 0);
            $row_class = $is_inactive ? 'class="inactive-row"' : '';
            
            $HTML .= '<tr '.$row_class.'>
                        <td>'.htmlspecialchars($array['codigo']).'</td>
                        <td>'.htmlspecialchars($array['descripcion']).'</td>
                        <td class="text-center align-middle"><span style="'.$color_style.'">'.htmlspecialchars($array['color_hex']).'</span></td>
                        <td class="text-center align-middle">'.htmlspecialchars($array['hr_min']).' - '.htmlspecialchars($array['hr_max']).'</td>
                        <td class="text-center align-middle">'.$icono.'</td>
                       
                        <td class="text-center" style="vertical-align: middle; min-width:160px;">
                            <span class="fa fa-edit btn-icon" title="Editar Prioridad" 
                                  onclick="GetRegisterPriority(\''.htmlspecialchars($array['id']).'\')"></span>
                            '.($array['activo'] == 1 ? 
                            '<span class="fa fa-trash btn-icon" style="color:darkred; margin-left:10px;" title="Eliminar Prioridad" 
                                  onclick="confirmDeletePriority(\''.htmlspecialchars($array['id']).'\', \''.htmlspecialchars($array['codigo']).'\', \''.htmlspecialchars($array['descripcion']).'\')"></span>' 
                            : '').'
                        </td>
                    </tr>';
        }
    }

    $HTML .= '</tbody></table>';
} else {
    $HTML .= '<div class="alert alert-info">No hay prioridades registradas.</div>';
}

echo $HTML;
?>