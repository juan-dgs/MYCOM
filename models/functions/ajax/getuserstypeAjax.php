<?php
$db = new Conexion();

$dt_tipo=findtablaq("SELECT t.id,t.codigo,t.descripcion
                            FROM users_types as t 
                            WHERE t.activo= 1 order by t.descripcion;",id: "codigo");

$HTML ='';
if ($dt_tipo!=false){
  $HTML .='<table id="tablaTipoDeUsuarios" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>Tipo De usuario</th>
                    <th>Descripcion</th>
                    <th class="text-center" style="min-width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>';

  foreach ($dt_tipo as $id => $array) {
    $HTML .= '  <tr>
                    <td>'.$dt_tipo[$id]['codigo'].'</td>
                    <td>'.$dt_tipo[$id]['descripcion'].'</td>
 
                      <td class="text-center" style="vertical-align: middle;min-width: 160px;">
                          
                          <span class="fa fa-edit btn-icon"  tittle="Editar Tipo De usuario"
                           onclick="GetRegisterUserTypes(\''.$dt_tipo[$id]['id'].'\',\''.$dt_tipo[$id]['codigo'].' '.$dt_tipo[$id]['descripcion'].'\')">
                          </span> 

                          <span class="fa fa-trash btn-icon" style="color: darkred; margin-left: 10px;" tittle="Eliminar Tipo De usuario"
                           onclick="confirmDeleteUserTypes(\''.$dt_tipo[$id]['id'].'\',\''.$dt_tipo[$id]['codigo'].'\' , \''. $dt_tipo[$id]['descripcion'].'\')">
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