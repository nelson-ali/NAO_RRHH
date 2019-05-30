<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  06-06-2016
*/

class EventosmarcacionesController extends ControllerBase
{
    /**
     * Funciones para la obtención del listado de eventos de marcación.
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Feventosmarcaciones();
        $eventosmarcaciones = Array();
        $data = array();
        $where = "";
        $pagenum = $_GET['pagenum'];
        $pagesize = $_GET['pagesize'];
        $ws = $_GET['ws'];
        $total_rows = 0;
        $idEventoMovil = 0;
        if (isset($_GET['ide'])) {
            $idEventoMovil = $_GET['ide'];
        }
        /**
         * En caso de no enviarse el número de id del evento no se retorna nada.
         */
        if ($idEventoMovil == 0) $pagesize = -1;
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
                            $where .= " " . $filterdatafield . " LIKE '%" . $filtervalue . "%'";
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
        $resul = $obj->getAll($idEventoMovil, 0, $ws, $where, "", $start, $pagesize);
        //comprobamos si hay filas
        if (count($resul) > 0) {
            foreach ($resul as $v) {
                $total_rows = $v->total_rows;
                $eventosmarcaciones[] = array(
                    'total_rows' => $v->total_rows,
                    'id_eventomovil' => $v->id_eventomovil,
                    'evento_movil' => $v->evento_movil,
                    'eventomovil_descripcion' => $v->eventomovil_descripcion,
                    'cant_marcaciones' => $v->cant_marcaciones,
                    'eventomovil_estado' => $v->eventomovil_estado,
                    'eventomovil_estado_descripcion' => $v->eventomovil_estado_descripcion,
                    'id_eventomarcacion' => $v->id_eventomarcacion,
                    'num_marcacion' => $v->num_marcacion,
                    'tipo_marcacion' => $v->tipo_marcacion,
                    'tipo_marcacion_descripcion' => $v->tipo_marcacion_descripcion,
                    'referencia' => $v->referencia,
                    'latitud' => $v->latitud,
                    'longitud' => $v->longitud,
                    'radio' => $v->radio,
                    'planillable' => $v->planillable,
                    'planillable_descripcion' => $v->planillable_descripcion,
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_ini)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_fin)) : "",
                    'plazo' => $v->plazo,
                    'plazo_descripcion' => $v->plazo_descripcion,
                    'eventomarcacion_descripcion' => $v->eventomarcacion_descripcion,
                    'eventomarcacion_observacion' => $v->eventomarcacion_observacion,
                    'eventomarcacion_estado' => $v->eventomarcacion_estado,
                    'eventomarcacion_estado_descripcion' => $v->eventomarcacion_estado_descripcion,
                    'eventomovil_user_reg_id' => $v->eventomovil_user_reg_id,
                    'eventomovil_fecha_reg' => $v->eventomovil_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->eventomovil_fecha_reg)) : "",
                    'eventomarcacion_user_reg_id' => $v->eventomarcacion_user_reg_id,
                    'eventomarcacion_fecha_reg' => $v->eventomarcacion_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->eventomarcacion_fecha_reg)) : "",
                    'eventomovil_user_mod_id' => $v->eventomovil_user_mod_id,
                    'eventomovil_fecha_mod' => $v->eventomovil_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->eventomovil_fecha_mod)) : "",
                    'eventomarcacion_user_mod_id' => $v->eventomarcacion_user_mod_id,
                    'eventomarcacion_fecha_mod' => $v->eventomarcacion_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->eventomarcacion_fecha_mod)) : ""
                );
            }
        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $eventosmarcaciones
        );
        echo json_encode($data);
    }

    /**
     * Función para el registro de eventos de marcación.
     */
    public function saveAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id_eventomovil"]) && $_POST["id_eventomovil"] > 0) {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                /**
                 * Modificación de registro de Evento Móvil.
                 */
                $idEventoMarcacion = $_POST['id'];
                $idEventoMovil = $_POST["id_eventomovil"];
                $numMarcacion = $_POST['num_marcacion'];
                $tipoMarcacion = $_POST['tipo_marcacion'];
                $referencia = $_POST['referencia'];
                $latitud = $_POST['latitud'];
                $longitud = $_POST['longitud'];
                $radio = $_POST['radio'];
                $planillable = $_POST['planillable'];
                $fechaIni = $_POST['fecha_ini'];
                $fechaFin = $_POST['fecha_fin'];
                $descripcion = $_POST['descripcion'];
                $telefonos = null;
                if ($_POST['telefonos'] != '') {
                    $telefonos = $_POST['telefonos'];
                }
                $observacion = $_POST['observacion'];
                if ($fechaIni != '' && $fechaFin != '') {
                    $objEventoMovil = Eventosmarcaciones::findFirstById($_POST["id"]);
                    if (is_object($objEventoMovil)) {
                        $sql = "id!=" . $idEventoMarcacion . " AND eventomovil_id='" . $idEventoMovil . "' AND baja_logica=1 ";
                        $sql .= "AND (('" . $fechaIni . "' BETWEEN fecha_ini AND fecha_fin) OR ('" . $fechaFin . "' BETWEEN fecha_ini AND fecha_fin)) ";
                        $objAux = Eventosmarcaciones::find($sql);
                        if (count($objAux) == 0) {
                            $objEventoMovil->eventomovil_id = $idEventoMovil;
                            $objEventoMovil->num_marcacion = $numMarcacion;
                            $objEventoMovil->tipo_marcacion = $tipoMarcacion;
                            $objEventoMovil->referencia = $referencia;
                            $objEventoMovil->latitud = $latitud;
                            $objEventoMovil->longitud = $longitud;
                            $objEventoMovil->radio = $radio;
                            $objEventoMovil->planillable = $planillable;
                            $objEventoMovil->fecha_ini = $fechaIni;
                            $objEventoMovil->fecha_fin = $fechaFin;
                            $objEventoMovil->descripcion = $descripcion;
                            $objEventoMovil->telefonos = $telefonos;
                            $objEventoMovil->observacion = $observacion;
                            $objEventoMovil->user_mod_id = $user_mod_id;
                            $objEventoMovil->fecha_mod = $hoy;
                            try {
                                if ($objEventoMovil->save()) {
                                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; el registro del Evento M&oacute;vil de modo satisfactorio.', 'id' => $objEventoMovil->id);
                                } else {
                                    $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro del Evento M&oacute;vil.', 'id' => 0);
                                }
                            } catch (\Exception $e) {
                                echo get_class($e), ": ", $e->getMessage(), "\n";
                                echo " File=", $e->getFile(), "\n";
                                echo " Line=", $e->getLine(), "\n";
                                echo $e->getTraceAsString();
                                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del Evento M&oacute;vil.', 'id' => 0);
                            }
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: Ya existe un evento con similares datos, no es admisible la duplicaci&oacute;n de valores para el nombre del evento y grupo.', 'id' => 0);
                        }
                    } else $msj = array('result' => 0, 'msj' => 'Error inesperado: Consulte con el Administrador.', 'id' => 0);

                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.', 'id' => 0);
                }
            } else {
                /**
                 * Nuevo registro de Evento Marcación.
                 */
                $idEventoMovil = $_POST["id_eventomovil"];
                $numMarcacion = $_POST['num_marcacion'];
                $tipoMarcacion = $_POST['tipo_marcacion'];
                $referencia = $_POST['referencia'];
                $latitud = $_POST['latitud'];
                $longitud = $_POST['longitud'];
                $radio = $_POST['radio'];
                $planillable = $_POST['planillable'];
                $fechaIni = $_POST['fecha_ini'];
                $fechaFin = $_POST['fecha_fin'];
                $descripcion = $_POST['descripcion'];
                $telefonos = null;
                if ($_POST['telefonos'] != '') {
                    $telefonos = $_POST['telefonos'];
                }
                $observacion = $_POST['observacion'];
                if ($fechaIni != '' && $fechaFin != '') {
                    $sql = "eventomovil_id='" . $idEventoMovil . "' AND baja_logica=1 ";
                    $sql .= "AND (('" . $fechaIni . "' BETWEEN fecha_ini AND fecha_fin) OR ('" . $fechaFin . "' BETWEEN fecha_ini AND fecha_fin)) ";
                    $objAux = Eventosmarcaciones::find($sql);
                    if (count($objAux) == 0) {
                        $objEventoMovil = new Eventosmarcaciones();
                        $objEventoMovil->eventomovil_id = $idEventoMovil;
                        $objEventoMovil->num_marcacion = $numMarcacion;
                        $objEventoMovil->tipo_marcacion = $tipoMarcacion;
                        $objEventoMovil->referencia = $referencia;
                        $objEventoMovil->latitud = $latitud;
                        $objEventoMovil->longitud = $longitud;
                        $objEventoMovil->radio = $radio;
                        $objEventoMovil->planillable = $planillable;
                        $objEventoMovil->fecha_ini = $fechaIni;
                        $objEventoMovil->fecha_fin = $fechaFin;
                        $objEventoMovil->descripcion = $descripcion;
                        $objEventoMovil->telefonos = $telefonos;
                        $objEventoMovil->observacion = $observacion;
                        $objEventoMovil->estado = 1;
                        $objEventoMovil->baja_logica = 1;
                        $objEventoMovil->agrupador = 0;
                        $objEventoMovil->user_reg_id = $user_mod_id;
                        $objEventoMovil->fecha_reg = $hoy;
                        try {
                            if ($objEventoMovil->save()) {
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se realiz&oacute; el registro del Evento M&oacute;vil de modo satisfactorio.', 'id' => $objEventoMovil->id);
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se pudo realizar el registro del Evento M&oacute;vil.', 'id' => 0);
                            }
                        } catch (\Exception $e) {
                            echo get_class($e), ": ", $e->getMessage(), "\n";
                            echo " File=", $e->getFile(), "\n";
                            echo " Line=", $e->getLine(), "\n";
                            echo $e->getTraceAsString();
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del Evento M&oacute;vil.', 'id' => 0);
                        }
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: Ya existe un evento de marcaci&oacute;n con similares datos, no es admisible la duplicaci&oacute;n de valores para la fecha y hora.', 'id' => 0);
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.', 'id' => 0);
                }
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se han enviado los datos correspondientes al Evento M&oacute;vil.', 'id' => 0);
        }
        echo json_encode($msj);
    }

    /**
     * Función para el la baja del registro de un Evento de Marcación.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Crítico Error
     *  -2: Error de Conexión
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
                $objEventoMarcacion = Eventosmarcaciones::findFirstById($_POST["id"]);
                $objEventoMarcacion->estado = 0;
                $objEventoMarcacion->baja_logica = 0;
                $objEventoMarcacion->user_mod_id = $user_mod_id;
                $objEventoMarcacion->fecha_mod = $hoy;
                if ($objEventoMarcacion->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                } else {
                    foreach ($objEventoMarcacion->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de Evento de Marcaci&oacute;n.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de Evento de Marcaci&oacute;n.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Evento de Marcaci&oacute;n.');
        }
        echo json_encode($msj);
    }
}