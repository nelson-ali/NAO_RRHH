<?php

/*
*   Oasis - Sistema de Gesti�n para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Telef�rico"
*   Versi�n:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creaci�n:  16-05-2014
*/

class EventosmovilesController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Funci�n para la carga de la p�gina de gesti�n de eventos m�viles.
     */
    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);

        /*$this->assets->addJs('/js/colorpicker-master/js/evol.colorpicker.min.js?v=' . $version);
        $this->assets->addCss('/js/colorpicker-master/css/evol.colorpicker.css?v=' . $version);*/
        $this->assets->addJs('/js/clockpicker/clockpicker.js?v=' . $version);
        $this->assets->addCss('/assets/css/clockpicker.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        $this->assets->addJs('/js/slider/bootstrap-slider.js?v=' . $version);
        $this->assets->addJs('/js/gmaps/gmaps.js?v=' . $version);
        $this->assets->addCss('/js/slider/bootstrap-slider.css?v=' . $version);

        $this->assets->addJs('/js/eventosmoviles/oasis.eventosmoviles.tab.js?v=' . $version);
        $this->assets->addJs('/js/eventosmoviles/oasis.eventosmoviles.index.js?v=' . $version);
        $this->assets->addJs('/js/eventosmoviles/oasis.eventosmoviles.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/eventosmoviles/oasis.eventosmoviles.marks.def.js?v=' . $version);
        $this->assets->addJs('/js/eventosmoviles/oasis.eventosmoviles.approve.js?v=' . $version);
        $this->assets->addJs('/js/eventosmoviles/oasis.eventosmoviles.export.js?v=' . $version);
        $this->assets->addJs('/js/eventosmoviles/oasis.eventosmoviles.down.js?v=' . $version);
    }

    /**
     * Funci�n para la obtenci�n del listado de eventos m�viles relacionados a las marcaciones dispuestas.
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Feventosmoviles();
        $eventosmoviles = Array();
        $data = array();
        $where = "";
        $pagenum = $_GET['pagenum'];
        $pagesize = $_GET['pagesize'];
        $total_rows = 0;
        $start = $pagenum * $pagesize;

        // filter data.
        if (isset($_GET['filterscount'])) {
            $filterscount = $_GET['filterscount'];

            if ($filterscount > 0) {
                $where = " WHERE (";
                $tmpdatafield = "";
                $tmpfilteroperator = "";
                for ($i = 0; $i < $filterscount; $i++) {
                    // get the filter's value.
                    $filtervalue = $_GET["filtervalue" . $i];
                    // get the filter's condition.
                    $filtercondition = $_GET["filtercondition" . $i];
                    // get the filter's column.
                    $filterdatafield = $_GET["filterdatafield" . $i];
                    // get the filter's operator.
                    $filteroperator = $_GET["filteroperator" . $i];

                    if ($tmpdatafield == "") {
                        $tmpdatafield = $filterdatafield;
                    } else if ($tmpdatafield <> $filterdatafield) {
                        $where .= ")AND(";
                    } else if ($tmpdatafield == $filterdatafield) {
                        if ($tmpfilteroperator == 0) {
                            $where .= " AND ";
                        } else
                            $where .= " OR ";
                    }

                    // build the "WHERE" clause depending on the filter's condition, value and datafield.
                    switch ($filtercondition) {
                        case "NOT_EMPTY":
                        case "NOT_NULL":
                            $where .= " " . $filterdatafield . " NOT LIKE '" . "" . "'";
                            break;
                        case "EMPTY":
                        case "NULL":
                            $where .= " " . $filterdatafield . " LIKE '" . "" . "'";
                            break;
                        case "CONTAINS_CASE_SENSITIVE":
                            $where .= " BINARY  " . $filterdatafield . " LIKE '%" . $filtervalue . "%'";
                            break;
                        case "CONTAINS":
                            $where .= " " . strtoupper($filterdatafield) . " LIKE '%" . strtoupper($filtervalue) . "%'";
                            break;
                        case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                            break;
                        case "DOES_NOT_CONTAIN":
                            $where .= " " . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                            break;
                        case "EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " = '" . $filtervalue . "'";
                            break;
                        case "EQUAL":
                            $where .= " " . $filterdatafield . " = '" . $filtervalue . "'";
                            break;
                        case "NOT_EQUAL_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " <> '" . $filtervalue . "'";
                            break;
                        case "NOT_EQUAL":
                            $where .= " " . $filterdatafield . " <> '" . $filtervalue . "'";
                            break;
                        case "GREATER_THAN":
                            $where .= " " . $filterdatafield . " > '" . $filtervalue . "'";
                            break;
                        case "LESS_THAN":
                            $where .= " " . $filterdatafield . " < '" . $filtervalue . "'";
                            break;
                        case "GREATER_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " >= '" . $filtervalue . "'";
                            break;
                        case "LESS_THAN_OR_EQUAL":
                            $where .= " " . $filterdatafield . " <= '" . $filtervalue . "'";
                            break;
                        case "STARTS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '" . $filtervalue . "%'";
                            break;
                        case "STARTS_WITH":
                            $where .= " " . $filterdatafield . " LIKE '" . $filtervalue . "%'";
                            break;
                        case "ENDS_WITH_CASE_SENSITIVE":
                            $where .= " BINARY " . $filterdatafield . " LIKE '%" . $filtervalue . "'";
                            break;
                        case "ENDS_WITH":
                            $where .= " " . $filterdatafield . " LIKE '%" . $filtervalue . "'";
                            break;
                    }

                    if ($i == $filterscount - 1) {
                        $where .= ")";
                    }

                    $tmpfilteroperator = $filteroperator;
                    $tmpdatafield = $filterdatafield;
                }
            }
        }
        $where = str_replace("WHERE", "", $where);
        $where = str_replace("'", "''", $where);
        $resul = $obj->getAll(0, $where, "", $start, $pagesize);
        //comprobamos si hay filas
        if (count($resul) > 0) {
            foreach ($resul as $v) {
                $total_rows = $v->total_rows;
                $eventosmoviles[] = array(
                    'total_rows' => $v->total_rows,
                    'id_eventomovil' => $v->id_eventomovil,
                    'evento_movil' => $v->evento_movil,
                    'grupo' => $v->grupo,
                    'descripcion' => $v->descripcion,
                    'cant_marcaciones' => $v->cant_marcaciones,
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_ini)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_fin)) : "",
                    'plazo' => $v->plazo,
                    'plazo_descripcion' => $v->plazo_descripcion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion
                );
            }
        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $eventosmoviles
        );
        echo json_encode($data);
    }

    /**
     * Funci�n para el registro y edici�n de un evento m�vil en el sistema.
     */
    public function saveAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Modificaci�n de registro de Evento M�vil.
             */
            $idEventoMovil = $_POST['id'];
            $eventoMovil = $_POST["evento_movil"];
            $grupo = $_POST['grupo'];
            $descripcion = $_POST['descripcion'];
            $observacion = $_POST['observacion'];
            if ($idEventoMovil > 0 && $eventoMovil != '') {
                $objEventoMovil = Eventosmoviles::findFirstById($_POST["id"]);
                if (is_object($objEventoMovil)) {
                    $objAux = Eventosmoviles::find("evento_movil LIKE '".$eventoMovil."' AND grupo='".$grupo."' AND id!=".$idEventoMovil." AND baja_logica=1");
                    if(count($objAux)==0){
                        $objEventoMovil->evento_movil = $eventoMovil;
                        $objEventoMovil->grupo = $grupo!=''?$grupo:null;
                        $objEventoMovil->descripcion = $descripcion;
                        $objEventoMovil->observacion = $observacion;
                        $objEventoMovil->user_mod_id = $user_mod_id;
                        $objEventoMovil->fecha_mod = $hoy;
                        try {
                            if ($objEventoMovil->save()) {
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; el registro del Evento M&oacute;vil de modo satisfactorio.','id'=>$objEventoMovil->id);
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro del Evento M&oacute;vil.','id'=>0);
                            }
                        } catch (\Exception $e) {
                            echo get_class($e), ": ", $e->getMessage(), "\n";
                            echo " File=", $e->getFile(), "\n";
                            echo " Line=", $e->getLine(), "\n";
                            echo $e->getTraceAsString();
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del Evento M&oacute;vil.','id'=>0);
                        }
                    }else{
                        $msj = array('result' => 0, 'msj' => 'Error: Ya existe un evento con similares datos, no es admisible la duplicaci&oacute;n de valores para el nombre del evento y grupo.','id'=>0);
                    }
                } else $msj = array('result' => 0, 'msj' => 'Error inesperado: Consulte con el Administrador.','id'=>0);

            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.','id'=>0);
            }
        } else {
            /**
             * Nuevo registro de Evento M�vil.
             */
            $eventoMovil = $_POST["evento_movil"];
            $grupo = $_POST['grupo'];
            $descripcion = $_POST['descripcion'];
            $observacion = $_POST['observacion'];
            if ($eventoMovil != '') {
                $objAux = Eventosmoviles::find("evento_movil='".$eventoMovil."' AND grupo='".$grupo."' AND baja_logica=1");
                if(count($objAux)==0){
                    $objEventoMovil = new Eventosmoviles();
                    $objEventoMovil->evento_movil = $eventoMovil;
                    $objEventoMovil->grupo = $grupo!=''?$grupo:null;
                    $objEventoMovil->descripcion = $descripcion;
                    $objEventoMovil->observacion = $observacion;
                    $objEventoMovil->estado = 2;
                    $objEventoMovil->baja_logica = 1;
                    $objEventoMovil->agrupador = 0;
                    $objEventoMovil->user_reg_id = $user_mod_id;
                    $objEventoMovil->fecha_reg = $hoy;
                    try {
                        if ($objEventoMovil->save()) {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se realiz&oacute; el registro del Evento M&oacute;vil de modo satisfactorio.','id'=>$objEventoMovil->id);
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se pudo realizar el registro del Evento M&oacute;vil.','id'=>0);
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del Evento M&oacute;vil.','id'=>0);
                    }
                }else{
                    $msj = array('result' => 0, 'msj' => 'Error: Ya existe un evento con similares datos, no es admisible la duplicaci&oacute;n de valores para el nombre del evento y grupo.','id'=>0);
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.','id'=>0);
            }
        }
        echo json_encode($msj);
    }
    /**
     * Funci�n para el la baja del registro de un Evento M�vil.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Cr�tico Error
     *  -2: Error de Conexi�n
     *  -3: Usuario no Autorizado
     */
    public function downAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                /**
                 * Baja de registro
                 */
                $objEventoMovil = Eventosmoviles::findFirstById($_POST["id"]);
                $objEventoMovil->estado = 0;
                $objEventoMovil->baja_logica = 0;
                $objEventoMovil->user_mod_id = $user_mod_id;
                $objEventoMovil->fecha_mod = $hoy;
                if ($objEventoMovil->save()) {
                    $db = $this->getDI()->get('db');
                    $sql = "UPDATE eventosmarcaciones SET baja_logica=0,user_mod_id=".$user_mod_id.", fecha_mod=current_date";
                    $ok = $db->execute($sql);
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                } else {
                    foreach ($objEventoMovil->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de Evento M&oacute;vil.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de Evento M&oacute;vil.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Evento M&oacute;vil.');
        }
        echo json_encode($msj);
    }
    public function approveAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Aprobaci�n de registro
             */
            $objEventosMoviles = Eventosmoviles::findFirstById($_POST["id"]);
            if ($objEventosMoviles->id > 0 && $objEventosMoviles->estado == 2) {
                try {
                    $objEventosMoviles->estado = 1;
                    $objEventosMoviles->user_mod_id = $user_mod_id;
                    $objEventosMoviles->fecha_mod = $hoy;
                    $ok = $objEventosMoviles->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se aprob&oacute; correctamente el registro del Evento M&oacute;vil.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se aprob&oacute; el registro del Evento M&oacute;vil.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del Evento M&oacute;vil.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro del Evento M&oacute;vil no cumple con el requisito establecido para su aprobaci&oacute;n, debe estar en estado EN PROCESO.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro de la Evento M&oacute;vil.');
        }
        echo json_encode($msj);
    }
}
