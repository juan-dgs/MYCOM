<?php
$db = new Conexion();

// Consulta SQL básica para clasificaciones
$sql = "SELECT id, codigo, descripcion, fh_registro 
        FROM act_c_clasificacion
        WHERE activo = 1";

$dt_clasificacion = findtablaq($sql, "id");

$HTML = '';
if ($dt_clasificacion != false) {
    $HTML .= '<table id="Tablaclasificacion" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($dt_clasificacion as $id => $array) {
        $HTML .= '<tr>
                  <td>'.(!empty($array['codigo']) ? htmlspecialchars($array['codigo']) : 'NA').'</td>
                  <td>'.htmlspecialchars($array['descripcion']).'</td>
                  <td>'.htmlspecialchars($array['fh_registro']).'</td>
                  <td class="text-center" style="vertical-align: middle; min-width: 100px;">
                      <span class="fa fa-edit btn-icon" title="Editar clasificación"
                       onclick="getClasification(\''.$id.'\',\''.htmlspecialchars($array['codigo']).'\')">
                      </span>

                      <span class="fa fa-trash btn-icon" style="color: darkred; margin-left: 10px;" title="Desactivar clasificación"
                       onclick="confirmDeleteClasification(\''.$id.'\',\''.htmlspecialchars($array['descripcion']).'\')">
                      </span>
                  </td>
              </tr>';
    }
    $HTML .= '</tbody></table>';
} else {
    $HTML = '<div class="alert alert-info">No se encontraron clasificaciones activas</div>';
}

echo $HTML;
?>