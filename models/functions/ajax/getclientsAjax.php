<?php
$db = new Conexion();

$dt_clientes = findtablaq("SELECT id, rfc, alias, razon_social, domicilio, contacto, correo, telefono, fh_registro, fh_inactivo
                            FROM act_c_clientes
                            WHERE activo = 1;", "id");

$HTML = '';
if ($dt_clientes != false) {
    $HTML .= '<table id="tablaclientes" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>RFC</th>
                        <th>Alias</th>
                        <th>Razón Social</th>
                        <th>Domicilio</th>
                        <th>Contacto</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Acciones</th>

                    </tr>
                </thead>
                <tbody>';

    foreach ($dt_clientes as $id => $cliente) {
        $HTML .= '<tr>
                    <td>' . htmlspecialchars($cliente['rfc']) . '</td>
                    <td>' . htmlspecialchars($cliente['alias']) . '</td>
                    <td>' . htmlspecialchars($cliente['razon_social']) . '</td>
                    <td>' . htmlspecialchars($cliente['domicilio']) . '</td>
                    <td>' . htmlspecialchars($cliente['contacto']) . '</td>
                    <td>' . htmlspecialchars($cliente['correo']) . '</td>
                    <td>' . htmlspecialchars($cliente['telefono']) . '</td>
                    <td class="text-center" style="vertical-align: middle; min-width: 160px;">
                        <span class="fa fa-edit btn-icon" title="Editar Cliente"
                            onclick="GetUser(\'' . $id . '\', \'' . htmlspecialchars($cliente['alias']) . '\')">
                        </span> 

                        <span class="fa fa-key btn-icon" style="margin-left: 10px;" title="Editar Contraseña"
                            onclick="ChangePass(\'' . $id . '\', \'' . htmlspecialchars($cliente['alias']) . '\')">
                        </span> 

                        <span class="fa fa-trash btn-icon" style="color: darkred; margin-left: 10px;" title="Eliminar Cliente"
                            onclick="confirmDeleteUser(\'' . $id . '\', \'' . htmlspecialchars($cliente['alias']) . '\')">
                        </span>
                    </td>
                </tr>';
    }

    $HTML .= '</tbody></table>';
} else {
    $HTML = "SIN DATOS EN LA TABLA.";
}

echo $HTML;
?>
