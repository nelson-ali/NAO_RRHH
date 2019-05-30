<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  02-12-2015
*/

class MisideasController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Función para la carga de la página de gestión de ideas de negocios.
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
        //$this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);

        $this->assets->addJs('/js/misideas/oasis.misideas.tab.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.index.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.list.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.finish.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.down.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.move.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.export.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.export.form.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.view.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.view.splitter.js?v=' . $version);
        $this->assets->addJs('/js/misideas/oasis.misideas.send.js?v=' . $version);
        $this->assets->addJs('/js/ckeditor/ckeditor.js?v=' . $version);
        $auth = $this->session->get('auth');
        $objUsr = new usuarios();
        $relaboral = $objUsr->getOneRelaboralActivo($auth['id']);
        if (is_object($relaboral)) {
            $this->view->setVar('idRelaboral', $relaboral[0]->id_relaboral);
            $this->view->setVar('idPersona', $relaboral[0]->id_persona);
            $this->view->setVar('ci', $relaboral[0]->ci);
            $this->view->setVar('nombres', $relaboral[0]->nombres);
        }
    }
    /**
     * Función para la obtención del listado de ideas pertenecientes a una persona.
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Fideas();
        $ideas = Array();
        $idRelaboral = 0;

        $this->view->disable();
        $obj = new Fideas();
        $ideas = Array();
        $gestion = 0;
        $mes = 0;
        $idRealboral = 0;
        if (isset($_GET["gestion"])) {
            $gestion = $_GET["gestion"];
        }
        if (isset($_GET["mes"])) {
            $mes = $_GET["mes"];
        }
        if (isset($_GET["id"])) {
            $idRelaboral = $_GET["id"];
        }
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
                    switch ($filtercondition) {
                        case 'NOT_EMPTY':
                        case 'NOT_NULL':
                            $where .= ' UPPER(' . $filterdatafield . ') NOT LIKE "' . '' . '"';
                            break;
                        case 'EMPTY':
                        case 'NULL':
                            $where .= ' (UPPER(' . $filterdatafield . ') LIKE "' . '' . '") OR (UPPER(' . $filterdatafield . ') IS NULL)';
                            break;
                        case 'CONTAINS_CASE_SENSITIVE':
                            $where .= ' BINARY  ' . $filterdatafield . ' LIKE "%' . $filtervalue . '%"';
                            break;
                        case 'CONTAINS':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'DOES_NOT_CONTAIN_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' NOT LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'DOES_NOT_CONTAIN':
                            $where .= ' UPPER(' . $filterdatafield . ') NOT LIKE UPPER("%' . $filtervalue . '%")';
                            break;
                        case 'EQUAL_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' = "' . $filtervalue . '"';
                            break;
                        case "EQUAL":
                            $where .= ' ' . $filterdatafield . ' = "' . $filtervalue . '"';
                            break;
                        case 'NOT_EQUAL_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' <> "' . $filtervalue . '"';
                            break;
                        case 'NOT_EQUAL':
                            $where .= ' ' . $filterdatafield . ' <> "' . $filtervalue . '"';
                            break;
                        case 'GREATER_THAN':
                            $where .= ' ' . $filterdatafield . ' > "' . $filtervalue . '"';
                            break;
                        case 'LESS_THAN':
                            $where .= ' ' . $filterdatafield . ' < "' . $filtervalue . '"';
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $where .= ' ' . $filterdatafield . ' >= "' . $filtervalue . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $where .= ' ' . $filterdatafield . ' <= "' . $filtervalue . '"';
                            break;
                        case 'STARTS_WITH_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' LIKE "' . $filtervalue . '%"';
                            break;
                        case 'STARTS_WITH':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("' . $filtervalue . '%")';
                            break;
                        case 'ENDS_WITH_CASE_SENSITIVE':
                            $where .= ' BINARY ' . $filterdatafield . ' LIKE "%' . $filtervalue . '"';
                            break;
                        case 'ENDS_WITH':
                            $where .= ' UPPER(' . $filterdatafield . ') LIKE UPPER("%' . $filtervalue . '")';
                            break;
                    }

                    if ($i == $filterscount - 1) {
                        $where .= ')';
                    }

                    $tmpfilteroperator = $filteroperator;
                    $tmpdatafield = $filterdatafield;
                }
            }
        }
        if ($idRelaboral > 0) {
            $gestion = $_GET["gestion"];
            $relaborales = Relaborales::findFirstById($idRelaboral);
            if ($idRelaboral > 0 && is_object($relaborales) && $relaborales->persona_id > 0) {
                $resul = $obj->getAllByGestionAndMonth($relaborales->persona_id, $gestion, 0, 100, $where, "", $start, $pagesize);
                //comprobamos si hay filas
                if (count($resul) > 0) {
                    foreach ($resul as $v) {
                        $total_rows = $v->total_rows;
                        $ideas[] = array(
                            'nro_row' => 0,
                            'id' => $v->id_idea,
                            'padre_id' => $v->padre_id,
                            'relaboral_id' => $v->relaboral_id,
                            'rubro_id' => $v->rubro_id,
                            'tipo_negocio' => $v->tipo_negocio,
                            'tipo_negocio_descripcion' => $v->tipo_negocio_descripcion,
                            'gestion' => $v->gestion,
                            'mes' => $v->mes,
                            'mes_nombre' => $v->mes_nombre,
                            'numero' => $v->numero,
                            'titulo' => $v->titulo,
                            'resumen' => $v->resumen,
                            'descripcion' => $v->descripcion,
                            'inversion' => $v->inversion,
                            'beneficios' => $v->beneficios,
                            'puntuacion_a' => $v->puntuacion_a,
                            'puntuacion_a_descripcion' => $v->puntuacion_a_descripcion,
                            'puntuacion_b' => $v->puntuacion_b,
                            'puntuacion_b_descripcion' => $v->puntuacion_b_descripcion,
                            'puntuacion_c' => $v->puntuacion_c,
                            'puntuacion_c_descripcion' => $v->puntuacion_c_descripcion,
                            'puntuacion_d' => $v->puntuacion_d,
                            'puntuacion_d_descripcion' => $v->puntuacion_d_descripcion,
                            'puntuacion_e' => $v->puntuacion_e,
                            'puntuacion_e_descripcion' => $v->puntuacion_e_descripcion,
                            'observacion' => $v->observacion,
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'baja_logica' => $v->baja_logica,
                            'agrupador' => $v->agrupador,
                            'user_reg_id' => $v->user_reg_id,
                            'user_reg' => $v->user_reg,
                            'pseudonimo' => $v->pseudonimo,
                            'fecha_reg' => $v->fecha_reg != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_reg)) : "",
                            'user_mod_id' => $v->user_mod_id,
                            'user_mod' => $v->user_mod,
                            'fecha_mod' => $v->fecha_mod != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_mod)) : "",
                            'user_punt_a_id' => $v->user_punt_a_id,
                            'user_punt_a' => $v->user_punt_a,
                            'fecha_punt_a' => $v->fecha_punt_a != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_punt_a)) : "",
                            'user_punt_b_id' => $v->user_punt_b_id,
                            'user_punt_b' => $v->user_punt_b,
                            'fecha_punt_b' => $v->fecha_punt_b != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_punt_b)) : "",
                            'user_punt_c_id' => $v->user_punt_c_id,
                            'user_punt_c' => $v->user_punt_c,
                            'fecha_punt_c' => $v->fecha_punt_c != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_punt_c)) : "",
                            'user_punt_d_id' => $v->user_punt_d_id,
                            'user_punt_d' => $v->user_punt_d,
                            'fecha_punt_d' => $v->fecha_punt_d != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_punt_d)) : "",
                            'user_punt_e_id' => $v->user_punt_e_id,
                            'user_punt_e' => $v->user_punt_e,
                            'fecha_punt_e' => $v->fecha_punt_e != "" ? date("d-m-Y H:i:s", strtotime($v->fecha_punt_e)) : ""
                        );
                    }
                }
            }
        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $ideas
        );
        echo json_encode($data);
    }

    /**
     * Función para el registro y modificación de las ideas de negocio.
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
             * Modificación de registro de Idea de Negocio.
             */
            $idRelaboral = $_POST['relaboral_id'];
            $tipoNegocio = $_POST["tipo_negocio"];
            $gestion = $_POST["gestion"];
            $mes = $_POST["mes"];
            $idIdea = $_POST['id'];
            $titulo = $_POST['titulo'];
            $resumen = $_POST['resumen'];
            $descripcion = $_POST['descripcion'];
            $observacion = $_POST['observacion'];
            if ($idRelaboral > 0 && $idIdea > 0 && $tipoNegocio > 0 && $gestion > 0 && $mes > 0 && $titulo != '' && $descripcion != '') {
                $objIdea = Ideas::findFirstById($_POST["id"]);
                if (is_object($objIdea)) {
                    $objIdea->relaboral_id = $idRelaboral;
                    $objIdea->tipo_negocio = $tipoNegocio;
                    $objIdea->gestion = $gestion;
                    $objIdea->mes = $mes;
                    $objIdea->resumen = $resumen;
                    $objIdea->descripcion = $descripcion;
                    $objIdea->observacion = $observacion;
                    $objIdea->user_mod_id = $user_mod_id;
                    $objIdea->fecha_mod = $hoy;
                    try {
                        if ($objIdea->save()) {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; el registro de la Idea de Negocio de modo satisfactorio.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro de la Idea de Negocio.');
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la Idea de Negocio.');
                    }
                } else $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados son similares a otro registro existente, debe modificar los valores necesariamente.');

            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        } else {
            /**
             * Nuevo registro de Idea de Negocio.
             */
            $idRelaboral = $_POST['relaboral_id'];
            $tipoNegocio = $_POST["tipo_negocio"];
            $gestion = $_POST["gestion"];
            $mes = $_POST["mes"];
            $titulo = $_POST['titulo'];
            $resumen = $_POST['resumen'];
            $descripcion = $_POST['descripcion'];
            $observacion = $_POST['observacion'];
            if ($idRelaboral > 0 && $titulo != '' && $descripcion != '') {
                $maximo = Ideas::maximum(
                    array(
                        "column" => "numero",
                        "conditions" => "relaboral_id = " . $idRelaboral . " AND gestion=" . $gestion . " AND mes=" . $mes . " AND baja_logica=1"
                    )
                );
                $objIdea = new Ideas();
                $objIdea->relaboral_id = $idRelaboral;
                $objIdea->tipo_negocio = $tipoNegocio;
                $objIdea->gestion = $gestion;
                $objIdea->mes = $mes;
                $objIdea->numero = $maximo + 1;
                $objIdea->titulo = $titulo;
                $objIdea->resumen = $resumen;
                $objIdea->descripcion = $descripcion;
                $objIdea->observacion = $observacion;
                $objIdea->estado = 1;
                $objIdea->baja_logica = 1;
                $objIdea->agrupador = 0;
                $objIdea->user_reg_id = $user_mod_id;
                $objIdea->fecha_reg = $hoy;
                try {
                    if ($objIdea->save()) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se realiz&oacute; el registro de la Idea de Negocio de modo satisfactorio. Se agradece el registro de su idea de negocio.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se pudo realizar el registro de la Idea de Negocio.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la Idea de Negocio.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }

    public function finishAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Aprobación de registro
             */
            $objIdeas = Ideas::findFirstById($_POST["id"]);
            if ($objIdeas->id > 0 && $objIdeas->estado == 1) {
                try {
                    $objIdeas->estado = 2;
                    $objIdeas->user_mod_id = $user_mod_id;
                    $objIdeas->fecha_mod = $hoy;
                    $ok = $objIdeas->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se concluy&oacute; correctamente el registro de la Idea de Negocio.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se pudo concluir el registro de la Idea de Negocio.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Idea de Negocio.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro de Idea de Negocio no cumple con el requisito establecido para su conclusi&oacute;n, debe estar en estado EN ELABORACI&Oacute;N.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro de la Idea de Negocio.');
        }
        echo json_encode($msj);
    }

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
                $objIdeas = Ideas::findFirstById($_POST["id"]);
                $objIdeas->estado = 0;
                $objIdeas->baja_logica = 0;
                $objIdeas->user_mod_id = $user_mod_id;
                $objIdeas->fecha_mod = $hoy;
                if ($objIdeas->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                } else {
                    foreach ($objIdeas->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de la Idea de Negocio.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de la Idea de Negocio.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la Idea de Negocio.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para el despliegue del listado de tipos de negocios conocidos.
     */
    public function listtiposdenegocioAction()
    {
        $this->view->disable();
        $tiposDeNegocio = Parametros::find(array("parametro LIKE 'TIPO_NEGOCIO' AND estado>0 AND baja_logica=1"));
        if (is_object($tiposDeNegocio)) {
            foreach ($tiposDeNegocio as $reg) {
                $data[] = array(
                    'tipo' => $reg->nivel,
                    'tipo_descripcion' => $reg->valor_1
                );
            }
        }
        echo json_encode($data);
    }
} 