

<?php
$db = new Conexion();

$folio = $db->real_escape_string($_POST['folio']);

$q = "SELECT a.id,a.dir,a.id_u_registra,a.fh_registra,concat(u.nombre,' ',u.apellido_p,' ',u.apellido_m) as nombre ,u.dir_foto
                FROM act_r_adjuntos as a LEFT JOIN 
                    users as u on a.id_u_registra = u.id 
                WHERE folio_act ='$folio' ORDER by fh_registra;";

$dt_adjuntos=findtablaq($q,"id");

$HTML ='';
if ($dt_adjuntos!=false){
  foreach ($dt_adjuntos as $id => $array) {
        $f = $dt_adjuntos[$id]["dir"];
        $ext = explode('.', $f)[1];                
        $dir = 'views/images/attachments/' . substr($folio, 0, 2) . '/' . $folio . '/' . $f;
        $icon='fas fa-eye';

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
                $icon='fas fa-arrow-alt-circle-down';

                break;

            case 'pdf':
                $img = "views/images/icons/pdf.png";
                $icon='fas fa-arrow-alt-circle-down';

                break;

            default:
                $img = "views/images/icons/otro.png";
                $icon='fas fa-arrow-alt-circle-down';

                break;
        }

        $HTML .= '<div class="col-xs-6 col-sm-4 col-md-2 adjunto-item"><img  class="img-adjunto" src="' . $img . '" >';

        $HTML .= '<div title="Aporte del usuario: '.$dt_adjuntos[$id]['nombre'].'" class="circular" 
                    style="background: url(views/images/profile/'.($dt_adjuntos[$id]['dir_foto']!=''?$dt_adjuntos[$id]['dir_foto']:'userDefault.png').');  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff;    position: absolute;    top: 0px; "></div>';


        if (USER_TYPE == 'SPUS' || USER_ID == $dt_adjuntos[$id]["id_u_registra"]) {
            $HTML .= '<button class="btn btn-default" type="button" onclick="deleteAdjunto(\'' . $id . '\',\'' . $dir . '\',false);" style="    position: absolute;    z-index: 1000;    left: 10px;    bottom: 10px;"><i class="fas fa-trash-alt"></i></button>';
        }
        if (USER_TYPE == 'SPUS' || USER_ID == $dt_adjuntos[$id]["id_u_registra"]||$icon=='fas fa-eye') {
            $HTML .= '<button class="btn btn-default" type="button" onclick="preview(\'' . $dir . '\',\'' . $ext . '\');" style="    position: absolute;    z-index: 1000;    right: 10px;    bottom: 10px;"><i class="' . $icon . '"></i></button>';
        }

        $HTML .= '</div>';

    }
  }else {
    $HTML = '<div class="alert alert-warning" role="alert">
                NO HAY ADJUNTOS EN ESTE FOLIO
                </div>';
    }





echo $HTML;
 ?>
