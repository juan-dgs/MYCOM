

    <?php
    $db = new Conexion();

    $qValidaPermisos = ' AND (a.id_usuario_resp="' . USER_ID . '" OR a.folio in (SELECT i.folio FROM act_r_involucrados as i WHERE i.id_usuario = "' . USER_ID . '"))';
    if (USER_TYPE == 'SPUS') {
      $qValidaPermisos = '';
    }


    $dt_acts = findtablaq(
      "SELECT a.folio,a.c_tipo_act,t.descripcion as tipo_desc,
                                        a.c_clasifica_act,c.descripcion as clasificacion_desc , 
                                        a.c_prioridad,p.descripcion as prioridad_desc,p.color_hex,p.icono,
                                        a.id_cliente,cl.alias as nombre_cliente, cl.razon_social as razon,cl.contacto,
                                        a.descripcion,a.comentario,a.notas,a.dispositivo,
                                        a.id_usuario_captura,concat(uc.nombre,' ',uc.apellido_p) as uc_nombre,uc.dir_foto as uc_foto,a.fh_captura,a.f_plan_i,a.f_plan_f,
                                        a.id_usuario_resp,concat(ur.nombre,' ',ur.apellido_p) as ur_nombre,ur.dir_foto as ur_foto,
                                        a.id_usuario_finaliza,concat(uf.nombre,' ',uf.apellido_p) as uf_nombre,uf.dir_foto as uf_foto,a.fh_finaliza,a.calificacion,
                                        a.c_estatus,s.descripcion as estatus_desc,avance,
                                          (SELECT GROUP_CONCAT(i.id,'|',ui.nombre,' ',ui.apellido_p,'|',ui.dir_foto) as i 
                                          FROM act_r_involucrados as i LEFT JOIN users as ui on i.id_usuario = ui.id 
                                          WHERE i.folio = a.folio) as involucrados,
                                          (SELECT GROUP_CONCAT(ad.dir) as a 
                                          FROM act_r_adjuntos as ad  
                                          WHERE ad.folio_act =a.folio) as adjuntos,
                                           (SELECT sum(1) as comenta 
                                          FROM act_r_comentarios as cm  
                                          WHERE cm.folio_act =a.folio and visto_por NOT LIKE '%*".USER_ID."*%') as comentarios_sin_ver,
                                          p.hr_min,p.hr_max
                                FROM actividades as a 
                                    LEFT JOIN act_c_tipos as t on a.c_tipo_act = t.codigo
                                    LEFT JOIN act_c_clasificacion as c on a.c_clasifica_act = c.codigo
                                    LEFT JOIN act_c_prioridades as p on a.c_prioridad = p.codigo
                                    LEFT JOIN act_c_clientes as cl on a.id_cliente = cl.id
                                    LEFT JOIN users as uc on a.id_usuario_captura = uc.id
                                    LEFT JOIN users as ur on a.id_usuario_resp = ur.id
                                    LEFT JOIN users as uf on a.id_usuario_finaliza = uf.id
                                    LEFT JOIN act_c_estatus as s on a.c_estatus = s.codigo
                                WHERE 1=1 $qValidaPermisos AND c_estatus = 'A' 
                                ORDER BY fh_captura desc;",
      "folio"
    );

    $HTML = '';
    if ($dt_acts != false) {
      $HTML .= '<table id="tablaActividades" class="display tab-hv dataTable table table-striped nowrap row-border hover order-column table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Descripción</th>
                    <th>Avance</th>
                </tr>
            </thead>
            <tbody>';

      foreach ($dt_acts as $id => $array) {

        $HTML .= '<tr id="tr'.$id.'">
                <td style="width: 200px;position:relative;">' .
          $dt_acts[$id]['folio'] . ' <br>' .
          $dt_acts[$id]['tipo_desc'] . ' - ' . $dt_acts[$id]['clasificacion_desc'] . ' <br>' .
          '<b><i class="' . $dt_acts[$id]['icono'] . '" style="color:' . $dt_acts[$id]['color_hex'] . ';"></i> ' . $dt_acts[$id]['prioridad_desc'] . '</b> <br>' .
          '<b title="' . $dt_acts[$id]['razon'] . '">' . $dt_acts[$id]['nombre_cliente'] . '</b><br>' .
          $dt_acts[$id]['contacto'] . ' <br>';

        if (USER_TYPE == 'SPUS' && $dt_acts[$id]['avance'] < 50) {
          $HTML .= '<button type="button" class="btn btn-default btn-icon" style="position: absolute;bottom:5px;right: 5px;" onclick="statusActividad(\'' . $dt_acts[$id]['folio'] . '\',\'X\',false)">
                                  <i class="fas fa-trash-alt"></i>
                              </button>';
        }

        $htmlAdjuntos = '';

        $htmlAdjuntos .= '<button title="Ver Adjuntos" class="btn btn-default btn-lg" type="button" onclick="verAdjuntos(\'' . $dt_acts[$id]['folio'] . '\');">' . ($dt_acts[$id]['adjuntos'] == '' ? "Subir Adjuntos" : count(explode(',', $dt_acts[$id]['adjuntos'])) . " Adjuntos") . '  <i class="fas fa-paperclip"></i></button>';
        if ($dt_acts[$id]['adjuntos'] != '') {
          //if(count(explode(',',$dt_acts[$id]['adjuntos']))<=3){   
          $contador = 0;
          foreach (explode(',', $dt_acts[$id]['adjuntos']) as $f) {
            $ext = explode('.', $f)[1];
            $dir = 'views/images/attachments/' . substr($dt_acts[$id]['folio'], 0, 2) . '/' . $dt_acts[$id]['folio'] . '/' . $f;
            $img = $dir;
            $valPreview = true;
            switch ($ext) {
              case 'jpg':
              case 'png':
              case 'gif':
                $img = $dir;
                break;

              case 'doc':
              case 'docx':
                $img = "views/images/icons/doc.png";
                break;

              case 'xls':
              case 'xlsx':
                $img = "views/images/icons/xls.png";
                $valPreview = false;
                break;

              case 'pdf':
                $img = "views/images/icons/pdf.png";
                $valPreview = false;
                break;

              default:
                $img = "views/images/icons/otro.png";
                $valPreview = false;
                break;
            }
            $htmlAdjuntos .= '<img ' . (USER_TYPE == 'SPUS' || $valPreview == true ? 'onclick="preview(\'' . $dir . '\',\'' . $ext . '\');" title="Visualizar Adjunto" ' : ' title="Adjunto no se puede visualizar por permisos." ') . ' class="img-adjunto" src="' . $img . '" width="80px;">';

            $contador++;
            if ($contador >= 5) {
              break;
            }
          }
        }

        $htmlDispositivo = '';
        if (count(explode('|', $dt_acts[$id]['dispositivo'])) == 3) {
          if (explode('|', $dt_acts[$id]['dispositivo'])[0] != '') {
            $htmlDispositivo .= '<b>Serie:</b>' . explode('|', $dt_acts[$id]['dispositivo'])[0] . "<br>";
          }
          if (explode('|', $dt_acts[$id]['dispositivo'])[1] != '') {
            $htmlDispositivo .= '<b>Mac:</b>' . explode('|', $dt_acts[$id]['dispositivo'])[1] . "<br>";
          }
          if (explode('|', $dt_acts[$id]['dispositivo'])[2] != '') {
            $htmlDispositivo .= '<b>Otro:</b>' . explode('|', $dt_acts[$id]['dispositivo'])[2] . "<br>";
          }
        }

        $HTML .= '</td>               
                <td>' .
          ($dt_acts[$id]['descripcion'] != '' ? '<b>Descripción: </b>' . $dt_acts[$id]['descripcion'] . '<br>' : '') .
          ($dt_acts[$id]['comentario'] != '' ? '<b>Comentarios: </b>' . $dt_acts[$id]['comentario'] . '<br>' : '') .
          ($dt_acts[$id]['notas'] != '' ? '<b>Notas: </b>' . $dt_acts[$id]['notas'] . ' <br>' : '') .
          $htmlDispositivo .
          $htmlAdjuntos .
          '</td>';
        $involucrados = '';
        foreach (explode(',', $dt_acts[$id]['involucrados']) as $i) {
          $involucrados .= ($i != '' ? '<div title="Usuario Involucrado: ' . explode('|', $i)[1] . '" class="circular" style="background: url(views/images/profile/' . (explode('|', $i)[2] != '' ? explode('|', $i)[2] : 'userDefault.png') . ');  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff; "></div>' : '');
        }


        $HTML .= '<td style="width: 250px;position:relative;">' .
          ($dt_acts[$id]['id_usuario_resp'] != '' ? '<div title="Usuario Responsable: ' . $dt_acts[$id]['ur_nombre'] . '" class="circular" style="background: url(views/images/profile/' . ($dt_acts[$id]['ur_foto'] != '' ? $dt_acts[$id]['ur_foto'] : 'userDefault.png') . ');  background-size:  cover; width:55px; height: 55px;  border: solid 2px #fff; "></div>' : '') .
          ($dt_acts[$id]['id_usuario_finaliza'] != '' && $dt_acts[$id]['id_usuario_resp'] != $dt_acts[$id]['id_usuario_finaliza'] ? '<div title="Usuario Finaliza: ' . $dt_acts[$id]['uf_nombre'] . '" class="circular" style="background: url(views/images/profile/' . ($dt_acts[$id]['uf_foto'] != '' ? $dt_acts[$id]['uf_foto'] : 'userDefault.png') . ');  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff; "></div>' : '') .
          $involucrados .
          ' <hr>  
                  <div style="position:relative;">
                    <div class="progress avance" style="position:relative;">
                      <b>' . $dt_acts[$id]['avance'] . '%</b>
                      <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="' . $dt_acts[$id]['avance'] . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $dt_acts[$id]['avance'] . '%"></div>
                      <button type="button" class="btn btn-default" style="    position: absolute;right: 0;"  onclick="openComentarios(\'' . $dt_acts[$id]['folio'] . '\',\'' . $dt_acts[$id]['id_usuario_resp'] . '\')">
                          <i class="fas fa-sync-alt"></i>
                      </button>                        
                    </div>'.  
                    ($dt_acts[$id]['comentarios_sin_ver']>0?'<div class="mark-number" id="cm'.$id.'">'.$dt_acts[$id]['comentarios_sin_ver'].'</div>':'')                   
                  .'</div>
                  <div id="rangoFechas" style="    width: 100%;    text-align: center;">';

        $fecha_actual = new DateTime();    

        $tiempoTranscurrido = calcularDiferenciaFechas($dt_acts[$id]['fh_captura'], $fecha_actual->format('Y-m-d H:i:s'));

        if($tiempoTranscurrido['horas']<$dt_acts[$id]['hr_min'] && $tiempoTranscurrido['horas']>$dt_acts[$id]['hr_max']){
              $HTML .='<div class="alert alert-warning" role="alert" title="SLA ['.$dt_acts[$id]['hr_min'].'-'.$dt_acts[$id]['hr_max'].'] hrs '.$dt_acts[$id]["prioridad_desc"].'-> Horas Transcurridas '.$tiempoTranscurrido['horas'].'">
                        <i class="fas fa-exclamation-triangle"></i> Actividad en Rango de SLA 
                      </div>';
        }
        
        if($tiempoTranscurrido['horas']>$dt_acts[$id]['hr_max']){
              $HTML .='<div class="alert alert-danger" role="alert" title="SLA ['.$dt_acts[$id]['hr_min'].'-'.$dt_acts[$id]['hr_max'].'] hrs '.$dt_acts[$id]["prioridad_desc"].'-> Horas Transcurridas '.$tiempoTranscurrido['horas'].'">
                          <i class="<i class="fas fa-bomb" ></i> Actividad Exedida de SLA 
                        </div>';
        }

     
     
        $HTML .= '<b title="Tiempo transcurrido desde '.$dt_acts[$id]['fh_captura'].' - '.$fecha_actual->format('Y-m-d H:i:s').' ">'.
                   $tiempoTranscurrido['texo']. 
                   '</b><br>';

        $HTML .= ($dt_acts[$id]['f_plan_i'] != '' ? '<b>Plan Inicio:</b>' . $dt_acts[$id]['f_plan_i'] . ' ' : '') .
          ($dt_acts[$id]['f_plan_f'] != '' ? '<b>Plan Fin:</b>' . $dt_acts[$id]['f_plan_f'] . '' : '');

        if (USER_TYPE == 'SPUS') {
          $HTML .= '<button type="button" class="btn btn-default btn-icon" style="position: absolute;right: 10px;top: 10px;" onclick="editActividad(\'' . $dt_acts[$id]['folio'] . '\')">
                              <i class="fas fa-pencil-alt"></i>
                          </button>';

          if ($dt_acts[$id]['avance'] == 100) {
            $HTML .= '<button type="button" class="btn btn-success" style="position: absolute;right: 60px;top: 10px;" onclick="statusActividad(\'' . $dt_acts[$id]['folio'] . '\',\'F\',false)">
                                    <i class="fas fa-pencil-alt btn-icon"></i> Finalizar
                                </button>';
          }
        }

        $HTML .= '</div></td>    
            </tr>';
      }

      $HTML .= '</tbody>
          </table>';
    } else {
      $HTML = '<div class="alert alert-warning" role="alert">
              NO TIENES ACTIVIDADES REGISTRADAS.
            </div>';
    }


    echo $HTML;
    ?>
