

<?php
$db = new Conexion();



$folio = $db->real_escape_string($_POST['folio']);

$q = "SELECT c.*,if(id_u='".USER_ID."',1,0) as val,concat(u.nombre,' ',u.apellido_p,' ',u.apellido_m) as nombre,u.dir_foto,u.activo,u.c_tipo_usuario as tipo_u FROM ( 
                            SELECT concat('0',id) AS id,id_usuario_captura as id_u,descripcion as comentario,fh_captura as fh_registra,0 as avance  FROM actividades WHERE folio ='$folio' UNION ALL 
                            SELECT concat('A',id) AS id,id_u_registra as id_u,GROUP_CONCAT(DISTINCT dir) AS comentario,max(fh_registra) as fh_registra ,0 as avance FROM act_r_adjuntos WHERE folio_act ='$folio' GROUP BY id_u_registra,date(fh_registra) UNION ALL 
                            SELECT concat('C',id) AS id,id_u_registra as id_u,comentario,fh_registra, avance FROM act_r_comentarios WHERE folio_act ='$folio' 
                    ) as c LEFT JOIN users as u on c.id_u = u.id 
                    ORDER by c.fh_registra;";

$dt_item=findtablaq($q,"id");

$HTML ='';
if ($dt_item!=false){
  foreach ($dt_item as $id => $array) {
    $HTML .= '<div class="direct-chat-msg '.($dt_item[$id]['val']!=1?'left':'right').'">
                    <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name '.($dt_item[$id]['val']!=1?'pull-left':'pull-right').'">'.$dt_item[$id]['nombre'].'</span>
                        <span class="direct-chat-timestamp '.($dt_item[$id]['val']!=1?'pull-right':'pull-left').'">'.  strftime('%c', strtotime($dt_item[$id]['fh_registra'])).'</span>
                    </div>
                    <div class="direct-chat-img" title="Usuario Responsable: '.$dt_item[$id]['nombre'].'" class="circular" style="background: url(views/images/profile/'.($dt_item[$id]['dir_foto']!=''?$dt_item[$id]['dir_foto']:'userDefault.png').');  background-size:  cover; '.($dt_item[$id]['tipo_u']=='SPUS'?"width:50px; height: 50px;  border: solid 3px #ffc007;":"width:40px; height: 40px;  border: solid 2px #fff;").' "></div>
                    <div class="direct-chat-text  '.(substr($id,0,1)=='A'?'text-center':'').' ">';

                    if(substr($id,0,1)!='A'){
                        $HTML .= $dt_item[$id]['comentario'].($dt_item[$id]['avance']>0?'<b style="position: absolute; top: 0; right: 0;  opacity: .2;">'.$dt_item[$id]['avance'].'%</b>':'');
                    }else{
                        if($dt_item[$id]['comentario']!=''){
                            foreach (explode(',',$dt_item[$id]['comentario']) as $i) {
                                $HTML .='<img src="views/images/adjuntos/'.substr($folio,0,2).'/'.$folio.'/'.$i.'" height="100px;">';                          
                            }
                        }
                    }
                   
                    $HTML .= '</div>
                </div>';
                //C:\xampp\htdocs\MYCOM\views\images\adjuntos\25\25T040006

  }
}else {
  $HTML = '<div class="alert alert-warning" role="alert">
              NO HAY COMENTARIOS DE ESTE FOLIO.
            </div>';

}





echo $HTML;
 ?>
