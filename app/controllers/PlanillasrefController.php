<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  02-06-2015
*/

class PlanillasrefController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

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
        $this->assets->addJs('/js/jqwidgets/jqxgrid.aggregates.js?v=' . $version);

        $this->assets->addJs('/js/colorpicker-master/js/evol.colorpicker.min.js?v=' . $version);
        $this->assets->addCss('/js/colorpicker-master/css/evol.colorpicker.css?v=' . $version);

        $this->assets->addJs('/js/planillasref/oasis.planillasref.tab.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.index.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.generate.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.edit.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.view.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.approve.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.export.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.down.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.impuestos.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.otras.js?v=' . $version);
        $this->assets->addJs('/js/planillasref/oasis.planillasref.otras.export.js?v=' . $version);
    }

    /**
     * Función para la obtención del listado de planillas registradas en el sistema.
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Fplanillasref();
        $planillassal = Array();
        $resul = $obj->getAll();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $planillassal[] = array(
                    'id' => $v->id,
                    'da_id' => $v->da_id,
                    'ejecutora_id' => $v->ejecutora_id,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'regional_id' => $v->regional_id,
                    'regional' => $v->regional,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'finpartida_id' => $v->finpartida_id,
                    'fin_partida' => $v->fin_partida,
                    'condicion_id' => $v->condicion_id,
                    'condicion' => $v->condicion,
                    'tipoplanilla_id' => $v->tipoplanilla_id,
                    'tipo_planilla' => $v->tipo_planilla,
                    'numero' => $v->numero == 0 ? null : $v->numero,
                    'total_ganado' => $v->total_ganado,
                    'total_liquido' => $v->total_liquido,
                    'cantidad_relaborales' => $v->cantidad_relaborales,
                    'observacion' => $v->observacion,
                    'motivo_anu' => $v->motivo_anu,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'user_ver_id' => $v->user_ver_id,
                    'fecha_ver' => $v->fecha_ver,
                    'user_apr_id' => $v->user_apr_id,
                    'fecha_apr' => $v->fecha_apr,
                    'user_rev_id' => $v->user_rev_id,
                    'fecha_rev' => $v->fecha_rev,
                    'user_anu_id' => $v->user_anu_id,
                    'fecha_anu' => $v->fecha_anu
                );
            }
        }
        echo json_encode($planillassal);
    }

    /**
     * Función para la obtención del listado de meses disponibles para la generación de planillas salariales.
     */
    public function getmesesgeneracionAction()
    {
        $this->view->disable();
        $meses = [];
        if (isset($_POST["gestion"]) && $_POST["gestion"] > 0) {
            $objPlanilla = new Fplanillasref();
            $resul = $objPlanilla->getMesesGeneracionPlanillasRef($_POST["gestion"]);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $meses[] = array(
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre
                    );
                }
            }
        }
        echo json_encode($meses);
    }

    /**
     * Función para la obtención del listado de financimientos por partida disponibles para la generación de planillas salariales,
     * considerando una gestión y mes particulares.
     */
    public function getfinpartidasgeneracionAction()
    {
        $this->view->disable();
        $finpartidas = [];
        if (isset($_POST["gestion"]) && $_POST["gestion"] > 0 && isset($_POST["mes"]) && $_POST["mes"] > 0) {
            $objPlanilla = new Fplanillasref();
            $resul = $objPlanilla->getFinPartidasGeneracionPlanillasRef($_POST["gestion"], $_POST["mes"]);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $finpartidas[] = array(
                        'id_finpartida' => $v->id_finpartida,
                        'fin_partida' => $v->fin_partida
                    );
                }
            }
        }
        echo json_encode($finpartidas);
    }

    /**
     * Función para la obtención del listado de tipos de planilla salarial disponibles.
     */
    public function gettiposplanillasrefAction()
    {
        $this->view->disable();
        $tipos_planillas = [];
        if (isset($_POST["gestion"]) && $_POST["gestion"] > 0 && isset($_POST["mes"]) && $_POST["mes"] > 0 && isset($_POST["id_finpartida"]) && $_POST["id_finpartida"] > 0) {
            $objPlanilla = new Fplanillasref();
            $resul = $objPlanilla->getTiposPlanillasGeneracionPlanillasRef($_POST["gestion"], $_POST["mes"], $_POST["id_finpartida"]);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $tipos_planillas[] = array(
                        'id_tipoplanilla' => $v->id_tipoplanilla,
                        'numero' => $v->numero,
                        'tipo_planilla_disponible' => $v->tipo_planilla_disponible
                    );
                }
            }
        }
        echo json_encode($tipos_planillas);
    }

    /**
     * Función para la generación de planillas salariales.
     */
    public function displayplanpreviaAction()
    {
        $this->view->disable();
        $obj = new Frelaboralesplanillaref();
        $planillassal = Array();
        $gestion = 0;
        $mes = 0;
        $idFinPartida = 0;
        $idTipoPlanilla = 0;
        $numeroPlanilla = 0;
        $jsonIdRelaborales = '';
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $arrIdRelaborales = array();
        if (isset($_GET["gestion"]) && $_GET["gestion"] > 0)
            $gestion = $_GET["gestion"];
        if (isset($_GET["mes"]) && $_GET["mes"] > 0)
            $mes = $_GET["mes"];
        if (isset($_GET["id_finpartida"]) && $_GET["id_finpartida"] > 0)
            $idFinPartida = $_GET["id_finpartida"];
        if (isset($_GET["id_tipoplanilla"]) && $_GET["id_tipoplanilla"] > 0)
            $idTipoPlanilla = $_GET["id_tipoplanilla"];
        if (isset($_GET["numero"]) && $_GET["numero"] > 0)
            $numeroPlanilla = $_GET["numero"];
        if (isset($_GET["id_relaborales"]) && $_GET["id_relaborales"] != '') {
            $arrIdRelaborales = explode("|", $_GET["id_relaborales"]);
        }
        if (count($arrIdRelaborales) > 0) {
            $jsonIdRelaborales = '{';
            foreach ($arrIdRelaborales as $clave => $idRelaboral) {
                $jsonIdRelaborales .= '"' . $clave . '":' . $idRelaboral . ',';
            }
            $jsonIdRelaborales .= ',';
            $jsonIdRelaborales = str_replace(",,", "", $jsonIdRelaborales);
            $jsonIdRelaborales .= '}';
        }
        if ($gestion > 0 && $mes > 0 && $idFinPartida > 0 && $idTipoPlanilla > 0 && $numeroPlanilla >= 0) {

            $resul = $obj->desplegarPlanillaPreviaRef($gestion, $mes, $idFinPartida, $jsonIdRelaborales);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {

                    $opcion = '';
                    if ($v->total_ganado > 0) $opcion = '<a href="#" class="btnForm110" id="' . $v->id_relaboral . '" onclick="abrirVentanaModalForm110ImpRef(' . $v->id_relaboral . ',' . $v->gestion . ',' . $v->mes . ',' . $idUsuario . ');"><i class="fa fa-file-text-o fa-2x text-info" title="Registrar Formulario 110"></i></a>';
                    $planillassal[] = array(
                        'opcion' => $opcion,
                        'id_relaboral' => $v->id_relaboral,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'cargo' => $v->cargo,
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'area' => $v->area,
                        'ubicacion' => $v->ubicacion,
                        'fin_partida' => $v->fin_partida,
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'procesocontratacion_codigo' => $v->procesocontratacion_codigo,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'sueldo' => $v->sueldo,
                        'dias_efectivos' => $v->dias_efectivos,
                        'monto_diario' => $v->monto_diario,
                        'faltas' => $v->faltas,
                        'lsgh' => $v->lsgh,
                        'vacacion' => $v->vacacion,
                        'otros' => $v->otros,
                        'id_form110impref' => $v->id_form110impref,
                        'importe' => $v->importe,
                        'rc_iva' => $v->rc_iva,
                        'retencion' => $v->retencion,
                        'form110impref_observacion' => $v->form110impref_observacion,
                        'fecha_form' => $v->fecha_form,
                        'total_ganado' => $v->total_ganado,
                        'total_liquido' => $v->total_liquido,
                        'cargo' => $v->cargo,
                        'total_descuentos' => $v->total_descuentos,
                        'total_ganado' => $v->total_ganado,
                        'total_liquido' => $v->total_liquido,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'cargar_formulario_110' => $jsonIdRelaborales == '' ? 0 : 1,
                        'available' => false
                    );
                }
            }
        }
        echo json_encode($planillassal);
    }

    /**
     * Función para la generación de la planilla de refrigerios.
     */
    public function genplanillaAction()
    {
        $this->view->disable();
        $obj = new Frelaboralesplanillaref();
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $planillassal = Array();
        $gestion = 0;
        $mes = 0;
        $idFinPartida = 0;
        $idTipoPlanilla = 0;
        $numeroPlanilla = 0;
        $arrIdRelaborales = array();
        $observacion = "";
        $jsonIdRelaborales = "";
        if (isset($_POST["gestion"]) && $_POST["gestion"] > 0)
            $gestion = $_POST["gestion"];
        if (isset($_POST["mes"]) && $_POST["mes"] > 0)
            $mes = $_POST["mes"];
        if (isset($_POST["id_finpartida"]) && $_POST["id_finpartida"] > 0)
            $idFinPartida = $_POST["id_finpartida"];
        if (isset($_POST["id_tipoplanilla"]) && $_POST["id_tipoplanilla"] > 0)
            $idTipoPlanilla = $_POST["id_tipoplanilla"];
        if (isset($_POST["numero"]) && $_POST["numero"] > 0)
            $numeroPlanilla = $_POST["numero"];
        if (isset($_POST["id_relaborales"]) && $_POST["id_relaborales"] != '') {
            $arrIdRelaborales = explode("|", $_POST["id_relaborales"]);
        }
        if (isset($_POST["obs"]) && $_POST["obs"] != '') {
            $observacion = $_POST["obs"];
        }
        if (count($arrIdRelaborales) > 0) {
            $jsonIdRelaborales = '{';
            foreach ($arrIdRelaborales as $clave => $idRelaboral) {
                $jsonIdRelaborales .= '"' . $clave . '":' . $idRelaboral . ',';
            }
            $jsonIdRelaborales .= ',';
            $jsonIdRelaborales = str_replace(",,", "", $jsonIdRelaborales);
            $jsonIdRelaborales .= '}';
        }
        $numeroPlanilla = $obj->obtenerNumeroDePlanillaRef($gestion, $mes, $idFinPartida);
        if ($numeroPlanilla == 1) {
            /**
             * Planilla Mensual
             */
            $idTipoPlanilla = 1;
        } else {
            /**
             * Planilla Adicional
             */
            $idTipoPlanilla = 2;
        }
        if ($gestion > 0 && $mes > 0 && $idFinPartida > 0 && $idTipoPlanilla > 0 && $numeroPlanilla >= 0 && $jsonIdRelaborales != '') {
            $resul = $obj->desplegarPlanillaPreviaRef($gestion, $mes, $idFinPartida, $jsonIdRelaborales);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                $planillaref = new Planillasref();
                $planillaref->da_id = 1;//Valor prefijado mientras no se tenga en la institución más Direcciones Administrativas
                $planillaref->ejecutora_id = 1;//Valor prefijado mientras no se tenga en la institución de más unidades ejecutoras.
                $planillaref->regional_id = 1; //Valor prefijado mientras no se tenga en la institución de más regionales.
                $planillaref->gestion = $gestion;
                $planillaref->mes = $mes;
                $planillaref->finpartida_id = $idFinPartida;
                $planillaref->tipoplanilla_id = $idTipoPlanilla;
                $planillaref->numero = $numeroPlanilla;
                $planillaref->total_ganado = 0;
                $planillaref->total_liquido = 0;
                if ($observacion != '') {
                    $planillaref->observacion = $observacion;;
                }
                $planillaref->estado = 1;
                $planillaref->baja_logica = 1;
                $planillaref->agrupador = 0;
                $planillaref->user_reg_id = $user_reg_id;
                $planillaref->fecha_reg = $hoy;
                $totalGanado = 0;
                $totalLiquido = 0;
                $cantidadPlanillados = 0;
                try {
                    if ($planillaref->save()) {
                        foreach ($resul as $v) {
                            #region registro del descuento correspondiente
                            $descuentoref = Descuentosref::findFirst(array("relaboral_id=" . $v->id_relaboral . " AND gestion=" . $v->gestion . " AND mes=" . $v->mes));
                            if (is_object($descuentoref)) {
                                $descuentoref->user_mod_id = $user_mod_id;
                                $descuentoref->fecha_mod = $hoy;
                            } else {
                                $descuentoref = new Descuentosref();
                                $descuentoref->relaboral_id = $v->id_relaboral;
                                $descuentoref->gestion = $v->gestion;
                                $descuentoref->mes = $v->mes;
                                $descuentoref->user_reg_id = $user_reg_id;
                                $descuentoref->fecha_reg = $hoy;

                            }
                            $descuentoref->faltas = $v->faltas;
                            $descuentoref->vacacion = $v->vacacion;
                            $descuentoref->lsgh = $v->lsgh;
                            $descuentoref->otros = $v->otros;
                            $descuentoref->total_descuentos = $v->total_descuentos;
                            $descuentoref->estado = 1;
                            $descuentoref->baja_logica = 1;
                            $descuentoref->agrupador = 0;

                            if ($descuentoref->save()) {

                                #region Control de registro de impuestos por refrigerios

                                $objForm110ImpRef = Form110impref::findFirst(array("relaboral_id=" . $v->id_relaboral . " AND gestion=" . $gestion . " AND mes=" . $mes));
                                $okForm = true;
                                if (!is_object($objForm110ImpRef)) {
                                    $objForm110ImpRef = new Form110impref();
                                    $objForm110ImpRef->relaboral_id = $v->id_relaboral;
                                    $objForm110ImpRef->gestion = $gestion;
                                    $objForm110ImpRef->mes = $mes;
                                    $objForm110ImpRef->observacion = "REGISTRO AUTOMATICO DEBIDO A QUE NO PRESENTO FORMULARIO 110";
                                    $objForm110ImpRef->user_reg_id = $user_reg_id;
                                    $objForm110ImpRef->fecha_reg = $hoy;
                                } else {
                                    $objForm110ImpRef->user_mod_id = $user_mod_id;
                                    $objForm110ImpRef->fecha_mod = $hoy;
                                }
                                $objForm110ImpRef->cantidad = 1;
                                $objForm110ImpRef->monto_diario = $v->monto_diario;
                                $objForm110ImpRef->importe = $v->importe;
                                $objForm110ImpRef->impuesto = $v->rc_iva;
                                /*$rc_iva_debido = $v->total_ganado * 0.13;
                                $retencion = round($rc_iva_debido - $v->importe*0.13,0);
                                if($retencion<0){
                                    $retencion = 0;
                                }
                                $objForm110ImpRef->retencion = $retencion;
                                */
                                $objForm110ImpRef->retencion = $v->retencion;
                                $objForm110ImpRef->fecha_form = $hoy;
                                $objForm110ImpRef->estado = 1;
                                $objForm110ImpRef->baja_logica = 1;
                                $objForm110ImpRef->agrupador = 0;
                                try {
                                    $okForm = $objForm110ImpRef->save();
                                } catch (\Exception $e) {
                                    $okForm = false;
                                }
                                #endregion Control de registro de impuestos por refrigerios
                                if (is_object($objForm110ImpRef) && $objForm110ImpRef->id > 0) {
                                    #region registro del pago salarial
                                    $pagosref = new Pagosref();
                                    $pagosref->form110impref_id = $objForm110ImpRef->id;
                                    $pagosref->relaboral_id = $v->id_relaboral;
                                    $pagosref->planillaref_id = $planillaref->id;
                                    $pagosref->descuentoref_id = $descuentoref->id;
                                    if ($v->id_form110impref != null && $v->id_form110impref > 0) {
                                        $pagosref->form110impref_id = $v->id_form110impref;
                                    }
                                    $pagosref->dias_efectivos = $v->dias_efectivos;
                                    $pagosref->total_ganado = $v->total_ganado;
                                    $totalGanado += $v->total_ganado;
                                    $retencion = 0;
                                    if ($objForm110ImpRef->retencion > 0) $retencion = $objForm110ImpRef->retencion;
                                    $pagosref->total_liquido = $v->total_ganado - $retencion;
                                    $totalLiquido += $v->total_liquido;
                                    $pagosref->estado = 1;
                                    $pagosref->baja_logica = 1;
                                    $pagosref->agrupador = 0;
                                    $pagosref->user_reg_id = $user_reg_id;
                                    $pagosref->fecha_reg = $hoy;

                                    if ($pagosref->save()) {
                                        /**
                                         * Una vez registrada la planilla se debe planillar los registros de horarios y marcaciones,
                                         * que consiste en poner en un estado PLANILLADO en el rango correspondiente para el registro de horarios y marcaciones
                                         */
                                        $obj = new Horariosymarcaciones();
                                        $ok = $obj->planillarHorariosYMarcacionesPorRefrigerios($v->id_relaboral, $gestion, $mes);
                                        if ($ok && $okForm) $cantidadPlanillados++;
                                        //$cantidadPlanillados++;
                                    } else break;
                                    #endregion registro del pago salarial
                                }
                            } else break;
                            #endregion registro del descuento correspondiente
                        }
                        if ($cantidadPlanillados == count($resul)) {
                            $planillaref->total_ganado = $totalGanado;
                            $planillaref->total_liquido = $totalLiquido;
                            if ($planillaref->save()) {
                                $msj = array('result' => 1, 'msj' => 'Generaci&oacute;n exitosa de la Planilla de Refrigerios con ' . $cantidadPlanillados . ' registros considerados.');
                            }
                        } else {
                            /**
                             * Se eliminan todos los pagos que se pudieran haber registrado con la planilla.
                             */
                            $db = $this->getDI()->get('db');
                            $db->execute("DELETE FROM pagosref WHERE planillaref_id=" . $planillaref->id);
                            $db->execute("DELETE FROM planillasref WHERE id=" . $planillaref->id);
                            $m = 'Error 1: No se guard&oacute; el registro de la Planilla de Refrigerio debido a errores en datos enviados.';
                            if (!$okForm) $m .= ' Sin embargo, no se registraron algunos formularios 110 por retenci&oacute;n. Comuniquese con el Administrador';
                            $msj = array('result' => 0, 'msj' => $m);

                        }
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error 2: No se guard&oacute; el registro debido a datos erroneos enviados.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Planilla de Refrigerio.');
                }

            } else {
                $msj = array('result' => 0, 'msj' => 'No se consider&oacute; ning&uacute;n registro para la Planilla de Refrigerio.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para mostrar la planilla generada (efectiva).
     */
    public function displayplanefectivaAction()
    {
        $this->view->disable();
        $obj = new Frelaboralesplanillaref();
        $planillassal = Array();
        $idPlanillaRef = 0;
        if (isset($_GET["id"]) && $_GET["id"] > 0)
            $idPlanillaRef = $_GET["id"];
        if ($idPlanillaRef > 0) {
            $resul = $obj->desplegarPlanillaRefEfectiva($idPlanillaRef);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $planillassal[] = array(
                        'id_relaboral' => $v->id_relaboral,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'cargo' => $v->cargo,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'area' => $v->area,
                        'ubicacion' => $v->ubicacion,
                        'fin_partida' => $v->fin_partida,
                        'id_procesocontratacion' => $v->id_procesocontratacion,
                        'procesocontratacion_codigo' => $v->procesocontratacion_codigo,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'sueldo' => $v->sueldo,
                        'dias_efectivos' => $v->dias_efectivos,
                        'monto_diario' => $v->monto_diario,
                        'faltas' => $v->faltas,
                        'lsgh' => $v->lsgh,
                        'vacacion' => $v->vacacion,
                        'otros' => $v->otros,
                        'id_form110impref' => $v->id_form110impref,
                        'importe' => $v->importe,
                        'rc_iva' => $v->rc_iva,
                        'retencion' => $v->retencion,
                        'form110impref_observacion' => $v->form110impref_observacion,
                        'fecha_form' => $v->fecha_form,
                        'total_ganado' => $v->total_ganado,
                        'total_liquido' => $v->total_liquido,
                        'cargo' => $v->cargo,
                        'total_descuentos' => $v->total_descuentos,
                        'total_ganado' => $v->total_ganado,
                        'total_liquido' => $v->total_liquido,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'available' => false
                    );
                }
            }
        }
        echo json_encode($planillassal);
    }

    /**
     * Función para la edición del registro de planilla y/o regisotros de pagos relacionados.
     * opcion: 0 => Modificar sólo el registro de la planilla
     *         1 => Modificar sólo los regisotrs de los pagos
     *         2 => Modificar los registros de las planillas y los pagos
     */
    public function editAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $idPlanillaRef = 0;
        $observacion = "";
        $opcion = 0;
        $ok = true;
        if (isset($_POST["id"]) && $_POST["id"] > 0)
            $idPlanillaRef = $_POST["id"];
        if (isset($_POST["opcion"]) && $_POST["opcion"] > 0)
            $opcion = $_POST["opcion"];
        if (isset($_POST["observacion"]) && $_POST["observacion"] != '')
            $observacion = $_POST["observacion"];
        if ($idPlanillaRef > 0) {
            /**
             * Se modifica el registro de planilla salarial
             */
            if ($opcion == 0 || $opcion == 2) {
                try {
                    $planillasref = Planillasref::findFirstById($idPlanillaRef);
                    if ($planillasref->id > 0) {
                        $planillasref->observacion = $observacion;
                        $planillasref->user_mod_id = $user_mod_id;
                        $planillasref->fecha_reg = $hoy;
                        $ok = $planillasref->save();
                        if ($ok) $ms = 'Modificaci&oacute;n exitosa del registro de planilla de refrigerio.';
                        else $ms = 'No se pudo modificar el registro de planilla de refrigerio.';
                    } else {
                        $ok = false;
                        $ms = 'No se pudo modificar el registro de planilla de refrigerio debido a que no se encontr&oacute;.';
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $ms = 'Error cr&iacute;tico: No se modific&oacute; el registro de planilla de refrigerio.';
                }
            }
            if ($ok) {
                /**
                 * Se modifican los registros de pagos salariales
                 */
                if ($opcion == 1 || $opcion == 2) {
                    if ($ms != '') $ms .= ' ';
                    try {
                        $db = $this->getDI()->get('db');
                        $ok = $db->execute("UPDATE TABLE pagossal SET observacion='$observacion',user_mod_id=" . $user_mod_id . ",fecha_mod=CURRENT_DATE WHERE planillasal_id=" . $idPlanillaRef);
                        if ($ok) $ms .= 'Modificaci&oacute; exitosa de los Pagos Salariales relacionados a la planilla salarial.';
                        else $ms .= 'No se pudo modificar los registros de Pagos Salariales relacionados a la planilla salarial.';
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $ms .= 'Error cr&iacute;tico: No se modificaron los registros de pagos salariales.';
                    }
                }
            }
            if ($ok) $msj = array('result' => 1, 'msj' => $ms);
            else  $msj = array('result' => 0, 'msj' => $ms);

        }
        echo json_encode($msj);
    }

    /**
     * Función para aprobar un registro de Planilla de Refrigerio.
     */
    public function approveAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_apr_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $idPlanillaRef = 0;
        $ok = true;
        if (isset($_POST["id"]) && $_POST["id"] > 0)
            $idPlanillaRef = $_POST["id"];
        if ($idPlanillaRef > 0) {
            try {
                $planillaref = Planillasref::findFirstById($idPlanillaRef);
                if ($planillaref->id > 0) {
                    $planillaref->estado = 3;
                    $planillaref->user_apr_id = $user_apr_id;
                    $planillaref->fecha_apr = $hoy;
                    $planillaref->save();
                    $msj = array('result' => 1, 'msj' => 'Aprobaci&oacute;n exitosa del registro.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se pudo aprobar el registro de Planilla de Refrigerio.');
                }
            } catch (\Exception $e) {
                echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();
                $msj = array('result' => 0, 'msj' => 'Error cr&iacute;tico: No se pudo aprobar el registro de Planilla de Refrigerio debido a un error en los datos enviados.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para dar de baja una Planilla Salarial
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
                $objPlanillaSal = Planillassal::findFirstById($_POST["id"]);
                $objPlanillaSal->estado = 0;
                $objPlanillaSal->baja_logica = 0;//Debido a que no es necesario que el registro continúe apareciendo en estado pasivo.
                $objPlanillaSal->user_mod_id = $user_mod_id;
                $objPlanillaSal->fecha_mod = $hoy;
                if ($objPlanillaSal->save()) {
                    $db = $this->getDI()->get('db');
                    $ok = $db->execute("UPDATE pagossal SET estado=0,baja_logica=0,user_mod_id=" . $user_mod_id . ",fecha_mod=CURRENT_DATE,user_anu_id=" . $user_mod_id . ",fecha_anu=CURRENT_DATE WHERE planillasal_id=" . $_POST["id"]);
                    if ($ok) $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                    else $msj = array('result' => 0, 'msj' => 'No se pudo realizar la baja de los registros de baja para esta planilla.');
                } else {
                    foreach ($objPlanillaSal->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de la Planilla Salarial.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de la Planilla Salarial.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la Planilla Salarial.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la exportación de la Planilla de Refrigerio generada, verificada o aprobada a formato Excel.
     * @param $idPlanillaRef
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportviewexcelAction($idPlanillaRef, $n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4', 'totales' => false),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'procesocontratacion_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'ci' => array('title' => 'CI', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar', 'totales' => false),
            'nivel_salarial' => array('title' => 'Nivel Salarial', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'dias_efectivos' => array('title' => 'Dias Efec.', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'monto_diario' => array('title' => 'Monto Diario', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'lsgh' => array('title' => 'LSGH', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'faltas' => array('title' => 'Faltas', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'vacacion' => array('title' => 'Vacacion', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'otros' => array('title' => 'Otros', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'importe' => array('title' => 'Importe', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'rc_iva' => array('title' => 'RC-IVA', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'retencion' => array('title' => 'Retencion', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'fecha_form' => array('title' => 'Fecha F. 110', 'width' => 10, 'align' => 'R', 'type' => 'date', 'totales' => false),
            'form110impref_observacion' => array('title' => 'F. 110 Obs.', 'width' => 10, 'align' => 'R', 'type' => 'string', 'totales' => false),
            'total_ganado' => array('title' => 'T. Ganado', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'total_liquido' => array('title' => 'T. Liquido', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'observacion' => array('title' => 'Observacion', 'width' => 10, 'align' => 'R', 'type' => 'string', 'totales' => false)
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $objP = new Fplanillasref();
            $planillasal = $objP->getOne($idPlanillaRef);
            $cabecera = 'PLANILLA DE REFRIGERIO ' . $planillasal[0]->gestion . ' ' . $planillasal[0]->mes_nombre . ' "' . $planillasal[0]->fin_partida . '" ' . $planillasal[0]->tipo_planilla . ' ';
            if ($planillasal[0]->numero > 0) {
                $cabecera .= '(' . $planillasal[0]->numero . ') ';
            }
            $excel->title_rpt = utf8_decode($cabecera);
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $excel->title_sheet_rpt = "Planilla de Refrigerio";
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTotalSelecteds = $excel->DefineSelectedTotalColsWithExclude($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^idPlanillaSal^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>" . $idPlanillaRef;
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaboralesplanillaref();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",nombres,gestion,mes";
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $arrTotales = array();
            $totalHaberes = $totalDiasEfectivos = $totalMontoDiario = $totalLsgh = $totalFaltas = $totalVacaciones = $totalOtros = $totalImporte = $totalImpuesto = $totalRetencion = $totalGanado = $totalLiquido = 0;
            $resul = $obj->desplegarPlanillaRefEfectiva($idPlanillaRef, $where, $groups);
            $relaboralesplanillasref = array();
            foreach ($resul as $v) {
                $relaboralesplanillasref[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'cargo' => $v->cargo,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'area' => $v->area,
                    'ubicacion' => $v->ubicacion,
                    'fin_partida' => $v->fin_partida,
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'procesocontratacion_codigo' => $v->procesocontratacion_codigo,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'sueldo' => $v->sueldo,
                    'dias_efectivos' => $v->dias_efectivos,
                    'monto_diario' => $v->monto_diario,
                    'faltas' => $v->faltas,
                    'lsgh' => $v->lsgh,
                    'vacacion' => $v->vacacion,
                    'otros' => $v->otros,
                    'id_form110impref' => $v->id_form110impref,
                    'importe' => $v->importe,
                    'rc_iva' => $v->rc_iva,
                    'retencion' => $v->retencion,
                    'form110impref_observacion' => $v->form110impref_observacion,
                    'fecha_form' => $v->fecha_form,
                    'total_ganado' => $v->total_ganado,
                    'total_liquido' => $v->total_liquido,
                    'cargo' => $v->cargo,
                    'total_descuentos' => $v->total_descuentos,
                    'total_ganado' => $v->total_ganado,
                    'total_liquido' => $v->total_liquido,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion
                );
                $haber = $diasEfectivos = $montoDiario = $lsgh = $faltas = $vacacion = $otros = $importe = $impuesto = $retencion = $ganado = $liquido = 0;
                if ($v->sueldo != '') {
                    $haber = $v->sueldo;
                }
                if ($v->dias_efectivos != '') {
                    $diasEfectivos = $v->dias_efectivos;
                }
                if ($v->monto_diario != '') {
                    $montoDiario = $v->monto_diario;
                }
                if ($v->lsgh != '') {
                    $lsgh = $v->lsgh;
                }
                if ($v->faltas != '') {
                    $faltas = $v->faltas;
                }
                if ($v->vacacion != '') {
                    $vacacion = $v->vacacion;
                }
                if ($v->otros != '') {
                    $otros = $v->otros;
                }
                if ($v->importe != '') {
                    $importe = $v->importe;
                }
                if ($v->rc_iva != '') {
                    $impuesto = $v->rc_iva;
                }
                if ($v->retencion != '') {
                    $retencion = $v->retencion;
                }
                if ($v->total_ganado != '') {
                    $ganado = $v->total_ganado;
                }
                if ($v->total_liquido != '') {
                    $liquido = $v->total_liquido;
                }
                $totalHaberes += $haber;
                $totalDiasEfectivos += $diasEfectivos;
                $totalMontoDiario += $montoDiario;
                $totalLsgh += $lsgh;
                $totalFaltas += $faltas;
                $totalVacaciones += $vacacion;
                $totalOtros += $otros;
                $totalImporte += $importe;
                $totalImpuesto += $impuesto;
                $totalRetencion += $retencion;
                $totalGanado += $ganado;
                $totalLiquido += $liquido;
            }
            $arrTodosTotales = array("sueldo" => $totalHaberes,
                "dias_efectivos" => $totalDiasEfectivos,
                "monto_diario" => $totalMontoDiario,
                "lsgh" => $totalLsgh,
                "faltas" => $totalFaltas,
                "vacacion" => $totalVacaciones,
                "otros" => $totalOtros,
                "importe" => $totalImporte,
                "rc_iva" => $totalImpuesto,
                "retencion" => $totalRetencion,
                "total_ganado" => $totalGanado,
                "total_liquido" => $totalLiquido);
            $arrTotales = $excel->generaFilaTotales($colSelecteds, $colTotalSelecteds, $arrTodosTotales);
            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($relaboralesplanillasref) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($relaboralesplanillasref);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($relaboralesplanillasref as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $relaboralesplanillasref[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA DATA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA DATA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                if (count($arrTotales) > 0) {
                    $celdacolores = array();
                    foreach ($arrTotales as $clave => $valor) {
                        $celdacolores[$clave] = "87CEEB";
                        $alignSelectedsTotals[] = "R";
                    }
                    $excel->Row($arrTotales, $alignSelectedsTotals, $formatTypes, $fila, $celdacolores, false);
                    $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                    $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                }
            }
            $excel->ShowLeftFooter = true;
            if ($excel->debug == 0) {
                $excel->display("AppData/planillaDeRefrigerio.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la generación del array con los anchos de columna definido, en consideración a las columnas mostradas.
     * @param $generalWiths Array de los anchos y alineaciones de columnas disponibles.
     * @param $columns Array de las columnas con las propiedades de oculto (hidden:1) y visible (hidden:null).
     * @return array Array con el listado de anchos por columna a desplegarse.
     */
    function DefineWidths($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = 8;
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $widthAlignAll[$key]['width'];
                }
            }
        }
        return $arrRes;
    }

    /*
     * Función para obtener la cantidad de veces que se considera una misma columna en el filtrado.
     * @param $columna
     * @param $array
     * @return int
     */
    function obtieneCantidadVecesConsideracionPorColumnaEnFiltros($columna, $array)
    {
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $cont++;
                }
            }
        }
        return $cont;
    }

    /**
     * Función para la obtención de los valores considerados en el filtro enviado.
     * @param $columna Nombre de la columna
     * @param $array Array con los registro de busquedas.
     * @return array Array con los valores considerados en el filtrado enviado.
     */
    function obtieneValoresConsideradosPorColumnaEnFiltros($columna, $array)
    {
        $arr_col = array();
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $arr_col[] = $val["valor"];
                }
            }
        }
        return $arr_col;
    }

    /**
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportviewpdfAction($idPlanillaRef, $n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $this->view->disable();
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4', 'totales' => false),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'procesocontratacion_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false),
            'ci' => array('title' => 'CI', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar', 'totales' => false),
            'nivel_salarial' => array('title' => 'Nivel Salarial', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'dias_efectivos' => array('title' => 'D. Efec.', 'width' => 15, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'monto_diario' => array('title' => 'M/D', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'lsgh' => array('title' => 'LSGH', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'faltas' => array('title' => 'Faltas', 'width' => 15, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'vacacion' => array('title' => 'Vac.', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'otros' => array('title' => 'Otros', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'importe' => array('title' => 'Importe', 'width' => 15, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'rc_iva' => array('title' => 'RC-IVA', 'width' => 15, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'retencion' => array('title' => 'Ret.', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'fecha_form' => array('title' => 'Fecha F. 110', 'width' => 10, 'align' => 'R', 'type' => 'date', 'totales' => true),
            'form110impref_observacion' => array('title' => 'F. 110 Obs.', 'width' => 10, 'align' => 'R', 'type' => 'string', 'totales' => false),
            'total_ganado' => array('title' => 'Ganado', 'width' => 15, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'total_liquido' => array('title' => 'Liquido', 'width' => 15, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'observacion' => array('title' => 'Observacion', 'width' => 10, 'align' => 'R', 'type' => 'string', 'totales' => false)
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $objP = new Fplanillasref();
            $planillaref = $objP->getOne($idPlanillaRef);
            $cabecera = 'PLANILLA DE REFRIGERIO ' . $planillaref[0]->gestion . ' ' . $planillaref[0]->mes_nombre . ' "' . $planillaref[0]->fin_partida . '" ' . $planillaref[0]->tipo_planilla . ' ';
            if ($planillaref[0]->numero > 0) {
                $cabecera .= '(' . $planillaref[0]->numero . ') ';
            }
            $pdf->title_rpt = utf8_decode($cabecera);
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTotalSelecteds = $pdf->DefineSelectedTotalColsWithExclude($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Frelaboralesplanillaref();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",nombres,gestion,mes";
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $arrTotales = array();
            $totalHaberes = $totalDiasEfectivos = $totalMontoDiario = $totalLsgh = $totalFaltas = $totalVacaciones = $totalOtros = $totalImporte = $totalImpuesto = $totalRetencion = $totalGanado = $totalLiquido = 0;
            $resul = $obj->desplegarPlanillaRefEfectiva($idPlanillaRef, $where, $groups);
            $relaboralesPlanillas = array();
            foreach ($resul as $v) {
                $relaboralesPlanillas[] = array(
                    'id_relaboral' => $v->id_relaboral,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'cargo' => $v->cargo,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'area' => $v->area,
                    'ubicacion' => $v->ubicacion,
                    'fin_partida' => $v->fin_partida,
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'procesocontratacion_codigo' => $v->procesocontratacion_codigo,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'sueldo' => $v->sueldo,
                    'dias_efectivos' => $v->dias_efectivos,
                    'monto_diario' => $v->monto_diario,
                    'faltas' => $v->faltas,
                    'lsgh' => $v->lsgh,
                    'vacacion' => $v->vacacion,
                    'otros' => $v->otros,
                    'id_form110impref' => $v->id_form110impref,
                    'importe' => $v->importe,
                    'rc_iva' => $v->rc_iva,
                    'retencion' => $v->retencion,
                    'form110impref_observacion' => $v->form110impref_observacion,
                    'fecha_form' => $v->fecha_form,
                    'total_ganado' => $v->total_ganado,
                    'total_liquido' => $v->total_liquido,
                    'cargo' => $v->cargo,
                    'total_descuentos' => $v->total_descuentos,
                    'total_ganado' => $v->total_ganado,
                    'total_liquido' => $v->total_liquido,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion
                );
                $haber = $diasEfectivos = $montoDiario = $lsgh = $faltas = $vacacion = $otros = $importe = $impuesto = $retencion = $ganado = $liquido = 0;
                if ($v->sueldo != '') {
                    $haber = $v->sueldo;
                }
                if ($v->dias_efectivos != '') {
                    $diasEfectivos = $v->dias_efectivos;
                }
                if ($v->monto_diario != '') {
                    $montoDiario = $v->monto_diario;
                }
                if ($v->lsgh != '') {
                    $lsgh = $v->lsgh;
                }
                if ($v->faltas != '') {
                    $faltas = $v->faltas;
                }
                if ($v->vacacion != '') {
                    $vacacion = $v->vacacion;
                }
                if ($v->otros != '') {
                    $otros = $v->otros;
                }
                if ($v->importe != '') {
                    $importe = $v->importe;
                }
                if ($v->rc_iva != '') {
                    $impuesto = $v->rc_iva;
                }
                if ($v->retencion != '') {
                    $retencion = $v->retencion;
                }
                if ($v->total_ganado != '') {
                    $ganado = $v->total_ganado;
                }
                if ($v->total_liquido != '') {
                    $liquido = $v->total_liquido;
                }
                $totalHaberes += $haber;
                $totalDiasEfectivos += $diasEfectivos;
                $totalMontoDiario += $montoDiario;
                $totalLsgh += $lsgh;
                $totalFaltas += $faltas;
                $totalVacaciones += $vacacion;
                $totalOtros += $otros;
                $totalImporte += $importe;
                $totalImpuesto += $impuesto;
                $totalRetencion += $retencion;
                $totalGanado += $ganado;
                $totalLiquido += $liquido;
            }
            $arrTodosTotales = array("sueldo" => $totalHaberes,
                "dias_efectivos" => $totalDiasEfectivos,
                "monto_diario" => $totalMontoDiario,
                "lsgh" => $totalLsgh,
                "faltas" => $totalFaltas,
                "vacacion" => $totalVacaciones,
                "otros" => $totalOtros,
                "importe" => $totalImporte,
                "rc_iva" => $totalImpuesto,
                "retencion" => $totalRetencion,
                "total_ganado" => $totalGanado,
                "total_liquido" => $totalLiquido);
            $arrTotales = $pdf->generaFilaTotales($colSelecteds, $colTotalSelecteds, $arrTodosTotales);
            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($relaboralesPlanillas) > 0) {
                foreach ($relaboralesPlanillas as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $relaboralesPlanillas[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
                if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                /**
                 * Añade totales
                 */
                $pdf->Row($arrTotales);
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('planillaDeRefrigerio.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }
} 