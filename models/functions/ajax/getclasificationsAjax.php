<?php
$db = new Conexion();

$dt_clasif = findtablaq("SELECT id, codigo, descripcion 
                         FROM act_c_clasificacion 
                         WHERE activo=1 
                         ORDER BY descripcion", "codigo");

$HTML = '';
if($dt_clasif != false) {
    $HTML .= '<table id="tablaClasificaciones" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th class="text-center" style="min-width:160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>';
    
    foreach($dt_clasif as $id => $array) {
        $HTML .= '<tr>
                    <td>'.$dt_clasif[$id]['codigo'].'</td>
                    <td>'.$dt_clasif[$id]['descripcion'].'</td>
                    <td class="text-center" style="vertical-align:middle;min-width:160px;">
                        <span class="fa fa-edit btn-icon" title="Editar" 
                              onclick="getRegisterClasification(\''.$dt_clasif[$id]['id'].'\')"></span>
                        <span class="fa fa-trash btn-icon" style="color:darkred;margin-left:10px;" title="Eliminar" 
                              onclick="confirmDeleteClasification(\''.$dt_clasif[$id]['id'].'\', \''.$dt_clasif[$id]['codigo'].'\', \''.$dt_clasif[$id]['descripcion'].'\')"></span>
                    </td>
                </tr>';
    }
    
    $HTML .= '</tbody></table>';
} else {
    $HTML = '<div class="alert alert-info">No hay clasificaciones registradas.</div>';
}

echo $HTML;
?>