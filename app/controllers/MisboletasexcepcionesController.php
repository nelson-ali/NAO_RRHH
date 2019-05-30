<?php

/*
*   Oasis - Sistema de GestiÃ³n para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi TelefÃ©rico"
*   VersiÃ³n:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha CreaciÃ³n:  09-09-2015
*/

class MisboletasexcepcionesController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * FunciÃ³n para la carga de la pÃ¡gina de gestiÃ³n de relaciones laborales.
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
        //$this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        //  $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);

        $this->assets->addCss('/assets/css/clockpicker.css?v=' . $version);
        $this->assets->addJs('/js/clockpicker/clockpicker.js?v=' . $version);

        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.tab.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.index.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.list.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.approve.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.turns.excepts.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.down.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.move.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.export.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.export.form.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.view.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.view.splitter.js?v=' . $version);
        $this->assets->addJs('/js/misboletasexcepciones/oasis.misboletasexcepciones.send.js?v=' . $version);
        $this->assets->addJs('/js/ckeditor/ckeditor.js?v=' . $version);
        $auth = $this->session->get('auth');
        $objUsr = new Usuarios();
        $relaboral = $objUsr->getOneRelaboralActivo($auth['id']);
        if (is_object($relaboral)) {
            $this->view->setVar('idRelaboral', $relaboral[0]->id_relaboral);
            $this->view->setVar('idPersona', $relaboral[0]->id_persona);
            $this->view->setVar('ci', $relaboral[0]->ci);
            $this->view->setVar('nombres', $relaboral[0]->nombres);
        }
    }

    /**
     * FunciÃ³n para la obtenciÃ³n del listado de registros de control de excepciones.
     * La diferencia con el mÃ©todo del controlador ControlexcepcionesController es que en este listado no debiera mostrarse
     * a las excepciones que corresponde boleta.
     * Autor: JLM
     */
    public function listporrelaboralAction()
    {
        $this->view->disable();
        $obj = new Fcontrolexcepciones();
        $controlexcepciones = Array();
        $idRelaboral = 0;
        $data = array();
        if (isset($_GET["id"])) {
            $idRelaboral = $_GET["id"];
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
                                $where .= " UPPER(" . $filterdatafield . ") NOT LIKE '" . "" . "'";
                                break;
                            case "EMPTY":
                            case "NULL":
                                $where .= " UPPER(" . $filterdatafield . ") LIKE '" . "" . "'";
                                break;
                            case "CONTAINS_CASE_SENSITIVE":
                                $where .= " BINARY  " . $filterdatafield . " LIKE '%" . $filtervalue . "%'";
                                break;
                            case "CONTAINS":
                                $where .= " UPPER(" . $filterdatafield . ") LIKE '%" . $filtervalue . "%'";
                                break;
                            case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
                                $where .= " BINARY " . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                                break;
                            case "DOES_NOT_CONTAIN":
                                $where .= " UPPER(" . $filterdatafield . " NOT LIKE '%" . $filtervalue . "%'";
                                break;
                            case "EQUAL_CASE_SENSITIVE":
                                $where .= " BINARY " . $filterdatafield . " = '" . $filtervalue . "'";
                                break;
                            case "EQUAL":
                                $where .= " UPPER(" . $filterdatafield . ") = '" . $filtervalue . "'";
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
                                $where .= " BINARY UPPER(" . $filterdatafield . ") LIKE '" . $filtervalue . "%'";
                                break;
                            case "STARTS_WITH":
                                $where .= " UPPER(" . $filterdatafield . ") LIKE '" . $filtervalue . "%'";
                                break;
                            case "ENDS_WITH_CASE_SENSITIVE":
                                $where .= " BINARY UPPER(" . $filterdatafield . ") LIKE '%" . $filtervalue . "'";
                                break;
                            case "ENDS_WITH":
                                $where .= " UPPER(" . $filterdatafield . ") LIKE '%" . $filtervalue . "'";
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
            if ($idRelaboral > 0) {
                $where = str_replace("WHERE ", "", $where);
                $where = str_replace("'", "''", $where);
                $resul = $obj->getAllByOneRelaboral($idRelaboral, $where, "", $start, $pagesize);
                //comprobamos si hay filas
                if (count($resul) > 0) {
                    foreach ($resul as $v) {
                        $total_rows = $v->total_rows;
                        $controlexcepciones[] = array(
                            'nro_row' => 0,
                            'id' => $v->id_controlexcepcion,
                            'id_relaboral' => $v->id_relaboral,
                            'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                            'hora_ini' => $v->hora_ini,
                            'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                            'hora_fin' => $v->hora_fin,
                            'justificacion' => $v->justificacion,
                            'destino' => $v->destino,
                            'turno' => $v->turno,
                            'turno_descripcion' => $v->compensatoria == 1 ? $v->turno != null ? $v->turno . "Â°" : null : null,
                            'entrada_salida' => $v->entrada_salida,
                            'entrada_salida_descripcion' => $v->compensatoria == 1 ? $v->entrada_salida == 0 ? "ENTRADA" : "SALIDA" : null,
                            'controlexcepcion_observacion' => $v->controlexcepcion_observacion,
                            'controlexcepcion_estado' => $v->controlexcepcion_estado,
                            'controlexcepcion_estado_descripcion' => $v->controlexcepcion_estado_descripcion,
                            'controlexcepcion_user_reg_id' => $v->controlexcepcion_user_reg_id,
                            'controlexcepcion_user_registrador' => $v->controlexcepcion_user_registrador,
                            'controlexcepcion_fecha_reg' => $v->controlexcepcion_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_reg)) : "",
                            'controlexcepcion_user_ver_id' => $v->controlexcepcion_user_ver_id,
                            'controlexcepcion_user_verificador' => $v->controlexcepcion_user_verificador,
                            'controlexcepcion_fecha_ver' => $v->controlexcepcion_fecha_ver != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_ver)) : "",
                            'controlexcepcion_user_apr_id' => $v->controlexcepcion_user_apr_id,
                            'controlexcepcion_user_aprobador' => $v->controlexcepcion_user_aprobador,
                            'controlexcepcion_fecha_apr' => $v->controlexcepcion_fecha_apr != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_apr)) : "",
                            'controlexcepcion_user_mod_id' => $v->controlexcepcion_user_mod_id,
                            'controlexcepcion_user_modificador' => $v->controlexcepcion_user_modificador,
                            'controlexcepcion_fecha_mod' => $v->controlexcepcion_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_mod)) : "",
                            /*'excepcion_id'=>$v->id_excepcion,*/
                            'excepcion_id' => $v->excepcion_id,
                            'excepcion' => $v->excepcion,
                            'tipoexcepcion_id' => $v->tipoexcepcion_id,
                            'tipo_excepcion' => $v->tipo_excepcion,
                            'codigo' => $v->codigo,
                            'color' => $v->color,
                            'compensatoria' => $v->compensatoria,
                            'compensatoria_descripcion' => $v->compensatoria_descripcion,
                            'genero_id' => $v->genero_id,
                            'genero' => $v->genero,
                            'cantidad' => $v->cantidad,
                            'unidad' => $v->unidad,
                            'fraccionamiento' => $v->fraccionamiento,
                            'frecuencia_descripcion' => $v->frecuencia_descripcion,
                            'redondeo' => $v->redondeo,
                            'redondeo_descripcion' => $v->redondeo_descripcion,
                            'horario' => $v->horario,
                            'horario_descripcion' => $v->horario_descripcion,
                            'refrigerio' => $v->refrigerio,
                            'refrigerio_descripcion' => $v->refrigerio_descripcion,
                            'lugar' => $v->refrigerio,
                            'lugar_descripcion' => $v->refrigerio_descripcion,
                            'observacion' => $v->observacion,
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'baja_logica' => $v->baja_logica,
                            'agrupador' => $v->agrupador,
                            'boleta' => $v->agrupador,
                            'boleta_descripcion' => $v->agrupador == 1 ? "SI" : "NO",
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod
                        );
                    }
                }
            }
            $data[] = array(
                'TotalRows' => $total_rows,
                'Rows' => $controlexcepciones
            );
        }
        echo json_encode($data);
    }

    /**
     * FunciÃ³n para la obtenciÃ³n del listado de registros de control de excepciones.
     * La diferencia con el mÃ©todo del controlador ControlexcepcionesController es que en este listado no debiera mostrarse
     * a las excepciones que corresponde boleta.
     * Autor: JLM
     */
    public function listboletasporrelaboralaAction()
    {
        $this->view->disable();
        $obj = new Fcontrolexcepciones();
        $controlexcepciones = Array();
        $idRelaboral = 0;
        $data = array();
        if (isset($_GET["id"])) {
            $idRelaboral = $_GET["id"];
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
            if ($idRelaboral > 0) {
                $resul = $obj->getAllByOneRelaboral($idRelaboral, $where, "", $start, $pagesize);
                //comprobamos si hay filas
                if (count($resul) > 0) {
                    $cex = Controlexcepciones::find(array("relaboral_id = " . $idRelaboral . " AND baja_logica=1"));
                    $total_rows = $cex->count();
                    foreach ($resul as $v) {
                        $controlexcepciones[] = array(
                            'nro_row' => 0,
                            'id' => $v->id_controlexcepcion,
                            'id_relaboral' => $v->id_relaboral,
                            'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                            'hora_ini' => $v->hora_ini,
                            'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                            'hora_fin' => $v->hora_fin,
                            'justificacion' => $v->justificacion,
                            'destino' => $v->destino,
                            'turno' => $v->turno,
                            'turno_descripcion' => $v->compensatoria == 1 ? $v->turno != null ? $v->turno . "Â°" : null : null,
                            'entrada_salida' => $v->entrada_salida,
                            'entrada_salida_descripcion' => $v->compensatoria == 1 ? $v->entrada_salida == 0 ? "ENTRADA" : "SALIDA" : null,
                            'controlexcepcion_observacion' => $v->controlexcepcion_observacion,
                            'controlexcepcion_estado' => $v->controlexcepcion_estado,
                            'controlexcepcion_estado_descripcion' => $v->controlexcepcion_estado_descripcion,
                            'controlexcepcion_user_reg_id' => $v->controlexcepcion_user_reg_id,
                            'controlexcepcion_user_registrador' => $v->controlexcepcion_user_registrador,
                            'controlexcepcion_fecha_reg' => $v->controlexcepcion_fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_reg)) : "",
                            'controlexcepcion_user_ver_id' => $v->controlexcepcion_user_ver_id,
                            'controlexcepcion_user_verificador' => $v->controlexcepcion_user_verificador,
                            'controlexcepcion_fecha_ver' => $v->controlexcepcion_fecha_ver != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_ver)) : "",
                            'controlexcepcion_user_apr_id' => $v->controlexcepcion_user_apr_id,
                            'controlexcepcion_user_aprobador' => $v->controlexcepcion_user_aprobador,
                            'controlexcepcion_fecha_apr' => $v->controlexcepcion_fecha_apr != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_apr)) : "",
                            'controlexcepcion_user_mod_id' => $v->controlexcepcion_user_mod_id,
                            'controlexcepcion_user_modificador' => $v->controlexcepcion_user_modificador,
                            'controlexcepcion_fecha_mod' => $v->controlexcepcion_fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->controlexcepcion_fecha_mod)) : "",
                            /*'excepcion_id'=>$v->id_excepcion,*/
                            'excepcion_id' => $v->excepcion_id,
                            'excepcion' => $v->excepcion,
                            'tipoexcepcion_id' => $v->tipoexcepcion_id,
                            'tipo_excepcion' => $v->tipo_excepcion,
                            'codigo' => $v->codigo,
                            'color' => $v->color,
                            'compensatoria' => $v->compensatoria,
                            'compensatoria_descripcion' => $v->compensatoria_descripcion,
                            'genero_id' => $v->genero_id,
                            'genero' => $v->genero,
                            'cantidad' => $v->cantidad,
                            'unidad' => $v->unidad,
                            'fraccionamiento' => $v->fraccionamiento,
                            'frecuencia_descripcion' => $v->frecuencia_descripcion,
                            'redondeo' => $v->redondeo,
                            'redondeo_descripcion' => $v->redondeo_descripcion,
                            'horario' => $v->horario,
                            'horario_descripcion' => $v->horario_descripcion,
                            'refrigerio' => $v->refrigerio,
                            'refrigerio_descripcion' => $v->refrigerio_descripcion,
                            'observacion' => $v->observacion,
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'baja_logica' => $v->baja_logica,
                            'agrupador' => $v->agrupador,
                            'boleta' => $v->agrupador,
                            'boleta_descripcion' => $v->agrupador == 1 ? "SI" : "NO",
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod
                        );
                    }
                }
            }
            $data[] = array(
                'TotalRows' => $total_rows,
                'Rows' => $controlexcepciones
            );
        }
        echo json_encode($data);
    }

    /**
     * FunciÃ³n para la obtenciÃ³n del listado de controles de excepciÃ³n para un registro de relaciÃ³n laboral considerando un rango de fechas.
     * El resultado repite registro de acuerdo a cada fecha dentro del rango de fechas.
     */
    public function listporrelaboralyrangoAction()
    {
        $this->view->disable();
        $controlexcepciones = Array();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0 && isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $obj = new Fcontrolexcepciones();
            $idRelaboral = $_POST["id_relaboral"];
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $resul = $obj->getAllByRelaboralAndRange($idRelaboral, $fechaIni, $fechaFin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $controlexcepciones[] = array(
                        'nro_row' => 0,
                        'id' => $v->id_controlexcepcion,
                        'id_relaboral' => $v->id_relaboral,
                        'fecha_ini' => $v->fecha_ini,
                        'hora_ini' => $v->hora_ini,
                        'fecha_fin' => $v->fecha_fin,
                        'hora_fin' => $v->hora_fin,
                        'justificacion' => $v->justificacion,
                        'controlexcepcion_observacion' => $v->controlexcepcion_observacion,
                        'controlexcepcion_estado' => $v->controlexcepcion_estado,
                        'controlexcepcion_estado_descripcion' => $v->controlexcepcion_estado_descripcion,
                        'controlexcepcion_user_reg_id' => $v->controlexcepcion_user_reg_id,
                        'controlexcepcion_user_registrador' => $v->controlexcepcion_user_registrador,
                        'controlexcepcion_fecha_reg' => $v->controlexcepcion_fecha_reg != "" ? date("Y-m-d", strtotime($v->controlexcepcion_fecha_reg)) : "",
                        'controlexcepcion_user_apr_id' => $v->controlexcepcion_user_apr_id,
                        'controlexcepcion_user_aprobador' => $v->controlexcepcion_user_aprobador,
                        'controlexcepcion_fecha_apr' => $v->controlexcepcion_fecha_apr != "" ? date("Y-m-d", strtotime($v->controlexcepcion_fecha_apr)) : "",
                        'controlexcepcion_user_mod_id' => $v->controlexcepcion_user_mod_id,
                        'controlexcepcion_user_modificador' => $v->controlexcepcion_user_modificador,
                        'controlexcepcion_fecha_mod' => $v->controlexcepcion_fecha_mod != "" ? date("Y-m-d", strtotime($v->controlexcepcion_fecha_mod)) : "",
                        'excepcion_id' => $v->excepcion_id,
                        'excepcion' => $v->excepcion,
                        'tipoexcepcion_id' => $v->tipoexcepcion_id,
                        'tipo_excepcion' => $v->tipo_excepcion,
                        'codigo' => $v->codigo,
                        'color' => $v->color,
                        'compensatoria' => $v->compensatoria,
                        'compensatoria_descripcion' => $v->compensatoria_descripcion,
                        'genero_id' => $v->genero_id,
                        'genero' => $v->genero,
                        'cantidad' => $v->cantidad,
                        'unidad' => $v->unidad,
                        'fraccionamiento' => $v->fraccionamiento,
                        'frecuencia_descripcion' => $v->frecuencia_descripcion,
                        'redondeo' => $v->redondeo,
                        'redondeo_descripcion' => $v->redondeo_descripcion,
                        'horario' => $v->horario,
                        'horario_descripcion' => $v->horario_descripcion,
                        'refrigerio' => $v->refrigerio,
                        'refrigerio_descripcion' => $v->refrigerio_descripcion,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg != "" ? date("Y-m-d", strtotime($v->fecha_reg)) : "",
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                        'fecha' => $v->fecha != "" ? date("Y-m-d", strtotime($v->fecha)) : "",
                        'dia' => $v->dia,
                        'dia_nombre' => $v->dia_nombre,
                        'dia_nombre_abr_ing' => $v->dia_nombre_abr_ing
                    );
                }
            }
        }
        echo json_encode($controlexcepciones);
    }

    /**
     * FunciÃ³n para el almacenamiento y actualizaciÃ³n de un registro de Control de ExcepciÃ³n.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: CrÃ­tico Error
     *  -2: Error de ConexiÃ³n
     *  -3: Usuario no Autorizado
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
             * ModificaciÃ³n de registro de Feriado
             */
            $idRelaboral = $_POST['relaboral_id'];
            $idExcepcion = $_POST['excepcion_id'];
            $fechaIni = $_POST['fecha_ini'];
            $horaIni = $_POST['hora_ini'];
            $fechaFin = $_POST['fecha_fin'];
            $horaFin = $_POST['hora_fin'];
            $justificacion = $_POST['justificacion'];
            $turno = $_POST['turno'];
            $entradaSalida = $_POST['entrada_salida'];
            $horario = $_POST['horario'];
            $observacion = $_POST['observacion'];
            if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $fechaFin != '' && $justificacion != '') {
                $objControlExcepciones = Controlexcepciones::findFirst(array("id=" . $_POST["id"]));
                if (count($objControlExcepciones) > 0) {
                    if ($horario == 1)
                        $cantMismosDatos = Controlexcepciones::count(array("id!=" . $_POST["id"] . " AND relaboral_id=" . $idRelaboral . " AND excepcion_id = " . $idExcepcion . " AND fecha_ini='" . $fechaIni . "' AND hora_ini='" . $horaIni . "' AND fecha_fin = '" . $fechaFin . "' AND hora_fin='" . $horaFin . "' AND baja_logica=1 AND estado>=0"));
                    else
                        $cantMismosDatos = Controlexcepciones::count(array("id!=" . $_POST["id"] . " AND relaboral_id=" . $idRelaboral . " AND excepcion_id = " . $idExcepcion . " AND fecha_ini='" . $fechaIni . "' AND fecha_fin = '" . $fechaFin . "' AND baja_logica=1 AND estado>=0"));
                    if ($cantMismosDatos == 0) {
                        if ($horario == 0) {
                            $datetimeIni = new DateTime();
                            $datetimeIni->setTime(0, 0, 0);
                            $datetimeIni->format('H:i:s');
                            $horaIni = $datetimeIni->format('H:i:s');
                            $datetimeFin = new DateTime();
                            $datetimeFin->setTime(23, 59, 59);
                            $datetimeFin->format('H:i:s');
                            $horaFin = $datetimeFin->format('H:i:s');
                        }
                        $objControlExcepciones->relaboral_id = $idRelaboral;
                        $objControlExcepciones->excepcion_id = $idExcepcion;
                        $objControlExcepciones->fecha_ini = $fechaIni;
                        $objControlExcepciones->fecha_fin = $fechaFin;
                        $objControlExcepciones->hora_ini = $horaIni;
                        $objControlExcepciones->hora_fin = $horaFin;
                        $objControlExcepciones->justificacion = $justificacion;
                        if ($turno > 0) {
                            $objControlExcepciones->turno = $turno;
                        } else $objControlExcepciones->turno = null;
                        if ($entradaSalida >= 0) {
                            $objControlExcepciones->entrada_salida = $entradaSalida;
                        } else $objControlExcepciones->entrada_salida = null;
                        $objControlExcepciones->observacion = $observacion;
                        $objControlExcepciones->user_mod_id = $user_mod_id;
                        $objControlExcepciones->fecha_mod = $hoy;
                        try {
                            $ok = $objControlExcepciones->save();
                            if ($ok) {
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente el registro del control de excepci&oacute;n.');
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro del control de excepci&oacute;n.');
                            }
                        } catch (\Exception $e) {
                            echo get_class($e), ": ", $e->getMessage(), "\n";
                            echo " File=", $e->getFile(), "\n";
                            echo " Line=", $e->getLine(), "\n";
                            echo $e->getTraceAsString();
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del control de excepci&oacute;n.');
                        }
                    } else $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados son similares a otro registro existente, debe modificar los valores necesariamente.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        } else {
            /**
             * Registro de Control de ExcepciÃ³n
             */
            $idRelaboral = $_POST['relaboral_id'];
            $idExcepcion = $_POST['excepcion_id'];
            $fechaIni = $_POST['fecha_ini'];
            $horaIni = $_POST['hora_ini'];
            $fechaFin = $_POST['fecha_fin'];
            $horaFin = $_POST['hora_fin'];
            $justificacion = $_POST['justificacion'];
            $turno = $_POST['turno'];
            $entradaSalida = $_POST['entrada_salida'];
            $horario = $_POST['horario'];
            $observacion = $_POST['observacion'];
            if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $fechaFin != '' && $justificacion != '') {
                if ($horario == 1)
                    $cantMismosDatos = Controlexcepciones::count(array("relaboral_id=" . $idRelaboral . " AND excepcion_id = " . $idExcepcion . " AND fecha_ini='" . $fechaIni . "' AND hora_ini='" . $horaIni . "' AND fecha_fin = '" . $fechaFin . "' AND hora_fin='" . $horaFin . "' AND baja_logica=1 AND estado>=0"));
                else
                    $cantMismosDatos = Controlexcepciones::count(array("relaboral_id=" . $idRelaboral . " AND excepcion_id = " . $idExcepcion . " AND fecha_ini='" . $fechaIni . "' AND fecha_fin = '" . $fechaFin . "' AND baja_logica=1 AND estado>=0"));
                if ($cantMismosDatos == 0) {
                    if ($horario == 0) {
                        $datetimeIni = new DateTime();
                        $datetimeIni->setTime(0, 0, 0);
                        $datetimeIni->format('H:i:s');
                        $horaIni = $datetimeIni->format('H:i:s');
                        $datetimeFin = new DateTime();
                        $datetimeFin->setTime(23, 59, 59);
                        $datetimeFin->format('H:i:s');
                        $horaFin = $datetimeFin->format('H:i:s');
                    }
                    $objControlExcepciones = new Controlexcepciones();
                    $objControlExcepciones->relaboral_id = $idRelaboral;
                    $objControlExcepciones->excepcion_id = $idExcepcion;
                    $objControlExcepciones->fecha_ini = $fechaIni;
                    $objControlExcepciones->fecha_fin = $fechaFin;
                    $objControlExcepciones->hora_ini = $horaIni;
                    $objControlExcepciones->hora_fin = $horaFin;
                    $objControlExcepciones->justificacion = $justificacion;
                    if ($turno > 0) {
                        $objControlExcepciones->turno = $turno;
                    } else $objControlExcepciones->turno = null;
                    if ($entradaSalida >= 0) {
                        $objControlExcepciones->entrada_salida = $entradaSalida;
                    } else $objControlExcepciones->entrada_salida = null;
                    $objControlExcepciones->observacion = $observacion;
                    $objControlExcepciones->estado = 1;
                    $objControlExcepciones->baja_logica = 1;
                    $objControlExcepciones->agrupador = 0;
                    $objControlExcepciones->user_reg_id = $user_reg_id;
                    $objControlExcepciones->fecha_reg = $hoy;
                    try {
                        $ok = $objControlExcepciones->save();
                        if ($ok) {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute;.');
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del control de excepci&oacute;n.');
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: Existe registro de control de excepci&oacute;n con datos similares.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }

    /*
     * FunciÃ³n para la aprobaciÃ³n del registro de un control de excepciÃ³n.
     */
    public function approveAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * AprobaciÃ³n de registro
             */
            $objControlExcepciones = Controlexcepciones::findFirstById($_POST["id"]);
            if ($objControlExcepciones->id > 0 && $objControlExcepciones->estado == 2) {
                try {
                    $objControlExcepciones->estado = 3;
                    $objControlExcepciones->user_mod_id = $user_mod_id;
                    $objControlExcepciones->user_apr_id = $user_mod_id;
                    $objControlExcepciones->fecha_mod = $hoy;
                    $objControlExcepciones->fecha_apr = $hoy;
                    $ok = $objControlExcepciones->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se aprob&oacute; correctamente el registro del control de  excepci&oacute;n.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se aprob&oacute; el registro de control de excepci&oacute;n.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del control de excepci&oacute;n.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro de control de excepci&oacute;n no cumple con el requisito establecido para su aprobaci&oacute;n, debe estar en estado EN PROCESO.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro del control de excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * FunciÃ³n para el la baja del registro de un control de excepciÃ³n.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: CrÃ­tico Error
     *  -2: Error de ConexiÃ³n
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
                $objControlExcepciones = Controlexcepciones::findFirstById($_POST["id"]);
                $objControlExcepciones->estado = 0;
                $objControlExcepciones->baja_logica = 1;
                $objControlExcepciones->user_mod_id = $user_mod_id;
                $objControlExcepciones->fecha_mod = $hoy;
                if ($objControlExcepciones->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                } else {
                    foreach ($objControlExcepciones->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de la excepci&oacute;n.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de la excepci&oacute;n.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del control de excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * FunciÃ³n para verificar si corresponde aplicar intermediaciÃ³n en el proceso de autorizaciÃ³n de boleta de excepciÃ³n.
     */
    public function verificaintemerdiacionAction()
    {
        $this->view->disable();
        $departamentoAdministrativa = $_POST['departamento_administrativo'];
        $cargo = $_POST['cargo'];
        if ($departamentoAdministrativa != '') {
            /**
             * Esto considerando que pueda darse el caso de un cambio de denominaciÃ³n.
             */
            $palabraBuscadaA = 'OPERACIONES';
            $palabraBuscadaB = 'MANTENIMIENTO';
            $palabraBuscadaC = 'JEFE';
            $palabraBuscadaD = 'JEFA';
            $palabraBuscadaE = 'ENCARGADO';
            $palabraBuscadaF = 'ENCARGADA';
            $posA = strpos($departamentoAdministrativa, $palabraBuscadaA);
            $posB = strpos($departamentoAdministrativa, $palabraBuscadaB);
            $posC = strpos(strtoupper($cargo), $palabraBuscadaC);
            $posD = strpos(strtoupper($cargo), $palabraBuscadaD);
            $posE = strpos(strtoupper($cargo), $palabraBuscadaE);
            $posF = strpos(strtoupper($cargo), $palabraBuscadaF);
            if ($posA !== false || $posB !== false) {
                if ($posC === false && $posD === false && $posE === false && $posF === false) {
                    $msj = array('result' => 1, 'msj' => 'Con intermediario.');
                } else $msj = array('result' => 0, 'msj' => 'Con intermediario.');
            } else $msj = array('result' => 0, 'msj' => 'Sin intermediario.');

        } else {
            /**
             * Si es Jef@ y/o Secretari@
             */
            /*$palabraBuscadaA   = 'SECRETARIA';
            $palabraBuscadaB   = 'JEFE';
            $palabraBuscadaC   = 'JEFA';
            $posA = strpos($palabraBuscadaA,$cargo);
            $posB = strpos($cargo, $palabraBuscadaB);
            $posC = strpos($cargo, $palabraBuscadaC);
            if($posA===false&&$posB===false&&$posC===false){
                $msj = array('result' => -1, 'msj' => 'Envio de dato nulo.');
            }else $msj = array('result' => 0, 'msj' => 'Excepciones de cargos.');*/
            $msj = array('result' => 0, 'msj' => 'Excepciones de cargos.');
        }
        echo json_encode($msj);
    }

    /**
     * FunciÃ³n para el envÃ­o de mensajes para solicitud de excepciÃ³n.
     */
    public function enviomensajeAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $fechaYHoraEnvio = date("d-m-Y H:i:s");
        $operacion = 0;
        $search = array("Ã¡", "Ã©", "Ã­", "Ã³", "Ãº", "Ã�", "Ã‰", "Ã�", "Ã“", "Ãš");
        $replace = array("&acute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;");
        if (isset($_POST["operacion"])) {
            $operacion = $_POST["operacion"];
        }
        $idRelaboralSolicitante = $_POST["id_rel_solicitante"];
        $idRelaboralDestinatarioPrincipal = $_POST["id_rel_dest_principal"];
        $idRelaboralDestinatarioSecundario = $_POST["id_rel_dest_secundario"];
        $copiaDestinatarioSecundario = $_POST["copia_destinatario_secundario"];
        $idControlExcepcion = $_POST["id_controlexcepcion"];
        $mensajeAdicional = $_POST["mensaje_adicional"];

        $param = Parametros::findFirst(array("parametro LIKE 'RUTA_APLICACION' AND estado=1 AND baja_logica=1"));
        $ruta = 'http://rrhh.local/controlexcepcionesvistobueno/vistobueno/';
        if (is_object($param)) {
            $ruta = 'http://' . $param->nivel . '/controlexcepcionesvistobueno/vistobueno/';
        }

        /**
         * La operaciÃ³n de solicitud puede ser producto de una previa VERIFICACION
         */
        if ($operacion == 0 || $operacion == 2) {
            $operacionSolicitada = "APROBACION";
            $estadoPreOperacionSolicitada = 4;
            $estadoOperacionSolicitada = 6;
            $estadoOperacionSolicitadaRechazada = -2;
            $estadoOperacionSolicitadaError = -4;
            $aceptarSolicitud = 'Aprobar';
        } else {
            $operacionSolicitada = "VERIFICACION";
            $estadoPreOperacionSolicitada = 3;
            $estadoOperacionSolicitada = 5;
            $estadoOperacionSolicitadaRechazada = -1;
            $estadoOperacionSolicitadaError = -3;
            $aceptarSolicitud = 'Verificar';
        }

        $nombreDestinatarioSecundario = '';
        $cargoDestinatarioSecundario = '';
        $departamentoDestinatarioSecundario = '';
        $gerenciaDestinatarioSecundario = '';


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
        /**
         * SÃ³lo se admite el envÃ­o del mensaje en caso de que el control de excepciÃ³n este en ELABORACIÃ“N O ELABORADO
         * (Este Ãºltimo caso para cuando se deba reenviar el mensaje)
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
                 * Se admite el envÃ­o de solicitudes para registros en estado EN ELABORACIÃ“N, ELABORADO, VERIFICACIÃ“N SOLICITADA Y APROBACIÃ“N SOLICITADA
                 * Las dos Ãºltimas opciones debido a que se plantea la necesidad de enviar nuevamente en caso de que el mensaje se haya eliminado en la
                 * Bandeja de Entrada del Destinatario.
                 */
                if ($controlexcepcion->controlexcepcion_estado == 1
                    || $controlexcepcion->controlexcepcion_estado == 2
                    || $controlexcepcion->controlexcepcion_estado == 3
                    || $controlexcepcion->controlexcepcion_estado == 4
                    || $controlexcepcion->controlexcepcion_estado == 5
                    /**
                     * En caso de que se estÃ© intentando enviar nuevamente un correo con error tÃ©cnico al momento del envÃ­o
                     */
                    || $controlexcepcion->controlexcepcion_estado == -3
                    || $controlexcepcion->controlexcepcion_estado == -4
                ) {

                    #region Registro del envÃ­o
                    /**
                     * Inicialmente se registra el estado previo
                     */
                    $ce = Controlexcepciones::findFirstById($idControlExcepcion);
                    $ce->estado = $estadoPreOperacionSolicitada;
                    $ce->user_mod_id = $idUsuario;
                    $ce->fecha_mod = $hoy;
                    $ce->save();
                    #endregion Registro del envÃ­o

                    $mensajeCabecera = "Estimad@ Usuari@:<br>";
                    $mensajeCabecera .= "Se ha solicitado la <b>" . str_replace($search, $replace, $operacionSolicitada) . "</b> de aplicaci&oacute;n de Excepci&oacute;n con el siguiente detalle: ";
                    $mensajePie = "Atte.,<br>";
                    $mensajePie .= "<b>Unidad de Administraci&oacute;n y Recursos Humanos<br>";
                    $mensajePie .= "DIRECCION GENERAL DE ASUNTOS ADMINISTRATIVOS<br>";
                    $mensajePie .= "- MINISTERIO DE ENERGIAS -</b><br>";
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
                    $idRelaboralDestinatarioSecundarioCodificado = rtrim(strtr(base64_encode($idRelaboralDestinatarioSecundario), '+/', '-_'), '=');
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
                    $cuerpo .= '<p style="font-family: Helvetica LT Condensed; color: #3085ff; font-weight: bold; font-size: 15px; text-align: center;">SOLICITUD DE ' . str_replace($search, $replace, $operacionSolicitada) . ' DE EXCEPCI&Oacute;N</p></td>';
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
                    $cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Tipo de Excepci&oacute;n:</span>&nbsp; ' . $excepcion . '</td>';
                    $cuerpo .= '</tr>';
                    /*$cuerpo .= '<tr>';
                    $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Justificaci&oacute;n:</span>&nbsp; ' . $justificacion . '</td>';
                    $cuerpo .= '</tr>';*/

                    /**
                     * En caso de tratarse de una comisiÃ³n se mostrarÃ¡ el motivo y el lugar.
                     */
                    if ($controlexcepcion->lugar == 1) {
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Motivo:</span>&nbsp; ' . utf8_decode($ce->justificacion) . '</td>';
                        $cuerpo .= '</tr>';
                        $cuerpo .= '<tr>';
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Lugar:</span>&nbsp; ' . utf8_decode($ce->destino) . '</td>';
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
                        $cuerpo .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;"><span style="font-weight: bold">Aplicaci&oacute;n R I. P.:</span>&nbsp; Art. ' . $excepcionrip->articulo;
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
                     * En caso de que la aprobacion provenga de una previa verificacion
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
                    $cuerpoCopia .= '<td style="font-family: Helvetica LT Condensed; font-size: 12px;">*********************************************************************************************************************************************</td>';
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
                    if ($copiaDestinatarioSecundario == 1 && $nombreDestinatarioSecundario != '' && is_object($contactoDestinatarioSecundario)) {
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
                    $cuerpo .= '<td style="text-align: left"><a href="' . $ruta . $idRelaboralSolicitanteCodificado . '/' . $idRelaboralDestinatarioPrincipalCodificado . '/' . $idRelaboralDestinatarioSecundarioCodificado . '/' . $idControlExcepcionCodificado . '/' . $estadoOperacionSolicitadaRechazadaCodificado . '/" id="aRechazarSolicitud"  target="_blank">Rechazar</a></td></tr></table>';
                    $linkRechazo = $ruta . $idRelaboralSolicitanteCodificado . '/' . $idRelaboralDestinatarioPrincipalCodificado . '/' . $idRelaboralDestinatarioSecundarioCodificado . '/' . $idControlExcepcionCodificado . '/' . $estadoOperacionSolicitadaRechazadaCodificado . '/';
                    $cuerpo .= '<br>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '</table></td>';
                    $cuerpo .= '</tr>';
                    $cuerpo .= '</table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br></br></br></br></br></br>' . $mensajePie . '</div>';
                    $cuerpoCopia .= '</table></td></tr></table></div></br><div id="divPieMensaje"></br></br></br></br></br></br></br></br></br></br></br></br>' . $mensajePie . '</div>';
                    $cuerpo .= '</body></html>';
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
                            #region Registro de historial de envios (Parte 1 de 3)
                            /**
                             * Se establece el registro del historial de envÃ­o de mensajes por correo electrÃ³nico.
                             * La posibilidad de modificar registro sÃ³lo se da cuando el estado  del registro es positivo.
                             */
                            $objCtrlExcepMsjes = Controlexcepcionesmensajes::findFirst("controlexcepcion_id = " . $idControlExcepcion . " AND controlexcepcion_estado > 0 AND estado > 0 AND baja_logica = 1");
                            if (is_object($objCtrlExcepMsjes)) {
                                $objCtrlExcepMsjes->user_mod_id = $idUsuario;
                                $objCtrlExcepMsjes->fecha_mod = $hoy;
                                $intentos = intval($objCtrlExcepMsjes->intentos) + 1;
                            } else {
                                $objCtrlExcepMsjes = new Controlexcepcionesmensajes();
                                $intentos = 1;
                                $objCtrlExcepMsjes->intentos = 1;
                                $objCtrlExcepMsjes->medio = 1;
                                $objCtrlExcepMsjes->estado = 1;
                                $objCtrlExcepMsjes->baja_logica = 1;
                                $objCtrlExcepMsjes->agrupador = 0;
                                $objCtrlExcepMsjes->user_reg_id = $idUsuario;
                                $objCtrlExcepMsjes->fecha_reg = $hoy;
                            }
                            $objCtrlExcepMsjes->controlexcepcion_id = $idControlExcepcion;
                            $objCtrlExcepMsjes->user_mail = $userMail;
                            $objCtrlExcepMsjes->relaboral_sol_id = $idRelaboralSolicitante;
                            $objCtrlExcepMsjes->user_sol_mail = $contactoRemitente->e_mail_inst;
                            $objCtrlExcepMsjes->relaboral_dest_id = $idRelaboralDestinatarioPrincipal;
                            $objCtrlExcepMsjes->user_dest_mail = $contactoDestinatarioPrincipal->e_mail_inst;
                            if ($copiaDestinatarioSecundario == 1 && $idRelaboralDestinatarioSecundario > 0 && is_object($contactoDestinatarioSecundario)) {
                                $objCtrlExcepMsjes->relaboral_cop_id = $idRelaboralDestinatarioSecundario;
                                $objCtrlExcepMsjes->user_cop_mail = $contactoDestinatarioSecundario->e_mail_inst;
                            }
                            $objCtrlExcepMsjes->operacion_solicitada = $operacion;
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
                            $mail->FromName = "Sistema de Recursos Humanos - MEN";
                            $operacionSolicitada = str_replace("&Oacute;", "�", $operacionSolicitada);
                            $mail->Subject = ucwords(utf8_decode("SOLICITUD " . $operacionSolicitada . " DE EXCEPCION"));
                            $mail->MsgHTML($cuerpo);
                            $mail->AddAddress($contactoDestinatarioPrincipal->e_mail_inst, $relaboralDestinatarioPrincipal->nombres);
                            $mail->AddCC($userMail, "SRRHH MEN");
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
                                 * En caso de haberse enviado correctamente se envÃ­a una copia, pero sin considerar las opciones de aprobaciÃ³n
                                 */
                                $mailCopia = new phpmaileroasis();
                                $mailCopia->IsSMTP();
                                $mailCopia->SMTPAuth = true;
                                $mailCopia->SMTPSecure = "ssl";
                                $mailCopia->Host = $hostMail;
                                $mailCopia->Port = $portMail;
                                $mailCopia->Username = $userMail;
                                $mailCopia->Password = $passMail;
                                $mailCopia->From = $userMail;
                                $mailCopia->FromName = "Sistema de Recursos Humanos - MEN";
                                $mailCopia->Subject = utf8_decode("Copia de SOLICITUD " . $operacionSolicitada . " DE EXCEPCION");
                                $mailCopia->MsgHTML($cuerpoCopia);
                                $mailCopia->AddAddress($contactoRemitente->e_mail_inst, $relaboralSolicitante->nombres);
                                $mailCopia->AddCC($userMail, "SRRHH - MEN");
                                /**
                                 * En caso de haberse seleccionado el envÃ­o al inmediato superior, se envÃ­a una copia
                                 */
                                if ($copiaDestinatarioSecundario == 1 && $idRelaboralDestinatarioSecundario > 0 && is_object($contactoDestinatarioSecundario)) {
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
                                        $msj = array('result' => 1, 'msj' => 'Envio exitoso de solicitud a las cuentas,' . ' sin embargo, hubo problemas en el env&oacute; de la copia al destinatario secundario.', 'estado' => $ce->estado);
                                    } else {
                                        $msj = array('result' => 1, 'msj' => 'Envio exitoso de solicitud a las cuentas:', 'estado' => $ce->estado);
                                    }
                                } else $msj = array('result' => 1, 'msj' => 'Envio exitoso de solicitud a las cuentas:', 'estado' => $ce->estado);
                                #region Registro de historial de envios  (Parte 2 de 3)
                                /**
                                 * Debido a que se ha logrado enviar al destinatario principal se registra el envÃ­o y los links dispuestos.
                                 */
                                $objCtrlExcepMsjes->link_aceptacion = $linkAceptacion;
                                $objCtrlExcepMsjes->link_rechazo = $linkRechazo;
                                $objCtrlExcepMsjes->user_env_id = $idUsuario;
                                $objCtrlExcepMsjes->fecha_env = $hoy;
                                #endregion Registro de historial de envios  (Parte 2 de 3)
                            } else {
                                #region Error en el envÃ­o
                                $ce = Controlexcepciones::findFirstById($idControlExcepcion);
                                $ce->user_mod_id = $idUsuario;
                                $ce->fecha_mod = $hoy;
                                $ce->estado = $estadoOperacionSolicitadaError;
                                if ($ce->save()) {
                                    $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a inexistencia de la cuenta del destinatario o error en el Servidor de Correo. Se volvera a reenviar en 5 minutos.', 'estado' => $ce->estado);
                                } else {
                                    $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a inexistencia de la cuenta del destinatario o error en el Servidor de Correo. Consulte con el Administrador.', 'estado' => $ce->estado);
                                }
                                #endregion Error en el envÃ­o
                            }
                            #region Registro de historial de envios  (Parte 3 de 3)
                            $objCtrlExcepMsjes->resultado = isset($msj["result"]) ? $msj["result"] : null;
                            $objCtrlExcepMsjes->mensaje = isset($msj["msj"]) ? $msj["msj"] : null;
                            $objCtrlExcepMsjes->controlexcepcion_estado = $ce->estado;
                            $objCtrlExcepMsjes->intentos = $intentos;
                            $objCtrlExcepMsjes->save();
                            #endregion Registro de historial de envios  (Parte 3 de 3)
                        } else {
                            $msj = array('result' => -1, 'msj' => 'Error Cr&iacute;tico: Datos incompletos.');
                        }
                    } else {
                        $msj = array('result' => 0, 'msj' => 'No se pudo enviar el correo debido a que no existe la cuenta del destinatario principal.', 'estado' => $controlexcepcion->controlexcepcion_estado);
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'No se admite el env&iacute;o del mensaje de correo debido a que el registro ya se encuentra inhabilitado para la tarea solicitada (' . $controlexcepcion->controlexcepcion_estado_descripcion . ').', 'estado' => $controlexcepcion->controlexcepcion_estado);
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'No se encontr&oacute; el registro correspondiente al usuario.');
            }
        } else {
            $msj = array('result' => 0, 'msj' => 'No se encontr&oacute; el registro correspondiente de la excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * FunciÃ³n para la verificaciÃ³n del cruce entre las excepciones registradas para un persona y adicionalmente
     * el control de la aplicabilidad del otorgamiento del permiso controlando que la frecuencia de uso no exceda lo permitido.
     */
    public function verificacruceexcesousoAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $id = $_POST['id'];
        $idRelaboral = $_POST['relaboral_id'];
        $idExcepcion = $_POST['excepcion_id'];
        $fechaIni = $_POST['fecha_ini'];
        $horaIni = $_POST['hora_ini'];
        $fechaFin = $_POST['fecha_fin'];
        $horaFin = $_POST['hora_fin'];
        $horario = $_POST['horario'];
        $justificacion = $_POST['justificacion'];
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $fechaFin != '' && $justificacion != '') {
            /**
             * Se realiza la verificaciÃ³n sobre el cruce de horarios y fechas de los controles de excepciÃ³n existentes y la que se intenta registrar o modificar.
             */
            /*$objControlExcepciones = Controlexcepciones::findFirstById($_POST["id"]);
            if ($objControlExcepciones->id > 0 && $objControlExcepciones->estado == 2) {
                try {
                    $objControlExcepciones->estado = 1;
                    $objControlExcepciones->user_mod_id = $user_mod_id;
                    $objControlExcepciones->fecha_mod = $hoy;
                    $ok = $objControlExcepciones->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se aprob&oacute; correctamente el registro del control de  excepci&oacute;n.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se aprob&oacute; el registro de control de excepci&oacute;n.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del control de excepci&oacute;n.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro de control de excepci&oacute;n no cumple con el requisito establecido para su aprobaci&oacute;n, debe estar en estado EN PROCESO.');
            }*/
            $msj = array('result' => 0, 'msj' => 'No existe cruce de horarios ni fechas.');
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro del control de excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * FunciÃ³n para el registro de un determinado tipo de permiso para un perfil laboral en particular.
     */
    public function savemassivebyperfilAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $auth['id'];
        $msj = Array();
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Registro del Control de ExcepciÃ³n masivo
             */
            $idPerfilLaboral = $_POST['id'];
            $idExcepcion = $_POST['excepcion_id'];
            $fechaIni = $_POST['fecha_ini'];
            $horaIni = $_POST['hora_ini'];
            $fechaFin = $_POST['fecha_fin'];
            $horaFin = $_POST['hora_fin'];
            $estado = 3;
            $justificacion = $_POST['justificacion'];
            $observacion = $_POST['observacion'];
            if ($idPerfilLaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $horaIni != '' && $fechaFin != '' && $horaFin != '' && $justificacion != '') {
                try {
                    $obj = new Controlexcepciones();
                    $ok = $obj->registroMasivoPorPerfil($idPerfilLaboral, $fechaIni, $horaIni, $fechaFin, $horaFin, $justificacion, $observacion, $estado, $user_reg_id);
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se realiz&oacute; correctamente el registro masivo.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute;.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del control de excepci&oacute;n masivo.');
                }

            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Obtiene la cantidad de dÃ­as de diferencia entre dos fechas.
     * @param $primera
     * @param $segunda
     * @param string $sep
     * @return int
     */
    public function vervalideztemporalidadsolicitudAction()
    {
        $idControlExcepcion = $_POST["id_controlexcepcion"];
        $this->view->disable();
        $cantidadDiasTranscurridos = 0;
        if ($idControlExcepcion > 0) {
            $controlexcepcion = Controlexcepciones::findFirstById($idControlExcepcion);
            $hoy = date("d-m-Y");
            $fechaIni = $controlexcepcion->fecha_ini != "" ? date("d-m-Y", strtotime($controlexcepcion->fecha_ini)) : "";
            $sep = "-";
            $valoresPrimera = explode($sep, $hoy);
            $valoresSegunda = explode($sep, $fechaIni);
            $diaPrimera = $valoresPrimera[0];
            $mesPrimera = $valoresPrimera[1];
            $anyoPrimera = $valoresPrimera[2];
            $diaSegunda = $valoresSegunda[0];
            $mesSegunda = $valoresSegunda[1];
            $anyoSegunda = $valoresSegunda[2];
            $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);
            $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);
            if (!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)) {
                // "La fecha ".$primera." no es vÃ¡lida";
                //$msj = array('result' => 0);
                $cantidadDiasTranscurridos = 0;
            } elseif (!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)) {
                // "La fecha ".$segunda." no es vÃ¡lida";
                //$msj = array('result' => 0);
                $cantidadDiasTranscurridos = 0;
            } else {
                //$result = $diasPrimeraJuliano - $diasSegundaJuliano;
                $cantidadDiasTranscurridos = $diasPrimeraJuliano - $diasSegundaJuliano;;
                //$msj = array('result' => $result);
            }
            $cantidadDeDiasAdmitidos = 2;
            $parametro = Parametros::findFirst("parametro LIKE 'CANTIDAD_DIAS_PERMITIDOS_PARA_OPERACION_CONTROLEXCEPCION'");
            if (is_object($parametro)) {
                $cantidadDeDiasAdmitidos = $parametro->nivel;
            }
            if ($cantidadDiasTranscurridos <= $cantidadDeDiasAdmitidos) {
                $cantidad = $cantidadDeDiasAdmitidos - $cantidadDiasTranscurridos;
                if ($cantidad == 0)
                    $msj = array('result' => 1, 'cantidad' => $cantidadDiasTranscurridos, 'msj' => 'Todavia se encuentra dentro el plazo permitido de env&iacute;o quedando s&oacute;lo hoy.');
                else $msj = array('result' => 1, 'cantidad' => $cantidadDiasTranscurridos, 'msj' => 'Todavia se encuentra dentro el plazo de entrega, queda(n) (' . $cantidad . ') d&iacute;a(s).');
            } else {
                $cantidad = $cantidadDiasTranscurridos - $cantidadDeDiasAdmitidos;
                $msj = array('result' => 0, 'cantidad' => $cantidad, 'msj' => 'El tiempo v&aacute;lido para la solicitud ha vencido hace ' . $cantidad . ' d&iacute;a(s). La cantidad admitida es ' . $cantidadDeDiasAdmitidos . ' d&iacute;as.');
            }
        } else $msj = array('result' => -1, 'cantidad' => -1, 'msj' => 'Error en el env&iacute;o de datos.');
        echo json_encode($msj);
    }

    /**
     * FunciÃ³n para la validaciÃ³n de la temporalidad de una solicitud.
     */
    public function verificavalideztemporalidadsolicitudAction()
    {
        $idRelaboral = $_POST["id_relaboral"];
        $fecha = $_POST["fecha"];
        $this->view->disable();
        $obj = new Fcontrolexcepciones();
        $opcion = 1;
        $plazo = $obj->verificaPlazoValidezSolicitud($idRelaboral, $fecha, $opcion);
        if ($plazo >= 0) {
            $msj = array('result' => 1, 'cantidad' => $plazo, 'msj' => '&Eacute;xito: el registro es vÃ¡lido.');
        } else {
            $plazo = $plazo * -1;
            if ($opcion == 1) {
                $msje = 'Error: El plazo de registro para &eacute;sta boleta con la fecha solicitada venci&oacute; hace ' . $plazo . ' d&iacute;a(s) h&aacute;biles.';
            } else {
                $msje = 'Error: El plazo de registro para &eacute;sta boleta con la fecha solicitada venci&oacute;. ';
            }
            $msj = array('result' => -1, 'cantidad' => $plazo, 'msj' => $msje);
        }

        echo json_encode($msj);
    }
} 