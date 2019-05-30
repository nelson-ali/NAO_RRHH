<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  12-10-2015
*/
require_once('../app/libs/qrlib/qrlib.php');
require_once('../app/libs/phpmailer/class.phpmailer.php');

/**
 * Class RegisterandrecoverController
 * Clase para el registro y recuperación de contraseñas olvidadas.
 */
class RegisterandrecoverController extends ControllerBaseOut
{

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        /*$this->view->disable();
        $this->response->redirect('/login');*/
    }

    /**
     * Función para la recuperación o reinicio de la contraseña del sistema de recursos humanos.
     */
    public function changepasswordAction()
    {
        $this->view->setMainView('recover');
        $this->view->setLayout('recover');
        $search = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
        $replace = array("&acute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;");
        $email = "";
        $msj = array();
        if (isset($_POST["email"])) {
            $email = $_POST["email"];
        }
        $arrEmail = explode("@", $email);
        $dominio = $arrEmail[1];
        if (count($arrEmail) == 2 && $dominio == "viasbolivia.gob.bo") {
            $param = Parametros::findFirst(array("parametro LIKE 'RUTA_APLICACION' AND estado=1 AND baja_logica=1"));
            $ruta = 'http://rrhh.local/registerandrecover/definepassword/';
            if (is_object($param)) {
                $ruta = 'http://' . $param->nivel . '/registerandrecover/definepassword/';
            }
            $operacionSolicitada = utf8_decode("Restablecimiento de Cuenta");
            if ($email != '') {

            }
            $contacto = Personascontactos::findFirst(array("e_mail_inst=:email:", 'bind' => array("email" => $email)));
            if (is_object($contacto)) {
                $relaboral = Relaborales::findFirst(array("persona_id=:persona_id: AND estado>=1 AND baja_logica=1", 'bind' => array("persona_id" => $contacto->persona_id)));
                if (is_object($relaboral)) {

                    $hoy = date("Y-m-d H:i:s");
                    $fechaYHoraEnvio = date("d-m-Y H:i:s");
                    $idRelaboralSolicitante = $relaboral->id;
                    $idPersonaSolicitante = $relaboral->persona_id;
                    $objRel = new Frelaborales();
                    $passwordAleatorio = $this->obtenerCadenaAleatoria(5);
                    $relaboralSolicitante = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                    $usuarioSolicitante = Usuarios::findFirst(array("persona_id='" . $idPersonaSolicitante . "'"));
                    $username = str_replace("@viasbolivia.gob.bo", "", $contacto->e_mail_inst);
                    $password = hash_hmac('sha256', trim($passwordAleatorio), '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
                    if (is_object($usuarioSolicitante)) {
                        /**
                         * Si el usuario existe sólo se debe modificar
                         */
                        $usuarioSolicitante->password = $password;
                        $oku = $usuarioSolicitante->save();
                    } else {
                        if (is_object($contacto) && $contacto->e_mail_inst != '' && $contacto->e_mail_inst != null) {
                            $fper = new Fpersonas();
                            $pseudonimo = "uta" . $contacto->persona_id;
                            $pseudonimoGenerado = $fper->getPseudonimo($contacto->persona_id);
                            if ($pseudonimoGenerado != '' && $pseudonimoGenerado != null) {
                                $pseudonimo = $pseudonimoGenerado;
                            }
                            /**
                             * Si el usuario no existe se crea un usuario sin acceso al módulo de administradores, sólo al de consultas por ello su nivel acá será 0
                             */
                            $usuarioSolicitante = new usuarios();
                            $usuarioSolicitante->persona_id = $idPersonaSolicitante;
                            $usuarioSolicitante->username = $username;
                            $usuarioSolicitante->password = $password;
                            $usuarioSolicitante->pseudonimo = $pseudonimo;
                            $usuarioSolicitante->habilitado = 1;
                            $usuarioSolicitante->logins = 1000;
                            /**
                             * Esto a objeto de sólo crear usuarios de consulta
                             */
                            $usuarioSolicitante->nivel = 0;
                            $usuarioSolicitante->super = 1;
                            $usuarioSolicitante->fecha_creacion = $hoy;
                            $oku = $usuarioSolicitante->save();
                            if ($oku) {
                                /**
                                 * Si el usuario se ha creado sin problemas, se crea el registro de modalidad de nivel que permite el acceso
                                 * sólo al módulo de consultas del sistema.
                                 */
                                $modalidadnivel = new modalidadnivel();
                                $modalidadnivel->usuario_id = $usuarioSolicitante->id;
                                $modalidadnivel->modalidad = 0;
                                $modalidadnivel->nivel = 11;
                                $modalidadnivel->estado = 1;
                                $modalidadnivel->baja_logica = 1;
                                $okmn = $modalidadnivel->save();
                                if ($okmn) {
                                    $idUsuario = $usuarioSolicitante->id;
                                }
                            }
                        }
                    }
                    if ($oku) {
                        $mensajeCabecera = "Estimad@ Usuario:<br>";
                        $mensajeCabecera .= "Usted ha solicitado la operaci&oacute;n de <b>" . str_replace($search,$replace,$operacionSolicitada) . "</b> ";
                        $mensajeCabecera .= "en el Sistema de Recursos Humanos por lo que se ha generado una nueva contraseña para su ingreso. ";
                        $mensajeCabecera .= "Se le recomiendo hacer el cambio del mismo luego de ingresar al sistema.";
                        $mensajeCabecera = utf8_decode($mensajeCabecera);
                        $mensajePie = "Atte.,<br>";
                        $mensajePie .= "<b>Unidad de Talento Humanos<br>";
                        $mensajePie .= "Direcci&oacute;n Administrativa Financiera<br>";
                        $mensajePie .= "Vias Bolivia</b><br>";
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
                        $idRelaboralSolicitanteCodificado = rtrim(strtr(base64_encode($idRelaboralSolicitante), '+/', '-_'), '=');
                        $ultimaVersionDeJqueryMin="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js";
                        $parDir = Parametros::findFirst(array("parametro LIKE 'DIRECCION_ULTIMA_VERSION_JQUERY_MIN' AND estado=1 AND baja_logica=1"));
                        if (is_object($parDir)) {
                            $ultimaVersionDeJqueryMin = $parDir->nivel;
                        }
                        $cuerpo = '<html>';
                        $cuerpo .= '<head>';
                        $cuerpo .= '<title>Env&iacute;o de Solicitud</title>';
                        $cuerpo .= '<script src="'.$ultimaVersionDeJqueryMin.'" type="text/javascript"></script>';
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
                        $cuerpo .= 'background-color: #3498DB;';
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
                        $cuerpo .= '<p style="font-family: Helvetica LT Condensed; color: #3085ff; font-weight: bold; font-size: 15px; text-align: center;">' . str_replace($search,$replace,$operacionSolicitada) . '</p></td>';
                        $cuerpo .= '</tr>';
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Solicitante:</span>&nbsp; ' . $nombreSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Cargo:</span>&nbsp; ' . $cargoSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                        if ($departamentoSolicitante != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Departamento:</span>&nbsp; ' . $departamentoSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                            $cuerpo .= '</tr>';
                        }
                        if ($gerenciaSolicitante != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Gerencia:</span>&nbsp; ' . $gerenciaSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                            $cuerpo .= '</tr>';
                        }
                        if ($fechaYHoraEnvio != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha y Hora de Env&iacute;o (Actual Mensaje):</span>&nbsp; ' . $fechaYHoraEnvio . '</td>';
                            $cuerpo .= '</tr>';
                        }
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td>';
                        $cuerpo .= '<p><span style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Contrase&ntilde;a:</span>&nbsp;' . $passwordAleatorio . '</span></p></td>';

                        $cuerpo .= '</table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>' . $mensajePie . '</div>';

                        $cuerpo .= '</body></html>';
                        if ($idRelaboralSolicitante > 0) {
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
                                $mail->FromName = "Sistema de Recursos Humanos";
                                $mail->Subject = utf8_decode("Solicitud " . $operacionSolicitada);
                                $mail->MsgHTML($cuerpo);
                                $mail->AddAddress($contacto->e_mail_inst, $relaboralSolicitante->nombres);
                                $mail->IsHTML(true);
                                $mail->smtpConnect([
                                    'ssl' => [
                                        'verify_peer' => false,
                                        'verify_peer_name' => false,
                                        'allow_self_signed' => true
                                    ]
                                ]);
                                if ($mail->Send()) {
                                    $msj = array('result' => 1, 'msj' => 'Exito! Se envi&oacute; un mensaje de correo electr&oacute;nico de confirmaci&oacute;n a su cuenta, revise su Bandeja de Entrada.');
                                } else $msj = array('result' => 0, 'msj' => 'No se ha podido enviar el correo electr&oacute;nico, cont&aacute;ctese con personal de Recursos Humanos.');
                            }
                        }
                    } else $msj = array('result' => -1, 'msj' => 'Error! No se ha podido crear el usuario correspondiente, cont&acute;tese con personal de Recursos Humanos.');
                } else $msj = array('result' => -2, 'msj' => 'Usted no tiene actualmente un registro activo de contrato en la empresa por lo que su acceso esta restringido..');
            } else {
                $msj = array('result' => -3, 'msj' => 'No se ha encontrado registro de su correo electr&oacute;nico en el sistema, cont&aacute;tese con personal de Recursos Humanos.');
            }
        } else $msj = array('result' => -4, 'msj' => 'El dato enviado no corresponde a un correo electr&oacute;nico v&aacute;lido. Tiene que tener la forma "micuenta@viasbolivia.gob.bo".');
        echo json_encode($msj);
    }

    /**
     * Función para la restablecimiento de la contraseña en base al enlace enviado a la cuenta de correo electrónico.
     * @throws Exception
     * @throws phpmailerException
     */
    public function recoverAction()
    {
        $this->view->setMainView('recover');
        $this->view->setLayout('recover');
        $email = "";
        $msj = array();
        if (isset($_POST["email"])) {
            $email = $_POST["email"];
        }
        $arrEmail = explode("@", $email);
        $dominio = $arrEmail[1];
        $opcion = 0;
        $oku = false;
        if (count($arrEmail) == 2 && $dominio == "viasbolivia.gob.bo") {
            $param = Parametros::findFirst(array("parametro LIKE 'RUTA_APLICACION' AND estado=1 AND baja_logica=1"));
            $ruta = 'http://rrhh.local/registerandrecover/solredefinepassword/';
            if (is_object($param)) {
                $ruta = 'http://' . $param->nivel . '/registerandrecover/solredefinepassword/';
            }
            $contacto = Personascontactos::findFirst(array("e_mail_inst=:email:", 'bind' => array("email" => $email)));
            if (is_object($contacto)) {
                $relaboral = Relaborales::findFirst(array("persona_id=:persona_id: AND estado>=1 AND baja_logica=1", 'bind' => array("persona_id" => $contacto->persona_id)));
                if (is_object($relaboral)) {

                    $hoy = date("Y-m-d H:i:s");
                    $fechaYHoraEnvio = date("d-m-Y H:i:s");
                    $fechaEnvioCodificado = rtrim(strtr(base64_encode(date("d-m-Y")), '+/', '-_'), '=');
                    $horaEnvioCodificado = rtrim(strtr(base64_encode(date("H:i:s")), '+/', '-_'), '=');
                    $idRelaboralSolicitante = $relaboral->id;
                    $idPersonaSolicitante = $relaboral->persona_id;
                    $objRel = new Frelaborales();
                    $passwordAleatorio = $this->obtenerCadenaAleatoria(5);
                    $relaboralSolicitante = $objRel->getOneRelaboralConsiderandoUltimaMovilidad($relaboral->id);
                    $usuarioSolicitante = Usuarios::findFirst(array("persona_id='" . $idPersonaSolicitante . "'"));
                    $username = str_replace("@viasbolivia.gob.bo", "", $contacto->e_mail_inst);
                    $password = hash_hmac('sha256', trim($passwordAleatorio), '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
                    if (is_object($usuarioSolicitante)) {
                        $operacionSolicitada = utf8_decode("Restablecimiento de Contraseña");
                        $oku = true;
                        $opcion = 2;
                        if($usuarioSolicitante->habilitado == 0){
                            #region Rehabilitación de usuario deshabilitado.
                            $usuarioSolicitante->habilitado = 1;
                            $usuarioSolicitante->user_hab_id = $usuarioSolicitante->id;
                            $usuarioSolicitante->fecha_hab = $hoy;
                            $usuarioSolicitante->user_mod_id = $usuarioSolicitante->id;
                            $usuarioSolicitante->fecha_mod = $hoy;
                            $usuarioSolicitante->save();
                            #endregion
                        }
                        /**
                         * Si el usuario existe sólo se debe modificar
                         */
                        /*$usuarioSolicitante->password=$password;
                        $oku = $usuarioSolicitante->save();*/
                    } else {
                        if (is_object($contacto) && $contacto->e_mail_inst != '' && $contacto->e_mail_inst != null) {
                            $operacionSolicitada = utf8_decode("Habilitación de Cuenta");
                            $opcion = 1;
                            /**
                             * Si el usuario no existe se crea un usuario sin acceso al módulo de administradores, sólo al de consultas por ello su nivel acá será 0
                             */
                            $fper = new Fpersonas();
                            $pseudonimo = "uta" . $contacto->persona_id;
                            $pseudonimoGenerado = $fper->getPseudonimo($contacto->persona_id);
                            if ($pseudonimoGenerado != '' && $pseudonimoGenerado != null) {
                                $pseudonimo = $pseudonimoGenerado;
                            }
                            $usuarioSolicitante = new usuarios();
                            $usuarioSolicitante->persona_id = $idPersonaSolicitante;
                            $usuarioSolicitante->username = $username;
                            $usuarioSolicitante->password = $password;
                            $usuarioSolicitante->pseudonimo = $pseudonimo;
                            $usuarioSolicitante->habilitado = 1;
                            $usuarioSolicitante->logins = 1000;
                            /**
                             * Esto a objeto de sólo crear usuarios de consulta
                             */
                            $usuarioSolicitante->nivel = 0;
                            $usuarioSolicitante->super = 1;
                            $usuarioSolicitante->fecha_creacion = $hoy;
                            $oku = $usuarioSolicitante->save();
                            if ($oku) {
                                /**
                                 * Si el usuario se ha creado sin problemas, se crea el registro de modalidad de nivel que permite el acceso
                                 * sólo al módulo de consultas del sistema.
                                 */
                                $modalidadnivel = new modalidadnivel();
                                $modalidadnivel->usuario_id = $usuarioSolicitante->id;
                                $modalidadnivel->modalidad = 0;
                                $modalidadnivel->nivel = 11;
                                $modalidadnivel->estado = 1;
                                $modalidadnivel->baja_logica = 1;
                                $okmn = $modalidadnivel->save();
                                if ($okmn) {
                                    $idUsuario = $usuarioSolicitante->id;
                                }
                            }
                        }
                    }
                    if ($oku) {
                        $mensajeCabecera = "Estimad@ Usuario:<br>";
                        $mensajeCabecera .= "Usted ha solicitado la operación de <b>" . $operacionSolicitada . "</b> en el Sistema de Recursos Humanos ";
                        if ($opcion == 1) {
                            $mensajeCabecera .= "por lo que se ha generado una nueva contraseña para su ingreso. ";
                            $mensajeCabecera .= "Se le recomiendo hacer el cambio del mismo luego de ingresar al sistema.";
                        } else {
                            if ($opcion == 2) {
                                $mensajeCabecera .= "por lo que debe hacer click sobre el enlace referenciado más abajo.";
                            }
                        }
                        $mensajeCabecera = utf8_decode($mensajeCabecera);
                        $mensajePie = "Atte.,<br>";
                        $mensajePie .= "<b>Unidad de Talento Humanos<br>";
                        $mensajePie .= "Direcci&oacute;n Administrativa Financiera<br>";
                        $mensajePie .= "Vias Bolivia</b><br>";
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
                        $idRelaboralSolicitanteCodificado = rtrim(strtr(base64_encode($idRelaboralSolicitante), '+/', '-_'), '=');
                        $ultimaVersionDeJqueryMin="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js";
                        $parDir = Parametros::findFirst(array("parametro LIKE 'DIRECCION_ULTIMA_VERSION_JQUERY_MIN' AND estado=1 AND baja_logica=1"));
                        if (is_object($parDir)) {
                            $ultimaVersionDeJqueryMin = $parDir->nivel;
                        }
                        $cuerpo = '<html>';
                        $cuerpo .= '<head>';
                        $cuerpo .= '<title>Env&iacute;o de Solicitud</title>';
                        $cuerpo .= '<script src="'.$ultimaVersionDeJqueryMin.'" type="text/javascript"></script>';
                        $cuerpo .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js" type="text/javascript"></script>';
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
                        $cuerpo .= 'background-color: #3498DB;';
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
                        $cuerpo .= '<p style="font-family: Helvetica LT Condensed; color: #3085ff; font-weight: bold; font-size: 15px; text-align: center;">' . $operacionSolicitada . '</p></td>';
                        $cuerpo .= '</tr>';
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Solicitante:</span>&nbsp; ' . $nombreSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Cargo:</span>&nbsp; ' . $cargoSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                        $cuerpo .= '</tr>';
                        if ($departamentoSolicitante != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Departamento:</span>&nbsp; ' . $departamentoSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                            $cuerpo .= '</tr>';
                        }
                        if ($gerenciaSolicitante != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><spanHelvetica LT Condensed"; font-size: 12px;"><span style="font-weight: bold">Gerencia:</span>&nbsp; ' . $gerenciaSolicitante . '</span> &nbsp; <spanHelvetica LT Condensed"; font-size: 12px;">&nbsp;</span></td>';
                            $cuerpo .= '</tr>';
                        }
                        if ($fechaYHoraEnvio != '') {
                            $cuerpo .= '<tr>';
                            $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Fecha y Hora de Env&iacute;o (Actual Mensaje):</span>&nbsp; ' . $fechaYHoraEnvio . '</td>';
                            $cuerpo .= '</tr>';
                        }
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td>';
                        if ($opcion == 1) {
                            $cuerpo .= '<p><span style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Contrase&ntilde;a:</span>&nbsp;' . $passwordAleatorio . '</span></p>';
                        } else {
                            if ($opcion == 2) {

                                $cuerpo .= '<br>';
                                $cuerpo .= '<table width="100%"><tr><td style="text-align: center"><a href="' . $ruta . $idRelaboralSolicitanteCodificado . '/' . $fechaEnvioCodificado . '/' . $horaEnvioCodificado . '/" id="aAprobarSolicitud"  target="_blank">Restablecer Contrase&ntilde;a</a></td></tr></table>';
                                $cuerpo .= '<br>';
                            }
                        }
                        $cuerpo .= '</td></table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br>' . $mensajePie . '</div>';

                        $cuerpo .= '</body></html>';
                        if ($idRelaboralSolicitante > 0) {

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
                                $mail->FromName = "Sistema de Recursos Humanos";
                                $mail->Subject = utf8_decode("Solicitud " . $operacionSolicitada);
                                $mail->MsgHTML($cuerpo);
                                $mail->AddAddress($contacto->e_mail_inst, $relaboralSolicitante->nombres);
                                $mail->IsHTML(true);
                                $mail->smtpConnect([
                                    'ssl' => [
                                        'verify_peer' => false,
                                        'verify_peer_name' => false,
                                        'allow_self_signed' => true
                                    ]
                                ]);
                                if ($mail->Send()) {
                                    $msj = array('result' => 1, 'msj' => 'Exito! Se envi&oacute; un mensaje de correo electr&oacute;nico de confirmaci&oacute;n a su cuenta, revise su Bandeja de Entrada.');
                                } else $msj = array('result' => 0, 'msj' => 'No se ha podido enviar el correo electr&oacute;nico, cont&aacute;ctese con personal de Recursos Humanos.');
                            } else $msj = array('result' => 0, 'msj' => 'No se ha podido enviar el correo electr&oacute;nico debido a la inexistencia de la cuenta del correo electr&oacute;nico de RRHH, cont&aacute;ctese con personal de Recursos Humanos.');
                        }
                    } else $msj = array('result' => -1, 'msj' => 'Error! No se ha podido crear el usuario correspondiente, cont&acute;tese con personal de Recursos Humanos.');
                } else $msj = array('result' => -2, 'msj' => 'Usted no tiene actualmente un registro activo de contrato en la empresa por lo que su acceso esta restringido..');
            } else {
                $msj = array('result' => -3, 'msj' => 'No se ha encontrado registro de su correo electr&oacute;nico en el sistema, cont&aacute;tese con personal de Recursos Humanos.');
            }
        } else $msj = array('result' => -4, 'msj' => 'El dato enviado no corresponde a un correo electr&oacute;nico v&aacute;lido. Tiene que tener la forma "micuenta@viasbolivia.gob.bo".');
        echo json_encode($msj);
    }

    /**
     * Función para enviar la solicitud de restablecimiento del password.
     * @param null $idRelaboralSolicitanteCodificado
     * @param null $fechaSolicitudCodificado
     * @param null $horaSolicitud
     */
    public function solredefinepasswordAction($idRelaboralSolicitanteCodificado = null, $fechaSolicitudCodificado = null, $horaSolicitudCodificado = null)
    {
        $this->view->disable();
        $idRelaboralSolicitante = base64_decode(str_pad(strtr($idRelaboralSolicitanteCodificado, '-_', '+/'), strlen($idRelaboralSolicitanteCodificado) % 4, '=', STR_PAD_RIGHT));
        $fechaSolicitud = base64_decode(str_pad(strtr($fechaSolicitudCodificado, '-_', '+/'), strlen($fechaSolicitudCodificado) % 4, '=', STR_PAD_RIGHT));
        $horaSolicitud = base64_decode(str_pad(strtr($horaSolicitudCodificado, '-_', '+/'), strlen($horaSolicitudCodificado) % 4, '=', STR_PAD_RIGHT));
        $accionRealizada = utf8_decode("Restablecimiento de Contraseña");
        $this->view->disable();
        $ultimaVersionDeJqueryMin="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js";
        $parametro = Parametros::findFirst(array("parametro LIKE 'DIRECCION_ULTIMA_VERSION_JQUERY_MIN' AND estado=1 AND baja_logica=1"));
        if (is_object($parametro)) {
            $ultimaVersionDeJqueryMin = $parametro->nivel;
        }
        $hoy = date("Y-m-d H:i:s");
        $ahora = date("d-m-Y");
        $cuerpo = "";
        $cuerpo = '<html>';
        $cuerpo .= '<head>';
        $cuerpo .= '<title>Env&iacute;o de Solicitud</title>';
        $cuerpo .= '<script src="'.$ultimaVersionDeJqueryMin.'" type="text/javascript"></script>';
        $cuerpo .= '<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">';
        $cuerpo .= '<style type="text/css">';
        $cuerpo .= 'body{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;color:#394263;font-size:13px;background-color:#f2f2f2}#main-container,#page-container{min-width:320px}#page-container{width:100%;padding:0;margin:0 auto;overflow-x:hidden;-webkit-transition:background-color .2s ease-out;transition:background-color .2s ease-out}#page-container,#sidebar{background-color:#11203a}#sidebar{width:0;position:absolute;overflow:hidden}#main-container,#sidebar,.header-fixed-bottom header,.header-fixed-top header{-webkit-transition:all .2s ease-out;transition:all .2s ease-out}#page-content{padding:10px 5px 1px;min-height:1200px;background-color:#eaedf1}#page-content+footer{padding:9px 10px;font-size:11px;background-color:#fff;border-top:1px solid #dbe1e8}#page-container.header-fixed-top{padding:50px 0 0}#page-container.header-fixed-bottom{padding:0 0 50px}.sidebar-open #sidebar{width:200px}.sidebar-open #main-container{margin-left:220px}.header-fixed-bottom #sidebar,.header-fixed-top #sidebar{position:fixed;left:0;top:0;bottom:0}.header-fixed-bottom .sidebar-content,.header-fixed-top .sidebar-content{padding-bottom:50px}.sidebar-open header.navbar-fixed-bottom,.sidebar-open header.navbar-fixed-top{left:220px}header.navbar-default,header.navbar-inverse{padding:0;margin:0;min-width:320px;max-height:50px;border:0}header.navbar-fixed-bottom,header.navbar-fixed-top{max-height:51px}header.navbar-default.navbar-fixed-top{border-bottom:1px solid #eaedf1}header.navbar-default.navbar-fixed-bottom{border-top:1px solid #eaedf1}header.navbar-inverse.navbar-fixed-top{border-bottom:1px solid #394263}header.navbar-inverse.navbar-fixed-bottom{border-top:1px solid #394263}.nav.navbar-nav-custom{float:left;margin:0}.nav.navbar-nav-custom>li{min-height:50px;float:left}.nav.navbar-nav-custom>li>a{min-width:50px;padding:5px 7px;line-height:40px;text-align:center;color:#394263}.nav.navbar-nav-custom>li>a .fi,.nav.navbar-nav-custom>li>a .gi,.nav.navbar-nav-custom>li>a .hi,.nav.navbar-nav-custom>li>a .si{margin-top:-3px}.navbar-inverse .nav.navbar-nav-custom>li>a{color:#fff}.nav.navbar-nav-custom>li.open>a,.nav.navbar-nav-custom>li>a:focus,.nav.navbar-nav-custom>li>a:hover{background-color:#1bbae1;color:#fff}.nav.navbar-nav-custom>li>a>img{width:40px;height:40px;border:2px solid #fff;border-radius:20px;vertical-align:top}.navbar-form-custom{padding:0;width:100px;float:left;height:50px}.navbar-form-custom .form-control{padding:10px;margin:0;height:50px;font-size:15px;background:0 0;border:0;z-index:2000}.navbar-form-custom .form-control:focus,.navbar-form-custom .form-control:hover{background-color:#fff}.navbar-form-custom .form-control:focus{position:absolute;top:0;left:0;right:0;font-size:18px;padding:10px 20px}.navbar-inverse .navbar-form-custom .form-control{color:#fff}.navbar-inverse .navbar-form-custom .form-control:focus,.navbar-inverse .navbar-form-custom .form-control:hover{background:#000;color:#fff}.sidebar-content{width:220px;color:#fff}.sidebar-section{padding:10px}.sidebar-brand{height:50px;line-height:50px;padding:0 10px;margin:0;font-weight:300;font-size:18px;display:block;color:#fff;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-brand:focus,.sidebar-brand:hover{background-color:#1bbae1;color:#fff;text-decoration:none}.sidebar-brand i{font-size:14px;display:inline-block;width:18px;text-align:center;margin-right:10px;opacity:.5;filter:alpha(opacity=50)}.sidebar-user{padding-left:88px;background:url(../img/template/ie8_opacity_light_10.png) repeat;background:rgba(255,255,255,.1)}.sidebar-user-avatar{width:68px;height:68px;float:left;padding:2px;margin-left:-78px;border-radius:34px;background:url(../img/template/ie8_opacity_light_75.png) repeat;background:rgba(255,255,255,.75)}.sidebar-user-avatar img{width:64px;height:64px;border-radius:32px}.sidebar-user-name{font-size:17px;font-weight:300;margin-top:10px;line-height:26px}.sidebar-user-links a{color:#fff;opacity:.3;filter:alpha(opacity:30);margin-right:5px}.sidebar-user-links a:focus,.sidebar-user-links a:hover{color:#fff;text-decoration:none;opacity:1;filter:alpha(opacity:100)}.sidebar-user-links a>i{font-size:14px}.sidebar-themes{list-style:none;margin:0;padding-bottom:7px;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-themes li{float:left;margin:0 3px 3px 0}.sidebar-themes li a{display:block;width:17px;height:17px;border-radius:10px;border-width:2px;border-style:solid}.sidebar-themes li a:focus,.sidebar-themes li a:hover,.sidebar-themes li.active a{border-color:#fff!important}.sidebar-nav{list-style:none;margin:0;padding:10px 0 0}.sidebar-nav .sidebar-header:first-child{margin-top:0}.sidebar-nav a{display:block;color:#eaedf1;padding:0 10px;min-height:35px;line-height:35px}.sidebar-nav a.open,.sidebar-nav a:hover,.sidebar-nav li.active>a{color:#fff;text-decoration:none;background:url(../img/template/ie8_opacity_dark_15.png) repeat;background:rgba(0,0,0,.15)}.sidebar-nav a.active{padding-left:5px;border-left:5px solid #1bbae1;background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.sidebar-nav a>.sidebar-nav-icon{margin-right:10px}.sidebar-nav a>.sidebar-nav-indicator{float:right;line-height:inherit;margin-left:4px;-webkit-transition:all .15s ease-out;transition:all .15s ease-out}.sidebar-nav a>.sidebar-nav-icon,.sidebar-nav a>.sidebar-nav-indicator{display:inline-block;opacity:.5;filter:alpha(opacity:50);width:18px;font-size:14px;text-align:center}.sidebar-nav a.active,.sidebar-nav a.active>.sidebar-nav-icon,.sidebar-nav a.active>.sidebar-nav-indicator,.sidebar-nav a.open,.sidebar-nav a.open>.sidebar-nav-icon,.sidebar-nav a.open>.sidebar-nav-indicator,.sidebar-nav a:hover,.sidebar-nav a:hover>.sidebar-nav-icon,.sidebar-nav a:hover>.sidebar-nav-indicator,.sidebar-nav li.active>a,.sidebar-nav li.active>a>.sidebar-nav-icon,.sidebar-nav li.active>a>.sidebar-nav-indicator{opacity:1;filter:alpha(opacity:100)}.sidebar-nav a.active>.sidebar-nav-indicator,.sidebar-nav a.open>.sidebar-nav-indicator,.sidebar-nav li.active>a>.sidebar-nav-indicator{-webkit-transform:rotate(-90deg);transform:rotate(-90deg)}.sidebar-nav ul{list-style:none;padding:0;margin:0;display:none;background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.sidebar-nav li.active>ul{display:block}.sidebar-nav ul a{margin:0;font-size:12px;padding-left:15px;min-height:32px;line-height:32px}.sidebar-nav ul a.active,.sidebar-nav ul a.active:hover{border-left:5px solid #1bbae1;padding-left:10px}.sidebar-nav ul ul{background:url(../img/template/ie8_opacity_dark_40.png) repeat;background:rgba(0,0,0,.4)}.sidebar-nav ul ul a{padding-left:25px}.sidebar-nav ul ul a.active,.sidebar-nav ul ul a.active:hover{padding-left:20px}.sidebar-header{margin:10px 0 0;padding:10px;line-height:12px}.sidebar-header+.sidebar-section{padding-top:0;padding-bottom:0}.sidebar-header .sidebar-header-title{color:#fff;font-size:11px;text-transform:uppercase;opacity:.5;filter:alpha(opacity:50)}.sidebar-header-options{float:right;display:inline-block}.sidebar-header-options>a,.sidebar-nav .sidebar-header-options a{float:right;margin:0;padding:0;min-height:0;line-height:inherit;display:block;min-width:18px;text-align:center;color:#fff;opacity:.3;filter:alpha(opacity:30)}.sidebar-header-options a.active,.sidebar-header-options a:focus,.sidebar-header-options a:hover,.sidebar-nav .sidebar-header-options a.active,.sidebar-nav .sidebar-header-options a:focus,.sidebar-nav .sidebar-header-options a:hover{background:0 0;color:#fff;opacity:1;filter:alpha(opacity:100)}.sidebar-header-options a>i{font-size:14px}.content-header{background-color:#fff;border-top:1px solid #eaedf1;border-bottom:1px solid #dbe1e8}.content-header h1,.content-header h2{margin:0;font-size:26px;line-height:32px}.content-header small .content-header small{font-size:17px}.header-section h1 i{font-size:56px;float:right;color:#eaedf1;margin:0 0 0 10px;line-height:64px}.header-section{padding:30px 10px}.content-header,.content-top{margin:-10px -5px 10px}.content-top{background-color:#fff;border-bottom:1px solid #dbe1e8}.content-header-media{position:relative;height:248px;overflow:hidden;border-top-color:#222}.content-header-media .header-section{z-index:200;position:absolute;top:0;left:0;right:0;color:#fff;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}.content-header-media i,.content-header-media small{color:#ddd}.content-header-media>img{position:absolute;top:0;left:50%;width:2560px;height:248px;margin-left:-1280px}.content-header-media>.content-header-media-map{height:270px}.block{margin:0 0 10px;padding:20px 15px 1px;background-color:#fff;border:1px solid #dbe1e8}.block.full{padding:20px 15px}.block .block-content-full{margin:-20px -15px -1px}.block .block-content-mini-padding{padding:8px}.block.full .block-content-full{margin:-20px -15px}.block-title{margin:-20px -15px 20px;background-color:#f9fafc;border-bottom:1px solid #eaedf1}.block-title h1,.block-title h2,.block-title h3,.block-title h4,.block-title h5,.block-title h6{display:inline-block;font-size:16px;line-height:1.4;margin:0;padding:10px 16px 7px;font-weight:400}.block-title h1 small,.block-title h2 small,.block-title h3 small,.block-title h4 small,.block-title h5 small,.block-title h6 small{font-size:13px;color:#777;font-weight:400}.block-title h1,.block-title h2,.block-title h3{padding-left:15px;padding-right:15px}.block-options,.block-title .nav-tabs{min-height:40px;line-height:38px}.block-title .nav-tabs{padding:3px 1px 0;border-bottom:0}.block-title .nav-tabs>li>a{border-bottom:0}.block-title .nav-tabs{margin-bottom:-2px}.block-title .nav-tabs>li>a{margin-bottom:0}.block-title .nav-tabs>li>a:hover{background:0 0}.block-title .nav-tabs>li.active>a,.block-title .nav-tabs>li.active>a:focus,.block-title .nav-tabs>li.active>a:hover{border:1px solid #eaedf1;border-bottom-color:#fff;background-color:#fff}.block-title code{padding:2px 3px}.block-options{margin:0 6px;line-height:37px}.block-options .label{display:inline-block;padding:6px;vertical-align:middle;font-size:13px}.block-top{margin:-20px -15px 20px;border-bottom:1px dotted #dbe1e8}.block-section{margin-bottom:20px}.widget{background-color:#fff;margin-bottom:10px}.widget .widget-extra,.widget .widget-extra-full{position:relative;padding:15px}.widget .widget-extra{padding-top:1px;padding-bottom:1px}.widget .widget-content-light{color:#fff}.widget .widget-content-light small{color:#eee}.widget .widget-icon,.widget .widget-image{width:64px;height:64px}.widget .widget-icon{height:64px;display:inline-block;line-height:64px;text-align:center;font-size:28px;color:#fff;border-radius:32px}.widget .widget-icon .fi,.widget .widget-icon .gi,.widget .widget-icon .hi,.widget .widget-icon .si{margin-top:-3px}.widget .widget-options,.widget .widget-options-left{position:absolute;top:5px;opacity:.5;filter:alpha(opacity=50)}.widget .widget-options{right:5px}.widget .widget-options-left{left:5px}.widget .widget-options-left:hover,.widget .widget-options:hover{opacity:1;filter:alpha(opacity=100)}.widget-simple{padding:15px}.widget-simple:after,.widget-simple:before{content:" ";display:table}.widget-simple:after{clear:both}.widget-simple .widget-icon,.widget-simple .widget-image{margin:0 15px}.widget-simple .widget-icon.pull-left,.widget-simple .widget-image.pull-left{margin-left:0}.widget-simple .widget-icon.pull-right,.widget-simple .widget-image.pull-right{margin-right:0}.widget-simple .widget-content{font-size:18px;margin:12px 0}.widget-simple .widget-content small{display:block;margin-top:7px;font-size:13px;font-weight:400}.widget-advanced .widget-header{position:relative;padding:15px 15px 50px;height:150px;overflow:hidden}.widget-advanced .widget-background{position:absolute;top:0;left:0;height:150px}.widget-advanced .widget-background-map{height:180px;width:100%}.widget-advanced .widget-content-image{position:absolute;top:0;left:0;width:100%;padding:15px;margin:0;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}.widget-advanced .widget-main{position:relative;padding:50px 15px 15px}.widget-advanced .widget-image-container{position:absolute;display:inline-block;padding:5px;width:74px;height:74px;top:-36px;left:50%;margin-left:-36px;border-radius:36px;background-color:#fff}.widget-advanced .widget-header .widget-image-container{position:static;left:auto;top:auto;margin:0}.widget-advanced-alt .widget-header,.widget-advanced-alt .widget-main{padding:15px}.widget-advanced-alt .widget-header{height:auto;min-height:150px}.content-float .pull-left{margin:0 20px 20px 0}.content-float .pull-right{margin:0 0 20px 20px}#to-top{display:none;position:fixed;bottom:55px;left:5px;border-radius:3px;padding:0 12px;font-size:28px;text-align:center;color:#fff;background-color:#000;opacity:.1;filter:alpha(opacity=10)}#to-top:hover{color:#fff;background-color:#1bbae1;text-decoration:none;opacity:1;filter:alpha(opacity=100)}#login-background{width:100%;height:224px;overflow:hidden;position:relative}#login-background>img{position:absolute;width:2560px;height:400px;left:50%;margin-left:-1280px}#login-container{position:absolute;width:300px;top:10px;left:50%;margin-left:-150px;z-index:1000}#login-container .login-title{padding:20px 10px;background:#394263;background:url(../img/template/ie8_opacity_dark_60.png) repeat;background:rgba(0,0,0,.6)}#login-container .login-title h1{font-size:26px;color:#fff}#login-container .login-title small{font-size:16px;color:#ddd}#login-container>.block{border:0}#login-container .register-terms{line-height:30px;margin-right:10px;float:left}.calendar-events{list-style:none;margin:0;padding:0}.calendar-events li{color:#fff;margin-bottom:5px;padding:5px 10px;border-radius:3px;background-color:#555;opacity:.85;filter:alpha(opacity=85)}.calendar-events li:hover{cursor:move;opacity:1;filter:alpha(opacity=100)}.gallery a img,.gallery img,.gallery-image img,a[data-toggle=lightbox-image] img{max-width:100%}a.gallery-link,a[data-toggle=lightbox-image]{cursor:pointer;cursor:-webkit-zoom-in;cursor:-moz-zoom-in;cursor:zoom-in}.gallery a:hover img,.gallery-image:hover img,a[data-toggle=lightbox-image]:hover img{opacity:.75;filter:alpha(opacity=75)}.gallery-image{position:relative}.gallery-image-options{position:absolute;top:0;bottom:0;left:0;right:0;display:none;padding:10px}.gallery-image:hover .gallery-image-options{display:block}.gallery>.row>div{margin-bottom:15px}.gallery.gallery-widget>.row>div{margin-bottom:0;padding-top:7px;padding-bottom:7px}.pie-chart .pie-avatar{position:absolute;top:8px;left:8px}.chart{height:360px}.chart-tooltip,.mini-chart-tooltip{position:absolute;display:none;color:#fff;background-color:#000;padding:4px 10px}.chart-pie-label{font-size:12px;text-align:center;padding:8px 12px;color:#fff}.mini-chart-tooltip{left:0;top:0;visibility:hidden}.timeline{position:relative}.timeline-header{margin:0;font-size:18px;font-weight:600;padding:0 15px;min-height:60px;line-height:60px;background-color:#fff;border-bottom:2px solid #f0f0f0;z-index:500}.timeline-list{list-style:none;margin:0;padding:0}.timeline-list:after{position:absolute;display:block;width:2px;top:0;left:95px;bottom:0;content:"";background-color:#f0f0f0;z-index:1}.timeline-header+.timeline-list:after{top:60px}.timeline-list li{position:relative;margin:0;padding:10px 0 ; border-bottom: 2px solid #fff }.timeline-list.timeline-hover li:hover{ }.timeline-list .timeline-icon{position:absolute;left:80px;top:10px;width:30px;height:30px;line-height:28px;font-size:14px;text-align:center;background-color:#fff;border:1px solid #ddd;border-radius:15px;z-index:500}.timeline-list .active .timeline-icon{background-color:#1bbae1;border-color:#1bbae1;color:#fff}.timeline-list .timeline-time{float:left;width:70px;text-align:right}.timeline-list .timeline-content{margin-left:120px}.block-content-full .timeline-content{padding-right:20px}.media-feed{margin-bottom:0}.media-feed>.media{margin-top:0;padding:20px 20px 0;border-top:1px dotted #dbe1e8}.media-feed>.media:first-child{border-top:0}.media-feed.media-feed-hover>.media:hover{background-color:#f9f9f9}#error-container{padding:120px 20px;position:relative}#error-container .error-options{position:absolute;top:20px;left:20px}#error-container h1{font-size:96px;color:#fff;margin-bottom:40px}#error-container h2{color:#ccc;margin-bottom:40px;line-height:1.4}#error-container form{padding:20px;border-radius:3px;background:#fff;background:url(../img/template/ie8_opacity_light_10.png) repeat;background:rgba(255,255,255,.1)}#error-container .form-control{border-color:#fff}.table.table-pricing{background-color:#fff}.table-pricing td,.table-pricing th{text-align:center}.table-pricing th{font-size:24px!important}.table-pricing td{font-size:15px;padding-top:12px!important;padding-bottom:12px!important}.table-pricing .table-price{background-color:#f9f9f9}.table-pricing .table-price.table-featured,.table-pricing.table-featured .table-price{background-color:#252525}.table-pricing th.table-featured,.table-pricing.table-featured th{background-color:#1bbae1;border-bottom:2px solid #394263;color:#fff}.table-pricing td.table-featured,.table-pricing.table-featured td{background-color:#394263;color:#fff}.navbar.navbar-default{background-color:#f9fafc}.navbar.navbar-inverse{background-color:#4c5471}.navbar-fixed-bottom,.navbar-fixed-top{border-width:0}.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{font-family:"Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;font-weight:300}.h1 .small,.h1 small,.h2 .small,.h2 small,.h3 .small,.h3 small,.h4 .small,.h4 small,.h5 .small,.h5 small,.h6 .small,.h6 small,h1 .small,h1 small,h2 .small,h2 small,h3 .small,h3 small,h4 .small,h4 small,h5 .small,h5 small,h6 .small,h6 small{font-weight:300;color:#777}h1,h2,h3{margin-bottom:15px}.text-primary,.text-primary:hover,a,a:focus,a:hover{color:#1bbae1}.text-danger,.text-danger:hover,a.text-danger,a.text-danger:focus,a.text-danger:hover{color:#e74c3c}.text-warning,.text-warning:hover,a.text-warning,a.text-warning:focus,a.text-warning:hover{color:#e67e22}.text-success,.text-success:hover,a.text-success,a.text-success:focus,a.text-success:hover{color:#27ae60}.text-info,.text-info:hover,a.text-info,a.text-info:focus,a.text-info:hover{color:#3498db}.text-muted,.text-muted:hover,a.text-muted,a.text-muted:focus,a.text-muted:hover{color:#999}b,strong{font-weight:600}ol,ul{padding-left:30px}.list-li-push li{margin-bottom:10px}p{line-height:1.6}article p{font-size:16px;line-height:1.8}.well{background-color:#f9f9f9;border:1px solid #eee}.page-header{border-bottom-width:1px;border-bottom-color:#ddd;margin:30px 0 20px}.sub-header{margin:10px 0 20px;padding:10px 0;border-bottom:1px dotted #ddd}blockquote{border-left-width:3px;margin:20px 0;padding:30px 60px 30px 20px;position:relative;width:100%;border-color:#eaedf1}blockquote:before{display:block;content:"\201C";font-family:serif;font-size:96px;position:absolute;right:10px;top:-30px;color:#eaedf1}blockquote.pull-right:before{left:10px;right:auto}label{font-weight:600}fieldset legend{font-size:16px;padding:30px 0 10px;border-bottom:2px solid #eaedf1}input[type=file]{padding-top:7px}input[type=email].form-control,input[type=password].form-control,input[type=text].form-control,textarea.form-control{-webkit-appearance:none}.form-control{font-size:13px;padding:6px 8px;max-width:100%;margin:1px 0;color:#394263;border-color:#dbe1e8}.form-control-borderless,.form-control-borderless .form-control,.form-control-borderless .input-group-addon,.form-control-borderless:focus{border:transparent!important}.input-group{margin-top:1px;margin-bottom:1px}.input-group .form-control{margin-top:0}.form-control:focus{border-color:#1bbae1}.help-block{color:#777;font-weight:400}.input-group-addon{min-width:45px;text-align:center;background-color:#fff;border-color:#dbe1e8}.form-horizontal .control-label{margin-bottom:5px}.form-bordered{margin:-15px -15px -1px}.modal-body .form-bordered{margin-bottom:-20px}.form-bordered fieldset legend{margin:0;padding-left:20px;padding-right:20px}.form-bordered .form-group{margin:0;border:0;padding:15px;border-bottom:1px dashed #eaedf1}.form-bordered .form-group.form-actions{background-color:#f9fafc;border-bottom:0;border-bottom-left-radius:4px;border-bottom-right-radius:4px}.form-horizontal.form-bordered .form-group{padding-left:0;padding-right:0}.form-bordered .help-block{margin-bottom:0}.has-success .checkbox,.has-success .checkbox-inline,.has-success .control-label,.has-success .help-block,.has-success .input-group-addon,.has-success .radio,.has-success .radio-inline{color:#27ae60}.has-success .form-control,.has-success .input-group-addon{border-color:#27ae60;background-color:#fff}.has-success .form-control:focus{border-color:#166638}.has-warning .checkbox,.has-warning .checkbox-inline,.has-warning .control-label,.has-warning .help-block,.has-warning .input-group-addon,.has-warning .radio,.has-warning .radio-inline{color:#e67e22}.has-warning .form-control,.has-warning .input-group-addon{border-color:#e67e22;background-color:#fff}.has-warning .form-control:focus{border-color:#b3621b}.has-error .checkbox,.has-error .checkbox-inline,.has-error .control-label,.has-error .help-block,.has-error .input-group-addon,.has-error .radio,.has-error .radio-inline{color:#e74c3c}.has-error .form-control,.has-error .input-group-addon{border-color:#e74c3c;background-color:#fff}.has-error .form-control:focus{border-color:#c0392b}.wizard-steps{border-bottom:1px solid #eaedf1;margin-bottom:20px}.form-bordered .wizard-steps{margin-bottom:0}.wizard-steps .row{margin:0}.wizard-steps .row div{padding:15px 0;font-size:15px;text-align:center}.form-bordered .wizard-steps .row div{padding-top:10px}.wizard-steps span{display:inline-block;width:100px;height:100px;line-height:100px;border:1px solid #1bbae1;border-radius:50px}.wizard-steps div.active span,.wizard-steps div.done span{background-color:#1bbae1;color:#fff}.wizard-steps div.done span{opacity:.25;filter:alpha(opacity=25)}.wizard-steps div.active span{opacity:1;filter:alpha(opacity=100)}.switch{margin:1px 0;position:relative;cursor:pointer}.switch input{position:absolute;opacity:0;filter:alpha(opacity=0)}.switch span{position:relative;display:inline-block;width:54px;height:28px;border-radius:28px;background-color:#f9f9f9;border:1px solid #ddd;-webkit-transition:background-color .35s;transition:background-color .35s}.switch span:after{content:"";position:absolute;left:7px;top:7px;bottom:7px;width:12px;background-color:#fff;border:1px solid #ddd;border-radius:24px;-webkit-box-shadow:1px 0 3px rgba(0,0,0,.05);box-shadow:1px 0 3px rgba(0,0,0,.05);-webkit-transition:all .15s ease-out;transition:all .15s ease-out}.switch input:checked+span:after{left:26px;width:24px;top:1px;bottom:1px;border:0;-webkit-box-shadow:-2px 0 3px rgba(0,0,0,.1);box-shadow:-2px 0 3px rgba(0,0,0,.1)}.switch input:checked+span{background-color:#eee}.switch-default span{border-color:#dbe1e8}.switch-default input:checked+span{background-color:#dbe1e8}.switch-primary span{border-color:#1bbae1}.switch-primary input:checked+span{background-color:#1bbae1}.switch-info span{border-color:#7abce7}.switch-info input:checked+span{background-color:#7abce7}.switch-success span{border-color:#aad178}.switch-success input:checked+span{background-color:#aad178}.switch-warning span{border-color:#f7be64}.switch-warning input:checked+span{background-color:#f7be64}.switch-danger span{border-color:#ef8a80}.switch-danger input:checked+span{background-color:#ef8a80}.table.table-vcenter td,.table.table-vcenter th{vertical-align:middle}.table-options{padding:6px 0}.table thead>tr>th{font-size:18px;font-weight:600}.table thead>tr>th>small{font-weight:400;font-size:75%}.table tfoot>tr>td,.table tfoot>tr>th,.table thead>tr>td,.table thead>tr>th{padding-top:14px;padding-bottom:14px}.table tfoot>tr>td,.table tfoot>tr>th{background-color:#f9fafc}.table-borderless tbody>tr>td,.table-borderless tbody>tr>th{border-top-width:0}.table tbody+tbody,.table tbody>tr>td,.table tbody>tr>th,.table tfoot>tr>td,.table tfoot>tr>th,.table thead>tr>td,.table thead>tr>th,.table-bordered,.table-bordered>tbody>tr>td,.table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>td,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{border-color:#eaedf1}.table-hover>tbody>tr:hover>td,.table-hover>tbody>tr:hover>th{background-color:#eaedf1}.list-group-item{border-color:#eaedf1}a.list-group-item.active,a.list-group-item.active:focus,a.list-group-item.active:hover{background-color:#1bbae1;border-color:#1bbae1}a.list-group-item.active .list-group-item-text,a.list-group-item.active:focus .list-group-item-text,a.list-group-item.active:hover .list-group-item-text{color:#fff}a.list-group-item:focus,a.list-group-item:hover{background-color:#f9fafc}a.list-group-item.active>.badge{background:url(../img/template/ie8_opacity_dark_40.png) repeat;background:rgba(0,0,0,.4);color:#fff}.dropdown-menu>.active>a,.dropdown-menu>.active>a:focus,.dropdown-menu>.active>a:hover,.dropdown-menu>li>a:focus,.dropdown-menu>li>a:hover,.nav .open>a,.nav .open>a:focus,.nav .open>a:hover,.nav-pills>li.active>a,.nav-pills>li.active>a:focus,.nav-pills>li.active>a:hover{color:#fff;background-color:#1bbae1}.nav>li i{font-size:14px}.nav-pills>.active>a>.badge{color:#1bbae1}.nav-stacked>li>a{margin:4px 0 0}.nav .caret,.nav a:focus .caret,.nav a:hover .caret{border-top-color:#1bbae1;border-bottom-color:#1bbae1}.nav>li>a:focus,.nav>li>a:hover{background-color:#f9fafc}.nav-tabs{border-bottom-color:#eaedf1}.nav-tabs>li{margin-bottom:0}.nav-tabs>li>a{padding-left:7px;padding-right:7px;margin-bottom:-1px}.nav-tabs>li>a:hover{border-color:#eaedf1}.nav-tabs>li.active>a,.nav-tabs>li.active>a:focus,.nav-tabs>li.active>a:hover{color:#394263;border-color:#eaedf1;border-bottom-color:transparent}.nav-pills>li.active>a>.badge{background:url(../img/template/ie8_opacity_dark_20.png) repeat;background:rgba(0,0,0,.2);color:#fff}.dropdown-menu{padding:0;font-size:13px;border-color:#dbe1e8;-webkit-box-shadow:0 3px 6px rgba(0,0,0,.1);box-shadow:0 3px 6px rgba(0,0,0,.1)}.dropdown-menu>li>a{padding:6px 10px}.dropdown-menu>li:first-child>a{border-top-left-radius:3px;border-top-right-radius:3px}.dropdown-menu>li:last-child>a{border-bottom-left-radius:3px;border-bottom-right-radius:3px}.dropdown-menu i{opacity:.2;filter:alpha(opacity=20);line-height:17px}.dropdown-menu a:hover i{opacity:.5;filter:alpha(opacity=50)}.dropdown-menu .divider{margin:2px 0;padding:0!important;background-color:#f0f0f0}li.dropdown-header{padding:5px 10px;color:#394263;background-color:#f9fafc;border-top:1px solid #eaedf1;border-bottom:1px solid #eaedf1}.dropdown-menu li:first-child.dropdown-header{border-top:0;border-top-left-radius:3px;border-top-right-radius:3px}.dropdown-menu.dropdown-custom{min-width:200px}.dropdown-menu.dropdown-custom>li{padding:8px 10px;font-size:12px}.dropdown-menu.dropdown-custom>li>a{border-radius:3px}.pagination>li>a,.pagination>li>span{color:#1bbae1;margin-left:5px;margin-right:5px;border:0!important;border-radius:25px!important}.pagination>.active>a,.pagination>.active>a:focus,.pagination>.active>a:hover,.pagination>.active>span,.pagination>.active>span:focus,.pagination>.active>span:hover{background-color:#1bbae1}.pager>li>a,.pager>li>span{border-color:#eaedf1}.pager>li>a:hover,.pagination>li>a:hover{background-color:#1bbae1;border-color:#1bbae1;color:#fff}.pager>li.disabled>a:hover{border-color:#eaedf1}.popover-title{background:0 0;border:0;font-size:17px;font-weight:600}.tooltip{z-index:1051}.tooltip.in{opacity:1;filter:alpha(opacity=100)}.tooltip-inner{padding:4px 6px;background-color:#000;color:#fff}.tooltip.top .tooltip-arrow,.tooltip.top-left .tooltip-arrow,.tooltip.top-right .tooltip-arrow{border-top-color:#000}.tooltip.right .tooltip-arrow{border-right-color:#000}.tooltip.left .tooltip-arrow{border-left-color:#000}.tooltip.bottom .tooltip-arrow,.tooltip.bottom-left .tooltip-arrow,.tooltip.bottom-right .tooltip-arrow{border-bottom-color:#000}.breadcrumb{background-color:#fff}.breadcrumb i{font-size:14px}.breadcrumb-top{margin:-10px -5px 10px;padding:7px 10px;border-top:1px solid #eaedf1;border-bottom:1px solid #dbe1e8;font-size:12px}.breadcrumb-top+.content-header,.content-header+.breadcrumb-top{margin-top:-11px}.breadcrumb>li+li:before{content:"\203a"}.progress,.progress-bar{height:20px;line-height:20px}.progress-bar-danger{background-color:#e74c3c}.progress-bar-warning{background-color:#f39c12}.progress-bar-success{background-color:#2ecc71}.progress-bar-info{background-color:#3498db}.modal-content{border-radius:3px}.modal-header{padding:15px 15px 14px;border-bottom:1px solid #eee;border-top-left-radius:4px;border-top-right-radius:4px}.modal-title{font-weight:300}.modal-body{padding:20px 15px}.modal-body .nav-tabs{margin:0 -15px 15px;padding:0 5px!important}.modal-footer{margin-top:0;padding:14px 15px 15px;border-top:1px solid #eee;background-color:#f9f9f9;border-bottom-left-radius:4px;border-bottom-right-radius:4px}.btn{margin:1px 0;background-color:#fff}.btn .fi,.btn .gi,.btn .hi,.btn .si{line-height:1}.btn.disabled,.btn[disabled],fieldset[disabled] .btn{opacity:.4;filter:alpha(opacity=40)}.block-options .btn,.input-group .btn,.modal-content .btn{margin-top:0;margin-bottom:0}.btn-default{background-color:#f1f3f6;border-color:#dbe1e8;color:#394263}.btn-default.btn-alt{background-color:#fff}.btn-default:hover{background-color:#eaedf1;border-color:#c2c8cf}.btn-default.active,.btn-default.disabled,.btn-default.disabled.active,.btn-default.disabled:active,.btn-default.disabled:focus,.btn-default.disabled:hover,.btn-default:active,.btn-default:focus,.btn-default[disabled].active,.btn-default[disabled]:active,.btn-default[disabled]:focus,.btn-default[disabled]:hover,.open .btn-default.dropdown-toggle,fieldset[disabled] .btn-default.active,fieldset[disabled] .btn-default:active,fieldset[disabled] .btn-default:focus,fieldset[disabled] .btn-default:hover{background-color:#eaedf1;border-color:#eaedf1}.btn-primary{background-color:#1BBAE1;border-color:#1C75CB;color:#fff}.btn-primary.btn-alt{background-color:#fff;color:#1C6FD0}.btn-primary:hover{background-color:#11203a;border-color:#1C6FD0;color:#fff}.btn-primary.active,.btn-primary.disabled,.btn-primary.disabled.active,.btn-primary.disabled:active,.btn-primary.disabled:focus,.btn-primary.disabled:hover,.btn-primary:active,.btn-primary:focus,.btn-primary[disabled].active,.btn-primary[disabled]:active,.btn-primary[disabled]:focus,.btn-primary[disabled]:hover,.open .btn-primary.dropdown-toggle,fieldset[disabled] .btn-primary.active,fieldset[disabled] .btn-primary:active,fieldset[disabled] .btn-primary:focus,fieldset[disabled] .btn-primary:hover{background-color:#11203A;border-color:#1bbae1;color:#fff}.btn-danger{background-color:#ef8a80;border-color:#e74c3c;color:#fff}.btn-danger.btn-alt{background-color:#fff;color:#e74c3c}.btn-danger:hover{background-color:#e74c3c;border-color:#9c3428;color:#fff}.btn-danger.active,.btn-danger.disabled,.btn-danger.disabled.active,.btn-danger.disabled:active,.btn-danger.disabled:focus,.btn-danger.disabled:hover,.btn-danger:active,.btn-danger:focus,.btn-danger[disabled].active,.btn-danger[disabled]:active,.btn-danger[disabled]:focus,.btn-danger[disabled]:hover,.open .btn-danger.dropdown-toggle,fieldset[disabled] .btn-danger.active,fieldset[disabled] .btn-danger:active,fieldset[disabled] .btn-danger:focus,fieldset[disabled] .btn-danger:hover{background-color:#e74c3c;border-color:#e74c3c;color:#fff}.btn-warning{background-color:#f7be64;border-color:#f39c12;color:#fff}.btn-warning.btn-alt{background-color:#fff;color:#f39c12}.btn-warning:hover{background-color:#f39c12;border-color:#b3730c;color:#fff}.btn-warning.active,.btn-warning.disabled,.btn-warning.disabled.active,.btn-warning.disabled:active,.btn-warning.disabled:focus,.btn-warning.disabled:hover,.btn-warning:active,.btn-warning:focus,.btn-warning[disabled].active,.btn-warning[disabled]:active,.btn-warning[disabled]:focus,.btn-warning[disabled]:hover,.open .btn-warning.dropdown-toggle,fieldset[disabled] .btn-warning.active,fieldset[disabled] .btn-warning:active,fieldset[disabled] .btn-warning:focus,fieldset[disabled] .btn-warning:hover{background-color:#f39c12;border-color:#f39c12;color:#fff}.btn-success{background-color:#aad178;border-color:#7db831;color:#fff}.btn-success.btn-alt{background-color:#fff;color:#7db831}.btn-success:hover{background-color:#7db831;border-color:#578022;color:#fff}.btn-success.active,.btn-success.disabled,.btn-success.disabled.active,.btn-success.disabled:active,.btn-success.disabled:focus,.btn-success.disabled:hover,.btn-success:active,.btn-success:focus,.btn-success[disabled].active,.btn-success[disabled]:active,.btn-success[disabled]:focus,.btn-success[disabled]:hover,.open .btn-success.dropdown-toggle,fieldset[disabled] .btn-success.active,fieldset[disabled] .btn-success:active,fieldset[disabled] .btn-success:focus,fieldset[disabled] .btn-success:hover{background-color:#7db831;border-color:#7db831;color:#fff}.btn-info{background-color:#7abce7;border-color:#3498db;color:#fff}.btn-info.btn-alt{background-color:#fff;color:#3498db}.btn-info:hover{background-color:#3498db;border-color:#2875a8;color:#fff}.btn-info.active,.btn-info.disabled,.btn-info.disabled.active,.btn-info.disabled:active,.btn-info.disabled:focus,.btn-info.disabled:hover,.btn-info:active,.btn-info:focus,.btn-info[disabled].active,.btn-info[disabled]:active,.btn-info[disabled]:focus,.btn-info[disabled]:hover,.open .btn-info.dropdown-toggle,fieldset[disabled] .btn-info.active,fieldset[disabled] .btn-info:active,fieldset[disabled] .btn-info:focus,fieldset[disabled] .btn-info:hover{background-color:#3498db;border-color:#3498db;color:#fff}.btn-link,.btn-link.btn-icon:focus,.btn-link.btn-icon:hover,.btn-link:focus,.btn-link:hover{color:#1bbae1}.btn-link.btn-icon{color:#999}.btn-link.btn-icon:focus,.btn-link.btn-icon:hover{text-decoration:none}.block-options .btn{border-radius:15px;padding-right:8px;padding-left:8px;min-width:30px;text-align:center}.panel{margin-bottom:20px}.panel-heading{padding:15px}.panel-title{font-size:14px}.panel-default>.panel-heading{background-color:#f9f9f9}.panel-group{margin-bottom:20px}pre{background:#151515;overflow:scroll}code{border:1px solid #fad4df;margin:1px 0;display:inline-block}.btn code{display:inline;margin:0}.alert{border-top-width:0;border-right-width:2px;border-bottom-width:0;border-left-width:2px}.alert-danger{color:#e74c3c;background-color:#ffd1cc;border-color:#ffb8b0}.alert-danger .alert-link{color:#e74c3c}.alert-warning{color:#e67e22;background-color:#ffe4cc;border-color:#ffd6b2}.alert-warning .alert-link{color:#e67e22}.alert-success{color:#27ae60;background-color:#daf2e4;border-color:#b8e5cb}.alert-success .alert-link{color:#27ae60}.alert-info{color:#3498db;background-color:#dae8f2;border-color:#b8d2e5}.alert-info .alert-link{color:#3498db}.alert-dismissable .close{top:-5px;right:-25px}.close{text-shadow:none}.alert.alert-alt{margin:0 0 2px;padding:5px;font-size:12px;border-width:0;border-left-width:2px}.alert.alert-alt small{opacity:.75;filter:alpha(opacity=75)}.alert-alt.alert-dismissable .close{right:0}.alert-alt.alert-dismissable .close:hover{color:#fff}.alert-danger.alert-alt{border-color:#e74c3c}.alert-warning.alert-alt{border-color:#e67e22}.alert-success.alert-alt{border-color:#27ae60}.alert-info.alert-alt{border-color:#3498db}.sidebar-content .alert.alert-alt{margin-left:-10px;padding-left:15px;background:0 0;color:#fff}.badge,.label{font-weight:400;font-size:90%}.label{padding:1px 4px}.badge{background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3);padding:3px 6px}.label-danger{background-color:#e74c3c}.label-danger[href]:focus,.label-danger[href]:hover{background-color:#ff5542}.label-warning{background-color:#e67e22}.label-warning[href]:focus,.label-warning[href]:hover{background-color:#ff8b26}.label-success{background-color:#27ae60}.label-success[href]:focus,.label-success[href]:hover{background-color:#2cc76c}.label-info{background-color:#2980b9}.label-info[href]:focus,.label-info[href]:hover{background-color:#2f92d4}.label-primary{background-color:#1bbae1}.label-primary[href]:focus,.label-primary[href]:hover{background-color:#5ac5e0}.label-default{background-color:#999}.label-default[href]:focus,.label-default[href]:hover{background-color:#777}.carousel-control.left,.carousel-control.left.no-hover:hover,.carousel-control.right,.carousel-control.right.no-hover:hover{background:0 0}.carousel-control.left:hover,.carousel-control.right:hover{background:url(../img/template/ie8_opacity_dark_30.png) repeat;background:rgba(0,0,0,.3)}.carousel-control span{position:absolute;top:50%;left:50%;z-index:5;display:inline-block}.carousel-control i{width:20px;height:20px;margin-top:-10px;margin-left:-10px}.alert,.carousel,.table,p{margin-bottom:20px}.btn.active,.form-control,.form-control:focus,.has-error .form-control:focus,.has-success .form-control:focus,.has-warning .form-control:focus,.navbar-form-custom .form-control:focus,.navbar-form-custom .form-control:hover,.open .btn.dropdown-toggle,.panel,.popover,.progress,.progress-bar{-webkit-box-shadow:none;box-shadow:none}.alert.alert-alt,.breadcrumb,.dropdown-menu,.navbar,.navbar-form-custom .form-control,.tooltip-inner{border-radius:0}.push-bit{margin-bottom:10px!important}.push{margin-bottom:15px!important}.push-top-bottom{margin-top:40px;margin-bottom:40px}.lt-ie9 .hidden-lt-ie9{display:none!important}.display-none{display:none}.remove-margin{margin:0!important}.remove-padding{padding:0!important}.remove-radius{border-radius:0!important}.remove-box-shadow{-webkit-box-shadow:none!important;box-shadow:none!important}.remove-transition{-moz-transition:none!important;-webkit-transition:none!important;transition:none!important}:focus{outline:0!important}.style-alt #page-content{background-color:#fff}.style-alt .block{border-color:#dbe1e8}.style-alt .block.block-alt-noborder{border-color:transparent}.style-alt .block-title{background-color:#dbe1e8;border-bottom-color:#dbe1e8}.style-alt #page-content+footer,.style-alt .breadcrumb-top+.content-header,.style-alt .content-header+.breadcrumb-top{background-color:#f9fafc}.style-alt .breadcrumb-top,.style-alt .content-header{border-bottom-color:#eaedf1}.style-alt #page-content+footer{border-top-color:#eaedf1}.style-alt .widget{background-color:#f6f6f6}.test-circle{display:inline-block;width:100px;height:100px;line-height:100px;font-size:18px;font-weight:600;text-align:center;border-radius:50px;background-color:#eee;border:2px solid #ccc;color:#fff;margin-bottom:15px}.themed-color{color:#1bbae1}.themed-border{border-color:#1bbae1}.themed-background{background-color:#1bbae1}.themed-color-dark{color:#394263}.themed-border-dark{border-color:#394263}.themed-background-dark{background-color:#394263}@media screen and (min-width:768px){#login-background{height:400px}#login-background>img{top:0}#login-container{width:480px;top:186px;margin-left:-240px}#main-container{min-width:768px}#page-content{padding:10px 10px 1px}#page-content+footer,.block,.block.full,.breadcrumb-top,.header-section,.modal-body{padding-left:20px;padding-right:20px}.block .block-content-full{margin:-20px -20px -1px}.block.full .block-content-full{margin:-20px}.breadcrumb-top,.content-header,.content-top{margin:-20px -20px 20px}.breadcrumb-top+.content-header,.content-header+.breadcrumb-top{margin-top:-21px}.block,.widget{margin-bottom:20px}.block-title,.block-top,.form-bordered{margin-left:-20px;margin-right:-20px}.form-bordered .form-group{padding-left:20px;padding-right:20px}.form-horizontal.form-bordered .form-group{padding-left:5px;padding-right:5px}.nav-tabs>li>a{padding-left:15px;padding-right:15px;margin-left:3px;margin-right:3px}}@media (min-width:992px){#sidebar{-webkit-transition:opacity .5s linear,background-color .2s ease-out;transition:opacity .5s linear,background-color .2s ease-out}#main-container,.header-fixed-bottom header,.header-fixed-top header{-webkit-transition:none;transition:none}#sidebar{width:65px!important;opacity:.2;filter:alpha(opacity=20)}#main-container{margin-left:65px!important}.sidebar-brand i{display:none}#sidebar:hover,.sidebar-full #sidebar{width:220px!important;opacity:1;filter:alpha(opacity=100)}#sidebar:hover .sidebar-brand i,.sidebar-full #sidebar .sidebar-brand i{display:inline-block}#sidebar:hover+#main-container,.sidebar-full #main-container{margin-left:220px!important}.sidebar-open header.navbar-fixed-bottom,.sidebar-open header.navbar-fixed-top,header.navbar-fixed-bottom,header.navbar-fixed-top{left:65px}#sidebar:hover+#main-container header.navbar-fixed-bottom,#sidebar:hover+#main-container header.navbar-fixed-top,.sidebar-full header.navbar-fixed-bottom,.sidebar-full header.navbar-fixed-top{left:220px}}@media (min-width:1200px){.header-fixed-bottom .sidebar-content,.header-fixed-top .sidebar-content{padding-bottom:0}article p{font-size:19px;line-height:1.9}}';
        $cuerpo .= '.timeline-icon2{position:position;width:30px;height:30px;line-height:28px;font-size:14px;text-align:center;background-color:#B4FF30;border:1px solid #B4FF30;border-radius:15px;z-index:500}';
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
        $cantidadAdmitidaDeMinutosParaCambiarElPassword = 120;
        $obj = new Fexcepciones();
        $cantidadMinutosTranscurridos = $obj->cantidadMinutosEntreDosFechas($fechaSolicitud . " " . $horaSolicitud);
        $parametro = Parametros::findFirst(array("parametro LIKE 'MINUTOS_PERMITIDOS_PARA_RESTABLECIMIENTO_PASSWORD' AND estado=1 AND baja_logica=1"));
        if (is_object($parametro)) {
            $cantidadAdmitidaDeMinutosParaCambiarElPassword = $parametro->nivel;
        }
        if ($idRelaboralSolicitante > 0 && $fechaSolicitud != '' && $horaSolicitud != '') {
            if ($cantidadMinutosTranscurridos <= $cantidadAdmitidaDeMinutosParaCambiarElPassword) {
                $relaboralSolicitante = Relaborales::findFirstById($idRelaboralSolicitante);
                if (is_object($relaboralSolicitante)) {

                    $cuerpo .= '<div style="min-height: 1020px;" id="page-content">';
                    $cuerpo .= '<div class="row">';
                    $cuerpo .= '<div class="col-md-4">';
                    $cuerpo .= '</div>';
                    $cuerpo .= '<div class="col-md-4">';
                    $cuerpo .= '<div class="block">';
                    $cuerpo .= '<h3>Restablecimiento de Contraseña<br><small id="smallRecomendacion">Al menos debe tener 8 car&aacute;cteres alfanum&eacute;ricos m&iacute;nimamente.</small></h3>';
                    $cuerpo .= '<div class="alert alert-success" id="divAlertSuccess">';
                    $cuerpo .= '<h4>&Eacute;xito!</h4>';
                    $cuerpo .= '<span id="spanAlertSuccess"></span>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '<div class="alert alert-danger" id="divAlertError">';
                    $cuerpo .= '<h4>Error!</h4>';
                    $cuerpo .= '<span id="spanAlertError"></span>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '<form action="" method="post" class="form-bordered" onsubmit="return false;">';
                    $cuerpo .= '<div style="text-align: center" id="divContador"><span id="spanContador">La ventana se cerrar&aacute; en <b id="bContador"></b> segundos.</span></div>';
                    $cuerpo .= '<div class="form-group" id="divPasswordA">';
                    $cuerpo .= '<label for="txtPasswordA">Contrase&ntilde;a</label>';
                    $cuerpo .= '<input id="txtPasswordA" name="txtPasswordA" class="form-control" placeholder="Introduzca la contrase&ntilde;a.." type="password">';
                    $cuerpo .= '<span class="help-block" id="spanErrorPasswordA"></span>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '<div class="form-group" id="divPasswordB">';
                    $cuerpo .= '<label for="txtPasswordB">Repita la Contrase&ntilde;a</label>';
                    $cuerpo .= '<input id="txtPasswordB" name="txtPasswordB" class="form-control" placeholder="Introduzca nuevamente la misma contrase&ntilde;a.." type="password">';
                    $cuerpo .= '<span class="help-block" id="spanErrorPasswordB"></span>';
                    $cuerpo .= '<input id="hdnValA" name="hdnValA" type="hidden" value="' . $idRelaboralSolicitanteCodificado . '">';
                    $cuerpo .= '<input id="hdnValB" name="hdnValB" type="hidden" value="' . $fechaSolicitudCodificado . '">';
                    $cuerpo .= '<input id="hdnValC" name="hdnValC" type="hidden" value="' . $horaSolicitudCodificado . '">';
                    $cuerpo .= '</div>';
                    $cuerpo .= '<div class="form-group form-actions">';
                    $cuerpo .= '<button id="btnGuardar" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-user"></i> Guardar</button>';
                    $cuerpo .= '<button id="btnResetear" type="reset" class="btn btn-sm btn-warning"><i class="fa fa-repeat"></i> Limpiar</button>';
                    $cuerpo .= '<button id="btnCerrarVentana" type="button" class="btn btn-sm btn-danger">Cerrar</button>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</form>';
                    /*$cuerpo .= '<div><a href="#" id="aCerrarVentana" ">Cerrar Ventana</a><br></div>';*/
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                    $cuerpo .= '</div>';
                }
                $cuerpo .= '<script type="text/javascript">';
                $cuerpo .= '
                $().ready(function() {
                  $("#divContador").hide();
                  $("#spanErrorPasswordA").html("");
                  $("#spanErrorPasswordB").html("");
                  $("#divPasswordA").removeClass("has-error");
                  $("#divPasswordB").removeClass("has-error");
                  $("#txtPasswordA").focus();
                  $("#divAlertSuccess").hide();
                  $("#divAlertError").hide();
                  $("#btnCerrarVentana").on("click",function(){
                    close();
                  });
                  $("#btnGuardar").on("click",function(){
                        var ok = validarFormulario();
                        if(ok){
                            var okk = guardar();
                            if(okk){
                                $("#smallRecomendacion").hide();
                                $("#divPasswordA").hide();
                                $("#divPasswordB").hide();
                                $("#btnGuardar").hide();
                                $("#btnResetear").hide();
                                $("#spanContador").show();

                            }
                        }
                  });
                });
                /**
                *   Función para la validación del formulario de restablecimiento de contraseñas.
                */
                function validarFormulario(){
                    var ok=true;
                    $("#divAlertSuccess").hide();
                    $("#divAlertError").hide();
                    $("#spanErrorPasswordA").html("");
                    $("#spanErrorPasswordB").html("");
                    $("#divPasswordA").removeClass("has-error");
                    $("#divPasswordB").removeClass("has-error");
                    var valA = $("#hdnValA").val();
                    var valB = $("#hdnValB").val();
                    var valC = $("#hdnValC").val();
                    var passA = $("#txtPasswordA").val();
                    var passB = $("#txtPasswordB").val();
                    var enfoque = null;
                    if(valA!=""&&valB!=""&&valC!=""){
                        if(passA!=""&&passB!=""){
                            if(passA==passB){
                                var n = passA.length;
                                if(n<8){
                                    ok = false;
                                    $("#spanErrorPasswordA").html("La contrase&ntilde;a debe tener al menos 8 car&aacute;cteres.");
                                    $("#divPasswordA").addClass("has-error");
                                    if(enfoque==null)enfoque = $("#txtPasswordA");
                                    $("#spanErrorPasswordB").html("La contrase&ntilde;a debe tener al menos 8 car&aacute;cteres.");
                                    $("#divPasswordB").addClass("has-error");
                                }
                            }else {
                                    $("#spanErrorPasswordA").html("Las contrase&ntilde;as no coinciden.");
                                    $("#divPasswordA").addClass("has-error");
                                    $("#spanErrorPasswordB").html("Las contrase&ntilde;as no coinciden.");
                                    $("#divPasswordB").addClass("has-error");
                                    if(enfoque==null)enfoque = $("#txtPasswordB");
                                    ok=false;
                            }
                        }else {
                            if(passA==""){
                                $("#spanErrorPasswordA").html("Introduzca la contrase&ntilde;a.");
                                $("#divPasswordA").addClass("has-error");
                                if(enfoque==null)enfoque = $("#txtPasswordA");
                            }
                            if(passB==""){
                                $("#spanErrorPasswordB").html("Introduzca nuevamente la misma contrase&ntilde;a.");
                                $("#divPasswordB").addClass("has-error");
                                if(enfoque==null)enfoque = $("#txtPasswordB");
                            }
                            enfoque.focus();
                            ok=false;
                        }
                    }else ok=false;
                    return ok;
                }
                /**
                * Función para guardar las nuevas contraseñas.
                */
                function guardar(){
                    var ok=true;
                    var valA = $("#hdnValA").val();
                    var valB = $("#hdnValB").val();
                    var valC = $("#hdnValC").val();
                    var passA = $("#txtPasswordA").val();
                    var passB = $("#txtPasswordB").val();
                    if(passA!=""&&passB!=""&&valA!=""&&valB!=""&&valC!=""){
                        var ok=$.ajax({
                                url:"/registerandrecover/redefinepassword/",
                                type:"POST",
                                datatype: "json",
                                async:false,
                                data:{
                                      val_a:valA,
                                      val_b:valB,
                                      val_c:valC,
                                      pass_a:passA,
                                      pass_b:passB
                                },
                                success: function(data) {  //alert(data);
                                    var res = jQuery.parseJSON(data);
                                    /**
                                     * Si se ha realizado correctamente el registro de la relación laboral
                                     */
                                    $(".msjes").hide();
                                    if(res.result==1){
                                    $("#divAlertSuccess").show();
                                    $("#spanAlertSuccess").html(res.msj);

                                    } else if(res.result==0){
                                        $("#divAlertError").show();
                                        $("#spanAlertError").html(res.msj);
                            }else{
                                /**
                                 * En caso de haberse presentado un error crítico al momento de registrarse la relación laboral
                                 */
                                 $("#divAlertError").show();
                                 $("#spanAlertError").html(res.msj);
                            }
                            var myCounter = new Countdown({
                                        seconds:5,  // number of seconds to count down
                                        onUpdateStatus: function(sec){
                                            $("#divContador").show();
                                            $("#bContador").text(sec);
                                        },
                                        onCounterEnd: function(){
                                            close();
                                        }
                                    });

                                    myCounter.start();

                        }, //mostramos el error
                        error: function() { alert("Se ha producido un error Inesperado"); }
                    });


                    }
                    return ok;
                }
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
            } else {


                $cuerpo .= '<div style="min-height: 1020px;" id="page-content">';
                $cuerpo .= '<div class="row">';
                $cuerpo .= '<div class="col-md-4">';
                $cuerpo .= '</div>';
                $cuerpo .= '<div class="col-md-4">';
                $cuerpo .= '<div class="block">';
                $cuerpo .= '<h3>Restablecimiento de Contraseña<br><small id="smallRecomendacion">Solicitud inv&aacute;lida</small></h3>';
                $cuerpo .= '<div class="alert alert-danger" id="divAlertError">';
                $cuerpo .= '<h4>Error!</h4>';
                $cuerpo .= '<span id="spanAlertError">El tiempo permitido para realizar la operaci&oacute;n concluy&oacute;.</span>';
                $cuerpo .= '</div>';
                $cuerpo .= '<form action="" method="post" class="form-bordered" onsubmit="return false;">';
                $cuerpo .= '<div style="text-align: center" id="divContador"><span id="spanContador">La ventana se cerrar&aacute; en <b id="bContador"></b> segundos.</span></div>';
                $cuerpo .= '<div class="form-group form-actions">';
                $cuerpo .= '<button id="btnCerrarVentana" type="button" class="btn btn-sm btn-danger">Cerrar</button>';
                $cuerpo .= '</div>';
                $cuerpo .= '</form>';
                /*$cuerpo .= '<div><a href="#" id="aCerrarVentana" ">Cerrar Ventana</a><br></div>';*/
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '</div>';
                $cuerpo .= '<script type="text/javascript">';
                $cuerpo .= '
                $().ready(function() {
                  $("#btnCerrarVentana").on("click",function(){
                    close();
                  });
                  $("#divContador").show();
                  var myCounter = new Countdown({
                  seconds:5,  // number of seconds to count down
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
                *   Función para el conteo de segundos.
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
    }

    /**
     * Función para el restablecimiento de las contraseñas del sistema para un determinado usuario existente.
     */
    public function redefinepasswordAction()
    {
        $msj = array();
        $this->view->disable();
        if (isset($_POST["val_a"]) && isset($_POST["val_b"]) && isset($_POST["val_c"])
            && isset($_POST["pass_a"]) && isset($_POST["pass_b"])
        ) {
            $idRelaboralSolicitanteCodificado = $_POST["val_a"];
            $fechaSolicitudCodificado = $_POST["val_b"];
            $horaSolicitudCodificado = $_POST["val_c"];
            $fechaSolicitud = base64_decode(str_pad(strtr($fechaSolicitudCodificado, '-_', '+/'), strlen($fechaSolicitudCodificado) % 4, '=', STR_PAD_RIGHT));
            $horaSolicitud = base64_decode(str_pad(strtr($horaSolicitudCodificado, '-_', '+/'), strlen($horaSolicitudCodificado) % 4, '=', STR_PAD_RIGHT));
            $fechaActual = date("d-m-Y");
            $horaActual = date("H:i:s");
            $passA = $_POST["pass_a"];
            $passB = $_POST["pass_b"];
            $cantidadAdmitidaDeMinutosParaCambiarElPassword = 120;
            $ok = $this->validarPasswords($passA, $passB, 5);
            $obj = new Fexcepciones();
            $cantidadMinutosTranscurridos = $obj->cantidadMinutosEntreDosFechas($fechaSolicitud . " " . $horaSolicitud);
            $parametro = Parametros::findFirst(array("parametro LIKE 'MINUTOS_PERMITIDOS_PARA_RESTABLECIMIENTO_PASSWORD' AND estado=1 AND baja_logica=1"));
            if (is_object($parametro)) {
                $cantidadAdmitidaDeMinutosParaCambiarElPassword = $parametro->nivel;
            }
            if ($ok && $cantidadMinutosTranscurridos <= $cantidadAdmitidaDeMinutosParaCambiarElPassword) {
                $idRelaboralSolicitante = base64_decode(str_pad(strtr($idRelaboralSolicitanteCodificado, '-_', '+/'), strlen($idRelaboralSolicitanteCodificado) % 4, '=', STR_PAD_RIGHT));
                /**
                 * De momento estos dos datos sólo se validan en cuanto a que no deben ser nulos.
                 */
                if ($idRelaboralSolicitante > 0) {
                    $relaboral = Relaborales::findFirstById($idRelaboralSolicitante);
                    if (is_object($relaboral) && $relaboral->estado > 0 && $relaboral->baja_logica == 1) {
                        $usuario = Usuarios::findFirst(array("persona_id=" . $relaboral->persona_id));
                        $password = hash_hmac('sha256', $passA, '2, 4, 6, 7, 9, 15, 20, 23, 25, 30');
                        $usuario->password = $password;
                        $ok = $usuario->save();
                        if ($ok) {
                            $msj = array('result' => 1, 'msj' => 'Restablecimiento exitoso de la contrase&ntilde;a. Ya puede ingresar al sistema.');
                        } else $msj = array('result' => 0, 'msj' => 'No se hall&oacute; registro coincidente de relaci&oacute;n laboral. Cont&aacute;ctese con el Administrador.');
                    } else $msj = array('result' => -1, 'msj' => 'No se hall&oacute; registro coincidente de relaci&oacute;n laboral. Cont&aacute;ctese con el Administrador.');
                } else $msj = array('result' => -2, 'msj' => 'No se hall&oacute; registro coincidente de relaci&oacute;n laboral. Cont&aacute;ctese con el Administrador.');
            } else {
                if (!$ok) {
                    $msj = array('result' => -3, 'msj' => 'Los datos enviados no cumplen los requisitos m&iacute;nimos para su registro.');
                } else {
                    if ($cantidadMinutosTranscurridos > $cantidadAdmitidaDeMinutosParaCambiarElPassword) {
                        $msj = array('result' => -4, 'msj' => 'El tiempo permitido para restablecer la contrase&ntilde;a ha concluido hace ' . ($cantidadMinutosTranscurridos - $cantidadAdmitidaDeMinutosParaCambiarElPassword) . " minutos.");
                    }
                }

            }
        }
        echo json_encode($msj);
    }


    /**
     * Función para validar los datos enviados para los password en el lado del servidor.
     * @param $passA
     * @param $passB
     * @param $numero
     * @return bool
     */
    function validarPasswords($passA, $passB, $numero)
    {
        $ok = true;
        if ($passA != "" && $passB != "") {
            if ($passA == $passB) {
                $n = strlen($passB);
                if ($n < $numero) {
                    $ok = false;
                }
            } else $ok = false;
        } else $ok = false;
        return $ok;
    }

    /**
     * Función para obtener una cadena aleatoria con el número de carácteres definidos por el parámetro enviado.
     * @param $numero
     * @return string
     */
    function obtenerCadenaAleatoria($numero)
    {
        $caracter = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime() * 1000000);
        $rand = "";
        for ($i = 0; $i < $numero; $i++) {
            $rand .= $caracter[rand() % strlen($caracter)];
        }
        return $rand;
    }

    /**
     * Función para obtener una cadena randómica.
     * @param int $length
     * @param bool $uc
     * @param bool $n
     * @param bool $sc
     * @return string
     */
    function obtenerCandenaRandomica($length = 10, $uc = TRUE, $n = TRUE, $sc = FALSE)
    {
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if ($uc == 1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($n == 1) $source .= '1234567890';
        if ($sc == 1) $source .= '|@#~$%()=^*+[]{}-_';
        if ($length > 0) {
            $rstr = "";
            $source = str_split($source, 1);
            for ($i = 1; $i <= $length; $i++) {
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1, count($source));
                $rstr .= $source[$num - 1];
            }

        }
        return $rstr;
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