<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  09-09-2015
*/
require_once('../app/libs/qrlib/qrlib.php');
require_once('../app/libs/phpmailer/class.phpmailer.php');

/**
 * Class ControlexcepcionesvistobuenoController
 * Clase para el control de solicitudes externas de verificación y/o aprobación de Solicitudes de Excepción.
 */
class ControlexcepcionesvistobuenoController extends ControllerBaseOut
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        $this->view->disable();
        $this->response->redirect('/login');
    }

    /**
     * Función para aplicar el visto bueno o rechazo de una solicitud de Excepción.
     * @param $aprobarRechazar
     * @param $idRelaboralSolicitanteCodificado
     * @param $idRelaboralDestinatarioPrincipalCodificado
     * @param $idRelaboralDestinatarioSecundarioCodificado
     * @param $idControlExcepcion
     */
    public function vistobuenoAction($idRelaboralSolicitanteCodificado = null, $idRelaboralDestinatarioPrincipalCodificado = null, $idRelaboralDestinatarioSecundarioCodificado = null, $idControlExcepcionCodificado = null, $estadoCodificado = null)
    {
        $idRelaboralSolicitante = base64_decode(str_pad(strtr($idRelaboralSolicitanteCodificado, '-_', '+/'), strlen($idRelaboralSolicitanteCodificado) % 4, '=', STR_PAD_RIGHT));
        $idRelaboralDestinatarioPrincipal = base64_decode(str_pad(strtr($idRelaboralDestinatarioPrincipalCodificado, '-_', '+/'), strlen($idRelaboralDestinatarioPrincipalCodificado) % 4, '=', STR_PAD_RIGHT));
        $idRelaboralDestinatarioSecundario = base64_decode(str_pad(strtr($idRelaboralDestinatarioSecundarioCodificado, '-_', '+/'), strlen($idRelaboralDestinatarioSecundarioCodificado) % 4, '=', STR_PAD_RIGHT));
        $idControlExcepcion = base64_decode(str_pad(strtr($idControlExcepcionCodificado, '-_', '+/'), strlen($idControlExcepcionCodificado) % 4, '=', STR_PAD_RIGHT));
        $estadoSolicitado = base64_decode(str_pad(strtr($estadoCodificado, '-_', '+/'), strlen($estadoCodificado) % 4, '=', STR_PAD_RIGHT));
        $accionSolicitada = "";
        switch ($estadoSolicitado) {
            case 3:
            case 5:
                $accionSolicitada = "VERIFICACI&Oacute;N SOLICITUD DE EXCEPCI&Oacute;N";
                break;
            case 6:
                $accionSolicitada = "APROBACI&Oacute;N SOLICITUD DE EXCEPCI&Oacute;N";
                break;
            case -1:
                $accionSolicitada = "RECHAZAR SOLICITUD DE VERIFICACI&Oacute;N";
                break;
            case -2:
                $accionSolicitada = "RECHAZAR SOLICITUD DE APROBACI&Oacute;N";
                break;
        }
        $this->view->disable();
        $hoy = date("Y-m-d H:i:s");
        $ahora = date("d-m-Y");
        $cuerpo = "";
        $opcionPlazo = 1;
        $cuerpo = '<html>';
        $cuerpo .= '<head>';
        $cuerpo .= '<title>Env&iacute;o de Solicitud</title>';
        $cuerpo .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>';
        $cuerpo .= '<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">';
        $cuerpo .= '<style type="text/css">';
        $cuerpo .= 'body{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;color:#394263;font-size:13px;background-color:#f2f2f2}#main-container,#page-container{min-width:320px}#page-container{width:100%;padding:0;margin:0 auto;overflow-x:hidden;-webkit-transition:background-color .2s ease-out;transition:background-color .2s ease-out}#page-container,#sidebar{background-color:#11203a}#sidebar{width:0;position:absolute;overflow:hidden}#main-container,#sidebar,.header-fixed-bottom header,.header-fixed-top header{-webkit-transition:all .2s ease-out;transition:all .2s ease-out}#page-content{padding:10px 5px 1px;min-height:1200px;background-color:#eaedf1}#page-content+footer{padding:9px 10px;font-size:11px;background-color:#fff;border-top:1px solid #dbe1e8}#page-container.header-fixed-top{padding:50px 0 0}#page-container.header-fixed-bottom{padding:0 0 50px}.sidebar-open #sidebar{width:200px}.sidebar-open #main-container{margin-left:220px}.header-fixed-bottom #sidebar,.header-fixed-top #sidebar{position:fixed;left:0;top:0;bottom:0}.header-fixed-bottom .sidebar-content,.header-fixed-top .sidebar-content{padding-bottom:50px}.sidebar-open header.navbar-fixed-bottom,.sidebar-open header.navbar-fixed-top{left:220px}header.navbar-default,header.navbar-inverse{padding:0;margin:0;min-width:320px;max-height:50px;border:0}header.navbar-fixed-bottom,header.navbar-fixed-top{max-height:51px}header.navbar-default.navbar-fixed-top{border-bottom:1px solid #eaedf1}header.navbar-default.navbar-fixed-bottom{border-top:1px solid #eaedf1}header.navbar-inverse.navbar-fixed-top{border-bottom:1px solid #394263}header.navbar-inverse.navbar-fixed-bottom{border-top:1px solid #394263}.nav.navbar-nav-custom{float:left;margin:0}.nav.navbar-nav-custom>li{min-height:50px;float:left}.nav.navbar-nav-custom>li>a{min-width:50px;padding:5px 7px;line-height:40px;text-align:center;color:#394263}.nav.navbar-nav-custom>li>a .fi,.nav.navbar-nav-custom>li>a .gi,.nav.navbar-nav-custom>li>a .hi,.nav.navbar-nav-custom>li>a .si{margin-top:-3px}.navbar-inverse .nav.navbar-nav-custom>li>a{color:#fff}.nav.navbar-nav-custom>li.open>a,.nav.navbar-nav-custom>li>a:focus,.nav.navbar-nav-custom>li>a:hover{background-color:#1bbae1;color:#fff}.nav.navbar-nav-custom>li>a>img{width:40px;height:40px;border:2px solid #fff;border-radius:20px;vertical-align:top}.navbar-form-custom{padding:0;width:100px;float:left;height:50px}.navbar-form-custom .form-control{padding:10px;margin:0;height:50px;font-size:15px;background:0 0;border:0;z-index:2000}.navbar-form-custom .form-control:focus,.navbar-form-custom .form-control:hover{background-color:#fff}.navbar-form-custom .form-control:focus{position:absolute;top:0;left:0;right:0;font-size:18px;padding:10px 20px}.navbar-inverse .navbar-form-custom .form-control{color:#fff}.navbar-inverse .navbar-form-custom .form-control:focus,.navbar-inverse .navbar-form-custom .form-control:hover{background:#000;color:#fff}.sidebar-content{width:220px;color:#fff}.sidebar-section{padding:10px}.sidebar-brand{height:50px;line-height:50px;padding:0 10px;margin:0;font-weight:300;font-size:18px;display:block;color:#fff;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-brand:focus,.sidebar-brand:hover{background-color:#1bbae1;color:#fff;text-decoration:none}.sidebar-brand i{font-size:14px;display:inline-block;width:18px;text-align:center;margin-right:10px;opacity:.5;filter:alpha(opacity=50)}.sidebar-user{padding-left:88px;background:url(../img/template/ie8_opacity_light_10.png) repeat;background:rgba(255,255,255,.1)}.sidebar-user-avatar{width:68px;height:68px;float:left;padding:2px;margin-left:-78px;border-radius:34px;background:url(../img/template/ie8_opacity_light_75.png) repeat;background:rgba(255,255,255,.75)}.sidebar-user-avatar img{width:64px;height:64px;border-radius:32px}.sidebar-user-name{font-size:17px;font-weight:300;margin-top:10px;line-height:26px}.sidebar-user-links a{color:#fff;opacity:.3;filter:alpha(opacity:30);margin-right:5px}.sidebar-user-links a:focus,.sidebar-user-links a:hover{color:#fff;text-decoration:none;opacity:1;filter:alpha(opacity:100)}.sidebar-user-links a>i{font-size:14px}.sidebar-themes{list-style:none;margin:0;padding-bottom:7px;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-themes li{float:left;margin:0 3px 3px 0}.sidebar-themes li a{display:block;width:17px;height:17px;border-radius:10px;border-width:2px;border-style:solid}.sidebar-themes li a:focus,.sidebar-themes li a:hover,.sidebar-themes li.active a{border-color:#fff!important}.sidebar-nav{list-style:none;margin:0;padding:10px 0 0}.sidebar-nav .sidebar-header:first-child{margin-top:0}.sidebar-nav a{display:block;color:#eaedf1;padding:0 10px;min-height:35px;line-height:35px}.sidebar-nav a.open,.sidebar-nav a:hover,.sidebar-nav li.active>a{color:#fff;text-decoration:none;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-nav a.active{padding-left:5px;border-left:5px solid #1bbae1;background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.sidebar-nav a>.sidebar-nav-icon{margin-right:10px}.sidebar-nav a>.sidebar-nav-indicator{float:right;line-height:inherit;margin-left:4px;-webkit-transition:all .15s ease-out;transition:all .15s ease-out}.sidebar-nav a>.sidebar-nav-icon,.sidebar-nav a>.sidebar-nav-indicator{display:inline-block;opacity:.5;filter:alpha(opacity:50);width:18px;font-size:14px;text-align:center}.sidebar-nav a.active,.sidebar-nav a.active>.sidebar-nav-icon,.sidebar-nav a.active>.sidebar-nav-indicator,.sidebar-nav a.open,.sidebar-nav a.open>.sidebar-nav-icon,.sidebar-nav a.open>.sidebar-nav-indicator,.sidebar-nav a:hover,.sidebar-nav a:hover>.sidebar-nav-icon,.sidebar-nav a:hover>.sidebar-nav-indicator,.sidebar-nav li.active>a,.sidebar-nav li.active>a>.sidebar-nav-icon,.sidebar-nav li.active>a>.sidebar-nav-indicator{opacity:1;filter:alpha(opacity:100)}.sidebar-nav a.active>.sidebar-nav-indicator,.sidebar-nav a.open>.sidebar-nav-indicator,.sidebar-nav li.active>a>.sidebar-nav-indicator{-webkit-transform:rotate(-90deg);transform:rotate(-90deg)}.sidebar-nav ul{list-style:none;padding:0;margin:0;display:none;background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.sidebar-nav li.active>ul{display:block}.sidebar-nav ul a{margin:0;font-size:12px;padding-left:15px;min-height:32px;line-height:32px}.sidebar-nav ul a.active,.sidebar-nav ul a.active:hover{border-left:5px solid #1bbae1;padding-left:10px}.sidebar-nav ul ul{background:url(../img/template/ie8_opacity_dark_40.png) repeat;background:rgba(0,0,0,.4)}.sidebar-nav ul ul a{padding-left:25px}.sidebar-nav ul ul a.active,.sidebar-nav ul ul a.active:hover{padding-left:20px}.sidebar-header{margin:10px 0 0;padding:10px;line-height:12px}.sidebar-header+.sidebar-section{padding-top:0;padding-bottom:0}.sidebar-header .sidebar-header-title{color:#fff;font-size:11px;text-transform:uppercase;opacity:.5;filter:alpha(opacity:50)}.sidebar-header-options{float:right;display:inline-block}.sidebar-header-options>a,.sidebar-nav .sidebar-header-options a{float:right;margin:0;padding:0;min-height:0;line-height:inherit;display:block;min-width:18px;text-align:center;color:#fff;opacity:.3;filter:alpha(opacity:30)}.sidebar-header-options a.active,.sidebar-header-options a:focus,.sidebar-header-options a:hover,.sidebar-nav .sidebar-header-options a.active,.sidebar-nav .sidebar-header-options a:focus,.sidebar-nav .sidebar-header-options a:hover{background:0 0;color:#fff;opacity:1;filter:alpha(opacity:100)}.sidebar-header-options a>i{font-size:14px}.content-header{background-color:#fff;border-top:1px solid #eaedf1;border-bottom:1px solid #dbe1e8}.content-header h1,.content-header h2{margin:0;font-size:26px;line-height:32px}.content-header small .content-header small{font-size:17px}.header-section h1 i{font-size:56px;float:right;color:#eaedf1;margin:0 0 0 10px;line-height:64px}.header-section{padding:30px 10px}.content-header,.content-top{margin:-10px -5px 10px}.content-top{background-color:#fff;border-bottom:1px solid #dbe1e8}.content-header-media{position:relative;height:248px;overflow:hidden;border-top-color:#222}.content-header-media .header-section{z-index:200;position:absolute;top:0;left:0;right:0;color:#fff;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}.content-header-media i,.content-header-media small{color:#ddd}.content-header-media>img{position:absolute;top:0;left:50%;width:2560px;height:248px;margin-left:-1280px}.content-header-media>.content-header-media-map{height:270px}.block{margin:0 0 10px;padding:20px 15px 1px;background-color:#fff;border:1px solid #dbe1e8}.block.full{padding:20px 15px}.block .block-content-full{margin:-20px -15px -1px}.block .block-content-mini-padding{padding:8px}.block.full .block-content-full{margin:-20px -15px}.block-title{margin:-20px -15px 20px;background-color:#f9fafc;border-bottom:1px solid #eaedf1}.block-title h1,.block-title h2,.block-title h3,.block-title h4,.block-title h5,.block-title h6{display:inline-block;font-size:16px;line-height:1.4;margin:0;padding:10px 16px 7px;font-weight:400}.block-title h1 small,.block-title h2 small,.block-title h3 small,.block-title h4 small,.block-title h5 small,.block-title h6 small{font-size:13px;color:#777;font-weight:400}.block-title h1,.block-title h2,.block-title h3{padding-left:15px;padding-right:15px}.block-options,.block-title .nav-tabs{min-height:40px;line-height:38px}.block-title .nav-tabs{padding:3px 1px 0;border-bottom:0}.block-title .nav-tabs>li>a{border-bottom:0}.block-title .nav-tabs{margin-bottom:-2px}.block-title .nav-tabs>li>a{margin-bottom:0}.block-title .nav-tabs>li>a:hover{background:0 0}.block-title .nav-tabs>li.active>a,.block-title .nav-tabs>li.active>a:focus,.block-title .nav-tabs>li.active>a:hover{border:1px solid #eaedf1;border-bottom-color:#fff;background-color:#fff}.block-title code{padding:2px 3px}.block-options{margin:0 6px;line-height:37px}.block-options .label{display:inline-block;padding:6px;vertical-align:middle;font-size:13px}.block-top{margin:-20px -15px 20px;border-bottom:1px dotted #dbe1e8}.block-section{margin-bottom:20px}.widget{background-color:#fff;margin-bottom:10px}.widget .widget-extra,.widget .widget-extra-full{position:relative;padding:15px}.widget .widget-extra{padding-top:1px;padding-bottom:1px}.widget .widget-content-light{color:#fff}.widget .widget-content-light small{color:#eee}.widget .widget-icon,.widget .widget-image{width:64px;height:64px}.widget .widget-icon{height:64px;display:inline-block;line-height:64px;text-align:center;font-size:28px;color:#fff;border-radius:32px}.widget .widget-icon .fi,.widget .widget-icon .gi,.widget .widget-icon .hi,.widget .widget-icon .si{margin-top:-3px}.widget .widget-options,.widget .widget-options-left{position:absolute;top:5px;opacity:.5;filter:alpha(opacity=50)}.widget .widget-options{right:5px}.widget .widget-options-left{left:5px}.widget .widget-options-left:hover,.widget .widget-options:hover{opacity:1;filter:alpha(opacity=100)}.widget-simple{padding:15px}.widget-simple:after,.widget-simple:before{content:" ";display:table}.widget-simple:after{clear:both}.widget-simple .widget-icon,.widget-simple .widget-image{margin:0 15px}.widget-simple .widget-icon.pull-left,.widget-simple .widget-image.pull-left{margin-left:0}.widget-simple .widget-icon.pull-right,.widget-simple .widget-image.pull-right{margin-right:0}.widget-simple .widget-content{font-size:18px;margin:12px 0}.widget-simple .widget-content small{display:block;margin-top:7px;font-size:13px;font-weight:400}.widget-advanced .widget-header{position:relative;padding:15px 15px 50px;height:150px;overflow:hidden}.widget-advanced .widget-background{position:absolute;top:0;left:0;height:150px}.widget-advanced .widget-background-map{height:180px;width:100%}.widget-advanced .widget-content-image{position:absolute;top:0;left:0;width:100%;padding:15px;margin:0;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}.widget-advanced .widget-main{position:relative;padding:50px 15px 15px}.widget-advanced .widget-image-container{position:absolute;display:inline-block;padding:5px;width:74px;height:74px;top:-36px;left:50%;margin-left:-36px;border-radius:36px;background-color:#fff}.widget-advanced .widget-header .widget-image-container{position:static;left:auto;top:auto;margin:0}.widget-advanced-alt .widget-header,.widget-advanced-alt .widget-main{padding:15px}.widget-advanced-alt .widget-header{height:auto;min-height:150px}.content-float .pull-left{margin:0 20px 20px 0}.content-float .pull-right{margin:0 0 20px 20px}#to-top{display:none;position:fixed;bottom:55px;left:5px;border-radius:3px;padding:0 12px;font-size:28px;text-align:center;color:#fff;background-color:#000;opacity:.1;filter:alpha(opacity=10)}#to-top:hover{color:#fff;background-color:#1bbae1;text-decoration:none;opacity:1;filter:alpha(opacity=100)}#login-background{width:100%;height:224px;overflow:hidden;position:relative}#login-background>img{position:absolute;width:2560px;height:400px;left:50%;margin-left:-1280px}#login-container{position:absolute;width:300px;top:10px;left:50%;margin-left:-150px;z-index:1000}#login-container .login-title{padding:20px 10px;background:#394263;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}#login-container .login-title h1{font-size:26px;color:#fff}#login-container .login-title small{font-size:16px;color:#ddd}#login-container>.block{border:0}#login-container .register-terms{line-height:30px;margin-right:10px;float:left}.calendar-events{list-style:none;margin:0;padding:0}.calendar-events li{color:#fff;margin-bottom:5px;padding:5px 10px;border-radius:3px;background-color:#555;opacity:.85;filter:alpha(opacity=85)}.calendar-events li:hover{cursor:move;opacity:1;filter:alpha(opacity=100)}.gallery a img,.gallery img,.gallery-image img,a[data-toggle=lightbox-image] img{max-width:100%}a.gallery-link,a[data-toggle=lightbox-image]{cursor:pointer;cursor:-webkit-zoom-in;cursor:-moz-zoom-in;cursor:zoom-in}.gallery a:hover img,.gallery-image:hover img,a[data-toggle=lightbox-image]:hover img{opacity:.75;filter:alpha(opacity=75)}.gallery-image{position:relative}.gallery-image-options{position:absolute;top:0;bottom:0;left:0;right:0;display:none;padding:10px}.gallery-image:hover .gallery-image-options{display:block}.gallery>.row>div{margin-bottom:15px}.gallery.gallery-widget>.row>div{margin-bottom:0;padding-top:7px;padding-bottom:7px}.pie-chart .pie-avatar{position:absolute;top:8px;left:8px}.chart{height:360px}.chart-tooltip,.mini-chart-tooltip{position:absolute;display:none;color:#fff;background-color:#000;padding:4px 10px}.chart-pie-label{font-size:12px;text-align:center;padding:8px 12px;color:#fff}.mini-chart-tooltip{left:0;top:0;visibility:hidden}.timeline{position:relative}.timeline-header{margin:0;font-size:18px;font-weight:600;padding:0 15px;min-height:60px;line-height:60px;background-color:#fff;border-bottom:2px solid #f0f0f0;z-index:500}.timeline-list{list-style:none;margin:0;padding:0}.timeline-list:after{position:absolute;display:block;width:2px;top:0;left:95px;bottom:0;content:"";background-color:#f0f0f0;z-index:1}.timeline-header+.timeline-list:after{top:60px}.timeline-list li{position:relative;margin:0;padding:10px 0 ; border-bottom: 2px solid #fff }.timeline-list.timeline-hover li:hover{ }.timeline-list .timeline-icon{position:absolute;left:80px;top:10px;width:30px;height:30px;line-height:28px;font-size:14px;text-align:center;background-color:#fff;border:1px solid #ddd;border-radius:15px;z-index:500}.timeline-list .active .timeline-icon{background-color:#1bbae1;border-color:#1bbae1;color:#fff}.timeline-list .timeline-time{float:left;width:70px;text-align:right}.timeline-list .timeline-content{margin-left:120px}.block-content-full .timeline-content{padding-right:20px}.media-feed{margin-bottom:0}.media-feed>.media{margin-top:0;padding:20px 20px 0;border-top:1px dotted #dbe1e8}.media-feed>.media:first-child{border-top:0}.media-feed.media-feed-hover>.media:hover{background-color:#f9f9f9}#error-container{padding:120px 20px;position:relative}#error-container .error-options{position:absolute;top:20px;left:20px}#error-container h1{font-size:96px;color:#fff;margin-bottom:40px}#error-container h2{color:#ccc;margin-bottom:40px;line-height:1.4}#error-container form{padding:20px;border-radius:3px;background:#fff;background:url(../img/template/ie8_opacity_light_10.png) repeat;background:rgba(255,255,255,.1)}#error-container .form-control{border-color:#fff}.table.table-pricing{background-color:#fff}.table-pricing td,.table-pricing th{text-align:center}.table-pricing th{font-size:24px!important}.table-pricing td{font-size:15px;padding-top:12px!important;padding-bottom:12px!important}.table-pricing .table-price{background-color:#f9f9f9}.table-pricing .table-price.table-featured,.table-pricing.table-featured .table-price{background-color:#252525}.table-pricing th.table-featured,.table-pricing.table-featured th{background-color:#1bbae1;border-bottom:2px solid #394263;color:#fff}.table-pricing td.table-featured,.table-pricing.table-featured td{background-color:#394263;color:#fff}.navbar.navbar-default{background-color:#f9fafc}.navbar.navbar-inverse{background-color:#4c5471}.navbar-fixed-bottom,.navbar-fixed-top{border-width:0}.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{font-family:"Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;font-weight:300}.h1 .small,.h1 small,.h2 .small,.h2 small,.h3 .small,.h3 small,.h4 .small,.h4 small,.h5 .small,.h5 small,.h6 .small,.h6 small,h1 .small,h1 small,h2 .small,h2 small,h3 .small,h3 small,h4 .small,h4 small,h5 .small,h5 small,h6 .small,h6 small{font-weight:300;color:#777}h1,h2,h3{margin-bottom:15px}.text-primary,.text-primary:hover,a,a:focus,a:hover{color:#1bbae1}.text-danger,.text-danger:hover,a.text-danger,a.text-danger:focus,a.text-danger:hover{color:#e74c3c}.text-warning,.text-warning:hover,a.text-warning,a.text-warning:focus,a.text-warning:hover{color:#e67e22}.text-success,.text-success:hover,a.text-success,a.text-success:focus,a.text-success:hover{color:#27ae60}.text-info,.text-info:hover,a.text-info,a.text-info:focus,a.text-info:hover{color:#3498db}.text-muted,.text-muted:hover,a.text-muted,a.text-muted:focus,a.text-muted:hover{color:#999}b,strong{font-weight:600}ol,ul{padding-left:30px}.list-li-push li{margin-bottom:10px}p{line-height:1.6}article p{font-size:16px;line-height:1.8}.well{background-color:#f9f9f9;border:1px solid #eee}.page-header{border-bottom-width:1px;border-bottom-color:#ddd;margin:30px 0 20px}.sub-header{margin:10px 0 20px;padding:10px 0;border-bottom:1px dotted #ddd}blockquote{border-left-width:3px;margin:20px 0;padding:30px 60px 30px 20px;position:relative;width:100%;border-color:#eaedf1}blockquote:before{display:block;content:"\201C";font-family:serif;font-size:96px;position:absolute;right:10px;top:-30px;color:#eaedf1}blockquote.pull-right:before{left:10px;right:auto}label{font-weight:600}fieldset legend{font-size:16px;padding:30px 0 10px;border-bottom:2px solid #eaedf1}input[type=file]{padding-top:7px}input[type=email].form-control,input[type=password].form-control,input[type=text].form-control,textarea.form-control{-webkit-appearance:none}.form-control{font-size:13px;padding:6px 8px;max-width:100%;margin:1px 0;color:#394263;border-color:#dbe1e8}.form-control-borderless,.form-control-borderless .form-control,.form-control-borderless .input-group-addon,.form-control-borderless:focus{border:transparent!important}.input-group{margin-top:1px;margin-bottom:1px}.input-group .form-control{margin-top:0}.form-control:focus{border-color:#1bbae1}.help-block{color:#777;font-weight:400}.input-group-addon{min-width:45px;text-align:center;background-color:#fff;border-color:#dbe1e8}.form-horizontal .control-label{margin-bottom:5px}.form-bordered{margin:-15px -15px -1px}.modal-body .form-bordered{margin-bottom:-20px}.form-bordered fieldset legend{margin:0;padding-left:20px;padding-right:20px}.form-bordered .form-group{margin:0;border:0;padding:15px;border-bottom:1px dashed #eaedf1}.form-bordered .form-group.form-actions{background-color:#f9fafc;border-bottom:0;border-bottom-left-radius:4px;border-bottom-right-radius:4px}.form-horizontal.form-bordered .form-group{padding-left:0;padding-right:0}.form-bordered .help-block{margin-bottom:0}.has-success .checkbox,.has-success .checkbox-inline,.has-success .control-label,.has-success .help-block,.has-success .input-group-addon,.has-success .radio,.has-success .radio-inline{color:#27ae60}.has-success .form-control,.has-success .input-group-addon{border-color:#27ae60;background-color:#fff}.has-success .form-control:focus{border-color:#166638}.has-warning .checkbox,.has-warning .checkbox-inline,.has-warning .control-label,.has-warning .help-block,.has-warning .input-group-addon,.has-warning .radio,.has-warning .radio-inline{color:#e67e22}.has-warning .form-control,.has-warning .input-group-addon{border-color:#e67e22;background-color:#fff}.has-warning .form-control:focus{border-color:#b3621b}.has-error .checkbox,.has-error .checkbox-inline,.has-error .control-label,.has-error .help-block,.has-error .input-group-addon,.has-error .radio,.has-error .radio-inline{color:#e74c3c}.has-error .form-control,.has-error .input-group-addon{border-color:#e74c3c;background-color:#fff}.has-error .form-control:focus{border-color:#c0392b}.wizard-steps{border-bottom:1px solid #eaedf1;margin-bottom:20px}.form-bordered .wizard-steps{margin-bottom:0}.wizard-steps .row{margin:0}.wizard-steps .row div{padding:15px 0;font-size:15px;text-align:center}.form-bordered .wizard-steps .row div{padding-top:10px}.wizard-steps span{display:inline-block;width:100px;height:100px;line-height:100px;border:1px solid #1bbae1;border-radius:50px}.wizard-steps div.active span,.wizard-steps div.done span{background-color:#1bbae1;color:#fff}.wizard-steps div.done span{opacity:.25;filter:alpha(opacity=25)}.wizard-steps div.active span{opacity:1;filter:alpha(opacity=100)}.switch{margin:1px 0;position:relative;cursor:pointer}.switch input{position:absolute;opacity:0;filter:alpha(opacity=0)}.switch span{position:relative;display:inline-block;width:54px;height:28px;border-radius:28px;background-color:#f9f9f9;border:1px solid #ddd;-webkit-transition:background-color .35s;transition:background-color .35s}.switch span:after{content:"";position:absolute;left:7px;top:7px;bottom:7px;width:12px;background-color:#fff;border:1px solid #ddd;border-radius:24px;-webkit-box-shadow:1px 0 3px rgba(0,0,0,.05);box-shadow:1px 0 3px rgba(0,0,0,.05);-webkit-transition:all .15s ease-out;transition:all .15s ease-out}.switch input:checked+span:after{left:26px;width:24px;top:1px;bottom:1px;border:0;-webkit-box-shadow:-2px 0 3px rgba(0,0,0,.1);box-shadow:-2px 0 3px rgba(0,0,0,.1)}.switch input:checked+span{background-color:#eee}.switch-default span{border-color:#dbe1e8}.switch-default input:checked+span{background-color:#dbe1e8}.switch-primary span{border-color:#1bbae1}.switch-primary input:checked+span{background-color:#1bbae1}.switch-info span{border-color:#7abce7}.switch-info input:checked+span{background-color:#7abce7}.switch-success span{border-color:#aad178}.switch-success input:checked+span{background-color:#aad178}.switch-warning span{border-color:#f7be64}.switch-warning input:checked+span{background-color:#f7be64}.switch-danger span{border-color:#ef8a80}.switch-danger input:checked+span{background-color:#ef8a80}.table.table-vcenter td,.table.table-vcenter th{vertical-align:middle}.table-options{padding:6px 0}.table thead>tr>th{font-size:18px;font-weight:600}.table thead>tr>th>small{font-weight:400;font-size:75%}.table tfoot>tr>td,.table tfoot>tr>th,.table thead>tr>td,.table thead>tr>th{padding-top:14px;padding-bottom:14px}.table tfoot>tr>td,.table tfoot>tr>th{background-color:#f9fafc}.table-borderless tbody>tr>td,.table-borderless tbody>tr>th{border-top-width:0}.table tbody+tbody,.table tbody>tr>td,.table tbody>tr>th,.table tfoot>tr>td,.table tfoot>tr>th,.table thead>tr>td,.table thead>tr>th,.table-bordered,.table-bordered>tbody>tr>td,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>td,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{border-color:#eaedf1}.table-hover>tbody>tr:hover>td,.table-hover>tbody>tr:hover>th{background-color:#eaedf1}.list-group-item{border-color:#eaedf1}a.list-group-item.active,a.list-group-item.active:focus,a.list-group-item.active:hover{background-color:#1bbae1;border-color:#1bbae1}a.list-group-item.active .list-group-item-text,a.list-group-item.active:focus .list-group-item-text,a.list-group-item.active:hover .list-group-item-text{color:#fff}a.list-group-item:focus,a.list-group-item:hover{background-color:#f9fafc}a.list-group-item.active>.badge{background:url(../img/template/ie8_opacity_dark_40.png) repeat;background:rgba(0,0,0,.4);color:#fff}.dropdown-menu>.active>a,.dropdown-menu>.active>a:focus,.dropdown-menu>.active>a:hover,.dropdown-menu>li>a:focus,.dropdown-menu>li>a:hover,.nav .open>a,.nav .open>a:focus,.nav .open>a:hover,.nav-pills>li.active>a,.nav-pills>li.active>a:focus,.nav-pills>li.active>a:hover{color:#fff;background-color:#1bbae1}.nav>li i{font-size:14px}.nav-pills>.active>a>.badge{color:#1bbae1}.nav-stacked>li>a{margin:4px 0 0}.nav .caret,.nav a:focus .caret,.nav a:hover .caret{border-top-color:#1bbae1;border-bottom-color:#1bbae1}.nav>li>a:focus,.nav>li>a:hover{background-color:#f9fafc}.nav-tabs{border-bottom-color:#eaedf1}.nav-tabs>li{margin-bottom:0}.nav-tabs>li>a{padding-left:7px;padding-right:7px;margin-bottom:-1px}.nav-tabs>li>a:hover{border-color:#eaedf1}.nav-tabs>li.active>a,.nav-tabs>li.active>a:focus,.nav-tabs>li.active>a:hover{color:#394263;border-color:#eaedf1;border-bottom-color:transparent}.nav-pills>li.active>a>.badge{background:url(../img/template/ie8_opacity_dark_20.png) repeat;background:rgba(0,0,0,.2);color:#fff}.dropdown-menu{padding:0;font-size:13px;border-color:#dbe1e8;-webkit-box-shadow:0 3px 6px rgba(0,0,0,.1);box-shadow:0 3px 6px rgba(0,0,0,.1)}.dropdown-menu>li>a{padding:6px 10px}.dropdown-menu>li:first-child>a{border-top-left-radius:3px;border-top-right-radius:3px}.dropdown-menu>li:last-child>a{border-bottom-left-radius:3px;border-bottom-right-radius:3px}.dropdown-menu i{opacity:.2;filter:alpha(opacity=20);line-height:17px}.dropdown-menu a:hover i{opacity:.5;filter:alpha(opacity=50)}.dropdown-menu .divider{margin:2px 0;padding:0!important;background-color:#f0f0f0}li.dropdown-header{padding:5px 10px;color:#394263;background-color:#f9fafc;border-top:1px solid #eaedf1;border-bottom:1px solid #eaedf1}.dropdown-menu li:first-child.dropdown-header{border-top:0;border-top-left-radius:3px;border-top-right-radius:3px}.dropdown-menu.dropdown-custom{min-width:200px}.dropdown-menu.dropdown-custom>li{padding:8px 10px;font-size:12px}.dropdown-menu.dropdown-custom>li>a{border-radius:3px}.pagination>li>a,.pagination>li>span{color:#1bbae1;margin-left:5px;margin-right:5px;border:0!important;border-radius:25px!important}.pagination>.active>a,.pagination>.active>a:focus,.pagination>.active>a:hover,.pagination>.active>span,.pagination>.active>span:focus,.pagination>.active>span:hover{background-color:#1bbae1}.pager>li>a,.pager>li>span{border-color:#eaedf1}.pager>li>a:hover,.pagination>li>a:hover{background-color:#1bbae1;border-color:#1bbae1;color:#fff}.pager>li.disabled>a:hover{border-color:#eaedf1}.popover-title{background:0 0;border:0;font-size:17px;font-weight:600}.tooltip{z-index:1051}.tooltip.in{opacity:1;filter:alpha(opacity=100)}.tooltip-inner{padding:4px 6px;background-color:#000;color:#fff}.tooltip.top .tooltip-arrow,.tooltip.top-left .tooltip-arrow,.tooltip.top-right .tooltip-arrow{border-top-color:#000}.tooltip.right .tooltip-arrow{border-right-color:#000}.tooltip.left .tooltip-arrow{border-left-color:#000}.tooltip.bottom .tooltip-arrow,.tooltip.bottom-left .tooltip-arrow,.tooltip.bottom-right .tooltip-arrow{border-bottom-color:#000}.breadcrumb{background-color:#fff}.breadcrumb i{font-size:14px}.breadcrumb-top{margin:-10px -5px 10px;padding:7px 10px;border-top:1px solid #eaedf1;border-bottom:1px solid #dbe1e8;font-size:12px}.breadcrumb-top+.content-header,.content-header+.breadcrumb-top{margin-top:-11px}.breadcrumb>li+li:before{content:"\203a"}.progress,.progress-bar{height:20px;line-height:20px}.progress-bar-danger{background-color:#e74c3c}.progress-bar-warning{background-color:#f39c12}.progress-bar-success{background-color:#2ecc71}.progress-bar-info{background-color:#3498db}.modal-content{border-radius:3px}.modal-header{padding:15px 15px 14px;border-bottom:1px solid #eee;border-top-left-radius:4px;border-top-right-radius:4px}.modal-title{font-weight:300}.modal-body{padding:20px 15px}.modal-body .nav-tabs{margin:0 -15px 15px;padding:0 5px!important}.modal-footer{margin-top:0;padding:14px 15px 15px;border-top:1px solid #eee;background-color:#f9f9f9;border-bottom-left-radius:4px;border-bottom-right-radius:4px}.btn{margin:1px 0;background-color:#fff}.btn .fi,.btn .gi,.btn .hi,.btn .si{line-height:1}.btn.disabled,.btn[disabled],fieldset[disabled] .btn{opacity:.4;filter:alpha(opacity=40)}.block-options .btn,.input-group .btn,.modal-content .btn{margin-top:0;margin-bottom:0}.btn-default{background-color:#f1f3f6;border-color:#dbe1e8;color:#394263}.btn-default.btn-alt{background-color:#fff}.btn-default:hover{background-color:#eaedf1;border-color:#c2c8cf}.btn-default.active,.btn-default.disabled,.btn-default.disabled.active,.btn-default.disabled:active,.btn-default.disabled:focus,.btn-default.disabled:hover,.btn-default:active,.btn-default:focus,.btn-default[disabled].active,.btn-default[disabled]:active,.btn-default[disabled]:focus,.btn-default[disabled]:hover,.open .btn-default.dropdown-toggle,fieldset[disabled] .btn-default.active,fieldset[disabled] .btn-default:active,fieldset[disabled] .btn-default:focus,fieldset[disabled] .btn-default:hover{background-color:#eaedf1;border-color:#eaedf1}.btn-primary{background-color:#1BBAE1;border-color:#1C75CB;color:#fff}.btn-primary.btn-alt{background-color:#fff;color:#1C6FD0}.btn-primary:hover{background-color:#11203a;border-color:#1C6FD0;color:#fff}.btn-primary.active,.btn-primary.disabled,.btn-primary.disabled.active,.btn-primary.disabled:active,.btn-primary.disabled:focus,.btn-primary.disabled:hover,.btn-primary:active,.btn-primary:focus,.btn-primary[disabled].active,.btn-primary[disabled]:active,.btn-primary[disabled]:focus,.btn-primary[disabled]:hover,.open .btn-primary.dropdown-toggle,fieldset[disabled] .btn-primary.active,fieldset[disabled] .btn-primary:active,fieldset[disabled] .btn-primary:focus,fieldset[disabled] .btn-primary:hover{background-color:#11203A;border-color:#1bbae1;color:#fff}.btn-danger{background-color:#ef8a80;border-color:#e74c3c;color:#fff}.btn-danger.btn-alt{background-color:#fff;color:#e74c3c}.btn-danger:hover{background-color:#e74c3c;border-color:#9c3428;color:#fff}.btn-danger.active,.btn-danger.disabled,.btn-danger.disabled.active,.btn-danger.disabled:active,.btn-danger.disabled:focus,.btn-danger.disabled:hover,.btn-danger:active,.btn-danger:focus,.btn-danger[disabled].active,.btn-danger[disabled]:active,.btn-danger[disabled]:focus,.btn-danger[disabled]:hover,.open .btn-danger.dropdown-toggle,fieldset[disabled] .btn-danger.active,fieldset[disabled] .btn-danger:active,fieldset[disabled] .btn-danger:focus,fieldset[disabled] .btn-danger:hover{background-color:#e74c3c;border-color:#e74c3c;color:#fff}.btn-warning{background-color:#f7be64;border-color:#f39c12;color:#fff}.btn-warning.btn-alt{background-color:#fff;color:#f39c12}.btn-warning:hover{background-color:#f39c12;border-color:#b3730c;color:#fff}.btn-warning.active,.btn-warning.disabled,.btn-warning.disabled.active,.btn-warning.disabled:active,.btn-warning.disabled:focus,.btn-warning.disabled:hover,.btn-warning:active,.btn-warning:focus,.btn-warning[disabled].active,.btn-warning[disabled]:active,.btn-warning[disabled]:focus,.btn-warning[disabled]:hover,.open .btn-warning.dropdown-toggle,fieldset[disabled] .btn-warning.active,fieldset[disabled] .btn-warning:active,fieldset[disabled] .btn-warning:focus,fieldset[disabled] .btn-warning:hover{background-color:#f39c12;border-color:#f39c12;color:#fff}.btn-success{background-color:#aad178;border-color:#7db831;color:#fff}.btn-success.btn-alt{background-color:#fff;color:#7db831}.btn-success:hover{background-color:#7db831;border-color:#578022;color:#fff}.btn-success.active,.btn-success.disabled,.btn-success.disabled.active,.btn-success.disabled:active,.btn-success.disabled:focus,.btn-success.disabled:hover,.btn-success:active,.btn-success:focus,.btn-success[disabled].active,.btn-success[disabled]:active,.btn-success[disabled]:focus,.btn-success[disabled]:hover,.open .btn-success.dropdown-toggle,fieldset[disabled] .btn-success.active,fieldset[disabled] .btn-success:active,fieldset[disabled] .btn-success:focus,fieldset[disabled] .btn-success:hover{background-color:#7db831;border-color:#7db831;color:#fff}.btn-info{background-color:#7abce7;border-color:#3498db;color:#fff}.btn-info.btn-alt{background-color:#fff;color:#3498db}.btn-info:hover{background-color:#3498db;border-color:#2875a8;color:#fff}.btn-info.active,.btn-info.disabled,.btn-info.disabled.active,.btn-info.disabled:active,.btn-info.disabled:focus,.btn-info.disabled:hover,.btn-info:active,.btn-info:focus,.btn-info[disabled].active,.btn-info[disabled]:active,.btn-info[disabled]:focus,.btn-info[disabled]:hover,.open .btn-info.dropdown-toggle,fieldset[disabled] .btn-info.active,fieldset[disabled] .btn-info:active,fieldset[disabled] .btn-info:focus,fieldset[disabled] .btn-info:hover{background-color:#3498db;border-color:#3498db;color:#fff}.btn-link,.btn-link.btn-icon:focus,.btn-link.btn-icon:hover,.btn-link:focus,.btn-link:hover{color:#1bbae1}.btn-link.btn-icon{color:#999}.btn-link.btn-icon:focus,.btn-link.btn-icon:hover{text-decoration:none}.block-options .btn{border-radius:15px;padding-right:8px;padding-left:8px;min-width:30px;text-align:center}.panel{margin-bottom:20px}.panel-heading{padding:15px}.panel-title{font-size:14px}.panel-default>.panel-heading{background-color:#f9f9f9}.panel-group{margin-bottom:20px}pre{background:#151515;overflow:scroll}code{border:1px solid #fad4df;margin:1px 0;display:inline-block}.btn code{display:inline;margin:0}.alert{border-top-width:0;border-right-width:2px;border-bottom-width:0;border-left-width:2px}.alert-danger{color:#e74c3c;background-color:#ffd1cc;border-color:#ffb8b0}.alert-danger .alert-link{color:#e74c3c}.alert-warning{color:#e67e22;background-color:#ffe4cc;border-color:#ffd6b2}.alert-warning .alert-link{color:#e67e22}.alert-success{color:#27ae60;background-color:#daf2e4;border-color:#b8e5cb}.alert-success .alert-link{color:#27ae60}.alert-info{color:#3498db;background-color:#dae8f2;border-color:#b8d2e5}.alert-info .alert-link{color:#3498db}.alert-dismissable .close{top:-5px;right:-25px}.close{text-shadow:none}.alert.alert-alt{margin:0 0 2px;padding:5px;font-size:12px;border-width:0;border-left-width:2px}.alert.alert-alt small{opacity:.75;filter:alpha(opacity=75)}.alert-alt.alert-dismissable .close{right:0}.alert-alt.alert-dismissable .close:hover{color:#fff}.alert-danger.alert-alt{border-color:#e74c3c}.alert-warning.alert-alt{border-color:#e67e22}.alert-success.alert-alt{border-color:#27ae60}.alert-info.alert-alt{border-color:#3498db}.sidebar-content .alert.alert-alt{margin-left:-10px;padding-left:15px;background:0 0;color:#fff}.badge,.label{font-weight:400;font-size:90%}.label{padding:1px 4px}.badge{background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3);padding:3px 6px}.label-danger{background-color:#e74c3c}.label-danger[href]:focus,.label-danger[href]:hover{background-color:#ff5542}.label-warning{background-color:#e67e22}.label-warning[href]:focus,.label-warning[href]:hover{background-color:#ff8b26}.label-success{background-color:#27ae60}.label-success[href]:focus,.label-success[href]:hover{background-color:#2cc76c}.label-info{background-color:#2980b9}.label-info[href]:focus,.label-info[href]:hover{background-color:#2f92d4}.label-primary{background-color:#1bbae1}.label-primary[href]:focus,.label-primary[href]:hover{background-color:#5ac5e0}.label-default{background-color:#999}.label-default[href]:focus,.label-default[href]:hover{background-color:#777}.carousel-control.left,.carousel-control.left.no-hover:hover,.carousel-control.right,.carousel-control.right.no-hover:hover{background:0 0}.carousel-control.left:hover,.carousel-control.right:hover{background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.carousel-control span{position:absolute;top:50%;left:50%;z-index:5;display:inline-block}.carousel-control i{width:20px;height:20px;margin-top:-10px;margin-left:-10px}.alert,.carousel,.table,p{margin-bottom:20px}.btn.active,.form-control,.form-control:focus,.has-error .form-control:focus,.has-success .form-control:focus,.has-warning .form-control:focus,.navbar-form-custom .form-control:focus,.navbar-form-custom .form-control:hover,.open .btn.dropdown-toggle,.panel,.popover,.progress,.progress-bar{-webkit-box-shadow:none;box-shadow:none}.alert.alert-alt,.breadcrumb,.dropdown-menu,.navbar,.navbar-form-custom .form-control,.tooltip-inner{border-radius:0}.push-bit{margin-bottom:10px!important}.push{margin-bottom:15px!important}.push-top-bottom{margin-top:40px;margin-bottom:40px}.lt-ie9 .hidden-lt-ie9{display:none!important}.display-none{display:none}.remove-margin{margin:0!important}.remove-padding{padding:0!important}.remove-radius{border-radius:0!important}.remove-box-shadow{-webkit-box-shadow:none!important;box-shadow:none!important}.remove-transition{-moz-transition:none!important;-webkit-transition:none!important;transition:none!important}:focus{outline:0!important}.style-alt #page-content{background-color:#fff}.style-alt .block{border-color:#dbe1e8}.style-alt .block.block-alt-noborder{border-color:transparent}.style-alt .block-title{background-color:#dbe1e8;border-bottom-color:#dbe1e8}.style-alt #page-content+footer,.style-alt .breadcrumb-top+.content-header,.style-alt .content-header+.breadcrumb-top{background-color:#f9fafc}.style-alt .breadcrumb-top,.style-alt .content-header{border-bottom-color:#eaedf1}.style-alt #page-content+footer{border-top-color:#eaedf1}.style-alt .widget{background-color:#f6f6f6}.test-circle{display:inline-block;width:100px;height:100px;line-height:100px;font-size:18px;font-weight:600;text-align:center;border-radius:50px;background-color:#eee;border:2px solid #ccc;color:#fff;margin-bottom:15px}.themed-color{color:#1bbae1}.themed-border{border-color:#1bbae1}.themed-background{background-color:#1bbae1}.themed-color-dark{color:#394263}.themed-border-dark{border-color:#394263}.themed-background-dark{background-color:#394263}@media screen and (min-width:768px){#login-background{height:400px}#login-background>img{top:0}#login-container{width:480px;top:186px;margin-left:-240px}#main-container{min-width:768px}#page-content{padding:10px 10px 1px}#page-content+footer,.block,.block.full,.breadcrumb-top,.header-section,.modal-body{padding-left:20px;padding-right:20px}.block .block-content-full{margin:-20px -20px -1px}.block.full .block-content-full{margin:-20px}.breadcrumb-top,.content-header,.content-top{margin:-20px -20px 20px}.breadcrumb-top+.content-header,.content-header+.breadcrumb-top{margin-top:-21px}.block,.widget{margin-bottom:20px}.block-title,.block-top,.form-bordered{margin-left:-20px;margin-right:-20px}.form-bordered .form-group{padding-left:20px;padding-right:20px}.form-horizontal.form-bordered .form-group{padding-left:5px;padding-right:5px}.nav-tabs>li>a{padding-left:15px;padding-right:15px;margin-left:3px;margin-right:3px}}@media (min-width:992px){#sidebar{-webkit-transition:opacity .5s linear,background-color .2s ease-out;transition:opacity .5s linear,background-color .2s ease-out}#main-container,.header-fixed-bottom header,.header-fixed-top header{-webkit-transition:none;transition:none}#sidebar{width:65px!important;opacity:.2;filter:alpha(opacity=20)}#main-container{margin-left:65px!important}.sidebar-brand i{display:none}#sidebar:hover,.sidebar-full #sidebar{width:220px!important;opacity:1;filter:alpha(opacity=100)}#sidebar:hover .sidebar-brand i,.sidebar-full #sidebar .sidebar-brand i{display:inline-block}#sidebar:hover+#main-container,.sidebar-full #main-container{margin-left:220px!important}.sidebar-open header.navbar-fixed-bottom,.sidebar-open header.navbar-fixed-top,header.navbar-fixed-bottom,header.navbar-fixed-top{left:65px}#sidebar:hover+#main-container header.navbar-fixed-bottom,#sidebar:hover+#main-container header.navbar-fixed-top,.sidebar-full header.navbar-fixed-bottom,.sidebar-full header.navbar-fixed-top{left:220px}}@media (min-width:1200px){.header-fixed-bottom .sidebar-content,.header-fixed-top .sidebar-content{padding-bottom:0}article p{font-size:19px;line-height:1.9}}';
        $cuerpo .= '.timeline-icon2{position:position;width:30px;height:30px;line-height:28px;font-size:14px;text-align:center;background-color:#B4FF30;border:1px solid #B4FF30;border-radius:15px;z-index:500}';
        $cuerpo .= '<style type="text/css">';
        //$cuerpo .= '<!--';
        $cuerpo .= '#aCerrarVentana{';
        $cuerpo .= 'color: #FFFFFF;';
        $cuerpo .= 'border: 2px #ff0a03 solid;';
        $cuerpo .= 'padding: 5px 20px 5px 20px;';
        $cuerpo .= 'background-color: #ff572b;';
        $cuerpo .= 'font-family: Comic Sans MS, Calibri, Arial;';
        $cuerpo .= 'font-size: 12px;';
        $cuerpo .= 'font-weight: bold;';
        $cuerpo .= 'text-decoration: none;';
        $cuerpo .= 'background-repeat: no-repeat;';
        $cuerpo .= 'border-radius: 15px;';
        $cuerpo .= '} ';
        $cuerpo .= '#divCabeceraMensaje {';
        $cuerpo .= 'position:relative;';
        $cuerpo .= '} ';
        $cuerpo .= '#divPieMensaje {';
        $cuerpo .= 'position:relative;';
        $cuerpo .= '} ';
        //$cuerpo .= '-->';
        $cuerpo .= '</style>';
        $cuerpo .= '</head>';
        $cuerpo .= '<body>';
        if ($idRelaboralSolicitante > 0 && $idRelaboralDestinatarioPrincipal > 0 && $idControlExcepcion > 0) {
            $nombreSolicitante = "";
            $cargoSolicitante = "";
            $departamentoSolicitante = "";
            $gerenciaSolicitante = "";
            $fechaIni = "";
            $fechaFin = "";
            $horaIni = "";
            $horaFin = "";
            $objCEx = new Fcontrolexcepciones();
            $controlexcepcion = $objCEx->getOne($idControlExcepcion);
            $objRel = new Frelaborales();
            $relaboralSolicitante = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralSolicitante);
            //$idRelaboralSolicitante=$controlexcepcion->relaboral_id;
            //$relaboralSolicitante = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($controlexcepcion->relaboral_id);
            $relaboralDestinatarioPrincipal = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralDestinatarioPrincipal);
            $contactoSolicitante = Personascontactos::findFirst(array("persona_id='" . $relaboralSolicitante->id_persona . "'"));
            $contactoDestinatarioPrincipal = Personascontactos::findFirst(array("persona_id='" . $relaboralDestinatarioPrincipal->id_persona . "'"));
            $idUsuario = 0;

            if (is_object($relaboralSolicitante)) {
                $nombreSolicitante = $relaboralSolicitante->nombres;
                $cargoSolicitante = utf8_decode($relaboralSolicitante->cargo);
                $departamentoSolicitante = utf8_decode($relaboralSolicitante->departamento_administrativo);
                $gerenciaSolicitante = utf8_decode($relaboralSolicitante->gerencia_administrativa);
            }
            /**
             * Sólo debiera hacerse la actualización si el registro se encuentra en estado SOLICITADO, en caso contrario
             * no se debe hacer nada.
             */
            if (is_object($controlexcepcion)
                && ($controlexcepcion->controlexcepcion_estado == 3
                    || $controlexcepcion->controlexcepcion_estado == 4
                    /**
                     * En caso de haberse presentado un error técnico en el envío, debe admitirse registros de este tipo
                     */
                    || $controlexcepcion->controlexcepcion_estado == -3
                    || $controlexcepcion->controlexcepcion_estado == -4
                )
                && is_object($relaboralDestinatarioPrincipal)
                && $relaboralDestinatarioPrincipal->estado > 0
                && $relaboralDestinatarioPrincipal->ci != ''
            ) {
                $fechaInicio = $controlexcepcion->fecha_ini != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_ini)) : "";

                /*$plazo = $objCEx->verificaPlazoValidezSolicitud($idRelaboralSolicitante, $fechaInicio, $opcionPlazo,1);*/
                /**
                 * Se modifica para que en el caso de las verificaciones y aprobaciones se considere otro plazo considerando desde la fecha del permiso.
                 */
                $plazo = $objCEx->verificaPlazoValidezSolicitud($idRelaboralDestinatarioPrincipal, $fechaInicio, $opcionPlazo, 1);
                if ($fechaInicio != '' && $plazo >= 0) {
                    $usuarioDestinatarioPrincipal = Usuarios::findFirst(array("persona_id='" . $relaboralDestinatarioPrincipal->id_persona . "'"));
                    if (is_object($usuarioDestinatarioPrincipal)) {
                        /**
                         * Si el usuario existe
                         */
                        $idUsuario = $usuarioDestinatarioPrincipal->id;

                    } else {
                        if (is_object($contactoDestinatarioPrincipal) && $contactoDestinatarioPrincipal->e_mail_inst != '' && $contactoDestinatarioPrincipal->e_mail_inst != null) {
                            /**
                             * Si el usuario no existe se crea un usuario sin acceso al módulo de administradores, sólo al de consultas por ello su nivel acá será 0
                             */
                            $objPrmTag = Parametros::findFirst("parametro LIKE 'ACCESS_KEY' AND nivel LIKE 'A' AND estado>0 AND baja_logica = 1");
                            $tag = $objPrmTag->valor_1;
                            $username = str_replace("@viasbolivia.gob.bo", "", $contactoDestinatarioPrincipal->e_mail_inst);
                            $password = hash_hmac('sha256', trim($relaboralDestinatarioPrincipal->ci), $tag);
                            $usuarioDestinatarioPrincipal = new Usuarios();
                            $usuarioDestinatarioPrincipal->persona_id = $relaboralDestinatarioPrincipal->id_persona;
                            $usuarioDestinatarioPrincipal->username = $username;
                            $usuarioDestinatarioPrincipal->password = $password;
                            $usuarioDestinatarioPrincipal->habilitado = 1;
                            $usuarioDestinatarioPrincipal->logins = 1000;
                            $usuarioDestinatarioPrincipal->nivel = 0;
                            $usuarioDestinatarioPrincipal->super = 1;
                            $usuarioDestinatarioPrincipal->fecha_creacion = $hoy;
                            $oku = $usuarioDestinatarioPrincipal->save();
                            if ($oku) {
                                /**
                                 * Si el usuario se ha creado sin problemas, se crea el registro de modalidad de nivel que permite el acceso
                                 * sólo al módulo de consultas del sistema.
                                 */
                                $modalidadnivel = new Modalidadnivel();
                                $modalidadnivel->usuario_id = $usuarioDestinatarioPrincipal->id;
                                $modalidadnivel->modalidad = 0;
                                $modalidadnivel->nivel = 11;
                                $modalidadnivel->estado = 1;
                                $modalidadnivel->baja_logica = 1;
                                $okmn = $modalidadnivel->save();
                                if ($okmn) {
                                    $idUsuario = $usuarioDestinatarioPrincipal->id;
                                }
                            }
                        }
                    }
                    $ok = false;
                    if (is_object($usuarioDestinatarioPrincipal)) {
                        $excepcion = $controlexcepcion->excepcion;
                        $justificacion = $controlexcepcion->justificacion;
                        $fechaIni = $controlexcepcion->fecha_ini != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_ini)) : "";
                        $fechaFin = $controlexcepcion->fecha_fin != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_fin)) : "";
                        $horaIni = $controlexcepcion->hora_ini;
                        $horaFin = $controlexcepcion->hora_fin;
                        $ce = Controlexcepciones::findFirstById($idControlExcepcion);
                        $accionRealizada = "";
                        /**
                         * Se verifica la existencia de algún registro en el historial, relacionada a alguna solicitud de operación en curso.
                         */
                        $objCtrlExcepMsjes = Controlexcepcionesmensajes::findFirst("controlexcepcion_id = " . $idControlExcepcion . " AND baja_logica = 1 AND controlexcepcion_estado > 0");
                        if (!is_object($objCtrlExcepMsjes)) {
                            $objCtrlExcepMsjes = new Controlexcepcionesmensajes();
                            $objCtrlExcepMsjes->controlexcepcion_id = $idControlExcepcion;
                            $parUser = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'USUARIO' AND estado=1 AND baja_logica=1"));
                            $userMail = '';
                            if (is_object($parUser)) {
                                $userMail = $parUser->valor_1;
                            }
                            $objCtrlExcepMsjes->user_mail = $userMail;
                            $objCtrlExcepMsjes->relaboral_sol_id = $idRelaboralSolicitante;
                            $objCtrlExcepMsjes->user_sol_mail = $contactoSolicitante->e_mail_inst;
                            $objCtrlExcepMsjes->relaboral_dest_id = $idRelaboralDestinatarioPrincipal;
                            $objCtrlExcepMsjes->user_dest_mail = $contactoDestinatarioPrincipal->e_mail_inst;

                            $objCtrlExcepMsjes->intentos = 1;
                            $objCtrlExcepMsjes->medio = 1;
                            $objCtrlExcepMsjes->estado = 1;
                            $objCtrlExcepMsjes->baja_logica = 1;
                            $objCtrlExcepMsjes->agrupador = 0;
                            $objCtrlExcepMsjes->user_reg_id = $idUsuario;
                            $objCtrlExcepMsjes->fecha_reg = $hoy;
                        }
                        switch ($estadoSolicitado) {
                            case 3:
                            case 5:
                                /**
                                 * Verificado
                                 */
                                $objCtrlExcepMsjes->operacion_solicitada = 3;
                                $accionRealizada = "SOLICITUD DE EXCEPCI&Oacute;N VERIFICADA";
                                $ce->estado = 5;
                                $ce->user_ver_id = $idUsuario;
                                $ce->fecha_ver = $hoy;
                                $ce->user_mod_id = $idUsuario;
                                $ce->fecha_mod = $hoy;
                                $ok = $ce->save();
                                if ($ok) {
                                    $objCtrlExcepMsjes->controlexcepcion_estado = 5;
                                    /**
                                     * En caso de haberse verificado la excepción, se envía un correo solicitando la APROBACIÓN
                                     */
                                    $operacion = 2;
                                    $mensajeAdicional = utf8_decode("Se envió de manera automática la Solicitud de Aprobación.");
                                    $this->enviomensajeexterno($idRelaboralSolicitante, $idRelaboralDestinatarioSecundario, 0, $idControlExcepcion, $mensajeAdicional, $operacion);
                                }
                                break;
                            case 6:
                                /**
                                 * Aprobar
                                 */
                                $objCtrlExcepMsjes->operacion_solicitada = 4;
                                $accionRealizada = "SOLICITUD DE EXCEPCI&Oacute;N APROBADA";
                                $ce->estado = 6;
                                $ce->user_apr_id = $idUsuario;
                                $ce->fecha_apr = $hoy;
                                $ce->user_mod_id = $idUsuario;
                                $ce->fecha_mod = $hoy;
                                $ok = $ce->save();
                                if ($ok) {
                                    $objCtrlExcepMsjes->controlexcepcion_estado = 6;
                                }
                                break;
                            case -1:
                                /**
                                 * Verificación rechazada
                                 */
                                $objCtrlExcepMsjes->operacion_solicitada = 3;
                                $accionRealizada = "SOLICITUD DE VERIFICACI&Oacute;N RECHAZADA";
                                $ce->estado = -1;
                                $ce->user_ver_id = $idUsuario;
                                $ce->fecha_ver = $hoy;
                                $ce->user_mod_id = $idUsuario;
                                $ce->fecha_mod = $hoy;
                                $ok = $ce->save();
                                if ($ok) {
                                    $objCtrlExcepMsjes->controlexcepcion_estado = -1;
                                }
                                break;
                            case -2:
                                /**
                                 * Aprobación rechazada
                                 */
                                $objCtrlExcepMsjes->operacion_solicitada = 4;
                                $ce->estado = -2;
                                $ce->user_apr_id = $idUsuario;
                                $ce->fecha_apr = $hoy;
                                $ce->user_mod_id = $idUsuario;
                                $ce->fecha_mod = $hoy;
                                $ok = $ce->save();
                                $accionRealizada = "SOLICITUD DE APROBACI&Oacute;N RECHAZADA";
                                if ($ok) {
                                    $objCtrlExcepMsjes->controlexcepcion_estado = -2;
                                }
                                break;
                        }
                    }
                    if ($ok) {

                        /**
                         * Si se ha registrado correctamente la solicitud de registra el hecho en el historial de envío.
                         */
                        $objCtrlExcepMsjes->user_ope_id = $idUsuario;
                        $objCtrlExcepMsjes->fecha_ope = $hoy;
                        $objCtrlExcepMsjes->user_mod_id = $idUsuario;
                        $objCtrlExcepMsjes->fecha_mod = $hoy;
                        $objCtrlExcepMsjes->save();

                        $hoy = date("d-m-Y H:i:s");
                        $this->enviarResultadoSolicitud($idControlExcepcion, $idRelaboralSolicitante, $idRelaboralDestinatarioPrincipal, $estadoSolicitado, $hoy);

                        $cuerpo .= '<div style="min-height: 1020px;" id="page-content">';
                        $cuerpo .= '<div class="row">';
                        $cuerpo .= '<div class="col-md-2">';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<div class="col-md-8">';
                        $cuerpo .= '<div class="block">';
                        $cuerpo .= '<h3>' . $accionRealizada . '<br><small id="smallRecomendacion">Operaci&oacute;n realizada con &eacute;xito</small></h3>';
                        $cuerpo .= '<div class="alert alert-success" id="divAlertSuccess">';
                        $cuerpo .= '<h4>&Eacute;xito!</h4>';
                        $cuerpo .= '<span id="spanAlertSuccess"></span>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<form action="" method="post" class="form-bordered" onsubmit="return false;">';
                        $cuerpo .= '<div style="text-align: center" id="divContador"><span id="spanContador">La pesta&ntilde;a se cerrar&aacute; en <b id="bContador"></b> segundos.</span></div>';
                        $cuerpo .= '<div class="form-group">';
                        $cuerpo .= '<div class="col-md-3">';
                        $cuerpo .= '<span>Solicitante:</span>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<div class="col-md-9">';
                        $cuerpo .= $nombreSolicitante;
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';

                        $cuerpo .= '<div class="form-group">';
                        $cuerpo .= '<div class="col-md-3">';
                        $cuerpo .= '<span>Cargo:</span>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<div class="col-md-9">';
                        $cuerpo .= $cargoSolicitante;
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';

                        if ($departamentoSolicitante != '') {
                            $cuerpo .= '<div class="form-group">';
                            $cuerpo .= '<div class="col-md-3">';
                            $cuerpo .= '<span>- </span>';
                            $cuerpo .= '</div>';
                            $cuerpo .= '<div class="col-md-9">';
                            $cuerpo .= utf8_decode($departamentoSolicitante);
                            $cuerpo .= '</div>';
                            $cuerpo .= '</div>';
                        }
                        if ($gerenciaSolicitante != '') {
                            $cuerpo .= '<div class="form-group">';
                            $cuerpo .= '<div class="col-md-3">';
                            $cuerpo .= '<span>- </span>';
                            $cuerpo .= '</div>';
                            $cuerpo .= '<div class="col-md-9">';
                            $cuerpo .= utf8_decode($gerenciaSolicitante);
                            $cuerpo .= '</div>';
                            $cuerpo .= '</div>';
                        }
                        $cuerpo .= '<div class="form-group">';
                        $cuerpo .= '<div class="col-md-3">';
                        $cuerpo .= '<span>Tipo de Excepci&oacute;n:</span>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<div class="col-md-9">';
                        $cuerpo .= $excepcion;
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        if ($controlexcepcion->lugar = 1) {
                            $cuerpo .= '<div class="form-group">';
                            $cuerpo .= '<div class="col-md-3">';
                            $cuerpo .= '<span>Lugar:</span>';
                            $cuerpo .= '</div>';
                            $cuerpo .= '<div class="col-md-9">';
                            $cuerpo .= $controlexcepcion->destino;
                            $cuerpo .= '</div>';
                            $cuerpo .= '</div>';
                        }
                        $cuerpo .= '<div class="form-group">';
                        $cuerpo .= '<div class="col-md-3">';
                        $cuerpo .= '<span>Motivo / Justificaci&oacute;n:</span>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<div class="col-md-9">';
                        $cuerpo .= utf8_decode($justificacion);
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';

                        if ($fechaIni != '' && $fechaFin != '') {
                            $cuerpo .= '<div class="form-group">';
                            $cuerpo .= '<div class="col-md-3">';
                            $cuerpo .= '<span>Fecha(s):</span>';
                            $cuerpo .= '</div>';
                            $cuerpo .= '<div class="col-md-9">';
                            if ($fechaIni != $fechaFin) {
                                $cuerpo .= $fechaIni . ' al ' . $fechaFin;
                            } else {
                                $cuerpo .= $fechaIni;
                            }
                            $cuerpo .= '</div>';
                            $cuerpo .= '</div>';

                        }
                        if ($horaIni != '' && $horaFin != '') {
                            $cuerpo .= '<div class="form-group">';
                            $cuerpo .= '<div class="col-md-3">';
                            $cuerpo .= '<span>Horario:</span>';
                            $cuerpo .= '</div>';
                            $cuerpo .= '<div class="col-md-9">';
                            $cuerpo .= $horaIni . ' a ' . $horaFin;
                            $cuerpo .= '</div>';
                            $cuerpo .= '</div>';
                        }
                        $cuerpo .= '<div class="form-group">';
                        $cuerpo .= '<div class="col-md-3">';
                        $cuerpo .= '<span>Fecha y Hora Operaci&oacute;n:</span>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<div class="col-md-9">';
                        $cuerpo .= $hoy;
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</br>';
                        $cuerpo .= '</br>';
                        $cuerpo .= '</br>';
                        $cuerpo .= '<div class="form-group form-actions">';
                        $cuerpo .= '<button id="btnCerrarVentana" type="button" class="btn btn-sm btn-danger">Cerrar</button>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</form>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';


                    } else {
                        $cuerpo .= '<div style="min-height: 1020px;" id="page-content">';
                        $cuerpo .= '<div class="row">';
                        $cuerpo .= '<div class="col-md-4">';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<div class="col-md-4">';
                        $cuerpo .= '<div class="block">';
                        $cuerpo .= '<h3>' . $accionSolicitada . '<br><small id="smallRecomendacion">Error!</small></h3>';
                        $cuerpo .= '<div class="alert alert-danger" id="divAlertError">';
                        $cuerpo .= '<h4>Error!</h4>';
                        $cuerpo .= '<span id="spanAlertError">Se ha presentado un error en la solicitud de la operaci&oacute;n, cont&aacute;ctese con el Administrador.</span>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '<form action="" method="post" class="form-bordered" onsubmit="return false;">';
                        $cuerpo .= '<div style="text-align: center" id="divContador"><span id="spanContador">La pesta&ntilde;a se cerrar&aacute; en <b id="bContador"></b> segundos.</span></div>';
                        $cuerpo .= '<div class="form-group form-actions">';
                        $cuerpo .= '<button id="btnCerrarVentana" type="button" class="btn btn-sm btn-danger">Cerrar</button>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</form>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                        $cuerpo .= '</div>';
                    }
                } else {
                    $plazo = $plazo * -1;
                    $cuerpo .= '<div style="min-height: 1020px;" id="page-content">';
                    $cuerpo .= '<div class="row">';
                    $cuerpo .= '<div class="col-md-4">';
                    $cuerpo .= '</div>';
                    $cuerpo .= '<div class="col-md-4">';
                    $cuerpo .= '<div class="block">';
                    $cuerpo .= '<h3>' . $accionSolicitada . '<br><small id="smallRecomendacion">Solicitud no admisible.</small></h3>';
                    $cuerpo .= '<div class="alert alert-danger" id="divAlertError">';
                    $cuerpo .= '<h4>Error!</h4>';
                    if ($opcionPlazo == 1) {
                        $cuerpo .= '<span id="spanAlertError">El tiempo admitido para realizar la operaci&oacute;n [' . $plazo . ' d&iacute;a(s) h&aacute;biles] se ha superado, por lo que ya no se puede realizar la operaci&oacute;n.</span>';
                    } else {
                        $cuerpo .= '<span id="spanAlertError">El tiempo admitido para realizar la operaci&oacute;n se ha superado, por lo que ya no se puede realizar la operaci&oacute;n.</span>';
                    }
                    $cuerpo .= '</div>';
                    $cuerpo .= '<form action="" method="post" class="form-bordered" onsubmit="return false;">';
                    $cuerpo .= '<div style="text-align: center" id="divContador"><span id="spanContador">La pesta&ntilde;a se cerrar&aacute; en <b id="bContador"></b> segundos.</span></div>';
                    $cuerpo .= '<div class="form-group form-actions">';
                    $cuerpo .= '<button id="btnCerrarVentana" type="button" class="btn btn-sm btn-danger">Cerrar</button>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</form>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';

                }
            } else {
                $cuerpo .= '<div style="min-height: 1020px;" id="page-content">';
                $cuerpo .= '<div class="row">';
                $cuerpo .= '<div class="col-md-4">';
                $cuerpo .= '</div>';
                $cuerpo .= '<div class="col-md-4">';
                $cuerpo .= '<div class="block">';
                $cuerpo .= '<h3>' . $accionSolicitada . '<br><small id="smallRecomendacion">Operaci&oacute;n no admisible.</small></h3>';
                $cuerpo .= '<div class="alert alert-danger" id="divAlertError">';
                $cuerpo .= '<h4>Error!</h4>';
                $cuerpo .= '<span id="spanAlertError">Registro ya procesado, operaci&oacute;n no admisible.</span>';
                $cuerpo .= '</div>';
                $cuerpo .= '<form action="" method="post" class="form-bordered" onsubmit="return false;">';
                $cuerpo .= '<div style="text-align: center" id="divContador"><span id="spanContador">La pesta&ntilde;a se cerrar&aacute; en <b id="bContador"></b> segundos.</span></div>';
                $cuerpo .= '<div class="form-group form-actions">';
                $cuerpo .= '<button id="btnCerrarVentana" type="button" class="btn btn-sm btn-danger">Cerrar</button>';
                $cuerpo .= '</div>';
                $cuerpo .= '</form>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
            }
            $cuerpo .= '<br>';
            /*$cuerpo .= '<div><a href="#" id="aCerrarVentana" ">Cerrar Ventana</a><br></div>';*/
            $cuerpo .= '<script type="text/javascript">';
            $cuerpo .= '
                        $().ready(function() {
                          $("#btnCerrarVentana").on("click",function(){
                            close();
                        });
                        var myCounter = new Countdown({
                            seconds:10,  // number of seconds to count down
                            onUpdateStatus: function(sec){
                                $("#divContador").show();
                                $("#bContador").text(sec);
                            },
                            onCounterEnd: function(){
                                close();
                            }
                        });

                        myCounter.start();

                        });
               /**
                *   Función para la contabilización de los segundos
                */
                function Countdown(options) {
                    var timer,
                    instance = this,
                    seconds = options.seconds || 10,
                    updateStatus = options.onUpdateStatus || function () {},
                    counterEnd = options.onCounterEnd || function () {};

                    function decrementCounter() {
                    updateStatus(seconds);
                    if (seconds === 0) {
                    counterEnd();
                    instance.stop();
                    }
                    seconds--;
                    }

                    this.start = function () {
                    clearInterval(timer);
                    timer = 0;
                    seconds = options.seconds;
                    timer = setInterval(decrementCounter, 1000);
                    };

                    this.stop = function () {
                    clearInterval(timer);
                    };
                }


                        ';
            $cuerpo .= '</script>';
            $cuerpo .= '</body>';
            $cuerpo .= '</html>';
            echo $cuerpo;
        }
    }

    /**
     * Función para el despliegue del detalle correspondiente a una boleta de excepción.
     * @param null $idControlExcepcionCodificado
     */
    public function detailAction($idControlExcepcionCodificado = null)
    {
        $this->view->disable();
        $cuerpo = '';
        if ($idControlExcepcionCodificado != '') {
            $idControlExcepcion = base64_decode(str_pad(strtr($idControlExcepcionCodificado, '-_', '+/'), strlen($idControlExcepcionCodificado) % 4, '=', STR_PAD_RIGHT));
            if (is_numeric($idControlExcepcion) && $idControlExcepcion > 0) {
                $objCE = new Fcontrolexcepciones();
                $objR = new Frelaborales();
                $controlexcepcion = $objCE->getOne($idControlExcepcion);
                if ($idControlExcepcion > 0 && $controlexcepcion->id_relaboral > 0) {
                    $objCorr = Controlexcepcionescorrelativo::findFirst(array("controlexcepcion_id = " . $idControlExcepcion . " AND estado=1 AND baja_logica=1"));
                    $gestion = substr($objCorr->gestion, -2);
                    $cant = strlen($objCorr->numero);
                    $prefijo = "";
                    switch ($cant) {
                        case 1:
                            $prefijo = "0000";
                            break;
                        case 2:
                            $prefijo = "000";
                            break;
                        case 3:
                            $prefijo = "00";
                            break;
                        case 4:
                            $prefijo = "0";
                            break;
                    }
                    $idRelaboralSolicitante = $controlexcepcion->id_relaboral;
                    $relaboralVerificador = $relaboralAprobador = null;
                    if ($controlexcepcion->controlexcepcion_user_ver_id > 0) {
                        $verificador = Usuarios::findFirstById($controlexcepcion->controlexcepcion_user_ver_id);
                        if (is_object($verificador)) {
                            $relaboralVerificador = $objR->getOneRelaboralConsiderandoUltimaMovilidadPorPersona($verificador->persona_id);
                        }
                    }
                    $this->view->disable();
                    $hoy = date("Y-m-d H:i:s");
                    $ahora = date("d-m-Y");
                    $cuerpo = "";
                    $cuerpo = '<html>';
                    $cuerpo .= '<head>';
                    $cuerpo .= '<title>Detalle Excepci&oacute;n</title>';
                    $cuerpo .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>';
                    $cuerpo .= '<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">';
                    $cuerpo .= '<style type="text/css">';
                    $cuerpo .= 'body{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;color:#394263;font-size:13px;background-color:#f2f2f2}#main-container,#page-container{min-width:320px}#page-container{width:100%;padding:0;margin:0 auto;overflow-x:hidden;-webkit-transition:background-color .2s ease-out;transition:background-color .2s ease-out}#page-container,#sidebar{background-color:#11203a}#sidebar{width:0;position:absolute;overflow:hidden}#main-container,#sidebar,.header-fixed-bottom header,.header-fixed-top header{-webkit-transition:all .2s ease-out;transition:all .2s ease-out}#page-content{padding:10px 5px 1px;min-height:1200px;background-color:#eaedf1}#page-content+footer{padding:9px 10px;font-size:11px;background-color:#fff;border-top:1px solid #dbe1e8}#page-container.header-fixed-top{padding:50px 0 0}#page-container.header-fixed-bottom{padding:0 0 50px}.sidebar-open #sidebar{width:200px}.sidebar-open #main-container{margin-left:220px}.header-fixed-bottom #sidebar,.header-fixed-top #sidebar{position:fixed;left:0;top:0;bottom:0}.header-fixed-bottom .sidebar-content,.header-fixed-top .sidebar-content{padding-bottom:50px}.sidebar-open header.navbar-fixed-bottom,.sidebar-open header.navbar-fixed-top{left:220px}header.navbar-default,header.navbar-inverse{padding:0;margin:0;min-width:320px;max-height:50px;border:0}header.navbar-fixed-bottom,header.navbar-fixed-top{max-height:51px}header.navbar-default.navbar-fixed-top{border-bottom:1px solid #eaedf1}header.navbar-default.navbar-fixed-bottom{border-top:1px solid #eaedf1}header.navbar-inverse.navbar-fixed-top{border-bottom:1px solid #394263}header.navbar-inverse.navbar-fixed-bottom{border-top:1px solid #394263}.nav.navbar-nav-custom{float:left;margin:0}.nav.navbar-nav-custom>li{min-height:50px;float:left}.nav.navbar-nav-custom>li>a{min-width:50px;padding:5px 7px;line-height:40px;text-align:center;color:#394263}.nav.navbar-nav-custom>li>a .fi,.nav.navbar-nav-custom>li>a .gi,.nav.navbar-nav-custom>li>a .hi,.nav.navbar-nav-custom>li>a .si{margin-top:-3px}.navbar-inverse .nav.navbar-nav-custom>li>a{color:#fff}.nav.navbar-nav-custom>li.open>a,.nav.navbar-nav-custom>li>a:focus,.nav.navbar-nav-custom>li>a:hover{background-color:#1bbae1;color:#fff}.nav.navbar-nav-custom>li>a>img{width:40px;height:40px;border:2px solid #fff;border-radius:20px;vertical-align:top}.navbar-form-custom{padding:0;width:100px;float:left;height:50px}.navbar-form-custom .form-control{padding:10px;margin:0;height:50px;font-size:15px;background:0 0;border:0;z-index:2000}.navbar-form-custom .form-control:focus,.navbar-form-custom .form-control:hover{background-color:#fff}.navbar-form-custom .form-control:focus{position:absolute;top:0;left:0;right:0;font-size:18px;padding:10px 20px}.navbar-inverse .navbar-form-custom .form-control{color:#fff}.navbar-inverse .navbar-form-custom .form-control:focus,.navbar-inverse .navbar-form-custom .form-control:hover{background:#000;color:#fff}.sidebar-content{width:220px;color:#fff}.sidebar-section{padding:10px}.sidebar-brand{height:50px;line-height:50px;padding:0 10px;margin:0;font-weight:300;font-size:18px;display:block;color:#fff;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-brand:focus,.sidebar-brand:hover{background-color:#1bbae1;color:#fff;text-decoration:none}.sidebar-brand i{font-size:14px;display:inline-block;width:18px;text-align:center;margin-right:10px;opacity:.5;filter:alpha(opacity=50)}.sidebar-user{padding-left:88px;background:url(../img/template/ie8_opacity_light_10.png) repeat;background:rgba(255,255,255,.1)}.sidebar-user-avatar{width:68px;height:68px;float:left;padding:2px;margin-left:-78px;border-radius:34px;background:url(../img/template/ie8_opacity_light_75.png) repeat;background:rgba(255,255,255,.75)}.sidebar-user-avatar img{width:64px;height:64px;border-radius:32px}.sidebar-user-name{font-size:17px;font-weight:300;margin-top:10px;line-height:26px}.sidebar-user-links a{color:#fff;opacity:.3;filter:alpha(opacity:30);margin-right:5px}.sidebar-user-links a:focus,.sidebar-user-links a:hover{color:#fff;text-decoration:none;opacity:1;filter:alpha(opacity:100)}.sidebar-user-links a>i{font-size:14px}.sidebar-themes{list-style:none;margin:0;padding-bottom:7px;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-themes li{float:left;margin:0 3px 3px 0}.sidebar-themes li a{display:block;width:17px;height:17px;border-radius:10px;border-width:2px;border-style:solid}.sidebar-themes li a:focus,.sidebar-themes li a:hover,.sidebar-themes li.active a{border-color:#fff!important}.sidebar-nav{list-style:none;margin:0;padding:10px 0 0}.sidebar-nav .sidebar-header:first-child{margin-top:0}.sidebar-nav a{display:block;color:#eaedf1;padding:0 10px;min-height:35px;line-height:35px}.sidebar-nav a.open,.sidebar-nav a:hover,.sidebar-nav li.active>a{color:#fff;text-decoration:none;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-nav a.active{padding-left:5px;border-left:5px solid #1bbae1;background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.sidebar-nav a>.sidebar-nav-icon{margin-right:10px}.sidebar-nav a>.sidebar-nav-indicator{float:right;line-height:inherit;margin-left:4px;-webkit-transition:all .15s ease-out;transition:all .15s ease-out}.sidebar-nav a>.sidebar-nav-icon,.sidebar-nav a>.sidebar-nav-indicator{display:inline-block;opacity:.5;filter:alpha(opacity:50);width:18px;font-size:14px;text-align:center}.sidebar-nav a.active,.sidebar-nav a.active>.sidebar-nav-icon,.sidebar-nav a.active>.sidebar-nav-indicator,.sidebar-nav a.open,.sidebar-nav a.open>.sidebar-nav-icon,.sidebar-nav a.open>.sidebar-nav-indicator,.sidebar-nav a:hover,.sidebar-nav a:hover>.sidebar-nav-icon,.sidebar-nav a:hover>.sidebar-nav-indicator,.sidebar-nav li.active>a,.sidebar-nav li.active>a>.sidebar-nav-icon,.sidebar-nav li.active>a>.sidebar-nav-indicator{opacity:1;filter:alpha(opacity:100)}.sidebar-nav a.active>.sidebar-nav-indicator,.sidebar-nav a.open>.sidebar-nav-indicator,.sidebar-nav li.active>a>.sidebar-nav-indicator{-webkit-transform:rotate(-90deg);transform:rotate(-90deg)}.sidebar-nav ul{list-style:none;padding:0;margin:0;display:none;background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.sidebar-nav li.active>ul{display:block}.sidebar-nav ul a{margin:0;font-size:12px;padding-left:15px;min-height:32px;line-height:32px}.sidebar-nav ul a.active,.sidebar-nav ul a.active:hover{border-left:5px solid #1bbae1;padding-left:10px}.sidebar-nav ul ul{background:url(../img/template/ie8_opacity_dark_40.png) repeat;background:rgba(0,0,0,.4)}.sidebar-nav ul ul a{padding-left:25px}.sidebar-nav ul ul a.active,.sidebar-nav ul ul a.active:hover{padding-left:20px}.sidebar-header{margin:10px 0 0;padding:10px;line-height:12px}.sidebar-header+.sidebar-section{padding-top:0;padding-bottom:0}.sidebar-header .sidebar-header-title{color:#fff;font-size:11px;text-transform:uppercase;opacity:.5;filter:alpha(opacity:50)}.sidebar-header-options{float:right;display:inline-block}.sidebar-header-options>a,.sidebar-nav .sidebar-header-options a{float:right;margin:0;padding:0;min-height:0;line-height:inherit;display:block;min-width:18px;text-align:center;color:#fff;opacity:.3;filter:alpha(opacity:30)}.sidebar-header-options a.active,.sidebar-header-options a:focus,.sidebar-header-options a:hover,.sidebar-nav .sidebar-header-options a.active,.sidebar-nav .sidebar-header-options a:focus,.sidebar-nav .sidebar-header-options a:hover{background:0 0;color:#fff;opacity:1;filter:alpha(opacity:100)}.sidebar-header-options a>i{font-size:14px}.content-header{background-color:#fff;border-top:1px solid #eaedf1;border-bottom:1px solid #dbe1e8}.content-header h1,.content-header h2{margin:0;font-size:26px;line-height:32px}.content-header small .content-header small{font-size:17px}.header-section h1 i{font-size:56px;float:right;color:#eaedf1;margin:0 0 0 10px;line-height:64px}.header-section{padding:30px 10px}.content-header,.content-top{margin:-10px -5px 10px}.content-top{background-color:#fff;border-bottom:1px solid #dbe1e8}.content-header-media{position:relative;height:248px;overflow:hidden;border-top-color:#222}.content-header-media .header-section{z-index:200;position:absolute;top:0;left:0;right:0;color:#fff;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}.content-header-media i,.content-header-media small{color:#ddd}.content-header-media>img{position:absolute;top:0;left:50%;width:2560px;height:248px;margin-left:-1280px}.content-header-media>.content-header-media-map{height:270px}.block{margin:0 0 10px;padding:20px 15px 1px;background-color:#fff;border:1px solid #dbe1e8}.block.full{padding:20px 15px}.block .block-content-full{margin:-20px -15px -1px}.block .block-content-mini-padding{padding:8px}.block.full .block-content-full{margin:-20px -15px}.block-title{margin:-20px -15px 20px;background-color:#f9fafc;border-bottom:1px solid #eaedf1}.block-title h1,.block-title h2,.block-title h3,.block-title h4,.block-title h5,.block-title h6{display:inline-block;font-size:16px;line-height:1.4;margin:0;padding:10px 16px 7px;font-weight:400}.block-title h1 small,.block-title h2 small,.block-title h3 small,.block-title h4 small,.block-title h5 small,.block-title h6 small{font-size:13px;color:#777;font-weight:400}.block-title h1,.block-title h2,.block-title h3{padding-left:15px;padding-right:15px}.block-options,.block-title .nav-tabs{min-height:40px;line-height:38px}.block-title .nav-tabs{padding:3px 1px 0;border-bottom:0}.block-title .nav-tabs>li>a{border-bottom:0}.block-title .nav-tabs{margin-bottom:-2px}.block-title .nav-tabs>li>a{margin-bottom:0}.block-title .nav-tabs>li>a:hover{background:0 0}.block-title .nav-tabs>li.active>a,.block-title .nav-tabs>li.active>a:focus,.block-title .nav-tabs>li.active>a:hover{border:1px solid #eaedf1;border-bottom-color:#fff;background-color:#fff}.block-title code{padding:2px 3px}.block-options{margin:0 6px;line-height:37px}.block-options .label{display:inline-block;padding:6px;vertical-align:middle;font-size:13px}.block-top{margin:-20px -15px 20px;border-bottom:1px dotted #dbe1e8}.block-section{margin-bottom:20px}.widget{background-color:#fff;margin-bottom:10px}.widget .widget-extra,.widget .widget-extra-full{position:relative;padding:15px}.widget .widget-extra{padding-top:1px;padding-bottom:1px}.widget .widget-content-light{color:#fff}.widget .widget-content-light small{color:#eee}.widget .widget-icon,.widget .widget-image{width:64px;height:64px}.widget .widget-icon{height:64px;display:inline-block;line-height:64px;text-align:center;font-size:28px;color:#fff;border-radius:32px}.widget .widget-icon .fi,.widget .widget-icon .gi,.widget .widget-icon .hi,.widget .widget-icon .si{margin-top:-3px}.widget .widget-options,.widget .widget-options-left{position:absolute;top:5px;opacity:.5;filter:alpha(opacity=50)}.widget .widget-options{right:5px}.widget .widget-options-left{left:5px}.widget .widget-options-left:hover,.widget .widget-options:hover{opacity:1;filter:alpha(opacity=100)}.widget-simple{padding:15px}.widget-simple:after,.widget-simple:before{content:" ";display:table}.widget-simple:after{clear:both}.widget-simple .widget-icon,.widget-simple .widget-image{margin:0 15px}.widget-simple .widget-icon.pull-left,.widget-simple .widget-image.pull-left{margin-left:0}.widget-simple .widget-icon.pull-right,.widget-simple .widget-image.pull-right{margin-right:0}.widget-simple .widget-content{font-size:18px;margin:12px 0}.widget-simple .widget-content small{display:block;margin-top:7px;font-size:13px;font-weight:400}.widget-advanced .widget-header{position:relative;padding:15px 15px 50px;height:150px;overflow:hidden}.widget-advanced .widget-background{position:absolute;top:0;left:0;height:150px}.widget-advanced .widget-background-map{height:180px;width:100%}.widget-advanced .widget-content-image{position:absolute;top:0;left:0;width:100%;padding:15px;margin:0;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}.widget-advanced .widget-main{position:relative;padding:50px 15px 15px}.widget-advanced .widget-image-container{position:absolute;display:inline-block;padding:5px;width:74px;height:74px;top:-36px;left:50%;margin-left:-36px;border-radius:36px;background-color:#fff}.widget-advanced .widget-header .widget-image-container{position:static;left:auto;top:auto;margin:0}.widget-advanced-alt .widget-header,.widget-advanced-alt .widget-main{padding:15px}.widget-advanced-alt .widget-header{height:auto;min-height:150px}.content-float .pull-left{margin:0 20px 20px 0}.content-float .pull-right{margin:0 0 20px 20px}#to-top{display:none;position:fixed;bottom:55px;left:5px;border-radius:3px;padding:0 12px;font-size:28px;text-align:center;color:#fff;background-color:#000;opacity:.1;filter:alpha(opacity=10)}#to-top:hover{color:#fff;background-color:#1bbae1;text-decoration:none;opacity:1;filter:alpha(opacity=100)}#login-background{width:100%;height:224px;overflow:hidden;position:relative}#login-background>img{position:absolute;width:2560px;height:400px;left:50%;margin-left:-1280px}#login-container{position:absolute;width:300px;top:10px;left:50%;margin-left:-150px;z-index:1000}#login-container .login-title{padding:20px 10px;background:#394263;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}#login-container .login-title h1{font-size:26px;color:#fff}#login-container .login-title small{font-size:16px;color:#ddd}#login-container>.block{border:0}#login-container .register-terms{line-height:30px;margin-right:10px;float:left}.calendar-events{list-style:none;margin:0;padding:0}.calendar-events li{color:#fff;margin-bottom:5px;padding:5px 10px;border-radius:3px;background-color:#555;opacity:.85;filter:alpha(opacity=85)}.calendar-events li:hover{cursor:move;opacity:1;filter:alpha(opacity=100)}.gallery a img,.gallery img,.gallery-image img,a[data-toggle=lightbox-image] img{max-width:100%}a.gallery-link,a[data-toggle=lightbox-image]{cursor:pointer;cursor:-webkit-zoom-in;cursor:-moz-zoom-in;cursor:zoom-in}.gallery a:hover img,.gallery-image:hover img,a[data-toggle=lightbox-image]:hover img{opacity:.75;filter:alpha(opacity=75)}.gallery-image{position:relative}.gallery-image-options{position:absolute;top:0;bottom:0;left:0;right:0;display:none;padding:10px}.gallery-image:hover .gallery-image-options{display:block}.gallery>.row>div{margin-bottom:15px}.gallery.gallery-widget>.row>div{margin-bottom:0;padding-top:7px;padding-bottom:7px}.pie-chart .pie-avatar{position:absolute;top:8px;left:8px}.chart{height:360px}.chart-tooltip,.mini-chart-tooltip{position:absolute;display:none;color:#fff;background-color:#000;padding:4px 10px}.chart-pie-label{font-size:12px;text-align:center;padding:8px 12px;color:#fff}.mini-chart-tooltip{left:0;top:0;visibility:hidden}.timeline{position:relative}.timeline-header{margin:0;font-size:18px;font-weight:600;padding:0 15px;min-height:60px;line-height:60px;background-color:#fff;border-bottom:2px solid #f0f0f0;z-index:500}.timeline-list{list-style:none;margin:0;padding:0}.timeline-list:after{position:absolute;display:block;width:2px;top:0;left:95px;bottom:0;content:"";background-color:#f0f0f0;z-index:1}.timeline-header+.timeline-list:after{top:60px}.timeline-list li{position:relative;margin:0;padding:10px 0 ; border-bottom: 2px solid #fff }.timeline-list.timeline-hover li:hover{ }.timeline-list .timeline-icon{position:absolute;left:80px;top:10px;width:30px;height:30px;line-height:28px;font-size:14px;text-align:center;background-color:#fff;border:1px solid #ddd;border-radius:15px;z-index:500}.timeline-list .active .timeline-icon{background-color:#1bbae1;border-color:#1bbae1;color:#fff}.timeline-list .timeline-time{float:left;width:70px;text-align:right}.timeline-list .timeline-content{margin-left:120px}.block-content-full .timeline-content{padding-right:20px}.media-feed{margin-bottom:0}.media-feed>.media{margin-top:0;padding:20px 20px 0;border-top:1px dotted #dbe1e8}.media-feed>.media:first-child{border-top:0}.media-feed.media-feed-hover>.media:hover{background-color:#f9f9f9}#error-container{padding:120px 20px;position:relative}#error-container .error-options{position:absolute;top:20px;left:20px}#error-container h1{font-size:96px;color:#fff;margin-bottom:40px}#error-container h2{color:#ccc;margin-bottom:40px;line-height:1.4}#error-container form{padding:20px;border-radius:3px;background:#fff;background:url(../img/template/ie8_opacity_light_10.png) repeat;background:rgba(255,255,255,.1)}#error-container .form-control{border-color:#fff}.table.table-pricing{background-color:#fff}.table-pricing td,.table-pricing th{text-align:center}.table-pricing th{font-size:24px!important}.table-pricing td{font-size:15px;padding-top:12px!important;padding-bottom:12px!important}.table-pricing .table-price{background-color:#f9f9f9}.table-pricing .table-price.table-featured,.table-pricing.table-featured .table-price{background-color:#252525}.table-pricing th.table-featured,.table-pricing.table-featured th{background-color:#1bbae1;border-bottom:2px solid #394263;color:#fff}.table-pricing td.table-featured,.table-pricing.table-featured td{background-color:#394263;color:#fff}.navbar.navbar-default{background-color:#f9fafc}.navbar.navbar-inverse{background-color:#4c5471}.navbar-fixed-bottom,.navbar-fixed-top{border-width:0}.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{font-family:"Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;font-weight:300}.h1 .small,.h1 small,.h2 .small,.h2 small,.h3 .small,.h3 small,.h4 .small,.h4 small,.h5 .small,.h5 small,.h6 .small,.h6 small,h1 .small,h1 small,h2 .small,h2 small,h3 .small,h3 small,h4 .small,h4 small,h5 .small,h5 small,h6 .small,h6 small{font-weight:300;color:#777}h1,h2,h3{margin-bottom:15px}.text-primary,.text-primary:hover,a,a:focus,a:hover{color:#1bbae1}.text-danger,.text-danger:hover,a.text-danger,a.text-danger:focus,a.text-danger:hover{color:#e74c3c}.text-warning,.text-warning:hover,a.text-warning,a.text-warning:focus,a.text-warning:hover{color:#e67e22}.text-success,.text-success:hover,a.text-success,a.text-success:focus,a.text-success:hover{color:#27ae60}.text-info,.text-info:hover,a.text-info,a.text-info:focus,a.text-info:hover{color:#3498db}.text-muted,.text-muted:hover,a.text-muted,a.text-muted:focus,a.text-muted:hover{color:#999}b,strong{font-weight:600}ol,ul{padding-left:30px}.list-li-push li{margin-bottom:10px}p{line-height:1.6}article p{font-size:16px;line-height:1.8}.well{background-color:#f9f9f9;border:1px solid #eee}.page-header{border-bottom-width:1px;border-bottom-color:#ddd;margin:30px 0 20px}.sub-header{margin:10px 0 20px;padding:10px 0;border-bottom:1px dotted #ddd}blockquote{border-left-width:3px;margin:20px 0;padding:30px 60px 30px 20px;position:relative;width:100%;border-color:#eaedf1}blockquote:before{display:block;content:"\201C";font-family:serif;font-size:96px;position:absolute;right:10px;top:-30px;color:#eaedf1}blockquote.pull-right:before{left:10px;right:auto}label{font-weight:600}fieldset legend{font-size:16px;padding:30px 0 10px;border-bottom:2px solid #eaedf1}input[type=file]{padding-top:7px}input[type=email].form-control,input[type=password].form-control,input[type=text].form-control,textarea.form-control{-webkit-appearance:none}.form-control{font-size:13px;padding:6px 8px;max-width:100%;margin:1px 0;color:#394263;border-color:#dbe1e8}.form-control-borderless,.form-control-borderless .form-control,.form-control-borderless .input-group-addon,.form-control-borderless:focus{border:transparent!important}.input-group{margin-top:1px;margin-bottom:1px}.input-group .form-control{margin-top:0}.form-control:focus{border-color:#1bbae1}.help-block{color:#777;font-weight:400}.input-group-addon{min-width:45px;text-align:center;background-color:#fff;border-color:#dbe1e8}.form-horizontal .control-label{margin-bottom:5px}.form-bordered{margin:-15px -15px -1px}.modal-body .form-bordered{margin-bottom:-20px}.form-bordered fieldset legend{margin:0;padding-left:20px;padding-right:20px}.form-bordered .form-group{margin:0;border:0;padding:15px;border-bottom:1px dashed #eaedf1}.form-bordered .form-group.form-actions{background-color:#f9fafc;border-bottom:0;border-bottom-left-radius:4px;border-bottom-right-radius:4px}.form-horizontal.form-bordered .form-group{padding-left:0;padding-right:0}.form-bordered .help-block{margin-bottom:0}.has-success .checkbox,.has-success .checkbox-inline,.has-success .control-label,.has-success .help-block,.has-success .input-group-addon,.has-success .radio,.has-success .radio-inline{color:#27ae60}.has-success .form-control,.has-success .input-group-addon{border-color:#27ae60;background-color:#fff}.has-success .form-control:focus{border-color:#166638}.has-warning .checkbox,.has-warning .checkbox-inline,.has-warning .control-label,.has-warning .help-block,.has-warning .input-group-addon,.has-warning .radio,.has-warning .radio-inline{color:#e67e22}.has-warning .form-control,.has-warning .input-group-addon{border-color:#e67e22;background-color:#fff}.has-warning .form-control:focus{border-color:#b3621b}.has-error .checkbox,.has-error .checkbox-inline,.has-error .control-label,.has-error .help-block,.has-error .input-group-addon,.has-error .radio,.has-error .radio-inline{color:#e74c3c}.has-error .form-control,.has-error .input-group-addon{border-color:#e74c3c;background-color:#fff}.has-error .form-control:focus{border-color:#c0392b}.wizard-steps{border-bottom:1px solid #eaedf1;margin-bottom:20px}.form-bordered .wizard-steps{margin-bottom:0}.wizard-steps .row{margin:0}.wizard-steps .row div{padding:15px 0;font-size:15px;text-align:center}.form-bordered .wizard-steps .row div{padding-top:10px}.wizard-steps span{display:inline-block;width:100px;height:100px;line-height:100px;border:1px solid #1bbae1;border-radius:50px}.wizard-steps div.active span,.wizard-steps div.done span{background-color:#1bbae1;color:#fff}.wizard-steps div.done span{opacity:.25;filter:alpha(opacity=25)}.wizard-steps div.active span{opacity:1;filter:alpha(opacity=100)}.switch{margin:1px 0;position:relative;cursor:pointer}.switch input{position:absolute;opacity:0;filter:alpha(opacity=0)}.switch span{position:relative;display:inline-block;width:54px;height:28px;border-radius:28px;background-color:#f9f9f9;border:1px solid #ddd;-webkit-transition:background-color .35s;transition:background-color .35s}.switch span:after{content:"";position:absolute;left:7px;top:7px;bottom:7px;width:12px;background-color:#fff;border:1px solid #ddd;border-radius:24px;-webkit-box-shadow:1px 0 3px rgba(0,0,0,.05);box-shadow:1px 0 3px rgba(0,0,0,.05);-webkit-transition:all .15s ease-out;transition:all .15s ease-out}.switch input:checked+span:after{left:26px;width:24px;top:1px;bottom:1px;border:0;-webkit-box-shadow:-2px 0 3px rgba(0,0,0,.1);box-shadow:-2px 0 3px rgba(0,0,0,.1)}.switch input:checked+span{background-color:#eee}.switch-default span{border-color:#dbe1e8}.switch-default input:checked+span{background-color:#dbe1e8}.switch-primary span{border-color:#1bbae1}.switch-primary input:checked+span{background-color:#1bbae1}.switch-info span{border-color:#7abce7}.switch-info input:checked+span{background-color:#7abce7}.switch-success span{border-color:#aad178}.switch-success input:checked+span{background-color:#aad178}.switch-warning span{border-color:#f7be64}.switch-warning input:checked+span{background-color:#f7be64}.switch-danger span{border-color:#ef8a80}.switch-danger input:checked+span{background-color:#ef8a80}.table.table-vcenter td,.table.table-vcenter th{vertical-align:middle}.table-options{padding:6px 0}.table thead>tr>th{font-size:18px;font-weight:600}.table thead>tr>th>small{font-weight:400;font-size:75%}.table tfoot>tr>td,.table tfoot>tr>th,.table thead>tr>td,.table thead>tr>th{padding-top:14px;padding-bottom:14px}.table tfoot>tr>td,.table tfoot>tr>th{background-color:#f9fafc}.table-borderless tbody>tr>td,.table-borderless tbody>tr>th{border-top-width:0}.table tbody+tbody,.table tbody>tr>td,.table tbody>tr>th,.table tfoot>tr>td,.table tfoot>tr>th,.table thead>tr>td,.table thead>tr>th,.table-bordered,.table-bordered>tbody>tr>td,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>td,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{border-color:#eaedf1}.table-hover>tbody>tr:hover>td,.table-hover>tbody>tr:hover>th{background-color:#eaedf1}.list-group-item{border-color:#eaedf1}a.list-group-item.active,a.list-group-item.active:focus,a.list-group-item.active:hover{background-color:#1bbae1;border-color:#1bbae1}a.list-group-item.active .list-group-item-text,a.list-group-item.active:focus .list-group-item-text,a.list-group-item.active:hover .list-group-item-text{color:#fff}a.list-group-item:focus,a.list-group-item:hover{background-color:#f9fafc}a.list-group-item.active>.badge{background:url(../img/template/ie8_opacity_dark_40.png) repeat;background:rgba(0,0,0,.4);color:#fff}.dropdown-menu>.active>a,.dropdown-menu>.active>a:focus,.dropdown-menu>.active>a:hover,.dropdown-menu>li>a:focus,.dropdown-menu>li>a:hover,.nav .open>a,.nav .open>a:focus,.nav .open>a:hover,.nav-pills>li.active>a,.nav-pills>li.active>a:focus,.nav-pills>li.active>a:hover{color:#fff;background-color:#1bbae1}.nav>li i{font-size:14px}.nav-pills>.active>a>.badge{color:#1bbae1}.nav-stacked>li>a{margin:4px 0 0}.nav .caret,.nav a:focus .caret,.nav a:hover .caret{border-top-color:#1bbae1;border-bottom-color:#1bbae1}.nav>li>a:focus,.nav>li>a:hover{background-color:#f9fafc}.nav-tabs{border-bottom-color:#eaedf1}.nav-tabs>li{margin-bottom:0}.nav-tabs>li>a{padding-left:7px;padding-right:7px;margin-bottom:-1px}.nav-tabs>li>a:hover{border-color:#eaedf1}.nav-tabs>li.active>a,.nav-tabs>li.active>a:focus,.nav-tabs>li.active>a:hover{color:#394263;border-color:#eaedf1;border-bottom-color:transparent}.nav-pills>li.active>a>.badge{background:url(../img/template/ie8_opacity_dark_20.png) repeat;background:rgba(0,0,0,.2);color:#fff}.dropdown-menu{padding:0;font-size:13px;border-color:#dbe1e8;-webkit-box-shadow:0 3px 6px rgba(0,0,0,.1);box-shadow:0 3px 6px rgba(0,0,0,.1)}.dropdown-menu>li>a{padding:6px 10px}.dropdown-menu>li:first-child>a{border-top-left-radius:3px;border-top-right-radius:3px}.dropdown-menu>li:last-child>a{border-bottom-left-radius:3px;border-bottom-right-radius:3px}.dropdown-menu i{opacity:.2;filter:alpha(opacity=20);line-height:17px}.dropdown-menu a:hover i{opacity:.5;filter:alpha(opacity=50)}.dropdown-menu .divider{margin:2px 0;padding:0!important;background-color:#f0f0f0}li.dropdown-header{padding:5px 10px;color:#394263;background-color:#f9fafc;border-top:1px solid #eaedf1;border-bottom:1px solid #eaedf1}.dropdown-menu li:first-child.dropdown-header{border-top:0;border-top-left-radius:3px;border-top-right-radius:3px}.dropdown-menu.dropdown-custom{min-width:200px}.dropdown-menu.dropdown-custom>li{padding:8px 10px;font-size:12px}.dropdown-menu.dropdown-custom>li>a{border-radius:3px}.pagination>li>a,.pagination>li>span{color:#1bbae1;margin-left:5px;margin-right:5px;border:0!important;border-radius:25px!important}.pagination>.active>a,.pagination>.active>a:focus,.pagination>.active>a:hover,.pagination>.active>span,.pagination>.active>span:focus,.pagination>.active>span:hover{background-color:#1bbae1}.pager>li>a,.pager>li>span{border-color:#eaedf1}.pager>li>a:hover,.pagination>li>a:hover{background-color:#1bbae1;border-color:#1bbae1;color:#fff}.pager>li.disabled>a:hover{border-color:#eaedf1}.popover-title{background:0 0;border:0;font-size:17px;font-weight:600}.tooltip{z-index:1051}.tooltip.in{opacity:1;filter:alpha(opacity=100)}.tooltip-inner{padding:4px 6px;background-color:#000;color:#fff}.tooltip.top .tooltip-arrow,.tooltip.top-left .tooltip-arrow,.tooltip.top-right .tooltip-arrow{border-top-color:#000}.tooltip.right .tooltip-arrow{border-right-color:#000}.tooltip.left .tooltip-arrow{border-left-color:#000}.tooltip.bottom .tooltip-arrow,.tooltip.bottom-left .tooltip-arrow,.tooltip.bottom-right .tooltip-arrow{border-bottom-color:#000}.breadcrumb{background-color:#fff}.breadcrumb i{font-size:14px}.breadcrumb-top{margin:-10px -5px 10px;padding:7px 10px;border-top:1px solid #eaedf1;border-bottom:1px solid #dbe1e8;font-size:12px}.breadcrumb-top+.content-header,.content-header+.breadcrumb-top{margin-top:-11px}.breadcrumb>li+li:before{content:"\203a"}.progress,.progress-bar{height:20px;line-height:20px}.progress-bar-danger{background-color:#e74c3c}.progress-bar-warning{background-color:#f39c12}.progress-bar-success{background-color:#2ecc71}.progress-bar-info{background-color:#3498db}.modal-content{border-radius:3px}.modal-header{padding:15px 15px 14px;border-bottom:1px solid #eee;border-top-left-radius:4px;border-top-right-radius:4px}.modal-title{font-weight:300}.modal-body{padding:20px 15px}.modal-body .nav-tabs{margin:0 -15px 15px;padding:0 5px!important}.modal-footer{margin-top:0;padding:14px 15px 15px;border-top:1px solid #eee;background-color:#f9f9f9;border-bottom-left-radius:4px;border-bottom-right-radius:4px}.btn{margin:1px 0;background-color:#fff}.btn .fi,.btn .gi,.btn .hi,.btn .si{line-height:1}.btn.disabled,.btn[disabled],fieldset[disabled] .btn{opacity:.4;filter:alpha(opacity=40)}.block-options .btn,.input-group .btn,.modal-content .btn{margin-top:0;margin-bottom:0}.btn-default{background-color:#f1f3f6;border-color:#dbe1e8;color:#394263}.btn-default.btn-alt{background-color:#fff}.btn-default:hover{background-color:#eaedf1;border-color:#c2c8cf}.btn-default.active,.btn-default.disabled,.btn-default.disabled.active,.btn-default.disabled:active,.btn-default.disabled:focus,.btn-default.disabled:hover,.btn-default:active,.btn-default:focus,.btn-default[disabled].active,.btn-default[disabled]:active,.btn-default[disabled]:focus,.btn-default[disabled]:hover,.open .btn-default.dropdown-toggle,fieldset[disabled] .btn-default.active,fieldset[disabled] .btn-default:active,fieldset[disabled] .btn-default:focus,fieldset[disabled] .btn-default:hover{background-color:#eaedf1;border-color:#eaedf1}.btn-primary{background-color:#1BBAE1;border-color:#1C75CB;color:#fff}.btn-primary.btn-alt{background-color:#fff;color:#1C6FD0}.btn-primary:hover{background-color:#11203a;border-color:#1C6FD0;color:#fff}.btn-primary.active,.btn-primary.disabled,.btn-primary.disabled.active,.btn-primary.disabled:active,.btn-primary.disabled:focus,.btn-primary.disabled:hover,.btn-primary:active,.btn-primary:focus,.btn-primary[disabled].active,.btn-primary[disabled]:active,.btn-primary[disabled]:focus,.btn-primary[disabled]:hover,.open .btn-primary.dropdown-toggle,fieldset[disabled] .btn-primary.active,fieldset[disabled] .btn-primary:active,fieldset[disabled] .btn-primary:focus,fieldset[disabled] .btn-primary:hover{background-color:#11203A;border-color:#1bbae1;color:#fff}.btn-danger{background-color:#ef8a80;border-color:#e74c3c;color:#fff}.btn-danger.btn-alt{background-color:#fff;color:#e74c3c}.btn-danger:hover{background-color:#e74c3c;border-color:#9c3428;color:#fff}.btn-danger.active,.btn-danger.disabled,.btn-danger.disabled.active,.btn-danger.disabled:active,.btn-danger.disabled:focus,.btn-danger.disabled:hover,.btn-danger:active,.btn-danger:focus,.btn-danger[disabled].active,.btn-danger[disabled]:active,.btn-danger[disabled]:focus,.btn-danger[disabled]:hover,.open .btn-danger.dropdown-toggle,fieldset[disabled] .btn-danger.active,fieldset[disabled] .btn-danger:active,fieldset[disabled] .btn-danger:focus,fieldset[disabled] .btn-danger:hover{background-color:#e74c3c;border-color:#e74c3c;color:#fff}.btn-warning{background-color:#f7be64;border-color:#f39c12;color:#fff}.btn-warning.btn-alt{background-color:#fff;color:#f39c12}.btn-warning:hover{background-color:#f39c12;border-color:#b3730c;color:#fff}.btn-warning.active,.btn-warning.disabled,.btn-warning.disabled.active,.btn-warning.disabled:active,.btn-warning.disabled:focus,.btn-warning.disabled:hover,.btn-warning:active,.btn-warning:focus,.btn-warning[disabled].active,.btn-warning[disabled]:active,.btn-warning[disabled]:focus,.btn-warning[disabled]:hover,.open .btn-warning.dropdown-toggle,fieldset[disabled] .btn-warning.active,fieldset[disabled] .btn-warning:active,fieldset[disabled] .btn-warning:focus,fieldset[disabled] .btn-warning:hover{background-color:#f39c12;border-color:#f39c12;color:#fff}.btn-success{background-color:#aad178;border-color:#7db831;color:#fff}.btn-success.btn-alt{background-color:#fff;color:#7db831}.btn-success:hover{background-color:#7db831;border-color:#578022;color:#fff}.btn-success.active,.btn-success.disabled,.btn-success.disabled.active,.btn-success.disabled:active,.btn-success.disabled:focus,.btn-success.disabled:hover,.btn-success:active,.btn-success:focus,.btn-success[disabled].active,.btn-success[disabled]:active,.btn-success[disabled]:focus,.btn-success[disabled]:hover,.open .btn-success.dropdown-toggle,fieldset[disabled] .btn-success.active,fieldset[disabled] .btn-success:active,fieldset[disabled] .btn-success:focus,fieldset[disabled] .btn-success:hover{background-color:#7db831;border-color:#7db831;color:#fff}.btn-info{background-color:#7abce7;border-color:#3498db;color:#fff}.btn-info.btn-alt{background-color:#fff;color:#3498db}.btn-info:hover{background-color:#3498db;border-color:#2875a8;color:#fff}.btn-info.active,.btn-info.disabled,.btn-info.disabled.active,.btn-info.disabled:active,.btn-info.disabled:focus,.btn-info.disabled:hover,.btn-info:active,.btn-info:focus,.btn-info[disabled].active,.btn-info[disabled]:active,.btn-info[disabled]:focus,.btn-info[disabled]:hover,.open .btn-info.dropdown-toggle,fieldset[disabled] .btn-info.active,fieldset[disabled] .btn-info:active,fieldset[disabled] .btn-info:focus,fieldset[disabled] .btn-info:hover{background-color:#3498db;border-color:#3498db;color:#fff}.btn-link,.btn-link.btn-icon:focus,.btn-link.btn-icon:hover,.btn-link:focus,.btn-link:hover{color:#1bbae1}.btn-link.btn-icon{color:#999}.btn-link.btn-icon:focus,.btn-link.btn-icon:hover{text-decoration:none}.block-options .btn{border-radius:15px;padding-right:8px;padding-left:8px;min-width:30px;text-align:center}.panel{margin-bottom:20px}.panel-heading{padding:15px}.panel-title{font-size:14px}.panel-default>.panel-heading{background-color:#f9f9f9}.panel-group{margin-bottom:20px}pre{background:#151515;overflow:scroll}code{border:1px solid #fad4df;margin:1px 0;display:inline-block}.btn code{display:inline;margin:0}.alert{border-top-width:0;border-right-width:2px;border-bottom-width:0;border-left-width:2px}.alert-danger{color:#e74c3c;background-color:#ffd1cc;border-color:#ffb8b0}.alert-danger .alert-link{color:#e74c3c}.alert-warning{color:#e67e22;background-color:#ffe4cc;border-color:#ffd6b2}.alert-warning .alert-link{color:#e67e22}.alert-success{color:#27ae60;background-color:#daf2e4;border-color:#b8e5cb}.alert-success .alert-link{color:#27ae60}.alert-info{color:#3498db;background-color:#dae8f2;border-color:#b8d2e5}.alert-info .alert-link{color:#3498db}.alert-dismissable .close{top:-5px;right:-25px}.close{text-shadow:none}.alert.alert-alt{margin:0 0 2px;padding:5px;font-size:12px;border-width:0;border-left-width:2px}.alert.alert-alt small{opacity:.75;filter:alpha(opacity=75)}.alert-alt.alert-dismissable .close{right:0}.alert-alt.alert-dismissable .close:hover{color:#fff}.alert-danger.alert-alt{border-color:#e74c3c}.alert-warning.alert-alt{border-color:#e67e22}.alert-success.alert-alt{border-color:#27ae60}.alert-info.alert-alt{border-color:#3498db}.sidebar-content .alert.alert-alt{margin-left:-10px;padding-left:15px;background:0 0;color:#fff}.badge,.label{font-weight:400;font-size:90%}.label{padding:1px 4px}.badge{background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3);padding:3px 6px}.label-danger{background-color:#e74c3c}.label-danger[href]:focus,.label-danger[href]:hover{background-color:#ff5542}.label-warning{background-color:#e67e22}.label-warning[href]:focus,.label-warning[href]:hover{background-color:#ff8b26}.label-success{background-color:#27ae60}.label-success[href]:focus,.label-success[href]:hover{background-color:#2cc76c}.label-info{background-color:#2980b9}.label-info[href]:focus,.label-info[href]:hover{background-color:#2f92d4}.label-primary{background-color:#1bbae1}.label-primary[href]:focus,.label-primary[href]:hover{background-color:#5ac5e0}.label-default{background-color:#999}.label-default[href]:focus,.label-default[href]:hover{background-color:#777}.carousel-control.left,.carousel-control.left.no-hover:hover,.carousel-control.right,.carousel-control.right.no-hover:hover{background:0 0}.carousel-control.left:hover,.carousel-control.right:hover{background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.carousel-control span{position:absolute;top:50%;left:50%;z-index:5;display:inline-block}.carousel-control i{width:20px;height:20px;margin-top:-10px;margin-left:-10px}.alert,.carousel,.table,p{margin-bottom:20px}.btn.active,.form-control,.form-control:focus,.has-error .form-control:focus,.has-success .form-control:focus,.has-warning .form-control:focus,.navbar-form-custom .form-control:focus,.navbar-form-custom .form-control:hover,.open .btn.dropdown-toggle,.panel,.popover,.progress,.progress-bar{-webkit-box-shadow:none;box-shadow:none}.alert.alert-alt,.breadcrumb,.dropdown-menu,.navbar,.navbar-form-custom .form-control,.tooltip-inner{border-radius:0}.push-bit{margin-bottom:10px!important}.push{margin-bottom:15px!important}.push-top-bottom{margin-top:40px;margin-bottom:40px}.lt-ie9 .hidden-lt-ie9{display:none!important}.display-none{display:none}.remove-margin{margin:0!important}.remove-padding{padding:0!important}.remove-radius{border-radius:0!important}.remove-box-shadow{-webkit-box-shadow:none!important;box-shadow:none!important}.remove-transition{-moz-transition:none!important;-webkit-transition:none!important;transition:none!important}:focus{outline:0!important}.style-alt #page-content{background-color:#fff}.style-alt .block{border-color:#dbe1e8}.style-alt .block.block-alt-noborder{border-color:transparent}.style-alt .block-title{background-color:#dbe1e8;border-bottom-color:#dbe1e8}.style-alt #page-content+footer,.style-alt .breadcrumb-top+.content-header,.style-alt .content-header+.breadcrumb-top{background-color:#f9fafc}.style-alt .breadcrumb-top,.style-alt .content-header{border-bottom-color:#eaedf1}.style-alt #page-content+footer{border-top-color:#eaedf1}.style-alt .widget{background-color:#f6f6f6}.test-circle{display:inline-block;width:100px;height:100px;line-height:100px;font-size:18px;font-weight:600;text-align:center;border-radius:50px;background-color:#eee;border:2px solid #ccc;color:#fff;margin-bottom:15px}.themed-color{color:#1bbae1}.themed-border{border-color:#1bbae1}.themed-background{background-color:#1bbae1}.themed-color-dark{color:#394263}.themed-border-dark{border-color:#394263}.themed-background-dark{background-color:#394263}@media screen and (min-width:768px){#login-background{height:400px}#login-background>img{top:0}#login-container{width:480px;top:186px;margin-left:-240px}#main-container{min-width:768px}#page-content{padding:10px 10px 1px}#page-content+footer,.block,.block.full,.breadcrumb-top,.header-section,.modal-body{padding-left:20px;padding-right:20px}.block .block-content-full{margin:-20px -20px -1px}.block.full .block-content-full{margin:-20px}.breadcrumb-top,.content-header,.content-top{margin:-20px -20px 20px}.breadcrumb-top+.content-header,.content-header+.breadcrumb-top{margin-top:-21px}.block,.widget{margin-bottom:20px}.block-title,.block-top,.form-bordered{margin-left:-20px;margin-right:-20px}.form-bordered .form-group{padding-left:20px;padding-right:20px}.form-horizontal.form-bordered .form-group{padding-left:5px;padding-right:5px}.nav-tabs>li>a{padding-left:15px;padding-right:15px;margin-left:3px;margin-right:3px}}@media (min-width:992px){#sidebar{-webkit-transition:opacity .5s linear,background-color .2s ease-out;transition:opacity .5s linear,background-color .2s ease-out}#main-container,.header-fixed-bottom header,.header-fixed-top header{-webkit-transition:none;transition:none}#sidebar{width:65px!important;opacity:.2;filter:alpha(opacity=20)}#main-container{margin-left:65px!important}.sidebar-brand i{display:none}#sidebar:hover,.sidebar-full #sidebar{width:220px!important;opacity:1;filter:alpha(opacity=100)}#sidebar:hover .sidebar-brand i,.sidebar-full #sidebar .sidebar-brand i{display:inline-block}#sidebar:hover+#main-container,.sidebar-full #main-container{margin-left:220px!important}.sidebar-open header.navbar-fixed-bottom,.sidebar-open header.navbar-fixed-top,header.navbar-fixed-bottom,header.navbar-fixed-top{left:65px}#sidebar:hover+#main-container header.navbar-fixed-bottom,#sidebar:hover+#main-container header.navbar-fixed-top,.sidebar-full header.navbar-fixed-bottom,.sidebar-full header.navbar-fixed-top{left:220px}}@media (min-width:1200px){.header-fixed-bottom .sidebar-content,.header-fixed-top .sidebar-content{padding-bottom:0}article p{font-size:19px;line-height:1.9}}';
                    $cuerpo .= '.timeline-icon2{position:position;width:30px;height:30px;line-height:28px;font-size:14px;text-align:center;background-color:#B4FF30;border:1px solid #B4FF30;border-radius:15px;z-index:500}';
                    $cuerpo .= '<style type="text/css">';
                    //$cuerpo .= '<!--';
                    $cuerpo .= '#aCerrarVentana{';
                    $cuerpo .= 'color: #FFFFFF;';
                    $cuerpo .= 'border: 2px #ff0a03 solid;';
                    $cuerpo .= 'padding: 5px 20px 5px 20px;';
                    $cuerpo .= 'background-color: #ff572b;';
                    $cuerpo .= 'font-family: Comic Sans MS, Calibri, Arial;';
                    $cuerpo .= 'font-size: 12px;';
                    $cuerpo .= 'font-weight: bold;';
                    $cuerpo .= 'text-decoration: none;';
                    $cuerpo .= 'background-repeat: no-repeat;';
                    $cuerpo .= 'border-radius: 15px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#divCabeceraMensaje {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#divPieMensaje {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= '} ';
                    //$cuerpo .= '-->';
                    $cuerpo .= '</style>';
                    $cuerpo .= '</head>';
                    $cuerpo .= '<body>';
                    if ($idControlExcepcion > 0) {
                        $nombreSolicitante = "";
                        $cargoSolicitante = "";
                        $departamentoSolicitante = "";
                        $gerenciaSolicitante = "";
                        $fechaIni = "";
                        $fechaFin = "";
                        $horaIni = "";
                        $horaFin = "";

                        $objRel = new Frelaborales();
                        $relaboralSolicitante = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralSolicitante);
                        if (is_object($relaboralSolicitante)) {
                            $nombreSolicitante = $relaboralSolicitante->nombres;
                            $cargoSolicitante = utf8_decode($relaboralSolicitante->cargo);
                            $departamentoSolicitante = utf8_decode($relaboralSolicitante->departamento_administrativo);
                            $gerenciaSolicitante = utf8_decode($relaboralSolicitante->gerencia_administrativa);
                        }
                        /**
                         * Sólo debiera hacerse la actualización si el registro se encuentra en estado SOLICITADO, en caso contrario
                         * no se debe hacer nada.
                         */
                        if (is_object($controlexcepcion)) {
                            $fechaInicio = $controlexcepcion->fecha_ini != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_ini)) : "";
                            $fechaFin = $controlexcepcion->fecha_fin != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_fin)) : "";
                            $cantidadDiasTranscurridos = $this->compararFechas($ahora, $fechaInicio);
                            $cantidadDeDiasAdmitidos = 2;
                            $parametro = Parametros::findFirst("parametro LIKE 'CANTIDAD_DIAS_PERMITIDOS_PARA_OPERACION_CONTROLEXCEPCION'");
                            if (is_object($parametro)) {
                                $cantidadDeDiasAdmitidos = $parametro->nivel;
                            }
                            $ok = true;
                            if ($ok) {
                                $hoy = date("d-m-Y H:i:s");
                                $cuerpo .= '<div style="min-height: 1020px;" id="page-content">';
                                $cuerpo .= '<div class="row">';
                                $cuerpo .= '<div class="col-md-2">';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="col-md-8">';
                                $cuerpo .= '<div class="block">';
                                $cuerpo .= '<h3>FORMULARIO DE CONTROL DE SALIDAS Y EXCEPCIONES<br><small id="smallRecomendacion"> C&oacute;digo:' . $prefijo . $objCorr->numero . "-" . $gestion . '</small></h3>';
                                $cuerpo .= '<form action="" method="post" class="form-bordered" onsubmit="return false;">';
                                $cuerpo .= '<div class="form-group">';
                                $cuerpo .= '<div class="col-md-3">';
                                $cuerpo .= '<span>Solicitante:</span>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="col-md-9">';
                                $cuerpo .= $nombreSolicitante;
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';

                                $cuerpo .= '<div class="form-group">';
                                $cuerpo .= '<div class="col-md-3">';
                                $cuerpo .= '<span>Cargo:</span>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="col-md-9">';
                                $cuerpo .= $cargoSolicitante;
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';

                                if ($departamentoSolicitante != '') {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>- </span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $cuerpo .= utf8_decode($departamentoSolicitante);
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';
                                }
                                if ($gerenciaSolicitante != '') {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>- </span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $cuerpo .= utf8_decode($gerenciaSolicitante);
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';
                                }
                                $cuerpo .= '<div class="form-group">';
                                $cuerpo .= '<div class="col-md-3">';
                                $cuerpo .= '<span>Aplicaci&oacute;n:</span>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="col-md-9">';
                                $cuerpo .= $controlexcepcion->excepcion;
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';

                                $cuerpo .= '<div class="form-group">';
                                $cuerpo .= '<div class="col-md-3">';
                                $cuerpo .= '<span>Tipo:</span>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="col-md-9">';
                                $cuerpo .= $controlexcepcion->tipo_excepcion;
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';

                                if ($controlexcepcion->observacion != "" && $controlexcepcion->observacion != null) {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>Descripci&oacute;n:</span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $cuerpo .= $controlexcepcion->observacion;
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';
                                }

                                /**
                                 * Se busca la reglamentación en el RIP para la excepción
                                 */
                                $objExRip = Excepcionesrip::findFirst("excepcion_id=" . $controlexcepcion->excepcion_id);
                                if (is_object($objExRip)) {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>Reglamento Interno de Personal (R. I. P.):</span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $rip = "";
                                    if ($objExRip->articulo != "" && $objExRip->articulo != null) {
                                        $rip .= "Art. " . $objExRip->articulo . "; ";
                                    }
                                    if ($objExRip->inciso != "" && $objExRip->inciso != null) {
                                        $rip .= "Inc. " . $objExRip->inciso . ";";
                                    }
                                    if (trim($rip) == "") {
                                        $cuerpo .= '&nbsp;';
                                    } else {
                                        $cuerpo .= $rip;
                                    }
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';
                                }
                                if ($controlexcepcion->lugar == 1) {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>Motivo:</span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $cuerpo .= $controlexcepcion->justificacion;
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';

                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>Lugar:</span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $cuerpo .= $controlexcepcion->destino;
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';
                                } else {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>Justificaci&oacute;n:</span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $cuerpo .= $controlexcepcion->justificacion;
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';
                                }

                                if ($fechaInicio != '' && $fechaFin != '') {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>Fecha(s):</span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';

                                    if ($fechaInicio != $fechaFin) {

                                        $cuerpo .= $fechaInicio . ' al ' . $fechaFin;
                                    } else {
                                        $cuerpo .= $fechaInicio;
                                    }
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';

                                }
                                if ($controlexcepcion->hora_ini != '' && $controlexcepcion->hora_fin != '') {
                                    $cuerpo .= '<div class="form-group">';
                                    $cuerpo .= '<div class="col-md-3">';
                                    $cuerpo .= '<span>Horario:</span>';
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '<div class="col-md-9">';
                                    $cuerpo .= $controlexcepcion->hora_ini . ' a ' . $controlexcepcion->hora_fin;
                                    $cuerpo .= '</div>';
                                    $cuerpo .= '</div>';
                                }

                                $cuerpo .= '<div class="form-group">';
                                $cuerpo .= '<div class="col-md-3">';
                                $cuerpo .= '<span>Estado:</span>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="col-md-9">';
                                $cuerpo .= $controlexcepcion->controlexcepcion_estado_descripcion;
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="form-group">';
                                $cuerpo .= '<div class="col-md-3">';
                                $cuerpo .= '<span>Observaci&oacute;n:</span>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '<div class="col-md-9">';
                                $cuerpo .= $controlexcepcion->controlexcepcion_observacion;
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';

                                if ($controlexcepcion->controlexcepcion_user_ver_id > 0) {
                                    $verificador = Usuarios::findFirstById($controlexcepcion->controlexcepcion_user_ver_id);
                                    if (is_object($verificador)) {
                                        $relaboralVerificador = $objR->getOneRelaboralConsiderandoUltimaMovilidadPorPersona($verificador->persona_id);
                                        $cuerpo .= '<fieldset><legend><i class="fa fa-angle-right"></i> &raquo; Datos Verificaci&oacute;n</legend>';
                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        $cuerpo .= '<span>Verificador:</span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $cuerpo .= $controlexcepcion->controlexcepcion_user_verificador;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';

                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        $cuerpo .= '<span>Cargo:</span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $cuerpo .= $relaboralVerificador->cargo;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';

                                        if ($relaboralVerificador->departamento_administrativo != '' && $relaboralVerificador->departamento_administrativo != null) {
                                            $cuerpo .= '<div class="form-group">';
                                            $cuerpo .= '<div class="col-md-3">';
                                            $cuerpo .= '<span>- </span>';
                                            $cuerpo .= '</div>';
                                            $cuerpo .= '<div class="col-md-9">';
                                            $cuerpo .= $relaboralVerificador->departamento_administrativo;
                                            $cuerpo .= '</div>';
                                            $cuerpo .= '</div>';
                                        }

                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        $cuerpo .= '<span>- </span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $cuerpo .= $relaboralVerificador->gerencia_administrativa;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';

                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        if ($controlexcepcion->controlexcepcion_estado == -1) $cuerpo .= '<span>Fecha Rechazo Verificaci&oacute;n:</span>';
                                        else $cuerpo .= '<span>Fecha Verificaci&oacute;n:</span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $fechaVerificacion = $controlexcepcion->controlexcepcion_fecha_ver != "" ? date("d-m-Y H:i:s", strtotime($controlexcepcion->controlexcepcion_fecha_ver)) : "";
                                        $cuerpo .= $fechaVerificacion;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';


                                        $cuerpo .= '</fieldset>';
                                    }
                                }
                                if ($controlexcepcion->controlexcepcion_user_apr_id > 0) {
                                    $aprobador = Usuarios::findFirstById($controlexcepcion->controlexcepcion_user_apr_id);
                                    if (is_object($aprobador)) {
                                        $relaboralAprobador = $objR->getOneRelaboralConsiderandoUltimaMovilidadPorPersona($aprobador->persona_id);
                                        $cuerpo .= '<fieldset><legend><i class="fa fa-angle-right"></i> &raquo; Datos Aprobaci&oacute;n</legend>';
                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        $cuerpo .= '<span>Aprobador:</span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $cuerpo .= $controlexcepcion->controlexcepcion_user_aprobador;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';

                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        $cuerpo .= '<span>Cargo:</span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $cuerpo .= $relaboralAprobador->cargo;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';

                                        if ($relaboralAprobador->departamento_administrativo != '' && $relaboralAprobador->departamento_administrativo != null) {
                                            $cuerpo .= '<div class="form-group">';
                                            $cuerpo .= '<div class="col-md-3">';
                                            $cuerpo .= '<span>- </span>';
                                            $cuerpo .= '</div>';
                                            $cuerpo .= '<div class="col-md-9">';
                                            $cuerpo .= $relaboralAprobador->departamento_administrativo;
                                            $cuerpo .= '</div>';
                                            $cuerpo .= '</div>';
                                        }

                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        $cuerpo .= '<span>- </span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $cuerpo .= $relaboralAprobador->gerencia_administrativa;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';

                                        $cuerpo .= '<div class="form-group">';
                                        $cuerpo .= '<div class="col-md-3">';
                                        if ($controlexcepcion->controlexcepcion_estado == -2) $cuerpo .= '<span>Fecha Rechazo Aprobaci&oacute;n:</span>';
                                        else $cuerpo .= '<span>Fecha Aprobaci&oacute;n:</span>';
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '<div class="col-md-9">';
                                        $fechaAprobacion = $controlexcepcion->controlexcepcion_fecha_apr != "" ? date("d-m-Y H:i:s", strtotime($controlexcepcion->controlexcepcion_fecha_apr)) : "";
                                        $cuerpo .= $fechaAprobacion;
                                        $cuerpo .= '</div>';
                                        $cuerpo .= '</div>';

                                        $cuerpo .= '</fieldset>';
                                    }
                                }
                                $cuerpo .= '</br>';
                                $cuerpo .= '<div class="form-group form-actions">';
                                $cuerpo .= '<button id="btnCerrarVentana" type="button" class="btn btn-sm btn-danger">Cerrar</button>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '</form>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';
                                $cuerpo .= '</div>';
                            }


                        }
                    }
                    $cuerpo .= '<br>';
                    $cuerpo .= '<script type="text/javascript">';
                    $cuerpo .= '
                        $().ready(function() {
                          $("#btnCerrarVentana").on("click",function(){
                            close();
                            });
                        });
                        ';
                    $cuerpo .= '</script>';
                    $cuerpo .= '</body>';
                    $cuerpo .= '</html>';
                }
            }
            echo $cuerpo;
        } else {
            /**
             * En caso de que no se envíe el valor para el identificador del registro de control de excepción.
             */
            header("Location: ../index");
        }
    }

    /**
     * Función para el envío de mensajes de correo electrónico de manera externa.
     * @throws Exception
     * @throws phpmailerException
     */
    public function enviomensajeexterno($idRelaboralSolicitante, $idRelaboralDestinatarioPrincipal, $idRelaboralDestinatarioSecundario, $idControlExcepcion, $mensajeAdicional, $operacion)
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $fechaYHoraEnvio = date("d-m-Y H:i:s");
        $ok = false;
        $operacionSolicitada = 0;
        $aceptarSolicitud = "";
        $estadoVerificacionSolicitada = 3;
        $estadoAprobacionSolicitada = 4;
        $estadoErrorEnSolicitudVerificacion = -3;
        $estadoErrorEnSolicitudAprobacion = -4;
        $estadoVerificado = 5;
        $estadoAprobado = 6;
        $estadoAprobacionRechazada = -2;

        $param = Parametros::findFirst(array("parametro LIKE 'RUTA_APLICACION' AND estado=1 AND baja_logica=1"));
        $ruta = 'http://rrhh.local/controlexcepcionesvistobueno/vistobueno/';
        if (is_object($param)) {
            $ruta = 'http://' . $param->nivel . '/controlexcepcionesvistobueno/vistobueno/';
        }
        $estadoOperacionSolicitada = 0;
        $estadoOperacionSolicitadaRechaada = 0;
        /**
         * La operación de solicitud puede ser producto de una previa VERIFICACIÓN
         */
        if ($operacion == 0 || $operacion == 2) {
            $operacionSolicitada = "APROBACIÓN";
            $estadoPreOperacionSolicitada = 4;
            $estadoOperacionSolicitada = 6;
            $estadoOperacionSolicitadaRechazada = -2;
            $estadoOperacionSolicitadaError = -4;
            $aceptarSolicitud = 'Aprobar Solicitud';
        } else {
            $operacionSolicitada = "VERIFICACIÓN";
            $estadoPreOperacionSolicitada = 3;
            $estadoOperacionSolicitada = 5;
            $estadoOperacionSolicitadaRechazada = -1;
            $estadoOperacionSolicitadaError = -3;
            $aceptarSolicitud = 'Verificar Solicitud';
        }


        $nombreDestinatario = '';
        $cargoDestinatario = '';
        $departamentoDestinatario = '';
        $gerenciaDestinatario = '';

        $nombreDestinatarioSecundario = '';
        $cargoDestinatarioSecundario = '';
        $departamentoDestinatarioSecundario = '';
        $gerenciaDestinatarioSecundario = '';


        /*$estadoSolicitado = $estadoAprobado;
        $estadoSolicitudRechazada = $estadoAprobacionRechazada;*/

        $objRel = new Frelaborales();
        $relaboralSolicitante = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralSolicitante);

        $relaboralDestinatarioPrincipal = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralDestinatarioPrincipal);
        $nombreDestinatario = utf8_decode($relaboralDestinatarioPrincipal->nombres);
        $cargoDestinatario = utf8_decode($relaboralDestinatarioPrincipal->cargo);
        $departamentoDestinatario = utf8_decode($relaboralDestinatarioPrincipal->departamento_administrativo);
        $gerenciaDestinatario = utf8_decode($relaboralDestinatarioPrincipal->gerencia_administrativa);
        $relaboralDestinatarioSecundario = null;
        if ($idRelaboralDestinatarioSecundario > 0) {
            $relaboralDestinatarioSecundario = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralDestinatarioSecundario);
            $nombreDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->nombres);
            $cargoDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->cargo);
            $departamentoDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->departamento_administrativo);
            $gerenciaDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->gerencia_administrativa);
        }

        $objCEx = new Fcontrolexcepciones();
        $controlexcepcion = $objCEx->getOne($idControlExcepcion);
        $excepcionrip = null;
        if (is_object($controlexcepcion)) {
            $excepcionrip = Excepcionesrip::findFirst(array("excepcion_id=" . $controlexcepcion->excepcion_id . " AND estado=1 AND baja_logica=1"));
        }
        /**
         * Sólo se admite el envío del mensaje en caso de que el control de excepción este en ELABORACIÓN O ELABORADO
         * (Este último caso para cuando se deba reenviar el mensaje)
         */
        if (is_object($controlexcepcion) && is_object($relaboralSolicitante)) {
            //$usuarioRemitente = Usuarios::findFirst(array("persona_id='".$relaboral->persona_id."'"));
            $contactoRemitente = Personascontactos::findFirst(array("persona_id='" . $relaboralSolicitante->id_persona . "'"));
            $contactoDestinatarioPrincipal = Personascontactos::findFirst(array("persona_id='" . $relaboralDestinatarioPrincipal->id_persona . "'"));
            $contactoDestinatarioSecundario = null;
            if ($idRelaboralDestinatarioSecundario > 0) {
                $contactoDestinatarioSecundario = Personascontactos::findFirst(array("persona_id='" . $relaboralDestinatarioSecundario->id_persona . "'"));
            }

            if (is_object($contactoRemitente)) {
                /**
                 * Se admite el envío de solicitudes para registros en estado EN ELABORACIÓN, ELABORADO, VERIFICACIÓN SOLICITADA Y APROBACIÓN SOLICITADA
                 * Las dos últimas opciones debido a que se plantea la necesidad de enviar nuevamente en caso de que el mensaje se haya eliminado en la
                 * Bandeja de Entrada del Destinatario.
                 */
                if ($controlexcepcion->controlexcepcion_estado == 1
                    || $controlexcepcion->controlexcepcion_estado == 2
                    || $controlexcepcion->controlexcepcion_estado == 3
                    || $controlexcepcion->controlexcepcion_estado == 4
                    || $controlexcepcion->controlexcepcion_estado == 5
                    /**
                     * En caso de que se esté intentando enviar nuevamente un correo con error técnico al momento del envío
                     */
                    || $controlexcepcion->controlexcepcion_estado == -3
                    || $controlexcepcion->controlexcepcion_estado == -4
                ) {

                    #region Registro del envío
                    /**
                     * Inicialmente se registra el estado previo
                     */
                    $ce = Controlexcepciones::findFirstById($idControlExcepcion);
                    $ce->estado = $estadoPreOperacionSolicitada;
                    $ce->user_mod_id = $user_mod_id;
                    $ce->fecha_mod = $hoy;
                    $ce->save();
                    #endregion Registro del envío

                    $mensajeCabecera = "Estimad@ Usuario:<br>";
                    $mensajeCabecera .= "Se ha solicitado la <b>" . utf8_decode($operacionSolicitada) . "</b> de aplicaci&oacute;n de Excepci&oacute;n con el siguiente detalle: ";
                    $mensajePie = "Atte.,<br>";
                    $mensajePie .= "<b>Unidad de Administraci&oacute;n y Recursos Humanos<br>";
                    $mensajePie .= "DAF<br>";
                    $mensajePie .= "- VIAS BOLIVIA -</b><br>";
                    $nombreSolicitante = "";
                    $cargoSolicitante = "";
                    $departamentoSolicitante = "";
                    $gerenciaSolicitante = "";
                    $fechaIni = "";
                    $fechaFin = "";
                    $horaIni = "";
                    $horaFin = "";
                    $mostrarHorario = 0;
                    if (is_object($relaboralSolicitante)) {
                        $nombreSolicitante = $relaboralSolicitante->nombres;
                        $cargoSolicitante = utf8_decode($relaboralSolicitante->cargo);
                        $departamentoSolicitante = utf8_decode($relaboralSolicitante->departamento_administrativo);
                        $gerenciaSolicitante = utf8_decode($relaboralSolicitante->gerencia_administrativa);
                    }
                    if (is_object($controlexcepcion)) {
                        $excepcion = $controlexcepcion->excepcion;
                        $justificacion = $controlexcepcion->justificacion;
                        $fechaIni = $controlexcepcion->fecha_ini != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_ini)) : "";
                        $fechaFin = $controlexcepcion->fecha_fin != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_fin)) : "";
                        $horaIni = $controlexcepcion->hora_ini;
                        $horaFin = $controlexcepcion->hora_fin;
                        $mostrarHorario = $controlexcepcion->horario;
                    }
                    $idRelaboralSolicitanteCodificado = rtrim(strtr(base64_encode($idRelaboralSolicitante), '+/', '-_'), '=');
                    $idRelaboralDestinatarioPrincipalCodificado = rtrim(strtr(base64_encode($idRelaboralDestinatarioPrincipal), '+/', '-_'), '=');
                    $idRelaboralDestinatarioSecundarioCodificado = rtrim(strtr(base64_encode(0), '+/', '-_'), '=');
                    $idControlExcepcionCodificado = rtrim(strtr(base64_encode($idControlExcepcion), '+/', '-_'), '=');
                    $estadoOperacionSolicitadaCodificado = rtrim(strtr(base64_encode($estadoOperacionSolicitada), '+/', '-_'), '=');
                    $estadoOperacionSolicitadaRechazadaCodificado = rtrim(strtr(base64_encode($estadoOperacionSolicitadaRechazada), '+/', '-_'), '=');
                    $estadoOperacionSolicitadaErrorCodificado = rtrim(strtr(base64_encode($estadoOperacionSolicitadaError), '+/', '-_'), '=');
                    $cuerpoCopia = '';
                    $cuerpo = '<html>';
                    $cuerpo .= '<head>';
                    $cuerpo .= '<title>Env&iacute;o de Solicitud</title>';
                    $cuerpo .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>';
                    $cuerpo .= '<style type="text/css">';
                    //$cuerpo .= '<!--';
                    $cuerpo .= '#datos {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= 'width:780px;';
                    $cuerpo .= 'left: 164px;';
                    $cuerpo .= 'top: 316px;';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #form1 table tr td {';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= 'font-weight: bold;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv2 {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= 'width:49px;';
                    $cuerpo .= 'height:45px;';
                    $cuerpo .= 'z-index:2;';
                    $cuerpo .= 'left: 12px;';
                    $cuerpo .= 'top: 11px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #notificacion table tr td {';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #notificacion table tr td {';
                    $cuerpo .= 'text-align: left;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #notificacion table tr td {';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= 'font-family: Arial, Helvetica, sans-serif;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv3 {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= 'width:833px;';
                    $cuerpo .= 'height:115px;';
                    $cuerpo .= 'z-index:1;';
                    $cuerpo .= 'left: 99px;';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= 'top: 16px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#aAprobarSolicitud{';
                    $cuerpo .= 'color: #FFFFFF;';
                    $cuerpo .= 'border: 2px #26dd5c solid;';
                    $cuerpo .= 'padding: 5px 20px 5px 20px;';
                    $cuerpo .= 'background-color: #34a853;';
                    $cuerpo .= 'font-family: Comic Sans MS, Calibri, Arial;';
                    $cuerpo .= 'font-size: 12px;';
                    $cuerpo .= 'font-weight: bold;';
                    $cuerpo .= 'text-decoration: none;';
                    $cuerpo .= 'background-repeat: no-repeat;';
                    $cuerpo .= 'border-radius: 15px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#aRechazarSolicitud{';
                    $cuerpo .= 'color: #FFFFFF;';
                    $cuerpo .= 'border: 2px #ff0a03 solid;';
                    $cuerpo .= 'padding: 5px 20px 5px 20px;';
                    $cuerpo .= 'background-color: #ff572b;';
                    $cuerpo .= 'font-family: Comic Sans MS, Calibri, Arial;';
                    $cuerpo .= 'font-size: 12px;';
                    $cuerpo .= 'font-weight: bold;';
                    $cuerpo .= 'text-decoration: none;';
                    $cuerpo .= 'background-repeat: no-repeat;';
                    $cuerpo .= 'border-radius: 15px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#divCabeceraMensaje {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#divPieMensaje {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= '} ';
                    //$cuerpo .= '-->';
                    $cuerpo .= '</style>';
                    $cuerpo .= '</head>';
                    $cuerpo .= '<body>';
                    $cuerpo .= '<div id="divCabeceraMensaje">';
                    $cuerpo .= $mensajeCabecera;
                    $cuerpo .= '</div>';
                    $cuerpo .= '<div id="apDiv3">';
                    $cuerpo .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td><table width="100%" border="0">';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td>';
                    $cuerpo .= '<p style="font-family: Helvetica LT Condensed; color: #3085ff; font-weight: bold; font-size: 15px; text-align: center;">SOLICITUD DE ' . utf8_decode($operacionSolicitada) . ' DE EXCEPCI&Oacute;N</p></td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Solicitante:</span>&nbsp; ' . $nombreSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Cargo:</span>&nbsp; ' . $cargoSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                    $cuerpo .= '</tr>';
                    if ($departamentoSolicitante != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $departamentoSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                    }
                    if ($gerenciaSolicitante != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $gerenciaSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                    }
                    /*                    $cuerpo .= '<tr>';
                                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Tipo de Excepci&oacute;n:</span>&nbsp; ' . $excepcion . '</td>';
                                        $cuerpo .= '</tr>';
                                        $cuerpo .= '<tr>';*/


                    /**
                     * En caso de tratarse de una comisión se mostrará el motivo y el lugar.
                     */
                    if ($controlexcepcion->lugar == 1) {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Motivo:</span>&nbsp; ' . $ce->justificacion . '</td>';
                        $cuerpo .= '</tr>';
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Lugar:</span>&nbsp; ' . $ce->destino . '</td>';
                        $cuerpo .= '</tr>';
                    } else {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Justificaci&oacute;n:</span>&nbsp; ' . $justificacion . '</td>';
                        $cuerpo .= '</tr>';
                    }
                    $excepcionrip = null;
                    if ($controlexcepcion->excepcion_id > 0) {
                        $excepcionrip = Excepcionesrip::findFirst(array("excepcion_id=" . $ce->excepcion_id . " AND estado=1 AND baja_logica=1"));
                    }

                    /**
                     * En caso de existir una justificacion regida al RIP del permiso.
                     */
                    if ($excepcionrip != null) {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Aplicaci&oacute;n R. I. P.:</span>&nbsp; Art. ' . $excepcionrip->articulo;
                        if ($excepcionrip->inciso != '' && $excepcionrip->inciso != null) {
                            $cuerpo .= '; Inc. ' . $excepcionrip->inciso . ";";
                        }
                        $cuerpo .= '</td>';
                        $cuerpo .= '</tr>';
                    }

                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Estado:</span>&nbsp; ' . $controlexcepcion->controlexcepcion_estado_descripcion . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                    $cuerpo .= '</tr>';

                    if ($fechaIni != '' && $fechaFin != '') {
                        $cuerpo .= '<tr>';
                        if ($fechaIni != $fechaFin) {
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fechas:</span>&nbsp; Del ' . $fechaIni . ' al ' . $fechaFin . '</td>';
                        } else {
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha:</span>&nbsp; ' . $fechaIni . '</td>';
                        }
                        $cuerpo .= '</tr>';
                    }
                    if ($mostrarHorario == 1) {
                        if ($horaIni != '' && $horaFin != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Horario:</span>&nbsp; ' . $horaIni . ' a ' . $horaFin . '</td>';
                            $cuerpo .= '</tr>';
                        }
                    }

                    if ($mensajeAdicional != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Mensaje Adicional:</span>&nbsp; ' . $mensajeAdicional . '</td>';
                        $cuerpo .= '</tr>';
                    }
                    /**
                     * En caso de que la aprobación provenga de una previa verificación
                     */
                    if ($operacion == 2 && $controlexcepcion->controlexcepcion_user_ver_id > 0) {
                        $usuarioVerificador = Usuarios::findFirstById($controlexcepcion->controlexcepcion_user_ver_id);
                        $relaboralVer = Relaborales::findFirst("estado>=1 AND persona_id=" . $usuarioVerificador->persona_id);
                        $relaboralVerificador = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($relaboralVer->id);
                        if (is_object($usuarioVerificador) && is_object($relaboralVer) && is_object($relaboralVerificador)) {
                            $fechaVerificacion = $controlexcepcion->controlexcepcion_fecha_ver != "" ? date("d-m-Y H:i:s", strtotime($controlexcepcion->controlexcepcion_fecha_ver)) : "";
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;">..............................................................................................................................................................................................................................</td>';
                            $cuerpo .= '</tr>';
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Verificador:</span>&nbsp; ' . $relaboralVerificador->nombres . '</td>';
                            $cuerpo .= '</tr>';
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Cargo Verificador:</span>&nbsp; ' . $relaboralVerificador->cargo . '</td>';
                            $cuerpo .= '</tr>';
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha y Hora Verificaci&oacute;n:</span>&nbsp; ' . $fechaVerificacion . '</td>';
                            $cuerpo .= '</tr>';
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;">..............................................................................................................................................................................................................................</td>';
                            $cuerpo .= '</tr>';
                        }
                    }
                    $cuerpoCopia = $cuerpo;

                    $cuerpoCopia .= '<tr>';
                    $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;">*******************************************************************************************************</td>';
                    $cuerpoCopia .= '</tr>';

                    $cuerpoCopia .= '<tr>';
                    $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Destinatario:</span>&nbsp; ' . $nombreDestinatario . '</td>';
                    $cuerpoCopia .= '</tr>';

                    $cuerpoCopia .= '<tr>';
                    $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Correo Destinatario:</span>&nbsp; ' . $contactoDestinatarioPrincipal->e_mail_inst . '</td>';
                    $cuerpoCopia .= '</tr>';

                    $cuerpoCopia .= '<tr>';
                    $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Cargo Destinatario:</span>&nbsp; ' . $cargoDestinatario . '</td>';
                    $cuerpoCopia .= '</tr>';

                    if ($departamentoDestinatario != '') {
                        $cuerpoCopia .= '<tr>';
                        $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $departamentoDestinatario . '</td>';
                        $cuerpoCopia .= '</tr>';
                    }

                    if ($gerenciaDestinatario != '') {
                        $cuerpoCopia .= '<tr>';
                        $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $gerenciaDestinatario . '</td>';
                        $cuerpoCopia .= '</tr>';
                    }
                    if ($nombreDestinatarioSecundario != '' && is_object($contactoDestinatarioSecundario)) {
                        $cuerpoCopia .= '<tr>';
                        $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Destinatario CC:</span>&nbsp; ' . $nombreDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpoCopia .= '</tr>';
                        $cuerpoCopia .= '<tr>';
                        $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Cargo Destinatario CC:</span>&nbsp; ' . $cargoDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpoCopia .= '</tr>';
                        if ($departamentoDestinatarioSecundario != '') {
                            $cuerpoCopia .= '<tr>';
                            $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">- CC:</span>&nbsp; ' . $departamentoDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                            $cuerpoCopia .= '</tr>';
                        }
                        if ($gerenciaDestinatarioSecundario != '') {
                            $cuerpoCopia .= '<tr>';
                            $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">- CC:</span>&nbsp; ' . $gerenciaDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                            $cuerpoCopia .= '</tr>';
                        }
                    }
                    if ($fechaYHoraEnvio != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha y Hora de Env&iacute;o (Actual Mensaje):</span>&nbsp; ' . $fechaYHoraEnvio . '</td>';
                        $cuerpo .= '</tr>';
                        $cuerpoCopia .= '<tr>';
                        $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha y Hora de Env&iacute;o (Actual Mensaje):</span>&nbsp; ' . $fechaYHoraEnvio . '</td>';
                        $cuerpoCopia .= '</tr>';
                    }
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td>';
                    $cuerpo .= '<p><span style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Opciones:</span>&nbsp;</span></p></td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '<tr><td>';
                    $cuerpo .= '<br>';
                    $cuerpo .= '<table width="100%"><tr><td style="text-align: right"><a href="' . $ruta . $idRelaboralSolicitanteCodificado . '/' . $idRelaboralDestinatarioPrincipalCodificado . '/' . $idRelaboralDestinatarioSecundarioCodificado . '/' . $idControlExcepcionCodificado . '/' . $estadoOperacionSolicitadaCodificado . '/" id="aAprobarSolicitud"  target="_blank">' . $aceptarSolicitud . '</a></td>';

                    $linkAceptacion = $ruta . $idRelaboralSolicitanteCodificado . '/' . $idRelaboralDestinatarioPrincipalCodificado . '/' . $idRelaboralDestinatarioSecundarioCodificado . '/' . $idControlExcepcionCodificado . '/' . $estadoOperacionSolicitadaCodificado . '/';

                    $cuerpo .= '<td style="text-align: left"><a href="' . $ruta . $idRelaboralSolicitanteCodificado . '/' . $idRelaboralDestinatarioPrincipalCodificado . '/' . $idRelaboralDestinatarioSecundarioCodificado . '/' . $idControlExcepcionCodificado . '/' . $estadoOperacionSolicitadaRechazadaCodificado . '/" id="aRechazarSolicitud"  target="_blank">Rechazar Solicitud</a></td></tr></table>';
                    $linkRechazo = $ruta . $idRelaboralSolicitanteCodificado . '/' . $idRelaboralDestinatarioPrincipalCodificado . '/' . $idRelaboralDestinatarioSecundarioCodificado . '/' . $idControlExcepcionCodificado . '/' . $estadoOperacionSolicitadaRechazadaCodificado . '/';

                    $cuerpo .= '<br>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '</table></td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '</table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br></br></br></br></br>' . $mensajePie . '</div></body></html>';
                    $cuerpoCopia .= '</table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br></br></br></br></br>' . $mensajePie . '</div></body></html>';

                    if ($idRelaboralDestinatarioPrincipal > 0) {
                        $parUser = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'USUARIO' AND estado=1 AND baja_logica=1"));
                        $userMail = '';
                        if (is_object($parUser)) {
                            $userMail = $parUser->valor_1;
                        }
                        $parPass = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'PASSWORD' AND estado=1 AND baja_logica=1"));
                        $passMail = '';
                        if (is_object($parPass)) {
                            $passMail = $parPass->valor_1;
                        }
                        $parHost = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'HOST' AND estado=1 AND baja_logica=1"));
                        $hostMail = '';
                        if (is_object($parHost)) {
                            $hostMail = $parHost->valor_1;
                        }
                        $parPort = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'PORT' AND estado=1 AND baja_logica=1"));
                        $portMail = '';
                        if (is_object($parPort)) {
                            $portMail = $parPort->valor_1;
                        }
                        if ($userMail != '' && $passMail != '' && $hostMail != '' && $portMail != '') {
                            /**
                             * Se verifica que no exista registro de solicitud de verificación.
                             */
                            $objCtrlExcepMsjes = Controlexcepcionesmensajes::findFirst("controlexcepcion_id = " . $idControlExcepcion . " AND controlexcepcion_estado > 0 AND baja_logica = 1");
                            if(is_object($objCtrlExcepMsjes)){
                                $objCtrlExcepMsjes->user_mod_id = $user_mod_id;
                                $objCtrlExcepMsjes->fecha_mod = $hoy;
                                $intentos = intval($objCtrlExcepMsjes->intentos) + 1;
                            }else{
                                $objCtrlExcepMsjes = new Controlexcepcionesmensajes();
                                $intentos = 1;
                                $objCtrlExcepMsjes->estado = 1;
                                $objCtrlExcepMsjes->baja_logica = 1;
                                $objCtrlExcepMsjes->agrupador = 0;
                                $objCtrlExcepMsjes->user_reg_id = $user_mod_id;
                                $objCtrlExcepMsjes->fecha_reg = $hoy;
                            }
                            $objCtrlExcepMsjes->controlexcepcion_id = $idControlExcepcion;
                            $objCtrlExcepMsjes->user_mail = $userMail;
                            $objCtrlExcepMsjes->relaboral_sol_id = $idRelaboralSolicitante;
                            $objCtrlExcepMsjes->user_sol_mail = $contactoRemitente->e_mail_inst;
                            $objCtrlExcepMsjes->relaboral_dest_id = $idRelaboralDestinatarioPrincipal;
                            $objCtrlExcepMsjes->user_dest_mail = $contactoDestinatarioPrincipal->e_mail_inst;
                            $objCtrlExcepMsjes->operacion_solicitada = 4;
                            $objCtrlExcepMsjes->medio = 1;
                            $objCtrlExcepMsjes->cuerpo_mensaje = utf8_encode($cuerpo);
                            #endregion Registro de historial de envios  (Parte 1 de 3)


                            $mail = new phpmaileroasis();
                            $mail->IsSMTP();
                            $mail->SMTPAuth = true;
                            $mail->SMTPSecure = "ssl";
                            $mail->Host = $hostMail;
                            $mail->Port = $portMail;
                            $mail->Username = $userMail;
                            $mail->Password = $passMail;
                            $mail->From = $userMail;
                            $mail->FromName = "Sistema de Recursos Humanos - VB";
                            $mail->Subject = utf8_decode("Solicitud de " . $operacionSolicitada . " de Excepcion");
                            $mail->MsgHTML($cuerpo);
                            $mail->AddAddress($contactoDestinatarioPrincipal->e_mail_inst, $relaboralDestinatarioPrincipal->nombres);
                            $mail->addCC($userMail, "SRRHH - VB");
                            $mail->IsHTML(true);
                            $mail->smtpConnect([
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                ]
                            ]);
                            if ($mail->Send()) {
                                /**
                                 * En caso de haberse enviado correctamente se envía una copia, pero sin considerar las opciones de aprobación
                                 */
                                $mailCopia = new phpmaileroasis();
                                $mailCopia->IsSMTP();
                                $mailCopia->SMTPAuth = true;
                                $mailCopia->SMTPSecure = "ssl";
                                $mailCopia->Host = $userMail;
                                $mailCopia->Port = $portMail;
                                $mailCopia->Username = $userMail;
                                $mailCopia->Password = $passMail;
                                $mailCopia->From = $userMail;
                                $mailCopia->FromName = "Sistema de Recursos Humanos - VB";
                                $mailCopia->Subject = utf8_decode("Copia de Solicitud " . $operacionSolicitada . " de Excepción");
                                $mailCopia->MsgHTML($cuerpoCopia);
                                $mailCopia->AddAddress($contactoRemitente->e_mail_inst, $relaboralSolicitante->nombres);
                                $mailCopia->addCC($userMail, "SRRHH - VB");
                                /**
                                 * En caso de haberse seleccionado el envío al inmediato superior, se envía una copia
                                 */
                                if ($idRelaboralDestinatarioSecundario > 0 && is_object($contactoDestinatarioSecundario)) {
                                    $mailCopia->AddCC($contactoDestinatarioSecundario->e_mail_inst, $relaboralDestinatarioSecundario->nombres);
                                }
                                $mailCopia->smtpConnect([
                                    'ssl' => [
                                        'verify_peer' => false,
                                        'verify_peer_name' => false,
                                        'allow_self_signed' => true
                                    ]
                                ]);
                                if ($mailCopia->Send()) {
                                    if (!is_object($contactoDestinatarioSecundario) && is_object($relaboralDestinatarioSecundario)) {
                                        $msj = array('result' => 1, 'msj' => 'Envio exitoso de solicitud a las cuentas,' . ' sin embargo, hubo problemas en el env&oacute; de la copia al destinatario secundario.', 'estado' => $controlexcepcion->controlexcepcion_estado);
                                    } else {
                                        $msj = array('result' => 1, 'msj' => 'Envio exitoso de solicitud a las cuentas:', 'estado' => $controlexcepcion->controlexcepcion_estado);
                                    }
                                } else {
                                    $msj = array('result' => 1, 'msj' => 'Envio exitoso de solicitud a las cuentas:', 'estado' => $controlexcepcion->controlexcepcion_estado);
                                }
                                #region Registro de historial de envios  (Parte 2 de 3)
                                /**
                                 * Debido a que se ha logrado enviar al destinatario principal se registra el envío y los links dispuestos.
                                 */
                                $objCtrlExcepMsjes->link_aceptacion = $linkAceptacion;
                                $objCtrlExcepMsjes->link_rechazo = $linkRechazo;
                                $objCtrlExcepMsjes->user_env_id = $user_mod_id;
                                $objCtrlExcepMsjes->fecha_env = $hoy;
                                #endregion Registro de historial de envios  (Parte 2 de 3)
                            } else {
                                #region Error en el envío
                                $ce = Controlexcepciones::findFirstById($idControlExcepcion);
                                $ce->user_mod_id = $user_mod_id;
                                $ce->fecha_mod = $hoy;
                                $ce->estado = $estadoOperacionSolicitadaError;
                                if ($ce->save()) {
                                    $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a inexistencia de la cuenta del destinatario o error en el Servidor de Correo. Se volvera a reenviar en 5 minutos.', 'estado' => -3);
                                } else {
                                    $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a inexistencia de la cuenta del destinatario o error en el Servidor de Correo. Consulte con el Administrador.', 'estado' => $controlexcepcion->controlexcepcion_estado);
                                }
                                #endregion Error en el envío
                            }
                            #region Registro de historial de envios  (Parte 3 de 3)
                            $objCtrlExcepMsjes->resultado = isset($msj["result"]) ? $msj["result"] : null;
                            $objCtrlExcepMsjes->mensaje = isset($msj["msj"]) ? $msj["msj"] : null;
                            $objCtrlExcepMsjes->controlexcepcion_estado = $ce->estado;
                            $objCtrlExcepMsjes->intentos = $intentos;
                            $objCtrlExcepMsjes->save();
                            #endregion Registro de historial de envios  (Parte 3 de 3)
                        } else {
                            $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a inexistencia de la cuenta del destinatario o error en el Servidor de Correo. Consulte con el Administrador.', 'estado' => $controlexcepcion->controlexcepcion_estado);
                        }
                    } else {
                        $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a que no existe la cuenta del destinatario principal.', 'estado' => $controlexcepcion->controlexcepcion_estado);
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'No se admite el env&iacute;o del mensaje de correo debido a que el registro ya se encuentra inhabilitado para la tarea solicitada (' . $controlexcepcion->controlexcepcion_estado_descripcion . ').', 'estado' => $controlexcepcion->controlexcepcion_estado);
                }
            }
        } else {
            $msj = array('result' => 0, 'msj' => 'No se encontr&oacute; el registro correspondiente de la excepci&oacute;n.');
        }
        return $msj;
    }

    /**
     * Función para el envío de correo ante una operación realizada sobre un registro de permiso.
     * @param $idControlExcepcion
     * @param $idRelaboralDestinatarioPrincipal
     * @param $idRelaboralDestinatarioSecundario
     * @param $estadoSolicitado
     * @param $hoy
     * @return array
     */
    public function enviarResultadoSolicitud($idControlExcepcion, $idRelaboralDestinatarioPrincipal, $idRelaboralDestinatarioSecundario, $estadoSolicitado, $hoy)
    {
        $this->view->disable();
        $idRelaboralSolicitante = $idRelaboralDestinatarioPrincipal;
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $fechaYHoraEnvio = date("d-m-Y H:i:s");
        $ok = false;
        $accionRealizada = "";
        $referenciaAccionRealizada = "";
        switch ($estadoSolicitado) {
            case 3:
            case 5:
                /**
                 * Verificado
                 */
                $accionRealizada = "SOLICITUD DE EXCEPCI&Oacute;N VERIFICADA";
                $referenciaAccionRealizada = "SOLICITUD DE EXCEPCIÓN VERIFICADA";
                break;
            case 6:
                /**
                 * Aprobar
                 */
                $accionRealizada = "SOLICITUD DE EXCEPCI&Oacute;N APROBADA";
                $referenciaAccionRealizada = "SOLICITUD DE EXCEPCIÓN APROBADA";
                break;
            case -1:
                /**
                 * Verificación rechazada
                 */
                $accionRealizada = "SOLICITUD DE VERIFICACI&Oacute;N RECHAZADA";
                $referenciaAccionRealizada = "SOLICITUD DE VERIFICACIÓN RECHAZADA";
                break;
            case -2:
                /**
                 * Aprobación rechazada
                 */
                $accionRealizada = "SOLICITUD DE APROBACI&Oacute;N RECHAZADA";
                $referenciaAccionRealizada = "SOLICITUD DE APROBACIÓN RECHAZADA";
                break;
        }


        $objRel = new Frelaborales();
        $relaboralDestinatarioPrincipal = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralDestinatarioPrincipal);
        $relaboralSolicitante = $relaboralDestinatarioPrincipal;
        $nombreDestinatario = utf8_decode($relaboralDestinatarioPrincipal->nombres);
        $cargoDestinatario = utf8_decode($relaboralDestinatarioPrincipal->cargo);
        $departamentoDestinatario = utf8_decode($relaboralDestinatarioPrincipal->departamento_administrativo);
        $gerenciaDestinatario = utf8_decode($relaboralDestinatarioPrincipal->gerencia_administrativa);
        $relaboralDestinatarioSecundario = null;
        if ($idRelaboralDestinatarioSecundario > 0) {
            $relaboralDestinatarioSecundario = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($idRelaboralDestinatarioSecundario);
            $nombreDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->nombres);
            $cargoDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->cargo);
            $departamentoDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->departamento_administrativo);
            $gerenciaDestinatarioSecundario = utf8_decode($relaboralDestinatarioSecundario->gerencia_administrativa);
        }

        $objCEx = new Fcontrolexcepciones();
        $controlexcepcion = $objCEx->getOne($idControlExcepcion);
        /**
         * Sólo se admite el envío del mensaje en caso de que el control de excepción este en ELABORACIÓN O ELABORADO
         * (Este último caso para cuando se deba reenviar el mensaje)
         */
        if (is_object($controlexcepcion) && is_object($relaboralSolicitante)) {
            $contactoRemitente = Personascontactos::findFirst(array("persona_id='" . $relaboralSolicitante->id_persona . "'"));
            $contactoDestinatarioPrincipal = Personascontactos::findFirst(array("persona_id='" . $relaboralDestinatarioPrincipal->id_persona . "'"));
            $contactoDestinatarioSecundario = null;
            if ($idRelaboralDestinatarioSecundario > 0) {
                $contactoDestinatarioSecundario = Personascontactos::findFirst(array("persona_id='" . $relaboralDestinatarioSecundario->id_persona . "'"));
            }

            if (is_object($contactoRemitente)) {
                /**
                 * Se admite el envío de confirmación de acción sobre solicitud para registros en estado APROBACION SOLICITADA, VERIFICADO, APROBADO, RECEPCIONADO, VERIFICACIÓN RECHAZADA Y APROBACIÓN RECHAZADA
                 */
                if ($controlexcepcion->controlexcepcion_estado == 4
                    || $controlexcepcion->controlexcepcion_estado == 5
                    || $controlexcepcion->controlexcepcion_estado == 6
                    || $controlexcepcion->controlexcepcion_estado == 7
                    || $controlexcepcion->controlexcepcion_estado == -1
                    || $controlexcepcion->controlexcepcion_estado == -2
                ) {
                    $mensajeCabecera = "Estimad@ Usuario:<br>";
                    $mensajeCabecera .= "Se ha procesado un registro de excepci&oacute;n de acuerdo al siguiente detalle: ";
                    $mensajePie = "Atte.,<br>";
                    $mensajePie .= "<b>Unidad de Administraci&oacute;n y Recursos Humanos<br>";
                    $mensajePie .= "DAF<br>";
                    $mensajePie .= "- VIAS BOLIVIA -</b><br>";
                    $nombreSolicitante = "";
                    $cargoSolicitante = "";
                    $departamentoSolicitante = "";
                    $gerenciaSolicitante = "";
                    $fechaIni = "";
                    $fechaFin = "";
                    $horaIni = "";
                    $horaFin = "";
                    $mostrarHorario = 0;
                    if (is_object($relaboralSolicitante)) {
                        $nombreSolicitante = $relaboralSolicitante->nombres;
                        $cargoSolicitante = utf8_decode($relaboralSolicitante->cargo);
                        $departamentoSolicitante = utf8_decode($relaboralSolicitante->departamento_administrativo);
                        $gerenciaSolicitante = utf8_decode($relaboralSolicitante->gerencia_administrativa);
                    }
                    if (is_object($controlexcepcion)) {
                        $excepcion = $controlexcepcion->excepcion;
                        $justificacion = $controlexcepcion->justificacion;
                        $fechaIni = $controlexcepcion->fecha_ini != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_ini)) : "";
                        $fechaFin = $controlexcepcion->fecha_fin != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_fin)) : "";
                        $horaIni = $controlexcepcion->hora_ini;
                        $horaFin = $controlexcepcion->hora_fin;
                        $mostrarHorario = $controlexcepcion->horario;
                    }
                    $cuerpoCopia = '';
                    $cuerpo = '<html>';
                    $cuerpo .= '<head>';
                    $cuerpo .= '<title>Env&iacute;o de Solicitud</title>';
                    $cuerpo .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>';
                    $cuerpo .= '<style type="text/css">';
                    //$cuerpo .= '<!--';
                    $cuerpo .= '#datos {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= 'width:780px;';
                    $cuerpo .= 'left: 164px;';
                    $cuerpo .= 'top: 316px;';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #form1 table tr td {';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= 'font-weight: bold;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv2 {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= 'width:49px;';
                    $cuerpo .= 'height:45px;';
                    $cuerpo .= 'z-index:2;';
                    $cuerpo .= 'left: 12px;';
                    $cuerpo .= 'top: 11px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #notificacion table tr td {';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #notificacion table tr td {';
                    $cuerpo .= 'text-align: left;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv1 #notificacion table tr td {';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= 'font-family: Arial, Helvetica, sans-serif;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#apDiv3 {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= 'width:833px;';
                    $cuerpo .= 'height:115px;';
                    $cuerpo .= 'z-index:1;';
                    $cuerpo .= 'left: 99px;';
                    $cuerpo .= 'text-align: center;';
                    $cuerpo .= 'top: 16px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#aAprobarSolicitud{';
                    $cuerpo .= 'color: #FFFFFF;';
                    $cuerpo .= 'border: 2px #26dd5c solid;';
                    $cuerpo .= 'padding: 5px 20px 5px 20px;';
                    $cuerpo .= 'background-color: #34a853;';
                    $cuerpo .= 'font-family: Comic Sans MS, Calibri, Arial;';
                    $cuerpo .= 'font-size: 12px;';
                    $cuerpo .= 'font-weight: bold;';
                    $cuerpo .= 'text-decoration: none;';
                    $cuerpo .= 'background-repeat: no-repeat;';
                    $cuerpo .= 'border-radius: 15px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#aRechazarSolicitud{';
                    $cuerpo .= 'color: #FFFFFF;';
                    $cuerpo .= 'border: 2px #ff0a03 solid;';
                    $cuerpo .= 'padding: 5px 20px 5px 20px;';
                    $cuerpo .= 'background-color: #ff572b;';
                    $cuerpo .= 'font-family: Comic Sans MS, Calibri, Arial;';
                    $cuerpo .= 'font-size: 12px;';
                    $cuerpo .= 'font-weight: bold;';
                    $cuerpo .= 'text-decoration: none;';
                    $cuerpo .= 'background-repeat: no-repeat;';
                    $cuerpo .= 'border-radius: 15px;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#divCabeceraMensaje {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= '} ';
                    $cuerpo .= '#divPieMensaje {';
                    $cuerpo .= 'position:relative;';
                    $cuerpo .= '} ';
                    //$cuerpo .= '-->';
                    $cuerpo .= '</style>';
                    $cuerpo .= '</head>';
                    $cuerpo .= '<body>';
                    $cuerpo .= '<div id="divCabeceraMensaje">';
                    $cuerpo .= $mensajeCabecera;
                    $cuerpo .= '</div>';
                    $cuerpo .= '<div id="apDiv3">';
                    $cuerpo .= '<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td><table width="100%" border="0">';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td>';
                    $cuerpo .= '<p style="font-family: Helvetica LT Condensed; color: #3085ff; font-weight: bold; font-size: 15px; text-align: center;">' . $accionRealizada . '</p></td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Ejecutor:</span>&nbsp; ' . $nombreDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Correo Ejecutor:</span>&nbsp; ' . $contactoDestinatarioSecundario->e_mail_inst . '</td>';
                    $cuerpo .= '</tr>';

                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Cargo:</span>&nbsp; ' . $cargoDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                    $cuerpo .= '</tr>';
                    if ($departamentoDestinatarioSecundario != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $departamentoDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                    }
                    if ($gerenciaDestinatarioSecundario != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $gerenciaDestinatarioSecundario . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                    }
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Tipo de Excepci&oacute;n:</span>&nbsp; ' . $excepcion . '</td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Justificaci&oacute;n:</span>&nbsp; ' . utf8_decode($justificacion) . '</td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Estado:</span>&nbsp; ' . $controlexcepcion->controlexcepcion_estado_descripcion . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                    $cuerpo .= '</tr>';

                    if ($fechaIni != '' && $fechaFin != '') {
                        $cuerpo .= '<tr>';
                        if ($fechaIni != $fechaFin) {
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fechas:</span>&nbsp; Del ' . $fechaIni . ' al ' . $fechaFin . '</td>';
                        } else {
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha:</span>&nbsp; ' . $fechaIni . '</td>';
                        }
                        $cuerpo .= '</tr>';
                    }
                    if ($mostrarHorario == 1) {
                        if ($horaIni != '' && $horaFin != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Horario:</span>&nbsp; ' . $horaIni . ' a ' . $horaFin . '</td>';
                            $cuerpo .= '</tr>';
                        }
                    }

                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;">*******************************************************************************************************</td>';
                    $cuerpo .= '</tr>';

                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Solicitante:</span>&nbsp; ' . $nombreSolicitante . '</td>';
                    $cuerpo .= '</tr>';

                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Correo Solicitante:</span>&nbsp; ' . $contactoDestinatarioPrincipal->e_mail_inst . '</td>';
                    $cuerpo .= '</tr>';

                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Cargo Solicitante:</span>&nbsp; ' . $cargoSolicitante . '</td>';
                    $cuerpo .= '</tr>';

                    if ($departamentoSolicitante != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $departamentoSolicitante . '</td>';
                        $cuerpo .= '</tr>';
                    }

                    if ($gerenciaSolicitante != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">- </span>&nbsp; ' . $gerenciaSolicitante . '</td>';
                        $cuerpo .= '</tr>';
                    }
                    if ($fechaYHoraEnvio != '') {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha y Hora de Env&iacute;o (Actual Mensaje):</span>&nbsp; ' . $fechaYHoraEnvio . '</td>';
                        $cuerpo .= '</tr>';
                    }

                    $cuerpo .= '</table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br></br></br></br></br></br></br>' . $mensajePie . '</div></body></html>';

                    if ($idRelaboralDestinatarioPrincipal > 0) {
                        $parUser = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'USUARIO' AND estado=1 AND baja_logica=1"));
                        $userMail = '';
                        if (is_object($parUser)) {
                            $userMail = $parUser->valor_1;
                        }
                        $parPass = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'PASSWORD' AND estado=1 AND baja_logica=1"));
                        $passMail = '';
                        if (is_object($parPass)) {
                            $passMail = $parPass->valor_1;
                        }
                        $parHost = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'HOST' AND estado=1 AND baja_logica=1"));
                        $hostMail = '';
                        if (is_object($parHost)) {
                            $hostMail = $parHost->valor_1;
                        }
                        $parPort = Parametros::findFirst(array("parametro LIKE 'USUARIO_CORREO_RRHH' AND nivel LIKE 'PORT' AND estado=1 AND baja_logica=1"));
                        $portMail = '';
                        if (is_object($parPort)) {
                            $portMail = $parPort->valor_1;
                        }
                        if ($userMail != '' && $passMail != '' && $hostMail != '' && $portMail != '') {
                            $mail = new phpmaileroasis();
                            $mail->IsSMTP();
                            $mail->SMTPAuth = true;
                            $mail->SMTPSecure = "ssl";
                            $mail->Host = $hostMail;
                            $mail->Port = $portMail;
                            $mail->Username = $userMail;
                            $mail->Password = $passMail;
                            $mail->From = $userMail;
                            $mail->FromName = "Sistema de Recursos Humanos - VB";
                            $referenciaAccionRealizada = str_replace("&Oacute;", "Ó", $referenciaAccionRealizada);
                            $mail->Subject = utf8_decode($referenciaAccionRealizada);
                            $mail->MsgHTML($cuerpo);
                            $mail->AddAddress($contactoDestinatarioPrincipal->e_mail_inst, $relaboralDestinatarioPrincipal->nombres);
                            $mail->AddCC($contactoDestinatarioSecundario->e_mail_inst, $relaboralDestinatarioSecundario->nombres);
                            $mail->AddCC($userMail, "SRRHH - VB");
                            $mail->IsHTML(true);
                            $mail->smtpConnect([
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                ]
                            ]);
                            if ($mail->Send()) {
                                $msj = array('result' => 1, 'msj' => 'Envio exitoso de solicitud a las cuentas.', 'estado' => $controlexcepcion->controlexcepcion_estado);
                            } else {
                                $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a que no existe la cuenta del solicitante.', 'estado' => $controlexcepcion->controlexcepcion_estado);
                            }

                        }
                    } else {
                        $msj = array('result' => 0, 'msj' => 'No se admite el env&iacute;o del mensaje de correo debido a que el registro ya se encuentra inhabilitado para la tarea solicitada (' . $controlexcepcion->controlexcepcion_estado_descripcion . ').', 'estado' => $controlexcepcion->controlexcepcion_estado);
                    }
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'No se encontr&oacute; el registro correspondiente de la excepci&oacute;n.');
            }
            return $msj;
        }
    }

    /**
     * Obtiene la cantidad de días de diferencia entre dos fechas.
     * @param $primera
     * @param $segunda
     * @param string $sep
     * @return int
     */
    public function compararFechas($primera, $segunda, $sep = "-")
    {
        $valoresPrimera = explode($sep, $primera);
        $valoresSegunda = explode($sep, $segunda);
        $diaPrimera = $valoresPrimera[0];
        $mesPrimera = $valoresPrimera[1];
        $anyoPrimera = $valoresPrimera[2];
        $diaSegunda = $valoresSegunda[0];
        $mesSegunda = $valoresSegunda[1];
        $anyoSegunda = $valoresSegunda[2];
        $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);
        $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);
        if (!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)) {
            // "La fecha ".$primera." no es válida";
            return 0;
        } elseif (!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)) {
            // "La fecha ".$segunda." no es válida";
            return 0;
        } else {
            return $diasPrimeraJuliano - $diasSegundaJuliano;
        }
    }
} 