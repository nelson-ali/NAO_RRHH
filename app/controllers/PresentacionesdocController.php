<?php

/*
*   Oasis - Sistema de Gesti�n para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Telef�rico"
*   Versi�n:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creaci�n:  18-02-2016
*/

class PresentacionesdocController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Funci�n para la carga de la p�gina de gesti�n de relaciones laborales.
     * Se cargan los combos necesarios.
     */
    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $version);
        // $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        // $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);

        $this->assets->addJs('/js/fileinput/fileinput.min.js?v=' . $version);
        $this->assets->addCss('/assets/css/fileinput.min.css?v=' . $version);
        $this->assets->addCss('/assets/css/bootstrap.vertical-tabs.min.css?v=' . $version);


        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.tab.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.index.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.list.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.list.count.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.approve.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.turns.excepts.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.down.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.move.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.export.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.view.js?v=' . $version);
        $this->assets->addJs('/js/presentacionesdoc/oasis.presentacionesdoc.view.splitter.js?v=' . $version);
        $this->assets->addJs('/js/datetimepicker/bootstrap-datetimepicker.min.js?v=' . $version);
        $this->assets->addCss('/js/datetimepicker/bootstrap-datetimepicker.min.css?v=' . $version);
        $maximo = 8388608;
        $max = Parametros::findFirst("parametro LIKE 'PRESENTACIONESDOC_MAX_FILE_SIZE' AND estado=1 AND baja_logica=1");
        if (is_object($max)) {
            $maximo = $max->nivel;
        }
        $this->view->setVar('max_file_size', $maximo);
    }

    /**
     * Funci�n para la obtenci�n del listado de grupos de tipos de documentos.
     */
    public function grupoarchivosAction()
    {
        $this->view->disable();
        $grupos = array();
        $res = Parametros::find(array('parametro LIKE "grupoarchivos" AND baja_logica = 1', 'order' => 'cast(nivel as integer) ASC'));
        foreach ($res as $v) {
            $grupos[] = array(
                'id' => $v->nivel,
                'grupo' => $v->valor_1,
                'grupo_html' => $v->valor_2
            );
        }
        echo json_encode($grupos);
    }

    /**
     * Funci�n para la obtenci�n del listado de documentos presentados de acuerdo a un tipo definido de documento, un registro de relaci�n laboral o persona.
     */
    public function listpresAction()
    {
        $this->view->disable();
        $presentacionesdoc = array();
        $idRelaboral = $_GET["id_relaboral"];
        $idPersona = $_GET["id_persona"];
        $idTipoDocumento = $_GET["id_tipodocumento"];
        $opcion = $_GET["opcion"];
        $campoJson = null;
        if ($idRelaboral >= 0) {
            $obj = new Presentacionesdoc();
            $res = $obj->getAllByPersonaRelaboralAndTipoDocumento($opcion, $idPersona, $idRelaboral, $idTipoDocumento);
            $contador = 0;
            foreach ($res as $v) {
                $campoJson = "";
                if ($v->columnas_aux != '') {
                    $campoJson = json_decode($v->columnas_aux);
                }
                $presentacionesdoc[$contador] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'fecha_ing' => $v->fecha_ing != "" ? date("d-m-Y", strtotime($v->fecha_ing)) : "",
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'cargo' => $v->cargo,
                    'nivelsalarial' => $v->nivelsalarial,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'proceso_codigo' => $v->proceso_codigo,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'id_gerencia_administrativa' => $v->id_gerencia_administrativa,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'id_departamento_administrativo' => $v->id_departamento_administrativo,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'id_organigrama' => $v->id_organigrama,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'organigrama_sigla' => $v->organigrama_sigla,
                    'organigrama_orden' => $v->organigrama_orden,
                    'id_area' => $v->id_area,
                    'area' => $v->area,
                    'id_ubicacion' => $v->id_ubicacion,
                    'ubicacion' => $v->ubicacion,
                    'partida' => $v->partida,
                    'relaboral_observacion' => ($v->relaboral_observacion != null) ? $v->relaboral_observacion : "",
                    'relaboral_estado' => $v->relaboral_estado,
                    'relaboral_estado_descripcion' => $v->relaboral_estado_descripcion,

                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,


                    'id_presentaciondoc' => $v->id_presentaciondoc,
                    'id_tipodocumento' => $v->id_tipodocumento,
                    /*'id_relaboral' => $v->id_relaboral,
                    'id_persona' => $v->id_persona,*/
                    'id_tipofechaemidoc' => $v->id_tipofechaemidoc,
                    'gestion_emi' => $v->gestion_emi,
                    'trim_emi' => $v->trim_emi,
                    'trim_emi_descripcion' => $v->trim_emi_descripcion,
                    'mes_emi' => $v->mes_emi,
                    'mes_emi_descripcion' => $v->mes_emi_descripcion,
                    'dia_emi' => $v->dia_emi,
                    'hora_emi' => $v->hora_emi,
                    'fecha_emi' => $v->fecha_emi != "" ? date($v->id_tipofechaemidoc == 4 ? "d-m-Y h:i:s" : "d-m-Y", strtotime($v->fecha_emi)) : "",
                    'fecha_pres' => $v->fecha_pres != "" ? date("d-m-Y", strtotime($v->fecha_pres)) : "",
                    'id_institucion' => $v->id_institucion,
                    'institucion' => $v->institucion,
                    'formato_archivo_digital' => $v->formato_archivo_digital,
                    'digital' => $v->digital,
                    'columnas_aux' => $v->columnas_aux,
                    'presentacionesdoc_observacion' => $v->presentacionesdoc_observacion,
                    'presentacionesdoc_estado' => $v->presentacionesdoc_estado,
                    'presentacionesdoc_estado_descripcion' => $v->presentacionesdoc_estado_descripcion
                );
                /**
                 * Se agregan los valores para los campos auxiliares
                 */
                if ($campoJson != null) {
                    foreach ($campoJson as $key => $val) {
                        $presentacionesdoc[$contador][$key] = $val;
                    }
                }
                $contador++;
            }
        }
        echo json_encode($presentacionesdoc);
    }

    /**
     * Funci�n para la obtenci�n del listado de emisores para la presentaci�n de documentos.
     */
    public function listemisoresAction()
    {
        $this->view->disable();
        $instituciones = array();
        $idTipoEmisor = $_POST["id_tipoemisordoc"];
        $res = null;
        switch ($idTipoEmisor) {
            case 2:
                /**
                 * La misma empresa.
                 */
                $res = Instituciones::find(array('id=1'));
                break;
            case 3:
                $res = Instituciones::find(array('id!=1 AND tipo_institucion LIKE "%ESTATAL%" AND estado=1 AND baja_logica=1'));
                break;
            case 4:
                $res = Instituciones::find(array('id!=1 AND tipo_institucion LIKE "%PRIVADA%" AND estado=1 AND baja_logica=1'));
                break;
            case 5:
                $res = Instituciones::find(array('id!=1 AND (tipo_institucion LIKE "%MIXTA%" OR tipo_institucion LIKE "%ESTATAL%" OR tipo_institucion LIKE "%PRIVADA%") AND estado=1 AND baja_logica=1'));
                break;
            default:
                $res = Instituciones::find(array('estado=1 AND baja_logica=1'));
                break;
        }
        if ($res != null && count($res) > 0 && $idTipoEmisor > 0) {
            if (count($res) > 0) {
                foreach ($res as $v) {
                    $agregacionSigla = "";
                    if ($v->visible == 1) {
                        $agregacionSigla = " (" . $v->sigla . ")";
                    }
                    $instituciones[] = array(
                        'id' => $v->id,
                        'razon_social' => $v->razon_social . $agregacionSigla,
                        'sigla' => $v->sigla,
                        'tipo_institucion' => $v->tipo_institucion,
                        'representante_legal' => $v->representante_legal,
                        'nit' => $v->nit
                    );
                }
            }
        }
        echo json_encode($instituciones);
    }

    /**
     * Funci�n para guardar un registro de presentaci�n de documentos.
     */
    public function saveAction()
    {
        $this->view->disable();
        $msj = Array();
        $auth = $this->session->get('auth');
        $user_mod_id = $user_reg_id = $auth['id'];
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edici�n de Presentaci�n de documento
             */
            $idPresentacionDoc = $_POST["id"];
            $idTipodocumento = $_POST['id_tipodocumento'];
            $idPersona = $_POST['id_persona'];
            $idRelaboral = $_POST['id_relaboral'];
            $idInstitucion = null;
            if ($_POST['id_institucion'] > 0) {
                $idInstitucion = $_POST['id_institucion'];
            }
            $gestionEmi = null;
            if ($_POST['gestion_emi'] > 0) {
                $gestionEmi = $_POST['gestion_emi'];
            }
            $trimEmi = null;
            if ($_POST['trim_emi'] > 0) {
                $trimEmi = $_POST['trim_emi'];
            }
            $mesEmi = null;
            if ($_POST['mes_emi'] > 0) {
                $mesEmi = $_POST['mes_emi'];
            }
            $diaEmi = null;
            if ($_POST['dia_emi'] > 0) {
                $diaEmi = $_POST['dia_emi'];
            }
            $horaEmi = null;
            if ($_POST['hora_emi'] > 0) {
                $horaEmi = $_POST['hora_emi'];
            }
            $fechaPres = null;
            if ($_POST['fecha_pres'] != '') {
                $fechaPres = $_POST['fecha_pres'];
            }
            $digital = null;
            if ($_POST['digital'] != '') {
                $digital = $_POST['digital'];
            }
            $columnasAux = null;
            if (isset($_POST["columnas_aux"]) && $_POST["columnas_aux"] != '') {
                $columnasAux = $_POST["columnas_aux"];
                $columnasAux = str_replace('|', '"', $columnasAux);
            }
            $observacion = $_POST['observacion'];
            $hoy = date("Y-m-d H:i:s");
            if ($idPresentacionDoc > 0 && $idTipodocumento > 0 && $idPersona > 0 && $idRelaboral > 0 && $fechaPres != '') {
                $objPresentacionesDoc = Presentacionesdoc::findFirstById($idPresentacionDoc);
                if (count($objPresentacionesDoc) > 0) {
                    $objPresentacionesDoc->tipodocumento_id = $idTipodocumento;
                    $objPresentacionesDoc->persona_id = $idPersona;
                    $objPresentacionesDoc->relaboral_id = $idRelaboral;
                    $objPresentacionesDoc->institucion_id = $idInstitucion;
                    $objPresentacionesDoc->gestion_emi = $gestionEmi;
                    $objPresentacionesDoc->trim_emi = $trimEmi;
                    $objPresentacionesDoc->mes_emi = $mesEmi;
                    $objPresentacionesDoc->dia_emi = $diaEmi;
                    $objPresentacionesDoc->hora_emi = $horaEmi;
                    $fechaPresRegistrada = trim($objPresentacionesDoc->fecha_pres != "" ? date("d-m-Y", strtotime($objPresentacionesDoc->fecha_pres)) : "");
                    if ($fechaPresRegistrada != $fechaPres && $objPresentacionesDoc->digital != null) {
                        $objPD = new Presentacionesdoc();
                        $objPresentacionesDoc->digital = $objPD->renameFile($objPresentacionesDoc, $fechaPres);
                    }
                    $objPresentacionesDoc->fecha_pres = $fechaPres;
                    $objPresentacionesDoc->columnas_aux = $columnasAux;
                    $objPresentacionesDoc->observacion = $observacion;
                    $objPresentacionesDoc->estado = 1;
                    $objPresentacionesDoc->visible = 1;
                    $objPresentacionesDoc->baja_logica = 1;
                    $objPresentacionesDoc->agrupador = 0;
                    $objPresentacionesDoc->user_mod_id = $user_mod_id;
                    $objPresentacionesDoc->fecha_mod = $hoy;
                    try {
                        $ok = $objPresentacionesDoc->save();
                        if ($ok) {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente el registro de presentaci&oacute;n del documento.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro de presentacioacute;n del documento.');
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la presentaci&oacute;n del documento.');
                    }
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        } else {
            /**
             * Registro de Horario
             */
            $idTipodocumento = $_POST['id_tipodocumento'];
            $idPersona = $_POST['id_persona'];
            $idRelaboral = $_POST['id_relaboral'];
            $idInstitucion = null;
            if ($_POST['id_institucion'] > 0) {
                $idInstitucion = $_POST['id_institucion'];
            }
            $gestionEmi = null;
            if ($_POST['gestion_emi'] > 0) {
                $gestionEmi = $_POST['gestion_emi'];
            }
            $trimEmi = null;
            if ($_POST['trim_emi'] > 0) {
                $trimEmi = $_POST['trim_emi'];
            }
            $mesEmi = null;
            if ($_POST['mes_emi'] > 0) {
                $mesEmi = $_POST['mes_emi'];
            }
            $diaEmi = null;
            if ($_POST['dia_emi'] > 0) {
                $diaEmi = $_POST['dia_emi'];
            }
            $horaEmi = null;
            if ($_POST['hora_emi'] > 0) {
                $horaEmi = $_POST['hora_emi'];
            }
            $fechaPres = null;
            if ($_POST['fecha_pres'] != '') {
                $fechaPres = $_POST['fecha_pres'];
            }
            $digital = null;
            if ($_POST['digital'] != '') {
                $digital = $_POST['digital'];
            }
            $columnasAux = null;
            if (isset($_POST["columnas_aux"]) && $_POST["columnas_aux"] != '') {
                $columnasAux = $_POST["columnas_aux"];
                $columnasAux = str_replace('|', '"', $columnasAux);
            }
            $observacion = $_POST['observacion'];
            $hoy = date("Y-m-d H:i:s");
            if ($idTipodocumento > 0 && $idPersona > 0 && $idRelaboral > 0 && $fechaPres != '') {
                $objPresentacionesDoc = new Presentacionesdoc();
                $objPresentacionesDoc->tipodocumento_id = $idTipodocumento;
                $objPresentacionesDoc->persona_id = $idPersona;
                $objPresentacionesDoc->relaboral_id = $idRelaboral;
                $objPresentacionesDoc->institucion_id = $idInstitucion;
                $objPresentacionesDoc->gestion_emi = $gestionEmi;
                $objPresentacionesDoc->trim_emi = $trimEmi;
                $objPresentacionesDoc->mes_emi = $mesEmi;
                $objPresentacionesDoc->dia_emi = $diaEmi;
                $objPresentacionesDoc->hora_emi = $horaEmi;
                $objPresentacionesDoc->fecha_pres = $fechaPres;
                $objPresentacionesDoc->digital = $digital;
                $objPresentacionesDoc->columnas_aux = $columnasAux;
                $objPresentacionesDoc->observacion = $observacion;
                $objPresentacionesDoc->estado = 1;
                $objPresentacionesDoc->visible = 1;
                $objPresentacionesDoc->baja_logica = 1;
                $objPresentacionesDoc->agrupador = 0;
                $objPresentacionesDoc->user_reg_id = $user_reg_id;
                $objPresentacionesDoc->fecha_reg = $hoy;
                try {
                    $ok = $objPresentacionesDoc->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente el registro de presentaci&oacute;n del documento.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el registro de presentacioacute;n del documento.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la presentaci&oacute;n del documento.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Funci�n para la baja del registro de presentaci�n de un documento.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Cr�tico Error
     *  -2: Error de Conexi�n
     *  -3: Usuario no Autorizado
     */
    public function delAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                /**
                 * Baja de registro
                 */
                $objPresentacionesDoc = Presentacionesdoc::findFirstById($_POST["id"]);
                $objPresentacionesDoc->estado = 0;
                $objPresentacionesDoc->baja_logica = 0;
                $objPresentacionesDoc->user_mod_id = $user_mod_id;
                $objPresentacionesDoc->fecha_mod = $hoy;
                if ($objPresentacionesDoc->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                } else {
                    foreach ($objPresentacionesDoc->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de presentaci&oacute;n de un documento.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de presentaci&oacute;n de documento.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de presentaci&oacute;n de documento.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para el almacenamiento de los registros.
     */
    public function setarchivoAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $separador = "_";

        // 'files' Refiere a los archivos adjuntados en el env�o
        if (empty($_FILES['files'])) {
            $msj = array('result' => -2, 'msj' => 'Error: El archivo no fue cargado.');
            echo json_encode($msj);
            // or you can throw an exception
            //return; // terminate
        } else {
            // Obtiene los archivos enviados
            $images = $_FILES['files'];
            // Obtiene el identificador del registro de relaci�n laboral
            $idRelaboral = empty($_POST['id_relaboral']) ? 0 : $_POST['id_relaboral'];
            // Obtiene el identificador del registro de la persona en el sistema
            $idPersona = empty($_POST['id_persona']) ? 0 : $_POST['id_persona'];
            // Obtiene el identificador del tipo de documento
            $idTipoDocumento = empty($_POST['id_tipodocumento']) ? 0 : $_POST['id_tipodocumento'];
            // Obtiene el id del registro de presentaci�n
            $idPresentacionDoc = empty($_POST['id_presentaciondoc']) ? 0 : $_POST['id_presentaciondoc'];

            /*echo "<p>-->idTipoDocumento: ".$idTipoDocumento;
            echo "<p>-->idPresentacionDoc: ".$idPresentacionDoc;*/
            // Una variable bandera para ver si est� bien
            $success = null;

            // Direcci�n donde se almacenar�n los archivos
            $paths = [];

            // Obtiene el nocbre de los archivos
            $filenames = $images['name'];

            // recorre y procesa los archivos
            for ($i = 0; $i < count($filenames); $i++) {
                $ext = explode('.', basename($filenames[$i]));
                if ($idRelaboral > 0 && $idPersona > 0 && $idTipoDocumento > 0 && $idPresentacionDoc > 0) {
                    $objRel = new Frelaborales();
                    $rel = $objRel->getOne($idRelaboral);
                    if (count($rel) > 0) {
                        $relaboral = $rel[0];
                    }
                    $objTipoDocumento = Tiposdocumentos::findFirstById($idTipoDocumento);
                    $objPresentacionDoc = Presentacionesdoc::findFirstById($idPresentacionDoc);
                    if ($relaboral != null && $objTipoDocumento != null && $objPresentacionDoc != null) {
                        $directorio = "../public/files/pres/" . trim($relaboral->ci);
                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0700);
                        }
                        $fechaPres = date("Y-m-d", strtotime($objPresentacionDoc->fecha_pres));
                        $target = $directorio . "/" . trim($relaboral->ci) . $separador . trim(strtoupper($objTipoDocumento->codigo)) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . strtolower(array_pop($ext));
                        if (move_uploaded_file($images['tmp_name'][$i], $target)) {
                            $objPresentacionDoc->digital = $target;
                            $objPresentacionDoc->user_mod_id = $user_mod_id;
                            $objPresentacionDoc->fecha_mod = $hoy;
                            if ($objPresentacionDoc->save()) {
                                $success = true;
                                $paths[] = $target;
                            }
                        } else {
                            $success = false;
                            break;
                        }
                    }
                }
            }
            // checkea y procesa de acuerdo al estado exitoso
            if ($success === true) {
                $msj = array('result' => 1, 'msj' => '&Eacute;xito: El archivo fue cargado exitosamente.');
            } elseif ($success === false) {
                $msj = array('result' => 0, 'msj' => 'Error: Error mientras se cargaba el archivo. Contactese con el Administrador.');
                foreach ($paths as $file) {
                    unlink($file);
                }
            } else {
                $msj = array('result' => -1, 'msj' => 'Error: Ningun archivo fue procesado.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Funci�n para mostrar el archivo correspondiente a los datos enviados.
     */
    public function getarchivoAction()
    {
        $this->view->disable();
        $idPersona = $_POST["id_persona"];
        $idRelaboral = $_POST["id_relaboral"];
        $idTipoDocumento = $_POST["id_tipodocumento"];
        $idPresentacionDoc = $_POST["id_presentaciondoc"];
        $separador = "_";
        $msj = Array();
        if ($idRelaboral > 0 && $idPersona > 0 && $idTipoDocumento > 0 && $idPresentacionDoc > 0) {
            $objRel = new Frelaborales();
            $rel = $objRel->getOne($idRelaboral);
            if (count($rel) > 0) {
                $relaboral = $rel[0];
            }
            $objTipoDocumento = Tiposdocumentos::findFirstById($idTipoDocumento);
            $objPresentacionDoc = Presentacionesdoc::findFirstById($idPresentacionDoc);
            if ($relaboral != null && $objTipoDocumento != null && $objPresentacionDoc != null && $objTipoDocumento->formato_archivo_digital != null && $objTipoDocumento->formato_archivo_digital != '') {
                $directorio = "files/pres/" . trim($relaboral->ci);
                $fechaPres = date("Y-m-d", strtotime($objPresentacionDoc->fecha_pres));
                $findme = ',';
                $pos = strpos($objTipoDocumento->formato_archivo_digital, $findme);
                if ($pos === false) {
                    $extension = strtolower($objTipoDocumento->formato_archivo_digital);
                    $rutaArchivo = $directorio . "/" . trim($relaboral->ci) . $separador . trim(strtoupper($objTipoDocumento->codigo)) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . $extension;
                    if (file_exists($rutaArchivo)) {
                        rename($rutaArchivo, $rutaArchivo);
                        /**
                         * A objeto de refrescar el cache de la imagen
                         */
                        $rutaArchivo .= "?" . rand(5, 15);
                        switch ($extension) {
                            case "pdf":
                                $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<embed src="' . $rutaArchivo . '" width="100%" height="600px" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">', 'msj' => 'Resultado exitoso.');
                                break;
                            case "txt":
                                $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<object width="100%" height="100%" type="text/plain" data="' . $rutaArchivo . '"></object>', 'msj' => 'Resultado exitoso.');
                                break;
                            default:
                                $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<img style="width: 100%;height: 100%;" class="img-rounded pull-right" id="imgArchivo" src="' . $rutaArchivo . '"/>', 'msj' => 'Resultado exitoso.');
                        }
                    } else {

                        $rutaArchivo = $directorio . "/" . trim($relaboral->ci) . $separador . trim(strtolower($objTipoDocumento->codigo)) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . $extension;
                        $rutaArchivoMayuscula = $directorio . "/" . trim($relaboral->ci) . $separador . trim(strtoupper($objTipoDocumento->codigo)) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . $extension;
                        if (file_exists($rutaArchivo)) {
                            rename($rutaArchivo, $rutaArchivoMayuscula);
                            $rutaArchivo = $rutaArchivoMayuscula;
                            /**
                             * A objeto de refrescar el cache de la imagen
                             */
                            $rutaArchivo .= "?" . rand(5, 15);
                            switch ($extension) {
                                case "pdf":
                                    $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<embed src="' . $rutaArchivo . '" width="100%" height="600px" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">', 'msj' => 'Resultado exitoso.');
                                    break;
                                case "txt":
                                    $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<object width="100%" height="100%" type="text/plain" data="' . $rutaArchivo . '"></object>', 'msj' => 'Resultado exitoso.');
                                    break;
                                default:
                                    $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<img style="width: 100%;height: 100%;" class="img-rounded pull-right" id="imgArchivo" src="' . $rutaArchivo . '"/>', 'msj' => 'Resultado exitoso.');
                            }
                        }
                    }
                } else {
                    $formatos = explode(",", strtolower($objTipoDocumento->formato_archivo_digital));
                    foreach ($formatos as $extension) {
                        $rutaArchivo = $directorio . "/" . trim($relaboral->ci) . $separador . trim(strtoupper($objTipoDocumento->codigo)) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . $extension;
                        if (file_exists($rutaArchivo)) {
                            rename($rutaArchivo, $rutaArchivo);
                            /**
                             * A objeto de refrescar el cache de la imagen
                             */
                            $rutaArchivo .= "?" . rand(5, 15);
                            switch ($extension) {
                                case 'pdf':
                                    $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<embed src="' . $rutaArchivo . '" width="100%" height="600px" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">', 'msj' => 'Resultado exitoso.');
                                    break;
                                case "txt":
                                    $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<object width="100%" height="100%" type="text/plain" data="' . $rutaArchivo . '"></object>', 'msj' => 'Resultado exitoso.');
                                    break;
                                default:
                                    $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<img style="width: 100%;height: 100%;" class="img-rounded pull-right" id="imgArchivo" src="' . $rutaArchivo . '"/>', 'msj' => 'Resultado exitoso.');
                            }
                            /**
                             * Se rompe el ciclo debido a que basta un arhivo.
                             */
                            break;
                        } else {
                            $rutaArchivo = $directorio . "/" . trim($relaboral->ci) . $separador . trim(strtolower($objTipoDocumento->codigo)) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . $extension;
                            $rutaArchivoMayuscula = $directorio . "/" . trim($relaboral->ci) . $separador . trim(strtoupper($objTipoDocumento->codigo)) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . $extension;
                            if (file_exists($rutaArchivo)) {
                                rename($rutaArchivo, $rutaArchivoMayuscula);
                                $rutaArchivo = $rutaArchivoMayuscula;
                                /**
                                 * A objeto de refrescar el cache de la imagen
                                 */
                                $rutaArchivo .= "?" . rand(5, 15);
                                switch ($extension) {
                                    case 'pdf':
                                        $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<embed src="' . $rutaArchivo . '" width="100%" height="600px" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">', 'msj' => 'Resultado exitoso.');
                                        break;
                                    case "txt":
                                        $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<object width="100%" height="100%" type="text/plain" data="' . $rutaArchivo . '"></object>', 'msj' => 'Resultado exitoso.');
                                        break;
                                    default:
                                        $msj = array('result' => 1, 'ruta' => $rutaArchivo, 'html' => '<img style="width: 100%;height: 100%;" class="img-rounded pull-right" id="imgArchivo" src="' . $rutaArchivo . '"/>', 'msj' => 'Resultado exitoso.');
                                }
                                /**
                                 * Se rompe el ciclo debido a que basta un arhivo.
                                 */
                                break;
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($msj);
    }

    /**
     * Funci�n para la eliminaci�n de un archivo digital.
     */
    public function delarchivoAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $idPersona = $_POST["id_persona"];
        $idRelaboral = $_POST["id_relaboral"];
        $idTipoDocumento = $_POST["id_tipodocumento"];
        $idPresentacionDoc = $_POST["id_presentaciondoc"];
        $separador = "_";
        $msj = Array();
        if ($idRelaboral > 0 && $idPersona > 0 && $idTipoDocumento > 0 && $idPresentacionDoc > 0) {
            $objRel = new Frelaborales();
            $rel = $objRel->getOne($idRelaboral);
            if (count($rel) > 0) {
                $relaboral = $rel[0];
            }
            $objTipoDocumento = Tiposdocumentos::findFirstById($idTipoDocumento);
            $objPresentacionDoc = Presentacionesdoc::findFirstById($idPresentacionDoc);
            if ($relaboral != null && $objTipoDocumento != null && $objPresentacionDoc != null && $objTipoDocumento->formato_archivo_digital != null && $objTipoDocumento->formato_archivo_digital != '') {
                $directorio = "files/pres/" . trim($relaboral->ci);
                $fechaPres = date("Y-m-d", strtotime($objPresentacionDoc->fecha_pres));
                $findme = ',';
                $pos = strpos($objTipoDocumento->formato_archivo_digital, $findme);
                if ($pos === false) {
                    $extension = strtolower($objTipoDocumento->formato_archivo_digital);
                    $rutaArchivo = $directorio . "/" . trim($relaboral->ci) . $separador . trim($objTipoDocumento->codigo) . $separador . $fechaPres . $separador . $idPresentacionDoc . "." . $extension;
                    if (file_exists($rutaArchivo)) {
                        if (unlink($_SERVER['DOCUMENT_ROOT'] . "/" . $rutaArchivo)) {
                            $objPresentacionDoc->digital = null;
                            $objPresentacionDoc->user_mod_id = $user_mod_id;
                            $objPresentacionDoc->fecha_mod = $hoy;
                            if ($objPresentacionDoc->save()) {
                                $msj = array('result' => 1, 'ruta' => $_SERVER['DOCUMENT_ROOT'] . "/" . $rutaArchivo, 'msj' => '&Eacute;xito: Archivo eliminado exitosamente.');
                            } else {
                                $msj = array('result' => 0, 'ruta' => $_SERVER['DOCUMENT_ROOT'] . "/" . $rutaArchivo, 'msj' => 'Error: No se pudo eliminar el archivo seleccionado.');
                            }

                        }
                    }
                } else {
                    $formatos = explode(",", strtolower($objTipoDocumento->formato_archivo_digital));
                    foreach ($formatos as $extension) {
                        $rutaArchivo = $directorio . "/" . trim($relaboral->ci) . "%" . trim($objTipoDocumento->codigo) . "%" . $fechaPres . "." . $extension;
                        if (file_exists($rutaArchivo)) {
                            if (unlink($_SERVER['DOCUMENT_ROOT'] . "/" . $rutaArchivo)) {
                                $objPresentacionDoc->digital = null;
                                $objPresentacionDoc->user_mod_id = $user_mod_id;
                                $objPresentacionDoc->fecha_mod = $hoy;
                                if ($objPresentacionDoc->save()) {
                                    $msj = array('result' => 1, 'ruta' => $_SERVER['DOCUMENT_ROOT'] . "/" . $rutaArchivo, 'msj' => '&Eacute;xito: Archivo eliminado exitosamente.');
                                } else {
                                    $msj = array('result' => 0, 'ruta' => $_SERVER['DOCUMENT_ROOT'] . "/" . $rutaArchivo, 'msj' => 'Error: No se pudo eliminar el archivo seleccionado.');
                                }

                            }
                        }
                    }
                }
            }
        }
        echo json_encode($msj);
    }

    public function asistenciaAction()
    {
        $this->view->disable();
        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $sql = "[SP_RPT_MARCACIONES_BIOMETRICOS]0,'5963270',0,'21-03-2016','22-03-2016'";
                $dbh = new PDO("sqlsrv:Server=192.168.131.241;Database=BiometricoK30", "sa", "S1stemas");
            } else {
                $dbh = new PDO("sqlsrv:Server=192.168.131.241;Database=BiometricoK30", "sa", "S1stemas");
                $sql = "SELECT d.DEPTNAME AS UBICACION,u.NAME AS NOMBRES,CASE WHEN u.SSN IS NOT NULL THEN u.SSN ELSE u.BADGENUMBER END AS CI,
                        u.TITLE AS CARGO,CONVERT(VARCHAR(10),c.CHECKTIME,103) AS MARCACION_FECHA,CONVERT(VARCHAR(10),c.CHECKTIME,108) AS MARCACION_HORA,
                        c.sn AS CODIGO_MAQUINA,m.MachineAlias AS ESTACION FROM dbo.USERINFO u
                        INNER JOIN dbo.CHECKINOUT c ON u.USERID = c.USERID
                        INNER JOIN dbo.DEPARTMENTS d ON d.DEPTID = u.DEFAULTDEPTID
                        INNER JOIN dbo.Machines m ON m.sn = c.sn
                        WHERE u.USERID=80";
            }
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            //var_dump($row);
            echo $row['UBICACION'] . " " . $row['NOMBRES'] . " " . utf8_encode($row['CI'] . " " . $row['CARGO'] . " " . $row['MARCACION_FECHA'] . " " . $row['MARCACION_FECHA'] . " " . $row['ESTACION']);
            echo '<br/>';
        }
        unset($dbh);
        unset($stmt);
    }
}
