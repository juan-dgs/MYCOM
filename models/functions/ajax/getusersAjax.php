<?php
$db = new Conexion();

$dt_usuario=findtablaq("SELECT u.id,u.nombre,u.apellido_p,u.apellido_m,u.dir_foto,t.descripcion as tipo, u.usuario,u.correo, u.f_registro as fecha,t.codigo as c_tipo
                            FROM users as u LEFT JOIN users_types as t on u.c_tipo_usuario = t.codigo
                            WHERE u.activo= 1;","id");

$HTML ='';
if ($dt_usuario!=false){
  $HTML .='<table id="tablaUsuarios" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';

  foreach ($dt_usuario as $id => $array) {
    $HTML .= '  <tr>
                  <td>'.$dt_usuario[$id]['dir_foto'].'</td>
                  <td>'.$dt_usuario[$id]['nombre'].' '. $dt_usuario[$id]['apellido_p'].' '. $dt_usuario[$id]['apellido_m'] .'</td>
                  <td>'.$dt_usuario[$id]['tipo'].'</td>
                  <td>'.$dt_usuario[$id]['usuario'].'</td>
                  <td>'.$dt_usuario[$id]['correo'].'</td>
                  <td>'.$dt_usuario[$id]['fecha'].'</td>
                      <td class="text-center" style="vertical-align: middle;min-width: 160px;">
                          
                          <span class="fa fa-edit btn-icon"  tittle="Editar usuario"
                           onclick="GetUser(\''.$id.'\',\''.$dt_usuario[$id]['nombre'].' '.$dt_usuario[$id]['apellido_p'].' '.$dt_usuario[$id]['apellido_m'].'\')">
                          </span> 

                          <span class="fa fa-key btn-icon" style=" margin-left: 10px;" tittle="Editar contraseña"
                           onclick="ChangePass(\''.$id.'\',\''.$dt_usuario[$id]['nombre'].' '.$dt_usuario[$id]['apellido_p'].' '.$dt_usuario[$id]['apellido_m'].'\')">
                          </span> 

                          <span class="fa fa-trash btn-icon" style="color: darkred; margin-left: 10px;" tittle="Eliminar usuario"
                           onclick="confirmDeleteUser(\''.$id.'\',\''.$dt_usuario[$id]['nombre'].' '. $dt_usuario[$id]['apellido_p'].' '. $dt_usuario[$id]['apellido_m'].'\',\''.$dt_usuario[$id]['c_tipo'].'\')">
                          </span>
                      </td>
                
                      
              </tr>';
  }
  $HTML .= '</tbody>
          </table>';
}else {
  $HTML = "SIN DATOS EN LA TABLA.";
}


echo $HTML;
 ?>
