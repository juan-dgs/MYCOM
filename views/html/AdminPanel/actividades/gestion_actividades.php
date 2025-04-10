<?php
include(HTML . 'AdminPanel/masterPanel/head.php');
include(HTML . 'AdminPanel/masterPanel/navbar.php');
include(HTML . 'AdminPanel/masterPanel/menu.php');
include(HTML . 'AdminPanel/masterPanel/breadcrumb.php');


$qTipo = 'SELECT codigo,descripcion FROM act_c_tipos WHERE activo=1;';
$dt_tipo = findtablaq($qTipo, "codigo");

$qClasifica = 'SELECT codigo,descripcion FROM act_c_clasificacion WHERE activo = 1;';
$dt_clasifica = findtablaq($qClasifica, "codigo");

$qPrio = 'SELECT codigo,descripcion,hr_min,hr_max FROM act_c_prioridades WHERE activo = 1;';
$dt_prio = findtablaq($qPrio, "codigo");

$qCliente = 'SELECT id,concat(alias," - ",razon_social) as nombre FROM act_c_clientes WHERE activo = 1;';
$dt_cliente = findtablaq($qCliente, "id");


$qUsuarios = 'SELECT id,concat(nombre," ",apellido_p," ",apellido_m) as nombre,usuario,c_tipo_usuario as tipo,dir_foto as foto FROM users WHERE activo =1;';
$dt_usuarios = findtablaq($qUsuarios, "id");

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!--link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/select2/css/select2.min.css"-->
<link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!--script src="<?php echo ADMINLTE; ?>plugins/select2/js/select2.full.min.js"></script-->

<link rel="stylesheet" href="<?php echo ADMINLTE; ?>plugins/summernote/summernote-bs4.min.css">
<script src="<?php echo ADMINLTE; ?>plugins/summernote/summernote-bs4.min.js"></script>


<!-- FILE INPUT -->
<link rel="stylesheet" href="views/components/fileinput/css/fileinput.min.css">
<link rel="stylesheet" href="views/components/fileinput/css/fileinput-rtl.min.css">

<script src="views/components/fileinput/js/plugins/buffer.min.js"></script>
<script src="views/components/fileinput/js/plugins/filetype.min.js"></script>
<script src="views/components/fileinput/js/plugins/piexif.min.js"></script>
<script src="views/components/fileinput/js/plugins/sortable.min.js"></script>
<script src="views/components/fileinput/js/fileinput.min.js"></script>
<script src="views/components/fileinput/themes/fa5/theme.min.js"></script>
<script src="views/components/fileinput/js/locales/es.js"></script>

<style>
    /* .box {
        position: relative;
        border-radius: 3px;
        background: #ffffff;
        width: 100%;
    }

    .box.box-primary {
        border-top-color: #3c8dbc;
    }

    .box.box-info {
        border-top-color: #00c0ef;
    }

    .box.box-danger {
        border-top-color: #dd4b39;
    }

    .box.box-warning {
        border-top-color: #f39c12;
    }

    .box.box-success {
        border-top-color: #00a65a;
    }

    .box.box-default {
        border-top-color: #d2d6de;
    }

    .box.collapsed-box .box-body,
    .box.collapsed-box .box-footer {
        display: none;
    }

    .box .nav-stacked>li {
        border-bottom: 1px solid #f4f4f4;
        margin: 0;
    }

    .box .nav-stacked>li:last-of-type {
        border-bottom: none;
    }

    .box.height-control .box-body {
        max-height: 300px;
        overflow: auto;
    }

    .box .border-right {
        border-right: 1px solid #f4f4f4;
    }

    .box .border-left {
        border-left: 1px solid #f4f4f4;
    }

    .box.box-solid {
        border-top: 0;
    }

    .box.box-solid>.box-header .btn.btn-default {
        background: transparent;
    }

    .box.box-solid>.box-header .btn:hover,
    .box.box-solid>.box-header a:hover {
        background: rgba(0, 0, 0, 0.1);
    }

    .box.box-solid.box-default {
        border: 1px solid #d2d6de;
    }

    .box.box-solid.box-default>.box-header {
        color: #444;
        background: #d2d6de;
        background-color: #d2d6de;
    }

    .box.box-solid.box-default>.box-header a,
    .box.box-solid.box-default>.box-header .btn {
        color: #444;
    }

    .box.box-solid.box-primary {
        border: 1px solid #3c8dbc;
    }

    .box.box-solid.box-primary>.box-header {
        color: #fff;
        background: #3c8dbc;
        background-color: #3c8dbc;
    }

    .box.box-solid.box-primary>.box-header a,
    .box.box-solid.box-primary>.box-header .btn {
        color: #fff;
    }

    .box.box-solid.box-info {
        border: 1px solid #00c0ef;
    }

    .box.box-solid.box-info>.box-header {
        color: #fff;
        background: #00c0ef;
        background-color: #00c0ef;
    }

    .box.box-solid.box-info>.box-header a,
    .box.box-solid.box-info>.box-header .btn {
        color: #fff;
    }

    .box.box-solid.box-danger {
        border: 1px solid #dd4b39;
    }

    .box.box-solid.box-danger>.box-header {
        color: #fff;
        background: #dd4b39;
        background-color: #dd4b39;
    }

    .box.box-solid.box-danger>.box-header a,
    .box.box-solid.box-danger>.box-header .btn {
        color: #fff;
    }

    .box.box-solid.box-warning {
        border: 1px solid #f39c12;
    }

    .box.box-solid.box-warning>.box-header {
        color: #fff;
        background: #f39c12;
        background-color: #f39c12;
    }

    .box.box-solid.box-warning>.box-header a,
    .box.box-solid.box-warning>.box-header .btn {
        color: #fff;
    }

    .box.box-solid.box-success {
        border: 1px solid #00a65a;
    }

    .box.box-solid.box-success>.box-header {
        color: #fff;
        background: #00a65a;
        background-color: #00a65a;
    }

    .box.box-solid.box-success>.box-header a,
    .box.box-solid.box-success>.box-header .btn {
        color: #fff;
    }

    .box.box-solid>.box-header>.box-tools .btn {
        border: 0;
        box-shadow: none;
    }

    .box.box-solid[class*='bg']>.box-header {
        color: #fff;
    }

    .box .box-group>.box {
        margin-bottom: 5px;
    }

    .box .knob-label {
        text-align: center;
        color: #333;
        font-weight: 100;
        font-size: 12px;
        margin-bottom: 0.3em;
    }

    .box>.overlay,
    .overlay-wrapper>.overlay,
    .box>.loading-img,
    .overlay-wrapper>.loading-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%
    }

    .box .overlay,
    .overlay-wrapper .overlay {
        z-index: 50;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 3px;
    }

    .box .overlay>.fa,
    .overlay-wrapper .overlay>.fa {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -15px;
        margin-top: -15px;
        color: #000;
        font-size: 30px;
    }

    .box .overlay.dark,
    .overlay-wrapper .overlay.dark {
        background: rgba(0, 0, 0, 0.5);
    }

    .box-header:before,
    .box-body:before,
    .box-footer:before,
    .box-header:after,
    .box-body:after,
    .box-footer:after {
        content: " ";
        display: table;
    }

    .box-header:after,
    .box-body:after,
    .box-footer:after {
        clear: both;
    }

    .box-header {
        color: #444;
        display: block;
        padding: 10px;
        position: relative;
    }

    .box-header.with-border {
        border-bottom: 1px solid #f4f4f4;
    }

    .collapsed-box .box-header.with-border {
        border-bottom: none;
    }

    .box-header>.fa,
    .box-header>.glyphicon,
    .box-header>.ion,
    .box-header .box-title {
        display: inline-block;
        font-size: 18px;
        margin: 0;
        line-height: 1;
    }

    .box-header>.fa,
    .box-header>.glyphicon,
    .box-header>.ion {
        margin-right: 5px;
    }

    .box-header>.box-tools {
        position: absolute;
        right: 10px;
        top: 5px;
    }

    .box-header>.box-tools [data-toggle="tooltip"] {
        position: relative;
    }

    .box-header>.box-tools.pull-right .dropdown-menu {
        right: 0;
        left: auto;
    }

    .btn-box-tool {
        padding: 5px;
        font-size: 12px;
        background: transparent;
        color: #97a0b3;
    }

    .open .btn-box-tool,
    .btn-box-tool:hover {
        color: #606c84;
    }

    .btn-box-tool.btn:active {
        box-shadow: none;
    }

    .box-body {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        padding: 10px;
    }

    .no-header .box-body {
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
    }

    .box-body>.table {
        margin-bottom: 0;
    }

    .box-body .fc {
        margin-top: 5px;
    }

    .box-body .full-width-chart {
        margin: -19px;
    }

    .box-body.no-padding .full-width-chart {
        margin: -9px;
    }

    .box-body .box-pane {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 3px;
    }

    .box-body .box-pane-right {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 0;
    }

    .box-footer {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        border-top: 1px solid #f4f4f4;
        padding: 10px;
        background-color: #fff;
    }

    .direct-chat .box-body {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        position: relative;
        overflow-x: hidden;
        padding: 0;
    }

    .direct-chat.chat-pane-open .direct-chat-contacts {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0);
    }

   

    .direct-chat-msg,
    .direct-chat-text {
        display: block;
    }

    .direct-chat-msg {
        margin-bottom: 10px;
    }

    .direct-chat-msg:before,
    .direct-chat-msg:after {
        content: " ";
        display: table;
    }

    .direct-chat-msg:after {
        clear: both;
    }

    .direct-chat-messages,
    .direct-chat-contacts {
        -webkit-transition: -webkit-transform .5s ease-in-out;
        -moz-transition: -moz-transform .5s ease-in-out;
        -o-transition: -o-transform .5s ease-in-out;
        transition: transform .5s ease-in-out;
    }

    .direct-chat-text {
        border-radius: 5px;
        position: relative;
        padding: 5px 10px;
        background: #d2d6de;
        border: 1px solid #d2d6de;
        margin: 5px 0 0 50px;
        color: #444;
    }

    .direct-chat-text:after,
    .direct-chat-text:before {
        position: absolute;
        right: 100%;
        top: 15px;
        border: solid transparent;
        border-right-color: #d2d6de;
        content: ' ';
        height: 0;
        width: 0;
        pointer-events: none;
    }

    .direct-chat-text:after {
        border-width: 5px;
        margin-top: -5px;
    }

    .direct-chat-text:before {
        border-width: 6px;
        margin-top: -6px;
    }

    .right .direct-chat-text {
        margin-right: 50px;
        margin-left: 0;
    }

    .right .direct-chat-text:after,
    .right .direct-chat-text:before {
        right: auto;
        left: 100%;
        border-right-color: transparent;
        border-left-color: #d2d6de;
    }

    .direct-chat-img {
        border-radius: 50%;
        float: left;
        width: 40px;
        height: 40px;
    }

    .right .direct-chat-img {
        float: right;
    }

    .direct-chat-info {
        display: block;
        margin-bottom: 2px;
        font-size: 12px;
    }

    .direct-chat-name {
        font-weight: 600;
    }

    .direct-chat-timestamp {
        color: #999;
    }

    .direct-chat-contacts-open .direct-chat-contacts {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0);
    }

    .direct-chat-contacts {
        -webkit-transform: translate(101%, 0);
        -ms-transform: translate(101%, 0);
        -o-transform: translate(101%, 0);
        transform: translate(101%, 0);
        position: absolute;
        top: 0;
        bottom: 0;
        height: 250px;
        width: 100%;
        background: #222d32;
        color: #fff;
        overflow: auto;
    }

    .contacts-list>li {
        border-bottom: 1px solid rgba(0, 0, 0, 0.2);
        padding: 10px;
        margin: 0;
    }

    .contacts-list>li:before,
    .contacts-list>li:after {
        content: " ";
        display: table;
    }

    .contacts-list>li:after {
        clear: both;
    }

    .contacts-list>li:last-of-type {
        border-bottom: none;
    }

    .contacts-list-img {
        border-radius: 50%;
        width: 40px;
        float: left;
    }

    .contacts-list-info {
        margin-left: 45px;
        color: #fff;
    }

    .contacts-list-name,
    .contacts-list-status {
        display: block;
    }

    .contacts-list-name {
        font-weight: 600;
    }

    .contacts-list-status {
        font-size: 12px;
    }

    .contacts-list-date {
        color: #aaa;
        font-weight: normal;
    }

    .contacts-list-msg {
        color: #999;
    }

    .direct-chat-danger .right>.direct-chat-text {
        background: #dd4b39;
        border-color: #dd4b39;
        color: #fff;
    }

    .direct-chat-danger .right>.direct-chat-text:after,
    .direct-chat-danger .right>.direct-chat-text:before {
        border-left-color: #dd4b39;
    }

    .direct-chat-primary .right>.direct-chat-text {
        background: #3c8dbc;
        border-color: #3c8dbc;
        color: #fff;
    }

    .direct-chat-primary .right>.direct-chat-text:after,
    .direct-chat-primary .right>.direct-chat-text:before {
        border-left-color: #3c8dbc;
    }

    .direct-chat-warning .right>.direct-chat-text {
        background: #f39c12;
        border-color: #f39c12;
        color: #fff;
    }

    .direct-chat-warning .right>.direct-chat-text:after,
    .direct-chat-warning .right>.direct-chat-text:before {
        border-left-color: #f39c12;
    }

    .direct-chat-info .right>.direct-chat-text {
        background: #00c0ef;
        border-color: #00c0ef;
        color: #fff;
    }

    .direct-chat-info .right>.direct-chat-text:after,
    .direct-chat-info .right>.direct-chat-text:before {
        border-left-color: #00c0ef;
    }

    .direct-chat-success .right>.direct-chat-text {
        background: #00a65a;
        border-color: #00a65a;
        color: #fff;
    }

    .direct-chat-success .right>.direct-chat-text:after,
    .direct-chat-success .right>.direct-chat-text:before {
        border-left-color: #00a65a;
    }*/


    .direct-chat-messages {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        -o-transform: translate(0, 0);
        transform: translate(0, 0);
        padding: 10px;
        height: 520px;
        overflow: auto;
    }

    .pull-right {
        float: right;
    }

    .avance {
        border-radius: 10px;
        height: 40px;
        position: relative;
        width: 100%;
        border: solid #07070726;
    }

    .avance b {
        position: absolute;
        top: 18px;
        left: 10px;
        font-size: 15px;
        font-weight: bold;
    }

    .novisible {
        display: none;
    }

    .btn-group.note-insert {
        display: none;
    }

    .text-center {
        text-align: center;
    }

    .direct-chat-text img,
    .img-adjunto {
        margin: 0 5px 5px 0px;
        border-radius: 8px;
        cursor: pointer;
    }

    #contentPreview img {
        width: 100%;
        /* Ocupa el 100% del ancho del contenedor */
        height: auto;
        /* Mantiene la proporción original */
        max-height: 600px;
        /* Establece la altura máxima */
        object-fit: contain;
        /* Asegura que la imagen no se deforme (opcional) */

    }

    #contentAdjuntos img {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    #contentAdjuntos img:hover {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        /* Sombra exterior */
        filter: brightness(1.1);
        /* Aumenta brillo */
    }

    #contentAdjuntos {
        height: 500px;
        overflow: auto;
        ;

    }

    #contentAdjuntos div.adjunto-item {
        position: relative;
        max-height: 240px;
        overflow: hidden;
    }

    #contentAdjuntos div.adjunto-item .btn {
        opacity: .5;
        transition: all 0.3s ease;
    }

    #contentAdjuntos div.adjunto-item .btn:hover {
        opacity: 1;
        transition: all 0.3s ease;
    }

    button.btn-icon {
        background: transparent;
        border: none;
    }

    button.btn-icon:hover {
        background: transparent;
        border: none;
    }

    .highlight {
        background-color: #c2ffd0 !important;
        box-shadow: 0 0 0 2px #bbf0c7;
        transition: all 0.3s ease;
        outline: none;
    }

    .cargando-spiner {
        width: 100%;
        padding: 80px;
        text-align: center;
    }

    .cargando-spiner i {
        font-size: 110px;
        color: #00000045;
    }

    .mark-number {
        position: absolute;
        right: -11px;
        background: #fd7e14;
        padding: 0px 15px;
        color: #fff;
        font-size: 20px;
        top: -20px;
        border-radius: 10px;
        font-weight: bold;
    }

    #contentInvolucrado {
        min-height: 50px;
        border-bottom: solid #00000012;
        margin-bottom: 3px;
    }

    .filtroOculto {
        font-size: 1px;
        position: absolute;
        top: 0;
        color: transparent;
    }
</style>

<?php if (USER_TYPE == 'SPUS') {    ?>
    <button class="btn btn-primary" type="button" onclick="newActividad();" style="    position: absolute;    z-index: 1000;"><i class="fa fa-plus"></i>Nueva Actividad</button>
<?php } ?>

<div id="contentActs">
</div>


<div id="modalFormularioActiviades" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titleFormularioActiviades"></h4>
            </div>
            <div class="modal-body" id="form-actividades">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_tipo_actividad">Tipo de Actividad:</label>
                            <select class="form-control select2ModalActividades" id="c_tipo_actividad" required>
                                <option value="">Seleciona Tipo de Actividad</option>
                                <?php
                                if (!empty($dt_tipo)) {
                                    foreach ($dt_tipo as $codigo => $array) {
                                        echo "<option value='" . $dt_tipo[$codigo]["codigo"] . "'>" . $dt_tipo[$codigo]["descripcion"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_clasifica_act">Clasificación:</label>
                            <select class="form-control select2ModalActividades" id="c_clasifica_act" required>
                                <option value="">Seleciona Clasificación</option>
                                <?php
                                if (!empty($dt_clasifica)) {
                                    foreach ($dt_clasifica as $codigo => $array) {
                                        echo "<option value='" . $dt_clasifica[$codigo]["codigo"] . "'>" . $dt_clasifica[$codigo]["descripcion"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_prioridad">Prioridad:</label>
                            <select class="form-control select2ModalActividades" id="c_prioridad" required>
                                <option value="">Seleciona Prioridad</option>
                                <?php
                                if (!empty($dt_prio)) {
                                    foreach ($dt_prio as $codigo => $array) {
                                        echo "<option value='" . $dt_prio[$codigo]["codigo"] . "' min='" . $dt_prio[$codigo]['hr_max'] . "' max='" . $dt_prio[$codigo]['hr_min'] . "' title='SLA max " . $dt_prio[$codigo]["hr_max"] . " hrs - min " . $dt_prio[$codigo]["hr_min"] . " hrs'>" . $dt_prio[$codigo]["descripcion"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_cliente">Cliente:</label>
                            <select class="form-control select2ModalActividades" id="c_cliente" required>
                                <option value="">Seleciona Cliente Responsable</option>
                                <?php
                                if (!empty($dt_cliente)) {
                                    foreach ($dt_cliente as $codigo => $array) {
                                        echo "<option value='" . $dt_cliente[$codigo]["id"] . "'>" . $dt_cliente[$codigo]["nombre"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group" style="position:relative;">
                            <label for="c_usuario_responsable">Usuario Responsable:</label>
                            <select class="form-control select2ModalActividades" id="c_usuario_responsable">
                                <option value="">Seleciona Usuarios Responsable</option>
                                <?php
                                if (!empty($dt_usuarios)) {
                                    foreach ($dt_usuarios as $codigo => $array) {
                                        echo "<option value='" . $dt_usuarios[$codigo]["id"] . "' foto='" . ($dt_usuarios[$codigo]["foto"] != '' ? $dt_usuarios[$codigo]["foto"] : 'userDefault.png') . "' usuario='" . $dt_usuarios[$codigo]["usuario"] . "' tipo='" . $dt_usuarios[$codigo]["tipo"] . "' title='" . $dt_usuarios[$codigo]["usuario"] . " " . $dt_usuarios[$codigo]["tipo"] . "'>" . $dt_usuarios[$codigo]["nombre"] . "</option>";
                                    }
                                }

                                ?>
                            </select>
                            <div id="c_usuario_responsable_foto" class="circular novisible" style="background-image: url(views/images/profile/userDefault.png);background-size:  cover; width: 60px;height: 60px;border: solid 2px #fff;position: absolute;top: 0;right: 16px; "></div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="c_usuarios_ivolucrados">Usuarios Involucrados:</label>
                            <select class="form-control select2ModalActividades" id="c_usuarios_ivolucrados" name="ivolucrados[]" multiple="multiple">>
                                <?php
                                if (!empty($dt_usuarios)) {
                                    foreach ($dt_usuarios as $codigo => $array) {
                                        echo "<option value='" . $dt_usuarios[$codigo]["id"] . "' foto='" . ($dt_usuarios[$codigo]["foto"] != '' ? $dt_usuarios[$codigo]["foto"] : 'userDefault.png') . "' usuario='" . $dt_usuarios[$codigo]["usuario"] . "' tipo='" . $dt_usuarios[$codigo]["tipo"] . "' title='" . $dt_usuarios[$codigo]["usuario"] . " " . $dt_usuarios[$codigo]["tipo"] . "'>" . $dt_usuarios[$codigo]["nombre"] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div id="c_usuario_involucrados_fotos"></div>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea id="descripcion" required></textarea>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="comentarios" id="comentarioContC" onclick='collapse("comentarioCont");'><i class="fa fa-angle-down"></i>Comentario:</label>
                            <div id="comentarioCont" class="collapse">
                                <textarea id="comentarios" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="notas" id="notasContC" onclick='collapse("notasCont");'><i class="fa fa-angle-down"></i>Notas:</label>
                            <div id="notasCont" class="collapse">
                                <textarea id="notas" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <label id="rangoContC" onclick='collapse("rangoCont");'><i class="fa fa-angle-down"></i>Rango de Fechas Plan:</label>
                        <div id="rangoCont" class="collapse">
                            <div class="form-group">
                                <label for="f_plan_i">Fecha Inicio:</label>
                                <input type="date" id="f_plan_i" min="<?php echo  date('Y-m-d'); ?>" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="f_plan_f">Fecha Fin:</label>
                                <input type="date" id="f_plan_f" min="<?php echo  date('Y-m-d'); ?>" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="dispositivo" id="rangoDisC" onclick='collapse("rangoDis");'><i class="fa fa-angle-down"></i>Dispositivo:</label>
                            <div id="rangoDis" class="collapse">
                                <input type="text" id="serie" class="form-control" placeholder="Numero de Serie">
                                <input type="text" id="mac" class="form-control" placeholder="Mac Adress">
                                <input type="text" id="otro" class="form-control" placeholder="Otro">
                            </div>
                        </div>
                    </div>




                </div>




            </div>
            <div class="modal-footer" id="btnsFormularioActiviades">
            </div>
        </div>
    </div>
</div>


<div id="modalSeguimientoActiviades" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titleSeguimiento"></h4>
            </div>
            <div class="modal-body">
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-body">
                        <div id="chatContent" class="direct-chat-messages">
                        </div>
                        <div class="box-footer">

                            <div id='chat-form-comentarios' class='collapse in'>
                                <div class="input-group">
                                    <input type="text" placeholder="Escribe avances o comentarios del folio" class="form-control" id='txtComentarios'>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn-flat" onclick='guardarAvance();'>Guardar</button>
                                    </span>
                                </div>
                                <div class="input-group" style='    width: 200px;    float: right;    margin-right: 90px;margin-top: 10px;' id='form-avance'>
                                    <span class="input-group-btn">
                                        <b>Avance: </b>
                                    </span>
                                    <input type="number" class="form-control" id='txtAvance' ultimo_avance='0'>
                                    <span class="input-group-btn">
                                        <b>%</b>
                                    </span>
                                </div>

                                <button type="button" class="btn btn-default pull-left" style='margin-top: 10px;' onclick="collapse('chat-form-adjuntos,chat-form-comentarios');inicializaFileInputAdjuntos('contFI');">Adjuntos <i class="fas fa-paperclip"></i></button>

                            </div>

                            <div id='chat-form-adjuntos' class='collapse'>
                                <div id='contFI'>
                                    <!--input id="adjuntosFI" name="archivos[]" type="file" multiple="true" class="fileinput file-loading" /-->
                                </div>

                                <button type="button" class="btn btn-default pull-left" style='margin-top: 10px;' onclick="collapse('chat-form-adjuntos,chat-form-comentarios');destruirFileInputAdjuntos();">Comentarios</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="modalAdjuntos" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="tituloAdjuntos">Vista Previa Adjunto</h4>
            </div>
            <div class="modal-body modal-preview row" id="contentAdjuntos">
            </div>
            <div class="modal-footer" style="display: block;">
                <button type="button" class="btn btn-default pull-left" style='margin-top: 10px;' onclick="collapse('subir-adjuntosA');inicializaFileInputAdjuntos('contFIA');">Adjuntos <i class="fas fa-paperclip"></i></button>

                <div id='subir-adjuntosA' class='collapse'>
                    <div id='contFIA'>
                        <!--input id="adjuntosFI" name="archivos[]" type="file" multiple="true" class="fileinput file-loading" /-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modalPreview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="tituloPreview">Vista Previa Adjunto</h4>
            </div>
            <div class="modal-body modal-preview" id="contentPreview">

            </div>
        </div>
    </div>
</div>

<script>
    var select2Act;
    var _CARGANDO = '<div class="cargando-spiner">' +
        '<i class="fa fa-spinner fa-spin fa-3x"></i>' +
        '</div>';

    $(document).ready(function() {
        getActividades();

        select2Act = $('.select2ModalActividades').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#modalFormularioActiviades')
        });

        $('#descripcion').summernote({
            height: 100
        });
        $('#comentarios').summernote({
            height: 100
        });
        $('#notas').summernote({
            height: 100
        });



        $("#f_plan_i").change(function() {
            $("#f_plan_f").attr('min', $("#f_plan_i").val());
        });

    });

    setTimeout(function() {
        defineFnCUR();
    }, 500);

    function collapse(id) {

        $.each(id.split(","), function(index, idx) {
            if ($("#" + idx + "C i").length > 0) {
                if ($("#" + idx).hasClass("show")) {
                    $("#" + idx + "C i").attr("class", "fa fa-angle-down");
                } else {
                    $("#" + idx + "C i").attr("class", "fa fa-angle-up");
                }
            }

            $("#" + idx).collapse('toggle');
        });

    }


    function defineFnCUR() {
        $(".select2ModalActividades").change(function() {
            if ($(this).attr("id") == 'c_usuario_responsable') {
                $('#c_usuarios_ivolucrados option').prop("disabled", false);
                $('#c_usuarios_ivolucrados').trigger('change');

                if ($(this).val() == '') {
                    $("#c_usuario_responsable_foto").addClass('novisible').css('background-image', '');
                } else {
                    $("#c_usuario_responsable_foto").removeClass('novisible').css('background-image', 'url("views/images/profile/' + $('option:selected', this).attr("foto") + '")');
                    $('#c_usuarios_ivolucrados option[value="' + $(this).val() + '"]').prop("disabled", true);
                    $('#c_usuarios_ivolucrados').trigger('change');

                    if ($('#c_usuarios_ivolucrados').val().indexOf($(this).val()) >= 0) {
                        $('#c_usuarios_ivolucrados').select2().val($(this).val()).trigger("change");

                        $("#c_usuarios_ivolucrados").val($("#c_usuarios_ivolucrados").val());
                    }

                }
                fotosInvolucrados($('#c_usuarios_ivolucrados').val());

            } else if ($(this).attr("id") == 'c_usuarios_ivolucrados') {
                fotosInvolucrados($(this).val());
            } else if ($(this).attr("id") == 'c_prioridad') {
                if ($("#c_prioridad").val() == 'P') {
                    $('#rangoCont').collapse("show");
                    $("#f_plan_i").val(formatDate());
                }
            }
        });
    }

    function fotosInvolucrados(arr) {
        var fotos = '';
        if (arr.length > 0) {
            $.each(arr, function(index, value) {
                fotos += '<div title="Usuario Involucrado: ' + $('#c_usuarios_ivolucrados option[value="' + value + '"]').html() + '" class="circular" style="background-image: url(views/images/profile/' + $('#c_usuarios_ivolucrados option[value="' + value + '"]').attr("foto") + ');  background-size:  cover; width:40px; height: 40px;  border: solid 2px #fff; "></div>';
            });
        }


        $("#c_usuario_involucrados_fotos").html(fotos);
    }


    function getActividades() {


        $.ajax({
            url: "ajax.php?mode=getactividades",
            type: "POST",
            data: {},
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {
                $('#contentActs').html(_CARGANDO);
            },
            success: function(datos) {
                $("#contentActs").html(datos);

                var arrayOrder = []; //[14, 'asc'], [0, 'asc'], [3, 'asc'], [5, 'asc']
                var arrayExport = ['excel']; //'excel'
                datatablebase("tablaActividades", false, 500, true, true, arrayOrder, arrayExport);
                //datatablebase(tableid, ffoot, scroll, order, search, arrayOrder, arrayExport)
                if (_FOLIO != '0') {
                    setTimeout(function() {
                        var $rowToHighlight = $('#tr' + _FOLIO);
                        highlightTableRow($rowToHighlight, 'highlight', true);
                    }, 500);
                }
            }
        });
    }

    var _MODO = '0';

    function newActividad() {
        if (_MODO != 'NEW') {
            _MODO = 'NEW';
            limpiaFormulario('form-actividades');
        }
        $("#modalFormularioActiviades").modal();
        $("#titleFormularioActiviades").html("<i class='fa fa-plus'></i> Captura nueva actividad");
        $("#btnsFormularioActiviades").html('<button type="button" class="btn btn-success" onclick="saveNewActividad()">Guardar</button>' +
            '<button type="button" class="btn btn-default" onclick="limpiaFormulario(\'form-actividades\')">Limpiar</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');
        $('#c_tipo_actividad').prop('disabled', false);
    }
    var _FOLIO = '0';

    function editActividad(folio) {
        _MODO = 'EDIT';
        _FOLIO = folio;

        $("#modalFormularioActiviades").modal();
        $("#titleFormularioActiviades").html("<i class='fas fa-pencil-alt'></i> Editar actividad folio " + folio);
        $("#btnsFormularioActiviades").html('<button type="button" class="btn btn-success" onclick="saveChangesActividad()">Guardar</button>' +
            '<button type="button" class="btn btn-danger pull-left" style="position: absolute;    left: 15px;" onclick="deleteActividad(\'' + folio + '\')">Elimina</button>' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>');


        $.ajax({
            url: "ajax.php?mode=getregister",
            type: "POST",
            data: {
                tabla: 'actividades',
                campoId: 'folio',
                datoId: folio
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {},
            success: function(datos) {
                var respuesta = JSON.parse(datos);
                //console.log(respuesta);

                $("#c_tipo_actividad").val(respuesta[1]["c_tipo_act"]);
                $('#c_tipo_actividad').prop('disabled', true);
                $('#c_tipo_actividad').trigger('change');


                $("#c_clasifica_act").val(respuesta[1]["c_clasifica_act"]);
                $('#c_clasifica_act').trigger('change');

                $("#c_prioridad").val(respuesta[1]["c_prioridad"]);
                $('#c_prioridad').trigger('change');

                $("#c_cliente").val(respuesta[1]["id_cliente"]);
                $('#c_cliente').trigger('change');

                $("#c_usuario_responsable").val(respuesta[1]["id_usuario_resp"]);
                $('#c_usuario_responsable').trigger('change');

                getInvolucrados(folio);


                if (respuesta[1]["f_plan_i"] != '' || respuesta[1]["f_plan_f"] != '') {
                    $('#rangoCont').collapse("show");
                    $("#f_plan_i").val(respuesta[1]["f_plan_i"]);
                    $("#f_plan_f").val(respuesta[1]["f_plan_f"]);
                } else {
                    $('#rangoCont').collapse("hide");
                    $("#f_plan_i").val('');
                    $("#f_plan_f").val('');
                }

                if (respuesta[1]["dispositivo"].toString().split('|').length == 3) {
                    $('#rangoDis').collapse("show");
                    $("#serie").val(respuesta[1]["dispositivo"].toString().split('|')[0]);
                    $("#mac").val(respuesta[1]["dispositivo"].toString().split('|')[1])
                    $("#otro").val(respuesta[1]["dispositivo"].toString().split('|')[2]);
                } else {
                    $('#rangoDis').collapse("hide");
                    $("#serie").val('');
                    $("#mac").val('')
                    $("#otro").val('');
                }

                $('#descripcion').summernote('code', respuesta[1]["descripcion"]);

                if (respuesta[1]["comentario"] != '') {
                    $("#comentarioCont").collapse('show');
                    $('#comentarios').summernote('code', respuesta[1]["comentario"]);
                } else {
                    $("#comentarioCont").collapse('hide');
                    $('#comentarios').summernote('code', '');
                }

                if (respuesta[1]["notas"] != '') {
                    $("#notasCont").collapse('show');
                    $('#notas').summernote('code', respuesta[1]["notas"]);
                } else {
                    $("#notasCont").collapse('hide');
                    $('#notas').summernote('code', '');
                }

            }
        });
    }

    function getInvolucrados(folio) {
        $.ajax({
            url: "ajax.php?mode=getinvolucrados",
            type: "POST",
            data: {
                folio: folio
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {},
            success: function(datos) {
                var respuesta = JSON.parse(datos);

                $("#c_usuarios_ivolucrados").val(respuesta.INVOLUCRADDOS);
                $('#c_usuarios_ivolucrados').trigger('change');

                fotosInvolucrados($('#c_usuarios_ivolucrados').val());

            }
        });
    }

    function validaFormulario(idForm) {
        $(".validaFormError").remove();
        var valida = true;
        $("#" + idForm + " [required]").each(function() {
            var labelCampo = $(this).siblings("label");
            if ($(this).val() == '') {
                labelCampo.append("<b class='validaFormError' style='color:#ff00007d;'>*[Campo Requerido]</b>")
                valida = false;
            } else {
                labelCampo.children(".validaFormError").remove();
            }
        });

        return valida;
    }

    function limpiaFormulario(idForm) {
        $("#" + idForm + " .collapse").collapse('hide');
        select2Act.val(null).trigger("change");


        $("#" + idForm + " input,#" + idForm + " select,#" + idForm + " textarea").each(function() {
            $(this).val('');
        });

        $('#descripcion').summernote('code', '');
        $('#comentarios').summernote('code', '');
        $('#notas').summernote('code', '');

    }

    function saveChangesActividad() {
        if (validaFormulario("form-actividades")) {
            var clasificacion = $('#c_clasifica_act').val();
            var prioridad = $('#c_prioridad').val();
            var cliente = $('#c_cliente').val();
            var u_responsable = $('#c_usuario_responsable').val();
            var u_involucrados = $('#c_usuarios_ivolucrados').val();
            var descripcion = $('#descripcion').val();
            var comentarios = $('#comentarios').val();
            var notas = $('#notas').val();
            var fi = $('#f_plan_i').val();
            var ff = $('#f_plan_f').val();
            var dispositivo = '';
            dispositivo += $('#serie').val() + '|';
            dispositivo += $('#mac').val() + '|';
            dispositivo += $('#otro').val();


            $.ajax({
                url: "ajax.php?mode=saveactividad",
                type: "POST",
                data: {
                    folio: _FOLIO,
                    clasificacion: clasificacion,
                    prioridad: prioridad,
                    cliente: cliente,
                    u_responsable: u_responsable,
                    u_involucrados: u_involucrados,
                    descripcion: descripcion,
                    comentarios: comentarios,
                    notas: notas,
                    fi: fi,
                    ff: ff,
                    dispositivo: dispositivo
                },
                error: function(request, status, error) {
                    notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
                },
                beforeSend: function() {},
                success: function(datos) {
                    //console.log(datos);
                    var respuesta = JSON.parse(datos);
                    if (respuesta.codigo == "1") {
                        $('#modalFormularioActiviades').modal('hide');
                        getActividades();
                        limpiaFormulario('form-actividades');
                        notify(respuesta.alerta, 1500, "success", "top-end");
                    } else {
                        notify(respuesta.alerta, 1500, "error", "top-end");
                    }
                }
            });
        } else {
            notify("ERROR: Llene correctamente el formulario.", 1500, "error", "top-end");
        }
    }


    function saveNewActividad() {
        if (validaFormulario("form-actividades")) {
            var tipo = $('#c_tipo_actividad').val();
            var clasificacion = $('#c_clasifica_act').val();
            var prioridad = $('#c_prioridad').val();
            var cliente = $('#c_cliente').val();
            var u_responsable = $('#c_usuario_responsable').val();
            var u_involucrados = $('#c_usuarios_ivolucrados').val();
            var descripcion = $('#descripcion').val();
            var comentarios = $('#comentarios').val();
            var notas = $('#notas').val();
            var fi = $('#f_plan_i').val();
            var ff = $('#f_plan_f').val();
            var dispositivo = '';
            dispositivo += $('#serie').val() + '|';
            dispositivo += $('#mac').val() + '|';
            dispositivo += $('#otro').val();


            $.ajax({
                url: "ajax.php?mode=newactividad",
                type: "POST",
                data: {
                    tipo: tipo,
                    clasificacion: clasificacion,
                    prioridad: prioridad,
                    cliente: cliente,
                    u_responsable: u_responsable,
                    u_involucrados: u_involucrados,
                    descripcion: descripcion,
                    comentarios: comentarios,
                    notas: notas,
                    fi: fi,
                    ff: ff,
                    dispositivo: dispositivo
                },
                error: function(request, status, error) {
                    notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
                },
                beforeSend: function() {},
                success: function(datos) {
                    //console.log(datos);
                    var respuesta = JSON.parse(datos);
                    if (respuesta.codigo == "1") {
                        $('#modalFormularioActiviades').modal('hide');
                        getActividades();
                        limpiaFormulario('form-actividades');
                        notify(respuesta.alerta, 1500, "success", "top-end");
                    } else {
                        notify(respuesta.alerta, 1500, "error", "top-end");
                    }
                }
            });
        } else {
            notify("ERROR: Llene correctamente el formulario.", 1500, "error", "top-end");
        }
    }

    var _USERR = 0;

    function openComentarios(folio, userR) {
        _FOLIO = folio;
        _USERR = userR;

        destruirFileInputAdjuntos();
        $("#chat-form-comentarios").collapse('show');
        $("#chat-form-adjuntos").collapse('hide');

        $("#modalSeguimientoActiviades").modal("show");

        $("#titleSeguimiento").html("<i class='fas fa-sync-alt'></i> Seguimiento Folio " + folio);
        $.ajax({
            url: "ajax.php?mode=getseguimientofolio",
            type: "POST",
            data: {
                folio: folio
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {
                $('#chatContent').html(_CARGANDO);
            },
            success: function(datos) {
                $("#chatContent").html(datos);

                if (userR != '<?PHP echo USER_ID; ?>' && 'SPUS' != '<?PHP echo USER_TYPE; ?>') {
                    $('#txtAvance').prop('disabled', true);
                } else {
                    $('#txtAvance').prop('disabled', false);
                }

                $('#txtAvance').val(0);
                $('#txtAvance').attr("ultimo_avance", 0);

                $(".porAvanceTot").each(function() {
                    $('#txtAvance').val(parseInt($(this).html().replace("%", "")));
                    $('#txtAvance').attr("ultimo_avance", parseInt($(this).html().replace("%", "")));
                });

                $("#cm" + folio).remove();

                setTimeout(function() {
                    scrollTo('chatContent');
                }, 500);

            }
        });
    }

    function scrollTo(id) {
        $("#" + id).scrollTop(10000);
    }

    function guardarAvance() {
        var avance = $("#txtAvance").val();
        var avanceAntes = $("#txtAvance").attr('ultimo_avance');
        var comentarios = $("#txtComentarios").val();

        if (parseInt(avance) < parseInt(avanceAntes)) {
            notify('Error el Avance no puede ser menor al ultimo avance.', 1500, "error", "top-end");
            $("#txtAvance").focus();
        } else if (parseInt(avance) > 100) {
            notify('Error el Avance no puede ser mayor a 100%', 1500, "error", "top-end");
            $("#txtAvance").val(100);
            $("#txtAvance").focus();
        } else if (comentarios == '') {
            notify('Error escribe un comentario.', 1500, "error", "top-end");
            $("#txtComentarios").focus();
        } else {
            $.ajax({
                url: "ajax.php?mode=saveseguimientofolio",
                type: "POST",
                data: {
                    folio: _FOLIO,
                    comentarios: comentarios,
                    avance: avance
                },
                error: function(request, status, error) {
                    notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
                },
                beforeSend: function() {},
                success: function(datos) {
                    //console.log(datos);
                    var respuesta = JSON.parse(datos);
                    if (respuesta["codigo"] == "1") {
                        openComentarios(_FOLIO, _USERR);
                        getActividades();
                        $("#txtComentarios").val('');
                        notify(respuesta["alerta"], 1500, "success", "top-end");
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                    }
                }
            });
        }
    }

    function destruirFileInputAdjuntos() {
        $("#adjuntosFI").remove();
    }

    function inicializaFileInputAdjuntos(id) {
        destruirFileInputAdjuntos();
        $("#" + id).html('<input id="adjuntosFI" name="archivos[]" type="file" multiple="true" class="fileinput file-loading" />');

        var extradata = {};

        extradata["modo"] = 'actividades_adjuntos';
        extradata["id"] = _FOLIO;


        $("#adjuntosFI").fileinput({
            initialPreview: [],
            initialPreviewAsData: true,
            initialPreviewConfig: [],
            uploadUrl: "ajax.php?mode=uploadfiles",
            uploadAsync: true,
            validateInitialCount: true,
            overwriteInitial: false,
            language: "es",
            previewFileType: "Imagen",
            browseClass: "btn btn-info",
            browseLabel: "Seleccionar Archivos",
            browseIcon: "<i class=\"fas fa-folder\"></i> ",
            removeClass: "btn btn-danger",
            removeLabel: "Eliminar",
            removeIcon: "<i class=\"fas fa-trash-restore\"></i> ",
            uploadClass: "btn btn-success",
            uploadLabel: "Subir",
            uploadIcon: "<i class=\"fas fa-upload\"></i> ",
            allowedFileExtensions: ["jpg", "png", "gif", "pdf", "xls", "xlsx", "doc", "docx"],
            minFileCount: 1,
            maxFileCount: 20,
            uploadExtraData: extradata,
            fileActionSettings: {
                dragIcon: "<i class=\"glyphicon glyphicon-move\" aria-hidden=\"true\"></i>"
            }
        }).on('fileuploaded', function(e, params) {
            if (params.response.status == 'success') {
                if (id == 'contFI') {
                    openComentarios(_FOLIO, _USERR);
                } else if (id == 'contFIA') {
                    verAdjuntos(_FOLIO);
                    $("#subir-adjuntosA").collapse("hide");
                }
                getActividades();
                notify("Completado! Se subieron los adjuntos correctamente.", 1500, "success", "top-end");
                destruirFileInputAdjuntos();
            } else {
                notify("ERROR! Ocurrio un al subir uno o mas archivos.", 1500, "error", "top-end");
                console.log(params.response);
            }
        });

    }

    function verAdjuntos(folio) {
        _FOLIO = folio;
        destruirFileInputAdjuntos();
        $('#subir-adjuntosA').collapse("hide");
        $("#modalAdjuntos").modal("show");
        $("#tituloAdjuntos").html("Administra Adjuntos Folio " + folio);

        $.ajax({
            url: "ajax.php?mode=getadjuntosfolio",
            type: "POST",
            data: {
                folio: folio
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {
                $('#contentAdjuntos').html(_CARGANDO);
            },
            success: function(datos) {
                $("#contentAdjuntos").html(datos);
            }
        });
    }


    function preview(url, ext) {
        $("#tituloPreview").html("Vista Previa Adjunto");

        switch (ext) {
            case 'jpg':
            case 'png':
            case 'gif':
                $("#modalPreview").modal("show");
                $("#contentPreview").html('<img src="' + url + '">');
                break;
            case "pdf":
                $("#modalPreview").modal("show");
                $("#contentPreview").html('<iframe src="' + url + '#navpanes=0" width=100% height=600></iframe>');
                break;
            default:
                notifyConfirm("Confirmas que quieres descargar el archivo?", "El adjunto seleccionado no se puede visualizar en el navegador", "warning", "download('" + url + "')");
        }
    }

    function download(url) {
        const link = document.createElement("a");
        link.href = url;
        link.download = url.split("/")[url.split('/').length - 1];
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    }

    function deleteAdjunto(id, url, valida) {
        if (!valida) {
            notifyConfirm("Estas seguro que quieres eliminar el adjunto?", "El adjunto no se podra recuperar, este se borra de forma definitiva.", "error", "deleteAdjunto('" + id + "','" + url + "',true)");
        } else {
            $.ajax({
                url: "ajax.php?mode=deleteadjunto",
                type: "POST",
                data: {
                    id: id,
                    url: url
                },
                error: function(request, status, error) {
                    notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
                },
                beforeSend: function() {},
                success: function(datos) {
                    var respuesta = JSON.parse(datos);
                    if (respuesta["codigo"] == "1") {
                        verAdjuntos(_FOLIO);
                        getActividades();

                        notify(respuesta["alerta"], 1500, "success", "top-end");
                    } else {
                        notify(respuesta["alerta"], 1500, "error", "top-end");
                        console.log(respuesta["alerta"]);
                    }
                }
            });
        }
    }



    function statusActividad(folio, status, valida) {
        if (!valida) {
            if (status == 'F') {
                notifyConfirm("Confirmas que quieres finalizar el folio " + folio + "?", "", "success", "statusActividad('" + folio + "','" + status + "',true);");
            } else if (status = 'X') {
                notifyConfirm("Confirmas que quieres cancelar el folio " + folio + "?", "", "error", "statusActividad('" + folio + "','" + status + "',true);");
            } else {
                notify("Error! Estatus [" + status + " no valido.]", 1500, "error", "top-end");
            }
        } else {
            _FOLIO = '0';

            editRegister(folio, 'actividades', 'folio', 'c_estatus', status);
            if (status == 'F') {
                editRegister(folio, 'actividades', 'folio', 'fh_finaliza', 'now()');
                editRegister(folio, 'actividades', 'folio', 'id_usuario_finaliza', '<?php echo USER_ID; ?>');
            }
        }
    }

    function highlightTableRow($row, highlightClass, scrollToRow = true) {
        // Restaurar estilo de la fila previamente resaltada
        if ($(highlightClass).length > 0) {
            $(highlightClass).removeClass(highlightClass);
        }

        // Resaltar nueva fila
        $row.addClass(highlightClass)
            .attr('tabindex', '-1')
            .focus();


        // Scroll opcional
        if (scrollToRow) {
            $('html, body').animate({
                scrollTop: $row.offset().top - 100
            }, 300);
        }
    }

    function editRegister(id, tabla, nameKey, columna, txt) {
        $.ajax({
            url: "ajax.php?mode=editregister",
            type: "POST",
            data: {
                id: id,
                tabla: tabla,
                nameKey: nameKey,
                columna: columna,
                txt: txt
            },
            error: function(request, status, error) {
                notify('Error inesperado, consulte a soporte.' + request + status + error, 1500, "error", "top-end");
            },
            beforeSend: function() {},
            success: function(datos) {
                //console.log(datos);
                var respuesta = JSON.parse(datos);
                if (respuesta["codigo"] == "1") {
                    getActividades();

                    notify(respuesta["alerta"], 1500, "success", "top-end");
                } else {
                    notify(respuesta["alerta"], 1500, "error", "top-end");
                    console.log(respuesta["alerta"]);
                }
            }
        });
    }
</script>


<?php include(HTML . 'AdminPanel/masterPanel/foot.php'); ?>