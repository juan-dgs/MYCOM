
<?php

   /*$qMenu = 'SELECT m.c_modulo,m.nivel1,m.nivel2,m.nivel3,m.tipo,m.titulo,m.vinculo,m.icono,m.dir,m.keywords,IF(u.id='.$_SESSION["user_id"].' or m.nivel1=0 or tipo = "MEN", 1,0)AS permiso
               FROM
                   menu AS m left JOIN
                   menu_permission as p on m.c_modulo=p.c_modulo left JOIN
                   users_types as ut on ut.codigo=p.c_tipo_usuario left JOIN
                   users as u on u.c_tipo_usuario=ut.codigo
               WHERE m.activo = 1
               GROUP by m.c_modulo
               HAVING permiso = 1
               ORDER by m.nivel1,m.nivel2,m.nivel3 ;';*/

$qMenu ='SELECT m.c_modulo,m.nivel1,m.nivel2,m.nivel3,m.tipo,m.titulo,m.vinculo,m.icono,m.dir,m.keywords
        FROM menu AS m left JOIN menu_permission as p on m.c_modulo=p.c_modulo left JOIN users_types as ut on ut.codigo=p.c_tipo_usuario WHERE m.activo = 1 AND 
              (m.nivel1=0 or tipo = "MEN" or p.c_tipo_usuario ="'.USER_TYPE.'")
        GROUP by m.c_modulo 
        ORDER by m.nivel1,m.nivel2,m.nivel3;';

  $dt_menu=findtablaq($qMenu,"c_modulo");

  $html_menu="";
  if ($qMenu != false){
    foreach ($dt_menu as $C_MODULO => $array) {
      if ($dt_menu[$C_MODULO]["nivel2"] == 0 and $dt_menu[$C_MODULO]["nivel3"] == 0){

        $html_n2='';
        $kw_n2 = '';
        $Tkw_n3 ='';
        $count_n2 = 0;

        if($dt_menu[$C_MODULO]["tipo"] =="MEN"){
          $html_n2='<ul class="nav nav-treeview">';
          foreach ($dt_menu as $C_MODULO_N2 => $array) {
            $html_n3 ='';
            $kw_n3 = '';
            $count_n3=0;

            if($dt_menu[$C_MODULO_N2]["tipo"] =="MEN"){
              $html_n3='<ul class="nav nav-treeview">';
              foreach ($dt_menu as $C_MODULO_N3 => $array) {
                if (
                    $dt_menu[$C_MODULO_N3]["nivel2"] != 0 and $dt_menu[$C_MODULO_N3]["nivel3"] != 0 and
                    $dt_menu[$C_MODULO_N3]["nivel1"] == $dt_menu[$C_MODULO]["nivel1"] and
                    $dt_menu[$C_MODULO_N3]["nivel2"] == $dt_menu[$C_MODULO_N2]["nivel2"]
                  ){
                  $html_n3.='<li class="nav-item" keywords="'.$dt_menu[$C_MODULO]["titulo"].' '.$dt_menu[$C_MODULO_N2]["titulo"].' '.$dt_menu[$C_MODULO_N3]["titulo"].' '.$dt_menu[$C_MODULO_N3]["keywords"].'">
                                  <a href="'.($dt_menu[$C_MODULO_N3]["tipo"] !="MEN"?$dt_menu[$C_MODULO_N3]["vinculo"]:'#').'" class="nav-link">
                                    <i class="fas '.$dt_menu[$C_MODULO_N3]["icono"].' nav-icon"></i>
                                    <p>'.$dt_menu[$C_MODULO_N3]["titulo"].($dt_menu[$C_MODULO_N3]["tipo"] =="MEN"?'<i class="right fas fa-angle-left"></i></p>':'').'</p>
                                  </a>
                                </li>';
                                $kw_n3.= ' '.$dt_menu[$C_MODULO]["titulo"].' '.$dt_menu[$C_MODULO_N2]["titulo"].' '.$dt_menu[$C_MODULO_N3]["titulo"].' '.$dt_menu[$C_MODULO_N3]["keywords"];
                                $Tkw_n3.= ' '.$dt_menu[$C_MODULO]["titulo"].' '.$dt_menu[$C_MODULO_N2]["titulo"].' '.$dt_menu[$C_MODULO_N3]["titulo"].' '.$dt_menu[$C_MODULO_N3]["keywords"];
                                $count_n3++;
                }
              }
              $html_n3.='</ul>';
            }

            if ($dt_menu[$C_MODULO_N2]["nivel2"] != 0 and $dt_menu[$C_MODULO_N2]["nivel3"] == 0 and $dt_menu[$C_MODULO_N2]["nivel1"] == $dt_menu[$C_MODULO]["nivel1"]){

              if($dt_menu[$C_MODULO_N2]["tipo"]!="MEN" or $count_n3>0){

                    $html_n2.='<li class="nav-item" keywords="'.$dt_menu[$C_MODULO]["titulo"].' '.$dt_menu[$C_MODULO_N2]["titulo"].' '.$dt_menu[$C_MODULO_N2]["keywords"].' '.$kw_n3.'">
                                    <a href="'.($dt_menu[$C_MODULO_N2]["tipo"] !="MEN"?$dt_menu[$C_MODULO_N2]["vinculo"]:'#').'" class="nav-link">
                                      <i class="fas '.$dt_menu[$C_MODULO_N2]["icono"].' nav-icon"></i>
                                      <p>'.$dt_menu[$C_MODULO_N2]["titulo"].($dt_menu[$C_MODULO_N2]["tipo"] =="MEN"?'<i class="right fas fa-angle-left"></i></p>':'').'</p>
                                    </a>
                                    '.$html_n3 .'
                                  </li>';
                                  $kw_n2 .= ' '.$dt_menu[$C_MODULO]["titulo"].' '.$dt_menu[$C_MODULO_N2]["titulo"].' '.$dt_menu[$C_MODULO_N2]["keywords"];
                                  $count_n2++;
              }

            }
          }
          $html_n2.='</ul>';

        }

        if($dt_menu[$C_MODULO]["tipo"]!="MEN" or $count_n2 >0){
                $html_menu .= '<li class="nav-item" keywords="'.$dt_menu[$C_MODULO]["titulo"].' '.$dt_menu[$C_MODULO]["keywords"].' '.$kw_n2.' '.$Tkw_n3.'">
                                <a href="'.($dt_menu[$C_MODULO]["tipo"] !="MEN"?$dt_menu[$C_MODULO]["vinculo"]:'').'" class="nav-link">
                                  <i class="fas '.$dt_menu[$C_MODULO]["icono"].' nav-icon"></i>
                                  <p>'.$dt_menu[$C_MODULO]["titulo"].($dt_menu[$C_MODULO]["tipo"] =="MEN"?'<i class="right fas fa-angle-left"></i></p>':'').'</p>
                                </a>
                                '.$html_n2.'
                              </li>';
        }

      }

    }
  }


 ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="logout" class="brand-link">
    <img src="<?php echo ADMINLTE; ?>dist/img/ADMINLTELogo.png" alt="ADMINLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">ADMINPANEL</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo USER_PHOTO; ?>" class="img-circle elevation-2" alt="">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo USER_NAME; ?></a>
        <b style="    color: #fff;    font-size: 11px;    float: right;    margin-top: -5px;"><?php echo USER_TYPE_DESC; ?></b>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group">
        <input class="form-control form-control-sidebar" type="text" placeholder="Buscar" aria-label="Buscar" id="ProgramSearch">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php echo $html_menu; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

<script>
  $(document).ready(function(){
      $("#ProgramSearch").keyup(function(){
                ProgramSearch();
      });
  });

  function ProgramSearch(){
    var filtro = $("#ProgramSearch").val().toLowerCase().replace('á', 'a').replace('é', 'e').replace('í', 'i').replace('ó', 'o').replace('ú', 'u').replace(/[^a-zA-Z 0-9.]+/g, '');
    var f = filtro.split(" ");
    if(f.length>0){
      $(".nav-treeview").css("display","block");

      $(".nav-item").each(function(){
        if($(this).attr("keywords") === '' || $(this).attr("keywords")==null){
            $(this).attr("keywords","NOPROGRAMAIGNORA");
          }
          var kws = $(this).attr("keywords").toLowerCase().replace('á', 'a').replace('é', 'e').replace('í', 'i').replace('ó', 'o').replace('ú', 'u').replace(/[^a-zA-Z 0-9.]+/g, '');

          

          var filtra = true;
          $.each(f,function(i){
            //console.log(kws + ' <> ' +f[i]+ ' = ' + kws.indexOf(f[i]));
            if(kws.indexOf(f[i])==-1){
              filtra = false;
              return false;
            }
          });

            if(filtra){
              $(this).css("display","block");
            }else{
              $(this).css("display","none");
            }



      });
    }else{
      $(".nav-item").css("display","block");
      $(".nav-treeview").css("display","none");
    }


  }

</script>
