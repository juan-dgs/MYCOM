<?php
$db = new Conexion();

$dt_tipos = findtablaq("SELECT id, codigo, descripcion, pre 
                        FROM act_c_tipos 
                        WHERE activo = 1 
                        ORDER BY descripcion;", "id");

$HTML = '';
if($dt_tipos != false) {
    $HTML .= '<table id="tablaTiposActividad" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width:100%;">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Prefijo</th>
                        <th class="text-center" style="min-width:160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>';
    
    foreach($dt_tipos as $id => $array) {
        $HTML .= '<tr>
                    <td>'.$dt_tipos[$id]['codigo'].'</td>
                    <td>'.$dt_tipos[$id]['descripcion'].'</td>
                    <td>'.$dt_tipos[$id]['pre'].'</td>
                    <td class="text-center" style="vertical-align:middle;min-width:160px;">
                        <span class="fa fa-edit btn-icon" title="Editar" 
                              onclick="GetRegisterActivityType(\''.$dt_tipos[$id]['id'].'\',\''.$dt_tipos[$id]['codigo'].'\',\''.$dt_tipos[$id]['descripcion'].'\')"></span>
                        <span class="fa fa-trash btn-icon" style="color:darkred;margin-left:10px;" title="Eliminar" 
                              onclick="confirmDeleteActivityType(\''.$dt_tipos[$id]['id'].'\',\''.$dt_tipos[$id]['codigo'].'\',\''.$dt_tipos[$id]['descripcion'].'\')"></span>
                    </td>
                  </tr>';
    }
    
    $HTML .= '</tbody></table>';
} else {
    $HTML = "No hay tipos de actividad registrados.";
}

echo $HTML;
?>