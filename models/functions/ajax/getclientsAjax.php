<?php
// Modo para obtener clientes

    $db = new Conexion();

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
            c.dir_logo,
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
                        <th>Foto</th>
                        <th>RFC</th>
                        <th>Alias</th>
                        <th>Razón Social</th>
                        <th>Domicilio</th>
                        <th>Contacto</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($dt_clientes as $id => $array) {
            $foto = $dt_clientes[$id]['dir_logo'];
            $fotoHtml = $foto ? '<img src="views/images/clients/'.$foto.'" style="width:40px;height:40px;border-radius:50%;object-fit:cover;cursor:pointer;" onerror="this.src=\'views/images/clients/clientDefault.png\'" onclick="editClientPhoto(\''.$id.'\',\''.$foto.'\')">' 
                              : '<img src="views/images/clients/clientDefault.png" style="width:40px;height:40px;border-radius:50%;object-fit:cover;cursor:pointer;" onclick="editClientPhoto(\''.$id.'\',\'\')">';
            
            $HTML .= '<tr>
                      <td>'.$fotoHtml.'</td>
                      <td>'.(!empty($dt_clientes[$id]['rfc']) ? htmlspecialchars($dt_clientes[$id]['rfc']) : 'NA').'</td>
                      <td>'.htmlspecialchars($dt_clientes[$id]['alias']).'</td>
                      <td>'.htmlspecialchars($dt_clientes[$id]['razon_social']).'</td>
                      <td>'.(!empty($dt_clientes[$id]['domicilio']) ? htmlspecialchars($dt_clientes[$id]['domicilio']) : 'NA').'</td>
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