<?php
$db = new Conexion();

// Consulta SQL corregida usando solo 'id'
$sql = "SELECT 
        c.id AS id, 
        c.rfc, 
        c.alias, 
        c.razon_social, 
        c.domicilio, 
        c.contacto, 
        c.correo, 
        c.telefono, 
        c.fh_registro,
        c.fh_inactivo,
        u.usuario AS u_inactivo
        FROM act_c_clientes c 
        LEFT JOIN users u ON c.u_inactivo = u.id
        WHERE c.activo = 1";

$dt_clientes = findtablaq($sql, "id");

$HTML = '';
if ($dt_clientes != false) {
    $HTML .= '<table id="tablaClientes" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>RFC</th>
                    <th>Alias</th>
                    <th>Razón Social</th>
                    <th>Contacto</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($dt_clientes as $id => $array) {
        $HTML .= '<tr>
                  <td>'.(!empty($dt_clientes[$id]['rfc']) ? htmlspecialchars($dt_clientes[$id]['rfc']) : 'NA').'</td>
                  <td>'.htmlspecialchars($dt_clientes[$id]['alias']).'</td>
                  <td>'.htmlspecialchars($dt_clientes[$id]['razon_social']).'</td>
                  <td>'.(!empty($dt_clientes[$id]['contacto']) ? htmlspecialchars($dt_clientes[$id]['contacto']) : 'NA').'</td>
                  <td>'.(!empty($dt_clientes[$id]['correo']) ? htmlspecialchars($dt_clientes[$id]['correo']) : 'NA').'</td>
                  <td>'.(!empty($dt_clientes[$id]['telefono']) ? htmlspecialchars($dt_clientes[$id]['telefono']) : 'NA').'</td>
                  <td>'.htmlspecialchars($dt_clientes[$id]['fh_registro']).'</td>
                  <td class="text-center" style="vertical-align: middle; min-width: 100px;">
                      <span class="fa fa-edit btn-icon" title="Editar cliente"
                       onclick="getClient(\''.$id.'\',\''.htmlspecialchars($dt_clientes[$id]['alias']).'\')">
                      </span>

                      <span class="fa fa-trash btn-icon" style="color: darkred; margin-left: 10px;" title="Desactivar cliente"
                       onclick="confirmDeleteClient(\''.$id.'\',\''.htmlspecialchars($dt_clientes[$id]['alias']).'\')">
                      </span>
                  </td>
              </tr>';
    }
    $HTML .= '</tbody></table>';
} else {
    $HTML = '<div class="alert alert-info">No se encontraron clientes activos</div>';
}

echo $HTML;
?>