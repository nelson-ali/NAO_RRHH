<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  13-03-2015
*/

class HorariosymarcacionesController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Función para la carga de la página de gestión de relaciones laborales.
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
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);

        $this->assets->addCss('/assets/css/clockpicker.css?v=' . $version);
        $this->assets->addJs('/js/clockpicker/clockpicker.js?v=' . $version);

        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.tab.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.index.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.list.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.approve.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.calculations.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.turns.excepts.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.down.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.move.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.view.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.export.marc.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.export.calc.js?v=' . $version);
        $this->assets->addJs('/js/horariosymarcaciones/oasis.horariosymarcaciones.view.splitter.js?v=' . $version);

        $this->assets->addJs('/js/tagsimput/bootstrap-tagsinput.js?v=' . $version);
        $this->assets->addCss('/js/tagsimput/bootstrap-tagsinput.css?v=' . $version);

        $this->defineViewCols();
    }

    /**
     * Función para definir las columnas visibles y visibles del formulario principal.
     */
    private function defineViewCols()
    {
        /**
         * Los valores para los checks de vista de campos del codigo de forma separada.
         */
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $listaDeColumnas = array(
            "hdnNombres", "hdnCi", "hdnExpd", "hdnGenero", "hdnEdad", "hdnFechaNac", "hdnUbicacion", "hdnCondicion", "hdnEstadoDescripcion", "hdnActivo",
            "hdnGerencia", "hdnDepartamento", "hdnArea", "hdnProcesoContratacion", "hdnFuente", "hdnNivelSalarial",
            "hdnCargo", "hdnHaber", "hdnFechaIng", "hdnFechaIni", "hdnFechaIncor", "hdnFechaFin", "hdnFechaBaja", "hdnMotivoBaja", "hdnObservacion",
        );
        $columnasOcultas = array();
        for ($ii = 0; $ii < count($listaDeColumnas); $ii++) {
            $this->view->setVar($listaDeColumnas[$ii], 0);
            $columnasOcultas[$listaDeColumnas[$ii]] = 0;
        }
        $objGrillaDetallePae = Columnasvisibles::findFirst("divgrilla_id='divGridRelaborales' AND user_id=" . $idUsuario);
        if (is_object($objGrillaDetallePae)) {
            $jsonCol = json_decode($objGrillaDetallePae->indexes, true);
            if (count($jsonCol)) {
                foreach ($jsonCol as $clave => $valor) {
                    if (isset($listaDeColumnas[$valor])) {
                        $this->view->setVar($listaDeColumnas[$valor], 1);
                        $columnasOcultas[$listaDeColumnas[$valor]] = 1;
                    }
                }
            }
        }
        $this->view->setVar('columnasOcultas', $columnasOcultas);
    }

    /**
     * Función para la obtención del listado de registros de control de marcaciones.
     */
    public function listporrelaboralAction()
    {
        $this->view->disable();
        $horariosymarcaciones = Array();
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            $obj = new Fhorariosymarcaciones();
            $idRelaboral = $_GET["id"];
            $resul = $obj->getAllFromOneRelaboral($idRelaboral);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $horariosymarcaciones[] = array(
                        'nro_row' => 0,
                        'id' => $v->id_horarioymarcacion,
                        'relaboral_id' => $v->relaboral_id,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'turno' => $v->turno,
                        'grupo' => $v->grupo,
                        'clasemarcacion' => $v->clasemarcacion,
                        'clasemarcacion_descripcion' => $v->clasemarcacion_descripcion,
                        'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                        'modalidad_marcacion' => $v->modalidad_marcacion,
                        'd1' => $v->d1,
                        'calendariolaboral1_id' => $v->calendariolaboral1_id,
                        'd2' => $v->d2,
                        'calendariolaboral2_id' => $v->calendariolaboral2_id,
                        'd3' => $v->d3,
                        'calendariolaboral3_id' => $v->calendariolaboral3_id,
                        'd4' => $v->d4,
                        'calendariolaboral4_id' => $v->calendariolaboral4_id,
                        'd5' => $v->d5,
                        'calendariolaboral5_id' => $v->calendariolaboral5_id,
                        'd6' => $v->d6,
                        'calendariolaboral6_id' => $v->calendariolaboral6_id,
                        'd7' => $v->d7,
                        'calendariolaboral7_id' => $v->calendariolaboral7_id,
                        'd8' => $v->d8,
                        'calendariolaboral8_id' => $v->calendariolaboral8_id,
                        'd9' => $v->d9,
                        'calendariolaboral9_id' => $v->calendariolaboral9_id,
                        'd10' => $v->d10,
                        'calendariolaboral10_id' => $v->calendariolaboral10_id,
                        'd11' => $v->d11,
                        'calendariolaboral11_id' => $v->calendariolaboral11_id,
                        'd12' => $v->d12,
                        'calendariolaboral12_id' => $v->calendariolaboral12_id,
                        'd13' => $v->d13,
                        'calendariolaboral13_id' => $v->calendariolaboral13_id,
                        'd14' => $v->d14,
                        'calendariolaboral14_id' => $v->calendariolaboral14_id,
                        'd15' => $v->d15,
                        'calendariolaboral15_id' => $v->calendariolaboral15_id,
                        'd16' => $v->d16,
                        'calendariolaboral16_id' => $v->calendariolaboral16_id,
                        'd17' => $v->d17,
                        'calendariolaboral17_id' => $v->calendariolaboral17_id,
                        'd18' => $v->d18,
                        'calendariolaboral18_id' => $v->calendariolaboral18_id,
                        'd19' => $v->d19,
                        'calendariolaboral19_id' => $v->calendariolaboral19_id,
                        'd20' => $v->d20,
                        'calendariolaboral20_id' => $v->calendariolaboral20_id,
                        'd21' => $v->d21,
                        'calendariolaboral21_id' => $v->calendariolaboral21_id,
                        'd22' => $v->d22,
                        'calendariolaboral22_id' => $v->calendariolaboral22_id,
                        'd23' => $v->d23,
                        'calendariolaboral23_id' => $v->calendariolaboral23_id,
                        'd24' => $v->d24,
                        'calendariolaboral24_id' => $v->calendariolaboral24_id,
                        'd25' => $v->d25,
                        'calendariolaboral25_id' => $v->calendariolaboral25_id,
                        'd26' => $v->d26,
                        'calendariolaboral26_id' => $v->calendariolaboral26_id,
                        'd27' => $v->d27,
                        'calendariolaboral27_id' => $v->calendariolaboral27_id,
                        'd28' => $v->d28,
                        'calendariolaboral28_id' => $v->calendariolaboral28_id,
                        'd29' => $v->d29,
                        'calendariolaboral29_id' => $v->calendariolaboral29_id,
                        'd30' => $v->d30,
                        'calendariolaboral30_id' => $v->calendariolaboral30_id,
                        'd31' => $v->d31,
                        'calendariolaboral31_id' => $v->calendariolaboral31_id,
                        'ultimo_dia' => $v->ultimo_dia,
                        'atrasos' => $v->atrasos,
                        'faltas' => $v->faltas,
                        'abandono' => $v->abandono,
                        'omision' => $v->omision,
                        'lsgh' => $v->lsgh,
                        'compensacion' => $v->compensacion,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_apr_id' => $v->user_apr_id,
                        'fecha_apr' => $v->fecha_apr,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                    );
                    #region sector para adicionar una fila para Excepciones
                    if ($v->modalidadmarcacion_id == 6) {
                        $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = $d8 = $d9 = $d10 = $d11 = $d12 = $d13 = $d14 = $d15 = $d16 = $d17 = $d18 = $d19 = $d20 = $d21 = $d22 = $d23 = $d24 = $d25 = $d26 = $d27 = $d28 = $d29 = $d30 = $d30 = $d31 = "";
                        if ($v->calendariolaboral1_id > 0) {
                            $res1 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 1, $v->calendariolaboral1_id);
                            if (is_object($res1) && $res1->count() > 0) {
                                foreach ($res1 as $r1) {
                                    $d1 = $r1->f_excepciones_en_dia;
                                }
                            }
                            $fe1 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 1);
                            if (is_object($fe1) && $fe1->count() > 0) {
                                foreach ($fe1 as $f1) {
                                    $d1 .= $f1->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral2_id > 0) {
                            $res2 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 2, $v->calendariolaboral2_id);
                            if (is_object($res2) && $res2->count() > 0) {
                                foreach ($res2 as $r2) {
                                    $d2 = $r2->f_excepciones_en_dia;
                                }
                            }
                            $fe2 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 2);
                            if (is_object($fe2) && $fe2->count() > 0) {
                                foreach ($fe2 as $f2) {
                                    $d2 .= $f2->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral3_id > 0) {
                            $res3 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 3, $v->calendariolaboral3_id);
                            if (is_object($res3) && $res3->count() > 0) {
                                foreach ($res3 as $r3) {
                                    $d3 = $r3->f_excepciones_en_dia;
                                }
                            }
                            $fe3 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 3);
                            if (is_object($fe3) && $fe3->count() > 0) {
                                foreach ($fe3 as $f3) {
                                    $d3 .= $f3->f_feriados_en_dia;
                                }
                            }
                        }

                        if ($v->calendariolaboral4_id > 0) {
                            $res4 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 4, $v->calendariolaboral4_id);
                            if (is_object($res4) && $res4->count() > 0) {
                                foreach ($res4 as $r4) {
                                    $d4 = $r4->f_excepciones_en_dia;
                                }
                            }
                            $fe4 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 4);
                            if (is_object($fe4) && $fe4->count() > 0) {
                                foreach ($fe4 as $f4) {
                                    $d4 .= $f4->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral5_id > 0) {
                            $res5 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 5, $v->calendariolaboral5_id);
                            if (is_object($res5) && $res5->count() > 0) {
                                foreach ($res5 as $r5) {
                                    $d5 = $r5->f_excepciones_en_dia;
                                }
                            }
                            $fe5 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 5);
                            if (is_object($fe5) && $fe5->count() > 0) {
                                foreach ($fe5 as $f5) {
                                    $d5 .= $f5->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral6_id > 0) {
                            $res6 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 6, $v->calendariolaboral6_id);
                            if (is_object($res6) && $res6->count() > 0) {
                                foreach ($res6 as $r6) {
                                    $d6 = $r6->f_excepciones_en_dia;
                                }
                            }
                            $fe6 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 6);
                            if (is_object($fe6) && $fe6->count() > 0) {
                                foreach ($fe6 as $f6) {
                                    $d6 .= $f6->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral7_id > 0) {
                            $res7 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 7, $v->calendariolaboral7_id);
                            if (is_object($res7) && $res7->count() > 0) {
                                foreach ($res7 as $r7) {
                                    $d7 = $r7->f_excepciones_en_dia;
                                }
                            }
                            $fe7 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 7);
                            if (is_object($fe7) && $fe7->count() > 0) {
                                foreach ($fe7 as $f7) {
                                    $d7 .= $f7->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral8_id > 0) {
                            $res8 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 8, $v->calendariolaboral8_id);
                            if (is_object($res8) && $res8->count() > 0) {
                                foreach ($res8 as $r8) {
                                    $d8 = $r8->f_excepciones_en_dia;
                                }
                            }
                            $fe8 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 8);
                            if (is_object($fe8) && $fe8->count() > 0) {
                                foreach ($fe8 as $f8) {
                                    $d8 .= $f8->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral9_id > 0) {
                            $res9 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 9, $v->calendariolaboral9_id);
                            if (is_object($res9) && $res9->count() > 0) {
                                foreach ($res9 as $r9) {
                                    $d9 = $r9->f_excepciones_en_dia;
                                }
                            }
                            $fe9 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 9);
                            if (is_object($fe9) && $fe9->count() > 0) {
                                foreach ($fe9 as $f9) {
                                    $d9 .= $f9->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral10_id > 0) {
                            $res10 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 10, $v->calendariolaboral10_id);
                            if (is_object($res10) && $res10->count() > 0) {
                                foreach ($res10 as $r10) {
                                    $d10 = $r10->f_excepciones_en_dia;
                                }
                            }
                            $fe10 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 10);
                            if (is_object($fe10) && $fe10->count() > 0) {
                                foreach ($fe10 as $f10) {
                                    $d10 .= $f10->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral11_id > 0) {
                            $res11 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 11, $v->calendariolaboral11_id);
                            if (is_object($res11) && $res11->count() > 0) {
                                foreach ($res11 as $r11) {
                                    $d11 = $r11->f_excepciones_en_dia;
                                }
                            }
                            $fe11 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 11);
                            if (is_object($fe11) && $fe11->count() > 0) {
                                foreach ($fe11 as $f11) {
                                    $d11 .= $f11->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral12_id > 0) {
                            $res12 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 12, $v->calendariolaboral12_id);
                            if (is_object($res12) && $res12->count() > 0) {
                                foreach ($res12 as $r12) {
                                    $d12 = $r12->f_excepciones_en_dia;
                                }
                            }
                            $fe12 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 12);
                            if (is_object($fe12) && $fe12->count() > 0) {
                                foreach ($fe12 as $f12) {
                                    $d12 .= $f12->f_feriados_en_dia;
                                }
                            }
                        }

                        if ($v->calendariolaboral13_id > 0) {
                            $res13 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 13, $v->calendariolaboral13_id);
                            if (is_object($res13) && $res13->count() > 0) {
                                foreach ($res13 as $r13) {
                                    $d13 = $r13->f_excepciones_en_dia;
                                }
                            }
                            $fe13 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 13);
                            if (is_object($fe13) && $fe13->count() > 0) {
                                foreach ($fe13 as $f13) {
                                    $d13 .= $f13->f_feriados_en_dia;
                                }
                            }
                        }

                        if ($v->calendariolaboral14_id > 0) {
                            $res14 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 14, $v->calendariolaboral14_id);
                            if (is_object($res14) && $res14->count() > 0) {
                                foreach ($res14 as $r14) {
                                    $d14 = $r14->f_excepciones_en_dia;
                                }
                            }
                            $fe14 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 14);
                            if (is_object($fe14) && $fe14->count() > 0) {
                                foreach ($fe14 as $f14) {
                                    $d14 .= $f14->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral15_id > 0) {
                            $res15 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 15, $v->calendariolaboral15_id);
                            if (is_object($res15) && $res15->count() > 0) {
                                foreach ($res15 as $r15) {
                                    $d15 = $r15->f_excepciones_en_dia;
                                }
                            }
                            $fe15 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 15);
                            if (is_object($fe15) && $fe15->count() > 0) {
                                foreach ($fe15 as $f15) {
                                    $d15 .= $f15->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral16_id > 0) {
                            $res16 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 16, $v->calendariolaboral16_id);
                            if (is_object($res16) && $res16->count() > 0) {
                                foreach ($res16 as $r16) {
                                    $d16 = $r16->f_excepciones_en_dia;
                                }
                            }
                            $fe16 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 16);
                            if (is_object($fe16) && $fe16->count() > 0) {
                                foreach ($fe16 as $f16) {
                                    $d16 .= $f16->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral17_id > 0) {
                            $res17 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 17, $v->calendariolaboral17_id);
                            if (is_object($res17) && $res17->count() > 0) {
                                foreach ($res17 as $r17) {
                                    $d17 = $r17->f_excepciones_en_dia;
                                }
                            }
                            $fe17 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 17);
                            if (is_object($fe17) && $fe17->count() > 0) {
                                foreach ($fe17 as $f17) {
                                    $d17 .= $f17->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral18_id > 0) {
                            $res18 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 18, $v->calendariolaboral18_id);
                            if (is_object($res18) && $res18->count() > 0) {
                                foreach ($res18 as $r) {
                                    $d18 = $r->f_excepciones_en_dia;
                                }
                            }
                            $fe18 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 18);
                            if (is_object($fe18) && $fe18->count() > 0) {
                                foreach ($fe18 as $f18) {
                                    $d18 .= $f18->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral19_id > 0) {
                            $res19 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 19, $v->calendariolaboral19_id);
                            if (is_object($res19) && $res19->count() > 0) {
                                foreach ($res19 as $r) {
                                    $d19 = $r->f_excepciones_en_dia;
                                }
                            }
                            $fe19 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 19);
                            if (is_object($fe19) && $fe19->count() > 0) {
                                foreach ($fe19 as $f19) {
                                    $d19 .= $f19->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral20_id > 0) {
                            $res20 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 20, $v->calendariolaboral20_id);
                            if (is_object($res20) && $res20->count() > 0) {
                                foreach ($res20 as $r) {
                                    $d20 = $r->f_excepciones_en_dia;
                                }
                            }
                            $fe20 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 20);
                            if (is_object($fe20) && $fe20->count() > 0) {
                                foreach ($fe20 as $f20) {
                                    $d20 .= $f20->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral21_id > 0) {
                            $res21 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 21, $v->calendariolaboral21_id);
                            if (is_object($res21) && $res21->count() > 0) {
                                foreach ($res21 as $r21) {
                                    $d21 = $r21->f_excepciones_en_dia;
                                }
                            }
                            $fe21 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 21);
                            if (is_object($fe21) && $fe21->count() > 0) {
                                foreach ($fe21 as $f21) {
                                    $d21 .= $f21->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral22_id > 0) {
                            $res22 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 22, $v->calendariolaboral22_id);
                            if (is_object($res22) && $res22->count() > 0) {
                                foreach ($res22 as $r22) {
                                    $d22 = $r22->f_excepciones_en_dia;
                                }
                            }
                            $fe22 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 22);
                            if (is_object($fe22) && $fe22->count() > 0) {
                                foreach ($fe22 as $f22) {
                                    $d22 .= $f22->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral23_id > 0) {
                            $res23 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 23, $v->calendariolaboral23_id);
                            if (is_object($res23) && $res23->count() > 0) {
                                foreach ($res23 as $r23) {
                                    $d23 = $r23->f_excepciones_en_dia;
                                }
                            }
                            $fe23 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 23);
                            if (is_object($fe23) && $fe23->count() > 0) {
                                foreach ($fe23 as $f23) {
                                    $d23 .= $f23->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral24_id > 0) {
                            $res24 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 24, $v->calendariolaboral24_id);
                            if (is_object($res24) && $res24->count() > 0) {
                                foreach ($res24 as $r24) {
                                    $d24 = $r24->f_excepciones_en_dia;
                                }
                            }
                            $fe24 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 24);
                            if (is_object($fe24) && $fe24->count() > 0) {
                                foreach ($fe24 as $f24) {
                                    $d24 .= $f24->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral25_id > 0) {
                            $res25 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 25, $v->calendariolaboral25_id);
                            if (is_object($res25) && $res25->count() > 0) {
                                foreach ($res25 as $r25) {
                                    $d25 = $r25->f_excepciones_en_dia;
                                }
                            }
                            $fe25 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 25);
                            if (is_object($fe25) && $fe25->count() > 0) {
                                foreach ($fe25 as $f25) {
                                    $d25 .= $f25->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral26_id > 0) {
                            $res26 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 26, $v->calendariolaboral26_id);
                            if (is_object($res26) && $res26->count() > 0) {
                                foreach ($res26 as $r26) {
                                    $d26 = $r26->f_excepciones_en_dia;
                                }
                            }
                            $fe26 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 26);
                            if (is_object($fe26) && $fe26->count() > 0) {
                                foreach ($fe26 as $f26) {
                                    $d26 .= $f26->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral27_id > 0) {
                            $res27 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 27, $v->calendariolaboral27_id);
                            if (is_object($res27) && $res27->count() > 0) {
                                foreach ($res27 as $r27) {
                                    $d27 = $r27->f_excepciones_en_dia;
                                }
                            }
                            $fe27 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 27);
                            if (is_object($fe27) && $fe27->count() > 0) {
                                foreach ($fe27 as $f27) {
                                    $d27 .= $f27->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral28_id > 0) {
                            $res28 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 28, $v->calendariolaboral28_id);
                            if (is_object($res28) && $res28->count() > 0) {
                                foreach ($res28 as $r28) {
                                    $d28 = $r28->f_excepciones_en_dia;
                                }
                            }
                            $fe28 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 28);
                            if (is_object($fe28) && $fe28->count() > 0) {
                                foreach ($fe28 as $f28) {
                                    $d28 .= $f28->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral29_id > 0) {
                            $res29 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 29, $v->calendariolaboral29_id);
                            if (is_object($res29) && $res29->count() > 0) {
                                foreach ($res29 as $r29) {
                                    $d29 = $r29->f_excepciones_en_dia;
                                }
                            }
                            $fe29 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 29);
                            if (is_object($fe29) && $fe29->count() > 0) {
                                foreach ($fe29 as $f29) {
                                    $d29 .= $f29->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral30_id > 0) {
                            $res30 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 30, $v->calendariolaboral30_id);
                            if (is_object($res30) && $res30->count() > 0) {
                                foreach ($res30 as $r30) {
                                    $d30 = $r30->f_excepciones_en_dia;
                                }
                            }
                            $fe30 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 30);
                            if (is_object($fe30) && $fe30->count() > 0) {
                                foreach ($fe30 as $f30) {
                                    $d30 .= $f30->f_feriados_en_dia;
                                }
                            }
                        }
                        if ($v->calendariolaboral31_id > 0) {
                            $res31 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 31, $v->calendariolaboral31_id);
                            if (is_object($res31) && $res31->count() > 0) {
                                foreach ($res31 as $r31) {
                                    $d31 = $r31->f_excepciones_en_dia;
                                }
                            }
                            $fe31 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 31);
                            if (is_object($fe31) && $fe31->count() > 0) {
                                foreach ($fe31 as $f31) {
                                    $d31 .= $f31->f_feriados_en_dia;
                                }
                            }
                        }
                        $horariosymarcaciones[] = array(
                            'nro_row' => 0,
                            'id' => $v->id_horarioymarcacion,
                            'relaboral_id' => $v->relaboral_id,
                            'gestion' => $v->gestion,
                            'mes' => $v->mes,
                            'mes_nombre' => $v->mes_nombre,
                            'turno' => $v->turno,
                            'grupo' => $v->grupo,
                            'clasemarcacion' => "e",
                            'clasemarcacion_descripcion' => "EXCEPCIONES / FERIADOS",
                            'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                            'modalidad_marcacion' => "EXCEPCIONES / FERIADOS",
                            'd1' => $d1,
                            'calendariolaboral1_id' => $v->calendariolaboral1_id,
                            'd2' => $d2,
                            'calendariolaboral2_id' => $v->calendariolaboral2_id,
                            'd3' => $d3,
                            'calendariolaboral3_id' => $v->calendariolaboral3_id,
                            'd4' => $d4,
                            'calendariolaboral4_id' => $v->calendariolaboral4_id,
                            'd5' => $d5,
                            'calendariolaboral5_id' => $v->calendariolaboral5_id,
                            'd6' => $d6,
                            'calendariolaboral6_id' => $v->calendariolaboral6_id,
                            'd7' => $d7,
                            'calendariolaboral7_id' => $v->calendariolaboral7_id,
                            'd8' => $d8,
                            'calendariolaboral8_id' => $v->calendariolaboral8_id,
                            'd9' => $d9,
                            'calendariolaboral9_id' => $v->calendariolaboral9_id,
                            'd10' => $d10,
                            'calendariolaboral10_id' => $v->calendariolaboral10_id,
                            'd11' => $d11,
                            'calendariolaboral11_id' => $v->calendariolaboral11_id,
                            'd12' => $d12,
                            'calendariolaboral12_id' => $v->calendariolaboral12_id,
                            'd13' => $d13,
                            'calendariolaboral13_id' => $v->calendariolaboral13_id,
                            'd14' => $d14,
                            'calendariolaboral14_id' => $v->calendariolaboral14_id,
                            'd15' => $d15,
                            'calendariolaboral15_id' => $v->calendariolaboral15_id,
                            'd16' => $d16,
                            'calendariolaboral16_id' => $v->calendariolaboral16_id,
                            'd17' => $d17,
                            'calendariolaboral17_id' => $v->calendariolaboral17_id,
                            'd18' => $d18,
                            'calendariolaboral18_id' => $v->calendariolaboral18_id,
                            'd19' => $d19,
                            'calendariolaboral19_id' => $v->calendariolaboral19_id,
                            'd20' => $d20,
                            'calendariolaboral20_id' => $v->calendariolaboral20_id,
                            'd21' => $d21,
                            'calendariolaboral21_id' => $v->calendariolaboral21_id,
                            'd22' => $d22,
                            'calendariolaboral22_id' => $v->calendariolaboral22_id,
                            'd23' => $d23,
                            'calendariolaboral23_id' => $v->calendariolaboral23_id,
                            'd24' => $d24,
                            'calendariolaboral24_id' => $v->calendariolaboral24_id,
                            'd25' => $d25,
                            'calendariolaboral25_id' => $v->calendariolaboral25_id,
                            'd26' => $d26,
                            'calendariolaboral26_id' => $v->calendariolaboral26_id,
                            'd27' => $d27,
                            'calendariolaboral27_id' => $v->calendariolaboral27_id,
                            'd28' => $d28,
                            'calendariolaboral28_id' => $v->calendariolaboral28_id,
                            'd29' => $d29,
                            'calendariolaboral29_id' => $v->calendariolaboral29_id,
                            'd30' => $d30,
                            'calendariolaboral30_id' => $v->calendariolaboral30_id,
                            'd31' => $d31,
                            'calendariolaboral31_id' => $v->calendariolaboral31_id,
                            'ultimo_dia' => $v->ultimo_dia,
                            'atrasos' => null,
                            'faltas' => null,
                            'abandono' => null,
                            'omision' => null,
                            'compensacion' => null,
                            'observacion' => $v->observacion,
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'baja_logica' => $v->baja_logica,
                            'agrupador' => $v->agrupador,
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_apr_id' => $v->user_apr_id,
                            'fecha_apr' => $v->fecha_apr,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod,
                        );
                    }
                    #endregion sector para adicionar una fila para Excepciones
                }
            }
        }
        echo json_encode($horariosymarcaciones);
    }

    /**
     * Función para obtener el listado de marcaciones con los cálculos correspondiente considerando el rango de dos meses
     */
    public function listallbyrangeAction()
    {
        $this->view->disable();
        $horariosymarcaciones = Array();
        if (isset($_GET["fecha_ini"]) && isset($_GET["fecha_fin"]) && isset($_GET["ci"]) && $_GET["ci"] != '') {
            $where = "";
            $idRelaboral = 0;
            $carnetAux = "";
            if (isset($_GET["id_relaboral"]) && $_GET["id_relaboral"] > 0)
                $idRelaboral = $_GET["id_relaboral"];
            if (isset($_GET["ci"]) && $_GET["ci"] > 0 && $_GET["ci"] != 'undefined')
                $carnetAux = $_GET["ci"];
            $fechaIni = $_GET["fecha_ini"];
            $fechaFin = $_GET["fecha_fin"];

            $obj = new Frelaboraleshorariosymarcaciones();
            $idRelaboral = 0;
            /*if($ci!=''&&$ci!=0){
                $where = " WHERE ci='".$ci."'";
            }*/
            $jsonIdRelaborales = "";
            if ($carnetAux != '') {
                /*if($where!='')$where.=" AND ci='".$CarnetAux."'";
                else $where.=" WHERE ci='".$CarnetAux."'";*/

                $arrCis = explode(",", $carnetAux);
                $jsonCis = "";
                if (count($arrCis) > 0) {
                    $jsonCis = '{';
                    foreach ($arrCis as $clave => $carnet) {
                        $jsonCis .= '"' . $clave . '":"' . $carnet . '",';
                    }
                    $jsonCis .= ',';
                    $jsonCis = str_replace(",,", "", $jsonCis);
                    $jsonCis .= '}';
                } else {
                    $jsonCis .= '{"0":"' . $carnetAux . '"}';
                }
                $objHM = new Fplanillasref();
                $arrIdRelaborales = $objHM->getIdRelaboralesEnJsonPorCarnets($jsonCis, $fechaIni, $fechaFin);
                $jsonIdRelaborales = '{"0":0}';
                if (is_object($arrIdRelaborales)) {
                    $clave = 0;
                    $jsonIdRelaborales = '{';
                    foreach ($arrIdRelaborales as $reg) {
                        $jsonIdRelaborales .= '"' . $clave . '":' . $reg->id . ',';
                        $clave++;
                    }
                    $jsonIdRelaborales .= ',';
                    $jsonIdRelaborales = str_replace(",,", "", $jsonIdRelaborales);
                    $jsonIdRelaborales .= '}';
                }
            }

            $resul = $obj->getAllByRangeTwoMonth($jsonIdRelaborales, $fechaIni, $fechaFin, $where);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $horariosymarcaciones[] = array(
                        'nro_row' => 0,
                        #region Columnas de procedimiento f_relaborales()
                        'relaboral_id' => $v->relaboral_id,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'cargo' => $v->cargo,
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'condicion' => $v->condicion,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'area' => $v->area,
                        'ubicacion' => $v->ubicacion,
                        #endregion Columnas de procedimiento f_relaborales()

                        'id' => $v->id_horarioymarcacion,
                        'relaboral_id' => $v->relaboral_id,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'turno' => $v->turno,
                        'grupo' => $v->grupo,
                        'clasemarcacion' => $v->clasemarcacion,
                        'clasemarcacion_descripcion' => $v->clasemarcacion_descripcion,
                        'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                        'modalidad_marcacion' => $v->modalidad_marcacion,
                        'd1' => $v->d1,
                        'calendariolaboral1_id' => $v->calendariolaboral1_id,
                        'd2' => $v->d2,
                        'calendariolaboral2_id' => $v->calendariolaboral2_id,
                        'd3' => $v->d3,
                        'calendariolaboral3_id' => $v->calendariolaboral3_id,
                        'd4' => $v->d4,
                        'calendariolaboral4_id' => $v->calendariolaboral4_id,
                        'd5' => $v->d5,
                        'calendariolaboral5_id' => $v->calendariolaboral5_id,
                        'd6' => $v->d6,
                        'calendariolaboral6_id' => $v->calendariolaboral6_id,
                        'd7' => $v->d7,
                        'calendariolaboral7_id' => $v->calendariolaboral7_id,
                        'd8' => $v->d8,
                        'calendariolaboral8_id' => $v->calendariolaboral8_id,
                        'd9' => $v->d9,
                        'calendariolaboral9_id' => $v->calendariolaboral9_id,
                        'd10' => $v->d10,
                        'calendariolaboral10_id' => $v->calendariolaboral10_id,
                        'd11' => $v->d11,
                        'calendariolaboral11_id' => $v->calendariolaboral11_id,
                        'd12' => $v->d12,
                        'calendariolaboral12_id' => $v->calendariolaboral12_id,
                        'd13' => $v->d13,
                        'calendariolaboral13_id' => $v->calendariolaboral13_id,
                        'd14' => $v->d14,
                        'calendariolaboral14_id' => $v->calendariolaboral14_id,
                        'd15' => $v->d15,
                        'calendariolaboral15_id' => $v->calendariolaboral15_id,
                        'd16' => $v->d16,
                        'calendariolaboral16_id' => $v->calendariolaboral16_id,
                        'd17' => $v->d17,
                        'calendariolaboral17_id' => $v->calendariolaboral17_id,
                        'd18' => $v->d18,
                        'calendariolaboral18_id' => $v->calendariolaboral18_id,
                        'd19' => $v->d19,
                        'calendariolaboral19_id' => $v->calendariolaboral19_id,
                        'd20' => $v->d20,
                        'calendariolaboral20_id' => $v->calendariolaboral20_id,
                        'd21' => $v->d21,
                        'calendariolaboral21_id' => $v->calendariolaboral21_id,
                        'd22' => $v->d22,
                        'calendariolaboral22_id' => $v->calendariolaboral22_id,
                        'd23' => $v->d23,
                        'calendariolaboral23_id' => $v->calendariolaboral23_id,
                        'd24' => $v->d24,
                        'calendariolaboral24_id' => $v->calendariolaboral24_id,
                        'd25' => $v->d25,
                        'calendariolaboral25_id' => $v->calendariolaboral25_id,
                        'd26' => $v->d26,
                        'calendariolaboral26_id' => $v->calendariolaboral26_id,
                        'd27' => $v->d27,
                        'calendariolaboral27_id' => $v->calendariolaboral27_id,
                        'd28' => $v->d28,
                        'calendariolaboral28_id' => $v->calendariolaboral28_id,
                        'd29' => $v->d29,
                        'calendariolaboral29_id' => $v->calendariolaboral29_id,
                        'd30' => $v->d30,
                        'calendariolaboral30_id' => $v->calendariolaboral30_id,
                        'd31' => $v->d31,
                        'calendariolaboral31_id' => $v->calendariolaboral31_id,
                        'ultimo_dia' => $v->ultimo_dia,
                        'atrasos' => $v->atrasos,
                        'atrasados' => $v->atrasados,
                        'faltas' => $v->faltas,
                        'abandono' => $v->abandono,
                        'omision' => $v->omision,
                        'lsgh' => $v->lsgh,
                        'compensacion' => $v->compensacion,
                        'descanso' => $v->descanso,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_apr_id' => $v->user_apr_id,
                        'fecha_apr' => $v->fecha_apr,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                    );
                    #region sector para adicionar una fila para Excepciones
                    if ($v->modalidadmarcacion_id == 6) {
                        $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = $d8 = $d9 = $d10 = $d11 = $d12 = $d13 = $d14 = $d15 = $d16 = $d17 = $d18 = $d19 = $d20 = $d21 = $d22 = $d23 = $d24 = $d25 = $d26 = $d27 = $d28 = $d29 = $d30 = $d30 = $d31 = "";
                        if ($v->calendariolaboral1_id > 0) {
                            $d1 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 1, $v->calendariolaboral1_id);
                        }
                        if ($v->calendariolaboral2_id > 0) {
                            $d2 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 2, $v->calendariolaboral2_id);
                        }
                        if ($v->calendariolaboral3_id > 0) {
                            $d3 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 3, $v->calendariolaboral3_id);
                        }

                        if ($v->calendariolaboral4_id > 0) {
                            $d4 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 4, $v->calendariolaboral4_id);
                        }
                        if ($v->calendariolaboral5_id > 0) {
                            $d5 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 5, $v->calendariolaboral5_id);
                        }
                        if ($v->calendariolaboral6_id > 0) {
                            $d6 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 6, $v->calendariolaboral6_id);
                        }
                        if ($v->calendariolaboral7_id > 0) {
                            $d7 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 7, $v->calendariolaboral7_id);
                        }
                        if ($v->calendariolaboral8_id > 0) {
                            $d8 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 8, $v->calendariolaboral8_id);
                        }
                        if ($v->calendariolaboral9_id > 0) {
                            $d9 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 9, $v->calendariolaboral9_id);
                        }
                        if ($v->calendariolaboral10_id > 0) {
                            $d10 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 10, $v->calendariolaboral10_id);
                        }
                        if ($v->calendariolaboral11_id > 0) {
                            $d11 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 11, $v->calendariolaboral11_id);
                        }
                        if ($v->calendariolaboral12_id > 0) {
                            $d12 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 12, $v->calendariolaboral12_id);
                        }

                        if ($v->calendariolaboral13_id > 0) {
                            $d13 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 13, $v->calendariolaboral13_id);
                        }

                        if ($v->calendariolaboral14_id > 0) {
                            $d14 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 14, $v->calendariolaboral14_id);
                        }
                        if ($v->calendariolaboral15_id > 0) {
                            $d15 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 15, $v->calendariolaboral15_id);
                        }
                        if ($v->calendariolaboral16_id > 0) {
                            $d16 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 16, $v->calendariolaboral16_id);
                        }
                        if ($v->calendariolaboral17_id > 0) {
                            $d17 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 17, $v->calendariolaboral17_id);
                        }
                        if ($v->calendariolaboral18_id > 0) {
                            $d18 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 18, $v->calendariolaboral18_id);
                        }
                        if ($v->calendariolaboral19_id > 0) {
                            $d19 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 19, $v->calendariolaboral19_id);
                        }
                        if ($v->calendariolaboral20_id > 0) {
                            $d20 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 20, $v->calendariolaboral20_id);
                        }
                        if ($v->calendariolaboral21_id > 0) {
                            $d21 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 21, $v->calendariolaboral21_id);
                        }
                        if ($v->calendariolaboral22_id > 0) {
                            $d22 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 22, $v->calendariolaboral22_id);
                        }
                        if ($v->calendariolaboral23_id > 0) {
                            $d23 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 23, $v->calendariolaboral23_id);
                        }
                        if ($v->calendariolaboral24_id > 0) {
                            $d24 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 24, $v->calendariolaboral24_id);
                        }
                        if ($v->calendariolaboral25_id > 0) {
                            $d25 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 25, $v->calendariolaboral25_id);
                        }
                        if ($v->calendariolaboral26_id > 0) {
                            $d26 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 26, $v->calendariolaboral26_id);
                        }
                        if ($v->calendariolaboral27_id > 0) {
                            $d27 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 27, $v->calendariolaboral27_id);
                        }
                        if ($v->calendariolaboral28_id > 0) {
                            $d28 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 28, $v->calendariolaboral28_id);
                        }
                        if ($v->calendariolaboral29_id > 0) {
                            $d29 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 29, $v->calendariolaboral29_id);
                        }
                        if ($v->calendariolaboral30_id > 0) {
                            $d30 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 30, $v->calendariolaboral30_id);
                        }
                        if ($v->calendariolaboral31_id > 0) {
                            $d31 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 31, $v->calendariolaboral31_id);
                        }
                        $horariosymarcaciones[] = array(
                            'nro_row' => 0,
                            #region Columnas de procedimiento f_relaborales()
                            'id_relaboral' => $v->id_relaboral,
                            'id_persona' => $v->id_persona,
                            'p_nombre' => $v->p_nombre,
                            's_nombre' => $v->s_nombre,
                            't_nombre' => $v->t_nombre,
                            'p_apellido' => $v->p_apellido,
                            's_apellido' => $v->s_apellido,
                            'c_apellido' => $v->c_apellido,
                            'nombres' => $v->nombres,
                            'ci' => $v->ci,
                            'expd' => $v->expd,
                            'cargo' => $v->cargo,
                            'sueldo' => str_replace(".00", "", $v->sueldo),
                            'condicion' => $v->condicion,
                            'gerencia_administrativa' => $v->gerencia_administrativa,
                            'departamento_administrativo' => $v->departamento_administrativo,
                            'area' => $v->area,
                            'ubicacion' => $v->ubicacion,
                            #endregion Columnas de procedimiento f_relaborales()

                            'id' => $v->id_horarioymarcacion,
                            'relaboral_id' => $v->relaboral_id,
                            'gestion' => $v->gestion,
                            'mes' => $v->mes,
                            'mes_nombre' => $v->mes_nombre,
                            'turno' => $v->turno,
                            'grupo' => $v->grupo,
                            'clasemarcacion' => "e",
                            'clasemarcacion_descripcion' => "EXCEPCIONES / FERIADOS",
                            'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                            'modalidad_marcacion' => "EXCEPCIONES / FERIADOS",
                            'd1' => $d1,
                            'calendariolaboral1_id' => $v->calendariolaboral1_id,
                            'd2' => $d2,
                            'calendariolaboral2_id' => $v->calendariolaboral2_id,
                            'd3' => $d3,
                            'calendariolaboral3_id' => $v->calendariolaboral3_id,
                            'd4' => $d4,
                            'calendariolaboral4_id' => $v->calendariolaboral4_id,
                            'd5' => $d5,
                            'calendariolaboral5_id' => $v->calendariolaboral5_id,
                            'd6' => $d6,
                            'calendariolaboral6_id' => $v->calendariolaboral6_id,
                            'd7' => $d7,
                            'calendariolaboral7_id' => $v->calendariolaboral7_id,
                            'd8' => $d8,
                            'calendariolaboral8_id' => $v->calendariolaboral8_id,
                            'd9' => $d9,
                            'calendariolaboral9_id' => $v->calendariolaboral9_id,
                            'd10' => $d10,
                            'calendariolaboral10_id' => $v->calendariolaboral10_id,
                            'd11' => $d11,
                            'calendariolaboral11_id' => $v->calendariolaboral11_id,
                            'd12' => $d12,
                            'calendariolaboral12_id' => $v->calendariolaboral12_id,
                            'd13' => $d13,
                            'calendariolaboral13_id' => $v->calendariolaboral13_id,
                            'd14' => $d14,
                            'calendariolaboral14_id' => $v->calendariolaboral14_id,
                            'd15' => $d15,
                            'calendariolaboral15_id' => $v->calendariolaboral15_id,
                            'd16' => $d16,
                            'calendariolaboral16_id' => $v->calendariolaboral16_id,
                            'd17' => $d17,
                            'calendariolaboral17_id' => $v->calendariolaboral17_id,
                            'd18' => $d18,
                            'calendariolaboral18_id' => $v->calendariolaboral18_id,
                            'd19' => $d19,
                            'calendariolaboral19_id' => $v->calendariolaboral19_id,
                            'd20' => $d20,
                            'calendariolaboral20_id' => $v->calendariolaboral20_id,
                            'd21' => $d21,
                            'calendariolaboral21_id' => $v->calendariolaboral21_id,
                            'd22' => $d22,
                            'calendariolaboral22_id' => $v->calendariolaboral22_id,
                            'd23' => $d23,
                            'calendariolaboral23_id' => $v->calendariolaboral23_id,
                            'd24' => $d24,
                            'calendariolaboral24_id' => $v->calendariolaboral24_id,
                            'd25' => $d25,
                            'calendariolaboral25_id' => $v->calendariolaboral25_id,
                            'd26' => $d26,
                            'calendariolaboral26_id' => $v->calendariolaboral26_id,
                            'd27' => $d27,
                            'calendariolaboral27_id' => $v->calendariolaboral27_id,
                            'd28' => $d28,
                            'calendariolaboral28_id' => $v->calendariolaboral28_id,
                            'd29' => $d29,
                            'calendariolaboral29_id' => $v->calendariolaboral29_id,
                            'd30' => $d30,
                            'calendariolaboral30_id' => $v->calendariolaboral30_id,
                            'd31' => $d31,
                            'calendariolaboral31_id' => $v->calendariolaboral31_id,
                            'ultimo_dia' => $v->ultimo_dia,
                            'atrasos' => null,
                            'faltas' => null,
                            'abandono' => null,
                            'omision' => null,
                            'lsgh' => null,
                            'compensacion' => null,
                            'observacion' => $v->observacion,
                            'estado' => $v->estado,
                            'estado_descripcion' => $v->estado_descripcion,
                            'baja_logica' => $v->baja_logica,
                            'agrupador' => $v->agrupador,
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_apr_id' => $v->user_apr_id,
                            'fecha_apr' => $v->fecha_apr,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod,
                        );
                    }
                    #endregion sector para adicionar una fila para Excepciones
                }
            }
        }
        echo json_encode($horariosymarcaciones);
    }

    /**
     * Función para la obtención del listado de controles de excepción para un registro de relación laboral considerando un rango de fechas.
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
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
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
     * Función para el almacenamiento y actualización de un registro de Control de Excepción.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Crítico Error
     *  -2: Error de Conexión
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
             * Modificación de registro de Feriado
             */
            $idRelaboral = $_POST['relaboral_id'];
            $idExcepcion = $_POST['excepcion_id'];
            $fechaIni = $_POST['fecha_ini'];
            $horaIni = $_POST['hora_ini'];
            $fechaFin = $_POST['fecha_fin'];
            $horaFin = $_POST['hora_fin'];
            $justificacion = $_POST['justificacion'];
            $observacion = $_POST['observacion'];
            if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $horaIni != '' && $fechaFin != '' && $horaFin != '' && $justificacion != '') {
                $objControlExcepciones = Controlexcepciones::findFirst(array("id=" . $_POST["id"]));
                if (count($objControlExcepciones) > 0) {
                    $cantMismosDatos = Controlexcepciones::count(array("id!=" . $_POST["id"] . " AND relaboral_id=" . $idRelaboral . " AND excepcion_id = " . $idExcepcion . " AND fecha_ini='" . $fechaIni . "' AND hora_ini='" . $horaIni . "' AND fecha_fin = '" . $fechaFin . "' AND hora_fin='" . $horaFin . "' AND baja_logica=1 AND estado>=0"));
                    if ($cantMismosDatos == 0) {
                        $objControlExcepciones->relaboral_id = $idRelaboral;
                        $objControlExcepciones->excepcion_id = $idExcepcion;
                        $objControlExcepciones->fecha_ini = $fechaIni;
                        $objControlExcepciones->fecha_fin = $fechaFin;
                        $objControlExcepciones->hora_ini = $horaIni;
                        $objControlExcepciones->hora_fin = $horaFin;
                        $objControlExcepciones->justificacion = $justificacion;
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
             * Registro de Control de Excepción
             */
            $idRelaboral = $_POST['relaboral_id'];
            $idExcepcion = $_POST['excepcion_id'];
            $fechaIni = $_POST['fecha_ini'];
            $horaIni = $_POST['hora_ini'];
            $fechaFin = $_POST['fecha_fin'];
            $horaFin = $_POST['hora_fin'];
            $justificacion = $_POST['justificacion'];
            $observacion = $_POST['observacion'];
            if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $horaIni != '' && $fechaFin != '' && $horaFin != '' && $justificacion != '') {
                $cantMismosDatos = Controlexcepciones::count(array("relaboral_id=" . $idRelaboral . " AND excepcion_id = " . $idExcepcion . " AND fecha_ini='" . $fechaIni . "' AND hora_ini='" . $horaIni . "' AND fecha_fin = '" . $fechaFin . "' AND hora_fin='" . $horaFin . "' AND baja_logica=1 AND estado>=0"));
                if ($cantMismosDatos == 0) {
                    $objControlExcepciones = new Controlexcepciones();
                    $objControlExcepciones->relaboral_id = $idRelaboral;
                    $objControlExcepciones->excepcion_id = $idExcepcion;
                    $objControlExcepciones->fecha_ini = $fechaIni;
                    $objControlExcepciones->fecha_fin = $fechaFin;
                    $objControlExcepciones->hora_ini = $horaIni;
                    $objControlExcepciones->hora_fin = $horaFin;
                    $objControlExcepciones->justificacion = $justificacion;
                    $objControlExcepciones->observacion = $observacion;
                    $objControlExcepciones->estado = 2;
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
     * Función para la aprobación del registro de un control de excepción.
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
             * Aprobación de registro
             */
            $objControlExcepciones = Controlexcepciones::findFirstById($_POST["id"]);
            if ($objControlExcepciones->id > 0 && $objControlExcepciones->estado == 2) {
                try {
                    $objControlExcepciones->estado = 3;
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
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro del control de excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para el la baja del registro de un control de excepción.
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
     * Función para dar de baja lógica a registros de horarios y marcaciones de acuerdo a una determinada gestión, mes, registro de relación laboral y clase de marcación.
     */
    public function delAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $idRelaboral = (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0) ? $_POST["id_relaboral"] : 0;
        $gestion = (isset($_POST["gestion"]) && $_POST["gestion"] > 0) ? $_POST["gestion"] : 0;
        $mes = (isset($_POST["mes"]) && $_POST["mes"] > 0) ? $_POST["mes"] : 0;
        $claseMarcacion = (isset($_POST["clasemarcacion"])) ? $_POST["clasemarcacion"] : "";
        //echo "$idRelaboral > 0 && $gestion > 0 && $mes > 0 && $claseMarcacion";
        try {
            if ($idRelaboral > 0 && $gestion > 0 && $mes > 0 && $claseMarcacion != "") {
                /**
                 * Baja de registro
                 */
                $objHyM = new Horariosymarcaciones();
                $ok = $objHyM->bajaRegistro($idUsuario, $idRelaboral, $gestion, $mes, $claseMarcacion, 4);
                if ($ok) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Baja l&oacute;gica realizada de modo satisfactorio.');
                } else {
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de la horario y marcaci&oacute;n.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de horario y marcaci&oacute;n.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de horario y marcaci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la verificación
     */
    public function verificacruceAction()
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
        $justificacion = $_POST['justificacion'];
        if ($idRelaboral > 0 && $idExcepcion > 0 && $fechaIni != '' && $horaIni != '' && $fechaFin != '' && $horaFin != '' && $justificacion != '') {
            /**
             * Se realiza la verificación sobre el cruce de horarios y fechas de los controles de excepción existentes y la que se intenta registrar o modificar.
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
     * Función para la exportación del reporte en formato Excel.
     * @param $n_rows Cantidad de lineas
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds  Columnas ordenadas .
     */
    public function exportmarcacionesexcelAction($idRelaboral, $n_rows, $columns, $filtros, $groups, $sorteds)
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
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'gestion' => array('title' => 'Gestion', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'turno' => array('title' => 'Turno', 'width' => 10, 'align' => 'C', 'type' => 'numeric'),
            'modalidad_marcacion' => array('title' => 'Modalidad', 'width' => 30, 'align' => 'C', 'type' => 'varchar'),
            'd1' => array('title' => 'Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado1_descripcion' => array('title' => 'Estado Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd2' => array('title' => 'Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado2_descripcion' => array('title' => 'Estado Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd3' => array('title' => 'Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado3_descripcion' => array('title' => 'Estado Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd4' => array('title' => 'Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado4_descripcion' => array('title' => 'Estado Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd5' => array('title' => 'Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado5_descripcion' => array('title' => 'Estado Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd6' => array('title' => 'Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado6_descripcion' => array('title' => 'Estado Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd7' => array('title' => 'Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado7_descripcion' => array('title' => 'Estado Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd8' => array('title' => 'Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado8_descripcion' => array('title' => 'Estado Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd9' => array('title' => 'Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado9_descripcion' => array('title' => 'Estado Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd10' => array('title' => 'Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado10_descripcion' => array('title' => 'Estado Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd11' => array('title' => 'Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado11_descripcion' => array('title' => 'Estado Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd12' => array('title' => 'Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado12_descripcion' => array('title' => 'Estado Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd13' => array('title' => 'Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado13_descripcion' => array('title' => 'Estado Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd14' => array('title' => 'Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado14_descripcion' => array('title' => 'Estado Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd15' => array('title' => 'Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado15_descripcion' => array('title' => 'Estado Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd16' => array('title' => 'Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado16_descripcion' => array('title' => 'Estado Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd17' => array('title' => 'Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado17_descripcion' => array('title' => 'Estado Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd18' => array('title' => 'Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado18_descripcion' => array('title' => 'Estado Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd19' => array('title' => 'Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado19_descripcion' => array('title' => 'Estado Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd20' => array('title' => 'Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado20_descripcion' => array('title' => 'Estado Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd21' => array('title' => 'Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado21_descripcion' => array('title' => 'Estado Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd22' => array('title' => 'Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado22_descripcion' => array('title' => 'Estado Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd23' => array('title' => 'Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado23_descripcion' => array('title' => 'Estado Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd24' => array('title' => 'Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado24_descripcion' => array('title' => 'Estado Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd25' => array('title' => 'Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado25_descripcion' => array('title' => 'Estado Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd26' => array('title' => 'Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado26_descripcion' => array('title' => 'Estado Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd27' => array('title' => 'Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado27_descripcion' => array('title' => 'Estado Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd28' => array('title' => 'Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado28_descripcion' => array('title' => 'Estado Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd29' => array('title' => 'Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado29_descripcion' => array('title' => 'Estado Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd30' => array('title' => 'Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado30_descripcion' => array('title' => 'Estado Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd31' => array('title' => 'Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'string'),
            /*'estado31_descripcion' => array('title' => 'Estado Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'ultimo_dia' => array('title' => 'Ultimo Dia Procesado', 'width' => 10, 'align' => 'C', 'type' => 'numeric'),
            'atrasos' => array('title' => 'Atrasos', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'faltas' => array('title' => 'Faltas', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'abandono' => array('title' => 'Abandono', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'omision' => array('title' => 'Omision', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'lsgh' => array('title' => 'LSGH', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'agrupador' => array('title' => 'Marc. Previstas', 'width' => 15, 'align' => 'C', 'type' => 'numeric'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if ($idRelaboral > 0 && count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $objR = new Frelaborales();
            $relaboral = $objR->getOne($idRelaboral);
            $excel->title_rpt = utf8_decode('Reporte Marcaciones "' . $relaboral[0]->nombres . '"');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
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
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^idRelaboral^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>" . $idRelaboral;
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
            $obj = new Fhorariosymarcaciones();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                /*if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }*/
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
                    $groups = " ORDER BY " . $groups . ",relaboral_id,gestion,mes,turno,modalidadmarcacion_id";
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
            $resul = $obj->getAllFromOneRelaboral($idRelaboral, $where, $groups);
            $horariosymarcaciones = array();
            $listaExcepciones = array();
            $contador = 0;
            foreach ($resul as $v) {
                $horariosymarcaciones[] = array(
                    'id' => $v->id_horarioymarcacion,
                    'relaboral_id' => $v->relaboral_id,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'turno' => $v->turno,
                    'grupo' => $v->grupo,
                    'clasemarcacion' => $v->clasemarcacion,
                    'clasemarcacion_descripcion' => $v->clasemarcacion_descripcion,
                    'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                    'modalidad_marcacion' => $v->modalidad_marcacion,
                    'd1' => $v->d1,
                    'calendariolaboral1_id' => $v->calendariolaboral1_id,
                    'd2' => $v->d2,
                    'calendariolaboral2_id' => $v->calendariolaboral2_id,
                    'd3' => $v->d3,
                    'calendariolaboral3_id' => $v->calendariolaboral3_id,
                    'd4' => $v->d4,
                    'calendariolaboral4_id' => $v->calendariolaboral4_id,
                    'd5' => $v->d5,
                    'calendariolaboral5_id' => $v->calendariolaboral5_id,
                    'd6' => $v->d6,
                    'calendariolaboral6_id' => $v->calendariolaboral6_id,
                    'd7' => $v->d7,
                    'calendariolaboral7_id' => $v->calendariolaboral7_id,
                    'd8' => $v->d8,
                    'calendariolaboral8_id' => $v->calendariolaboral8_id,
                    'd9' => $v->d9,
                    'calendariolaboral9_id' => $v->calendariolaboral9_id,
                    'd10' => $v->d10,
                    'calendariolaboral10_id' => $v->calendariolaboral10_id,
                    'd11' => $v->d11,
                    'calendariolaboral11_id' => $v->calendariolaboral11_id,
                    'd12' => $v->d12,
                    'calendariolaboral12_id' => $v->calendariolaboral12_id,
                    'd13' => $v->d13,
                    'calendariolaboral13_id' => $v->calendariolaboral13_id,
                    'd14' => $v->d14,
                    'calendariolaboral14_id' => $v->calendariolaboral14_id,
                    'd15' => $v->d15,
                    'calendariolaboral15_id' => $v->calendariolaboral15_id,
                    'd16' => $v->d16,
                    'calendariolaboral16_id' => $v->calendariolaboral16_id,
                    'd17' => $v->d17,
                    'calendariolaboral17_id' => $v->calendariolaboral17_id,
                    'd18' => $v->d18,
                    'calendariolaboral18_id' => $v->calendariolaboral18_id,
                    'd19' => $v->d19,
                    'calendariolaboral19_id' => $v->calendariolaboral19_id,
                    'd20' => $v->d20,
                    'calendariolaboral20_id' => $v->calendariolaboral20_id,
                    'd21' => $v->d21,
                    'calendariolaboral21_id' => $v->calendariolaboral21_id,
                    'd22' => $v->d22,
                    'calendariolaboral22_id' => $v->calendariolaboral22_id,
                    'd23' => $v->d23,
                    'calendariolaboral23_id' => $v->calendariolaboral23_id,
                    'd24' => $v->d24,
                    'calendariolaboral24_id' => $v->calendariolaboral24_id,
                    'd25' => $v->d25,
                    'calendariolaboral25_id' => $v->calendariolaboral25_id,
                    'd26' => $v->d26,
                    'calendariolaboral26_id' => $v->calendariolaboral26_id,
                    'd27' => $v->d27,
                    'calendariolaboral27_id' => $v->calendariolaboral27_id,
                    'd28' => $v->d28,
                    'calendariolaboral28_id' => $v->calendariolaboral28_id,
                    'd29' => $v->d29,
                    'calendariolaboral29_id' => $v->calendariolaboral29_id,
                    'd30' => $v->d30,
                    'calendariolaboral30_id' => $v->calendariolaboral30_id,
                    'd31' => $v->d31,
                    'calendariolaboral31_id' => $v->calendariolaboral31_id,
                    'ultimo_dia' => $v->ultimo_dia,
                    'atrasos' => $v->atrasos,
                    'faltas' => $v->faltas,
                    'abandono' => $v->abandono,
                    'omision' => $v->omision,
                    'lsgh' => $v->lsgh,
                    'compensacion' => $v->compensacion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_apr_id' => $v->user_apr_id,
                    'fecha_apr' => $v->fecha_apr,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                );
                #region sector para adicionar una fila para Excepciones
                if ($v->modalidadmarcacion_id == 6) {
                    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = $d8 = $d9 = $d10 = $d11 = $d12 = $d13 = $d14 = $d15 = $d16 = $d17 = $d18 = $d19 = $d20 = $d21 = $d22 = $d23 = $d24 = $d25 = $d26 = $d27 = $d28 = $d29 = $d30 = $d30 = $d31 = "";
                    if ($v->calendariolaboral1_id > 0) {
                        $res1 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 1, $v->calendariolaboral1_id, 1);
                        if (is_object($res1) && $res1->count() > 0) {
                            foreach ($res1 as $r1) {
                                $d1 = $r1->f_excepciones_en_dia;
                            }
                        }
                        $fe1 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 1, 1);
                        if (is_object($fe1) && $fe1->count() > 0) {
                            foreach ($fe1 as $f1) {
                                $d1 .= $f1->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral2_id > 0) {
                        $res2 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 2, $v->calendariolaboral2_id, 1);
                        if (is_object($res2) && $res2->count() > 0) {
                            foreach ($res2 as $r2) {
                                $d2 = $r2->f_excepciones_en_dia;
                            }
                        }
                        $fe2 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 2, 1);
                        if (is_object($fe2) && $fe2->count() > 0) {
                            foreach ($fe2 as $f2) {
                                $d2 .= $f2->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral3_id > 0) {
                        $res3 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 3, $v->calendariolaboral3_id, 1);
                        if (is_object($res3) && $res3->count() > 0) {
                            foreach ($res3 as $r3) {
                                $d3 = $r3->f_excepciones_en_dia;
                            }
                        }
                        $fe3 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 3, 1);
                        if (is_object($fe3) && $fe3->count() > 0) {
                            foreach ($fe3 as $f3) {
                                $d3 .= $f3->f_feriados_en_dia;
                            }
                        }
                    }

                    if ($v->calendariolaboral4_id > 0) {
                        $res4 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 4, $v->calendariolaboral4_id, 1);
                        if (is_object($res4) && $res4->count() > 0) {
                            foreach ($res4 as $r4) {
                                $d4 = $r4->f_excepciones_en_dia;
                            }
                        }
                        $fe4 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 4, 1);
                        if (is_object($fe4) && $fe4->count() > 0) {
                            foreach ($fe4 as $f4) {
                                $d4 .= $f4->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral5_id > 0) {
                        $res5 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 5, $v->calendariolaboral5_id, 1);
                        if (is_object($res5) && $res5->count() > 0) {
                            foreach ($res5 as $r5) {
                                $d5 = $r5->f_excepciones_en_dia;
                            }
                        }
                        $fe5 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 5, 1);
                        if (is_object($fe5) && $fe5->count() > 0) {
                            foreach ($fe5 as $f5) {
                                $d5 .= $f5->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral6_id > 0) {
                        $res6 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 6, $v->calendariolaboral6_id, 1);
                        if (is_object($res6) && $res6->count() > 0) {
                            foreach ($res6 as $r6) {
                                $d6 = $r6->f_excepciones_en_dia;
                            }
                        }
                        $fe6 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 6, 1);
                        if (is_object($fe6) && $fe6->count() > 0) {
                            foreach ($fe6 as $f6) {
                                $d6 .= $f6->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral7_id > 0) {
                        $res7 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 7, $v->calendariolaboral7_id, 1);
                        if (is_object($res7) && $res7->count() > 0) {
                            foreach ($res7 as $r7) {
                                $d7 = $r7->f_excepciones_en_dia;
                            }
                        }
                        $fe7 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 7, 1);
                        if (is_object($fe7) && $fe7->count() > 0) {
                            foreach ($fe7 as $f7) {
                                $d7 .= $f7->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral8_id > 0) {
                        $res8 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 8, $v->calendariolaboral8_id, 1);
                        if (is_object($res8) && $res8->count() > 0) {
                            foreach ($res8 as $r8) {
                                $d8 = $r8->f_excepciones_en_dia;
                            }
                        }
                        $fe8 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 8, 1);
                        if (is_object($fe8) && $fe8->count() > 0) {
                            foreach ($fe8 as $f8) {
                                $d8 .= $f8->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral9_id > 0) {
                        $res9 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 9, $v->calendariolaboral9_id, 1);
                        if (is_object($res9) && $res9->count() > 0) {
                            foreach ($res9 as $r9) {
                                $d9 = $r9->f_excepciones_en_dia;
                            }
                        }
                        $fe9 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 9, 1);
                        if (is_object($fe9) && $fe9->count() > 0) {
                            foreach ($fe9 as $f9) {
                                $d9 .= $f9->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral10_id > 0) {
                        $res10 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 10, $v->calendariolaboral10_id, 1);
                        if (is_object($res10) && $res10->count() > 0) {
                            foreach ($res10 as $r10) {
                                $d10 = $r10->f_excepciones_en_dia;
                            }
                        }
                        $fe10 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 10, 1);
                        if (is_object($fe10) && $fe10->count() > 0) {
                            foreach ($fe10 as $f10) {
                                $d10 .= $f10->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral11_id > 0) {
                        $res11 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 11, $v->calendariolaboral11_id, 1);
                        if (is_object($res11) && $res11->count() > 0) {
                            foreach ($res11 as $r11) {
                                $d11 = $r11->f_excepciones_en_dia;
                            }
                        }
                        $fe11 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 11, 1);
                        if (is_object($fe11) && $fe11->count() > 0) {
                            foreach ($fe11 as $f11) {
                                $d11 .= $f11->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral12_id > 0) {
                        $res12 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 12, $v->calendariolaboral12_id, 1);
                        if (is_object($res12) && $res12->count() > 0) {
                            foreach ($res12 as $r12) {
                                $d12 = $r12->f_excepciones_en_dia;
                            }
                        }
                        $fe12 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 12, 1);
                        if (is_object($fe12) && $fe12->count() > 0) {
                            foreach ($fe12 as $f12) {
                                $d12 .= $f12->f_feriados_en_dia;
                            }
                        }
                    }

                    if ($v->calendariolaboral13_id > 0) {
                        $res13 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 13, $v->calendariolaboral13_id, 1);
                        if (is_object($res13) && $res13->count() > 0) {
                            foreach ($res13 as $r13) {
                                $d13 = $r13->f_excepciones_en_dia;
                            }
                        }
                        $fe13 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 13, 1);
                        if (is_object($fe13) && $fe13->count() > 0) {
                            foreach ($fe13 as $f13) {
                                $d13 .= $f13->f_feriados_en_dia;
                            }
                        }
                    }

                    if ($v->calendariolaboral14_id > 0) {
                        $res14 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 14, $v->calendariolaboral14_id, 1);
                        if (is_object($res14) && $res14->count() > 0) {
                            foreach ($res14 as $r14) {
                                $d14 = $r14->f_excepciones_en_dia;
                            }
                        }
                        $fe14 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 14, 1);
                        if (is_object($fe14) && $fe14->count() > 0) {
                            foreach ($fe14 as $f14) {
                                $d14 .= $f14->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral15_id > 0) {
                        $res15 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 15, $v->calendariolaboral15_id, 1);
                        if (is_object($res15) && $res15->count() > 0) {
                            foreach ($res15 as $r15) {
                                $d15 = $r15->f_excepciones_en_dia;
                            }
                        }
                        $fe15 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 15, 1);
                        if (is_object($fe15) && $fe15->count() > 0) {
                            foreach ($fe15 as $f15) {
                                $d15 .= $f15->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral16_id > 0) {
                        $res16 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 16, $v->calendariolaboral16_id, 1);
                        if (is_object($res16) && $res16->count() > 0) {
                            foreach ($res16 as $r16) {
                                $d16 = $r16->f_excepciones_en_dia;
                            }
                        }
                        $fe16 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 16, 1);
                        if (is_object($fe16) && $fe16->count() > 0) {
                            foreach ($fe16 as $f16) {
                                $d16 .= $f16->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral17_id > 0) {
                        $res17 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 17, $v->calendariolaboral17_id, 1);
                        if (is_object($res17) && $res17->count() > 0) {
                            foreach ($res17 as $r17) {
                                $d17 = $r17->f_excepciones_en_dia;
                            }
                        }
                        $fe17 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 17, 1);
                        if (is_object($fe17) && $fe17->count() > 0) {
                            foreach ($fe17 as $f17) {
                                $d17 .= $f17->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral18_id > 0) {
                        $res18 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 18, $v->calendariolaboral18_id, 1);
                        if (is_object($res18) && $res18->count() > 0) {
                            foreach ($res18 as $r) {
                                $d18 = $r->f_excepciones_en_dia;
                            }
                        }
                        $fe18 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 18, 1);
                        if (is_object($fe18) && $fe18->count() > 0) {
                            foreach ($fe18 as $f18) {
                                $d18 .= $f18->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral19_id > 0) {
                        $res19 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 19, $v->calendariolaboral19_id, 1);
                        if (is_object($res19) && $res19->count() > 0) {
                            foreach ($res19 as $r) {
                                $d19 = $r->f_excepciones_en_dia;
                            }
                        }
                        $fe19 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 19, 1);
                        if (is_object($fe19) && $fe19->count() > 0) {
                            foreach ($fe19 as $f19) {
                                $d19 .= $f19->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral20_id > 0) {
                        $res20 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 20, $v->calendariolaboral20_id, 1);
                        if (is_object($res20) && $res20->count() > 0) {
                            foreach ($res20 as $r) {
                                $d20 = $r->f_excepciones_en_dia;
                            }
                        }
                        $fe20 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 20, 1);
                        if (is_object($fe20) && $fe20->count() > 0) {
                            foreach ($fe20 as $f20) {
                                $d20 .= $f20->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral21_id > 0) {
                        $res21 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 21, $v->calendariolaboral21_id, 1);
                        if (is_object($res21) && $res21->count() > 0) {
                            foreach ($res21 as $r21) {
                                $d21 = $r21->f_excepciones_en_dia;
                            }
                        }
                        $fe21 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 21, 1);
                        if (is_object($fe21) && $fe21->count() > 0) {
                            foreach ($fe21 as $f21) {
                                $d21 .= $f21->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral22_id > 0) {
                        $res22 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 22, $v->calendariolaboral22_id, 1);
                        if (is_object($res22) && $res22->count() > 0) {
                            foreach ($res22 as $r22) {
                                $d22 = $r22->f_excepciones_en_dia;
                            }
                        }
                        $fe22 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 22, 1);
                        if (is_object($fe22) && $fe22->count() > 0) {
                            foreach ($fe22 as $f22) {
                                $d22 .= $f22->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral23_id > 0) {
                        $res23 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 23, $v->calendariolaboral23_id, 1);
                        if (is_object($res23) && $res23->count() > 0) {
                            foreach ($res23 as $r23) {
                                $d23 = $r23->f_excepciones_en_dia;
                            }
                        }
                        $fe23 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 23, 1);
                        if (is_object($fe23) && $fe23->count() > 0) {
                            foreach ($fe23 as $f23) {
                                $d23 .= $f23->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral24_id > 0) {
                        $res24 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 24, $v->calendariolaboral24_id, 1);
                        if (is_object($res24) && $res24->count() > 0) {
                            foreach ($res24 as $r24) {
                                $d24 = $r24->f_excepciones_en_dia;
                            }
                        }
                        $fe24 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 24, 1);
                        if (is_object($fe24) && $fe24->count() > 0) {
                            foreach ($fe24 as $f24) {
                                $d24 .= $f24->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral25_id > 0) {
                        $res25 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 25, $v->calendariolaboral25_id, 1);
                        if (is_object($res25) && $res25->count() > 0) {
                            foreach ($res25 as $r25) {
                                $d25 = $r25->f_excepciones_en_dia;
                            }
                        }
                        $fe25 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 25, 1);
                        if (is_object($fe25) && $fe25->count() > 0) {
                            foreach ($fe25 as $f25) {
                                $d25 .= $f25->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral26_id > 0) {
                        $res26 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 26, $v->calendariolaboral26_id, 1);
                        if (is_object($res26) && $res26->count() > 0) {
                            foreach ($res26 as $r26) {
                                $d26 = $r26->f_excepciones_en_dia;
                            }
                        }
                        $fe26 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 26, 1);
                        if (is_object($fe26) && $fe26->count() > 0) {
                            foreach ($fe26 as $f26) {
                                $d26 .= $f26->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral27_id > 0) {
                        $res27 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 27, $v->calendariolaboral27_id, 1);
                        if (is_object($res27) && $res27->count() > 0) {
                            foreach ($res27 as $r27) {
                                $d27 = $r27->f_excepciones_en_dia;
                            }
                        }
                        $fe27 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 27, 1);
                        if (is_object($fe27) && $fe27->count() > 0) {
                            foreach ($fe27 as $f27) {
                                $d27 .= $f27->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral28_id > 0) {
                        $res28 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 28, $v->calendariolaboral28_id, 1);
                        if (is_object($res28) && $res28->count() > 0) {
                            foreach ($res28 as $r28) {
                                $d28 = $r28->f_excepciones_en_dia;
                            }
                        }
                        $fe28 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 28, 1);
                        if (is_object($fe28) && $fe28->count() > 0) {
                            foreach ($fe28 as $f28) {
                                $d28 .= $f28->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral29_id > 0) {
                        $res29 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 29, $v->calendariolaboral29_id, 1);
                        if (is_object($res29) && $res29->count() > 0) {
                            foreach ($res29 as $r29) {
                                $d29 = $r29->f_excepciones_en_dia;
                            }
                        }
                        $fe29 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 29, 1);
                        if (is_object($fe29) && $fe29->count() > 0) {
                            foreach ($fe29 as $f29) {
                                $d29 .= $f29->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral30_id > 0) {
                        $res30 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 30, $v->calendariolaboral30_id, 1);
                        if (is_object($res30) && $res30->count() > 0) {
                            foreach ($res30 as $r30) {
                                $d30 = $r30->f_excepciones_en_dia;
                            }
                        }
                        $fe30 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 30, 1);
                        if (is_object($fe30) && $fe30->count() > 0) {
                            foreach ($fe30 as $f30) {
                                $d30 .= $f30->f_feriados_en_dia;
                            }
                        }
                    }
                    if ($v->calendariolaboral31_id > 0) {
                        $res31 = $obj->getExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 31, $v->calendariolaboral31_id, 1);
                        if (is_object($res31) && $res31->count() > 0) {
                            foreach ($res31 as $r31) {
                                $d31 = $r31->f_excepciones_en_dia;
                            }
                        }
                        $fe31 = $obj->getFeriadosEnDia($v->gestion, $v->mes, 31, 1);
                        if (is_object($fe31) && $fe31->count() > 0) {
                            foreach ($fe31 as $f31) {
                                $d31 .= $f31->f_feriados_en_dia;
                            }
                        }
                    }
                    $horariosymarcaciones[] = array(
                        'id' => $v->id_horarioymarcacion,
                        'relaboral_id' => $v->relaboral_id,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'turno' => $v->turno,
                        'grupo' => $v->grupo,
                        'clasemarcacion' => "e",
                        'clasemarcacion_descripcion' => "EXCEPCIONES / FERIADOS",
                        'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                        'modalidad_marcacion' => "EXCEPCIONES / FERIADOS",
                        'd1' => $d1,
                        'calendariolaboral1_id' => $v->calendariolaboral1_id,
                        'd2' => $d2,
                        'calendariolaboral2_id' => $v->calendariolaboral2_id,
                        'd3' => $d3,
                        'calendariolaboral3_id' => $v->calendariolaboral3_id,
                        'd4' => $d4,
                        'calendariolaboral4_id' => $v->calendariolaboral4_id,
                        'd5' => $d5,
                        'calendariolaboral5_id' => $v->calendariolaboral5_id,
                        'd6' => $d6,
                        'calendariolaboral6_id' => $v->calendariolaboral6_id,
                        'd7' => $d7,
                        'calendariolaboral7_id' => $v->calendariolaboral7_id,
                        'd8' => $d8,
                        'calendariolaboral8_id' => $v->calendariolaboral8_id,
                        'd9' => $d9,
                        'calendariolaboral9_id' => $v->calendariolaboral9_id,
                        'd10' => $d10,
                        'calendariolaboral10_id' => $v->calendariolaboral10_id,
                        'd11' => $d11,
                        'calendariolaboral11_id' => $v->calendariolaboral11_id,
                        'd12' => $d12,
                        'calendariolaboral12_id' => $v->calendariolaboral12_id,
                        'd13' => $d13,
                        'calendariolaboral13_id' => $v->calendariolaboral13_id,
                        'd14' => $d14,
                        'calendariolaboral14_id' => $v->calendariolaboral14_id,
                        'd15' => $d15,
                        'calendariolaboral15_id' => $v->calendariolaboral15_id,
                        'd16' => $d16,
                        'calendariolaboral16_id' => $v->calendariolaboral16_id,
                        'd17' => $d17,
                        'calendariolaboral17_id' => $v->calendariolaboral17_id,
                        'd18' => $d18,
                        'calendariolaboral18_id' => $v->calendariolaboral18_id,
                        'd19' => $d19,
                        'calendariolaboral19_id' => $v->calendariolaboral19_id,
                        'd20' => $d20,
                        'calendariolaboral20_id' => $v->calendariolaboral20_id,
                        'd21' => $d21,
                        'calendariolaboral21_id' => $v->calendariolaboral21_id,
                        'd22' => $d22,
                        'calendariolaboral22_id' => $v->calendariolaboral22_id,
                        'd23' => $d23,
                        'calendariolaboral23_id' => $v->calendariolaboral23_id,
                        'd24' => $d24,
                        'calendariolaboral24_id' => $v->calendariolaboral24_id,
                        'd25' => $d25,
                        'calendariolaboral25_id' => $v->calendariolaboral25_id,
                        'd26' => $d26,
                        'calendariolaboral26_id' => $v->calendariolaboral26_id,
                        'd27' => $d27,
                        'calendariolaboral27_id' => $v->calendariolaboral27_id,
                        'd28' => $d28,
                        'calendariolaboral28_id' => $v->calendariolaboral28_id,
                        'd29' => $d29,
                        'calendariolaboral29_id' => $v->calendariolaboral29_id,
                        'd30' => $d30,
                        'calendariolaboral30_id' => $v->calendariolaboral30_id,
                        'd31' => $d31,
                        'calendariolaboral31_id' => $v->calendariolaboral31_id,
                        'ultimo_dia' => $v->ultimo_dia,
                        'atrasos' => null,
                        'faltas' => null,
                        'abandono' => null,
                        'omision' => null,
                        'lsgh' => null,
                        'compensacion' => null,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => null,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_apr_id' => $v->user_apr_id,
                        'fecha_apr' => $v->fecha_apr,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                    );
                }
                #endregion sector para adicionar una fila para Excepciones
            }
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
            if (count($horariosymarcaciones) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($horariosymarcaciones);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($horariosymarcaciones as $i => $val) {
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
                        $rowData = $excel->DefineRows($j + 1, $horariosymarcaciones[$j], $colSelecteds);
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
                        $celdacolores = array();
                        if ($rowData[0] % 7 == 0) {
                            if ($excel->debug == 1) {
                                echo "<p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>";
                            }
                            for ($i = 1; $i <= 31; $i++) {
                                if (in_array("d" . $i, $colSelecteds)) {
                                    $clave = array_search("d" . $i, $colSelecteds);
                                    if (isset($rowData[$clave]) && $rowData[$clave] != '') {
                                        $celdacolores[$clave] = "FF00FF00";
                                    }
                                }
                            }
                            if ($excel->debug == 1) {
                                print_r($celdacolores);
                                echo "<p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>";
                            }
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila, $celdacolores);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_marcaciones.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la exportación del reporte con cálculos en rango de fechas en formato Excel.
     * @param $lstIdpersonasAux Listado de identificadores de personas.
     * @param $fechaIni Fecha de inicio del rango para el reporte.
     * @param $fechaFin Fecha de finalización del rango para el reporte.
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds  Columnas ordenadas .
     */
    public function exportcalculosexcelAction()
    {
        $this->view->disable();
        ini_set('max_execution_time', 11000);
        $carnetAux = $_POST["carnets"];
        $fechaIni = $_POST["fecha_ini"];
        $fechaFin = $_POST["fecha_fin"];
        $n_rows = $_POST["n_rows"];
        $columns = $_POST["columns"];
        $filtros = $_POST["filters"];
        $groups = $_POST["groups"];
        $sorteds = $_POST["sorteds"];
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
        ini_set('memory_limit', '1024M');
        set_time_limit('180000');
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4', 'totales' => false),
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => true),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'ci' => array('title' => 'CI', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => true),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar', 'totales' => true),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'gestion' => array('title' => 'Gestion', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => true),
            'turno' => array('title' => 'Turno', 'width' => 10, 'align' => 'C', 'type' => 'numeric', 'totales' => false),
            'modalidad_marcacion' => array('title' => 'Modalidad', 'width' => 30, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'd1' => array('title' => 'Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado1_descripcion' => array('title' => 'Estado Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd2' => array('title' => 'Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado2_descripcion' => array('title' => 'Estado Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd3' => array('title' => 'Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado3_descripcion' => array('title' => 'Estado Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd4' => array('title' => 'Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado4_descripcion' => array('title' => 'Estado Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd5' => array('title' => 'Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado5_descripcion' => array('title' => 'Estado Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd6' => array('title' => 'Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado6_descripcion' => array('title' => 'Estado Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd7' => array('title' => 'Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado7_descripcion' => array('title' => 'Estado Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd8' => array('title' => 'Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado8_descripcion' => array('title' => 'Estado Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd9' => array('title' => 'Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado9_descripcion' => array('title' => 'Estado Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd10' => array('title' => 'Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado10_descripcion' => array('title' => 'Estado Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd11' => array('title' => 'Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado11_descripcion' => array('title' => 'Estado Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd12' => array('title' => 'Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado12_descripcion' => array('title' => 'Estado Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd13' => array('title' => 'Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado13_descripcion' => array('title' => 'Estado Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd14' => array('title' => 'Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado14_descripcion' => array('title' => 'Estado Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd15' => array('title' => 'Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado15_descripcion' => array('title' => 'Estado Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd16' => array('title' => 'Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado16_descripcion' => array('title' => 'Estado Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd17' => array('title' => 'Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado17_descripcion' => array('title' => 'Estado Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd18' => array('title' => 'Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado18_descripcion' => array('title' => 'Estado Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd19' => array('title' => 'Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado19_descripcion' => array('title' => 'Estado Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd20' => array('title' => 'Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado20_descripcion' => array('title' => 'Estado Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd21' => array('title' => 'Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado21_descripcion' => array('title' => 'Estado Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd22' => array('title' => 'Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado22_descripcion' => array('title' => 'Estado Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd23' => array('title' => 'Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado23_descripcion' => array('title' => 'Estado Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd24' => array('title' => 'Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado24_descripcion' => array('title' => 'Estado Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd25' => array('title' => 'Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado25_descripcion' => array('title' => 'Estado Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd26' => array('title' => 'Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado26_descripcion' => array('title' => 'Estado Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd27' => array('title' => 'Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado27_descripcion' => array('title' => 'Estado Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd28' => array('title' => 'Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado28_descripcion' => array('title' => 'Estado Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd29' => array('title' => 'Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado29_descripcion' => array('title' => 'Estado Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd30' => array('title' => 'Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado30_descripcion' => array('title' => 'Estado Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd31' => array('title' => 'Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado31_descripcion' => array('title' => 'Estado Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'ultimo_dia' => array('title' => 'U/Dia', 'width' => 10, 'align' => 'C', 'type' => 'numeric', 'totales' => false),
            'atrasos' => array('title' => 'Atrasos', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'atrasados' => array('title' => 'Atrasados', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'faltas' => array('title' => 'Faltas', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'abandono' => array('title' => 'Abandono', 'width' => 18, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'omision' => array('title' => 'Sin Marcacion', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'lsgh' => array('title' => 'LSGH', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'agrupador' => array('title' => 'Marc. Previstas', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'descanso' => array('title' => 'Descanso', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'compensacion' => array('title' => 'Puntualidad', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'observacion' => array('title' => 'Obs.', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false)
        );
        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if ($fechaIni != "" && $fechaFin != "" && count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte Rango Marcaciones (' . $fechaIni . ' al ' . $fechaFin . ')');
            $excel->title_total_rpt = utf8_decode('Cuadro Resumen de Datos Marcaciones (' . $fechaIni . ' al ' . $fechaFin . ')');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            if ($excel->debug == 1) {
                $hoy = date("Y-m-d H:i:s");
                echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% INICIO: " . $hoy . "</p>";
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^COLUMNAS A MOSTRARSE^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^(" . count($colSelecteds) . ")</p>";
                print_r($colSelecteds);
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
            }
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
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^Rango Fechas^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^</p>";
                echo "Del " . $fechaIni . " al " . $fechaFin;
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::TOTAL COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
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
            $obj = new Frelaboraleshorariosymarcaciones();
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
                    $groups = " ORDER BY " . $groups . ",relaboral_id,gestion,mes,turno,modalidadmarcacion_id";
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
            if ($carnetAux != '') {
                $lstCi = "";
                $lstIdPersonas = "";
                $arrCarnets = explode(",", $carnetAux);
                if (count($arrCarnets) > 0) {
                    foreach ($arrCarnets as $ci) {
                        $lstCi .= "'$ci',";
                    }
                    $lstCi .= ",";
                    $lstCi = str_replace(",,", "", $lstCi);
                    $objP = Personas::Find("ci IN (" . $lstCi . ")");
                    if (is_object($objP)) {
                        foreach ($objP as $p) {
                            $lstIdPersonas .= $p->id . ",";
                        }
                        $lstIdPersonas .= ",";
                        $lstIdPersonas = str_replace(",,", "", $lstIdPersonas);
                    }
                }
                $objHM = new Fplanillasref();
                $arrIdRelaborales = $objHM->getIdRelaboralesEnJsonPorIdPersonas($lstIdPersonas, $fechaIni, $fechaFin);
                $jsonIdRelaborales = '{"0":0}';
                if (is_object($arrIdRelaborales)) {
                    $clave = 0;
                    $jsonIdRelaborales = '{';
                    foreach ($arrIdRelaborales as $reg) {
                        $jsonIdRelaborales .= '"' . $clave . '":' . $reg->id . ',';
                        $clave++;
                    }
                    $jsonIdRelaborales .= ',';
                    $jsonIdRelaborales = str_replace(",,", "", $jsonIdRelaborales);
                    $jsonIdRelaborales .= '}';
                }
                $jsonIdRelaborales = str_replace('{,}', '{"0":0}', $jsonIdRelaborales);
            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->getAllByRangeTwoMonth($jsonIdRelaborales, $fechaIni, $fechaFin, $where, $groups);
            $arrTotales = array();
            $horariosymarcaciones = array();
            $arrPrevistaMasEfectiva = array();
            $totalAtrasos = $totalAtrasados = $totalFaltas = $totalAbandono = $totalOmision = $totalLsgh = $totalAgrupador = $totalDescanso = $totalCompensacion = 0;
            $almacenado = array();
            /**
             * Se establece esta variable a objeto de mantener una numeración por registro laboral.
             */
            foreach ($resul as $v) {

                #region Cálculo del índice de puntualidad y personas que hacen horas extras
                $almacenado[1][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d1;
                $almacenado[2][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d2;
                $almacenado[3][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d3;
                $almacenado[4][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d4;
                $almacenado[5][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d5;
                $almacenado[6][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d6;
                $almacenado[7][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d7;
                $almacenado[8][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d8;
                $almacenado[9][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d9;
                $almacenado[10][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d10;
                $almacenado[11][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d11;
                $almacenado[12][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d12;
                $almacenado[13][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d13;
                $almacenado[14][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d14;
                $almacenado[15][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d15;
                $almacenado[16][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d16;
                $almacenado[17][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d17;
                $almacenado[18][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d18;
                $almacenado[19][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d19;
                $almacenado[20][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d20;
                $almacenado[21][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d21;
                $almacenado[22][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d22;
                $almacenado[23][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d23;
                $almacenado[24][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d24;
                $almacenado[25][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d25;
                $almacenado[26][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d26;
                $almacenado[27][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d27;
                $almacenado[28][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d28;
                $almacenado[29][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d29;
                $almacenado[30][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d30;
                $almacenado[31][$v->turno][$v->grupo][$v->modalidadmarcacion_id] = $v->d31;
                $puntualidad = 0;
                $puntExtra[1] = $puntExtra[2] = $puntExtra[3] = $puntExtra[4] = $puntExtra[5] = $puntExtra[6] = $puntExtra[7] = $puntExtra[8] = $puntExtra[9] = $puntExtra[10] = "";
                $puntExtra[11] = $puntExtra[12] = $puntExtra[13] = $puntExtra[14] = $puntExtra[15] = $puntExtra[16] = $puntExtra[17] = $puntExtra[18] = $puntExtra[19] = $puntExtra[20] = "";
                $puntExtra[21] = $puntExtra[22] = $puntExtra[23] = $puntExtra[24] = $puntExtra[25] = $puntExtra[26] = $puntExtra[27] = $puntExtra[28] = $puntExtra[29] = $puntExtra[30] = $puntExtra[31] = "";
                if ($v->modalidadmarcacion_id == 3) {
                    if (isset($almacenado[1][$v->turno][$v->grupo][3]) && $almacenado[1][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[1] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[2][$v->turno][$v->grupo][3]) && $almacenado[2][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[2] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[3][$v->turno][$v->grupo][3]) && $almacenado[3][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[3] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[4][$v->turno][$v->grupo][3]) && $almacenado[4][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[4] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[5][$v->turno][$v->grupo][3]) && $almacenado[5][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[5] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[6][$v->turno][$v->grupo][3]) && $almacenado[6][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[6] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[7][$v->turno][$v->grupo][3]) && $almacenado[7][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[7] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[8][$v->turno][$v->grupo][3]) && $almacenado[8][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[8] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[9][$v->turno][$v->grupo][3]) && $almacenado[9][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[9] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[10][$v->turno][$v->grupo][3]) && $almacenado[10][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[10] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[11][$v->turno][$v->grupo][3]) && $almacenado[11][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[11] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[12][$v->turno][$v->grupo][3]) && $almacenado[12][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[12] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[13][$v->turno][$v->grupo][3]) && $almacenado[13][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[13] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[14][$v->turno][$v->grupo][3]) && $almacenado[14][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[14] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[15][$v->turno][$v->grupo][3]) && $almacenado[15][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[15] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[16][$v->turno][$v->grupo][3]) && $almacenado[16][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[16] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[17][$v->turno][$v->grupo][3]) && $almacenado[17][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[17] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[18][$v->turno][$v->grupo][3]) && $almacenado[18][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[18] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[19][$v->turno][$v->grupo][3]) && $almacenado[19][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[19] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[20][$v->turno][$v->grupo][3]) && $almacenado[20][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[20] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[21][$v->turno][$v->grupo][3]) && $almacenado[21][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[21] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[22][$v->turno][$v->grupo][3]) && $almacenado[22][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[22] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[23][$v->turno][$v->grupo][3]) && $almacenado[23][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[23] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[24][$v->turno][$v->grupo][3]) && $almacenado[24][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[24] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[25][$v->turno][$v->grupo][3]) && $almacenado[25][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[25] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[26][$v->turno][$v->grupo][3]) && $almacenado[26][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[26] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[27][$v->turno][$v->grupo][3]) && $almacenado[27][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[27] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[28][$v->turno][$v->grupo][3]) && $almacenado[28][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[28] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[29][$v->turno][$v->grupo][3]) && $almacenado[29][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[29] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[30][$v->turno][$v->grupo][3]) && $almacenado[30][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[30] = " 1 ";
                        $puntualidad++;
                    }
                    if (isset($almacenado[31][$v->turno][$v->grupo][3]) && $almacenado[31][$v->turno][$v->grupo][3] == "00:00:00") {
                        $puntExtra[31] = " 1 ";
                        $puntualidad++;
                    }
                }
                $horariosymarcacionesPuntualidad = array(
                    #region Columnas de procedimiento f_relaborales()
                    'id_relaboral' => $v->relaboral_id,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'cargo' => $v->cargo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'condicion' => $v->condicion,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'area' => $v->area,
                    'ubicacion' => $v->ubicacion,
                    #endregion Columnas de procedimiento f_relaborales()

                    'id' => $v->id_horarioymarcacion,
                    'relaboral_id' => $v->relaboral_id,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'turno' => $v->turno,
                    'grupo' => $v->grupo,
                    'clasemarcacion' => "p",
                    'clasemarcacion_descripcion' => "PUNTUALIDAD / OTROS",
                    'modalidadmarcacion_id' => 8,
                    'modalidad_marcacion' => ($v->modalidadmarcacion_id == 3) ? "PUNTUALIDAD" : "OTROS",
                    'd1' => $puntExtra[1],
                    'calendariolaboral1_id' => $v->calendariolaboral1_id,
                    'd2' => $puntExtra[2],
                    'calendariolaboral2_id' => $v->calendariolaboral2_id,
                    'd3' => $puntExtra[3],
                    'calendariolaboral3_id' => $v->calendariolaboral3_id,
                    'd4' => $puntExtra[4],
                    'calendariolaboral4_id' => $v->calendariolaboral4_id,
                    'd5' => $puntExtra[5],
                    'calendariolaboral5_id' => $v->calendariolaboral5_id,
                    'd6' => $puntExtra[6],
                    'calendariolaboral6_id' => $v->calendariolaboral6_id,
                    'd7' => $puntExtra[7],
                    'calendariolaboral7_id' => $v->calendariolaboral7_id,
                    'd8' => $puntExtra[8],
                    'calendariolaboral8_id' => $v->calendariolaboral8_id,
                    'd9' => $puntExtra[9],
                    'calendariolaboral9_id' => $v->calendariolaboral9_id,
                    'd10' => $puntExtra[10],
                    'calendariolaboral10_id' => $v->calendariolaboral10_id,
                    'd11' => $puntExtra[11],
                    'calendariolaboral11_id' => $v->calendariolaboral11_id,
                    'd12' => $puntExtra[12],
                    'calendariolaboral12_id' => $v->calendariolaboral12_id,
                    'd13' => $puntExtra[13],
                    'calendariolaboral13_id' => $v->calendariolaboral13_id,
                    'd14' => $puntExtra[14],
                    'calendariolaboral14_id' => $v->calendariolaboral14_id,
                    'd15' => $puntExtra[15],
                    'calendariolaboral15_id' => $v->calendariolaboral15_id,
                    'd16' => $puntExtra[16],
                    'calendariolaboral16_id' => $v->calendariolaboral16_id,
                    'd17' => $puntExtra[17],
                    'calendariolaboral17_id' => $v->calendariolaboral17_id,
                    'd18' => $puntExtra[18],
                    'calendariolaboral18_id' => $v->calendariolaboral18_id,
                    'd19' => $puntExtra[19],
                    'calendariolaboral19_id' => $v->calendariolaboral19_id,
                    'd20' => $puntExtra[20],
                    'calendariolaboral20_id' => $v->calendariolaboral20_id,
                    'd21' => $puntExtra[21],
                    'calendariolaboral21_id' => $v->calendariolaboral21_id,
                    'd22' => $puntExtra[22],
                    'calendariolaboral22_id' => $v->calendariolaboral22_id,
                    'd23' => $puntExtra[23],
                    'calendariolaboral23_id' => $v->calendariolaboral23_id,
                    'd24' => $puntExtra[24],
                    'calendariolaboral24_id' => $v->calendariolaboral24_id,
                    'd25' => $puntExtra[25],
                    'calendariolaboral25_id' => $v->calendariolaboral25_id,
                    'd26' => $puntExtra[26],
                    'calendariolaboral26_id' => $v->calendariolaboral26_id,
                    'd27' => $puntExtra[27],
                    'calendariolaboral27_id' => $v->calendariolaboral27_id,
                    'd28' => $puntExtra[28],
                    'calendariolaboral28_id' => $v->calendariolaboral28_id,
                    'd29' => $puntExtra[29],
                    'calendariolaboral29_id' => $v->calendariolaboral29_id,
                    'd30' => $puntExtra[30],
                    'calendariolaboral30_id' => $v->calendariolaboral30_id,
                    'd31' => $puntExtra[31],
                    'calendariolaboral31_id' => $v->calendariolaboral31_id,
                    'ultimo_dia' => $v->ultimo_dia,
                    'atrasos' => null,
                    'atrasados' => null,
                    'faltas' => null,
                    'abandono' => null,
                    'omision' => null,
                    'lsgh' => null,
                    'compensacion' => $puntualidad,
                    'descanso' => null,
                    'puntualidad' => null,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => null,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_apr_id' => $v->user_apr_id,
                    'fecha_apr' => $v->fecha_apr,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                );
                /**
                 * Se usará el valor almacenado en $puntualidad para definir la compensación
                 */
                $compensacion = $puntualidad;
                $totalCompensacion += $compensacion;
                if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] > 0) {
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] + $compensacion;
                } else {
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $compensacion;
                }
                #endregion Cálculo del índice de puntualidad y personas que hacen horas extras
                #region Sector para almacenamiento de los totales
                if ($v->modalidadmarcacion_id == 3 || $v->modalidadmarcacion_id == 6) {
                    $faltas = $abandono = $omision = $lsgh = $compensacion = $puntualidad = 0;
                    /**
                     * Se marca al registro de relación laboral para conocer quienes tienen el cálculo Previsto y Efectivo realizado.
                     */
                    $arrPrevistaMasEfectiva[$v->relaboral_id][$v->gestion][$v->mes] = 1;
                    if ($v->faltas != '') {
                        $faltas = $v->faltas;
                    }
                    if ($v->abandono != '') {
                        $abandono = $v->abandono;
                    }
                    if ($v->omision != '') {
                        $omision = $v->omision;
                    }
                    if ($v->lsgh != '') {
                        $lsgh = $v->lsgh;
                    }
                    /*if ($v->compensacion != '') {
                        $compensacion = $v->compensacion;
                    }*/
                    $totalFaltas += $faltas;
                    $totalAbandono += $abandono;
                    $totalOmision += $omision;
                    $totalLsgh += $lsgh;
                    $totalCompensacion += $puntualidad;
                    //$totalPuntualidad += $puntualidad;

                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["id_relaboral"] = $v->relaboral_id;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["nombres"] = $v->nombres;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ci"] = $v->ci;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["expd"] = $v->expd;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gestion"] = $v->gestion;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes"] = $v->mes;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes_nombre"] = $v->mes_nombre;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["condicion"] = $v->condicion;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gerencia_administrativa"] = $v->gerencia_administrativa;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["departamento_administrativo"] = $v->departamento_administrativo;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["area"] = $v->area;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ubicacion"] = $v->ubicacion;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["cargo"] = $v->cargo;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["sueldo"] = $v->sueldo;
                    /*echo "<p>---------------------------------------------------------------------</p>";
                    echo "1) ".$atrasos.", 2) ".$faltas.", 3) ".$abandono.", 4) ".$omision.", 5) ".$lsgh.", 6) ".$compensacion;
                    echo "<p>---------------------------------------------------------------------</p>";*/

                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] + $faltas;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] = $faltas;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] + $abandono;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] = $abandono;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] + $omision;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] = $omision;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] + $lsgh;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] = $lsgh;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] + $compensacion;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $compensacion;
                    }
                }

                /**
                 * Sumatoria de las marcaciones previstas por persona
                 */
                if ($v->clasemarcacion == 'T') {
                    $atrasos = $agrupador = $atrasados = $descanso = $compensacion = 0;
                    if ($v->atrasos != '') {
                        $atrasos = $v->atrasos;
                    }
                    if ($v->agrupador != '') {
                        $agrupador = $v->agrupador;
                    }
                    if ($v->atrasados != '') {
                        $atrasados = $v->atrasados;
                    }
                    if ($v->descanso != '') {
                        $descanso = $v->descanso;
                    }
                    if ($puntualidad > 0) {
                        $compensacion = $puntualidad;
                    }
                    $totalAtrasos += $atrasos;
                    $totalAgrupador += $agrupador;
                    $totalAtrasados += $atrasados;
                    $totalDescanso += $descanso;
                    $totalCompensacion += $compensacion;
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] + $atrasos;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] = $atrasos;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] + $agrupador;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] = $agrupador;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] + $atrasados;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] = $atrasados;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] + $descanso;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] = $descanso;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] + $compensacion;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $compensacion;
                    }
                    /**
                     * Si no se ha instanciado la variable de totales se debe al menos poner valores que alerten de la ausencia de marcación efectiva.
                     * Pudiendo ser la razón la existencia de descanso o la inexistencia de calendario para esa persona en ese mes.
                     * Se agrega la gestión y mes.
                     */
                    if (!isset($arrPrevistaMasEfectiva[$v->relaboral_id][$v->gestion][$v->mes])) {

                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["id_relaboral"] = $v->relaboral_id;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["nombres"] = $v->nombres;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ci"] = $v->ci;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["expd"] = $v->expd;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gestion"] = $v->gestion;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes"] = $v->mes;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes_nombre"] = $v->mes_nombre;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["condicion"] = $v->condicion;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gerencia_administrativa"] = $v->gerencia_administrativa;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["departamento_administrativo"] = $v->departamento_administrativo;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["area"] = $v->area;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ubicacion"] = $v->ubicacion;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["cargo"] = $v->cargo;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["sueldo"] = $v->sueldo;

                        /**
                         * Se establece este valor para dar una alerta del error
                         */
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] = 31;
                        $totalFaltas += 31;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] = 0;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] = 0;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] = 0;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = 0;
                    }
                }
                $horariosymarcaciones[] = array(
                    #region Columnas de procedimiento f_relaborales()
                    'id_relaboral' => $v->relaboral_id,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => trim($v->expd),
                    'cargo' => $v->cargo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'condicion' => $v->condicion,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'area' => $v->area,
                    'ubicacion' => $v->ubicacion,
                    #endregion Columnas de procedimiento f_relaborales()

                    'id' => $v->id_horarioymarcacion,
                    'relaboral_id' => $v->relaboral_id,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'turno' => $v->turno,
                    'grupo' => $v->grupo,
                    'clasemarcacion' => $v->clasemarcacion,
                    'clasemarcacion_descripcion' => $v->clasemarcacion_descripcion,
                    'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                    'modalidad_marcacion' => $v->modalidad_marcacion,
                    'd1' => $v->d1,
                    'calendariolaboral1_id' => $v->calendariolaboral1_id,
                    'd2' => $v->d2,
                    'calendariolaboral2_id' => $v->calendariolaboral2_id,
                    'd3' => $v->d3,
                    'calendariolaboral3_id' => $v->calendariolaboral3_id,
                    'd4' => $v->d4,
                    'calendariolaboral4_id' => $v->calendariolaboral4_id,
                    'd5' => $v->d5,
                    'calendariolaboral5_id' => $v->calendariolaboral5_id,
                    'd6' => $v->d6,
                    'calendariolaboral6_id' => $v->calendariolaboral6_id,
                    'd7' => $v->d7,
                    'calendariolaboral7_id' => $v->calendariolaboral7_id,
                    'd8' => $v->d8,
                    'calendariolaboral8_id' => $v->calendariolaboral8_id,
                    'd9' => $v->d9,
                    'calendariolaboral9_id' => $v->calendariolaboral9_id,
                    'd10' => $v->d10,
                    'calendariolaboral10_id' => $v->calendariolaboral10_id,
                    'd11' => $v->d11,
                    'calendariolaboral11_id' => $v->calendariolaboral11_id,
                    'd12' => $v->d12,
                    'calendariolaboral12_id' => $v->calendariolaboral12_id,
                    'd13' => $v->d13,
                    'calendariolaboral13_id' => $v->calendariolaboral13_id,
                    'd14' => $v->d14,
                    'calendariolaboral14_id' => $v->calendariolaboral14_id,
                    'd15' => $v->d15,
                    'calendariolaboral15_id' => $v->calendariolaboral15_id,
                    'd16' => $v->d16,
                    'calendariolaboral16_id' => $v->calendariolaboral16_id,
                    'd17' => $v->d17,
                    'calendariolaboral17_id' => $v->calendariolaboral17_id,
                    'd18' => $v->d18,
                    'calendariolaboral18_id' => $v->calendariolaboral18_id,
                    'd19' => $v->d19,
                    'calendariolaboral19_id' => $v->calendariolaboral19_id,
                    'd20' => $v->d20,
                    'calendariolaboral20_id' => $v->calendariolaboral20_id,
                    'd21' => $v->d21,
                    'calendariolaboral21_id' => $v->calendariolaboral21_id,
                    'd22' => $v->d22,
                    'calendariolaboral22_id' => $v->calendariolaboral22_id,
                    'd23' => $v->d23,
                    'calendariolaboral23_id' => $v->calendariolaboral23_id,
                    'd24' => $v->d24,
                    'calendariolaboral24_id' => $v->calendariolaboral24_id,
                    'd25' => $v->d25,
                    'calendariolaboral25_id' => $v->calendariolaboral25_id,
                    'd26' => $v->d26,
                    'calendariolaboral26_id' => $v->calendariolaboral26_id,
                    'd27' => $v->d27,
                    'calendariolaboral27_id' => $v->calendariolaboral27_id,
                    'd28' => $v->d28,
                    'calendariolaboral28_id' => $v->calendariolaboral28_id,
                    'd29' => $v->d29,
                    'calendariolaboral29_id' => $v->calendariolaboral29_id,
                    'd30' => $v->d30,
                    'calendariolaboral30_id' => $v->calendariolaboral30_id,
                    'd31' => $v->d31,
                    'calendariolaboral31_id' => $v->calendariolaboral31_id,
                    'ultimo_dia' => $v->ultimo_dia,
                    'atrasos' => $v->atrasos,
                    'atrasados' => $v->atrasados,
                    'faltas' => $v->faltas,
                    'abandono' => $v->abandono,
                    'omision' => $v->omision,
                    'lsgh' => $v->lsgh,
                    'compensacion' => ($v->clasemarcacion == 'T') ? $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] : $v->compensacion,
                    'descanso' => $v->descanso,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_apr_id' => $v->user_apr_id,
                    'fecha_apr' => $v->fecha_apr,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                );
                #endregion Sector para almacenamiento de los totales
                #region Sector para adicionar una fila para Excepciones
                if ($v->modalidadmarcacion_id == 3 || $v->modalidadmarcacion_id == 6) {
                    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = $d8 = $d9 = $d10 = $d11 = $d12 = $d13 = $d14 = $d15 = $d16 = $d17 = $d18 = $d19 = $d20 = $d21 = $d22 = $d23 = $d24 = $d25 = $d26 = $d27 = $d28 = $d29 = $d30 = $d30 = $d31 = "";
                    if ($v->calendariolaboral1_id > 0) {
                        $d1 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 1, $v->calendariolaboral1_id, 1);
                    }
                    if ($v->calendariolaboral2_id > 0) {
                        $d2 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 2, $v->calendariolaboral2_id, 1);
                    }
                    if ($v->calendariolaboral3_id > 0) {
                        $d3 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 3, $v->calendariolaboral3_id, 1);
                    }
                    if ($v->calendariolaboral4_id > 0) {
                        $d4 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 4, $v->calendariolaboral4_id, 1);
                    }
                    if ($v->calendariolaboral5_id > 0) {
                        $d5 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 5, $v->calendariolaboral5_id, 1);
                    }
                    if ($v->calendariolaboral6_id > 0) {
                        $d6 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 6, $v->calendariolaboral6_id, 1);
                    }
                    if ($v->calendariolaboral7_id > 0) {
                        $d7 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 7, $v->calendariolaboral7_id, 1);
                    }
                    if ($v->calendariolaboral8_id > 0) {
                        $d8 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 8, $v->calendariolaboral8_id, 1);
                    }
                    if ($v->calendariolaboral9_id > 0) {
                        $d9 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 9, $v->calendariolaboral9_id, 1);
                    }
                    if ($v->calendariolaboral10_id > 0) {
                        $d10 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 10, $v->calendariolaboral10_id, 1);
                    }
                    if ($v->calendariolaboral11_id > 0) {
                        $d11 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 11, $v->calendariolaboral11_id, 1);
                    }
                    if ($v->calendariolaboral12_id > 0) {
                        $d12 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 12, $v->calendariolaboral12_id, 1);
                    }
                    if ($v->calendariolaboral13_id > 0) {
                        $d13 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 13, $v->calendariolaboral13_id, 1);
                    }
                    if ($v->calendariolaboral14_id > 0) {
                        $d14 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 14, $v->calendariolaboral14_id, 1);
                    }
                    if ($v->calendariolaboral15_id > 0) {
                        $d15 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 15, $v->calendariolaboral15_id, 1);
                    }
                    if ($v->calendariolaboral16_id > 0) {
                        $d16 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 16, $v->calendariolaboral16_id, 1);
                    }
                    if ($v->calendariolaboral17_id > 0) {
                        $d17 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 17, $v->calendariolaboral17_id, 1);
                    }
                    if ($v->calendariolaboral18_id > 0) {
                        $d18 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 18, $v->calendariolaboral18_id, 1);
                    }
                    if ($v->calendariolaboral19_id > 0) {
                        $d19 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 19, $v->calendariolaboral19_id, 1);
                    }
                    if ($v->calendariolaboral20_id > 0) {
                        $d20 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 20, $v->calendariolaboral20_id, 1);
                    }
                    if ($v->calendariolaboral21_id > 0) {
                        $d21 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 21, $v->calendariolaboral21_id, 1);
                    }
                    if ($v->calendariolaboral22_id > 0) {
                        $d22 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 22, $v->calendariolaboral22_id, 1);
                    }
                    if ($v->calendariolaboral23_id > 0) {
                        $d23 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 23, $v->calendariolaboral23_id, 1);
                    }
                    if ($v->calendariolaboral24_id > 0) {
                        $d24 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 24, $v->calendariolaboral24_id, 1);
                    }
                    if ($v->calendariolaboral25_id > 0) {
                        $d25 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 25, $v->calendariolaboral25_id, 1);
                    }
                    if ($v->calendariolaboral26_id > 0) {
                        $d26 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 26, $v->calendariolaboral26_id, 1);
                    }
                    if ($v->calendariolaboral27_id > 0) {
                        $d27 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 27, $v->calendariolaboral27_id, 1);
                    }
                    if ($v->calendariolaboral28_id > 0) {
                        $d28 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 28, $v->calendariolaboral28_id, 1);
                    }
                    if ($v->calendariolaboral29_id > 0) {
                        $d29 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 29, $v->calendariolaboral29_id, 1);
                    }
                    if ($v->calendariolaboral30_id > 0) {
                        $d30 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 30, $v->calendariolaboral30_id, 1);
                    }
                    if ($v->calendariolaboral31_id > 0) {
                        $d31 = $obj->obtenerExcepcionesYFeriadosEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 31, $v->calendariolaboral31_id, 1);
                    }

                    $horariosymarcaciones[] = array(
                        #region Columnas de procedimiento f_relaborales()
                        'id_relaboral' => $v->relaboral_id,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'cargo' => $v->cargo,
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'condicion' => $v->condicion,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'area' => $v->area,
                        'ubicacion' => $v->ubicacion,
                        #endregion Columnas de procedimiento f_relaborales()

                        'id' => $v->id_horarioymarcacion,
                        'relaboral_id' => $v->relaboral_id,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'turno' => $v->turno,
                        'grupo' => $v->grupo,
                        'clasemarcacion' => "e",
                        'clasemarcacion_descripcion' => "EXCEPCIONES / FERIADOS",
                        'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                        'modalidad_marcacion' => "EXCEPCIONES / FERIADOS",
                        'd1' => $d1,
                        'calendariolaboral1_id' => $v->calendariolaboral1_id,
                        'd2' => $d2,
                        'calendariolaboral2_id' => $v->calendariolaboral2_id,
                        'd3' => $d3,
                        'calendariolaboral3_id' => $v->calendariolaboral3_id,
                        'd4' => $d4,
                        'calendariolaboral4_id' => $v->calendariolaboral4_id,
                        'd5' => $d5,
                        'calendariolaboral5_id' => $v->calendariolaboral5_id,
                        'd6' => $d6,
                        'calendariolaboral6_id' => $v->calendariolaboral6_id,
                        'd7' => $d7,
                        'calendariolaboral7_id' => $v->calendariolaboral7_id,
                        'd8' => $d8,
                        'calendariolaboral8_id' => $v->calendariolaboral8_id,
                        'd9' => $d9,
                        'calendariolaboral9_id' => $v->calendariolaboral9_id,
                        'd10' => $d10,
                        'calendariolaboral10_id' => $v->calendariolaboral10_id,
                        'd11' => $d11,
                        'calendariolaboral11_id' => $v->calendariolaboral11_id,
                        'd12' => $d12,
                        'calendariolaboral12_id' => $v->calendariolaboral12_id,
                        'd13' => $d13,
                        'calendariolaboral13_id' => $v->calendariolaboral13_id,
                        'd14' => $d14,
                        'calendariolaboral14_id' => $v->calendariolaboral14_id,
                        'd15' => $d15,
                        'calendariolaboral15_id' => $v->calendariolaboral15_id,
                        'd16' => $d16,
                        'calendariolaboral16_id' => $v->calendariolaboral16_id,
                        'd17' => $d17,
                        'calendariolaboral17_id' => $v->calendariolaboral17_id,
                        'd18' => $d18,
                        'calendariolaboral18_id' => $v->calendariolaboral18_id,
                        'd19' => $d19,
                        'calendariolaboral19_id' => $v->calendariolaboral19_id,
                        'd20' => $d20,
                        'calendariolaboral20_id' => $v->calendariolaboral20_id,
                        'd21' => $d21,
                        'calendariolaboral21_id' => $v->calendariolaboral21_id,
                        'd22' => $d22,
                        'calendariolaboral22_id' => $v->calendariolaboral22_id,
                        'd23' => $d23,
                        'calendariolaboral23_id' => $v->calendariolaboral23_id,
                        'd24' => $d24,
                        'calendariolaboral24_id' => $v->calendariolaboral24_id,
                        'd25' => $d25,
                        'calendariolaboral25_id' => $v->calendariolaboral25_id,
                        'd26' => $d26,
                        'calendariolaboral26_id' => $v->calendariolaboral26_id,
                        'd27' => $d27,
                        'calendariolaboral27_id' => $v->calendariolaboral27_id,
                        'd28' => $d28,
                        'calendariolaboral28_id' => $v->calendariolaboral28_id,
                        'd29' => $d29,
                        'calendariolaboral29_id' => $v->calendariolaboral29_id,
                        'd30' => $d30,
                        'calendariolaboral30_id' => $v->calendariolaboral30_id,
                        'd31' => $d31,
                        'calendariolaboral31_id' => $v->calendariolaboral31_id,
                        'ultimo_dia' => $v->ultimo_dia,
                        'atrasos' => null,
                        'atrasados' => null,
                        'faltas' => null,
                        'abandono' => null,
                        'omision' => null,
                        'lsgh' => null,
                        'compensacion' => null,
                        'descanso' => null,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => null,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_apr_id' => $v->user_apr_id,
                        'fecha_apr' => $v->fecha_apr,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                    );
                    $horariosymarcaciones[] = $horariosymarcacionesPuntualidad;
                }
                #endregion sector para adicionar una fila para Excepciones
            }
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
            if (count($horariosymarcaciones) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($horariosymarcaciones);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                $arrCis = Array();
                $numeradorRelaboral = 0;
                foreach ($horariosymarcaciones as $i => $val) {
                    /**
                     * Se agrega un control para modificar el contandor cuando se cambio de contrato.
                     */
                    if (isset($val["relaboral_id"]) && !in_array($val["relaboral_id"], $arrCis)) {
                        $arrCis[] = $val["relaboral_id"];
                        $numeradorRelaboral++;
                    }

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
                        $rowData = $excel->DefineRows($numeradorRelaboral, $horariosymarcaciones[$j], $colSelecteds);
                        //Se modifica el numerador de filas debido a que se requiere un numerador por registro de relación laboral
                        //$rowData = $excel->DefineRows($j + 1, $horariosymarcaciones[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($numeradorRelaboral, $val, $colSelecteds);
                        //Se modifica el numerador de filas debido a que se requiere un numerador por registro de relación laboral
                        //$rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $celdacolores = array();
                        if (in_array("EXCEPCIONES / FERIADOS", $rowData)) {
                            if ($excel->debug == 1) {
                                echo "<p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>";
                            }
                            for ($i = 1; $i <= 31; $i++) {
                                if (in_array("d" . $i, $colSelecteds)) {
                                    $clave = array_search("d" . $i, $colSelecteds);
                                    if (isset($rowData[$clave]) && $rowData[$clave] != '') {
                                        $celdacolores[$clave] = "FF00FF00";
                                    }
                                }
                            }
                            if ($excel->debug == 1) {
                                print_r($celdacolores);
                                echo "<p>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</p>";
                            }
                        }
                        if (in_array("SUMATORIA DE RETRASOS", $rowData) && !in_array("OTROS", $rowData)) {
                            if ($excel->debug == 1) {
                                echo "<p><" . (in_array("SUMATORIA", $rowData)) . "> 888888888888888888888888888888888888888888888888888888888888 COLORES CELDA TOTALES 888888888888888888888888888888888888888888888888888888888888888888888888888888</p>";
                            }
                            for ($i = 0; $i < count($rowData); $i++) {
                                $celdacolores[] = "99D9EA";
                            }
                            if ($excel->debug == 1) {
                                print_r($celdacolores);
                                echo "<p>8888888888888888888888888888888888888888888888888888888888888CONTENIDO DEL ROW DATA 888888888888888888888888888888888888888888888888888888888888888888888888888888</p>";
                                print_r($rowData);
                                echo "<p>8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888</p>";
                            }
                        }

                        if ($excel->debug == 1) {
                            echo "<p>##################################################################################################</p>";
                            print_r($arrPrevistaMasEfectiva);
                            echo "<p>##################################################################################################</p>";
                        }
                        if ($val["relaboral_id"] > 0 && !isset($arrPrevistaMasEfectiva[$val["relaboral_id"]])) {
                            for ($i = 1; $i <= 31; $i++) {
                                if (in_array("d" . $i, $colSelecteds)) {
                                    $clave = array_search("d" . $i, $colSelecteds);
                                    if (isset($rowData[$clave]) && $rowData[$clave] != '') {
                                        $celdacolores[$clave] = "F74507";
                                    }
                                }
                            }
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila, $celdacolores);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $excel->setWidthForColumns();
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                $totalColSelecteds = $excel->DefineSelectedTotalCols($generalConfigForAllColumns);
                $totalTitleColSelecteds = $excel->DefineTotalTitleCols($generalConfigForAllColumns, $totalColSelecteds);

                if ($excel->debug == 1) {
                    echo "<P>************************************************************TOTALES*****************************************</P>";
                    print_r($arrTotales);
                    echo "<P>*********************************************COLUMNAS SELECCIONADAS*****************************************</P>";
                    print_r($totalColSelecteds);
                    echo "<P>*****************************************TITULOS DE COLUMNAS SELECCIONADAS*****************************************</P>";
                    print_r($totalTitleColSelecteds);
                    echo "<P>*****************************************************************************************************************</P>";
                    $hoy = date("Y-m-d H:i:s");
                    echo "<p>%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% INICIO: " . $hoy . "</p>";

                } else {
                    $excel->agregarPaginaTotales($arrTotales, $totalColSelecteds, $totalTitleColSelecteds, $totalAtrasos, $totalAtrasados, $totalFaltas, $totalAbandono, $totalOmision, $totalLsgh, $totalAgrupador, $totalDescanso, $totalCompensacion);
                }
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_marcaciones.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para el despliegue del reporte de marcaciones en formato PDF.
     * @param $idRelaboral
     * @param $n_rows
     * @param $columns
     * @param $filtros
     * @param $groups
     * @param $sorteds
     */
    public function exportmarcacionespdfAction($idRelaboral, $n_rows, $columns, $filtros, $groups, $sorteds)
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
            'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'gestion' => array('title' => 'Gestion', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'turno' => array('title' => 'Turno', 'width' => 10, 'align' => 'C', 'type' => 'numeric'),
            'modalidad_marcacion' => array('title' => 'Modalidad', 'width' => 30, 'align' => 'C', 'type' => 'varchar'),
            'd1' => array('title' => 'Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado1_descripcion' => array('title' => 'Estado Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd2' => array('title' => 'Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado2_descripcion' => array('title' => 'Estado Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd3' => array('title' => 'Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado3_descripcion' => array('title' => 'Estado Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd4' => array('title' => 'Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado4_descripcion' => array('title' => 'Estado Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd5' => array('title' => 'Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado5_descripcion' => array('title' => 'Estado Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd6' => array('title' => 'Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado6_descripcion' => array('title' => 'Estado Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd7' => array('title' => 'Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado7_descripcion' => array('title' => 'Estado Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd8' => array('title' => 'Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado8_descripcion' => array('title' => 'Estado Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd9' => array('title' => 'Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado9_descripcion' => array('title' => 'Estado Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd10' => array('title' => 'Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado10_descripcion' => array('title' => 'Estado Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd11' => array('title' => 'Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado11_descripcion' => array('title' => 'Estado Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd12' => array('title' => 'Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado12_descripcion' => array('title' => 'Estado Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd13' => array('title' => 'Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado13_descripcion' => array('title' => 'Estado Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd14' => array('title' => 'Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado14_descripcion' => array('title' => 'Estado Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd15' => array('title' => 'Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado15_descripcion' => array('title' => 'Estado Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd16' => array('title' => 'Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado16_descripcion' => array('title' => 'Estado Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd17' => array('title' => 'Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado17_descripcion' => array('title' => 'Estado Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd18' => array('title' => 'Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado18_descripcion' => array('title' => 'Estado Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd19' => array('title' => 'Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado19_descripcion' => array('title' => 'Estado Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd20' => array('title' => 'Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado20_descripcion' => array('title' => 'Estado Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd21' => array('title' => 'Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado21_descripcion' => array('title' => 'Estado Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd22' => array('title' => 'Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado22_descripcion' => array('title' => 'Estado Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd23' => array('title' => 'Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado23_descripcion' => array('title' => 'Estado Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd24' => array('title' => 'Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado24_descripcion' => array('title' => 'Estado Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd25' => array('title' => 'Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado25_descripcion' => array('title' => 'Estado Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd26' => array('title' => 'Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado26_descripcion' => array('title' => 'Estado Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd27' => array('title' => 'Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado27_descripcion' => array('title' => 'Estado Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd28' => array('title' => 'Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado28_descripcion' => array('title' => 'Estado Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd29' => array('title' => 'Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado29_descripcion' => array('title' => 'Estado Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd30' => array('title' => 'Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado30_descripcion' => array('title' => 'Estado Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd31' => array('title' => 'Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            /*'estado31_descripcion' => array('title' => 'Estado Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'ultimo_dia' => array('title' => 'U/Dia', 'width' => 10, 'align' => 'C', 'type' => 'numeric'),
            'atrasos' => array('title' => 'Atrasos', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'atrasados' => array('title' => 'Atrasados', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'faltas' => array('title' => 'Faltas', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'abandono' => array('title' => 'Abandono', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'omision' => array('title' => 'Omision', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'lsgh' => array('title' => 'LSGH', 'width' => 20, 'align' => 'C', 'type' => 'numeric'),
            'agrupador' => array('title' => 'Marc. Previstas', 'width' => 15, 'align' => 'C', 'type' => 'numeric'),
            'descanso' => array('title' => 'Desc.', 'width' => 15, 'align' => 'C', 'type' => 'numeric'),
            'observacion' => array('title' => 'Obs.', 'width' => 30, 'align' => 'L', 'type' => 'varchar')
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
            $objR = new Frelaborales();
            $relaboral = $objR->getOne($idRelaboral);
            $pdf->title_rpt = utf8_decode('Reporte Marcaciones "' . $relaboral[0]->nombres . '"');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            if ($pdf->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^COLUMNAS A MOSTRARSE^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^(" . count($colSelecteds) . ")</p>";
                print_r($colSelecteds);
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
            }
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^idRelaboral^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>" . $idRelaboral;
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::TOTAL COLUMNAS::::::::::::::::::::::::::::::::::::::::::(" . count($columns) . ")<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::(" . count($filtros) . ")<p>";
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
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
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
            $obj = new Fhorariosymarcaciones();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                /*if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }*/
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
                    $groups = " ORDER BY " . $groups . ",relaboral_id,gestion,mes,turno,modalidadmarcacion_id";
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
            $resul = $obj->getAllFromOneRelaboral($idRelaboral, $where, $groups);

            $horariosymarcaciones = array();
            foreach ($resul as $v) {
                $horariosymarcaciones[] = array(
                    'id' => $v->id_horarioymarcacion,
                    'relaboral_id' => $v->relaboral_id,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'turno' => $v->turno,
                    'grupo' => $v->grupo,
                    'clasemarcacion' => $v->clasemarcacion,
                    'clasemarcacion_descripcion' => $v->clasemarcacion_descripcion,
                    'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                    'modalidad_marcacion' => $v->modalidad_marcacion,
                    'd1' => $v->d1,
                    'calendariolaboral1_id' => $v->calendariolaboral1_id,
                    'd2' => $v->d2,
                    'calendariolaboral2_id' => $v->calendariolaboral2_id,
                    'd3' => $v->d3,
                    'calendariolaboral3_id' => $v->calendariolaboral3_id,
                    'd4' => $v->d4,
                    'calendariolaboral4_id' => $v->calendariolaboral4_id,
                    'd5' => $v->d5,
                    'calendariolaboral5_id' => $v->calendariolaboral5_id,
                    'd6' => $v->d6,
                    'calendariolaboral6_id' => $v->calendariolaboral6_id,
                    'd7' => $v->d7,
                    'calendariolaboral7_id' => $v->calendariolaboral7_id,
                    'd8' => $v->d8,
                    'calendariolaboral8_id' => $v->calendariolaboral8_id,
                    'd9' => $v->d9,
                    'calendariolaboral9_id' => $v->calendariolaboral9_id,
                    'd10' => $v->d10,
                    'calendariolaboral10_id' => $v->calendariolaboral10_id,
                    'd11' => $v->d11,
                    'calendariolaboral11_id' => $v->calendariolaboral11_id,
                    'd12' => $v->d12,
                    'calendariolaboral12_id' => $v->calendariolaboral12_id,
                    'd13' => $v->d13,
                    'calendariolaboral13_id' => $v->calendariolaboral13_id,
                    'd14' => $v->d14,
                    'calendariolaboral14_id' => $v->calendariolaboral14_id,
                    'd15' => $v->d15,
                    'calendariolaboral15_id' => $v->calendariolaboral15_id,
                    'd16' => $v->d16,
                    'calendariolaboral16_id' => $v->calendariolaboral16_id,
                    'd17' => $v->d17,
                    'calendariolaboral17_id' => $v->calendariolaboral17_id,
                    'd18' => $v->d18,
                    'calendariolaboral18_id' => $v->calendariolaboral18_id,
                    'd19' => $v->d19,
                    'calendariolaboral19_id' => $v->calendariolaboral19_id,
                    'd20' => $v->d20,
                    'calendariolaboral20_id' => $v->calendariolaboral20_id,
                    'd21' => $v->d21,
                    'calendariolaboral21_id' => $v->calendariolaboral21_id,
                    'd22' => $v->d22,
                    'calendariolaboral22_id' => $v->calendariolaboral22_id,
                    'd23' => $v->d23,
                    'calendariolaboral23_id' => $v->calendariolaboral23_id,
                    'd24' => $v->d24,
                    'calendariolaboral24_id' => $v->calendariolaboral24_id,
                    'd25' => $v->d25,
                    'calendariolaboral25_id' => $v->calendariolaboral25_id,
                    'd26' => $v->d26,
                    'calendariolaboral26_id' => $v->calendariolaboral26_id,
                    'd27' => $v->d27,
                    'calendariolaboral27_id' => $v->calendariolaboral27_id,
                    'd28' => $v->d28,
                    'calendariolaboral28_id' => $v->calendariolaboral28_id,
                    'd29' => $v->d29,
                    'calendariolaboral29_id' => $v->calendariolaboral29_id,
                    'd30' => $v->d30,
                    'calendariolaboral30_id' => $v->calendariolaboral30_id,
                    'd31' => $v->d31,
                    'calendariolaboral31_id' => $v->calendariolaboral31_id,
                    'ultimo_dia' => $v->ultimo_dia,
                    'atrasos' => $v->atrasos,
                    'atrasados' => $v->atrasados,
                    'faltas' => $v->faltas,
                    'abandono' => $v->abandono,
                    'omision' => $v->omision,
                    'lsgh' => $v->lsgh,
                    'compensacion' => $v->compensacion,
                    'descanso' => $v->descanso,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_apr_id' => $v->user_apr_id,
                    'fecha_apr' => $v->fecha_apr,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod
                );
                if ($v->modalidadmarcacion_id == 6) {
                    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = $d8 = $d9 = $d10 = $d11 = $d12 = $d13 = $d14 = $d15 = $d16 = $d17 = $d18 = $d19 = $d20 = $d21 = $d22 = $d23 = $d24 = $d25 = $d26 = $d27 = $d28 = $d29 = $d30 = $d30 = $d31 = "";
                    if ($v->calendariolaboral1_id > 0) {
                        $d1 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 1, $v->calendariolaboral1_id, 1);
                        $d1 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 1, 1);
                    }
                    if ($v->calendariolaboral2_id > 0) {
                        $d2 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 2, $v->calendariolaboral2_id, 1);
                        $d2 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 2, 1);
                    }
                    if ($v->calendariolaboral3_id > 0) {
                        $d3 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 3, $v->calendariolaboral3_id, 1);
                        $d3 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 3, 1);
                    }
                    if ($v->calendariolaboral4_id > 0) {
                        $d4 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 4, $v->calendariolaboral4_id, 1);
                        $d4 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 4, 1);
                    }
                    if ($v->calendariolaboral5_id > 0) {
                        $d5 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 5, $v->calendariolaboral5_id, 1);
                        $d5 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 5, 1);
                    }
                    if ($v->calendariolaboral6_id > 0) {
                        $d6 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 6, $v->calendariolaboral6_id, 1);
                        $d6 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 6, 1);
                    }
                    if ($v->calendariolaboral7_id > 0) {
                        $d7 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 7, $v->calendariolaboral7_id, 1);
                        $d7 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 7, 1);
                    }
                    if ($v->calendariolaboral8_id > 0) {
                        $d8 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 8, $v->calendariolaboral8_id, 1);
                        $d8 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 8, 1);
                    }
                    if ($v->calendariolaboral9_id > 0) {
                        $d9 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 9, $v->calendariolaboral9_id, 1);
                        $d9 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 9, 1);
                    }
                    if ($v->calendariolaboral10_id > 0) {
                        $d10 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 10, $v->calendariolaboral10_id, 1);
                        $d10 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 10, 1);
                    }
                    if ($v->calendariolaboral11_id > 0) {
                        $d11 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 11, $v->calendariolaboral11_id, 1);
                        $d11 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 11, 1);
                    }
                    if ($v->calendariolaboral12_id > 0) {
                        $d12 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 12, $v->calendariolaboral12_id, 1);
                        $d12 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 12, 1);
                    }
                    if ($v->calendariolaboral13_id > 0) {
                        $d13 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 13, $v->calendariolaboral13_id, 1);
                        $d13 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 13, 1);
                    }
                    if ($v->calendariolaboral14_id > 0) {
                        $d14 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 14, $v->calendariolaboral14_id, 1);
                        $d14 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 14, 1);
                    }
                    if ($v->calendariolaboral15_id > 0) {
                        $d15 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 15, $v->calendariolaboral15_id, 1);
                        $d15 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 15, 1);
                    }
                    if ($v->calendariolaboral16_id > 0) {
                        $d16 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 16, $v->calendariolaboral16_id, 1);
                        $d16 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 16, 1);
                    }
                    if ($v->calendariolaboral17_id > 0) {
                        $d17 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 17, $v->calendariolaboral17_id, 1);
                        $d17 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 17, 1);
                    }
                    if ($v->calendariolaboral18_id > 0) {
                        $d18 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 18, $v->calendariolaboral18_id, 1);
                        $d18 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 18, 1);
                    }
                    if ($v->calendariolaboral19_id > 0) {
                        $d19 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 19, $v->calendariolaboral19_id, 1);
                        $d19 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 19, 1);
                    }
                    if ($v->calendariolaboral20_id > 0) {
                        $d20 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 20, $v->calendariolaboral20_id, 1);
                        $d20 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 20, 1);
                    }
                    if ($v->calendariolaboral21_id > 0) {
                        $d21 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 21, $v->calendariolaboral21_id, 1);
                        $d21 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 21, 1);
                    }
                    if ($v->calendariolaboral22_id > 0) {
                        $d22 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 22, $v->calendariolaboral22_id, 1);
                        $d22 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 22, 1);
                    }
                    if ($v->calendariolaboral23_id > 0) {
                        $d23 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 23, $v->calendariolaboral23_id, 1);
                        $d23 = $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 23, 1);
                    }
                    if ($v->calendariolaboral24_id > 0) {
                        $d24 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 24, $v->calendariolaboral24_id, 1);
                        $d24 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 24, 1);
                    }
                    if ($v->calendariolaboral25_id > 0) {
                        $d25 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 25, $v->calendariolaboral25_id, 1);
                        $d25 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 25, 1);
                    }
                    if ($v->calendariolaboral26_id > 0) {
                        $d26 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 26, $v->calendariolaboral26_id, 1);
                        $d26 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 26, 1);
                    }
                    if ($v->calendariolaboral27_id > 0) {
                        $d27 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 27, $v->calendariolaboral27_id, 1);
                        $d27 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 27, 1);
                    }
                    if ($v->calendariolaboral28_id > 0) {
                        $d28 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 28, $v->calendariolaboral28_id, 1);
                        $d28 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 28, 1);
                    }
                    if ($v->calendariolaboral29_id > 0) {
                        $d29 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 29, $v->calendariolaboral29_id, 1);
                        $d29 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 29, 1);
                    }
                    if ($v->calendariolaboral30_id > 0) {
                        $d30 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 30, $v->calendariolaboral30_id, 1);
                        $d30 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 30, 1);
                    }
                    if ($v->calendariolaboral31_id > 0) {
                        $d31 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 31, $v->calendariolaboral31_id, 1);
                        $d31 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 31, 1);
                    }
                    $horariosymarcaciones[] = array(
                        'id' => $v->id_horarioymarcacion,
                        'relaboral_id' => $v->relaboral_id,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'turno' => $v->turno,
                        'grupo' => $v->grupo,
                        'clasemarcacion' => "e",
                        'clasemarcacion_descripcion' => "EXCEPCIONES / FERIADOS",
                        'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                        'modalidad_marcacion' => "EXCEPCIONES / FERIADOS",
                        'd1' => $d1,
                        'calendariolaboral1_id' => $v->calendariolaboral1_id,
                        'd2' => $d2,
                        'calendariolaboral2_id' => $v->calendariolaboral2_id,
                        'd3' => $d3,
                        'calendariolaboral3_id' => $v->calendariolaboral3_id,
                        'd4' => $d4,
                        'calendariolaboral4_id' => $v->calendariolaboral4_id,
                        'd5' => $d5,
                        'calendariolaboral5_id' => $v->calendariolaboral5_id,
                        'd6' => $d6,
                        'calendariolaboral6_id' => $v->calendariolaboral6_id,
                        'd7' => $d7,
                        'calendariolaboral7_id' => $v->calendariolaboral7_id,
                        'd8' => $d8,
                        'calendariolaboral8_id' => $v->calendariolaboral8_id,
                        'd9' => $d9,
                        'calendariolaboral9_id' => $v->calendariolaboral9_id,
                        'd10' => $d10,
                        'calendariolaboral10_id' => $v->calendariolaboral10_id,
                        'd11' => $d11,
                        'calendariolaboral11_id' => $v->calendariolaboral11_id,
                        'd12' => $d12,
                        'calendariolaboral12_id' => $v->calendariolaboral12_id,
                        'd13' => $d13,
                        'calendariolaboral13_id' => $v->calendariolaboral13_id,
                        'd14' => $d14,
                        'calendariolaboral14_id' => $v->calendariolaboral14_id,
                        'd15' => $d15,
                        'calendariolaboral15_id' => $v->calendariolaboral15_id,
                        'd16' => $d16,
                        'calendariolaboral16_id' => $v->calendariolaboral16_id,
                        'd17' => $d17,
                        'calendariolaboral17_id' => $v->calendariolaboral17_id,
                        'd18' => $d18,
                        'calendariolaboral18_id' => $v->calendariolaboral18_id,
                        'd19' => $d19,
                        'calendariolaboral19_id' => $v->calendariolaboral19_id,
                        'd20' => $d20,
                        'calendariolaboral20_id' => $v->calendariolaboral20_id,
                        'd21' => $d21,
                        'calendariolaboral21_id' => $v->calendariolaboral21_id,
                        'd22' => $d22,
                        'calendariolaboral22_id' => $v->calendariolaboral22_id,
                        'd23' => $d23,
                        'calendariolaboral23_id' => $v->calendariolaboral23_id,
                        'd24' => $d24,
                        'calendariolaboral24_id' => $v->calendariolaboral24_id,
                        'd25' => $d25,
                        'calendariolaboral25_id' => $v->calendariolaboral25_id,
                        'd26' => $d26,
                        'calendariolaboral26_id' => $v->calendariolaboral26_id,
                        'd27' => $d27,
                        'calendariolaboral27_id' => $v->calendariolaboral27_id,
                        'd28' => $d28,
                        'calendariolaboral28_id' => $v->calendariolaboral28_id,
                        'd29' => $d29,
                        'calendariolaboral29_id' => $v->calendariolaboral29_id,
                        'd30' => $d30,
                        'calendariolaboral30_id' => $v->calendariolaboral30_id,
                        'd31' => $d31,
                        'calendariolaboral31_id' => $v->calendariolaboral31_id,
                        'ultimo_dia' => $v->ultimo_dia,
                        'atrasos' => null,
                        'atrasados' => null,
                        'faltas' => null,
                        'abandono' => null,
                        'omision' => null,
                        'lsgh' => null,
                        'compensacion' => null,
                        'descanso' => null,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_apr_id' => $v->user_apr_id,
                        'fecha_apr' => $v->fecha_apr,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                    );
                }
                #endregion sector para adicionar una fila para Excepciones
            }
            //$pdf->Open("L");
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

            if (count($horariosymarcaciones) > 0) {
                foreach ($horariosymarcaciones as $i => $val) {
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
                        $rowData = $pdf->DefineRows($j + 1, $horariosymarcaciones[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        if ($pdf->debug == 1) {
                            echo "<p>***********************************VALOR POR LA LINEA " . ($j + 1) . "***************************************************</p>";
                            print_r($val);
                            echo "<p>***************************************************************************************</p>";
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_marcaciones.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }
    /**
     * Función para el despliegue del reporte de cálculos de marcaciones en formato PDF.
     * @param $lstIdPersonasAux Listado de identificadores de personas.
     * @param $fechaIni Fecha de inicio del rango del reporte.
     * @param $fechaFin Fecha de finalización del rango del reporte.
     * @param $n_rows Cantidad de registros.
     * @param $columns Array con las columnas a considerarse.
     * @param $filtros Array de los filtros aplicados.
     * @param $groups Array de las agrupaciones aplicadas.
     * @param $sorteds Array de los órdenes aplicados.
     */
    //public function exportcalculospdfAction($lstIdPersonasAux,$fechaIni,$fechaFin,$n_rows, $columns, $filtros,$groups,$sorteds)
    public function exportcalculospdfAction()
    {
        $this->view->disable();
        $carnetAux = $_POST["carnets"];
        $fechaIni = $_POST["fecha_ini"];
        $fechaFin = $_POST["fecha_fin"];
        $n_rows = $_POST["n_rows"];
        $columns = $_POST["columns"];
        $filtros = $_POST["filters"];
        $groups = $_POST["groups"];
        $sorteds = $_POST["sorteds"];
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
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'ci' => array('title' => 'CI', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => true),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar', 'totales' => true),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => true),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric', 'totales' => true),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'gestion' => array('title' => 'Gestion', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'mes_nombre' => array('title' => 'Mes', 'width' => 20, 'align' => 'C', 'type' => 'varchar', 'totales' => true),
            'turno' => array('title' => 'Turno', 'width' => 10, 'align' => 'C', 'type' => 'numeric', 'totales' => false),
            'modalidad_marcacion' => array('title' => 'Modalidad', 'width' => 30, 'align' => 'C', 'type' => 'varchar', 'totales' => false),
            'd1' => array('title' => 'Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado1_descripcion' => array('title' => 'Estado Dia 1', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd2' => array('title' => 'Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado2_descripcion' => array('title' => 'Estado Dia 2', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd3' => array('title' => 'Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado3_descripcion' => array('title' => 'Estado Dia 3', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd4' => array('title' => 'Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado4_descripcion' => array('title' => 'Estado Dia 4', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd5' => array('title' => 'Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado5_descripcion' => array('title' => 'Estado Dia 5', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd6' => array('title' => 'Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado6_descripcion' => array('title' => 'Estado Dia 6', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd7' => array('title' => 'Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado7_descripcion' => array('title' => 'Estado Dia 7', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd8' => array('title' => 'Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado8_descripcion' => array('title' => 'Estado Dia 8', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd9' => array('title' => 'Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado9_descripcion' => array('title' => 'Estado Dia 9', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd10' => array('title' => 'Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado10_descripcion' => array('title' => 'Estado Dia 10', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd11' => array('title' => 'Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado11_descripcion' => array('title' => 'Estado Dia 11', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd12' => array('title' => 'Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado12_descripcion' => array('title' => 'Estado Dia 12', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd13' => array('title' => 'Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado13_descripcion' => array('title' => 'Estado Dia 13', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd14' => array('title' => 'Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado14_descripcion' => array('title' => 'Estado Dia 14', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd15' => array('title' => 'Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado15_descripcion' => array('title' => 'Estado Dia 15', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd16' => array('title' => 'Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado16_descripcion' => array('title' => 'Estado Dia 16', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd17' => array('title' => 'Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado17_descripcion' => array('title' => 'Estado Dia 17', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd18' => array('title' => 'Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado18_descripcion' => array('title' => 'Estado Dia 18', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd19' => array('title' => 'Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado19_descripcion' => array('title' => 'Estado Dia 19', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd20' => array('title' => 'Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado20_descripcion' => array('title' => 'Estado Dia 20', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd21' => array('title' => 'Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado21_descripcion' => array('title' => 'Estado Dia 21', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd22' => array('title' => 'Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado22_descripcion' => array('title' => 'Estado Dia 22', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd23' => array('title' => 'Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado23_descripcion' => array('title' => 'Estado Dia 23', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd24' => array('title' => 'Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado24_descripcion' => array('title' => 'Estado Dia 24', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd25' => array('title' => 'Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado25_descripcion' => array('title' => 'Estado Dia 25', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd26' => array('title' => 'Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado26_descripcion' => array('title' => 'Estado Dia 26', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd27' => array('title' => 'Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado27_descripcion' => array('title' => 'Estado Dia 27', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd28' => array('title' => 'Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado28_descripcion' => array('title' => 'Estado Dia 28', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd29' => array('title' => 'Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado29_descripcion' => array('title' => 'Estado Dia 29', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd30' => array('title' => 'Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado30_descripcion' => array('title' => 'Estado Dia 30', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'd31' => array('title' => 'Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'date', 'totales' => false),
            /*'estado31_descripcion' => array('title' => 'Estado Dia 31', 'width' => 18, 'align' => 'C', 'type' => 'varchar'),*/
            'ultimo_dia' => array('title' => 'U/Dia', 'width' => 10, 'align' => 'C', 'type' => 'numeric', 'totales' => false),
            'atrasos' => array('title' => 'Atrasos', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'atrasados' => array('title' => 'Atrasados', 'width' => 18, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'faltas' => array('title' => 'Faltas', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'abandono' => array('title' => 'Abandono', 'width' => 18, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'omision' => array('title' => 'Sin Marcacion', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'lsgh' => array('title' => 'LSGH', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'agrupador' => array('title' => 'M/Prev.', 'width' => 15, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'descanso' => array('title' => 'Descanso', 'width' => 18, 'align' => 'C', 'type' => 'numeric', 'totales' => true),
            'observacion' => array('title' => 'Obs.', 'width' => 30, 'align' => 'L', 'type' => 'varchar', 'totales' => false)
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
            $pdf->title_rpt = utf8_decode('Reporte Rango Marcaciones (' . $fechaIni . ' al ' . $fechaFin . ')');
            $pdf->title_total_rpt = utf8_decode('Cuadro Resumen de Datos Marcaciones (' . $fechaIni . ' al ' . $fechaFin . ')');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            if ($pdf->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^COLUMNAS A MOSTRARSE^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^(" . count($colSelecteds) . ")</p>";
                print_r($colSelecteds);
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
            }
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);

            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->totalAlignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            $pdf->totalAlignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^Rango Fecha^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo $fechaIni . "<->" . $fechaFin;
                echo "<p>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^";
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::TOTAL COLUMNAS::::::::::::::::::::::::::::::::::::::::::(" . count($columns) . ")<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::(" . count($filtros) . ")<p>";
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
            $obj = new Frelaboraleshorariosymarcaciones();
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
                    $groups = " ORDER BY " . $groups . ",relaboral_id,gestion,mes,turno,modalidadmarcacion_id";
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
            if ($carnetAux != '') {
                $lstCi = "";
                $lstIdPersonas = "";
                $arrCarnets = explode(",", $carnetAux);
                if (count($arrCarnets) > 0) {
                    foreach ($arrCarnets as $ci) {
                        $lstCi .= "'$ci',";
                    }
                    $lstCi .= ",";
                    $lstCi = str_replace(",,", "", $lstCi);
                    $objP = Personas::Find("ci IN (" . $lstCi . ")");
                    if (is_object($objP)) {
                        foreach ($objP as $p) {
                            $lstIdPersonas .= $p->id . ",";
                        }
                        $lstIdPersonas .= ",";
                        $lstIdPersonas = str_replace(",,", "", $lstIdPersonas);
                    }
                }
                $objHM = new Fplanillasref();
                $arrIdRelaborales = $objHM->getIdRelaboralesEnJsonPorIdPersonas($lstIdPersonas, $fechaIni, $fechaFin);
                $jsonIdRelaborales = '{"0":0}';
                if (is_object($arrIdRelaborales)) {
                    $clave = 0;
                    $jsonIdRelaborales = '{';
                    foreach ($arrIdRelaborales as $reg) {
                        $jsonIdRelaborales .= '"' . $clave . '":' . $reg->id . ',';
                        $clave++;
                    }
                    $jsonIdRelaborales .= ',';
                    $jsonIdRelaborales = str_replace(",,", "", $jsonIdRelaborales);
                    $jsonIdRelaborales .= '}';
                }
            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->getAllByRangeTwoMonth($jsonIdRelaborales, $fechaIni, $fechaFin, $where, $groups);

            $arrTotales = array();
            $horariosymarcaciones = array();
            $arrPrevistaMasEfectiva = array();
            $totalAtrasos = $totalAtrasados = $totalFaltas = $totalAbandono = $totalOmision = $totalLsgh = $totalAgrupador = $totalDescanso = $totalCompensacion = 0;

            foreach ($resul as $v) {
                $horariosymarcaciones[] = array(
                    #region Columnas de procedimiento f_relaborales()
                    'id_relaboral' => $v->relaboral_id,
                    'id_persona' => $v->id_persona,
                    'p_nombre' => $v->p_nombre,
                    's_nombre' => $v->s_nombre,
                    't_nombre' => $v->t_nombre,
                    'p_apellido' => $v->p_apellido,
                    's_apellido' => $v->s_apellido,
                    'c_apellido' => $v->c_apellido,
                    'nombres' => $v->nombres,
                    'ci' => $v->ci,
                    'expd' => $v->expd,
                    'cargo' => $v->cargo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'condicion' => $v->condicion,
                    'gerencia_administrativa' => $v->gerencia_administrativa,
                    'departamento_administrativo' => $v->departamento_administrativo,
                    'area' => $v->area,
                    'ubicacion' => $v->ubicacion,
                    #endregion Columnas de procedimiento f_relaborales()

                    'id' => $v->id_horarioymarcacion,
                    'relaboral_id' => $v->relaboral_id,
                    'gestion' => $v->gestion,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'turno' => $v->turno,
                    'grupo' => $v->grupo,
                    'clasemarcacion' => trim($v->clasemarcacion),
                    'clasemarcacion_descripcion' => trim($v->clasemarcacion_descripcion),
                    'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                    'modalidad_marcacion' => trim($v->modalidad_marcacion),
                    'd1' => $v->d1,
                    'calendariolaboral1_id' => $v->calendariolaboral1_id,
                    'd2' => $v->d2,
                    'calendariolaboral2_id' => $v->calendariolaboral2_id,
                    'd3' => $v->d3,
                    'calendariolaboral3_id' => $v->calendariolaboral3_id,
                    'd4' => $v->d4,
                    'calendariolaboral4_id' => $v->calendariolaboral4_id,
                    'd5' => $v->d5,
                    'calendariolaboral5_id' => $v->calendariolaboral5_id,
                    'd6' => $v->d6,
                    'calendariolaboral6_id' => $v->calendariolaboral6_id,
                    'd7' => $v->d7,
                    'calendariolaboral7_id' => $v->calendariolaboral7_id,
                    'd8' => $v->d8,
                    'calendariolaboral8_id' => $v->calendariolaboral8_id,
                    'd9' => $v->d9,
                    'calendariolaboral9_id' => $v->calendariolaboral9_id,
                    'd10' => $v->d10,
                    'calendariolaboral10_id' => $v->calendariolaboral10_id,
                    'd11' => $v->d11,
                    'calendariolaboral11_id' => $v->calendariolaboral11_id,
                    'd12' => $v->d12,
                    'calendariolaboral12_id' => $v->calendariolaboral12_id,
                    'd13' => $v->d13,
                    'calendariolaboral13_id' => $v->calendariolaboral13_id,
                    'd14' => $v->d14,
                    'calendariolaboral14_id' => $v->calendariolaboral14_id,
                    'd15' => $v->d15,
                    'calendariolaboral15_id' => $v->calendariolaboral15_id,
                    'd16' => $v->d16,
                    'calendariolaboral16_id' => $v->calendariolaboral16_id,
                    'd17' => $v->d17,
                    'calendariolaboral17_id' => $v->calendariolaboral17_id,
                    'd18' => $v->d18,
                    'calendariolaboral18_id' => $v->calendariolaboral18_id,
                    'd19' => $v->d19,
                    'calendariolaboral19_id' => $v->calendariolaboral19_id,
                    'd20' => $v->d20,
                    'calendariolaboral20_id' => $v->calendariolaboral20_id,
                    'd21' => $v->d21,
                    'calendariolaboral21_id' => $v->calendariolaboral21_id,
                    'd22' => $v->d22,
                    'calendariolaboral22_id' => $v->calendariolaboral22_id,
                    'd23' => $v->d23,
                    'calendariolaboral23_id' => $v->calendariolaboral23_id,
                    'd24' => $v->d24,
                    'calendariolaboral24_id' => $v->calendariolaboral24_id,
                    'd25' => $v->d25,
                    'calendariolaboral25_id' => $v->calendariolaboral25_id,
                    'd26' => $v->d26,
                    'calendariolaboral26_id' => $v->calendariolaboral26_id,
                    'd27' => $v->d27,
                    'calendariolaboral27_id' => $v->calendariolaboral27_id,
                    'd28' => $v->d28,
                    'calendariolaboral28_id' => $v->calendariolaboral28_id,
                    'd29' => $v->d29,
                    'calendariolaboral29_id' => $v->calendariolaboral29_id,
                    'd30' => $v->d30,
                    'calendariolaboral30_id' => $v->calendariolaboral30_id,
                    'd31' => $v->d31,
                    'calendariolaboral31_id' => $v->calendariolaboral31_id,
                    'ultimo_dia' => $v->ultimo_dia,
                    'atrasos' => $v->atrasos,
                    'atrasados' => $v->atrasados,
                    'faltas' => $v->faltas,
                    'abandono' => $v->abandono,
                    'omision' => $v->omision,
                    'lsgh' => $v->lsgh,
                    'compensacion' => $v->compensacion,
                    'descanso' => $v->descanso,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_apr_id' => $v->user_apr_id,
                    'fecha_apr' => $v->fecha_apr,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                );
                #region Sector para almacenamiento de los totales
                if ($v->modalidadmarcacion_id == 3 || $v->modalidadmarcacion_id == 6) {
                    /**
                     * Se marca al registro de relación laboral para conocer quienes tienen el cálculo Previsto y Efectivo realizado.
                     */
                    $arrPrevistaMasEfectiva[$v->relaboral_id][$v->gestion][$v->mes] = 1;
                    $atrasos = $faltas = $abandono = $omision = $lsgh = $compensacion = 0;
                    if ($v->atrasos != '') {
                        $atrasos = $v->atrasos;
                    }
                    if ($v->faltas != '') {
                        $faltas = $v->faltas;
                    }
                    if ($v->abandono != '') {
                        $abandono = $v->abandono;
                    }
                    if ($v->omision != '') {
                        $omision = $v->omision;
                    }
                    if ($v->lsgh != '') {
                        $lsgh = $v->lsgh;
                    }
                    if ($v->compensacion != '') {
                        $compensacion = $v->compensacion;
                    }
                    $totalAtrasos += $atrasos;
                    $totalFaltas += $faltas;
                    $totalAbandono += $abandono;
                    $totalOmision += $omision;
                    $totalLsgh += $lsgh;
                    $totalCompensacion += $compensacion;

                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["id_relaboral"] = $v->relaboral_id;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["nombres"] = $v->nombres;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ci"] = $v->ci;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["expd"] = $v->expd;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gestion"] = $v->gestion;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes"] = $v->mes;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes_nombre"] = $v->mes_nombre;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["condicion"] = $v->condicion;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gerencia_administrativa"] = $v->gerencia_administrativa;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["departamento_administrativo"] = $v->departamento_administrativo;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["area"] = $v->area;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ubicacion"] = $v->ubicacion;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["cargo"] = $v->cargo;
                    $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["sueldo"] = $v->sueldo;
                    /*echo "<p>---------------------------------------------------------------------</p>";
                    echo "1) ".$atrasos.", 2) ".$faltas.", 3) ".$abandono.", 4) ".$omision.", 5) ".$lsgh.", 6) ".$compensacion;
                    echo "<p>---------------------------------------------------------------------</p>";*/
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] + $atrasos;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasos"] = $atrasos;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] + $faltas;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] = $faltas;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] + $abandono;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] = $abandono;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] + $omision;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] = $omision;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] + $lsgh;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] = $lsgh;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] + $compensacion;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = $compensacion;
                    }
                }
                /**
                 * Sumatoria de las marcaciones previstas por persona
                 */
                if ($v->clasemarcacion == 'T') {
                    $agrupador = $atrasados = $descanso = 0;
                    if ($v->agrupador != '') {
                        $agrupador = $v->agrupador;
                    }
                    $totalAgrupador += $agrupador;
                    $totalAtrasados += $atrasados;
                    $totalDescanso += $descanso;
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] + $agrupador;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["agrupador"] = $agrupador;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] + $atrasados;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["atrasados"] = $atrasados;
                    }
                    if (isset($arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"]) && $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] > 0) {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] = $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] + $descanso;
                    } else {
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["descanso"] = $descanso;
                    }
                    /**
                     * Si no se ha instanciado la variable de totales se debe al menos poner valores que alerten de la ausencia de marcación efectiva.
                     * Pudiendo ser la razón la existencia de descanso o la inexistencia de calendario para esa persona en ese mes.
                     */
                    if (!isset($arrPrevistaMasEfectiva[$v->relaboral_id][$v->gestion][$v->mes])) {

                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["id_relaboral"] = $v->relaboral_id;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["nombres"] = $v->nombres;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ci"] = $v->ci;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["expd"] = $v->expd;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gestion"] = $v->gestion;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes"] = $v->mes;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["mes_nombre"] = $v->mes_nombre;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["condicion"] = $v->condicion;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["gerencia_administrativa"] = $v->gerencia_administrativa;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["departamento_administrativo"] = $v->departamento_administrativo;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["area"] = $v->area;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["ubicacion"] = $v->ubicacion;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["cargo"] = $v->cargo;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["sueldo"] = $v->sueldo;

                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["faltas"] = 31;
                        $totalFaltas += 31;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["abandono"] = 0;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["omision"] = 0;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["lsgh"] = 0;
                        $arrTotales[$v->relaboral_id][$v->gestion][$v->mes]["compensacion"] = 0;
                    }
                }
                #endregion Sector para almacenamiento de los totales
                #region Sector para adicionar una fila para Excepciones
                if ($v->modalidadmarcacion_id == 6) {
                    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = $d8 = $d9 = $d10 = $d11 = $d12 = $d13 = $d14 = $d15 = $d16 = $d17 = $d18 = $d19 = $d20 = $d21 = $d22 = $d23 = $d24 = $d25 = $d26 = $d27 = $d28 = $d29 = $d30 = $d30 = $d31 = "";
                    if ($v->calendariolaboral1_id > 0) {
                        $d1 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 1, $v->calendariolaboral1_id, 1);
                        $d1 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 1, 1);
                    }
                    if ($v->calendariolaboral2_id > 0) {
                        $d2 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 2, $v->calendariolaboral2_id, 1);
                        $d2 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 2, 1);
                    }
                    if ($v->calendariolaboral3_id > 0) {
                        $d3 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 3, $v->calendariolaboral3_id, 1);
                        $d3 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 3, 1);
                    }
                    if ($v->calendariolaboral4_id > 0) {
                        $d4 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 4, $v->calendariolaboral4_id, 1);
                        $d4 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 4, 1);
                    }
                    if ($v->calendariolaboral5_id > 0) {
                        $d5 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 5, $v->calendariolaboral5_id, 1);
                        $d5 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 5, 1);
                    }
                    if ($v->calendariolaboral6_id > 0) {
                        $d6 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 6, $v->calendariolaboral6_id, 1);
                        $d6 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 6, 1);
                    }
                    if ($v->calendariolaboral7_id > 0) {
                        $d7 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 7, $v->calendariolaboral7_id, 1);
                        $d7 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 7, 1);
                    }
                    if ($v->calendariolaboral8_id > 0) {
                        $d8 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 8, $v->calendariolaboral8_id, 1);
                        $d8 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 8, 1);
                    }
                    if ($v->calendariolaboral9_id > 0) {
                        $d9 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 9, $v->calendariolaboral9_id, 1);
                        $d9 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 9, 1);
                    }
                    if ($v->calendariolaboral10_id > 0) {
                        $d10 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 10, $v->calendariolaboral10_id, 1);
                        $d10 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 10, 1);
                    }
                    if ($v->calendariolaboral11_id > 0) {
                        $d11 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 11, $v->calendariolaboral11_id, 1);
                        $d11 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 11, 1);
                    }
                    if ($v->calendariolaboral12_id > 0) {
                        $d12 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 12, $v->calendariolaboral12_id, 1);
                        $d12 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 12, 1);
                    }
                    if ($v->calendariolaboral13_id > 0) {
                        $d13 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 13, $v->calendariolaboral13_id, 1);
                        $d13 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 13, 1);
                    }
                    if ($v->calendariolaboral14_id > 0) {
                        $d14 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 14, $v->calendariolaboral14_id, 1);
                        $d14 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 14, 1);
                    }
                    if ($v->calendariolaboral15_id > 0) {
                        $d15 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 15, $v->calendariolaboral15_id, 1);
                        $d15 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 15, 1);
                    }
                    if ($v->calendariolaboral16_id > 0) {
                        $d16 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 16, $v->calendariolaboral16_id, 1);
                        $d16 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 16, 1);
                    }
                    if ($v->calendariolaboral17_id > 0) {
                        $d17 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 17, $v->calendariolaboral17_id, 1);
                        $d17 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 17, 1);
                    }
                    if ($v->calendariolaboral18_id > 0) {
                        $d18 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 18, $v->calendariolaboral18_id, 1);
                        $d18 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 18, 1);
                    }
                    if ($v->calendariolaboral19_id > 0) {
                        $d19 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 19, $v->calendariolaboral19_id, 1);
                        $d19 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 19, 1);
                    }
                    if ($v->calendariolaboral20_id > 0) {
                        $d20 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 20, $v->calendariolaboral20_id, 1);
                        $d20 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 20, 1);
                    }
                    if ($v->calendariolaboral21_id > 0) {
                        $d21 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 21, $v->calendariolaboral21_id, 1);
                        $d21 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 21, 1);
                    }
                    if ($v->calendariolaboral22_id > 0) {
                        $d22 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 22, $v->calendariolaboral22_id, 1);
                        $d22 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 22, 1);
                    }
                    if ($v->calendariolaboral23_id > 0) {
                        $d23 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 23, $v->calendariolaboral23_id, 1);
                        $d23 = $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 23, 1);
                    }
                    if ($v->calendariolaboral24_id > 0) {
                        $d24 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 24, $v->calendariolaboral24_id, 1);
                        $d24 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 24, 1);
                    }
                    if ($v->calendariolaboral25_id > 0) {
                        $d25 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 25, $v->calendariolaboral25_id, 1);
                        $d25 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 25, 1);
                    }
                    if ($v->calendariolaboral26_id > 0) {
                        $d26 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 26, $v->calendariolaboral26_id, 1);
                        $d26 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 26, 1);
                    }
                    if ($v->calendariolaboral27_id > 0) {
                        $d27 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 27, $v->calendariolaboral27_id, 1);
                        $d27 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 27, 1);
                    }
                    if ($v->calendariolaboral28_id > 0) {
                        $d28 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 28, $v->calendariolaboral28_id, 1);
                        $d28 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 28, 1);
                    }
                    if ($v->calendariolaboral29_id > 0) {
                        $d29 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 29, $v->calendariolaboral29_id, 1);
                        $d29 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 29, 1);
                    }
                    if ($v->calendariolaboral30_id > 0) {
                        $d30 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 30, $v->calendariolaboral30_id, 1);
                        $d30 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 30, 1);
                    }
                    if ($v->calendariolaboral31_id > 0) {
                        $d31 = $obj->obtenerExcepcionesEnDia($v->relaboral_id, 0, $v->gestion, $v->mes, 31, $v->calendariolaboral31_id, 1);
                        $d31 .= $obj->obtenerFeriadosEnDia($v->gestion, $v->mes, 31, 1);
                    }
                    $horariosymarcaciones[] = array(
                        #region Columnas de procedimiento f_relaborales()
                        'id_relaboral' => $v->relaboral_id,
                        'id_persona' => $v->id_persona,
                        'p_nombre' => $v->p_nombre,
                        's_nombre' => $v->s_nombre,
                        't_nombre' => $v->t_nombre,
                        'p_apellido' => $v->p_apellido,
                        's_apellido' => $v->s_apellido,
                        'c_apellido' => $v->c_apellido,
                        'nombres' => $v->nombres,
                        'ci' => $v->ci,
                        'expd' => $v->expd,
                        'cargo' => $v->cargo,
                        'sueldo' => str_replace(".00", "", $v->sueldo),
                        'condicion' => $v->condicion,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'area' => $v->area,
                        'ubicacion' => $v->ubicacion,
                        #endregion Columnas de procedimiento f_relaborales()

                        'id' => $v->id_horarioymarcacion,
                        'relaboral_id' => $v->relaboral_id,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'turno' => $v->turno,
                        'grupo' => $v->grupo,
                        'clasemarcacion' => "e",
                        'clasemarcacion_descripcion' => "EXCEPCIONES",
                        'modalidadmarcacion_id' => $v->modalidadmarcacion_id,
                        'modalidad_marcacion' => "EXCEPCIONES",
                        'd1' => $d1,
                        'calendariolaboral1_id' => $v->calendariolaboral1_id,
                        'd2' => $d2,
                        'calendariolaboral2_id' => $v->calendariolaboral2_id,
                        'd3' => $d3,
                        'calendariolaboral3_id' => $v->calendariolaboral3_id,
                        'd4' => $d4,
                        'calendariolaboral4_id' => $v->calendariolaboral4_id,
                        'd5' => $d5,
                        'calendariolaboral5_id' => $v->calendariolaboral5_id,
                        'd6' => $d6,
                        'calendariolaboral6_id' => $v->calendariolaboral6_id,
                        'd7' => $d7,
                        'calendariolaboral7_id' => $v->calendariolaboral7_id,
                        'd8' => $d8,
                        'calendariolaboral8_id' => $v->calendariolaboral8_id,
                        'd9' => $d9,
                        'calendariolaboral9_id' => $v->calendariolaboral9_id,
                        'd10' => $d10,
                        'calendariolaboral10_id' => $v->calendariolaboral10_id,
                        'd11' => $d11,
                        'calendariolaboral11_id' => $v->calendariolaboral11_id,
                        'd12' => $d12,
                        'calendariolaboral12_id' => $v->calendariolaboral12_id,
                        'd13' => $d13,
                        'calendariolaboral13_id' => $v->calendariolaboral13_id,
                        'd14' => $d14,
                        'calendariolaboral14_id' => $v->calendariolaboral14_id,
                        'd15' => $d15,
                        'calendariolaboral15_id' => $v->calendariolaboral15_id,
                        'd16' => $d16,
                        'calendariolaboral16_id' => $v->calendariolaboral16_id,
                        'd17' => $d17,
                        'calendariolaboral17_id' => $v->calendariolaboral17_id,
                        'd18' => $d18,
                        'calendariolaboral18_id' => $v->calendariolaboral18_id,
                        'd19' => $d19,
                        'calendariolaboral19_id' => $v->calendariolaboral19_id,
                        'd20' => $d20,
                        'calendariolaboral20_id' => $v->calendariolaboral20_id,
                        'd21' => $d21,
                        'calendariolaboral21_id' => $v->calendariolaboral21_id,
                        'd22' => $d22,
                        'calendariolaboral22_id' => $v->calendariolaboral22_id,
                        'd23' => $d23,
                        'calendariolaboral23_id' => $v->calendariolaboral23_id,
                        'd24' => $d24,
                        'calendariolaboral24_id' => $v->calendariolaboral24_id,
                        'd25' => $d25,
                        'calendariolaboral25_id' => $v->calendariolaboral25_id,
                        'd26' => $d26,
                        'calendariolaboral26_id' => $v->calendariolaboral26_id,
                        'd27' => $d27,
                        'calendariolaboral27_id' => $v->calendariolaboral27_id,
                        'd28' => $d28,
                        'calendariolaboral28_id' => $v->calendariolaboral28_id,
                        'd29' => $d29,
                        'calendariolaboral29_id' => $v->calendariolaboral29_id,
                        'd30' => $d30,
                        'calendariolaboral30_id' => $v->calendariolaboral30_id,
                        'd31' => $d31,
                        'calendariolaboral31_id' => $v->calendariolaboral31_id,
                        'ultimo_dia' => $v->ultimo_dia,
                        'atrasos' => null,
                        'atrasados' => null,
                        'faltas' => null,
                        'abandono' => null,
                        'omision' => null,
                        'lsgh' => null,
                        'compensacion' => null,
                        'descanso' => null,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => null,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_apr_id' => $v->user_apr_id,
                        'fecha_apr' => $v->fecha_apr,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                    );
                }
                #endregion sector para adicionar una fila para Excepciones
            }
            //$pdf->Open("L");
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

            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $arrIdRelaborales = array();
            $numeradorRelaboral = 0;
            if (count($horariosymarcaciones) > 0) {
                foreach ($horariosymarcaciones as $i => $val) {
                    /**
                     * Se agrega un control para modificar el contandor cuando se cambio de contrato.
                     */
                    if (isset($val["relaboral_id"]) && !in_array($val["relaboral_id"], $arrIdRelaborales)) {
                        $arrIdRelaborales[] = $val["relaboral_id"];
                        $numeradorRelaboral++;
                    }
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
                        $rowData = $pdf->DefineRows($numeradorRelaboral, $horariosymarcaciones[$j], $colSelecteds);
                        //Se modifica el numerador de filas debido a que se requiere un numerador por registro de relación laboral
                        //$rowData = $pdf->DefineRows($j + 1, $horariosymarcaciones[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        if ($pdf->debug == 1) {
                            echo "<p>***********************************VALOR POR LA LINEA " . ($j + 1) . "***************************************************</p>";
                            print_r($val);
                            echo "<p>***************************************************************************************</p>";
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($numeradorRelaboral, $val, $colSelecteds);
                        //Se modifica el numerador de filas debido a que se requiere un numerador por registro de relación laboral
                        //$rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
                #region Espacio para la definición de datos para la grilla de totales
                $totalCols = $pdf->DefineSelectedTotalCols($generalConfigForAllColumns);
                $totalTitleCols = $pdf->DefineTotalTitleCols($generalConfigForAllColumns, $totalCols);
                $totalAligns = $pdf->DefineAlignsForTotals($generalConfigForAllColumns, $totalCols);
                $totalWidths = $this->DefineTotalWidths($generalConfigForAllColumns, $totalCols);
                $anchoT = 0;
                foreach ($totalWidths as $w) {
                    $anchoT = $anchoT + $w;
                }
                $pdf->totalColTitleSelecteds = $totalTitleCols;
                $pdf->totalWidths = $totalWidths;
                $pdf->totalAligns = $totalAligns;
                $pdf->totalTableWidth = $anchoT;

                $pdf->agregarPaginaTotales($arrTotales, $totalAtrasos, $totalAtrasados, $totalFaltas, $totalAbandono, $totalOmision, $totalLsgh, $totalAgrupador, $totalDescanso, $totalCompensacion);
                #endregion Espacio para la definición de datos para la grilla de totales
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_marcaciones.pdf', 'I');
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

    /**
     * Función para la obtención del listado de anchos de campo para la grilla de totales.
     * @param $widthAlignAll
     * @param $totalCols
     * @return array
     */
    function DefineTotalWidths($widthAlignAll, $totalCols)
    {
        $arrRes = Array();
        foreach ($totalCols as $key => $val) {
            if (isset($widthAlignAll[$val])) {
                $arrRes[] = $widthAlignAll[$val]['width'];
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
     * Función para la obtención del listado descriptivo de marcaciones.
     */
    function listdescriptivomarcacionesAction()
    {
        $this->view->disable();
        $horariosymarcacionesgenerados = Array();
        if (isset($_POST["id"]) && $_POST["id"] > 0 && isset($_POST["clasemarcacion"])) {
            $obj = new Fhorariosymarcacionesgenerados();
            $idRelaboral = $_POST["id"];
            $clasemarcacion = $_POST["clasemarcacion"];
            $resul = $obj->getAll($idRelaboral, 0, 0, $clasemarcacion);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $horariosymarcacionesgenerados[] = array(
                        'nro_row' => 0,
                        'id_relaboral' => $v->id_relaboral,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'grupo' => $v->grupo,
                        'perfil_fecha_ini' => $v->perfil_fecha_ini != "" ? date("d-m-Y", strtotime($v->perfil_fecha_ini)) : "",
                        'perfil_fecha_fin' => $v->perfil_fecha_fin != "" ? date("d-m-Y", strtotime($v->perfil_fecha_fin)) : "",
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'rango_fecha_ini' => $v->rango_fecha_ini != "" ? date("d-m-Y", strtotime($v->rango_fecha_ini)) : "",
                        'rango_fecha_fin' => $v->rango_fecha_fin != "" ? date("d-m-Y", strtotime($v->rango_fecha_fin)) : "",
                        'estado' => $v->estado,
                        'estado_descripcion' => ($v->estado != null) ? $v->estado_descripcion : "SIN GENERAR",
                        'estado_global' => $v->estado_global
                    );
                }
            }
        }
        echo json_encode($horariosymarcacionesgenerados);
    }

    /**
     * Función para la obtención del listado descriptivo de marcaciones consderando el cruce entre dos clases de marcación.
     * El uso común de esta función será cuando se requiera comparar los estados entre lo previsto (H) y lo efectivo (M).
     */
    function listdescriptivomarcacionescruzadaAction()
    {
        $this->view->disable();
        $horariosymarcacionesgenerados = Array();
        if (isset($_POST["id"]) && $_POST["id"] > 0 && isset($_POST["clasemarcacionA"]) && isset($_POST["clasemarcacionB"])) {
            $obj = new Fhorariosymarcacionesgenerados();
            $idRelaboral = $_POST["id"];
            $clasemarcacionA = $_POST["clasemarcacionA"];
            $clasemarcacionB = $_POST["clasemarcacionB"];
            $resul = $obj->getAllCruzada($idRelaboral, 0, 0, $clasemarcacionA, $clasemarcacionB);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $horariosymarcacionesgenerados[] = array(
                        'nro_row' => 0,
                        'id_relaboral' => $v->id_relaboral,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'grupo' => $v->grupo,
                        'perfil_fecha_ini' => $v->perfil_fecha_ini != "" ? date("d-m-Y", strtotime($v->perfil_fecha_ini)) : "",
                        'perfil_fecha_fin' => $v->perfil_fecha_fin != "" ? date("d-m-Y", strtotime($v->perfil_fecha_fin)) : "",
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre,
                        'rango_fecha_ini' => $v->rango_fecha_ini != "" ? date("d-m-Y", strtotime($v->rango_fecha_ini)) : "",
                        'rango_fecha_fin' => $v->rango_fecha_fin != "" ? date("d-m-Y", strtotime($v->rango_fecha_fin)) : "",
                        /*'a_estado'=>$v->a_estado,
                        'a_estado_descripcion'=>($v->a_estado!=null)?$v->a_estado_descripcion:"SIN GENERAR",
                        'a_estado_global'=>$v->a_estado_global,
                        'b_estado'=>$v->b_estado,
                        'b_estado_descripcion'=>($v->b_estado!=null)?$v->b_estado_descripcion:"SIN GENERAR",
                        'b_estado_global'=>$v->b_estado_global,*/
                        'cruzada_estado' => $v->cruzada_estado,
                        'cruzada_estado_descripcion' => $v->cruzada_estado_descripcion,
                        'cruzada_estado_global' => $v->cruzada_estado_global
                    );
                }
            }
        }
        echo json_encode($horariosymarcacionesgenerados);
    }

    /**
     * Función para la generación del registro de marcación PREVISTA para un registro de relación laboral, gestión y mes determinados.
     */
    function generarmarcacionprevistaAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0
            && isset($_POST["gestion"]) && $_POST["gestion"] > 0
            && isset($_POST["mes"]) && $_POST["mes"] > 0
            && isset($_POST["fecha_ini"]) && $_POST["fecha_ini"] != ''
            && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"] != ''
        ) {
            #region Edición de Registro
            $idRelaboral = $_POST["id_relaboral"];
            $gestion = $_POST["gestion"];
            $mes = $_POST["mes"];
            $fechaIni = "01-" . $mes . "-" . $gestion;
            $fechaFin = $_POST["fecha_fin"];
            $clasemarcacion = $_POST["clasemarcacion"];
            $objFCL = new Fcalendariolaboral();
            $objM = new Fmarcaciones();
            $ultimoDia = 0;
            /**
             * Se hace un control de la existencia de al menos una marcación mixta inicial en el rango del mes completo a objeto de
             * prever el registro de marcaciones mixtas iniciales (Marcaciones de salida que proceden del turno del día anterior y
             * que salen del registro de marcaciones normales de salidas establecidas pues genera incoherencia.
             */
            //$existeMarcacionCruzadaMixtaInicialEnMes = $objM->controlExisteMarcacionMixtaInicialEnRango($idRelaboral,$fechaIni,$fechaFin);
            $existeMarcacionCruzadaMixtaInicialEnMes = $objM->controlExisteMarcacionMixtaInicialEnGestionMes($idRelaboral, $gestion, $mes);
            $fechas = $objM->getUltimaFecha($mes, $gestion);
            if (is_object($fechas) && $fechas->count() > 0) {
                foreach ($fechas as $fecha) {
                    $arrFecha = explode("-", $fecha->f_ultimo_dia_mes);
                    $ultimoDia = $arrFecha[2];
                    $fechaFin = $ultimoDia . "-" . $mes . "-" . $gestion;
                }
            }
            $cantidadGrupos = 0;
            $entradas = 0;
            $salidas = 0;
            $objRango = new Ffechasrango();
            $rangoFechas = $objRango->getAll($fechaIni, $fechaFin);
            $matrizHorarios = array();
            $matrizIdCalendarios = array();
            $matrizEstados = array();
            $matrizDiasSemana = array();
            $matrizHorariosCruzados = array();
            $matrizIdCalendariosHorariosCruzados = array();
            $matrizEstadosCruzados = array();
            $swIncluyeOtroMes = false;
            if ($rangoFechas->count() > 0) {
                #region Estableciendo los valores para las variables del objeto
                foreach ($rangoFechas as $rango) {
                    $resul = $objFCL->getAllRegisteredByPerfilAndRelaboralRangoFechas(0, $idRelaboral, $rango->fecha, $rango->fecha);
                    $turnoaux = 0;
                    $grupoaux = 0;
                    if ($resul->count() > 0) {
                        foreach ($resul as $v) {
                            $arrFecha = explode("-", $rango->fecha);
                            $diaaux = intval($arrFecha[2]);
                            if (($v->tipo_horario != 3 && $rango->dia != 0 && $rango->dia != 6) || $v->tipo_horario == 3) {

                                $matrizDiasSemana[$diaaux] = $rango->dia;
                                $turnoaux++;
                                $grupoaux++;
                                $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_entrada;
                                $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                                    if ($v->horario_cruzado == 1) {
                                        /**
                                         * Es necesario determinar que NO se puede tener dos perfiles en un mismo día.
                                         * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                         */
                                        $grupoaux++;
                                        $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = null;
                                        $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = null;
                                        $matrizEstados[$diaaux][$turnoaux][$grupoaux] = null;
                                        if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                        $matrizIdHorariosCruzados[$diaaux][1][-1] = $v->id_horariolaboral;
                                        $matrizHorariosCruzados[$diaaux][1][-1] = $v->hora_salida;
                                        $matrizIdCalendariosHorariosCruzados[$diaaux][1][-1] = $v->id_calendariolaboral;
                                        $matrizEstadosCruzados[$diaaux][1][-1] = 1;
                                    } else {
                                        /**
                                         * Es necesario determinar que NO se puede tener dos perfiles en un mismo día.
                                         * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                         */
                                        $grupoaux++;
                                        $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                        $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                        $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                        if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                        $matrizIdHorariosCruzados[$diaaux][1][-1] = null;
                                        $matrizHorariosCruzados[$diaaux][1][-1] = null;
                                        $matrizIdCalendariosHorariosCruzados[$diaaux][1][-1] = null;
                                        $matrizEstadosCruzados[$diaaux][1][-1] = null;
                                    }
                                } else {
                                    /**
                                     * Es necesario determinar que no se puede tener dos perfiles en un mismo día.
                                     * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                     */
                                    $grupoaux++;
                                    $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                    $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                    $matrizIdHorarios[$diaaux][$turnoaux][$grupoaux] = $v->id_horariolaboral;
                                    $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                    $matrizInicioRangoSalida[$diaaux][$turnoaux][$grupoaux] = $v->hora_inicio_rango_sal;
                                    $matrizFinalRangoSalida[$diaaux][$turnoaux][$grupoaux] = $v->hora_final_rango_sal;
                                    if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                    /**
                                     * Se verifica que haya entrada en un día y salida en otro.
                                     */
                                    if (strtotime($v->hora_entrada) > strtotime($v->hora_salida)) {
                                        $matrizHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                        $matrizIdCalendariosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                        $matrizEstadosCruzados[$diaaux][$turnoaux][$grupoaux] = 1;
                                        $matrizIdHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_horariolaboral;
                                        $matrizInicioRangoSalidaCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_inicio_rango_sal;
                                        $matrizFinalRangoSalidaCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_final_rango_sal;
                                        if ($diaaux == $ultimoDia) $swIncluyeOtroMes = true;
                                    }
                                }
                            }
                        }
                    }
                }

                #region Baja de Registros para descartar registros que ya no sean útiles
                $db = $this->getDI()->get('db');
                $sql = "UPDATE horariosymarcaciones SET baja_logica = 0,";
                $sql .= "d1=null,calendariolaboral1_id=null,";
                $sql .= "d2=null,calendariolaboral2_id=null,";
                $sql .= "d3=null,calendariolaboral3_id=null,";
                $sql .= "d4=null,calendariolaboral4_id=null,";
                $sql .= "d5=null,calendariolaboral5_id=null,";
                $sql .= "d6=null,calendariolaboral6_id=null,";
                $sql .= "d7=null,calendariolaboral7_id=null,";
                $sql .= "d8=null,calendariolaboral8_id=null,";
                $sql .= "d9=null,calendariolaboral9_id=null,";
                $sql .= "d10=null,calendariolaboral10_id=null,";
                $sql .= "d11=null,calendariolaboral11_id=null,";
                $sql .= "d12=null,calendariolaboral12_id=null,";
                $sql .= "d13=null,calendariolaboral13_id=null,";
                $sql .= "d14=null,calendariolaboral14_id=null,";
                $sql .= "d15=null,calendariolaboral15_id=null,";
                $sql .= "d16=null,calendariolaboral16_id=null,";
                $sql .= "d17=null,calendariolaboral17_id=null,";
                $sql .= "d18=null,calendariolaboral18_id=null,";
                $sql .= "d19=null,calendariolaboral19_id=null,";
                $sql .= "d20=null,calendariolaboral20_id=null,";
                $sql .= "d21=null,calendariolaboral21_id=null,";
                $sql .= "d22=null,calendariolaboral22_id=null,";
                $sql .= "d23=null,calendariolaboral23_id=null,";
                $sql .= "d24=null,calendariolaboral24_id=null,";
                $sql .= "d25=null,calendariolaboral25_id=null,";
                $sql .= "d26=null,calendariolaboral26_id=null,";
                $sql .= "d27=null,calendariolaboral27_id=null,";
                $sql .= "d28=null,calendariolaboral28_id=null,";
                $sql .= "d29=null,calendariolaboral29_id=null,";
                $sql .= "d30=null,calendariolaboral30_id=null,";
                $sql .= "d31=null,calendariolaboral31_id=null ";
                $sql .= "WHERE relaboral_id=" . $idRelaboral;
                $sql .= " AND gestion = " . $gestion . " AND mes=" . $mes . " AND modalidadmarcacion_id IN (1,4)";
                $db->execute($sql);
                #enregion
            }
            /**
             * Se contabilizan el número de grupos existentes, el mínimo debería ser 2
             */
            if (count($cantidadGrupos) > 0 && $cantidadGrupos % 2 == 0) {
                for ($i = 2; $i <= $cantidadGrupos; $i = $i + 2) {
                    $turno = $i / 2;
                    $grupoA = $i - 1;
                    $grupoB = $i;
                    $consultaEntrada = "relaboral_id=" . $idRelaboral . " AND ";
                    /**
                     * Se crea una consulta adicional en caso de existir un horario cruzado,
                     * es decir, que ingrese en un día y culminé este mismo turno al día siguiente.
                     */
                    $consultaSalidaAux = "relaboral_id=" . $idRelaboral . " AND ";

                    $consultaEntrada .= "gestion=" . $gestion . " AND ";
                    $consultaSalidaAux .= "gestion=" . $gestion . " AND ";

                    $consultaEntrada .= "mes=" . $mes . " AND ";
                    $consultaEntrada .= "turno=" . $turno . " AND ";

                    $consultaSalidaAux .= "mes=" . $mes . " AND ";
                    $consultaSalidaAux .= "turno=1 AND ";

                    $consultaSalida = $consultaEntrada;

                    $consultaEntrada .= "grupo=" . $grupoA . " AND ";
                    $consultaSalida .= "grupo=" . $grupoB . " AND ";
                    $consultaSalidaAux .= "grupo=-1 AND ";

                    $consultaEntrada .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaSalida .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaSalidaAux .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaEntrada .= "modalidadmarcacion_id = 1 AND ";
                    $consultaSalida .= "modalidadmarcacion_id = 4 AND ";
                    $consultaSalidaAux .= "modalidadmarcacion_id = 4 AND ";
                    /*$consultaEntrada .= "estado>=1 AND baja_logica=1 ";
                    $consultaSalida .= "estado>=1 AND baja_logica=1 ";
                    $consultaSalidaAux .= "estado>=1 AND baja_logica=1 ";*/
                    /**
                     * Inicialmente se habian puesto con baja_logica todos los registros referentes para que no aparecieran en los reportes
                     * si no corresponden, pero si corresponden vuelven simplemente a ser activados.
                     */
                    $consultaEntrada .= "estado>=1";
                    $consultaSalida .= "estado>=1";
                    $consultaSalidaAux .= "estado>=1";
                    /**
                     * Se hace una consulta para ver los registro de entrada y salida válidos
                     */
                    $objMEntrada = Horariosymarcaciones::findFirst(array($consultaEntrada));
                    $objMSalida = Horariosymarcaciones::findFirst(array($consultaSalida));
                    /**
                     * En caso de requerirse la generación adicional del registro de marcación prevista
                     * para un grupo que almacenará los registros de salida del día previo dado que existe
                     * al menos una marcacion cruzada inicial en todo el mes.
                     */
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux = Horariosymarcaciones::findFirst(array($consultaSalidaAux));
                    }

                    if (!is_object($objMEntrada)) {
                        $objMEntrada = new Horariosymarcaciones();
                        $objMEntrada->relaboral_id = $idRelaboral;
                        $objMEntrada->gestion = $gestion;
                        $objMEntrada->mes = $mes;
                        $objMEntrada->clasemarcacion = $clasemarcacion;
                        $objMEntrada->user_reg_id = $user_reg_id;
                        $objMEntrada->fecha_reg = $hoy;
                        $objMEntrada->estado1 = null;
                        $objMEntrada->estado2 = null;
                        $objMEntrada->estado3 = null;
                        $objMEntrada->estado4 = null;
                        $objMEntrada->estado5 = null;
                        $objMEntrada->estado6 = null;
                        $objMEntrada->estado7 = null;
                        $objMEntrada->estado8 = null;
                        $objMEntrada->estado9 = null;
                        $objMEntrada->estado10 = null;
                        $objMEntrada->estado11 = null;
                        $objMEntrada->estado12 = null;
                        $objMEntrada->estado13 = null;
                        $objMEntrada->estado14 = null;
                        $objMEntrada->estado15 = null;
                        $objMEntrada->estado16 = null;
                        $objMEntrada->estado17 = null;
                        $objMEntrada->estado18 = null;
                        $objMEntrada->estado19 = null;
                        $objMEntrada->estado20 = null;
                        $objMEntrada->estado21 = null;
                        $objMEntrada->estado22 = null;
                        $objMEntrada->estado23 = null;
                        $objMEntrada->estado24 = null;
                        $objMEntrada->estado25 = null;
                        $objMEntrada->estado26 = null;
                        $objMEntrada->estado27 = null;
                        $objMEntrada->estado28 = null;
                        $objMEntrada->estado29 = null;
                        $objMEntrada->estado30 = null;
                        $objMEntrada->estado31 = null;
                    } else {
                        $objMEntrada->user_mod_id = $user_reg_id;
                        $objMEntrada->fecha_mod = $hoy;
                    }
                    if (!is_object($objMSalida)) {
                        $objMSalida = new Horariosymarcaciones();
                        $objMSalida->relaboral_id = $idRelaboral;
                        $objMSalida->gestion = $gestion;
                        $objMSalida->mes = $mes;
                        $objMSalida->clasemarcacion = $clasemarcacion;
                        $objMSalida->user_reg_id = $user_reg_id;
                        $objMSalida->fecha_reg = $hoy;
                        $objMSalida->estado1 = null;
                        $objMSalida->estado2 = null;
                        $objMSalida->estado3 = null;
                        $objMSalida->estado4 = null;
                        $objMSalida->estado5 = null;
                        $objMSalida->estado6 = null;
                        $objMSalida->estado7 = null;
                        $objMSalida->estado8 = null;
                        $objMSalida->estado9 = null;
                        $objMSalida->estado10 = null;
                        $objMSalida->estado11 = null;
                        $objMSalida->estado12 = null;
                        $objMSalida->estado13 = null;
                        $objMSalida->estado14 = null;
                        $objMSalida->estado15 = null;
                        $objMSalida->estado16 = null;
                        $objMSalida->estado17 = null;
                        $objMSalida->estado18 = null;
                        $objMSalida->estado19 = null;
                        $objMSalida->estado20 = null;
                        $objMSalida->estado21 = null;
                        $objMSalida->estado22 = null;
                        $objMSalida->estado23 = null;
                        $objMSalida->estado24 = null;
                        $objMSalida->estado25 = null;
                        $objMSalida->estado26 = null;
                        $objMSalida->estado27 = null;
                        $objMSalida->estado28 = null;
                        $objMSalida->estado29 = null;
                        $objMSalida->estado30 = null;
                        $objMSalida->estado31 = null;
                    } else {
                        $objMSalida->user_mod_id = $user_mod_id;
                        $objMSalida->fecha_mod = $hoy;
                    }
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        if (!is_object($objMSalidaAux)) {
                            $objMSalidaAux = new Horariosymarcaciones();
                            $objMSalidaAux->relaboral_id = $idRelaboral;
                            $objMSalidaAux->gestion = $gestion;
                            $objMSalidaAux->mes = $mes;
                            $objMSalidaAux->clasemarcacion = $clasemarcacion;
                            $objMSalidaAux->user_reg_id = $user_reg_id;
                            $objMSalidaAux->fecha_reg = $hoy;
                            $objMSalidaAux->estado1 = null;
                            $objMSalidaAux->estado2 = null;
                            $objMSalidaAux->estado3 = null;
                            $objMSalidaAux->estado4 = null;
                            $objMSalidaAux->estado5 = null;
                            $objMSalidaAux->estado6 = null;
                            $objMSalidaAux->estado7 = null;
                            $objMSalidaAux->estado8 = null;
                            $objMSalidaAux->estado9 = null;
                            $objMSalidaAux->estado10 = null;
                            $objMSalidaAux->estado11 = null;
                            $objMSalidaAux->estado12 = null;
                            $objMSalidaAux->estado13 = null;
                            $objMSalidaAux->estado14 = null;
                            $objMSalidaAux->estado15 = null;
                            $objMSalidaAux->estado16 = null;
                            $objMSalidaAux->estado17 = null;
                            $objMSalidaAux->estado18 = null;
                            $objMSalidaAux->estado19 = null;
                            $objMSalidaAux->estado20 = null;
                            $objMSalidaAux->estado21 = null;
                            $objMSalidaAux->estado22 = null;
                            $objMSalidaAux->estado23 = null;
                            $objMSalidaAux->estado24 = null;
                            $objMSalidaAux->estado25 = null;
                            $objMSalidaAux->estado26 = null;
                            $objMSalidaAux->estado27 = null;
                            $objMSalidaAux->estado28 = null;
                            $objMSalidaAux->estado29 = null;
                            $objMSalidaAux->estado30 = null;
                            $objMSalidaAux->estado31 = null;
                        } else {
                            $objMSalidaAux->user_mod_id = $user_mod_id;
                            $objMSalidaAux->fecha_mod = $hoy;
                        }
                    }
                    /**
                     * Se reinician todos los valores a objeto de no dejar rastros de los anteriores valores.
                     * Sin embargo, el estado para un día en particular esta ya ELABORADO(2), APROBADO (3) o PLANILLADO (4) ya no se modificará el dato
                     */
                    if ($objMEntrada->estado1 == null || $objMEntrada->estado1 <= 1) {
                        $objMEntrada->d1 = null;
                        $objMEntrada->calendariolaboral1_id = null;
                        $objMEntrada->estado1 = null;
                    }
                    if ($objMEntrada->estado2 == null || $objMEntrada->estado2 <= 1) {
                        $objMEntrada->d2 = null;
                        $objMEntrada->calendariolaboral2_id = null;
                        $objMEntrada->estado2 = null;
                    }
                    if ($objMEntrada->estado3 == null || $objMEntrada->estado3 <= 1) {
                        $objMEntrada->d3 = null;
                        $objMEntrada->calendariolaboral3_id = null;
                        $objMEntrada->estado3 = null;
                    }
                    if ($objMEntrada->estado4 == null || $objMEntrada->estado4 <= 1) {
                        $objMEntrada->d4 = null;
                        $objMEntrada->calendariolaboral4_id = null;
                        $objMEntrada->estado4 = null;
                    }
                    if ($objMEntrada->estado5 == null || $objMEntrada->estado5 <= 1) {
                        $objMEntrada->d5 = null;
                        $objMEntrada->calendariolaboral5_id = null;
                        $objMEntrada->estado5 = null;
                    }
                    if ($objMEntrada->estado6 == null || $objMEntrada->estado6 <= 1) {
                        $objMEntrada->d6 = null;
                        $objMEntrada->calendariolaboral6_id = null;
                        $objMEntrada->estado6 = null;
                    }
                    if ($objMEntrada->estado7 == null || $objMEntrada->estado7 <= 1) {
                        $objMEntrada->d7 = null;
                        $objMEntrada->calendariolaboral7_id = null;
                        $objMEntrada->estado7 = null;
                    }
                    if ($objMEntrada->estado8 == null || $objMEntrada->estado8 <= 1) {
                        $objMEntrada->d8 = null;
                        $objMEntrada->calendariolaboral8_id = null;
                        $objMEntrada->estado8 = null;
                    }
                    if ($objMEntrada->estado9 == null || $objMEntrada->estado9 <= 1) {
                        $objMEntrada->d9 = null;
                        $objMEntrada->calendariolaboral9_id = null;
                        $objMEntrada->estado9 = null;
                    }
                    if ($objMEntrada->estado10 == null || $objMEntrada->estado10 <= 1) {
                        $objMEntrada->d10 = null;
                        $objMEntrada->calendariolaboral10_id = null;
                        $objMEntrada->estado10 = null;
                    }
                    if ($objMEntrada->estado11 == null || $objMEntrada->estado11 <= 1) {
                        $objMEntrada->d11 = null;
                        $objMEntrada->calendariolaboral11_id = null;
                        $objMEntrada->estado11 = null;
                    }
                    if ($objMEntrada->estado12 == null || $objMEntrada->estado12 <= 1) {
                        $objMEntrada->d12 = null;
                        $objMEntrada->calendariolaboral12_id = null;
                        $objMEntrada->estado12 = null;
                    }
                    if ($objMEntrada->estado13 == null || $objMEntrada->estado13 <= 1) {
                        $objMEntrada->d13 = null;
                        $objMEntrada->calendariolaboral13_id = null;
                        $objMEntrada->estado13 = null;
                    }
                    if ($objMEntrada->estado14 == null || $objMEntrada->estado14 <= 1) {
                        $objMEntrada->d14 = null;
                        $objMEntrada->calendariolaboral14_id = null;
                        $objMEntrada->estado14 = null;
                    }
                    if ($objMEntrada->estado15 == null || $objMEntrada->estado15 <= 1) {
                        $objMEntrada->d15 = null;
                        $objMEntrada->calendariolaboral15_id = null;
                        $objMEntrada->estado15 = null;
                    }
                    if ($objMEntrada->estado16 == null || $objMEntrada->estado16 <= 1) {
                        $objMEntrada->d16 = null;
                        $objMEntrada->calendariolaboral16_id = null;
                        $objMEntrada->estado16 = null;
                    }
                    if ($objMEntrada->estado17 == null || $objMEntrada->estado17 <= 1) {
                        $objMEntrada->d17 = null;
                        $objMEntrada->calendariolaboral17_id = null;
                        $objMEntrada->estado17 = null;
                    }
                    if ($objMEntrada->estado18 == null || $objMEntrada->estado18 <= 1) {
                        $objMEntrada->d18 = null;
                        $objMEntrada->calendariolaboral18_id = null;
                        $objMEntrada->estado18 = null;
                    }
                    if ($objMEntrada->estado19 == null || $objMEntrada->estado19 <= 1) {
                        $objMEntrada->d19 = null;
                        $objMEntrada->calendariolaboral19_id = null;
                        $objMEntrada->estado19 = null;
                    }
                    if ($objMEntrada->estado20 == null || $objMEntrada->estado20 <= 1) {
                        $objMEntrada->d20 = null;
                        $objMEntrada->calendariolaboral20_id = null;
                        $objMEntrada->estado20 = null;
                    }
                    if ($objMEntrada->estado21 == null || $objMEntrada->estado21 <= 1) {
                        $objMEntrada->d21 = null;
                        $objMEntrada->calendariolaboral21_id = null;
                        $objMEntrada->estado21 = null;
                    }
                    if ($objMEntrada->estado22 == null || $objMEntrada->estado22 <= 1) {
                        $objMEntrada->d22 = null;
                        $objMEntrada->calendariolaboral22_id = null;
                        $objMEntrada->estado22 = null;
                    }
                    if ($objMEntrada->estado23 == null || $objMEntrada->estado23 <= 1) {
                        $objMEntrada->d23 = null;
                        $objMEntrada->calendariolaboral23_id = null;
                        $objMEntrada->estado23 = null;
                    }
                    if ($objMEntrada->estado24 == null || $objMEntrada->estado24 <= 1) {
                        $objMEntrada->d24 = null;
                        $objMEntrada->calendariolaboral24_id = null;
                        $objMEntrada->estado24 = null;
                    }
                    if ($objMEntrada->estado25 == null || $objMEntrada->estado25 <= 1) {
                        $objMEntrada->d25 = null;
                        $objMEntrada->calendariolaboral25_id = null;
                        $objMEntrada->estado25 = null;
                    }
                    if ($objMEntrada->estado26 == null || $objMEntrada->estado26 <= 1) {
                        $objMEntrada->d26 = null;
                        $objMEntrada->calendariolaboral26_id = null;
                        $objMEntrada->estado26 = null;
                    }
                    if ($objMEntrada->estado27 == null || $objMEntrada->estado27 <= 1) {
                        $objMEntrada->d27 = null;
                        $objMEntrada->calendariolaboral27_id = null;
                        $objMEntrada->estado27 = null;
                    }
                    if ($objMEntrada->estado28 == null || $objMEntrada->estado28 <= 1) {
                        $objMEntrada->d28 = null;
                        $objMEntrada->calendariolaboral28_id = null;
                        $objMEntrada->estado28 = null;
                    }
                    if ($objMEntrada->estado29 == null || $objMEntrada->estado29 <= 1) {
                        $objMEntrada->d29 = null;
                        $objMEntrada->calendariolaboral29_id = null;
                        $objMEntrada->estado29 = null;
                    }
                    if ($objMEntrada->estado30 == null || $objMEntrada->estado30 <= 1) {
                        $objMEntrada->d30 = null;
                        $objMEntrada->calendariolaboral30_id = null;
                        $objMEntrada->estado30 = null;
                    }
                    if ($objMEntrada->estado31 == null || $objMEntrada->estado31 <= 1) {
                        $objMEntrada->d31 = null;
                        $objMEntrada->calendariolaboral31_id = null;
                        $objMEntrada->estado31 = null;
                    }

                    if ($objMSalida->estado1 == null || $objMSalida->estado1 <= 1) {
                        $objMSalida->d1 = null;
                        $objMSalida->calendariolaboral1_id = null;
                        $objMSalida->estado1 = null;
                    }
                    if ($objMSalida->estado2 == null || $objMSalida->estado2 <= 1) {
                        $objMSalida->d2 = null;
                        $objMSalida->calendariolaboral2_id = null;
                        $objMSalida->estado2 = null;
                    }
                    if ($objMSalida->estado3 == null || $objMSalida->estado3 <= 1) {
                        $objMSalida->d3 = null;
                        $objMSalida->calendariolaboral3_id = null;
                        $objMSalida->estado3 = null;
                    }
                    if ($objMSalida->estado4 == null || $objMSalida->estado4 <= 1) {
                        $objMSalida->d4 = null;
                        $objMSalida->calendariolaboral4_id = null;
                        $objMSalida->estado4 = null;
                    }
                    if ($objMSalida->estado5 == null || $objMSalida->estado5 <= 1) {
                        $objMSalida->d5 = null;
                        $objMSalida->calendariolaboral5_id = null;
                        $objMSalida->estado5 = null;
                    }
                    if ($objMSalida->estado6 == null || $objMSalida->estado6 <= 1) {
                        $objMSalida->d6 = null;
                        $objMSalida->calendariolaboral6_id = null;
                        $objMSalida->estado6 = null;
                    }
                    if ($objMSalida->estado7 == null || $objMSalida->estado7 <= 1) {
                        $objMSalida->d7 = null;
                        $objMSalida->calendariolaboral7_id = null;
                        $objMSalida->estado7 = null;
                    }
                    if ($objMSalida->estado8 == null || $objMSalida->estado8 <= 1) {
                        $objMSalida->d8 = null;
                        $objMSalida->calendariolaboral8_id = null;
                        $objMSalida->estado8 = null;
                    }
                    if ($objMSalida->estado9 == null || $objMSalida->estado9 <= 1) {
                        $objMSalida->d9 = null;
                        $objMSalida->calendariolaboral9_id = null;
                        $objMSalida->estado9 = null;
                    }
                    if ($objMSalida->estado10 == null || $objMSalida->estado10 <= 1) {
                        $objMSalida->d10 = null;
                        $objMSalida->calendariolaboral10_id = null;
                        $objMSalida->estado10 = null;
                    }
                    if ($objMSalida->estado11 == null || $objMSalida->estado11 <= 1) {
                        $objMSalida->d11 = null;
                        $objMSalida->calendariolaboral11_id = null;
                        $objMSalida->estado11 = null;
                    }
                    if ($objMSalida->estado12 == null || $objMSalida->estado12 <= 1) {
                        $objMSalida->d12 = null;
                        $objMSalida->calendariolaboral12_id = null;
                        $objMSalida->estado12 = null;
                    }
                    if ($objMSalida->estado13 == null || $objMSalida->estado13 <= 1) {
                        $objMSalida->d13 = null;
                        $objMSalida->calendariolaboral13_id = null;
                        $objMSalida->estado13 = null;
                    }
                    if ($objMSalida->estado14 == null || $objMSalida->estado14 <= 1) {
                        $objMSalida->d14 = null;
                        $objMSalida->calendariolaboral14_id = null;
                        $objMSalida->estado14 = null;
                    }
                    if ($objMSalida->estado15 == null || $objMSalida->estado15 <= 1) {
                        $objMSalida->d15 = null;
                        $objMSalida->calendariolaboral15_id = null;
                        $objMSalida->estado15 = null;
                    }
                    if ($objMSalida->estado16 == null || $objMSalida->estado16 <= 1) {
                        $objMSalida->d16 = null;
                        $objMSalida->calendariolaboral16_id = null;
                        $objMSalida->estado16 = null;
                    }
                    if ($objMSalida->estado17 == null || $objMSalida->estado17 <= 1) {
                        $objMSalida->d17 = null;
                        $objMSalida->calendariolaboral17_id = null;
                        $objMSalida->estado17 = null;
                    }
                    if ($objMSalida->estado18 == null || $objMSalida->estado18 <= 1) {
                        $objMSalida->d18 = null;
                        $objMSalida->calendariolaboral18_id = null;
                        $objMSalida->estado18 = null;
                    }
                    if ($objMSalida->estado19 == null || $objMSalida->estado19 <= 1) {
                        $objMSalida->d19 = null;
                        $objMSalida->calendariolaboral19_id = null;
                        $objMSalida->estado19 = null;
                    }
                    if ($objMSalida->estado20 == null || $objMSalida->estado20 <= 1) {
                        $objMSalida->d20 = null;
                        $objMSalida->calendariolaboral20_id = null;
                        $objMSalida->estado20 = null;
                    }
                    if ($objMSalida->estado21 == null || $objMSalida->estado21 <= 1) {
                        $objMSalida->d21 = null;
                        $objMSalida->calendariolaboral21_id = null;
                        $objMSalida->estado21 = null;
                    }
                    if ($objMSalida->estado22 == null || $objMSalida->estado22 <= 1) {
                        $objMSalida->d22 = null;
                        $objMSalida->calendariolaboral22_id = null;
                        $objMSalida->estado22 = null;
                    }
                    if ($objMSalida->estado23 == null || $objMSalida->estado23 <= 1) {
                        $objMSalida->d23 = null;
                        $objMSalida->calendariolaboral23_id = null;
                        $objMSalida->estado23 = null;
                    }
                    if ($objMSalida->estado24 == null || $objMSalida->estado24 <= 1) {
                        $objMSalida->d24 = null;
                        $objMSalida->calendariolaboral24_id = null;
                        $objMSalida->estado24 = null;
                    }
                    if ($objMSalida->estado25 == null || $objMSalida->estado25 <= 1) {
                        $objMSalida->d25 = null;
                        $objMSalida->calendariolaboral25_id = null;
                        $objMSalida->estado25 = null;
                    }
                    if ($objMSalida->estado26 == null || $objMSalida->estado26 <= 1) {
                        $objMSalida->d26 = null;
                        $objMSalida->calendariolaboral26_id = null;
                        $objMSalida->estado26 = null;
                    }
                    if ($objMSalida->estado27 == null || $objMSalida->estado27 <= 1) {
                        $objMSalida->d27 = null;
                        $objMSalida->calendariolaboral27_id = null;
                        $objMSalida->estado27 = null;
                    }
                    if ($objMSalida->estado28 == null || $objMSalida->estado28 <= 1) {
                        $objMSalida->d28 = null;
                        $objMSalida->calendariolaboral28_id = null;
                        $objMSalida->estado28 = null;
                    }
                    if ($objMSalida->estado29 == null || $objMSalida->estado29 <= 1) {
                        $objMSalida->d29 = null;
                        $objMSalida->calendariolaboral29_id = null;
                        $objMSalida->estado29 = null;
                    }
                    if ($objMSalida->estado30 == null || $objMSalida->estado30 <= 1) {
                        $objMSalida->d30 = null;
                        $objMSalida->calendariolaboral30_id = null;
                        $objMSalida->estado30 = null;
                    }
                    if ($objMSalida->estado31 == null || $objMSalida->estado31 <= 1) {
                        $objMSalida->d31 = null;
                        $objMSalida->calendariolaboral31_id = null;
                        $objMSalida->estado31 = null;
                    }

                    $objMEntrada->turno = $turno;
                    $objMSalida->turno = $turno;
                    $objMEntrada->grupo = $grupoA;
                    $objMSalida->grupo = $grupoB;
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux->turno = 1;
                        $objMSalidaAux->grupo = -1;
                    }
                    for ($dia = 1; $dia <= 31; $dia++) {
                        if (isset($matrizDiasSemana[$dia])) {
                            if (isset($matrizHorarios[$dia][$turno][$grupoA])) {
                                switch ($dia) {
                                    case 1 :
                                        if ($objMEntrada->estado1 == null || $objMEntrada->estado1 <= 1) {
                                            $objMEntrada->calendariolaboral1_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado1 = 1;
                                            $objMEntrada->d1 = $matrizHorarios[$dia][$turno][$grupoA];
                                            if (!isset($matrizHorariosCruzados[$dia][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral1_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                                $objMSalida->estado1 = $matrizEstados[$dia][$turno][$grupoB];
                                                $objMSalida->d1 = $matrizHorarios[$dia][$turno][$grupoB];
                                            } else {
                                                if ($existeMarcacionCruzadaMixtaInicialEnMes == 0) {
                                                    $ctrlMarcacion = $objM->controlExisteMarcacionMixta($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                    if ($ctrlMarcacion == 3) {
                                                        if ($objMSalida->estado1 == null || $objMSalida->estado1 <= 1) {
                                                            $objHorario = new Horarioslaborales();
                                                            $objCalHor = $objHorario->obtenerUltimoIdCalendarioYHorarioCruzadoEnDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                            if (is_object($objCalHor)) {
                                                                $objMSalida->estado1 = 1;
                                                                $objMSalida->d1 = $objCalHor[0]->hora_salida;
                                                                $objMSalida->calendariolaboral1_id = $objCalHor[0]->id_calendariolaboral;
                                                            }
                                                        }
                                                    } else {
                                                        $objMSalida->calendariolaboral1_id = null;
                                                        $objMSalida->estado1 = null;
                                                        $objMSalida->d1 = null;
                                                    }
                                                } else {

                                                }
                                            }
                                        }
                                        break;
                                    case 2 :
                                        if ($objMEntrada->estado2 == null || $objMEntrada->estado2 <= 1) {
                                            $objMEntrada->calendariolaboral2_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado2 = 1;
                                            $objMEntrada->d2 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral2_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado2 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d2 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 3 :
                                        if ($objMEntrada->estado3 == null || $objMEntrada->estado3 <= 1) {
                                            $objMEntrada->calendariolaboral3_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado3 = 1;
                                            $objMEntrada->d3 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral3_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado3 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d3 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 4 :
                                        if ($objMEntrada->estado4 == null || $objMEntrada->estado4 <= 1) {
                                            $objMEntrada->calendariolaboral4_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado4 = 1;
                                            $objMEntrada->d4 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral4_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado4 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d4 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 5 :
                                        if ($objMEntrada->estado5 == null || $objMEntrada->estado5 <= 1) {
                                            $objMEntrada->calendariolaboral5_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado5 = 1;
                                            $objMEntrada->d5 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral5_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado5 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d5 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 6 :
                                        if ($objMEntrada->estado6 == null || $objMEntrada->estado6 <= 1) {
                                            $objMEntrada->calendariolaboral6_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado6 = 1;
                                            $objMEntrada->d6 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral6_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado6 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d6 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 7 :
                                        if ($objMEntrada->estado7 == null || $objMEntrada->estado7 <= 1) {
                                            $objMEntrada->calendariolaboral7_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado7 = 1;
                                            $objMEntrada->d7 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral7_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado7 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d7 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 8 :
                                        if ($objMEntrada->estado8 == null || $objMEntrada->estado8 <= 1) {
                                            $objMEntrada->calendariolaboral8_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado8 = 1;
                                            $objMEntrada->d8 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral8_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado8 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d8 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 9 :
                                        if ($objMEntrada->estado9 == null || $objMEntrada->estado9 <= 1) {
                                            $objMEntrada->calendariolaboral9_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado9 = 1;
                                            $objMEntrada->d9 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral9_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado9 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d9 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 10:
                                        if ($objMEntrada->estado10 == null || $objMEntrada->estado10 <= 1) {
                                            $objMEntrada->calendariolaboral10_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado10 = 1;
                                            $objMEntrada->d10 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral10_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado10 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d10 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 11:
                                        if ($objMEntrada->estado11 == null || $objMEntrada->estado11 <= 1) {
                                            $objMEntrada->calendariolaboral11_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado11 = 1;
                                            $objMEntrada->d11 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral11_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado11 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d11 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 12:
                                        if ($objMEntrada->estado12 == null || $objMEntrada->estado12 <= 1) {
                                            $objMEntrada->calendariolaboral12_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado12 = 1;
                                            $objMEntrada->d12 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral12_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado12 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d12 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 13:
                                        if ($objMEntrada->estado13 == null || $objMEntrada->estado13 <= 1) {
                                            $objMEntrada->calendariolaboral13_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado13 = 1;
                                            $objMEntrada->d13 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral13_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado13 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d13 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 14:
                                        if ($objMEntrada->estado14 == null || $objMEntrada->estado14 <= 1) {
                                            $objMEntrada->calendariolaboral14_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado14 = 1;
                                            $objMEntrada->d14 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral14_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado14 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d14 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 15:
                                        if ($objMEntrada->estado15 == null || $objMEntrada->estado15 <= 1) {
                                            $objMEntrada->calendariolaboral15_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado15 = 1;
                                            $objMEntrada->d15 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral15_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado15 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d15 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 16:
                                        if ($objMEntrada->estado16 == null || $objMEntrada->estado16 <= 1) {
                                            $objMEntrada->calendariolaboral16_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado16 = 1;
                                            $objMEntrada->d16 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral16_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado16 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d16 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 17:
                                        if ($objMEntrada->estado17 == null || $objMEntrada->estado17 <= 1) {
                                            $objMEntrada->calendariolaboral17_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado17 = 1;
                                            $objMEntrada->d17 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral17_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado17 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d17 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 18:
                                        if ($objMEntrada->estado18 == null || $objMEntrada->estado18 <= 1) {
                                            $objMEntrada->calendariolaboral18_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado18 = 1;
                                            $objMEntrada->d18 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral18_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado18 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d18 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 19:
                                        if ($objMEntrada->estado19 == null || $objMEntrada->estado19 <= 1) {
                                            $objMEntrada->calendariolaboral19_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado19 = 1;
                                            $objMEntrada->d19 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral19_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado19 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d19 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 20:
                                        if ($objMEntrada->estado20 == null || $objMEntrada->estado20 <= 1) {
                                            $objMEntrada->calendariolaboral20_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado20 = 1;
                                            $objMEntrada->d20 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral20_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado20 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d20 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 21:
                                        if ($objMEntrada->estado21 == null || $objMEntrada->estado21 <= 1) {
                                            $objMEntrada->calendariolaboral21_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado21 = 1;
                                            $objMEntrada->d21 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral21_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado21 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d21 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 22:
                                        if ($objMEntrada->estado22 == null || $objMEntrada->estado22 <= 1) {
                                            $objMEntrada->calendariolaboral22_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado22 = 1;
                                            $objMEntrada->d22 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral22_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado22 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d22 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 23:
                                        if ($objMEntrada->estado23 == null || $objMEntrada->estado23 <= 1) {
                                            $objMEntrada->calendariolaboral23_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado23 = 1;
                                            $objMEntrada->d23 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral23_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado23 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d23 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 24:
                                        if ($objMEntrada->estado24 == null || $objMEntrada->estado24 <= 1) {
                                            $objMEntrada->calendariolaboral24_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado24 = 1;
                                            $objMEntrada->d24 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral24_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado24 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d24 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 25:
                                        if ($objMEntrada->estado25 == null || $objMEntrada->estado25 <= 1) {
                                            $objMEntrada->calendariolaboral25_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado25 = 1;
                                            $objMEntrada->d25 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral25_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado25 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d25 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 26:
                                        if ($objMEntrada->estado26 == null || $objMEntrada->estado26 <= 1) {
                                            $objMEntrada->calendariolaboral26_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado26 = 1;
                                            $objMEntrada->d26 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral26_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado26 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d26 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 27:
                                        if ($objMEntrada->estado27 == null || $objMEntrada->estado27 <= 1) {
                                            $objMEntrada->calendariolaboral27_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado27 = 1;
                                            $objMEntrada->d27 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral27_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado27 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d27 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 28:
                                        if ($objMEntrada->estado28 == null || $objMEntrada->estado28 <= 1) {
                                            $objMEntrada->calendariolaboral28_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado28 = 1;
                                            $objMEntrada->d28 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral28_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado28 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d28 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 29:
                                        if ($ultimoDia >= 29 && $objMEntrada->estado29 == null || $objMEntrada->estado29 <= 1) {
                                            $objMEntrada->calendariolaboral29_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado29 = 1;
                                            $objMEntrada->d29 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral29_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado29 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d29 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 30:
                                        if ($ultimoDia >= 30 && $objMEntrada->estado30 == null || $objMEntrada->estado30 <= 1) {
                                            $objMEntrada->calendariolaboral30_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado30 = 1;
                                            $objMEntrada->d30 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral30_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado30 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d30 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 31:
                                        if ($ultimoDia >= 31 && $objMEntrada->estado31 == null || $objMEntrada->estado31 <= 1) {
                                            $objMEntrada->calendariolaboral31_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado31 = 1;
                                            $objMEntrada->d31 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral31_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado31 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d31 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                }
                            }
                        }
                        /**
                         * Calculo de las horas para las salidas con cruce
                         */
                        if (isset($matrizHorariosCruzados[$dia][$turno][$grupoB]) || isset($matrizHorariosCruzados[$dia - 1][$turno][$grupoB])) {

                            switch ($dia) {
                                case 1 :
                                    /**
                                     * Asignación momentanea
                                     */
                                    /*$objMSalida->calendariolaboral1_id=null;
                                        $objMSalida->estado1=null;
                                        $objMSalida->d1=null;*/
                                    break;
                                case 2 :
                                    if ($objMSalida->estado2 == null || $objMSalida->estado2 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral2_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado2 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d2 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral2_id = null;
                                            $objMSalida->estado2 = null;
                                            $objMSalida->d2 = null;
                                        }
                                    }
                                    break;
                                case 3 :
                                    if ($objMSalida->estado3 == null || $objMSalida->estado3 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral3_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado3 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d3 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral3_id = null;
                                            $objMSalida->estado3 = null;
                                            $objMSalida->d3 = null;
                                        }
                                    }
                                    break;
                                case 4 :
                                    if ($objMSalida->estado4 == null || $objMSalida->estado4 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral4_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado4 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d4 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral4_id = null;
                                            $objMSalida->estado4 = null;
                                            $objMSalida->d4 = null;
                                        }
                                    }
                                    break;
                                case 5 :
                                    if ($objMSalida->estado5 == null || $objMSalida->estado5 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral5_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado5 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d5 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral5_id = null;
                                            $objMSalida->estado5 = null;
                                            $objMSalida->d5 = null;
                                        }
                                    }
                                    break;
                                case 6 :
                                    if ($objMSalida->estado6 == null || $objMSalida->estado6 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral6_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado6 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d6 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral6_id = null;
                                            $objMSalida->estado6 = null;
                                            $objMSalida->d6 = null;
                                        }
                                    }
                                    break;
                                case 7 :
                                    if ($objMSalida->estado7 == null || $objMSalida->estado7 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral7_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado7 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d7 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral7_id = null;
                                            $objMSalida->estado7 = null;
                                            $objMSalida->d7 = null;
                                        }
                                    }
                                    break;
                                case 8 :
                                    if ($objMSalida->estado8 == null || $objMSalida->estado8 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral8_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado8 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d8 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral8_id = null;
                                            $objMSalida->estado8 = null;
                                            $objMSalida->d8 = null;
                                        }
                                    }
                                    break;
                                case 9 :
                                    if ($objMSalida->estado9 == null || $objMSalida->estado9 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral9_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado9 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d9 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral9_id = null;
                                            $objMSalida->estado9 = null;
                                            $objMSalida->d9 = null;
                                        }
                                    }
                                    break;
                                case 10:
                                    if ($objMSalida->estado10 == null || $objMSalida->estado10 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral10_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado10 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d10 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral10_id = null;
                                            $objMSalida->estado10 = null;
                                            $objMSalida->d10 = null;
                                        }
                                    }
                                    break;
                                case 11:
                                    if ($objMSalida->estado11 == null || $objMSalida->estado11 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral11_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado11 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d11 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral11_id = null;
                                            $objMSalida->estado11 = null;
                                            $objMSalida->d11 = null;
                                        }
                                    }
                                    break;
                                case 12:
                                    if ($objMSalida->estado12 == null || $objMSalida->estado12 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral12_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado12 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d12 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral12_id = null;
                                            $objMSalida->estado12 = null;
                                            $objMSalida->d12 = null;
                                        }
                                    }
                                    break;
                                case 13:
                                    if ($objMSalida->estado13 == null || $objMSalida->estado13 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral13_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado13 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d13 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral13_id = null;
                                            $objMSalida->estado13 = null;
                                            $objMSalida->d13 = null;
                                        }
                                    }
                                    break;
                                case 14:
                                    if ($objMSalida->estado14 == null || $objMSalida->estado14 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral14_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado14 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d14 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral14_id = null;
                                            $objMSalida->estado14 = null;
                                            $objMSalida->d14 = null;
                                        }
                                    }
                                    break;
                                case 15:
                                    if ($objMSalida->estado15 == null || $objMSalida->estado15 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral15_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado15 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d15 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral15_id = null;
                                            $objMSalida->estado15 = null;
                                            $objMSalida->d15 = null;
                                        }
                                    }
                                    break;
                                case 16:
                                    if ($objMSalida->estado16 == null || $objMSalida->estado16 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral16_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado16 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d16 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral16_id = null;
                                            $objMSalida->estado16 = null;
                                            $objMSalida->d16 = null;
                                        }
                                    }
                                    break;
                                case 17:
                                    if ($objMSalida->estado17 == null || $objMSalida->estado17 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral17_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado17 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d17 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral17_id = null;
                                            $objMSalida->estado17 = null;
                                            $objMSalida->d17 = null;
                                        }
                                    }
                                    break;
                                case 18:
                                    if ($objMSalida->estado18 == null || $objMSalida->estado18 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral18_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado18 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d18 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral18_id = null;
                                            $objMSalida->estado18 = null;
                                            $objMSalida->d18 = null;
                                        }
                                    }
                                    break;
                                case 19:
                                    if ($objMSalida->estado19 == null || $objMSalida->estado19 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral19_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado19 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d19 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral19_id = null;
                                            $objMSalida->estado19 = null;
                                            $objMSalida->d19 = null;
                                        }
                                    }
                                    break;
                                case 20:
                                    if ($objMSalida->estado20 == null || $objMSalida->estado20 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral20_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado20 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d20 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral20_id = null;
                                            $objMSalida->estado20 = null;
                                            $objMSalida->d20 = null;
                                        }
                                    }
                                    break;
                                case 21:
                                    if ($objMSalida->estado21 == null || $objMSalida->estado21 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral21_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado21 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d21 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral21_id = null;
                                            $objMSalida->estado21 = null;
                                            $objMSalida->d21 = null;
                                        }
                                    }
                                    break;
                                case 22:
                                    if ($objMSalida->estado22 == null || $objMSalida->estado22 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral22_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado22 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d22 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral22_id = null;
                                            $objMSalida->estado22 = null;
                                            $objMSalida->d22 = null;
                                        }
                                    }
                                    break;
                                case 23:
                                    if ($objMSalida->estado23 == null || $objMSalida->estado23 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral23_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado23 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d23 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral23_id = null;
                                            $objMSalida->estado23 = null;
                                            $objMSalida->d23 = null;
                                        }
                                    }
                                    break;
                                case 24:
                                    if ($objMSalida->estado24 == null || $objMSalida->estado24 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral24_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado24 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d24 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral24_id = null;
                                            $objMSalida->estado24 = null;
                                            $objMSalida->d24 = null;
                                        }
                                    }
                                    break;
                                case 25:
                                    if ($objMSalida->estado25 == null || $objMSalida->estado25 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral25_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado25 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d25 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral25_id = null;
                                            $objMSalida->estado25 = null;
                                            $objMSalida->d25 = null;
                                        }
                                    }
                                    break;
                                case 26:
                                    if ($objMSalida->estado26 == null || $objMSalida->estado26 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral26_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado26 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d26 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral26_id = null;
                                            $objMSalida->estado26 = null;
                                            $objMSalida->d26 = null;
                                        }
                                    }
                                    break;
                                case 27:
                                    if ($objMSalida->estado27 == null || $objMSalida->estado27 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral27_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado27 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d27 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral27_id = null;
                                            $objMSalida->estado27 = null;
                                            $objMSalida->d27 = null;
                                        }
                                    }
                                    break;
                                case 28:
                                    if ($objMSalida->estado28 == null || $objMSalida->estado28 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral28_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado28 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d28 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral28_id = null;
                                            $objMSalida->estado28 = null;
                                            $objMSalida->d28 = null;
                                        }
                                    }
                                    break;
                                case 29:
                                    if ($ultimoDia >= 29 && ($objMSalida->estado29 == null || $objMSalida->estado29 <= 1)) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral29_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado29 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d29 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral29_id = null;
                                            $objMSalida->estado29 = null;
                                            $objMSalida->d29 = null;
                                        }
                                    }
                                    break;
                                case 30:
                                    if ($ultimoDia >= 30 && ($objMSalida->estado30 == null || $objMSalida->estado30 <= 1)) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral30_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado30 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d30 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral30_id = null;
                                            $objMSalida->estado30 = null;
                                            $objMSalida->d30 = null;
                                        }
                                    }
                                    break;
                                case 31:
                                    if ($ultimoDia >= 31 && ($objMSalida->estado31 == null || $objMSalida->estado31 <= 1)) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral31_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado31 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d31 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral31_id = null;
                                            $objMSalida->estado31 = null;
                                            $objMSalida->d31 = null;
                                        }
                                    }
                                    break;
                            }
                        }
                        if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {

                            if (isset($matrizHorariosCruzados[$dia][1][-1]) || isset($matrizHorariosCruzados[$dia - 1][1][-1])) {
                                switch ($dia) {
                                    case 1 :

                                        $ctrlHorarioCruzado = $objM->controlExisteMarcacionMixta($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                        if ($ctrlHorarioCruzado == 1 || $ctrlHorarioCruzado == 3) {
                                            /**
                                             * Es necesario obtener el ultimo horario de salida previsto del anterior mes en caso de seguir editable.
                                             */
                                            if ($objMSalidaAux->estado1 == null || $objMSalidaAux->estado1 <= 1) {
                                                $objC = new Calendarioslaborales();
                                                $idCalendarioDiaPrevio = $objC->getUltimoIdCalendarioLaboralEntradaDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                $horaSalidaPendienteDiaPrevio = $objC->getUltimaHoraSalidaPendienteDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                $objMSalidaAux->calendariolaboral1_id = $idCalendarioDiaPrevio;
                                                $objMSalidaAux->estado1 = 1;
                                                $objMSalidaAux->d1 = $horaSalidaPendienteDiaPrevio;
                                            }
                                        } else {
                                            $objMSalidaAux->calendariolaboral1_id = null;
                                            $objMSalidaAux->estado1 = null;
                                            $objMSalidaAux->d1 = null;
                                        }
                                        break;
                                    case 2 :
                                        if ($objMSalidaAux->estado2 == null || $objMSalidaAux->estado2 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral2_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado2 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d2 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral2_id = null;
                                                $objMSalidaAux->estado2 = null;
                                                $objMSalidaAux->d2 = null;
                                            }
                                        }
                                        break;
                                    case 3 :
                                        if ($objMSalidaAux->estado3 == null || $objMSalidaAux->estado3 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral3_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado3 = $matrizEstadosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->d3 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral3_id = null;
                                                $objMSalidaAux->estado3 = null;
                                                $objMSalidaAux->d3 = null;
                                            }
                                        }
                                        break;
                                    case 4 :
                                        if ($objMSalidaAux->estado4 == null || $objMSalidaAux->estado4 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral4_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado4 = $matrizEstadosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->d4 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral4_id = null;
                                                $objMSalidaAux->estado4 = null;
                                                $objMSalidaAux->d4 = null;
                                            }
                                        }
                                        break;
                                    case 5 :
                                        if ($objMSalidaAux->estado5 == null || $objMSalidaAux->estado5 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral5_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado5 = $matrizEstadosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->d5 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral5_id = null;
                                                $objMSalidaAux->estado5 = null;
                                                $objMSalidaAux->d5 = null;
                                            }
                                        }
                                        break;
                                    case 6 :
                                        if ($objMSalidaAux->estado6 == null || $objMSalidaAux->estado6 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral6_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado6 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d6 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral6_id = null;
                                                $objMSalidaAux->estado6 = null;
                                                $objMSalidaAux->d6 = null;
                                            }
                                        }
                                        break;
                                    case 7 :
                                        if ($objMSalidaAux->estado7 == null || $objMSalidaAux->estado7 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral7_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado7 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d7 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral7_id = null;
                                                $objMSalidaAux->estado7 = null;
                                                $objMSalidaAux->d7 = null;
                                            }
                                        }
                                        break;
                                    case 8 :
                                        if ($objMSalidaAux->estado8 == null || $objMSalidaAux->estado8 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral8_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado8 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d8 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral8_id = null;
                                                $objMSalidaAux->estado8 = null;
                                                $objMSalidaAux->d8 = null;
                                            }
                                        }
                                        break;
                                    case 9 :
                                        if ($objMSalidaAux->estado9 == null || $objMSalidaAux->estado9 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral9_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado9 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d9 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral9_id = null;
                                                $objMSalidaAux->estado9 = null;
                                                $objMSalidaAux->d9 = null;
                                            }
                                        }
                                        break;
                                    case 10:
                                        if ($objMSalidaAux->estado10 == null || $objMSalidaAux->estado10 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral10_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado10 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d10 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral10_id = null;
                                                $objMSalidaAux->estado10 = null;
                                                $objMSalidaAux->d10 = null;
                                            }
                                        }
                                        break;
                                    case 11:
                                        if ($objMSalidaAux->estado11 == null || $objMSalidaAux->estado11 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral11_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado11 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d11 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral11_id = null;
                                                $objMSalidaAux->estado11 = null;
                                                $objMSalidaAux->d11 = null;
                                            }
                                        }
                                        break;
                                    case 12:
                                        if ($objMSalidaAux->estado12 == null || $objMSalidaAux->estado12 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral12_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado12 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d12 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral12_id = null;
                                                $objMSalidaAux->estado12 = null;
                                                $objMSalidaAux->d12 = null;
                                            }
                                        }
                                        break;
                                    case 13:
                                        if ($objMSalidaAux->estado13 == null || $objMSalidaAux->estado13 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral13_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado13 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d13 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral13_id = null;
                                                $objMSalidaAux->estado13 = null;
                                                $objMSalidaAux->d13 = null;
                                            }
                                        }
                                        break;
                                    case 14:
                                        if ($objMSalidaAux->estado14 == null || $objMSalidaAux->estado14 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral14_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado14 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d14 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral14_id = null;
                                                $objMSalidaAux->estado14 = null;
                                                $objMSalidaAux->d14 = null;
                                            }
                                        }
                                        break;
                                    case 15:
                                        if ($objMSalidaAux->estado15 == null || $objMSalidaAux->estado15 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral15_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado15 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d15 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral15_id = null;
                                                $objMSalidaAux->estado15 = null;
                                                $objMSalidaAux->d15 = null;
                                            }
                                        }
                                        break;
                                    case 16:
                                        if ($objMSalidaAux->estado16 == null || $objMSalidaAux->estado16 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral16_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado16 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d16 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral16_id = null;
                                                $objMSalidaAux->estado16 = null;
                                                $objMSalidaAux->d16 = null;
                                            }
                                        }
                                        break;
                                    case 17:
                                        if ($objMSalidaAux->estado17 == null || $objMSalidaAux->estado17 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral17_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado17 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d17 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral17_id = null;
                                                $objMSalidaAux->estado17 = null;
                                                $objMSalidaAux->d17 = null;
                                            }
                                        }
                                        break;
                                    case 18:
                                        if ($objMSalidaAux->estado18 == null || $objMSalidaAux->estado18 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral18_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado18 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d18 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral18_id = null;
                                                $objMSalidaAux->estado18 = null;
                                                $objMSalidaAux->d18 = null;
                                            }
                                        }
                                        break;
                                    case 19:
                                        if ($objMSalidaAux->estado19 == null || $objMSalidaAux->estado19 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral19_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado19 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d19 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral19_id = null;
                                                $objMSalidaAux->estado19 = null;
                                                $objMSalidaAux->d19 = null;
                                            }
                                        }
                                        break;
                                    case 20:
                                        if ($objMSalidaAux->estado20 == null || $objMSalidaAux->estado20 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral20_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado20 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d20 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral20_id = null;
                                                $objMSalidaAux->estado20 = null;
                                                $objMSalidaAux->d20 = null;
                                            }
                                        }
                                        break;
                                    case 21:
                                        if ($objMSalidaAux->estado21 == null || $objMSalidaAux->estado21 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral21_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado21 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d21 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral21_id = null;
                                                $objMSalidaAux->estado21 = null;
                                                $objMSalidaAux->d21 = null;
                                            }
                                        }
                                        break;
                                    case 22:
                                        if ($objMSalidaAux->estado22 == null || $objMSalidaAux->estado22 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral22_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado22 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d22 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral22_id = null;
                                                $objMSalidaAux->estado22 = null;
                                                $objMSalidaAux->d22 = null;
                                            }
                                        }
                                        break;
                                    case 23:
                                        if ($objMSalidaAux->estado23 == null || $objMSalidaAux->estado23 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral23_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado23 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d23 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral23_id = null;
                                                $objMSalidaAux->estado23 = null;
                                                $objMSalidaAux->d23 = null;
                                            }
                                        }
                                        break;
                                    case 24:
                                        if ($objMSalidaAux->estado24 == null || $objMSalidaAux->estado24 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral24_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado24 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d24 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral24_id = null;
                                                $objMSalidaAux->estado24 = null;
                                                $objMSalidaAux->d24 = null;
                                            }
                                        }
                                        break;
                                    case 25:
                                        if ($objMSalidaAux->estado25 == null || $objMSalidaAux->estado25 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral25_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado25 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d25 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral25_id = null;
                                                $objMSalidaAux->estado25 = null;
                                                $objMSalidaAux->d25 = null;
                                            }
                                        }
                                        break;
                                    case 26:
                                        if ($objMSalidaAux->estado26 == null || $objMSalidaAux->estado26 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral26_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado26 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d26 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral26_id = null;
                                                $objMSalidaAux->estado26 = null;
                                                $objMSalidaAux->d26 = null;
                                            }
                                        }
                                        break;
                                    case 27:
                                        if ($objMSalidaAux->estado27 == null || $objMSalidaAux->estado27 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral27_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado27 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d27 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral27_id = null;
                                                $objMSalidaAux->estado27 = null;
                                                $objMSalidaAux->d27 = null;
                                            }
                                        }
                                        break;
                                    case 28:
                                        if ($objMSalidaAux->estado28 == null || $objMSalidaAux->estado28 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral28_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado28 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d28 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral28_id = null;
                                                $objMSalidaAux->estado28 = null;
                                                $objMSalidaAux->d28 = null;
                                            }
                                        }
                                        break;
                                    /**
                                     * Se adiciona el control de los 29 días o más debido a que no todos los meses llegan hasta esa fecha
                                     */
                                    case 29:
                                        if ($ultimoDia >= 29 && ($objMSalidaAux->estado29 == null || $objMSalidaAux->estado29 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral29_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado29 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d29 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral29_id = null;
                                                $objMSalidaAux->estado29 = null;
                                                $objMSalidaAux->d29 = null;
                                            }
                                        }
                                        break;
                                    case 30:
                                        if ($ultimoDia >= 30 && ($objMSalidaAux->estado30 == null || $objMSalidaAux->estado30 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral30_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado30 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d30 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral30_id = null;
                                                $objMSalidaAux->estado30 = null;
                                                $objMSalidaAux->d30 = null;
                                            }
                                        }
                                        break;
                                    case 31:
                                        if ($ultimoDia >= 31 && ($objMSalidaAux->estado31 == null || $objMSalidaAux->estado31 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1])) {
                                                $objMSalidaAux->calendariolaboral31_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][1][-1];
                                                $objMSalidaAux->estado31 = $matrizEstadosCruzados[$dia - 1][1][-1];;
                                                $objMSalidaAux->d31 = $matrizHorariosCruzados[$dia - 1][1][-1];
                                            } else {
                                                $objMSalidaAux->calendariolaboral31_id = null;
                                                $objMSalidaAux->estado31 = null;
                                                $objMSalidaAux->d31 = null;
                                            }
                                        }
                                        break;
                                }
                            }
                        }
                    }
                    $objMEntrada->modalidadmarcacion_id = 1;
                    $objMSalida->modalidadmarcacion_id = 4;
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux->modalidadmarcacion_id = 4;
                    }
                    //$objMEntrada->ultimo_dia=$diaaux;$objMSalida->ultimo_dia=$diaaux;
                    $objMEntrada->ultimo_dia = $ultimoDia;
                    $objMSalida->ultimo_dia = $ultimoDia;
                    $objMEntrada->estado = 1;
                    $objMSalida->estado = 1;
                    $objMEntrada->baja_logica = 1;
                    $objMSalida->baja_logica = 1;
                    $objMEntrada->agrupador = 0;
                    $objMSalida->agrupador = 0;
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        //$objMSalidaAux->ultimo_dia=$diaaux;
                        $objMSalidaAux->ultimo_dia = $ultimoDia;
                        $objMSalidaAux->estado = 1;
                        $objMSalidaAux->baja_logica = 1;
                        $objMSalidaAux->agrupador = 0;
                    }
                    try {
                        $okE = $objMEntrada->save();
                        $okS = $objMSalida->save();
                        //$okE = $okS = true;
                        if ($okE) {
                            $entradas++;
                        }
                        if ($okS) {
                            $salidas++;
                        }
                        if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                            $okSA = $objMSalidaAux->save();
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el detalle correspondiente a registros previstos de marcaci&oacute;n.');
                    }
                }
                if ($entradas > 0 && $salidas > 0 && $entradas == $salidas) {
                    #region Se eliminan los registros ya descartados en el proceso anterior
                    $sql = "DELETE FROM horariosymarcaciones WHERE relaboral_id=" . $idRelaboral;
                    $sql .= " AND gestion = " . $gestion . " AND mes=" . $mes . " AND modalidadmarcacion_id IN (1,4) AND baja_logica = 0";
                    $db->execute($sql);
                    #endregion
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: El detalle correspondiente a los registros previstos de marcaci&oacute;n fueron generados correctamente.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro previstos de marcación.');
                }
            }
            #endregion Edición de Registro
        } else {
            $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro previstos de marcación debido a que no se enviaron todos los datos necesarios.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la generación de la marcación prevista considerando la existencia de marcaciones cruzadas iniciales.
     */
    function generarmarcacionprevistaoriginalAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0
            && isset($_POST["gestion"]) && $_POST["gestion"] > 0
            && isset($_POST["mes"]) && $_POST["mes"] > 0
            && isset($_POST["fecha_ini"]) && $_POST["fecha_ini"] != ''
            && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"] != ''
        ) {
            #region Edición de Registro
            $idRelaboral = $_POST["id_relaboral"];
            $gestion = $_POST["gestion"];
            $mes = $_POST["mes"];
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $clasemarcacion = $_POST["clasemarcacion"];
            $objFCL = new Fcalendariolaboral();
            $objM = new Fmarcaciones();
            $ultimoDia = 0;
            /**
             * Se hace un control de la existencia de al menos una marcación mixta inicial en el rango del mes completo a objeto de
             * prever el registro de marcaciones mixtas iniciales (Marcaciones de salida que proceden del turno del día anterior y
             * que salen del registro de marcaciones normales de salidas establecidas pues genera incoherencia.
             */
            //$existeMarcacionCruzadaMixtaInicialEnMes = $objM->controlExisteMarcacionMixtaInicialEnRango($idRelaboral,$fechaIni,$fechaFin);
            $existeMarcacionCruzadaMixtaInicialEnMes = $objM->controlExisteMarcacionMixtaInicialEnGestionMes($idRelaboral, $gestion, $mes);
            $fechas = $objM->getUltimaFecha($mes, $gestion);
            if (is_object($fechas) && $fechas->count() > 0) {
                foreach ($fechas as $fecha) {
                    $arrFecha = explode("-", $fecha->f_ultimo_dia_mes);
                    $ultimoDia = $arrFecha[2];
                }
            }
            $cantidadGrupos = 0;
            $entradas = 0;
            $salidas = 0;
            $objRango = new Ffechasrango();
            $rangoFechas = $objRango->getAll($fechaIni, $fechaFin);
            $matrizHorarios = array();
            $matrizIdCalendarios = array();
            $matrizEstados = array();
            $matrizDiasSemana = array();
            $matrizHorariosCruzados = array();
            $matrizIdCalendariosHorariosCruzados = array();
            $matrizEstadosCruzados = array();
            $swIncluyeOtroMes = false;
            if ($rangoFechas->count() > 0) {
                #region Estableciendo los valores para las variables del objeto
                foreach ($rangoFechas as $rango) {
                    $resul = $objFCL->getAllRegisteredByPerfilAndRelaboralRangoFechas(0, $idRelaboral, $rango->fecha, $rango->fecha);
                    $turnoaux = 0;
                    $grupoaux = 0;
                    if ($resul->count() > 0) {
                        foreach ($resul as $v) {
                            $arrFecha = explode("-", $rango->fecha);
                            $diaaux = intval($arrFecha[2]);
                            if (($v->tipo_horario != 3 && $rango->dia != 0 && $rango->dia != 6) || $v->tipo_horario == 3) {
                                $matrizDiasSemana[$diaaux] = $rango->dia;
                                $turnoaux++;
                                $grupoaux++;
                                $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_entrada;
                                $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;

                                if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {

                                    if ($v->horario_cruzado == 1) {
                                        /**
                                         * Es necesario determinar que NO se puede tener dos perfiles en un mismo día.
                                         * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                         */
                                        $grupoaux++;
                                        $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = null;
                                        $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = null;
                                        $matrizEstados[$diaaux][$turnoaux][$grupoaux] = null;
                                        if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                        /**
                                         * Se verifica que haya entrada en un día y salida en otro.
                                         */
                                        if (strtotime($v->hora_entrada) > strtotime($v->hora_salida)) {
                                            $matrizHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                            $matrizIdCalendariosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                            $matrizEstadosCruzados[$diaaux][$turnoaux][$grupoaux] = 1;
                                        }
                                    } else {
                                        /**
                                         * Es necesario determinar que NO se puede tener dos perfiles en un mismo día.
                                         * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                         */
                                        $grupoaux++;
                                        $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                        $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                        $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                        if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                        /**
                                         * Se verifica que haya entrada en un día y salida en otro.
                                         */
                                        if (strtotime($v->hora_entrada) > strtotime($v->hora_salida)) {
                                            $matrizHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                            $matrizIdCalendariosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                            $matrizEstadosCruzados[$diaaux][$turnoaux][$grupoaux] = 1;
                                            if ($diaaux == $ultimoDia) $swIncluyeOtroMes = true;
                                        }
                                    }
                                } else {
                                    /**
                                     * Es necesario determinar que NO se puede tener dos perfiles en un mismo día.
                                     * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                     */
                                    $grupoaux++;
                                    $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                    $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                    $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                    if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                    /**
                                     * Se verifica que haya entrada en un día y salida en otro.
                                     */
                                    if (strtotime($v->hora_entrada) > strtotime($v->hora_salida)) {
                                        $matrizHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                        $matrizIdCalendariosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                        $matrizEstadosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                        if ($diaaux == $ultimoDia) $swIncluyeOtroMes = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            /**
             * Se contabilizan el número de grupos existentes, el mínimo debería ser 2
             */
            if (count($cantidadGrupos) > 0 && $cantidadGrupos % 2 == 0) {
                for ($i = 2; $i <= $cantidadGrupos; $i = $i + 2) {
                    $turno = $i / 2;
                    $grupoA = $i - 1;
                    $grupoB = $i;
                    $consultaEntrada = "relaboral_id=" . $idRelaboral . " AND ";
                    /**
                     * Se crea una consulta adicional en caso de existir un horario cruzado,
                     * es decir, que ingrese en un día y culminé este mismo turno al día siguiente.
                     */
                    $consultaSalidaAux = "relaboral_id=" . $idRelaboral . " AND ";

                    $consultaEntrada .= "gestion=" . $gestion . " AND ";
                    $consultaSalidaAux .= "gestion=" . $gestion . " AND ";

                    $consultaEntrada .= "mes=" . $mes . " AND ";
                    $consultaEntrada .= "turno=" . $turno . " AND ";

                    $consultaSalidaAux .= "mes=" . $mes . " AND ";
                    $consultaSalidaAux .= "turno=1 AND ";

                    $consultaSalida = $consultaEntrada;

                    $consultaEntrada .= "grupo=" . $grupoA . " AND ";
                    $consultaSalida .= "grupo=" . $grupoB . " AND ";
                    $consultaSalidaAux .= "grupo=-1 AND ";

                    $consultaEntrada .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaSalida .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaSalidaAux .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaEntrada .= "modalidadmarcacion_id = 1 AND ";
                    $consultaSalida .= "modalidadmarcacion_id = 4 AND ";
                    $consultaSalidaAux .= "modalidadmarcacion_id = 4 AND ";
                    $consultaEntrada .= "estado>=1 AND baja_logica=1 ";
                    $consultaSalida .= "estado>=1 AND baja_logica=1 ";
                    $consultaSalidaAux .= "estado>=1 AND baja_logica=1 ";

                    /**
                     * Se hace una consulta para ver los registro de entrada y salida válidos
                     */
                    $objMEntrada = Horariosymarcaciones::findFirst(array($consultaEntrada));
                    $objMSalida = Horariosymarcaciones::findFirst(array($consultaSalida));
                    /**
                     * En caso de requerirse la generación adicional del registro de marcación prevista
                     * para un grupo que almacenará los registros de salida del día previo dado que existe
                     * al menos una marcacion cruzada inicial en todo el mes.
                     */
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux = Horariosymarcaciones::findFirst(array($consultaSalidaAux));
                    }

                    if (!is_object($objMEntrada)) {
                        $objMEntrada = new Horariosymarcaciones();
                        $objMEntrada->relaboral_id = $idRelaboral;
                        $objMEntrada->gestion = $gestion;
                        $objMEntrada->mes = $mes;
                        $objMEntrada->clasemarcacion = $clasemarcacion;
                        $objMEntrada->user_reg_id = $user_reg_id;
                        $objMEntrada->fecha_reg = $hoy;
                        $objMEntrada->estado1 = null;
                        $objMEntrada->estado2 = null;
                        $objMEntrada->estado3 = null;
                        $objMEntrada->estado4 = null;
                        $objMEntrada->estado5 = null;
                        $objMEntrada->estado6 = null;
                        $objMEntrada->estado7 = null;
                        $objMEntrada->estado8 = null;
                        $objMEntrada->estado9 = null;
                        $objMEntrada->estado10 = null;
                        $objMEntrada->estado11 = null;
                        $objMEntrada->estado12 = null;
                        $objMEntrada->estado13 = null;
                        $objMEntrada->estado14 = null;
                        $objMEntrada->estado15 = null;
                        $objMEntrada->estado16 = null;
                        $objMEntrada->estado17 = null;
                        $objMEntrada->estado18 = null;
                        $objMEntrada->estado19 = null;
                        $objMEntrada->estado20 = null;
                        $objMEntrada->estado21 = null;
                        $objMEntrada->estado22 = null;
                        $objMEntrada->estado23 = null;
                        $objMEntrada->estado24 = null;
                        $objMEntrada->estado25 = null;
                        $objMEntrada->estado26 = null;
                        $objMEntrada->estado27 = null;
                        $objMEntrada->estado28 = null;
                        $objMEntrada->estado29 = null;
                        $objMEntrada->estado30 = null;
                        $objMEntrada->estado31 = null;
                    } else {
                        $objMEntrada->user_mod_id = $user_reg_id;
                        $objMEntrada->fecha_mod = $hoy;
                    }
                    if (!is_object($objMSalida)) {
                        $objMSalida = new Horariosymarcaciones();
                        $objMSalida->relaboral_id = $idRelaboral;
                        $objMSalida->gestion = $gestion;
                        $objMSalida->mes = $mes;
                        $objMSalida->clasemarcacion = $clasemarcacion;
                        $objMSalida->user_reg_id = $user_reg_id;
                        $objMSalida->fecha_reg = $hoy;
                        $objMSalida->estado1 = null;
                        $objMSalida->estado2 = null;
                        $objMSalida->estado3 = null;
                        $objMSalida->estado4 = null;
                        $objMSalida->estado5 = null;
                        $objMSalida->estado6 = null;
                        $objMSalida->estado7 = null;
                        $objMSalida->estado8 = null;
                        $objMSalida->estado9 = null;
                        $objMSalida->estado10 = null;
                        $objMSalida->estado11 = null;
                        $objMSalida->estado12 = null;
                        $objMSalida->estado13 = null;
                        $objMSalida->estado14 = null;
                        $objMSalida->estado15 = null;
                        $objMSalida->estado16 = null;
                        $objMSalida->estado17 = null;
                        $objMSalida->estado18 = null;
                        $objMSalida->estado19 = null;
                        $objMSalida->estado20 = null;
                        $objMSalida->estado21 = null;
                        $objMSalida->estado22 = null;
                        $objMSalida->estado23 = null;
                        $objMSalida->estado24 = null;
                        $objMSalida->estado25 = null;
                        $objMSalida->estado26 = null;
                        $objMSalida->estado27 = null;
                        $objMSalida->estado28 = null;
                        $objMSalida->estado29 = null;
                        $objMSalida->estado30 = null;
                        $objMSalida->estado31 = null;
                    } else {
                        $objMSalida->user_mod_id = $user_mod_id;
                        $objMSalida->fecha_mod = $hoy;
                    }
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        if (!is_object($objMSalidaAux)) {
                            $objMSalidaAux = new Horariosymarcaciones();
                            $objMSalidaAux->relaboral_id = $idRelaboral;
                            $objMSalidaAux->gestion = $gestion;
                            $objMSalidaAux->mes = $mes;
                            $objMSalidaAux->clasemarcacion = $clasemarcacion;
                            $objMSalidaAux->user_reg_id = $user_reg_id;
                            $objMSalidaAux->fecha_reg = $hoy;
                            $objMSalidaAux->estado1 = null;
                            $objMSalidaAux->estado2 = null;
                            $objMSalidaAux->estado3 = null;
                            $objMSalidaAux->estado4 = null;
                            $objMSalidaAux->estado5 = null;
                            $objMSalidaAux->estado6 = null;
                            $objMSalidaAux->estado7 = null;
                            $objMSalidaAux->estado8 = null;
                            $objMSalidaAux->estado9 = null;
                            $objMSalidaAux->estado10 = null;
                            $objMSalidaAux->estado11 = null;
                            $objMSalidaAux->estado12 = null;
                            $objMSalidaAux->estado13 = null;
                            $objMSalidaAux->estado14 = null;
                            $objMSalidaAux->estado15 = null;
                            $objMSalidaAux->estado16 = null;
                            $objMSalidaAux->estado17 = null;
                            $objMSalidaAux->estado18 = null;
                            $objMSalidaAux->estado19 = null;
                            $objMSalidaAux->estado20 = null;
                            $objMSalidaAux->estado21 = null;
                            $objMSalidaAux->estado22 = null;
                            $objMSalidaAux->estado23 = null;
                            $objMSalidaAux->estado24 = null;
                            $objMSalidaAux->estado25 = null;
                            $objMSalidaAux->estado26 = null;
                            $objMSalidaAux->estado27 = null;
                            $objMSalidaAux->estado28 = null;
                            $objMSalidaAux->estado29 = null;
                            $objMSalidaAux->estado30 = null;
                            $objMSalidaAux->estado31 = null;
                        } else {
                            $objMSalidaAux->user_mod_id = $user_mod_id;
                            $objMSalidaAux->fecha_mod = $hoy;
                        }
                    }
                    /**
                     * Se reinician todos los valores a objeto de no dejar rastros de los anteriores valores.
                     * Sin embargo, el estado para un día en particular esta ya ELABORADO(2), APROBADO (3) o PLANILLADO (4) ya no se modificará el dato
                     */
                    if ($objMEntrada->estado1 == null || $objMEntrada->estado1 <= 1) {
                        $objMEntrada->d1 = null;
                        $objMEntrada->calendariolaboral1_id = null;
                        $objMEntrada->estado1 = null;
                    }
                    if ($objMEntrada->estado2 == null || $objMEntrada->estado2 <= 1) {
                        $objMEntrada->d2 = null;
                        $objMEntrada->calendariolaboral2_id = null;
                        $objMEntrada->estado2 = null;
                    }
                    if ($objMEntrada->estado3 == null || $objMEntrada->estado3 <= 1) {
                        $objMEntrada->d3 = null;
                        $objMEntrada->calendariolaboral3_id = null;
                        $objMEntrada->estado3 = null;
                    }
                    if ($objMEntrada->estado4 == null || $objMEntrada->estado4 <= 1) {
                        $objMEntrada->d4 = null;
                        $objMEntrada->calendariolaboral4_id = null;
                        $objMEntrada->estado4 = null;
                    }
                    if ($objMEntrada->estado5 == null || $objMEntrada->estado5 <= 1) {
                        $objMEntrada->d5 = null;
                        $objMEntrada->calendariolaboral5_id = null;
                        $objMEntrada->estado5 = null;
                    }
                    if ($objMEntrada->estado6 == null || $objMEntrada->estado6 <= 1) {
                        $objMEntrada->d6 = null;
                        $objMEntrada->calendariolaboral6_id = null;
                        $objMEntrada->estado6 = null;
                    }
                    if ($objMEntrada->estado7 == null || $objMEntrada->estado7 <= 1) {
                        $objMEntrada->d7 = null;
                        $objMEntrada->calendariolaboral7_id = null;
                        $objMEntrada->estado7 = null;
                    }
                    if ($objMEntrada->estado8 == null || $objMEntrada->estado8 <= 1) {
                        $objMEntrada->d8 = null;
                        $objMEntrada->calendariolaboral8_id = null;
                        $objMEntrada->estado8 = null;
                    }
                    if ($objMEntrada->estado9 == null || $objMEntrada->estado9 <= 1) {
                        $objMEntrada->d9 = null;
                        $objMEntrada->calendariolaboral9_id = null;
                        $objMEntrada->estado9 = null;
                    }
                    if ($objMEntrada->estado10 == null || $objMEntrada->estado10 <= 1) {
                        $objMEntrada->d10 = null;
                        $objMEntrada->calendariolaboral10_id = null;
                        $objMEntrada->estado10 = null;
                    }
                    if ($objMEntrada->estado11 == null || $objMEntrada->estado11 <= 1) {
                        $objMEntrada->d11 = null;
                        $objMEntrada->calendariolaboral11_id = null;
                        $objMEntrada->estado11 = null;
                    }
                    if ($objMEntrada->estado12 == null || $objMEntrada->estado12 <= 1) {
                        $objMEntrada->d12 = null;
                        $objMEntrada->calendariolaboral12_id = null;
                        $objMEntrada->estado12 = null;
                    }
                    if ($objMEntrada->estado13 == null || $objMEntrada->estado13 <= 1) {
                        $objMEntrada->d13 = null;
                        $objMEntrada->calendariolaboral13_id = null;
                        $objMEntrada->estado13 = null;
                    }
                    if ($objMEntrada->estado14 == null || $objMEntrada->estado14 <= 1) {
                        $objMEntrada->d14 = null;
                        $objMEntrada->calendariolaboral14_id = null;
                        $objMEntrada->estado14 = null;
                    }
                    if ($objMEntrada->estado15 == null || $objMEntrada->estado15 <= 1) {
                        $objMEntrada->d15 = null;
                        $objMEntrada->calendariolaboral15_id = null;
                        $objMEntrada->estado15 = null;
                    }
                    if ($objMEntrada->estado16 == null || $objMEntrada->estado16 <= 1) {
                        $objMEntrada->d16 = null;
                        $objMEntrada->calendariolaboral16_id = null;
                        $objMEntrada->estado16 = null;
                    }
                    if ($objMEntrada->estado17 == null || $objMEntrada->estado17 <= 1) {
                        $objMEntrada->d17 = null;
                        $objMEntrada->calendariolaboral17_id = null;
                        $objMEntrada->estado17 = null;
                    }
                    if ($objMEntrada->estado18 == null || $objMEntrada->estado18 <= 1) {
                        $objMEntrada->d18 = null;
                        $objMEntrada->calendariolaboral18_id = null;
                        $objMEntrada->estado18 = null;
                    }
                    if ($objMEntrada->estado19 == null || $objMEntrada->estado19 <= 1) {
                        $objMEntrada->d19 = null;
                        $objMEntrada->calendariolaboral19_id = null;
                        $objMEntrada->estado19 = null;
                    }
                    if ($objMEntrada->estado20 == null || $objMEntrada->estado20 <= 1) {
                        $objMEntrada->d20 = null;
                        $objMEntrada->calendariolaboral20_id = null;
                        $objMEntrada->estado20 = null;
                    }
                    if ($objMEntrada->estado21 == null || $objMEntrada->estado21 <= 1) {
                        $objMEntrada->d21 = null;
                        $objMEntrada->calendariolaboral21_id = null;
                        $objMEntrada->estado21 = null;
                    }
                    if ($objMEntrada->estado22 == null || $objMEntrada->estado22 <= 1) {
                        $objMEntrada->d22 = null;
                        $objMEntrada->calendariolaboral22_id = null;
                        $objMEntrada->estado22 = null;
                    }
                    if ($objMEntrada->estado23 == null || $objMEntrada->estado23 <= 1) {
                        $objMEntrada->d23 = null;
                        $objMEntrada->calendariolaboral23_id = null;
                        $objMEntrada->estado23 = null;
                    }
                    if ($objMEntrada->estado24 == null || $objMEntrada->estado24 <= 1) {
                        $objMEntrada->d24 = null;
                        $objMEntrada->calendariolaboral24_id = null;
                        $objMEntrada->estado24 = null;
                    }
                    if ($objMEntrada->estado25 == null || $objMEntrada->estado25 <= 1) {
                        $objMEntrada->d25 = null;
                        $objMEntrada->calendariolaboral25_id = null;
                        $objMEntrada->estado25 = null;
                    }
                    if ($objMEntrada->estado26 == null || $objMEntrada->estado26 <= 1) {
                        $objMEntrada->d26 = null;
                        $objMEntrada->calendariolaboral26_id = null;
                        $objMEntrada->estado26 = null;
                    }
                    if ($objMEntrada->estado27 == null || $objMEntrada->estado27 <= 1) {
                        $objMEntrada->d27 = null;
                        $objMEntrada->calendariolaboral27_id = null;
                        $objMEntrada->estado27 = null;
                    }
                    if ($objMEntrada->estado28 == null || $objMEntrada->estado28 <= 1) {
                        $objMEntrada->d28 = null;
                        $objMEntrada->calendariolaboral28_id = null;
                        $objMEntrada->estado28 = null;
                    }
                    if ($objMEntrada->estado29 == null || $objMEntrada->estado29 <= 1) {
                        $objMEntrada->d29 = null;
                        $objMEntrada->calendariolaboral29_id = null;
                        $objMEntrada->estado29 = null;
                    }
                    if ($objMEntrada->estado30 == null || $objMEntrada->estado30 <= 1) {
                        $objMEntrada->d30 = null;
                        $objMEntrada->calendariolaboral30_id = null;
                        $objMEntrada->estado30 = null;
                    }
                    if ($objMEntrada->estado31 == null || $objMEntrada->estado31 <= 1) {
                        $objMEntrada->d31 = null;
                        $objMEntrada->calendariolaboral31_id = null;
                        $objMEntrada->estado31 = null;
                    }

                    if ($objMSalida->estado1 == null || $objMSalida->estado1 <= 1) {
                        $objMSalida->d1 = null;
                        $objMSalida->calendariolaboral1_id = null;
                        $objMSalida->estado1 = null;
                    }
                    if ($objMSalida->estado2 == null || $objMSalida->estado2 <= 1) {
                        $objMSalida->d2 = null;
                        $objMSalida->calendariolaboral2_id = null;
                        $objMSalida->estado2 = null;
                    }
                    if ($objMSalida->estado3 == null || $objMSalida->estado3 <= 1) {
                        $objMSalida->d3 = null;
                        $objMSalida->calendariolaboral3_id = null;
                        $objMSalida->estado3 = null;
                    }
                    if ($objMSalida->estado4 == null || $objMSalida->estado4 <= 1) {
                        $objMSalida->d4 = null;
                        $objMSalida->calendariolaboral4_id = null;
                        $objMSalida->estado4 = null;
                    }
                    if ($objMSalida->estado5 == null || $objMSalida->estado5 <= 1) {
                        $objMSalida->d5 = null;
                        $objMSalida->calendariolaboral5_id = null;
                        $objMSalida->estado5 = null;
                    }
                    if ($objMSalida->estado6 == null || $objMSalida->estado6 <= 1) {
                        $objMSalida->d6 = null;
                        $objMSalida->calendariolaboral6_id = null;
                        $objMSalida->estado6 = null;
                    }
                    if ($objMSalida->estado7 == null || $objMSalida->estado7 <= 1) {
                        $objMSalida->d7 = null;
                        $objMSalida->calendariolaboral7_id = null;
                        $objMSalida->estado7 = null;
                    }
                    if ($objMSalida->estado8 == null || $objMSalida->estado8 <= 1) {
                        $objMSalida->d8 = null;
                        $objMSalida->calendariolaboral8_id = null;
                        $objMSalida->estado8 = null;
                    }
                    if ($objMSalida->estado9 == null || $objMSalida->estado9 <= 1) {
                        $objMSalida->d9 = null;
                        $objMSalida->calendariolaboral9_id = null;
                        $objMSalida->estado9 = null;
                    }
                    if ($objMSalida->estado10 == null || $objMSalida->estado10 <= 1) {
                        $objMSalida->d10 = null;
                        $objMSalida->calendariolaboral10_id = null;
                        $objMSalida->estado10 = null;
                    }
                    if ($objMSalida->estado11 == null || $objMSalida->estado11 <= 1) {
                        $objMSalida->d11 = null;
                        $objMSalida->calendariolaboral11_id = null;
                        $objMSalida->estado11 = null;
                    }
                    if ($objMSalida->estado12 == null || $objMSalida->estado12 <= 1) {
                        $objMSalida->d12 = null;
                        $objMSalida->calendariolaboral12_id = null;
                        $objMSalida->estado12 = null;
                    }
                    if ($objMSalida->estado13 == null || $objMSalida->estado13 <= 1) {
                        $objMSalida->d13 = null;
                        $objMSalida->calendariolaboral13_id = null;
                        $objMSalida->estado13 = null;
                    }
                    if ($objMSalida->estado14 == null || $objMSalida->estado14 <= 1) {
                        $objMSalida->d14 = null;
                        $objMSalida->calendariolaboral14_id = null;
                        $objMSalida->estado14 = null;
                    }
                    if ($objMSalida->estado15 == null || $objMSalida->estado15 <= 1) {
                        $objMSalida->d15 = null;
                        $objMSalida->calendariolaboral15_id = null;
                        $objMSalida->estado15 = null;
                    }
                    if ($objMSalida->estado16 == null || $objMSalida->estado16 <= 1) {
                        $objMSalida->d16 = null;
                        $objMSalida->calendariolaboral16_id = null;
                        $objMSalida->estado16 = null;
                    }
                    if ($objMSalida->estado17 == null || $objMSalida->estado17 <= 1) {
                        $objMSalida->d17 = null;
                        $objMSalida->calendariolaboral17_id = null;
                        $objMSalida->estado17 = null;
                    }
                    if ($objMSalida->estado18 == null || $objMSalida->estado18 <= 1) {
                        $objMSalida->d18 = null;
                        $objMSalida->calendariolaboral18_id = null;
                        $objMSalida->estado18 = null;
                    }
                    if ($objMSalida->estado19 == null || $objMSalida->estado19 <= 1) {
                        $objMSalida->d19 = null;
                        $objMSalida->calendariolaboral19_id = null;
                        $objMSalida->estado19 = null;
                    }
                    if ($objMSalida->estado20 == null || $objMSalida->estado20 <= 1) {
                        $objMSalida->d20 = null;
                        $objMSalida->calendariolaboral20_id = null;
                        $objMSalida->estado20 = null;
                    }
                    if ($objMSalida->estado21 == null || $objMSalida->estado21 <= 1) {
                        $objMSalida->d21 = null;
                        $objMSalida->calendariolaboral21_id = null;
                        $objMSalida->estado21 = null;
                    }
                    if ($objMSalida->estado22 == null || $objMSalida->estado22 <= 1) {
                        $objMSalida->d22 = null;
                        $objMSalida->calendariolaboral22_id = null;
                        $objMSalida->estado22 = null;
                    }
                    if ($objMSalida->estado23 == null || $objMSalida->estado23 <= 1) {
                        $objMSalida->d23 = null;
                        $objMSalida->calendariolaboral23_id = null;
                        $objMSalida->estado23 = null;
                    }
                    if ($objMSalida->estado24 == null || $objMSalida->estado24 <= 1) {
                        $objMSalida->d24 = null;
                        $objMSalida->calendariolaboral24_id = null;
                        $objMSalida->estado24 = null;
                    }
                    if ($objMSalida->estado25 == null || $objMSalida->estado25 <= 1) {
                        $objMSalida->d25 = null;
                        $objMSalida->calendariolaboral25_id = null;
                        $objMSalida->estado25 = null;
                    }
                    if ($objMSalida->estado26 == null || $objMSalida->estado26 <= 1) {
                        $objMSalida->d26 = null;
                        $objMSalida->calendariolaboral26_id = null;
                        $objMSalida->estado26 = null;
                    }
                    if ($objMSalida->estado27 == null || $objMSalida->estado27 <= 1) {
                        $objMSalida->d27 = null;
                        $objMSalida->calendariolaboral27_id = null;
                        $objMSalida->estado27 = null;
                    }
                    if ($objMSalida->estado28 == null || $objMSalida->estado28 <= 1) {
                        $objMSalida->d28 = null;
                        $objMSalida->calendariolaboral28_id = null;
                        $objMSalida->estado28 = null;
                    }
                    if ($objMSalida->estado29 == null || $objMSalida->estado29 <= 1) {
                        $objMSalida->d29 = null;
                        $objMSalida->calendariolaboral29_id = null;
                        $objMSalida->estado29 = null;
                    }
                    if ($objMSalida->estado30 == null || $objMSalida->estado30 <= 1) {
                        $objMSalida->d30 = null;
                        $objMSalida->calendariolaboral30_id = null;
                        $objMSalida->estado30 = null;
                    }
                    if ($objMSalida->estado31 == null || $objMSalida->estado31 <= 1) {
                        $objMSalida->d31 = null;
                        $objMSalida->calendariolaboral31_id = null;
                        $objMSalida->estado31 = null;
                    }

                    $objMEntrada->turno = $turno;
                    $objMSalida->turno = $turno;
                    $objMEntrada->grupo = $grupoA;
                    $objMSalida->grupo = $grupoB;
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux->turno = 1;
                        $objMSalidaAux->grupo = -1;
                    }
                    for ($dia = 1; $dia <= 31; $dia++) {
                        if (isset($matrizDiasSemana[$dia])) {
                            if (isset($matrizHorarios[$dia][$turno][$grupoA])) {
                                switch ($dia) {
                                    case 1 :
                                        if ($objMEntrada->estado1 == null || $objMEntrada->estado1 <= 1) {
                                            $objMEntrada->calendariolaboral1_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado1 = 1;
                                            $objMEntrada->d1 = $matrizHorarios[$dia][$turno][$grupoA];
                                            if (!isset($matrizHorariosCruzados[$dia][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral1_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                                $objMSalida->estado1 = $matrizEstados[$dia][$turno][$grupoB];
                                                $objMSalida->d1 = $matrizHorarios[$dia][$turno][$grupoB];
                                            }
                                        }
                                        break;
                                    case 2 :
                                        if ($objMEntrada->estado2 == null || $objMEntrada->estado2 <= 1) {
                                            $objMEntrada->calendariolaboral2_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado2 = 1;
                                            $objMEntrada->d2 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral2_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado2 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d2 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 3 :
                                        if ($objMEntrada->estado3 == null || $objMEntrada->estado3 <= 1) {
                                            $objMEntrada->calendariolaboral3_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado3 = 1;
                                            $objMEntrada->d3 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral3_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado3 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d3 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 4 :
                                        if ($objMEntrada->estado4 == null || $objMEntrada->estado4 <= 1) {
                                            $objMEntrada->calendariolaboral4_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado4 = 1;
                                            $objMEntrada->d4 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral4_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado4 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d4 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 5 :
                                        if ($objMEntrada->estado5 == null || $objMEntrada->estado5 <= 1) {
                                            $objMEntrada->calendariolaboral5_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado5 = 1;
                                            $objMEntrada->d5 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral5_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado5 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d5 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 6 :
                                        if ($objMEntrada->estado6 == null || $objMEntrada->estado6 <= 1) {
                                            $objMEntrada->calendariolaboral6_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado6 = 1;
                                            $objMEntrada->d6 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral6_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado6 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d6 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 7 :
                                        if ($objMEntrada->estado7 == null || $objMEntrada->estado7 <= 1) {
                                            $objMEntrada->calendariolaboral7_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado7 = 1;
                                            $objMEntrada->d7 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral7_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado7 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d7 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 8 :
                                        if ($objMEntrada->estado8 == null || $objMEntrada->estado8 <= 1) {
                                            $objMEntrada->calendariolaboral8_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado8 = 1;
                                            $objMEntrada->d8 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral8_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado8 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d8 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 9 :
                                        if ($objMEntrada->estado9 == null || $objMEntrada->estado9 <= 1) {
                                            $objMEntrada->calendariolaboral9_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado9 = 1;
                                            $objMEntrada->d9 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral9_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado9 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d9 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 10:
                                        if ($objMEntrada->estado10 == null || $objMEntrada->estado10 <= 1) {
                                            $objMEntrada->calendariolaboral10_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado10 = 1;
                                            $objMEntrada->d10 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral10_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado10 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d10 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 11:
                                        if ($objMEntrada->estado11 == null || $objMEntrada->estado11 <= 1) {
                                            $objMEntrada->calendariolaboral11_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado11 = 1;
                                            $objMEntrada->d11 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral11_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado11 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d11 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 12:
                                        if ($objMEntrada->estado12 == null || $objMEntrada->estado12 <= 1) {
                                            $objMEntrada->calendariolaboral12_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado12 = 1;
                                            $objMEntrada->d12 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral12_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado12 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d12 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 13:
                                        if ($objMEntrada->estado13 == null || $objMEntrada->estado13 <= 1) {
                                            $objMEntrada->calendariolaboral13_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado13 = 1;
                                            $objMEntrada->d13 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral13_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado13 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d13 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 14:
                                        if ($objMEntrada->estado14 == null || $objMEntrada->estado14 <= 1) {
                                            $objMEntrada->calendariolaboral14_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado14 = 1;
                                            $objMEntrada->d14 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral14_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado14 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d14 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 15:
                                        if ($objMEntrada->estado15 == null || $objMEntrada->estado15 <= 1) {
                                            $objMEntrada->calendariolaboral15_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado15 = 1;
                                            $objMEntrada->d15 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral15_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado15 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d15 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 16:
                                        if ($objMEntrada->estado16 == null || $objMEntrada->estado16 <= 1) {
                                            $objMEntrada->calendariolaboral16_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado16 = 1;
                                            $objMEntrada->d16 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral16_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado16 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d16 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 17:
                                        if ($objMEntrada->estado17 == null || $objMEntrada->estado17 <= 1) {
                                            $objMEntrada->calendariolaboral17_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado17 = 1;
                                            $objMEntrada->d17 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral17_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado17 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d17 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 18:
                                        if ($objMEntrada->estado18 == null || $objMEntrada->estado18 <= 1) {
                                            $objMEntrada->calendariolaboral18_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado18 = 1;
                                            $objMEntrada->d18 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral18_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado18 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d18 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 19:
                                        if ($objMEntrada->estado19 == null || $objMEntrada->estado19 <= 1) {
                                            $objMEntrada->calendariolaboral19_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado19 = 1;
                                            $objMEntrada->d19 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral19_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado19 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d19 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 20:
                                        if ($objMEntrada->estado20 == null || $objMEntrada->estado20 <= 1) {
                                            $objMEntrada->calendariolaboral20_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado20 = 1;
                                            $objMEntrada->d20 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral20_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado20 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d20 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 21:
                                        if ($objMEntrada->estado21 == null || $objMEntrada->estado21 <= 1) {
                                            $objMEntrada->calendariolaboral21_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado21 = 1;
                                            $objMEntrada->d21 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral21_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado21 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d21 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 22:
                                        if ($objMEntrada->estado22 == null || $objMEntrada->estado22 <= 1) {
                                            $objMEntrada->calendariolaboral22_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado22 = 1;
                                            $objMEntrada->d22 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral22_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado22 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d22 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 23:
                                        if ($objMEntrada->estado23 == null || $objMEntrada->estado23 <= 1) {
                                            $objMEntrada->calendariolaboral23_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado23 = 1;
                                            $objMEntrada->d23 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral23_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado23 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d23 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 24:
                                        if ($objMEntrada->estado24 == null || $objMEntrada->estado24 <= 1) {
                                            $objMEntrada->calendariolaboral24_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado24 = 1;
                                            $objMEntrada->d24 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral24_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado24 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d24 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 25:
                                        if ($objMEntrada->estado25 == null || $objMEntrada->estado25 <= 1) {
                                            $objMEntrada->calendariolaboral25_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado25 = 1;
                                            $objMEntrada->d25 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral25_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado25 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d25 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 26:
                                        if ($objMEntrada->estado26 == null || $objMEntrada->estado26 <= 1) {
                                            $objMEntrada->calendariolaboral26_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado26 = 1;
                                            $objMEntrada->d26 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral26_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado26 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d26 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 27:
                                        if ($objMEntrada->estado27 == null || $objMEntrada->estado27 <= 1) {
                                            $objMEntrada->calendariolaboral27_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado27 = 1;
                                            $objMEntrada->d27 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral27_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado27 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d27 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 28:
                                        if ($objMEntrada->estado28 == null || $objMEntrada->estado28 <= 1) {
                                            $objMEntrada->calendariolaboral28_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado28 = 1;
                                            $objMEntrada->d28 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral28_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado28 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d28 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 29:
                                        if ($objMEntrada->estado29 == null || $objMEntrada->estado29 <= 1) {
                                            $objMEntrada->calendariolaboral29_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado29 = 1;
                                            $objMEntrada->d29 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral29_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado29 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d29 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 30:
                                        if ($objMEntrada->estado30 == null || $objMEntrada->estado30 <= 1) {
                                            $objMEntrada->calendariolaboral30_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado30 = 1;
                                            $objMEntrada->d30 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral30_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado30 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d30 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                    case 31:
                                        if ($objMEntrada->estado31 == null || $objMEntrada->estado31 <= 1) {
                                            $objMEntrada->calendariolaboral31_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado31 = 1;
                                            $objMEntrada->d31 = $matrizHorarios[$dia][$turno][$grupoA];
                                            $objMSalida->calendariolaboral31_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado31 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d31 = $matrizHorarios[$dia][$turno][$grupoB];
                                        }
                                        break;
                                }
                            }
                        }
                        /**
                         * Calculo de las horas para las salidas con cruce
                         */
                        if (isset($matrizHorariosCruzados[$dia][$turno][$grupoB]) || isset($matrizHorariosCruzados[$dia - 1][$turno][$grupoB])) {

                            switch ($dia) {
                                case 1 :
                                    /**
                                     * Asignación momentanea
                                     */
                                    $objMSalida->calendariolaboral1_id = null;
                                    $objMSalida->estado1 = null;
                                    $objMSalida->d1 = null;
                                    break;
                                case 2 :
                                    if ($objMSalida->estado2 == null || $objMSalida->estado2 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral2_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado2 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d2 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral2_id = null;
                                            $objMSalida->estado2 = null;
                                            $objMSalida->d2 = null;
                                        }
                                    }
                                    break;
                                case 3 :
                                    if ($objMSalida->estado3 == null || $objMSalida->estado3 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral3_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado3 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d3 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral3_id = null;
                                            $objMSalida->estado3 = null;
                                            $objMSalida->d3 = null;
                                        }
                                    }
                                    break;
                                case 4 :
                                    if ($objMSalida->estado4 == null || $objMSalida->estado4 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral4_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado4 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d4 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral4_id = null;
                                            $objMSalida->estado4 = null;
                                            $objMSalida->d4 = null;
                                        }
                                    }
                                    break;
                                case 5 :
                                    if ($objMSalida->estado5 == null || $objMSalida->estado5 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral5_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado5 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d5 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral5_id = null;
                                            $objMSalida->estado5 = null;
                                            $objMSalida->d5 = null;
                                        }
                                    }
                                    break;
                                case 6 :
                                    if ($objMSalida->estado6 == null || $objMSalida->estado6 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral6_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado6 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d6 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral6_id = null;
                                            $objMSalida->estado6 = null;
                                            $objMSalida->d6 = null;
                                        }
                                    }
                                    break;
                                case 7 :
                                    if ($objMSalida->estado7 == null || $objMSalida->estado7 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral7_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado7 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d7 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral7_id = null;
                                            $objMSalida->estado7 = null;
                                            $objMSalida->d7 = null;
                                        }
                                    }
                                    break;
                                case 8 :
                                    if ($objMSalida->estado8 == null || $objMSalida->estado8 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral8_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado8 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d8 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral8_id = null;
                                            $objMSalida->estado8 = null;
                                            $objMSalida->d8 = null;
                                        }
                                    }
                                    break;
                                case 9 :
                                    if ($objMSalida->estado9 == null || $objMSalida->estado9 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral9_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado9 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d9 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral9_id = null;
                                            $objMSalida->estado9 = null;
                                            $objMSalida->d9 = null;
                                        }
                                    }
                                    break;
                                case 10:
                                    if ($objMSalida->estado10 == null || $objMSalida->estado10 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral10_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado10 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d10 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral10_id = null;
                                            $objMSalida->estado10 = null;
                                            $objMSalida->d10 = null;
                                        }
                                    }
                                    break;
                                case 11:
                                    if ($objMSalida->estado11 == null || $objMSalida->estado11 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral11_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado11 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d11 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral11_id = null;
                                            $objMSalida->estado11 = null;
                                            $objMSalida->d11 = null;
                                        }
                                    }
                                    break;
                                case 12:
                                    if ($objMSalida->estado12 == null || $objMSalida->estado12 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral12_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado12 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d12 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral12_id = null;
                                            $objMSalida->estado12 = null;
                                            $objMSalida->d12 = null;
                                        }
                                    }
                                    break;
                                case 13:
                                    if ($objMSalida->estado13 == null || $objMSalida->estado13 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral13_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado13 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d13 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral13_id = null;
                                            $objMSalida->estado13 = null;
                                            $objMSalida->d13 = null;
                                        }
                                    }
                                    break;
                                case 14:
                                    if ($objMSalida->estado14 == null || $objMSalida->estado14 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral14_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado14 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d14 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral14_id = null;
                                            $objMSalida->estado14 = null;
                                            $objMSalida->d14 = null;
                                        }
                                    }
                                    break;
                                case 15:
                                    if ($objMSalida->estado15 == null || $objMSalida->estado15 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral15_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado15 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d15 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral15_id = null;
                                            $objMSalida->estado15 = null;
                                            $objMSalida->d15 = null;
                                        }
                                    }
                                    break;
                                case 16:
                                    if ($objMSalida->estado16 == null || $objMSalida->estado16 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral16_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado16 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d16 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral16_id = null;
                                            $objMSalida->estado16 = null;
                                            $objMSalida->d16 = null;
                                        }
                                    }
                                    break;
                                case 17:
                                    if ($objMSalida->estado17 == null || $objMSalida->estado17 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral17_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado17 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d17 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral17_id = null;
                                            $objMSalida->estado17 = null;
                                            $objMSalida->d17 = null;
                                        }
                                    }
                                    break;
                                case 18:
                                    if ($objMSalida->estado18 == null || $objMSalida->estado18 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral18_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado18 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d18 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral18_id = null;
                                            $objMSalida->estado18 = null;
                                            $objMSalida->d18 = null;
                                        }
                                    }
                                    break;
                                case 19:
                                    if ($objMSalida->estado19 == null || $objMSalida->estado19 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral19_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado19 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d19 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral19_id = null;
                                            $objMSalida->estado19 = null;
                                            $objMSalida->d19 = null;
                                        }
                                    }
                                    break;
                                case 20:
                                    if ($objMSalida->estado20 == null || $objMSalida->estado20 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral20_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado20 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d20 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral20_id = null;
                                            $objMSalida->estado20 = null;
                                            $objMSalida->d20 = null;
                                        }
                                    }
                                    break;
                                case 21:
                                    if ($objMSalida->estado21 == null || $objMSalida->estado21 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral21_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado21 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d21 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral21_id = null;
                                            $objMSalida->estado21 = null;
                                            $objMSalida->d21 = null;
                                        }
                                    }
                                    break;
                                case 22:
                                    if ($objMSalida->estado22 == null || $objMSalida->estado22 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral22_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado22 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d22 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral22_id = null;
                                            $objMSalida->estado22 = null;
                                            $objMSalida->d22 = null;
                                        }
                                    }
                                    break;
                                case 23:
                                    if ($objMSalida->estado23 == null || $objMSalida->estado23 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral23_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado23 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d23 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral23_id = null;
                                            $objMSalida->estado23 = null;
                                            $objMSalida->d23 = null;
                                        }
                                    }
                                    break;
                                case 24:
                                    if ($objMSalida->estado24 == null || $objMSalida->estado24 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral24_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado24 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d24 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral24_id = null;
                                            $objMSalida->estado24 = null;
                                            $objMSalida->d24 = null;
                                        }
                                    }
                                    break;
                                case 25:
                                    if ($objMSalida->estado25 == null || $objMSalida->estado25 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral25_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado25 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d25 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral25_id = null;
                                            $objMSalida->estado25 = null;
                                            $objMSalida->d25 = null;
                                        }
                                    }
                                    break;
                                case 26:
                                    if ($objMSalida->estado26 == null || $objMSalida->estado26 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral26_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado26 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d26 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral26_id = null;
                                            $objMSalida->estado26 = null;
                                            $objMSalida->d26 = null;
                                        }
                                    }
                                    break;
                                case 27:
                                    if ($objMSalida->estado27 == null || $objMSalida->estado27 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral27_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado27 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d27 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral27_id = null;
                                            $objMSalida->estado27 = null;
                                            $objMSalida->d27 = null;
                                        }
                                    }
                                    break;
                                case 28:
                                    if ($objMSalida->estado28 == null || $objMSalida->estado28 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral28_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado28 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d28 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral28_id = null;
                                            $objMSalida->estado28 = null;
                                            $objMSalida->d28 = null;
                                        }
                                    }
                                    break;
                                case 29:
                                    if ($objMSalida->estado29 == null || $objMSalida->estado29 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral29_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado29 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d29 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral29_id = null;
                                            $objMSalida->estado29 = null;
                                            $objMSalida->d29 = null;
                                        }
                                    }
                                    break;
                                case 30:
                                    if ($objMSalida->estado30 == null || $objMSalida->estado30 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral30_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado30 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d30 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral30_id = null;
                                            $objMSalida->estado30 = null;
                                            $objMSalida->d30 = null;
                                        }
                                    }
                                    break;
                                case 31:
                                    if ($objMSalida->estado31 == null || $objMSalida->estado31 <= 1) {
                                        if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                            $objMSalida->calendariolaboral31_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->estado31 = $matrizEstados[$dia - 1][$turno][$grupoB];
                                            $objMSalida->d31 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                        } else {
                                            $objMSalida->calendariolaboral31_id = null;
                                            $objMSalida->estado31 = null;
                                            $objMSalida->d31 = null;
                                        }
                                    }
                                    break;
                            }
                            if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {

                                switch ($dia) {
                                    case 1 :

                                        $ctrlHorarioCruzado = $objM->controlExisteMarcacionMixta($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                        if ($ctrlHorarioCruzado == 1 || $ctrlHorarioCruzado == 3) {
                                            /**
                                             * Es necesario obtener el ultimo horario de salida previsto del anterior mes en caso de seguir editable.
                                             */
                                            if ($objMSalidaAux->estado1 == null || $objMSalidaAux->estado1 <= 1) {
                                                $objC = new Calendarioslaborales();
                                                $idCalendarioDiaPrevio = $objC->getUltimoIdCalendarioLaboralEntradaDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                $horaSalidaPendienteDiaPrevio = $objC->getUltimaHoraSalidaPendienteDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                $objMSalidaAux->calendariolaboral1_id = $idCalendarioDiaPrevio;
                                                $objMSalidaAux->estado1 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d1 = $horaSalidaPendienteDiaPrevio;
                                            }
                                        } else {
                                            $objMSalidaAux->calendariolaboral1_id = null;
                                            $objMSalidaAux->estado1 = null;
                                            $objMSalidaAux->d1 = null;
                                        }
                                        break;
                                    case 2 :
                                        if ($objMSalidaAux->estado2 == null || $objMSalidaAux->estado2 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia + 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral2_id = $matrizIdCalendariosHorariosCruzados[$dia + 1][$turno][$grupoB];
                                                $objMSalidaAux->estado2 = $matrizEstadosCruzados[$dia + 1][$turno][$grupoB];;
                                                $objMSalidaAux->d2 = $matrizHorariosCruzados[$dia + 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral2_id = null;
                                                $objMSalidaAux->estado2 = null;
                                                $objMSalidaAux->d2 = null;
                                            }
                                        }
                                        break;
                                    case 3 :
                                        if ($objMSalidaAux->estado3 == null || $objMSalidaAux->estado3 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral3_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado3 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d3 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral3_id = null;
                                                $objMSalidaAux->estado3 = null;
                                                $objMSalidaAux->d3 = null;
                                            }
                                        }
                                        break;
                                    case 4 :
                                        if ($objMSalidaAux->estado4 == null || $objMSalidaAux->estado4 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral4_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado4 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d4 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral4_id = null;
                                                $objMSalidaAux->estado4 = null;
                                                $objMSalidaAux->d4 = null;
                                            }
                                        }
                                        break;
                                    case 5 :
                                        if ($objMSalidaAux->estado5 == null || $objMSalidaAux->estado5 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral5_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado5 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d5 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral5_id = null;
                                                $objMSalidaAux->estado5 = null;
                                                $objMSalidaAux->d5 = null;
                                            }
                                        }
                                        break;
                                    case 6 :
                                        if ($objMSalidaAux->estado6 == null || $objMSalidaAux->estado6 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral6_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado6 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d6 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral6_id = null;
                                                $objMSalidaAux->estado6 = null;
                                                $objMSalidaAux->d6 = null;
                                            }
                                        }
                                        break;
                                    case 7 :
                                        if ($objMSalidaAux->estado7 == null || $objMSalidaAux->estado7 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral7_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado7 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d7 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral7_id = null;
                                                $objMSalidaAux->estado7 = null;
                                                $objMSalidaAux->d7 = null;
                                            }
                                        }
                                        break;
                                    case 8 :
                                        if ($objMSalidaAux->estado8 == null || $objMSalidaAux->estado8 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral8_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado8 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d8 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral8_id = null;
                                                $objMSalidaAux->estado8 = null;
                                                $objMSalidaAux->d8 = null;
                                            }
                                        }
                                        break;
                                    case 9 :
                                        if ($objMSalidaAux->estado9 == null || $objMSalidaAux->estado9 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral9_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado9 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d9 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral9_id = null;
                                                $objMSalidaAux->estado9 = null;
                                                $objMSalidaAux->d9 = null;
                                            }
                                        }
                                        break;
                                    case 10:
                                        if ($objMSalidaAux->estado10 == null || $objMSalidaAux->estado10 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral10_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado10 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d10 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral10_id = null;
                                                $objMSalidaAux->estado10 = null;
                                                $objMSalidaAux->d10 = null;
                                            }
                                        }
                                        break;
                                    case 11:
                                        if ($objMSalidaAux->estado11 == null || $objMSalidaAux->estado11 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral11_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado11 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d11 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral11_id = null;
                                                $objMSalidaAux->estado11 = null;
                                                $objMSalidaAux->d11 = null;
                                            }
                                        }
                                        break;
                                    case 12:
                                        if ($objMSalidaAux->estado12 == null || $objMSalidaAux->estado12 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral12_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado12 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d12 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral12_id = null;
                                                $objMSalidaAux->estado12 = null;
                                                $objMSalidaAux->d12 = null;
                                            }
                                        }
                                        break;
                                    case 13:
                                        if ($objMSalidaAux->estado13 == null || $objMSalidaAux->estado13 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral13_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado13 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d13 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral13_id = null;
                                                $objMSalidaAux->estado13 = null;
                                                $objMSalidaAux->d13 = null;
                                            }
                                        }
                                        break;
                                    case 14:
                                        if ($objMSalidaAux->estado14 == null || $objMSalidaAux->estado14 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral14_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado14 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d14 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral14_id = null;
                                                $objMSalidaAux->estado14 = null;
                                                $objMSalidaAux->d14 = null;
                                            }
                                        }
                                        break;
                                    case 15:
                                        if ($objMSalidaAux->estado15 == null || $objMSalidaAux->estado15 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral15_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado15 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d15 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral15_id = null;
                                                $objMSalidaAux->estado15 = null;
                                                $objMSalidaAux->d15 = null;
                                            }
                                        }
                                        break;
                                    case 16:
                                        if ($objMSalidaAux->estado16 == null || $objMSalidaAux->estado16 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral16_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado16 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d16 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral16_id = null;
                                                $objMSalidaAux->estado16 = null;
                                                $objMSalidaAux->d16 = null;
                                            }
                                        }
                                        break;
                                    case 17:
                                        if ($objMSalidaAux->estado17 == null || $objMSalidaAux->estado17 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral17_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado17 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d17 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral17_id = null;
                                                $objMSalidaAux->estado17 = null;
                                                $objMSalidaAux->d17 = null;
                                            }
                                        }
                                        break;
                                    case 18:
                                        if ($objMSalidaAux->estado18 == null || $objMSalidaAux->estado18 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral18_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado18 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d18 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral18_id = null;
                                                $objMSalidaAux->estado18 = null;
                                                $objMSalidaAux->d18 = null;
                                            }
                                        }
                                        break;
                                    case 19:
                                        if ($objMSalidaAux->estado19 == null || $objMSalidaAux->estado19 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral19_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado19 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d19 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral19_id = null;
                                                $objMSalidaAux->estado19 = null;
                                                $objMSalidaAux->d19 = null;
                                            }
                                        }
                                        break;
                                    case 20:
                                        if ($objMSalidaAux->estado20 == null || $objMSalidaAux->estado20 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral20_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado20 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d20 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral20_id = null;
                                                $objMSalidaAux->estado20 = null;
                                                $objMSalidaAux->d20 = null;
                                            }
                                        }
                                        break;
                                    case 21:
                                        if ($objMSalidaAux->estado21 == null || $objMSalidaAux->estado21 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral21_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado21 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d21 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral21_id = null;
                                                $objMSalidaAux->estado21 = null;
                                                $objMSalidaAux->d21 = null;
                                            }
                                        }
                                        break;
                                    case 22:
                                        if ($objMSalidaAux->estado22 == null || $objMSalidaAux->estado22 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral22_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado22 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d22 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral22_id = null;
                                                $objMSalidaAux->estado22 = null;
                                                $objMSalidaAux->d22 = null;
                                            }
                                        }
                                        break;
                                    case 23:
                                        if ($objMSalidaAux->estado23 == null || $objMSalidaAux->estado23 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral23_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado23 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d23 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral23_id = null;
                                                $objMSalidaAux->estado23 = null;
                                                $objMSalidaAux->d23 = null;
                                            }
                                        }
                                        break;
                                    case 24:
                                        if ($objMSalidaAux->estado24 == null || $objMSalidaAux->estado24 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral24_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado24 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d24 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral24_id = null;
                                                $objMSalidaAux->estado24 = null;
                                                $objMSalidaAux->d24 = null;
                                            }
                                        }
                                        break;
                                    case 25:
                                        if ($objMSalidaAux->estado25 == null || $objMSalidaAux->estado25 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral25_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado25 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d25 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral25_id = null;
                                                $objMSalidaAux->estado25 = null;
                                                $objMSalidaAux->d25 = null;
                                            }
                                        }
                                        break;
                                    case 26:
                                        if ($objMSalidaAux->estado26 == null || $objMSalidaAux->estado26 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral26_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado26 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d26 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral26_id = null;
                                                $objMSalidaAux->estado26 = null;
                                                $objMSalidaAux->d26 = null;
                                            }
                                        }
                                        break;
                                    case 27:
                                        if ($objMSalidaAux->estado27 == null || $objMSalidaAux->estado27 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral27_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado27 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d27 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral27_id = null;
                                                $objMSalidaAux->estado27 = null;
                                                $objMSalidaAux->d27 = null;
                                            }
                                        }
                                        break;
                                    case 28:
                                        if ($objMSalidaAux->estado28 == null || $objMSalidaAux->estado28 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral28_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado28 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d28 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral28_id = null;
                                                $objMSalidaAux->estado28 = null;
                                                $objMSalidaAux->d28 = null;
                                            }
                                        }
                                        break;
                                    case 29:
                                        if ($objMSalidaAux->estado29 == null || $objMSalidaAux->estado29 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral29_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado29 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d29 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral29_id = null;
                                                $objMSalidaAux->estado29 = null;
                                                $objMSalidaAux->d29 = null;
                                            }
                                        }
                                        break;
                                    case 30:
                                        if ($objMSalidaAux->estado30 == null || $objMSalidaAux->estado30 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral30_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado30 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d30 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral30_id = null;
                                                $objMSalidaAux->estado30 = null;
                                                $objMSalidaAux->d30 = null;
                                            }
                                        }
                                        break;
                                    case 31:
                                        if ($objMSalidaAux->estado31 == null || $objMSalidaAux->estado31 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral31_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado31 = $matrizEstadosCruzados[$dia - 1][$turno][$grupoB];;
                                                $objMSalidaAux->d31 = $matrizHorariosCruzados[$dia - 1][$turno][$grupoB];
                                            } else {
                                                $objMSalidaAux->calendariolaboral31_id = null;
                                                $objMSalidaAux->estado31 = null;
                                                $objMSalidaAux->d31 = null;
                                            }
                                        }
                                        break;
                                }

                            }

                        }

                    }
                    $objMEntrada->modalidadmarcacion_id = 1;
                    $objMSalida->modalidadmarcacion_id = 4;
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux->modalidadmarcacion_id = 4;
                    }
                    $objMEntrada->ultimo_dia = $diaaux;
                    $objMSalida->ultimo_dia = $diaaux;
                    $objMEntrada->estado = 1;
                    $objMSalida->estado = 1;
                    $objMEntrada->baja_logica = 1;
                    $objMSalida->baja_logica = 1;
                    $objMEntrada->agrupador = 0;
                    $objMSalida->agrupador = 0;
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux->ultimo_dia = $diaaux;
                        $objMSalidaAux->estado = 1;
                        $objMSalidaAux->baja_logica = 1;
                        $objMSalidaAux->agrupador = 0;
                    }
                    try {
                        $okE = $objMEntrada->save();
                        $okS = $objMSalida->save();
                        //$okE = $okS = true;
                        if ($okE) {
                            $entradas++;
                        }
                        if ($okS) {
                            $salidas++;
                        }
                        if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                            $okSA = $objMSalidaAux->save();
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el detalle correspondiente a registros previstos de marcaci&oacute;n.');
                    }
                }
                if ($entradas > 0 && $salidas > 0 && $entradas == $salidas) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: El detalle correspondiente a los registros previstos de marcaci&oacute;n fueron generados correctamente.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro previstos de marcación.');
                }
            }
            #endregion Edición de Registro
        } else {
            $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro previstos de marcación debido a que no se enviaron todos los datos necesarios.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para generar el registro de marcación efectiva.
     */
    function generarmarcacionefectivadirectaAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0
            && isset($_POST["gestion"]) && $_POST["gestion"] > 0
            && isset($_POST["mes"]) && $_POST["mes"] > 0
            && isset($_POST["fecha_ini"]) && $_POST["fecha_ini"] != ''
            && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"] != ''
        ) {
            $arrFechaIni = explode("-", $_POST["fecha_ini"]);
            $arrFechaFin = explode("-", $_POST["fecha_fin"]);
            $primerDia = intval($arrFechaIni[0]);
            $ultimoDia = intval($arrFechaFin[0]);
            $obj = new Fhorariosymarcaciones();
            /**
             * Si el contrato es pasivo se busca de un día más hacia adelante para registrar marcaciones de horarios cruzados (de un día posterior a la baja).
             */
            if (isset($_POST["fecha_baja"]) && $_POST["fecha_fin"] == $_POST["fecha_baja"]) {
                $arrFechaNueva = explode("-", $this->sumarDiasFecha($_POST["fecha_fin"], 1));
                if ($arrFechaNueva[1] == $arrFechaFin[1] && $arrFechaNueva[2] == $arrFechaFin[2]) {
                    $ultimoDia = intval($arrFechaNueva[0]);
                }
            }
            $idRelaborales = '{"0":' . $_POST["id_relaboral"] . '}';

            $ok = $obj->calculoEfectivas($idRelaborales, $_POST["gestion"], $_POST["mes"], $primerDia, $ultimoDia, $idUsuario);
            if ($ok > 0) {
                $msj = array('result' => 1, 'msj' => '&Eacute;xito! C&aacute;lculo efectuado satisfactoria.');
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: No se pudo realizar el c&aacute;lculo.');
            }
        } else {
            $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro previstos de marcación debido a que no se enviaron todos los datos necesarios.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención del registro de marcaciones previstas y efectivas.
     */
    function generarmarcacionefectivaAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0
            && isset($_POST["gestion"]) && $_POST["gestion"] > 0
            && isset($_POST["mes"]) && $_POST["mes"] > 0
            && isset($_POST["fecha_ini"]) && $_POST["fecha_ini"] != ''
            && isset($_POST["fecha_fin"]) && $_POST["fecha_fin"] != ''
        ) {
            #region Edición de Registro
            $idRelaboral = $_POST["id_relaboral"];
            $gestion = $_POST["gestion"];
            $mes = $_POST["mes"];
            $fechaIni = $_POST["fecha_ini"];
            $arrFechaIni = explode("-", $fechaIni);
            $primerDiaCalculo = intval($arrFechaIni[0]);
            $fechaFin = $_POST["fecha_fin"];
            $arrFechaFin = explode("-", $fechaFin);
            $ultimoDiaCalculo = intval($arrFechaFin[0]);
            $clasemarcacion = $_POST["clasemarcacion"];
            $objFCL = new Fcalendariolaboral();
            $cantidadGrupos = 0;
            $consultaEntrada = "";
            $consultaSalida = "";
            $entradas = 0;
            $salidas = 0;
            $ultimoDia = 0;
            $objM = new Fmarcaciones();
            /**
             * Se hace un control de la existencia de al menos una marcación mixta inicial en el rango del mes completo a objeto de
             * prever el registro de marcaciones mixtas iniciales (Marcaciones de salida que proceden del turno del día anterior y
             * que salen del registro de marcaciones normales de salidas establecidas pues genera incoherencia.
             */
            #region Baja de Registros cruzados que abarcan a un registro auxiliar (agrupador=-1) para descartar los registros innecesarios
            $db = $this->getDI()->get('db');
            $sql = "UPDATE horariosymarcaciones SET baja_logica = 0 ";
            $sql .= "WHERE relaboral_id=" . $idRelaboral;
            $sql .= " AND gestion = " . $gestion . " AND mes=" . $mes . " AND grupo=-1 AND modalidadmarcacion_id IN (5)";
            $db->execute($sql);
            #enregion

            $existeMarcacionCruzadaMixtaInicialEnMes = $objM->controlExisteMarcacionMixtaInicialEnGestionMes($idRelaboral, $gestion, $mes);
            //$existeMarcacionCruzadaMixtaInicialEnMes = $objM->controlExisteMarcacionMixtaInicialEnRango($idRelaboral,$fechaIni,$fechaFin);
            $objRango = new Ffechasrango();
            $rangoFechas = $objRango->getAll($fechaIni, $fechaFin);

            $fechas = $objM->getUltimaFecha($mes, $gestion);
            /*if (is_object($fechas) && $fechas->count() > 0) {
                foreach ($fechas as $fecha) {
                    $arrFecha = explode("-", $fecha->f_ultimo_dia_mes);
                    $ultimoDia = $arrFecha[2];
                }
            }*/

            $uFecha = $objM->getUltimaFechaMesGestion($mes, $gestion);
            if ($uFecha != null) {
                $arrFecha = explode("-", $uFecha);
                $ultimoDia = $arrFecha[2];
            }

            $matrizHorarios = array();
            $matrizIdCalendarios = array();
            $matrizDiasSemana = array();
            $matrizHorariosCruzados = array();
            $matrizIdCalendariosHorariosCruzados = array();
            $matrizEstadosHorariosCruzados = array();
            $swIncluyeOtroMes = false;
            $matrizFechas = array();
            $matrizIdHorarios = array();
            $matrizEstados = array();
            $matrizInicioRangoEntrada = array();
            $matrizFinalRangoEntrada = array();
            $matrizInicioRangoSalida = array();
            $matrizFinalRangoSalida = array();
            $matrizInicioRangoSalidaCruzados = array();
            $matrizFinalRangoSalidaCruzados = array();
            $matrizIdHorariosCruzados = array();
            if ($rangoFechas->count() > 0) {
                #region Estableciendo los valores para las variables del objeto
                foreach ($rangoFechas as $rango) {
                    $resul = $objFCL->getAllRegisteredByPerfilAndRelaboralRangoFechas(0, $idRelaboral, $rango->fecha, $rango->fecha);
                    $turnoaux = 0;
                    $grupoaux = 0;
                    if ($resul->count() > 0) {
                        foreach ($resul as $v) {
                            $arrFecha = explode("-", $rango->fecha);
                            $diaaux = intval($arrFecha[2]);
                            if (($v->tipo_horario != 3 && $rango->dia != 0 && $rango->dia != 6) || $v->tipo_horario == 3) {
                                $matrizFechas[$diaaux] = $rango->fecha;
                                $matrizDiasSemana[$diaaux] = $rango->dia;
                                $turnoaux++;
                                $grupoaux++;
                                $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_entrada;
                                $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                $matrizIdHorarios[$diaaux][$turnoaux][$grupoaux] = $v->id_horariolaboral;
                                $matrizInicioRangoEntrada[$diaaux][$turnoaux][$grupoaux] = $v->hora_inicio_rango_ent;
                                $matrizFinalRangoEntrada[$diaaux][$turnoaux][$grupoaux] = $v->hora_final_rango_ent;
                                if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                                    if ($v->horario_cruzado == 1) {
                                        /**
                                         * Es necesario determinar que NO se puede tener dos perfiles en un mismo día.
                                         * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                         */
                                        $grupoaux++;
                                        $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = null;
                                        $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = null;
                                        $matrizEstados[$diaaux][$turnoaux][$grupoaux] = null;
                                        if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                        /**
                                         * Se verifica que haya entrada en un día y salida en otro.
                                         */
                                        if (strtotime($v->hora_entrada) > strtotime($v->hora_salida)) {
                                            $matrizIdHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_horariolaboral;
                                            $matrizHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                            $matrizIdCalendariosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                            $matrizEstadosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = 1;
                                        }
                                    } else {
                                        /**
                                         * Es necesario determinar que NO se puede tener dos perfiles en un mismo día.
                                         * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                         */
                                        $grupoaux++;
                                        $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                        $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                        $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                        if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                        /**
                                         * Se verifica que haya entrada en un día y salida en otro.
                                         */
                                        if (strtotime($v->hora_entrada) > strtotime($v->hora_salida)) {
                                            $matrizHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                            $matrizIdCalendariosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                            $matrizEstadosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = 1;
                                            if ($diaaux == $ultimoDia) $swIncluyeOtroMes = true;
                                        }
                                    }
                                } else {
                                    /**
                                     * Es necesario determinar que no se puede tener dos perfiles en un mismo día.
                                     * Por consiguiente no se admite dos tipos distintos de horarios para un mismo día.
                                     */
                                    $grupoaux++;
                                    $matrizHorarios[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                    $matrizIdCalendarios[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                    $matrizIdHorarios[$diaaux][$turnoaux][$grupoaux] = $v->id_horariolaboral;
                                    $matrizEstados[$diaaux][$turnoaux][$grupoaux] = 1;
                                    $matrizInicioRangoSalida[$diaaux][$turnoaux][$grupoaux] = $v->hora_inicio_rango_sal;
                                    $matrizFinalRangoSalida[$diaaux][$turnoaux][$grupoaux] = $v->hora_final_rango_sal;
                                    if ($cantidadGrupos < $grupoaux) $cantidadGrupos = $grupoaux;

                                    /**
                                     * Se verifica que haya entrada en un día y salida en otro.
                                     */
                                    if (strtotime($v->hora_entrada) > strtotime($v->hora_salida)) {
                                        $matrizHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_salida;
                                        $matrizIdCalendariosHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_calendariolaboral;
                                        $matrizEstadosCruzados[$diaaux][$turnoaux][$grupoaux] = 1;
                                        $matrizIdHorariosCruzados[$diaaux][$turnoaux][$grupoaux] = $v->id_horariolaboral;
                                        $matrizInicioRangoSalidaCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_inicio_rango_sal;
                                        $matrizFinalRangoSalidaCruzados[$diaaux][$turnoaux][$grupoaux] = $v->hora_final_rango_sal;
                                        if ($diaaux == $ultimoDia) $swIncluyeOtroMes = true;
                                    }
                                }
                            }
                        }
                    }
                }
                #region Baja de Registros para descartar los registros innecesarios
                $db = $this->getDI()->get('db');
                $sql = "UPDATE horariosymarcaciones SET baja_logica = 0 WHERE relaboral_id=" . $idRelaboral;
                $sql .= " AND gestion = " . $gestion . " AND mes=" . $mes . " AND modalidadmarcacion_id IN (2,5)";
                $db->execute($sql);
                #enregion
            }
            /**
             * Se contabilizan el número de grupos existentes, el mínimo debería ser 2
             */
            if (count($cantidadGrupos) > 0 && $cantidadGrupos % 2 == 0) {
                for ($i = 2; $i <= $cantidadGrupos; $i = $i + 2) {
                    $turno = $i / 2;
                    $grupoA = $i - 1;
                    $grupoB = $i;
                    $consultaEntrada = "relaboral_id=" . $idRelaboral . " AND ";
                    $consultaSalidaAux = "relaboral_id=" . $idRelaboral . " AND ";

                    $consultaEntrada .= "gestion=" . $gestion . " AND ";
                    $consultaSalidaAux .= "gestion=" . $gestion . " AND ";

                    $consultaEntrada .= "mes=" . $mes . " AND ";
                    $consultaEntrada .= "turno=" . $turno . " AND ";
                    /**
                     * Se crea una consulta adicional en caso de existir un horario cruzado,
                     * es decir, que ingrese en un día y culminé este mismo turno al día siguiente.
                     */
                    $consultaSalidaAux .= "mes=" . $mes . " AND ";
                    $consultaSalidaAux .= "turno=1 AND ";

                    $consultaSalida = $consultaEntrada;

                    $consultaEntrada .= "grupo=" . $grupoA . " AND ";
                    $consultaSalida .= "grupo=" . $grupoB . " AND ";;
                    /**
                     * Este valor se establece debido a que se desconoce de que turno procede el horario de ingreso,
                     * y siendo que se lo conociera, si se volviera a usar el mismo valor daría lugar a duplicación
                     * de registro.
                     */
                    $consultaSalidaAux .= "grupo=-1 AND ";

                    $consultaEntrada .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaSalida .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaSalidaAux .= "clasemarcacion LIKE '" . $clasemarcacion . "' AND ";
                    $consultaEntrada .= "modalidadmarcacion_id = 2 AND ";
                    $consultaSalida .= "modalidadmarcacion_id = 5 AND ";
                    $consultaSalidaAux .= "modalidadmarcacion_id = 5 AND ";
                    $consultaEntrada .= "estado>=1 AND baja_logica=0 ";
                    $consultaSalida .= "estado>=1 AND baja_logica=0 ";
                    //$consultaSalidaAux .= "estado>=1 AND baja_logica=0 ";
                    /**
                     * Modificación debido a que se duplicaban registros en algunos casos innecesariamente 14/02/2017
                     */
                    $consultaSalidaAux .= "estado>=1 ";

                    /**
                     * Se hace una consulta para ver los registro de entrada y salida válidos
                     */

                    $objMEntrada = Horariosymarcaciones::findFirst(array($consultaEntrada));
                    $objMSalida = Horariosymarcaciones::findFirst(array($consultaSalida));

                    /**
                     * Si la marcación de salida determina que se genere la marcación prevista para el mes siguiente
                     */
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux = Horariosymarcaciones::findFirst(array($consultaSalidaAux));
                    }
                    if (!is_object($objMEntrada)) {
                        $objMEntrada = new Horariosymarcaciones();
                        $objMEntrada->relaboral_id = $idRelaboral;
                        $objMEntrada->gestion = $gestion;
                        $objMEntrada->mes = $mes;
                        $objMEntrada->clasemarcacion = $clasemarcacion;
                        $objMEntrada->user_reg_id = $user_reg_id;
                        $objMEntrada->fecha_reg = $hoy;
                        $objMEntrada->estado1 = null;
                        $objMEntrada->estado2 = null;
                        $objMEntrada->estado3 = null;
                        $objMEntrada->estado4 = null;
                        $objMEntrada->estado5 = null;
                        $objMEntrada->estado6 = null;
                        $objMEntrada->estado7 = null;
                        $objMEntrada->estado8 = null;
                        $objMEntrada->estado9 = null;
                        $objMEntrada->estado10 = null;
                        $objMEntrada->estado11 = null;
                        $objMEntrada->estado12 = null;
                        $objMEntrada->estado13 = null;
                        $objMEntrada->estado14 = null;
                        $objMEntrada->estado15 = null;
                        $objMEntrada->estado16 = null;
                        $objMEntrada->estado17 = null;
                        $objMEntrada->estado18 = null;
                        $objMEntrada->estado19 = null;
                        $objMEntrada->estado20 = null;
                        $objMEntrada->estado21 = null;
                        $objMEntrada->estado22 = null;
                        $objMEntrada->estado23 = null;
                        $objMEntrada->estado24 = null;
                        $objMEntrada->estado25 = null;
                        $objMEntrada->estado26 = null;
                        $objMEntrada->estado27 = null;
                        $objMEntrada->estado28 = null;
                        $objMEntrada->estado29 = null;
                        $objMEntrada->estado30 = null;
                        $objMEntrada->estado31 = null;
                    } else {
                        $objMEntrada->user_mod_id = $user_reg_id;
                        $objMEntrada->fecha_mod = $hoy;
                    }
                    if (!is_object($objMSalida)) {
                        $objMSalida = new Horariosymarcaciones();
                        $objMSalida->relaboral_id = $idRelaboral;
                        $objMSalida->gestion = $gestion;
                        $objMSalida->mes = $mes;
                        $objMSalida->clasemarcacion = $clasemarcacion;
                        $objMSalida->user_reg_id = $user_reg_id;
                        $objMSalida->fecha_reg = $hoy;
                        $objMSalida->estado1 = null;
                        $objMSalida->estado2 = null;
                        $objMSalida->estado3 = null;
                        $objMSalida->estado4 = null;
                        $objMSalida->estado5 = null;
                        $objMSalida->estado6 = null;
                        $objMSalida->estado7 = null;
                        $objMSalida->estado8 = null;
                        $objMSalida->estado9 = null;
                        $objMSalida->estado10 = null;
                        $objMSalida->estado11 = null;
                        $objMSalida->estado12 = null;
                        $objMSalida->estado13 = null;
                        $objMSalida->estado14 = null;
                        $objMSalida->estado15 = null;
                        $objMSalida->estado16 = null;
                        $objMSalida->estado17 = null;
                        $objMSalida->estado18 = null;
                        $objMSalida->estado19 = null;
                        $objMSalida->estado20 = null;
                        $objMSalida->estado21 = null;
                        $objMSalida->estado22 = null;
                        $objMSalida->estado23 = null;
                        $objMSalida->estado24 = null;
                        $objMSalida->estado25 = null;
                        $objMSalida->estado26 = null;
                        $objMSalida->estado27 = null;
                        $objMSalida->estado28 = null;
                        $objMSalida->estado29 = null;
                        $objMSalida->estado30 = null;
                        $objMSalida->estado31 = null;
                    } else {
                        $objMSalida->user_mod_id = $user_mod_id;
                        $objMSalida->fecha_mod = $hoy;
                    }
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        if (!is_object($objMSalidaAux)) {
                            $objMSalidaAux = new Horariosymarcaciones();
                            $objMSalidaAux->relaboral_id = $idRelaboral;
                            $objMSalidaAux->gestion = $gestion;
                            $objMSalidaAux->mes = $mes;
                            $objMSalidaAux->turno = 1;
                            $objMSalidaAux->grupo = -1;
                            $objMSalidaAux->clasemarcacion = $clasemarcacion;
                            $objMSalidaAux->user_reg_id = $user_reg_id;
                            $objMSalidaAux->fecha_reg = $hoy;
                            $objMSalidaAux->estado1 = null;
                            $objMSalidaAux->estado2 = null;
                            $objMSalidaAux->estado3 = null;
                            $objMSalidaAux->estado4 = null;
                            $objMSalidaAux->estado5 = null;
                            $objMSalidaAux->estado6 = null;
                            $objMSalidaAux->estado7 = null;
                            $objMSalidaAux->estado8 = null;
                            $objMSalidaAux->estado9 = null;
                            $objMSalidaAux->estado10 = null;
                            $objMSalidaAux->estado11 = null;
                            $objMSalidaAux->estado12 = null;
                            $objMSalidaAux->estado13 = null;
                            $objMSalidaAux->estado14 = null;
                            $objMSalidaAux->estado15 = null;
                            $objMSalidaAux->estado16 = null;
                            $objMSalidaAux->estado17 = null;
                            $objMSalidaAux->estado18 = null;
                            $objMSalidaAux->estado19 = null;
                            $objMSalidaAux->estado20 = null;
                            $objMSalidaAux->estado21 = null;
                            $objMSalidaAux->estado22 = null;
                            $objMSalidaAux->estado23 = null;
                            $objMSalidaAux->estado24 = null;
                            $objMSalidaAux->estado25 = null;
                            $objMSalidaAux->estado26 = null;
                            $objMSalidaAux->estado27 = null;
                            $objMSalidaAux->estado28 = null;
                            $objMSalidaAux->estado29 = null;
                            $objMSalidaAux->estado30 = null;
                            $objMSalidaAux->estado31 = null;
                        } else {
                            $objMSalidaAux->user_mod_id = $user_mod_id;
                            $objMSalidaAux->fecha_mod = $hoy;
                        }
                    }
                    /**
                     * Se reinician todos los valores a objeto de no dejar rastros de los anteriores valores.
                     * Sin embargo, el estado para un día en particular esta ya ELABORADO(2), APROBADO (3) o PLANILLADO (4) ya no se modificará el dato
                     */
                    if (($primerDiaCalculo <= 1 && 1 <= $ultimoDiaCalculo) && ($objMEntrada->estado1 == null || $objMEntrada->estado1 <= 1)) {
                        $objMEntrada->d1 = null;
                        $objMEntrada->calendariolaboral1_id = null;
                        $objMEntrada->estado1 = null;
                    }
                    if (($primerDiaCalculo <= 2 && 2 <= $ultimoDiaCalculo) && ($objMEntrada->estado2 == null || $objMEntrada->estado2 <= 1)) {
                        $objMEntrada->d2 = null;
                        $objMEntrada->calendariolaboral2_id = null;
                        $objMEntrada->estado2 = null;
                    }
                    if (($primerDiaCalculo <= 3 && 3 <= $ultimoDiaCalculo) && ($objMEntrada->estado3 == null || $objMEntrada->estado3 <= 1)) {
                        $objMEntrada->d3 = null;
                        $objMEntrada->calendariolaboral3_id = null;
                        $objMEntrada->estado3 = null;
                    }
                    if (($primerDiaCalculo <= 4 && 4 <= $ultimoDiaCalculo) && ($objMEntrada->estado4 == null || $objMEntrada->estado4 <= 1)) {
                        $objMEntrada->d4 = null;
                        $objMEntrada->calendariolaboral4_id = null;
                        $objMEntrada->estado4 = null;
                    }
                    if (($primerDiaCalculo <= 5 && 5 <= $ultimoDiaCalculo) && ($objMEntrada->estado5 == null || $objMEntrada->estado5 <= 1)) {
                        $objMEntrada->d5 = null;
                        $objMEntrada->calendariolaboral5_id = null;
                        $objMEntrada->estado5 = null;
                    }
                    if (($primerDiaCalculo <= 6 && 6 <= $ultimoDiaCalculo) && ($objMEntrada->estado6 == null || $objMEntrada->estado6 <= 1)) {
                        $objMEntrada->d6 = null;
                        $objMEntrada->calendariolaboral6_id = null;
                        $objMEntrada->estado6 = null;
                    }
                    if (($primerDiaCalculo <= 7 && 7 <= $ultimoDiaCalculo) && ($objMEntrada->estado7 == null || $objMEntrada->estado7 <= 1)) {
                        $objMEntrada->d7 = null;
                        $objMEntrada->calendariolaboral7_id = null;
                        $objMEntrada->estado7 = null;
                    }
                    if (($primerDiaCalculo <= 8 && 8 <= $ultimoDiaCalculo) && ($objMEntrada->estado8 == null || $objMEntrada->estado8 <= 1)) {
                        $objMEntrada->d8 = null;
                        $objMEntrada->calendariolaboral8_id = null;
                        $objMEntrada->estado8 = null;
                    }
                    if (($primerDiaCalculo <= 9 && 9 <= $ultimoDiaCalculo) && ($objMEntrada->estado9 == null || $objMEntrada->estado9 <= 1)) {
                        $objMEntrada->d9 = null;
                        $objMEntrada->calendariolaboral9_id = null;
                        $objMEntrada->estado9 = null;
                    }
                    if (($primerDiaCalculo <= 10 && 10 <= $ultimoDiaCalculo) && ($objMEntrada->estado10 == null || $objMEntrada->estado10 <= 1)) {
                        $objMEntrada->d10 = null;
                        $objMEntrada->calendariolaboral10_id = null;
                        $objMEntrada->estado10 = null;
                    }
                    if (($primerDiaCalculo <= 11 && 11 <= $ultimoDiaCalculo) && ($objMEntrada->estado11 == null || $objMEntrada->estado11 <= 1)) {
                        $objMEntrada->d11 = null;
                        $objMEntrada->calendariolaboral11_id = null;
                        $objMEntrada->estado11 = null;
                    }
                    if (($primerDiaCalculo <= 12 && 12 <= $ultimoDiaCalculo) && ($objMEntrada->estado12 == null || $objMEntrada->estado12 <= 1)) {
                        $objMEntrada->d12 = null;
                        $objMEntrada->calendariolaboral12_id = null;
                        $objMEntrada->estado12 = null;
                    }
                    if (($primerDiaCalculo <= 13 && 13 <= $ultimoDiaCalculo) && ($objMEntrada->estado13 == null || $objMEntrada->estado13 <= 1)) {
                        $objMEntrada->d13 = null;
                        $objMEntrada->calendariolaboral13_id = null;
                        $objMEntrada->estado13 = null;
                    }
                    if (($primerDiaCalculo <= 14 && 14 <= $ultimoDiaCalculo) && ($objMEntrada->estado14 == null || $objMEntrada->estado14 <= 1)) {
                        $objMEntrada->d14 = null;
                        $objMEntrada->calendariolaboral14_id = null;
                        $objMEntrada->estado14 = null;
                    }
                    if (($primerDiaCalculo <= 15 && 15 <= $ultimoDiaCalculo) && ($objMEntrada->estado15 == null || $objMEntrada->estado15 <= 1)) {
                        $objMEntrada->d15 = null;
                        $objMEntrada->calendariolaboral15_id = null;
                        $objMEntrada->estado15 = null;
                    }
                    if (($primerDiaCalculo <= 16 && 16 <= $ultimoDiaCalculo) && ($objMEntrada->estado16 == null || $objMEntrada->estado16 <= 1)) {
                        $objMEntrada->d16 = null;
                        $objMEntrada->calendariolaboral16_id = null;
                        $objMEntrada->estado16 = null;
                    }
                    if (($primerDiaCalculo <= 17 && 17 <= $ultimoDiaCalculo) && ($objMEntrada->estado17 == null || $objMEntrada->estado17 <= 1)) {
                        $objMEntrada->d17 = null;
                        $objMEntrada->calendariolaboral17_id = null;
                        $objMEntrada->estado17 = null;
                    }
                    if (($primerDiaCalculo <= 18 && 18 <= $ultimoDiaCalculo) && ($objMEntrada->estado18 == null || $objMEntrada->estado18 <= 1)) {
                        $objMEntrada->d18 = null;
                        $objMEntrada->calendariolaboral18_id = null;
                        $objMEntrada->estado18 = null;
                    }
                    if (($primerDiaCalculo <= 19 && 19 <= $ultimoDiaCalculo) && ($objMEntrada->estado19 == null || $objMEntrada->estado19 <= 1)) {
                        $objMEntrada->d19 = null;
                        $objMEntrada->calendariolaboral19_id = null;
                        $objMEntrada->estado19 = null;
                    }
                    if (($primerDiaCalculo <= 20 && 20 <= $ultimoDiaCalculo) && ($objMEntrada->estado20 == null || $objMEntrada->estado20 <= 1)) {
                        $objMEntrada->d20 = null;
                        $objMEntrada->calendariolaboral20_id = null;
                        $objMEntrada->estado20 = null;
                    }
                    if (($primerDiaCalculo <= 21 && 21 <= $ultimoDiaCalculo) && ($objMEntrada->estado21 == null || $objMEntrada->estado21 <= 1)) {
                        $objMEntrada->d21 = null;
                        $objMEntrada->calendariolaboral21_id = null;
                        $objMEntrada->estado21 = null;
                    }
                    if (($primerDiaCalculo <= 22 && 22 <= $ultimoDiaCalculo) && ($objMEntrada->estado22 == null || $objMEntrada->estado22 <= 1)) {
                        $objMEntrada->d22 = null;
                        $objMEntrada->calendariolaboral22_id = null;
                        $objMEntrada->estado22 = null;
                    }
                    if (($primerDiaCalculo <= 23 && 23 <= $ultimoDiaCalculo) && ($objMEntrada->estado23 == null || $objMEntrada->estado23 <= 1)) {
                        $objMEntrada->d23 = null;
                        $objMEntrada->calendariolaboral23_id = null;
                        $objMEntrada->estado23 = null;
                    }
                    if (($primerDiaCalculo <= 24 && 24 <= $ultimoDiaCalculo) && ($objMEntrada->estado24 == null || $objMEntrada->estado24 <= 1)) {
                        $objMEntrada->d24 = null;
                        $objMEntrada->calendariolaboral24_id = null;
                        $objMEntrada->estado24 = null;
                    }
                    if (($primerDiaCalculo <= 25 && 25 <= $ultimoDiaCalculo) && ($objMEntrada->estado25 == null || $objMEntrada->estado25 <= 1)) {
                        $objMEntrada->d25 = null;
                        $objMEntrada->calendariolaboral25_id = null;
                        $objMEntrada->estado25 = null;
                    }
                    if (($primerDiaCalculo <= 26 && 26 <= $ultimoDiaCalculo) && ($objMEntrada->estado26 == null || $objMEntrada->estado26 <= 1)) {
                        $objMEntrada->d26 = null;
                        $objMEntrada->calendariolaboral26_id = null;
                        $objMEntrada->estado26 = null;
                    }
                    if (($primerDiaCalculo <= 27 && 27 <= $ultimoDiaCalculo) && ($objMEntrada->estado27 == null || $objMEntrada->estado27 <= 1)) {
                        $objMEntrada->d27 = null;
                        $objMEntrada->calendariolaboral27_id = null;
                        $objMEntrada->estado27 = null;
                    }
                    if (($primerDiaCalculo <= 28 && 28 <= $ultimoDiaCalculo) && ($objMEntrada->estado28 == null || $objMEntrada->estado28 <= 1)) {
                        $objMEntrada->d28 = null;
                        $objMEntrada->calendariolaboral28_id = null;
                        $objMEntrada->estado28 = null;
                    }
                    if (($primerDiaCalculo <= 29 && 29 <= $ultimoDiaCalculo) && ($objMEntrada->estado29 == null || $objMEntrada->estado29 <= 1)) {
                        $objMEntrada->d29 = null;
                        $objMEntrada->calendariolaboral29_id = null;
                        $objMEntrada->estado29 = null;
                    }
                    if (($primerDiaCalculo <= 30 && 30 <= $ultimoDiaCalculo) && ($objMEntrada->estado30 == null || $objMEntrada->estado30 <= 1)) {
                        $objMEntrada->d30 = null;
                        $objMEntrada->calendariolaboral30_id = null;
                        $objMEntrada->estado30 = null;
                    }
                    if (($primerDiaCalculo <= 31 && 31 <= $ultimoDiaCalculo) && ($objMEntrada->estado31 == null || $objMEntrada->estado31 <= 1)) {
                        $objMEntrada->d31 = null;
                        $objMEntrada->calendariolaboral31_id = null;
                        $objMEntrada->estado31 = null;
                    }

                    if (($primerDiaCalculo <= 1 && 1 <= $ultimoDiaCalculo) && ($objMSalida->estado1 == null || $objMSalida->estado1 <= 1)) {
                        $objMSalida->d1 = null;
                        $objMSalida->calendariolaboral1_id = null;
                        $objMSalida->estado1 = null;
                    }
                    if (($primerDiaCalculo <= 2 && 2 <= $ultimoDiaCalculo) && ($objMSalida->estado2 == null || $objMSalida->estado2 <= 1)) {
                        $objMSalida->d2 = null;
                        $objMSalida->calendariolaboral2_id = null;
                        $objMSalida->estado2 = null;
                    }
                    if (($primerDiaCalculo <= 3 && 3 <= $ultimoDiaCalculo) && ($objMSalida->estado3 == null || $objMSalida->estado3 <= 1)) {
                        $objMSalida->d3 = null;
                        $objMSalida->calendariolaboral3_id = null;
                        $objMSalida->estado3 = null;
                    }
                    if (($primerDiaCalculo <= 4 && 4 <= $ultimoDiaCalculo) && ($objMSalida->estado4 == null || $objMSalida->estado4 <= 1)) {
                        $objMSalida->d4 = null;
                        $objMSalida->calendariolaboral4_id = null;
                        $objMSalida->estado4 = null;
                    }
                    if (($primerDiaCalculo <= 5 && 5 <= $ultimoDiaCalculo) && ($objMSalida->estado5 == null || $objMSalida->estado5 <= 1)) {
                        $objMSalida->d5 = null;
                        $objMSalida->calendariolaboral5_id = null;
                        $objMSalida->estado5 = null;
                    }
                    if (($primerDiaCalculo <= 6 && 6 <= $ultimoDiaCalculo) && ($objMSalida->estado6 == null || $objMSalida->estado6 <= 1)) {
                        $objMSalida->d6 = null;
                        $objMSalida->calendariolaboral6_id = null;
                        $objMSalida->estado6 = null;
                    }
                    if (($primerDiaCalculo <= 7 && 7 <= $ultimoDiaCalculo) && ($objMSalida->estado7 == null || $objMSalida->estado7 <= 1)) {
                        $objMSalida->d7 = null;
                        $objMSalida->calendariolaboral7_id = null;
                        $objMSalida->estado7 = null;
                    }
                    if (($primerDiaCalculo <= 8 && 8 <= $ultimoDiaCalculo) && ($objMSalida->estado8 == null || $objMSalida->estado8 <= 1)) {
                        $objMSalida->d8 = null;
                        $objMSalida->calendariolaboral8_id = null;
                        $objMSalida->estado8 = null;
                    }
                    if (($primerDiaCalculo <= 9 && 9 <= $ultimoDiaCalculo) && ($objMSalida->estado9 == null || $objMSalida->estado9 <= 1)) {
                        $objMSalida->d9 = null;
                        $objMSalida->calendariolaboral9_id = null;
                        $objMSalida->estado9 = null;
                    }
                    if (($primerDiaCalculo <= 10 && 10 <= $ultimoDiaCalculo) && ($objMSalida->estado10 == null || $objMSalida->estado10 <= 1)) {
                        $objMSalida->d10 = null;
                        $objMSalida->calendariolaboral10_id = null;
                        $objMSalida->estado10 = null;
                    }
                    if (($primerDiaCalculo <= 11 && 11 <= $ultimoDiaCalculo) && ($objMSalida->estado11 == null || $objMSalida->estado11 <= 1)) {
                        $objMSalida->d11 = null;
                        $objMSalida->calendariolaboral11_id = null;
                        $objMSalida->estado11 = null;
                    }
                    if (($primerDiaCalculo <= 12 && 12 <= $ultimoDiaCalculo) && ($objMSalida->estado12 == null || $objMSalida->estado12 <= 1)) {
                        $objMSalida->d12 = null;
                        $objMSalida->calendariolaboral12_id = null;
                        $objMSalida->estado12 = null;
                    }
                    if (($primerDiaCalculo <= 13 && 13 <= $ultimoDiaCalculo) && ($objMSalida->estado13 == null || $objMSalida->estado13 <= 1)) {
                        $objMSalida->d13 = null;
                        $objMSalida->calendariolaboral13_id = null;
                        $objMSalida->estado13 = null;
                    }
                    if (($primerDiaCalculo <= 14 && 14 <= $ultimoDiaCalculo) && ($objMSalida->estado14 == null || $objMSalida->estado14 <= 1)) {
                        $objMSalida->d14 = null;
                        $objMSalida->calendariolaboral14_id = null;
                        $objMSalida->estado14 = null;
                    }
                    if (($primerDiaCalculo <= 15 && 15 <= $ultimoDiaCalculo) && ($objMSalida->estado15 == null || $objMSalida->estado15 <= 1)) {
                        $objMSalida->d15 = null;
                        $objMSalida->calendariolaboral15_id = null;
                        $objMSalida->estado15 = null;
                    }
                    if (($primerDiaCalculo <= 16 && 16 <= $ultimoDiaCalculo) && ($objMSalida->estado16 == null || $objMSalida->estado16 <= 1)) {
                        $objMSalida->d16 = null;
                        $objMSalida->calendariolaboral16_id = null;
                        $objMSalida->estado16 = null;
                    }
                    if (($primerDiaCalculo <= 17 && 17 <= $ultimoDiaCalculo) && ($objMSalida->estado17 == null || $objMSalida->estado17 <= 1)) {
                        $objMSalida->d17 = null;
                        $objMSalida->calendariolaboral17_id = null;
                        $objMSalida->estado17 = null;
                    }
                    if (($primerDiaCalculo <= 18 && 18 <= $ultimoDiaCalculo) && ($objMSalida->estado18 == null || $objMSalida->estado18 <= 1)) {
                        $objMSalida->d18 = null;
                        $objMSalida->calendariolaboral18_id = null;
                        $objMSalida->estado18 = null;
                    }
                    if (($primerDiaCalculo <= 19 && 19 <= $ultimoDiaCalculo) && ($objMSalida->estado19 == null || $objMSalida->estado19 <= 1)) {
                        $objMSalida->d19 = null;
                        $objMSalida->calendariolaboral19_id = null;
                        $objMSalida->estado19 = null;
                    }
                    if (($primerDiaCalculo <= 20 && 20 <= $ultimoDiaCalculo) && ($objMSalida->estado20 == null || $objMSalida->estado20 <= 1)) {
                        $objMSalida->d20 = null;
                        $objMSalida->calendariolaboral20_id = null;
                        $objMSalida->estado20 = null;
                    }
                    if (($primerDiaCalculo <= 21 && 21 <= $ultimoDiaCalculo) && ($objMSalida->estado21 == null || $objMSalida->estado21 <= 1)) {
                        $objMSalida->d21 = null;
                        $objMSalida->calendariolaboral21_id = null;
                        $objMSalida->estado21 = null;
                    }
                    if (($primerDiaCalculo <= 22 && 22 <= $ultimoDiaCalculo) && ($objMSalida->estado22 == null || $objMSalida->estado22 <= 1)) {
                        $objMSalida->d22 = null;
                        $objMSalida->calendariolaboral22_id = null;
                        $objMSalida->estado22 = null;
                    }
                    if (($primerDiaCalculo <= 23 && 23 <= $ultimoDiaCalculo) && ($objMSalida->estado23 == null || $objMSalida->estado23 <= 1)) {
                        $objMSalida->d23 = null;
                        $objMSalida->calendariolaboral23_id = null;
                        $objMSalida->estado23 = null;
                    }
                    if (($primerDiaCalculo <= 24 && 24 <= $ultimoDiaCalculo) && ($objMSalida->estado24 == null || $objMSalida->estado24 <= 1)) {
                        $objMSalida->d24 = null;
                        $objMSalida->calendariolaboral24_id = null;
                        $objMSalida->estado24 = null;
                    }
                    if (($primerDiaCalculo <= 25 && 25 <= $ultimoDiaCalculo) && ($objMSalida->estado25 == null || $objMSalida->estado25 <= 1)) {
                        $objMSalida->d25 = null;
                        $objMSalida->calendariolaboral25_id = null;
                        $objMSalida->estado25 = null;
                    }
                    if (($primerDiaCalculo <= 26 && 26 <= $ultimoDiaCalculo) && ($objMSalida->estado26 == null || $objMSalida->estado26 <= 1)) {
                        $objMSalida->d26 = null;
                        $objMSalida->calendariolaboral26_id = null;
                        $objMSalida->estado26 = null;
                    }
                    if (($primerDiaCalculo <= 27 && 27 <= $ultimoDiaCalculo) && ($objMSalida->estado27 == null || $objMSalida->estado27 <= 1)) {
                        $objMSalida->d27 = null;
                        $objMSalida->calendariolaboral27_id = null;
                        $objMSalida->estado27 = null;
                    }
                    if (($primerDiaCalculo <= 28 && 28 <= $ultimoDiaCalculo) && ($objMSalida->estado28 == null || $objMSalida->estado28 <= 1)) {
                        $objMSalida->d28 = null;
                        $objMSalida->calendariolaboral28_id = null;
                        $objMSalida->estado28 = null;
                    }
                    if (($primerDiaCalculo <= 29 && 29 <= $ultimoDiaCalculo) && ($objMSalida->estado29 == null || $objMSalida->estado29 <= 1)) {
                        $objMSalida->d29 = null;
                        $objMSalida->calendariolaboral29_id = null;
                        $objMSalida->estado29 = null;
                    }
                    if (($primerDiaCalculo <= 30 && 30 <= $ultimoDiaCalculo) && ($objMSalida->estado30 == null || $objMSalida->estado30 <= 1)) {
                        $objMSalida->d30 = null;
                        $objMSalida->calendariolaboral30_id = null;
                        $objMSalida->estado30 = null;
                    }
                    if (($primerDiaCalculo <= 31 && 31 <= $ultimoDiaCalculo) && ($objMSalida->estado31 == null || $objMSalida->estado31 <= 1)) {
                        $objMSalida->d31 = null;
                        $objMSalida->calendariolaboral31_id = null;
                        $objMSalida->estado31 = null;
                    }

                    $objMEntrada->turno = $turno;
                    $objMSalida->turno = $turno;
                    $objMEntrada->grupo = $grupoA;
                    $objMSalida->grupo = $grupoB;
                    /*for($dia=1;$dia<=31;$dia++){*/
                    for ($dia = $primerDiaCalculo; $dia <= $ultimoDiaCalculo; $dia++) {
                        if (isset($matrizDiasSemana[$dia])) {
                            if (isset($matrizHorarios[$dia][$turno][$grupoA])) {
                                $objME = new Marcaciones();
                                $objMS = new Marcaciones();
                                $horaMarcacionEntrada = null;
                                $horaMarcacionSalida = null;
                                $fecha = $matrizFechas[$dia];
                                $idHorarioLaboral = $matrizIdHorarios[$dia][$turno][$grupoA];
                                //$resultE = $objME->obtenerUnaMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboral);
                                $horaMarcacionEntrada = $objME->obtenerHoraMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboral);
                                //$resultS = $objMS->obtenerMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboral, 1);
                                $horaMarcacionSalida = $objMS->obtenerHoraMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboral, 1);
                                //echo "<p>----->1:  ".$horaMarcacionEntrada." 2: ".$horaMarcacionSalida;
                                /*if (is_object($resultE)) {
                                    foreach ($resultE as $obe) {
                                        $horaMarcacionEntrada = $obe->hora;
                                    }
                                } else {
                                    $horaMarcacionEntrada = null;
                                }
                                if (is_object($resultS)) {
                                    foreach ($resultS as $obs) {
                                        $horaMarcacionSalida = $obs->hora;
                                    }
                                } else {
                                    $horaMarcacionSalida = null;
                                }*/
                                switch ($dia) {

                                    case 1 :
                                        $objMEntrada->calendariolaboral1_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado1 = 1;
                                        $objMEntrada->d1 = $horaMarcacionEntrada;
                                        if ($existeMarcacionCruzadaMixtaInicialEnMes == 0) {
                                            /**
                                             * Cálculos para el control de marcaciones cruzadas en mes previo
                                             */
                                            $ctrlMarcacion = $objM->controlExisteMarcacionMixta($idRelaboral, $fecha);
                                            if ($ctrlMarcacion == 1 || $ctrlMarcacion == 3) {
                                                $objHorario = new Horarioslaborales();
                                                $objCalHor = $objHorario->obtenerUltimoIdCalendarioYHorarioCruzadoEnDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                if (is_object($objCalHor)) {
                                                    $objMSalida->estado1 = 1;
                                                    $objMSalida->calendariolaboral1_id = $objCalHor[0]->id_calendariolaboral;
                                                }
                                            } else {
                                                if ($ctrlMarcacion == 0) {
                                                    $horaMarcacionSalida = $objMS->obtenerHoraMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboral, 1);
                                                    $objMSalida->estado1 = $matrizEstados[$dia][$turno][$grupoB];
                                                    $objMSalida->calendariolaboral1_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                                } else {
                                                    $horaMarcacionSalida = null;
                                                }
                                            }
                                            /*if (is_object($resultS)) {
                                                foreach ($resultS as $obs) {
                                                    $horaMarcacionSalida = $obs->hora;
                                                }
                                            } else {
                                                $horaMarcacionSalida = null;
                                            }*/
                                            $objMSalida->d1 = $horaMarcacionSalida;
                                        } else {
                                            $ctrlMarcacion = $objM->controlExisteMarcacionMixta($idRelaboral, $fecha);

                                            if ($ctrlMarcacion == 1 || $ctrlMarcacion == 0) {
                                                $objMSalida->calendariolaboral1_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                                $objMSalida->estado1 = $matrizEstados[$dia][$turno][$grupoB];
                                                $objMSalida->d1 = $horaMarcacionSalida;
                                            } else {
                                                $objMSalida->calendariolaboral1_id = null;
                                                $objMSalida->estado1 = null;
                                                $objMSalida->d1 = null;
                                            }

                                        }
                                        break;
                                    case 2 :
                                        $objMEntrada->calendariolaboral2_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado2 = 1;
                                        $objMEntrada->d2 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral2_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado2 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d2 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 3 :
                                        $objMEntrada->calendariolaboral3_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado3 = 1;
                                        $objMEntrada->d3 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral3_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado3 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d3 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 4 :
                                        $objMEntrada->calendariolaboral4_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado4 = 1;
                                        $objMEntrada->d4 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral4_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado4 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d4 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 5 :
                                        $objMEntrada->calendariolaboral5_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado5 = 1;
                                        $objMEntrada->d5 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral5_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado5 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d5 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 6 :
                                        $objMEntrada->calendariolaboral6_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado6 = 1;
                                        $objMEntrada->d6 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral6_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado6 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d6 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 7 :
                                        $objMEntrada->calendariolaboral7_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado7 = 1;
                                        $objMEntrada->d7 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral7_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado7 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d7 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 8 :
                                        $objMEntrada->calendariolaboral8_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado8 = 1;
                                        $objMEntrada->d8 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral8_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado8 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d8 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 9 :
                                        $objMEntrada->calendariolaboral9_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado9 = 1;
                                        $objMEntrada->d9 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral9_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado9 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d9 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 10:
                                        $objMEntrada->calendariolaboral10_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado10 = 1;
                                        $objMEntrada->d10 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral10_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado10 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d10 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 11:
                                        $objMEntrada->calendariolaboral11_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado11 = 1;
                                        $objMEntrada->d11 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral11_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado11 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d11 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 12:
                                        $objMEntrada->calendariolaboral12_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado12 = 1;
                                        $objMEntrada->d12 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral12_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado12 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d12 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 13:
                                        $objMEntrada->calendariolaboral13_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado13 = 1;
                                        $objMEntrada->d13 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral13_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado13 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d13 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 14:
                                        $objMEntrada->calendariolaboral14_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado14 = 1;
                                        $objMEntrada->d14 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral14_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado14 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d14 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 15:
                                        $objMEntrada->calendariolaboral15_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado15 = 1;
                                        $objMEntrada->d15 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral15_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado15 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d15 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 16:
                                        $objMEntrada->calendariolaboral16_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado16 = 1;
                                        $objMEntrada->d16 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral16_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado16 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d16 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 17:
                                        $objMEntrada->calendariolaboral17_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado17 = 1;
                                        $objMEntrada->d17 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral17_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado17 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d17 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 18:
                                        $objMEntrada->calendariolaboral18_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado18 = 1;
                                        $objMEntrada->d18 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral18_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado18 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d18 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 19:
                                        $objMEntrada->calendariolaboral19_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado19 = 1;
                                        $objMEntrada->d19 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral19_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado19 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d19 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 20:
                                        $objMEntrada->calendariolaboral20_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado20 = 1;
                                        $objMEntrada->d20 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral20_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado20 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d20 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 21:
                                        $objMEntrada->calendariolaboral21_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado21 = 1;
                                        $objMEntrada->d21 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral21_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado21 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d21 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 22:
                                        $objMEntrada->calendariolaboral22_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado22 = 1;
                                        $objMEntrada->d22 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral22_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado22 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d22 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 23:
                                        $objMEntrada->calendariolaboral23_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado23 = 1;
                                        $objMEntrada->d23 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral23_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado23 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d23 = $horaMarcacionSalida;
                                        }
                                        //  echo "<p>--->".$horaMarcacionSalida;
                                        break;
                                    case 24:
                                        $objMEntrada->calendariolaboral24_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado24 = 1;
                                        $objMEntrada->d24 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral24_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado24 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d24 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 25:
                                        $objMEntrada->calendariolaboral25_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado25 = 1;
                                        $objMEntrada->d25 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral25_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado25 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d25 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 26:
                                        $objMEntrada->calendariolaboral26_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado26 = 1;
                                        $objMEntrada->d26 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral26_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado26 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d26 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 27:
                                        $objMEntrada->calendariolaboral27_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado27 = 1;
                                        $objMEntrada->d27 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral27_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado27 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d27 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 28:
                                        $objMEntrada->calendariolaboral28_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                        $objMEntrada->estado28 = 1;
                                        $objMEntrada->d28 = $horaMarcacionEntrada;
                                        if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                            $objMSalida->calendariolaboral28_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                            $objMSalida->estado28 = $matrizEstados[$dia][$turno][$grupoB];
                                            $objMSalida->d28 = $horaMarcacionSalida;
                                        }
                                        break;
                                    case 29:
                                        if ($ultimoDia >= 29) {
                                            $objMEntrada->calendariolaboral29_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado29 = 1;
                                            $objMEntrada->d29 = $horaMarcacionEntrada;
                                            if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                                $objMSalida->calendariolaboral29_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                                $objMSalida->estado29 = $matrizEstados[$dia][$turno][$grupoB];
                                                $objMSalida->d29 = $horaMarcacionSalida;
                                            }
                                        }
                                        break;
                                    case 30:
                                        if ($ultimoDia >= 30) {
                                            $objMEntrada->calendariolaboral30_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado30 = 1;
                                            $objMEntrada->d30 = $horaMarcacionEntrada;
                                            if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                                $objMSalida->calendariolaboral30_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                                $objMSalida->estado30 = $matrizEstados[$dia][$turno][$grupoB];
                                                $objMSalida->d30 = $horaMarcacionSalida;
                                            }
                                        }
                                        break;
                                    case 31:
                                        if ($ultimoDia >= 31) {
                                            $objMEntrada->calendariolaboral31_id = $matrizIdCalendarios[$dia][$turno][$grupoA];
                                            $objMEntrada->estado31 = 1;
                                            $objMEntrada->d31 = $horaMarcacionEntrada;
                                            if ($matrizIdCalendarios[$dia][$turno][$grupoB] != null) {
                                                $objMSalida->calendariolaboral31_id = $matrizIdCalendarios[$dia][$turno][$grupoB];
                                                $objMSalida->estado31 = $matrizEstados[$dia][$turno][$grupoB];
                                                $objMSalida->d31 = $horaMarcacionSalida;
                                            }
                                        }
                                        break;
                                }
                            }
                        }
                        /**
                         * En caso de que en el mes exista al menos un marcación cruzada Mixta Inicial es necesario crear un registro adicional al ya existente
                         */
                        if ($existeMarcacionCruzadaMixtaInicialEnMes == 0) {
                            $objMS = new Marcaciones();
                            #region Cálculo de las marcaciones para las salidas con cruce
                            if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB]) || isset($matrizHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                //$diaCruzado = $dia-1;
                                $horaMarcacionSalida = null;
                                if (isset($matrizHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                    $idHorarioLaboral = $matrizIdHorariosCruzados[$dia - 1][$turno][$grupoB];
                                    if ($dia > 1) {
                                        $diaAux = $dia - 1;
                                        $fechaAux = $diaAux . "-" . $mes . "-" . $gestion;
                                        //$resultS = $objMS->obtenerMarcacionValida($idRelaboral, 0, $fechaAux, $idHorarioLaboral, 1);
                                        $horaMarcacionSalida = $objMS->obtenerHoraMarcacionValida($idRelaboral, 0, $fechaAux, $idHorarioLaboral, 1);
                                        /*if (is_object($resultS)) {
                                            foreach ($resultS as $obs) {
                                                $horaMarcacionSalida = $obs->hora;
                                            }
                                        }*/
                                    }
                                }
                                switch ($dia) {
                                    /*case 1 :if($objMSalida->estado1 ==null||$objMSalida->estado1 <=1){
                                        $objMSalida->calendariolaboral2_id=$matrizIdCalendariosHorariosCruzados[$dia-1][$turno][$grupoB];
                                        $objMSalida->estado2=1;
                                        $objMSalida->d2=$horaMarcacionSalida;}
                                        break;*/
                                    case 2 :
                                        if ($objMSalida->estado2 == null || $objMSalida->estado2 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral2_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado2 = 1;
                                                $objMSalida->d2 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral2_id = null;
                                                    $objMSalida->estado2 = null;
                                                    $objMSalida->d2 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 3 :
                                        if ($objMSalida->estado3 == null || $objMSalida->estado3 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral3_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado3 = 1;
                                                $objMSalida->d3 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral3_id = null;
                                                    $objMSalida->estado3 = null;
                                                    $objMSalida->d3 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 4 :
                                        if ($objMSalida->estado4 == null || $objMSalida->estado4 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral4_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado4 = 1;
                                                $objMSalida->d4 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral4_id = null;
                                                    $objMSalida->estado4 = null;
                                                    $objMSalida->d4 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 5 :
                                        if ($objMSalida->estado5 == null || $objMSalida->estado5 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral5_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado5 = 1;
                                                $objMSalida->d5 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral5_id = null;
                                                    $objMSalida->estado5 = null;
                                                    $objMSalida->d5 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 6 :
                                        if ($objMSalida->estado6 == null || $objMSalida->estado6 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral6_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado6 = 1;
                                                $objMSalida->d6 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral8_id = null;
                                                    $objMSalida->estado8 = null;
                                                    $objMSalida->d8 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 7 :
                                        if ($objMSalida->estado7 == null || $objMSalida->estado7 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral7_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado7 = 1;
                                                $objMSalida->d7 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral7_id = null;
                                                    $objMSalida->estado7 = null;
                                                    $objMSalida->d7 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 8 :
                                        if ($objMSalida->estado8 == null || $objMSalida->estado8 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral8_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado8 = 1;
                                                $objMSalida->d8 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral8_id = null;
                                                    $objMSalida->estado8 = null;
                                                    $objMSalida->d8 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 9 :
                                        if ($objMSalida->estado9 == null || $objMSalida->estado9 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral9_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado9 = 1;
                                                $objMSalida->d9 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral9_id = null;
                                                    $objMSalida->estado9 = null;
                                                    $objMSalida->d9 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 10:
                                        if ($objMSalida->estado10 == null || $objMSalida->estado10 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral10_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado10 = 1;
                                                $objMSalida->d10 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral10_id = null;
                                                    $objMSalida->estado10 = null;
                                                    $objMSalida->d10 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 11:
                                        if ($objMSalida->estado11 == null || $objMSalida->estado11 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral11_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado11 = 1;
                                                $objMSalida->d11 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral11_id = null;
                                                    $objMSalida->estado11 = null;
                                                    $objMSalida->d11 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 12:
                                        if ($objMSalida->estado12 == null || $objMSalida->estado12 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral12_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado12 = 1;
                                                $objMSalida->d12 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral12_id = null;
                                                    $objMSalida->estado12 = null;
                                                    $objMSalida->d12 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 13:
                                        if ($objMSalida->estado13 == null || $objMSalida->estado13 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral13_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado13 = 1;
                                                $objMSalida->d13 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral13_id = null;
                                                    $objMSalida->estado13 = null;
                                                    $objMSalida->d13 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 14:
                                        if ($objMSalida->estado14 == null || $objMSalida->estado14 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral14_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado14 = 1;
                                                $objMSalida->d14 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral14_id = null;
                                                    $objMSalida->estado14 = null;
                                                    $objMSalida->d14 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 15:
                                        if ($objMSalida->estado15 == null || $objMSalida->estado15 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral15_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado15 = 1;
                                                $objMSalida->d15 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral15_id = null;
                                                    $objMSalida->estado15 = null;
                                                    $objMSalida->d15 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 16:
                                        if ($objMSalida->estado16 == null || $objMSalida->estado16 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral16_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado16 = 1;
                                                $objMSalida->d16 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral16_id = null;
                                                    $objMSalida->estado16 = null;
                                                    $objMSalida->d16 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 17:
                                        if ($objMSalida->estado17 == null || $objMSalida->estado17 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral17_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado17 = 1;
                                                $objMSalida->d17 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral17_id = null;
                                                    $objMSalida->estado17 = null;
                                                    $objMSalida->d17 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 18:
                                        if ($objMSalida->estado18 == null || $objMSalida->estado18 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral18_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado18 = 1;
                                                $objMSalida->d18 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral18_id = null;
                                                    $objMSalida->estado18 = null;
                                                    $objMSalida->d18 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 19:
                                        if ($objMSalida->estado19 == null || $objMSalida->estado19 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral19_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado19 = 1;
                                                $objMSalida->d19 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral19_id = null;
                                                    $objMSalida->estado19 = null;
                                                    $objMSalida->d19 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 20:
                                        if ($objMSalida->estado20 == null || $objMSalida->estado20 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral20_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado20 = 1;
                                                $objMSalida->d20 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral20_id = null;
                                                    $objMSalida->estado20 = null;
                                                    $objMSalida->d20 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 21:
                                        if ($objMSalida->estado21 == null || $objMSalida->estado21 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral21_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado21 = 1;
                                                $objMSalida->d21 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral21_id = null;
                                                    $objMSalida->estado21 = null;
                                                    $objMSalida->d21 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 22:
                                        if ($objMSalida->estado22 == null || $objMSalida->estado22 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral22_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado22 = 1;
                                                $objMSalida->d22 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral22_id = null;
                                                    $objMSalida->estado22 = null;
                                                    $objMSalida->d22 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 23:
                                        if ($objMSalida->estado23 == null || $objMSalida->estado23 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral23_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado23 = 1;
                                                $objMSalida->d23 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral23_id = null;
                                                    $objMSalida->estado23 = null;
                                                    $objMSalida->d23 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 24:
                                        if ($objMSalida->estado24 == null || $objMSalida->estado24 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral24_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado24 = 1;
                                                $objMSalida->d24 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral24_id = null;
                                                    $objMSalida->estado24 = null;
                                                    $objMSalida->d24 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 25:
                                        if ($objMSalida->estado25 == null || $objMSalida->estado25 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral25_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado25 = 1;
                                                $objMSalida->d25 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral25_id = null;
                                                    $objMSalida->estado25 = null;
                                                    $objMSalida->d25 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 26:
                                        if ($objMSalida->estado26 == null || $objMSalida->estado26 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral26_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado26 = 1;
                                                $objMSalida->d26 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral26_id = null;
                                                    $objMSalida->estado26 = null;
                                                    $objMSalida->d26 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 27:
                                        if ($objMSalida->estado27 == null || $objMSalida->estado27 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral27_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado27 = 1;
                                                $objMSalida->d27 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral27_id = null;
                                                    $objMSalida->estado27 = null;
                                                    $objMSalida->d27 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 28:
                                        if ($objMSalida->estado28 == null || $objMSalida->estado28 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral28_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado28 = 1;
                                                $objMSalida->d28 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral28_id = null;
                                                    $objMSalida->estado28 = null;
                                                    $objMSalida->d28 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 29:
                                        if ($ultimoDia >= 29 && ($objMSalida->estado29 == null || $objMSalida->estado29 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral29_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado29 = 1;
                                                $objMSalida->d29 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral29_id = null;
                                                    $objMSalida->estado29 = null;
                                                    $objMSalida->d29 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 30:
                                        if ($ultimoDia >= 30 && ($objMSalida->estado30 == null || $objMSalida->estado30 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral30_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado30 = 1;
                                                $objMSalida->d30 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral30_id = null;
                                                    $objMSalida->estado30 = null;
                                                    $objMSalida->d30 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 31:
                                        if ($ultimoDia >= 31 && ($objMSalida->estado31 == null || $objMSalida->estado31 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalida->calendariolaboral31_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalida->estado31 = 1;
                                                $objMSalida->d31 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalida->calendariolaboral31_id = null;
                                                    $objMSalida->estado31 = null;
                                                    $objMSalida->d31 = null;
                                                }
                                            }
                                        }
                                        break;
                                }
                            }
                            #endregion
                        } else {
                            #region Cálculo de las marcaciones para las salidas con cruce y además se presenta una marcación cruzada inicial que amerita la existencia de un registro adicional de ingresos
                            if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB]) || isset($matrizHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                $horaMarcacionSalida = null;
                                if (isset($matrizHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                    $idHorarioLaboral = $matrizIdHorariosCruzados[$dia - 1][$turno][$grupoB];
                                    /**
                                     * Se reconsidera el cálculo debido a que la obtención de la marcación ya toma en cuenta
                                     * horarios cruzados.
                                     */
                                    if ($dia > 1) {
                                        $fecha = $matrizFechas[$dia - 1];
                                    }
                                    /*$resultS = $objMS->obtenerMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboral, 1);*/
                                    $horaMarcacionSalida = $objMS->obtenerHoraMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboral, 1);
                                    /*if (is_object($resultS)) {
                                        foreach ($resultS as $obs) {
                                            $horaMarcacionSalida = $obs->hora;
                                        }
                                    }*/
                                }
                                switch ($dia) {
                                    case 1 :
                                        $ctrlHorarioCruzado = $objM->controlExisteMarcacionMixta($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                        if ($ctrlHorarioCruzado == 1 || $ctrlHorarioCruzado == 3) {
                                            /**
                                             * Es necesario obtener la última marcación de salida efectiva del anterior mes en caso de seguir editable.
                                             */
                                            if ($objMSalidaAux->estado1 == null || $objMSalidaAux->estado1 <= 1) {
                                                $objC = new Calendarioslaborales();
                                                $horaMarcacionSalida = null;
                                                $idCalendarioDiaPrevio = $objC->getUltimoIdCalendarioLaboralEntradaDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                $idHorarioLaboralSalidaPendienteDiaPrevio = $objC->getUltimoIdHorarioLaboralPendienteDiaPrevio($idRelaboral, $dia . "-" . $mes . "-" . $gestion);
                                                //$resultS = $objMS->obtenerMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboralSalidaPendienteDiaPrevio, 1);
                                                $horaMarcacionSalida = $objMS->obtenerHoraMarcacionValida($idRelaboral, 0, $fecha, $idHorarioLaboralSalidaPendienteDiaPrevio, 1);
                                                /*if (is_object($resultS)) {
                                                    foreach ($resultS as $obs) {
                                                        $horaMarcacionSalida = $obs->hora;
                                                    }
                                                }*/
                                                $objMSalidaAux->calendariolaboral1_id = $idCalendarioDiaPrevio;
                                                $objMSalidaAux->estado1 = 1;
                                                $objMSalidaAux->d1 = $horaMarcacionSalida;
                                            }
                                        } else {
                                            $objMSalidaAux->calendariolaboral1_id = null;
                                            $objMSalidaAux->estado1 = null;
                                            $objMSalidaAux->d1 = null;
                                        }
                                        break;
                                    case 2 :
                                        if ($objMSalidaAux->estado2 == null || $objMSalidaAux->estado2 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral2_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado2 = 1;
                                                $objMSalidaAux->d2 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral2_id = null;
                                                    $objMSalidaAux->estado2 = null;
                                                    $objMSalidaAux->d2 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 3 :
                                        if ($objMSalidaAux->estado3 == null || $objMSalidaAux->estado3 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral3_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado3 = 1;
                                                $objMSalidaAux->d3 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral3_id = null;
                                                    $objMSalidaAux->estado3 = null;
                                                    $objMSalidaAux->d3 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 4 :
                                        if ($objMSalidaAux->estado4 == null || $objMSalidaAux->estado4 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral4_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado4 = 1;
                                                $objMSalidaAux->d4 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral4_id = null;
                                                    $objMSalidaAux->estado4 = null;
                                                    $objMSalidaAux->d4 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 5 :
                                        if ($objMSalidaAux->estado5 == null || $objMSalidaAux->estado5 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral5_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado5 = 1;
                                                $objMSalidaAux->d5 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral5_id = null;
                                                    $objMSalidaAux->estado5 = null;
                                                    $objMSalidaAux->d5 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 6 :
                                        if ($objMSalidaAux->estado6 == null || $objMSalidaAux->estado6 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral6_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado6 = 1;
                                                $objMSalidaAux->d6 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral8_id = null;
                                                    $objMSalidaAux->estado8 = null;
                                                    $objMSalidaAux->d8 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 7 :
                                        if ($objMSalidaAux->estado7 == null || $objMSalidaAux->estado7 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral7_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado7 = 1;
                                                $objMSalidaAux->d7 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral7_id = null;
                                                    $objMSalidaAux->estado7 = null;
                                                    $objMSalidaAux->d7 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 8 :
                                        if ($objMSalidaAux->estado8 == null || $objMSalidaAux->estado8 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral8_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado8 = 1;
                                                $objMSalidaAux->d8 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral8_id = null;
                                                    $objMSalidaAux->estado8 = null;
                                                    $objMSalidaAux->d8 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 9 :
                                        if ($objMSalidaAux->estado9 == null || $objMSalidaAux->estado9 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral9_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado9 = 1;
                                                $objMSalidaAux->d9 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral9_id = null;
                                                    $objMSalidaAux->estado9 = null;
                                                    $objMSalidaAux->d9 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 10:
                                        if ($objMSalidaAux->estado10 == null || $objMSalidaAux->estado10 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral10_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado10 = 1;
                                                $objMSalidaAux->d10 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral10_id = null;
                                                    $objMSalidaAux->estado10 = null;
                                                    $objMSalidaAux->d10 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 11:
                                        if ($objMSalidaAux->estado11 == null || $objMSalidaAux->estado11 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral11_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado11 = 1;
                                                $objMSalidaAux->d11 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral11_id = null;
                                                    $objMSalidaAux->estado11 = null;
                                                    $objMSalidaAux->d11 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 12:
                                        if ($objMSalidaAux->estado12 == null || $objMSalidaAux->estado12 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral12_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado12 = 1;
                                                $objMSalidaAux->d12 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral12_id = null;
                                                    $objMSalidaAux->estado12 = null;
                                                    $objMSalidaAux->d12 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 13:
                                        if ($objMSalidaAux->estado13 == null || $objMSalidaAux->estado13 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral13_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado13 = 1;
                                                $objMSalidaAux->d13 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral13_id = null;
                                                    $objMSalidaAux->estado13 = null;
                                                    $objMSalidaAux->d13 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 14:
                                        if ($objMSalidaAux->estado14 == null || $objMSalidaAux->estado14 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral14_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado14 = 1;
                                                $objMSalidaAux->d14 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral14_id = null;
                                                    $objMSalidaAux->estado14 = null;
                                                    $objMSalidaAux->d14 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 15:
                                        if ($objMSalidaAux->estado15 == null || $objMSalidaAux->estado15 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral15_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado15 = 1;
                                                $objMSalidaAux->d15 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral15_id = null;
                                                    $objMSalidaAux->estado15 = null;
                                                    $objMSalidaAux->d15 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 16:
                                        if ($objMSalidaAux->estado16 == null || $objMSalidaAux->estado16 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral16_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado16 = 1;
                                                $objMSalidaAux->d16 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral16_id = null;
                                                    $objMSalidaAux->estado16 = null;
                                                    $objMSalidaAux->d16 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 17:
                                        if ($objMSalidaAux->estado17 == null || $objMSalidaAux->estado17 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral17_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado17 = 1;
                                                $objMSalidaAux->d17 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral17_id = null;
                                                    $objMSalidaAux->estado17 = null;
                                                    $objMSalidaAux->d17 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 18:
                                        if ($objMSalidaAux->estado18 == null || $objMSalidaAux->estado18 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral18_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado18 = 1;
                                                $objMSalidaAux->d18 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral18_id = null;
                                                    $objMSalidaAux->estado18 = null;
                                                    $objMSalidaAux->d18 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 19:
                                        if ($objMSalidaAux->estado19 == null || $objMSalidaAux->estado19 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral19_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado19 = 1;
                                                $objMSalidaAux->d19 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral19_id = null;
                                                    $objMSalidaAux->estado19 = null;
                                                    $objMSalidaAux->d19 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 20:
                                        if ($objMSalidaAux->estado20 == null || $objMSalidaAux->estado20 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral20_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado20 = 1;
                                                $objMSalidaAux->d20 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral20_id = null;
                                                    $objMSalidaAux->estado20 = null;
                                                    $objMSalidaAux->d20 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 21:
                                        if ($objMSalidaAux->estado21 == null || $objMSalidaAux->estado21 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral21_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado21 = 1;
                                                $objMSalidaAux->d21 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral21_id = null;
                                                    $objMSalidaAux->estado21 = null;
                                                    $objMSalidaAux->d21 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 22:
                                        if ($objMSalidaAux->estado22 == null || $objMSalidaAux->estado22 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral22_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado22 = 1;
                                                $objMSalidaAux->d22 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral22_id = null;
                                                    $objMSalidaAux->estado22 = null;
                                                    $objMSalidaAux->d22 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 23:
                                        if ($objMSalidaAux->estado23 == null || $objMSalidaAux->estado23 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral23_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado23 = 1;
                                                $objMSalidaAux->d23 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral23_id = null;
                                                    $objMSalidaAux->estado23 = null;
                                                    $objMSalidaAux->d23 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 24:
                                        if ($objMSalidaAux->estado24 == null || $objMSalidaAux->estado24 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral24_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado24 = 1;
                                                $objMSalidaAux->d24 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral24_id = null;
                                                    $objMSalidaAux->estado24 = null;
                                                    $objMSalidaAux->d24 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 25:
                                        if ($objMSalidaAux->estado25 == null || $objMSalidaAux->estado25 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral25_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado25 = 1;
                                                $objMSalidaAux->d25 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral25_id = null;
                                                    $objMSalidaAux->estado25 = null;
                                                    $objMSalidaAux->d25 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 26:
                                        if ($objMSalidaAux->estado26 == null || $objMSalidaAux->estado26 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral26_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado26 = 1;
                                                $objMSalidaAux->d26 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral26_id = null;
                                                    $objMSalidaAux->estado26 = null;
                                                    $objMSalidaAux->d26 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 27:
                                        if ($objMSalidaAux->estado27 == null || $objMSalidaAux->estado27 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral27_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado27 = 1;
                                                $objMSalidaAux->d27 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral27_id = null;
                                                    $objMSalidaAux->estado27 = null;
                                                    $objMSalidaAux->d27 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 28:
                                        if ($objMSalidaAux->estado28 == null || $objMSalidaAux->estado28 <= 1) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral28_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado28 = 1;
                                                $objMSalidaAux->d28 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral28_id = null;
                                                    $objMSalidaAux->estado28 = null;
                                                    $objMSalidaAux->d28 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 29:
                                        if ($ultimoDia >= 29 && ($objMSalidaAux->estado29 == null || $objMSalidaAux->estado29 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral29_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado29 = 1;
                                                $objMSalidaAux->d29 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral29_id = null;
                                                    $objMSalidaAux->estado29 = null;
                                                    $objMSalidaAux->d29 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 30:
                                        if ($ultimoDia >= 30 && ($objMSalidaAux->estado30 == null || $objMSalidaAux->estado30 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral30_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado30 = 1;
                                                $objMSalidaAux->d30 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral30_id = null;
                                                    $objMSalidaAux->estado30 = null;
                                                    $objMSalidaAux->d30 = null;
                                                }
                                            }
                                        }
                                        break;
                                    case 31:
                                        if ($ultimoDia >= 31 && ($objMSalidaAux->estado31 == null || $objMSalidaAux->estado31 <= 1)) {
                                            if (isset($matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB])) {
                                                $objMSalidaAux->calendariolaboral31_id = $matrizIdCalendariosHorariosCruzados[$dia - 1][$turno][$grupoB];
                                                $objMSalidaAux->estado31 = 1;
                                                $objMSalidaAux->d31 = $horaMarcacionSalida;
                                            } else {
                                                if (isset($matrizIdCalendariosHorariosCruzados[$dia][$turno][$grupoB])) {
                                                    $objMSalidaAux->calendariolaboral31_id = null;
                                                    $objMSalidaAux->estado31 = null;
                                                    $objMSalidaAux->d31 = null;
                                                }
                                            }
                                        }
                                        break;
                                }
                            }
                            #endregion
                        }
                    }
                    $objMEntrada->modalidadmarcacion_id = 2;
                    $objMSalida->modalidadmarcacion_id = 5;
                    if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                        $objMSalidaAux->modalidadmarcacion_id = 5;
                        $objMSalidaAux->ultimo_dia = $ultimoDia;
                        //$objMSalidaAux->ultimo_dia=$diaaux;
                        $objMSalidaAux->estado = 1;
                        $objMSalidaAux->baja_logica = 1;
                        $objMSalidaAux->agrupador = 0;
                    }
                    //$objMEntrada->ultimo_dia=$diaaux;$objMSalida->ultimo_dia=$diaaux;
                    $objMEntrada->ultimo_dia = $ultimoDia;
                    $objMSalida->ultimo_dia = $ultimoDia;
                    $objMEntrada->estado = 1;
                    $objMSalida->estado = 1;
                    $objMEntrada->baja_logica = 1;
                    $objMSalida->baja_logica = 1;
                    $objMEntrada->agrupador = 0;
                    $objMSalida->agrupador = 0;
                    try {
                        $okE = $objMEntrada->save();
                        $okS = $objMSalida->save();
                        //$okE=$okS=true;
                        if ($okE) {
                            $entradas++;
                        }
                        if ($okS) {
                            $salidas++;
                        }
                        if ($existeMarcacionCruzadaMixtaInicialEnMes == 1) {
                            $okSA = $objMSalidaAux->save();
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el detalle correspondiente a registros efectivos de marcaci&oacute;n.');
                    }
                }
                if ($entradas > 0 && $salidas > 0 && $entradas == $salidas) {
                    #region Eliminación de registros ya descartados en un proceso anterior
                    $operacion = 2;
                    $sqldel = "INSERT INTO horariosymarcaciones_del ";
                    $sqldel .= "(";
                    $sqldel .= "SELECT hm.*," . $user_reg_id . ",current_timestamp,CAST('" . $fechaIni . "' AS DATE), CAST('" . $fechaFin . "' AS DATE), CAST(" . $operacion . " AS INTEGER) FROM horariosymarcaciones hm WHERE hm.relaboral_id=" . $idRelaboral;
                    $sqldel .= " AND hm.gestion = " . $gestion . " AND hm.mes=" . $mes . " AND hm.modalidadmarcacion_id IN (2,5) AND hm.baja_logica = 0)";
                    $db->execute($sqldel);

                    $sql = "DELETE FROM horariosymarcaciones WHERE relaboral_id=" . $idRelaboral;
                    $sql .= " AND gestion = " . $gestion . " AND mes=" . $mes . " AND modalidadmarcacion_id IN (2,5) AND baja_logica = 0";
                    $db->execute($sql);
                    #endregion
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: El detalle correspondiente a los registros efectivos de marcaci&oacute;n fueron generados correctamente.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro efectivos de marcación.');
                }
            }
            #endregion Edición de Registro
        } else {
            $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro previstos de marcación debido a que no se enviaron todos los datos necesarios.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para dar de baja a los registros de horarios de marcación correspondientes a un registro de relación laboral, gestión y mes determinados.
     */
    public function eliminarAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0 && isset($_POST["gestion"]) && $_POST["gestion"] > 0 && isset($_POST["gestion"]) && $_POST["mes"] > 0) {
            $idRelaboral = $_POST["id_relaboral"];
            $gestion = $_POST["gestion"];
            $mes = $_POST["mes"];
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $clasemarcacion = $_POST["clasemarcacion"];
            if ($clasemarcacion == "H")
                $result = Horariosymarcaciones::find(array("relaboral_id=" . $idRelaboral . " AND gestion=" . $gestion . " AND mes=" . $mes . " AND estado>=1 AND baja_logica=1"));
            else $result = Horariosymarcaciones::find(array("relaboral_id=" . $idRelaboral . " AND gestion=" . $gestion . " AND mes=" . $mes . " AND clasemarcacion='$clasemarcacion' AND estado>=1 AND baja_logica=1"));
            if ($result->count() > 0) {
                $ok2 = true;
                foreach ($result as $val) {
                    $objHM = Horariosymarcaciones::findFirstById($val->id);
                    $objHM->baja_logica = 0;
                    $objHM->user_mod_id = $user_mod_id;
                    $objHM->fecha_mod = $hoy;
                    try {
                        $ok = $objHM->save();
                        if (!$ok) {
                            $ok2 = false;
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'No se pudo dar de baja a los registros correspondientes debido a un error cr&iacute;tico.');
                    }
                }
                if ($ok2) {
                    $msj = array('result' => 1, 'msj' => 'Registro de Baja realizado de modo satisfactorio.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'La baja no se efectu&oacute; de forma satisfactoria.');
                }
            }
        } else {
            $msj = array('result' => 0, 'msj' => 'No se pudo dar de baja a los registros solicitados.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención del listado de meses para la generación de marcaciones previstas y efectivas.
     */
    public function getmesesAction()
    {
        $this->view->disable();
        $rangoMeses = [];
        if (isset($_POST["gestion"])) {
            $rangoMeses[] = array('mes' => 1, 'mes_nombre' => "ENERO");
            $rangoMeses[] = array('mes' => 2, 'mes_nombre' => "FEBRERO");
            $rangoMeses[] = array('mes' => 3, 'mes_nombre' => "MARZO");
            $rangoMeses[] = array('mes' => 4, 'mes_nombre' => "ABRIL");
            $rangoMeses[] = array('mes' => 5, 'mes_nombre' => "MAYO");
            $rangoMeses[] = array('mes' => 6, 'mes_nombre' => "JUNIO");
            $rangoMeses[] = array('mes' => 7, 'mes_nombre' => "JULIO");
            $rangoMeses[] = array('mes' => 8, 'mes_nombre' => "AGOSTO");
            $rangoMeses[] = array('mes' => 9, 'mes_nombre' => "SEPTIEMBRE");
            $rangoMeses[] = array('mes' => 10, 'mes_nombre' => "OCTUBRE");
            $rangoMeses[] = array('mes' => 11, 'mes_nombre' => "NOVIEMBRE");
            $rangoMeses[] = array('mes' => 12, 'mes_nombre' => "DICIEMBRE");
        }
        echo json_encode($rangoMeses);
    }

    /**
     * Función para la obtención del listado de tipos para la generación de marcaciones (Previstas y/o efectivas).
     */
    public function gettiposgeneracionAction()
    {
        $this->view->disable();
        $tipos = [];
        $permisos = $this->obtenerPermisosPorControladorMasIdentificador(strtolower(str_replace("Controller.php", "", basename(__FILE__))), "boolEsPosibleGenerarMatrizEfectiva");
        $obj = json_decode($permisos);
        $ver = $obj->v;
        if (isset($_POST["gestion"]) && isset($_POST["mes"])) {
            if ($ver == 1) $tipos[] = array('tipo' => 1, 'tipo_descripcion' => "PREVISTA & EFECTIVA");
            $tipos[] = array('tipo' => 2, 'tipo_descripcion' => "PREVISTA");
            if ($ver == 1) $tipos[] = array('tipo' => 3, 'tipo_descripcion' => "EFECTIVA");
        }
        echo json_encode($tipos);
    }

    /**
     * Función para convertir un array a formato CSV.
     * @param $input_array
     * @param $output_file_name
     * @param $delimiter
     */
    public function converToCsv($input_array, $output_file_name, $delimiter)
    {
        /** open raw memory as file, no need for temp files */
        $temp_memory = fopen('php://memory', 'w');
        /** loop through array  */
        foreach ($input_array as $line) {
            /** default php csv handler **/
            fputcsv($temp_memory, $line, $delimiter);
        }
        /** rewrind the "file" with the csv lines **/
        fseek($temp_memory, 0);
        /** Modificar la cabecera para ser un archivo descargable **/
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
        /** Enviar el archivo al navegador para descarga */
        fpassthru($temp_memory);
    }

    /**
     * Función para la exportación en formato CSV del resumen de datos almacenados en cuanto a horarios y marcaciones.
     * @param $gestion
     * @param $mesIni
     * @param $mesFin
     * @param $ids
     */
    public function exportresumcsvAction($gestion, $mesIni, $mesFin, $ids)
    {
        $this->view->disable();
        $gestion = base64_decode(str_pad(strtr($gestion, '-_', '+/'), strlen($gestion) % 4, '=', STR_PAD_RIGHT));
        $mesIni = base64_decode(str_pad(strtr($mesIni, '-_', '+/'), strlen($mesIni) % 4, '=', STR_PAD_RIGHT));
        $mesFin = base64_decode(str_pad(strtr($mesFin, '-_', '+/'), strlen($mesFin) % 4, '=', STR_PAD_RIGHT));
        $ids = base64_decode(str_pad(strtr($ids, '-_', '+/'), strlen($ids) % 4, '=', STR_PAD_RIGHT));
        $gestion = json_decode($gestion, true);
        $mesIni = json_decode($mesIni, true);
        $mesFin = json_decode($mesFin, true);
        $ids = json_decode($ids, true);

        $arrResultado = array();
        if ($gestion > 0 && $mesIni > 0 && $mesFin > 0 && $ids != '') {
            $obj = new Fhorariosymarcaciones();
            $arrIds = explode(",", $ids);
            if (count($arrIds) > 0) {
                $arrIdRelaborales = array();
                for ($i = 0; $i < count($arrIds); $i++) {
                    $arrIdRelaborales[$i] = intval($arrIds[$i]);
                }
                $idRelaboralesJson = json_encode($arrIdRelaborales, JSON_FORCE_OBJECT);
            }
            $res = $obj->obtenerResumen($idRelaboralesJson, $gestion, $mesIni, $mesFin);
            $nro = 0;
            $idRelaboral = 0;
            $arrResultado[] = array("Nro.", "Nombres", "CI", "Expd", "Gerencia", "Departamento", "Cargo", "Estado",
                "Fecha Ing", "Fecha Ini", "Fecha Incor", "Fecha Fin", "Fecha Baja", "Gestion", "Mes", "Turno", "Modalidad",
                "1", "2", "3", "4", "5", "6", "7", "8", "9", "10",
                "11", "12", "13", "14", "15", "16", "17", "18", "19", "20",
                "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "Ultimo dia"
            );
            foreach ($res as $val) {
                if ($idRelaboral != $val->relaboral_id) {
                    $nro++;
                    $idRelaboral = $val->relaboral_id;
                }
                $fechaIng = $val->fecha_ing != "" ? date("Y-m-d", strtotime($val->fecha_ing)) : "";
                $fechaIni = $val->fecha_ini != "" ? date("Y-m-d", strtotime($val->fecha_ini)) : "";
                $fechaIncor = $val->fecha_incor != "" ? date("Y-m-d", strtotime($val->fecha_incor)) : "";
                $fechaFin = $val->fecha_fin != "" ? date("Y-m-d", strtotime($val->fecha_fin)) : "";
                $fechaBaja = $val->fecha_baja != "" ? date("Y-m-d", strtotime($val->fecha_baja)) : "";
                $arrResultado[] = array(
                    $nro, $val->nombres, $val->ci, $val->expd, $val->gerencia_administrativa, $val->departamento_administrativo, $val->cargo,
                    $val->relaborales_estado_descripcion, $fechaIng, $fechaIni, $fechaIncor, $fechaFin, $fechaBaja,
                    $val->gestion, $val->mes_nombre, $val->turno, $val->modalidad_marcacion,
                    $val->d1, $val->d2, $val->d3, $val->d4, $val->d5, $val->d6, $val->d7, $val->d8, $val->d9, $val->d10,
                    $val->d11, $val->d12, $val->d13, $val->d14, $val->d15, $val->d16, $val->d17, $val->d18, $val->d19, $val->d20,
                    $val->d21, $val->d22, $val->d23, $val->d24, $val->d25, $val->d26, $val->d27, $val->d28, $val->d29, $val->d20, $val->d31, $val->ultimo_dia
                );

            }
        }
        $this->converToCsv($arrResultado, "resumen.csv", ";");
    }

    /**
     * Función para la obtención del reporte resumen de horarios y marcaciones.
     */
    public function getresumebypostAction()
    {
        $this->view->disable();
        $gestion = $_POST["gestion"];
        $mesIni = $_POST["mes_ini"];
        $mesFin = $_POST["mes_fin"];
        $ids = $_POST["ids"];
        if ($gestion > 0 && $mesIni > 0 && $mesFin > 0 && $ids != '') {
            $obj = new Fhorariosymarcaciones();
            $arrIds = explode(",", $ids);
            if (count($arrIds) > 0) {
                $arrIdRelaborales = array();
                for ($i = 0; $i < count($arrIds); $i++) {
                    $arrIdRelaborales[$i] = intval($arrIds[$i]);
                }
                $idRelaboralesJson = json_encode($arrIdRelaborales, JSON_FORCE_OBJECT);
            }
            $res = $obj->obtenerResumen($idRelaboralesJson, $gestion, $mesIni, $mesFin);
            $nro = 0;
            $idRelaboral = 0;
            $arrResultado[] = array("Nro.", "Nombres", "CI", "Expd", "Gerencia", "Departamento", "Cargo", "Estado",
                "Fecha Ing", "Fecha Ini", "Fecha Incor", "Fecha Fin", "Fecha Baja", "Gestion", "Mes", "Turno", "Modalidad",
                "Dia 1", "Dia 2", "Dia 3", "Dia 4", "Dia 5", "Dia 6", "Dia 7", "Dia 8", "Dia 9", "Dia 10",
                "Dia 11", "Dia 12", "Dia 13", "Dia 14", "Dia 15", "Dia 16", "Dia 17", "Dia 18", "Dia 19", "Dia 20",
                "Dia 21", "Dia 22", "Dia 23", "Dia 24", "Dia 25", "Dia 26", "Dia 27", "Dia 28", "Dia 29", "Dia 30", "Dia 31", "Ultimo Dia"
            );
            foreach ($res as $val) {
                if ($idRelaboral != $val->relaboral_id) {
                    $nro++;
                    $idRelaboral = $val->relaboral_id;
                }
                $fechaIng = $val->fecha_ing != "" ? date("Y-m-d", strtotime($val->fecha_ing)) : "";
                $fechaIni = $val->fecha_ini != "" ? date("Y-m-d", strtotime($val->fecha_ini)) : "";
                $fechaIncor = $val->fecha_incor != "" ? date("Y-m-d", strtotime($val->fecha_incor)) : "";
                $fechaFin = $val->fecha_fin != "" ? date("Y-m-d", strtotime($val->fecha_fin)) : "";
                $fechaBaja = $val->fecha_baja != "" ? date("Y-m-d", strtotime($val->fecha_baja)) : "";
                $arrResultado[] = array(
                    $nro, $val->nombres, $val->ci, $val->expd, $val->gerencia_administrativa, $val->departamento_administrativo, $val->cargo,
                    $val->relaborales_estado_descripcion, $fechaIng, $fechaIni, $fechaIncor, $fechaFin, $fechaBaja,
                    $val->gestion, $val->mes_nombre, $val->turno, $val->modalidad_marcacion,
                    $val->d1, $val->d2, $val->d3, $val->d4, $val->d5, $val->d6, $val->d7, $val->d8, $val->d9, $val->d10,
                    $val->d11, $val->d12, $val->d13, $val->d14, $val->d15, $val->d16, $val->d17, $val->d18, $val->d19, $val->d20,
                    $val->d21, $val->d22, $val->d23, $val->d24, $val->d25, $val->d26, $val->d27, $val->d28, $val->d29, $val->d30, $val->d31, $val->ultimo_dia
                );

            }
        }
        echo json_encode($arrResultado);
    }

    /**
     * Función para el vaciado de datos de registros de horario y marcación de acuerdo a un registro laboral, gestión, mes y clase de marcación.
     */
    public function vaciarAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $msj = array();
        $idRelaboral = (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0) ? $_POST["id_relaboral"] : 0;
        $gestion = (isset($_POST["gestion"]) && $_POST["gestion"] > 0) ? $_POST["gestion"] : 0;
        $mes = (isset($_POST["mes"]) && $_POST["mes"] > 0) ? $_POST["mes"] : 0;
        $clasemarcacion = (isset($_POST["clasemarcacion"])) ? $_POST["clasemarcacion"] : 0;
        if ($idUsuario > 0 && $idRelaboral > 0 && $gestion > 0 && $mes > 0 && $clasemarcacion != "") {
            $objHyM = new Horariosymarcaciones();
            $ok = $objHyM->vaciarRegistro($idUsuario, $idRelaboral, $gestion, $mes, $clasemarcacion);
            if ($ok) {
                $msj = array("result" => 1, "msj" => "&Eacute;xito: Registro vaciado de modo satisfactorio.");
            } else {
                $msj = array("result" => 0, "msj" => "Error: No se pudo vaciar los registros asociados de horarios y marcaciones.");
            }
        } else {
            $msj = array("result" => -1, "msj" => "Error cr&iacute;tico: No se pudo vaciar los registros asociados de horarios y marcaciones debido a datos incompletos.");
        }
        echo json_encode($msj);
    }
}
