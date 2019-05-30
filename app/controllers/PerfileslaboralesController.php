<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  12-12-2014
*/

class PerfileslaboralesController extends ControllerBase
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

        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);


        $this->assets->addJs('/js/jquery.kolorpicker.js?v=' . $version);
        $this->assets->addCss('/assets/css/kolorpicker.css?v=' . $version);

        $this->assets->addJs('/js/slider/bootstrap-slider.js?v=' . $version);
        $this->assets->addCss('/js/slider/bootstrap-slider.css?v=' . $version);

        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $version);

        $this->assets->addJs('/js/enscroll/enscroll.js?v=' . $version);


        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.tab.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.index.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.approve.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.new.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.edit.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.down.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.turn.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.calendar.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.calendar.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/perfileslaborales/oasis.perfileslaborales.cupos.js?v=' . $version);
    }

    /**
     * Función para la carga del primer listado sobre la página de gestión de perfiles laborales.
     * Se inhabilita la vista para el uso de jqwidgets,
     */
    public function listAction()
    {
        $this->view->disable();
        $perfillaboral = Array();
        $resul = Perfileslaborales::find(array('baja_logica=:baja_logica1: AND estado>=:estado1:', 'bind' => array('baja_logica1' => 1, 'estado1' => 1), 'order' => 'perfil_laboral,grupo'));
        $permisoC = true;
        $permisoR = true;
        $permisoU = true;
        $permisoD = true;
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $chk = '';
                $new = '';
                $edit = '';
                $down = '';
                $view = '';
                $chk = '<input type="checkbox" id="chk_' . $v->id . '">';
                // Se evalua el permiso de creación de nuevo registro
                if ($permisoC) {
                    $new = '<input type="button" id="btn_new_' . $v->id . '" value=Nuevo class=btn_new>';

                }
                //Se evalua el permiso de edición de registro
                if ($permisoU) {
                    if ($v->estado == 2) {
                        $edit = '<input type="button" id="btn_edit_' . $v->id . '" value=Editar class=btn_edit>';
                    }
                }
                //Se evalua
                $aprobar = '<input type="button" id="btn_appr_' . $v->id . '" value="Aprobar" class="btn_approve">';
                $down = '<input type="button" id="btn_del_' . $v->id . '" value="Baja" class="btn_del">';
                $view = '<input type="button" id="btn_view_' . $v->id . '" value="Ver" class="btn_view">';
                $estado_descripcion = '';
                switch ($v->estado) {
                    case 0:
                        $estado_descripcion = 'PASIVO';
                        break;
                    case 1:
                        $estado_descripcion = 'ACTIVO';
                        break;
                    case 2:
                        $estado_descripcion = 'EN PROCESO';
                        break;
                }
                switch ($v->tipo_horario) {
                    case 1:
                        $tipo_horario_descripcion = 'DISCONTINUO (LUN A VIE)';
                        break;
                    case 2:
                        $tipo_horario_descripcion = 'CONTINUO (LUN A VIE)';
                        break;
                    case 3:
                        $tipo_horario_descripcion = 'MULTIPLE (LUN A DOM)';
                        break;
                }
                $perfillaboral[] = array(
                    'chk' => $chk,
                    'nro_row' => 0,
                    'nuevo' => $new,
                    'aprobar' => $aprobar,
                    'editar' => $edit,
                    'eliminar' => $down,
                    'ver' => $view,
                    'id' => $v->id,
                    'perfil_laboral' => $v->perfil_laboral,
                    'grupo' => $v->grupo,
                    'tipo_horario' => $v->tipo_horario,
                    'tipo_horario_descripcion' => $tipo_horario_descripcion,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $estado_descripcion,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod

                );
            }
        }
        echo json_encode($perfillaboral);
    }

    /**
     * Función para la obtención del listado de perfiles disponibles para su asignación correspondiente.
     */
    public function listactivosAction()
    {
        $this->view->disable();
        $perfillaboral = Array();
        $resul = Perfileslaborales::find(array('estado=:estado1: AND baja_logica=:baja_logica1:', 'bind' => array('baja_logica1' => 1, 'estado1' => 1), 'order' => 'perfil_laboral'));
        $permisoC = true;
        $permisoR = true;
        $permisoU = true;
        $permisoD = true;
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $chk = '';
                $new = '';
                $edit = '';
                $down = '';
                $view = '';
                $chk = '<input type="checkbox" id="chk_' . $v->id . '">';
                // Se evalua el permiso de creación de nuevo registro
                if ($permisoC) {
                    $new = '<input type="button" id="btn_new_' . $v->id . '" value=Nuevo class=btn_new>';

                }
                //Se evalua el permiso de edición de registro
                if ($permisoU) {
                    if ($v->estado == 2) {
                        $edit = '<input type="button" id="btn_edit_' . $v->id . '" value=Editar class=btn_edit>';
                    }
                }
                //Se evalua
                $aprobar = '<input type="button" id="btn_appr_' . $v->id . '" value="Aprobar" class="btn_approve">';
                $down = '<input type="button" id="btn_del_' . $v->id . '" value="Baja" class="btn_del">';
                $view = '<input type="button" id="btn_view_' . $v->id . '" value="Ver" class="btn_view">';
                $estado_descripcion = '';
                switch ($v->estado) {
                    case 0:
                        $estado_descripcion = 'PASIVO';
                        break;
                    case 1:
                        $estado_descripcion = 'ACTIVO';
                        break;
                    case 2:
                        $estado_descripcion = 'EN PROCESO';
                        break;
                }
                switch ($v->tipo_horario) {
                    case 1:
                        $tipo_horario_descripcion = 'DISCONTINUO (LUN A VIE)';
                        break;
                    case 2:
                        $tipo_horario_descripcion = 'CONTINUO (LUN A VIE)';
                        break;
                    case 3:
                        $tipo_horario_descripcion = 'MULTIPLE (LUN A DOM)';
                        break;
                }
                $perfillaboral[] = array(
                    'chk' => $chk,
                    'nro_row' => 0,
                    'nuevo' => $new,
                    'aprobar' => $aprobar,
                    'editar' => $edit,
                    'eliminar' => $down,
                    'ver' => $view,
                    'id' => $v->id,
                    'perfil_laboral' => $v->perfil_laboral,
                    'grupo' => $v->grupo,
                    'tipo_horario' => $v->tipo_horario,
                    'tipo_horario_descripcion' => $tipo_horario_descripcion,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $estado_descripcion,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod

                );
            }
        }
        echo json_encode($perfillaboral);
    }

    /**
     * Función para la obtención del listado de tipos de horarios disponibles en el sistema.
     */
    public function listtiposhorariosAction()
    {
        $this->view->disable();
        /**
         * Se cambió de minúsculas a mayúsculas.
         */
        $resul = Parametros::find(array("parametro LIKE 'TIPOS_HORARIOS'"));
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tiposHorarios[] = array(
                    'tipo_horario' => $v->nivel,
                    'tipo_horario_descripcion' => $v->valor_1
                );
            }
        } else $tiposHorarios = array();
        echo json_encode($tiposHorarios);
    }

    /**
     * Función para la obtención de la fecha de inicio próxima en consideración a la última fecha registrada en el calendario.
     */
    public function getfechainiproximoAction()
    {
        $this->view->disable();
        $fechaIniProximo = [];
        if (isset($_POST["id"])) {
            $objPerfil = new Perfileslaborales();
            $resul = $objPerfil->getPrimerDiaSiguienteMesParaCalendario($_POST["id"]);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    if ($v->o_gestion > 0) {
                        $fechaIniProximo[] = array(
                            'dia' => $v->o_dia,
                            'mes' => $v->o_mes,
                            'gestion' => $v->o_gestion
                        );
                    }
                }
            }
        }
        echo json_encode($fechaIniProximo);
    }

    /**
     * Función para la obtención de la última fecha de un determinado mes en una determinada gestión.
     */
    public function getultimafechamesAction()
    {
        $this->view->disable();
        $fechaResultado = "";
        if (isset($_POST["fecha"])) {
            $fecha = new DateTime($_POST["fecha"]);
            $fecha->modify('last day of this month');
            $fechaResultado = $fecha->format('d-m-Y');
        }
        echo $fechaResultado;
    }

    /**
     * Función para sumar una determinada cantidad de días a una fecha
     */
    public function getfechamasdiasAction()
    {
        $this->view->disable();
        $fechaResultado = "";
        if (isset($_POST["fecha"]) && isset($_POST["dias"])) {
            $dias = $_POST["dias"];
            $fecha = new DateTime($_POST["fecha"]);
            $fecha->add(new DateInterval("P" . $dias . "D"));
            $fechaResultado = $fecha->format("d-m-Y");
        }
        echo $fechaResultado;
    }

    /**
     * Función para restar una determinada cantidad de días a una fecha
     */
    public function getfechamenosdiasAction()
    {
        $this->view->disable();
        $fechaResultado = "";
        if (isset($_POST["fecha"]) && isset($_POST["dias"])) {
            $dias = $_POST["dias"];
            $fecha = new DateTime($_POST["fecha"]);
            $fecha->sub(new DateInterval("P" . $dias . "D"));
            $fechaResultado = $fecha->format("d-m-Y");
        }
        echo $fechaResultado;
    }

    /**
     * Función para la determinación de si las fechas enviadas están dentro del parámetro.
     * @return 1:Si esta; 0: No esta
     */
    function checkinrangeAction()
    {
        $result = 0;
        $this->view->disable();
        if (isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"]) && isset($_POST["fecha_eval"])) {
            $fecha_ini = $_POST["fecha_ini"];
            $fecha_fin = $_POST["fecha_fin"];
            $fecha_eval = $_POST["fecha_eval"];
            $start_ts = strtotime($fecha_ini);
            $end_ts = strtotime($fecha_fin);
            $user_ts = strtotime($fecha_eval);
            $result = (($user_ts >= $start_ts) && ($user_ts <= $end_ts)) == true ? 1 : 0;
        }
        echo $result;
    }

    /**
     * Función para el almacenamiento y actualización de un registro de perfil laboral.
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
        $user_mod_id = $auth['id'];
        $user_reg_id = $auth['id'];
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $fecha_fin = "31/12/" . $gestion_actual;
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edición de registro
             */
            $objPerfilLaboral = Perfileslaborales::findFirstById($_POST["id"]);
            $perfil_laboral = $_POST['perfil_laboral'];
            $grupo = $_POST['grupo'];
            $tipo_horario = $_POST['tipo_horario'];
            $controlFaltasOmision = 1;
            if (isset($_POST['control_f_o'])) {
                $controlFaltasOmision = $_POST['control_f_o'];
            }
            $observacion = $_POST['observacion'];
            $resul = Perfileslaborales::find(array("UPPER(perfil_laboral) LIKE UPPER('" . $perfil_laboral . "') AND (UPPER(grupo) LIKE UPPER('" . $grupo . "') OR (grupo is null and '" . $grupo . "' like '')) AND id!=" . $objPerfilLaboral->id));
            if (count($resul) > 0) {
                $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el registro de debido a que ya existe otro registro con el mismo nombre de perfil y/o grupo.');
            } else {
                try {

                    $objPerfilLaboral->perfil_laboral = $perfil_laboral;
                    $objPerfilLaboral->grupo = $grupo;
                    $objPerfilLaboral->tipo_horario = $tipo_horario;
                    $objPerfilLaboral->observacion = ($observacion == "") ? null : $observacion;
                    $objPerfilLaboral->baja_logica = 1;
                    $objPerfilLaboral->agrupador = $controlFaltasOmision;
                    $objPerfilLaboral->user_mod_id = $user_mod_id;
                    $objPerfilLaboral->fecha_mod = $hoy;
                    $ok = $objPerfilLaboral->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente.');
                    } else {
                        foreach ($objPerfilLaboral->getMessages() as $message) {
                            echo $message, "\n";
                        }
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de perfil laboral.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de perfil laboral.');
                }
            }
        } else {
            /**
             * Nuevo Registro
             */
            if (isset($_POST['perfil_laboral'])) {
                $perfil_laboral = $_POST['perfil_laboral'];
                $grupo = $_POST['grupo'] == '' ? '' : $_POST["grupo"];
                $tipo_horario = $_POST['tipo_horario'];
                $controlFaltasOmision = 1;
                if (isset($_POST['control_f_o'])) {
                    $controlFaltasOmision = $_POST['control_f_o'];
                }
                $observacion = $_POST['observacion'];
                if ($perfil_laboral != '') {
                    try {
                        $resul = Perfileslaborales::find(array("UPPER(perfil_laboral) LIKE UPPER('" . $perfil_laboral . "') AND (UPPER(grupo) LIKE UPPER('" . $grupo . "') OR (grupo is null and '" . $grupo . "' like ''))"));
                        if ($resul->count() > 0) {
                            $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el registro de perfil laboral debido a que ya existe un perfil con el mismo nombre y/o denominaci&oacute;n de grupo.');
                        } else {
                            $objPerfilLaboral = new Perfileslaborales();
                            $objPerfilLaboral->id = null;
                            $objPerfilLaboral->perfil_laboral = $perfil_laboral;
                            if ($grupo != '') $objPerfilLaboral->grupo = $grupo;
                            $objPerfilLaboral->tipo_horario = $tipo_horario;
                            $objPerfilLaboral->observacion = ($observacion == "") ? null : $observacion;
                            $objPerfilLaboral->estado = 2;
                            $objPerfilLaboral->baja_logica = 1;
                            $objPerfilLaboral->agrupador = $controlFaltasOmision;
                            $objPerfilLaboral->user_reg_id = $user_reg_id;
                            $objPerfilLaboral->fecha_reg = $hoy;
                            $ok = $objPerfilLaboral->save();
                            if ($ok) {
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                            } else {
                                foreach ($objPerfilLaboral->getMessages() as $message) {
                                    echo $message, "\n";
                                }
                                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de perfil laboral.');
                            }
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de perfil laboral.');
                    }
                } else {
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos del perfil laboral.');
                }
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos en el perfil laboral.');
            }
        }
        echo json_encode($msj);
    }

    /*
     * Función para la aprobación del registro de perfil laboral que se encontraba en estado EN PROCESO.
     */
    public function approveAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $user_reg_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Aprobación de registro
             */
            $objPerfilLaboral = Perfileslaborales::findFirstById($_POST["id"]);
            if ($objPerfilLaboral->id > 0 && $objPerfilLaboral->estado == 2) {
                try {
                    $objPerfilLaboral->estado = 1;
                    $objPerfilLaboral->user_mod_id = $user_mod_id;
                    $objPerfilLaboral->fecha_mod = $hoy;
                    $ok = $objPerfilLaboral->save();
                    if ($ok) {
                        //$this->adjudicarCargo($objRelaboral->cargo_id,$user_mod_id);
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se aprob&oacute; correctamente el registro de perfil laboral.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se aprob&oacute; el registro de perfil laboral.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de perfil laboral.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro de perfil laboral no cumple con el requisito establecido para su aprobaci&oacute;n, debe estar en estado EN PROCESO.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro de perfil laboral.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para el la baja del registro de una perfil laboral..
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
                $objPerfilLaboral = Perfileslaborales::findFirst(array("id=" . $_POST["id"]));
                if(is_object($objPerfilLaboral)){
                    $objRelaboralesPerfiles = Relaboralesperfiles::find(array("perfillaboral_id = ".$_POST["id"]." AND baja_logica=1"));
                    if(count($objRelaboralesPerfiles)==0){
                        $objPerfilLaboral->estado = 0;
                        $objPerfilLaboral->user_mod_id = $user_mod_id;
                        $objPerfilLaboral->fecha_mod = $hoy;
                        if ($objPerfilLaboral->save()) {
                            /**
                             * Se modifica el estado del cargo a desadjudicado a objeto de permitir su uso.
                             */
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                        } else {
                            foreach ($objPerfilLaboral->getMessages() as $message) {
                                echo $message, "\n";
                            }
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; la baja del registro de perfil laboral.');
                        }
                    } else{
                        $msj = array('result' => -1, 'msj' => 'Error: No se puede dar de baja el registro debido a que actualmente tiene '.count($objRelaboralesPerfiles).' asignaciones laborales activas.');
                    }
                }else{
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se encontr&oacute; el registro correspondiente para dar de baja.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; la baja del registro de perfil laboral debido a que no se especific&oacute; el registro de perfil laboral.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de perfil laboral.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención de los datos referentes a una persona en especifico.
     */
    public function personascontactoAction()
    {
        $personas = Array();
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                $id_persona = $_POST["id"];
                $obj = new Fpersonas();
                $objPersona = $obj->getOne($id_persona);
                //findFirst("id = 1");
                if ($objPersona->count() > 0) {
                    foreach ($objPersona as $v) {
                        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                        $date = new DateTime($v->fecha_nac);
                        $fecha_nac = $date->format('d') . "-" . $meses[$date->format('m') - 1] . "-" . $date->format('Y');
                        $personas[] = array(
                            'id_persona' => $v->id_persona,
                            'postulante_id' => $v->postulante_id,
                            'p_nombre' => $v->p_nombre,
                            's_nombre' => $v->s_nombre,
                            't_nombre' => $v->t_nombre,
                            'p_apellido' => $v->p_apellido,
                            's_apellido' => $v->s_apellido,
                            'c_apellido' => $v->c_apellido,
                            'tipo_documento' => $v->tipo_documento,
                            'ci' => $v->ci,
                            'expd' => $v->expd,
                            'fecha_caducidad' => $v->fecha_caducidad,
                            'num_complemento' => '',
                            'fecha_nac' => $fecha_nac,
                            'edad' => $v->edad,
                            'lugar_nac' => $v->lugar_nac,
                            'genero' => $v->genero,
                            'e_civil' => $v->e_civil,
                            'codigo' => $v->codigo,
                            'nacionalidad' => $v->nacionalidad,
                            'nit' => $v->nit,
                            'num_func_sigma' => $v->num_func_sigma,
                            'grupo_sanguineo' => $v->grupo_sanguineo,
                            'num_lib_ser_militar' => $v->num_lib_ser_militar,
                            'num_reg_profesional' => $v->num_reg_profesional,
                            'observacion' => $v->observacion,
                            'estado' => $v->estado,
                            'baja_logica' => $v->baja_logica,
                            'agrupador' => $v->agrupador,
                            'user_reg_id' => $v->user_reg_id,
                            'fecha_reg' => $v->fecha_reg,
                            'user_mod_id' => $v->user_mod_id,
                            'fecha_mod' => $v->fecha_mod,
                            'direccion_dom' => ($v->direccion_dom != null) ? $v->direccion_dom : '',
                            'telefono_fijo' => ($v->telefono_fijo != null) ? $v->telefono_fijo : '',
                            'telefono_inst' => ($v->telefono_inst != null) ? $v->telefono_inst : '',
                            'telefono_fax' => ($v->telefono_fax != null) ? $v->telefono_fax : '',
                            'interno_inst' => ($v->interno_inst != null) ? $v->interno_inst : '',
                            'celular_per' => ($v->celular_per != null) ? $v->celular_per : '',
                            'celular_inst' => ($v->celular_inst != null) ? $v->celular_inst : '',
                            'num_credencial' => ($v->num_credencial != null) ? $v->num_credencial : '',
                            'ac_no' => ($v->ac_no != null) ? $v->ac_no : '',
                            'e_mail_per' => ($v->e_mail_per != null) ? $v->e_mail_per : '',
                            'e_mail_inst' => ($v->e_mail_inst != null) ? $v->e_mail_inst : '',
                            'contacto_observacion' => ($v->contacto_observacion != null) ? $v->contacto_observacion : '',
                            'contacto_estado' => ($v->contacto_estado != null) ? $v->contacto_estado : ''
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
        echo json_encode($personas);
    }

    /*
     * Función para la obtención de la fotografía de la persona.
     * @param $ci Número de carnet de identidad
     * @param $num_complemento Número complemento
     */
    function obtenerrutafotoAction()
    {
        $this->view->disable();
        $msj = Array();
        $ruta = "";
        $rutaImagenesCredenciales = "/images/personal/";
        $extencionImagenesCredenciales = ".jpg";
        $num_complemento = "";
        if (isset($_POST["num_complemento"])) {
            $num_complemento = $_POST["num_complemento"];
        }
        try {
            if (isset($_POST["ci"])) {
                $ruta = "";
                $nombreImagenArchivo = $rutaImagenesCredenciales . trim($_POST["ci"]);
                if ($num_complemento != "") $nombreImagenArchivo = $nombreImagenArchivo . trim($num_complemento);
                $ruta = $nombreImagenArchivo . $extencionImagenesCredenciales;
                /**
                 * Se verifica la existencia del archivo
                 */
                if (file_exists(getcwd() . $ruta))
                    $msj = array('result' => 1, 'ruta' => $ruta, 'msj' => 'Resultado exitoso.');
                else $msj = array('result' => 0, 'ruta' => '/images/perfil-profesional.jpg', 'msj' => 'No se encontr&oacute; la fotograf&iacute;a. ' . $ruta);
            } else $msj = array('result' => 0, 'ruta' => '', 'msj' => 'No se envi&oacute; n&uacute;mero de documento.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'ruta' => $ruta, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.');
        }
        echo json_encode($msj);
    }

    /*
     *  Función para la obtención de las gestiones en las cuales
     */
    function listgestionesporpersonaAction()
    {
        $gestiones = Array();
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                $obj = new Relaborales();
                $arr = $obj->getCol($_POST["id"]);
                foreach ($arr as $clave => $valor) {
                    $gestiones[] = $valor;
                }
            }
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            //$msj = array('result' => -1, 'ruta'=>$ruta, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de relaci&oacute;n laboral.');
        }
        echo json_encode($gestiones);
    }

    /**
     * Función para la carga del historial de movilidad funcionaria.
     */
    public function listhistorialturnosAction()
    {
        $this->view->disable();
        $turnoslaborales = Array();
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            $idPerfilLaboral = $_GET["id"];
            $obj = new Fturnoslaborales();
            $resul = $obj->getAllByOne($_GET["id"]);
            $tipo_horario_descripcion = "";
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $turnoslaborales[] = array(
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'grupo' => $v->grupo,
                        'gestion' => $v->gestion,
                        'numero_mes' => $v->numero_mes,
                        'mes' => $v->mes,
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'tipo_horario' => $v->tipo_horario,
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'id_tolerancia' => $v->id_tolerancia,
                        'tipo_tolerancia' => $v->tipo_tolerancia,
                        'id_jornada_laboral' => $v->id_jornada_laboral,
                        'jornada_laboral' => $v->jornada_laboral
                    );
                }
            }
        }
        echo json_encode($turnoslaborales);
    }

    /**
     * Función para la obtención del listado de áreas administrativas disponibles de acuerdo a un identificador de organigrama.
     * En caso de que dicho valor sea nulo o cero se devolverán todas las areas disponibles en el organigrama.
     */
    public function listareasAction()
    {
        $organigramas = Array();
        $this->view->disable();
        if (isset($_POST["id_padre"]) && $_POST["id_padre"] >= 0) {
            $obj = new Organigramas();
            $resul = $obj->getAreas($_POST["id_padre"]);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $organigramas[] = array(
                        'id_area' => $v->id_area,
                        'padre_id' => $v->padre_id,
                        'gestion' => $v->gestion,
                        'da_id' => $v->da_id,
                        'regional_id' => $v->regional_id,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'nivel_estructural_id' => $v->nivel_estructural_id,
                        'sigla' => $v->sigla,
                        'fecha_ini' => $v->fecha_ini,
                        'fecha_fin' => $v->fecha_fin,
                        'codigo' => $v->codigo,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'baja_logica' => $v->baja_logica,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod,
                        'area_sustantiva' => $v->area_sustantiva
                    );
                }
            }
        }
        echo json_encode($organigramas);
    }

    /**
     * Función básica de impresión.
     * Su uso es con fines de verificación de funcionamiento simplemente.
     */
    public function printbasicAction()
    {
        $this->view->disable();
        $pdf = new pdfoasis();

        $pdf->AddPage();

        $miCabecera = array('Nro', 'Nombre', 'Apellido', 'Matrícula');

        $misDatos = array(
            array('nombre' => 'Hugo', 'apellido' => 'Martínez', 'matricula' => '20420423'),
            array('nombre' => 'Araceli', 'apellido' => 'Morales', 'matricula' => '204909'),
            array('nombre' => 'Georgina', 'apellido' => 'Galindo', 'matricula' => '2043442'),
            array('nombre' => 'Luis', 'apellido' => 'Dolores', 'matricula' => '20411122'),
            array('nombre' => 'Mario', 'apellido' => 'Linares', 'matricula' => '2049990'),
            array('nombre' => 'Viridiana', 'apellido' => 'Badillo', 'matricula' => '20418855'),
            array('nombre' => 'Yadira', 'apellido' => 'García', 'matricula' => '20443335')
        );

        $pdf->tablaHorizontal($miCabecera, $misDatos);

        $pdf->Output(); //Salida al navegador
    }

    /**
     * Función para la obtención del reporte de relaciones laborales en formato PDF.
     * @param $n_rows Cantidad de lineas
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds
     */
    public function exportpdfAction($n_rows, $columns, $filtros, $groups, $sorteds)
    {   $this->view->disable();
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
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'observacion' => array('title' => 'Observacion', 'width' => 20, 'align' => 'L', 'type' => 'varchar')
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
            $pdf->title_rpt = utf8_decode('Reporte Relación Laboral');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
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
            $obj = new Frelaborales();
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
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
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
            $resul = $obj->getAllWithPersonsOneRecord($where, $groups);

            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
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
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac,
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'item' => $v->item,
                    'carrera_amd' => $v->carrera_amd,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
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
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod
                );
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

            if (count($relaboral) > 0) {
                foreach ($relaboral as $i => $val) {
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
                        $rowData = $pdf->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
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
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_relaboral.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }

    /**
     * Función para la exportación del reporte en formato Excel.
     * @param $n_rows Cantidad de lineas
     * @param $columns Array con las columnas mostradas en el reporte
     * @param $filtros Array con los filtros aplicados sobre las columnas.
     * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
     * @param $sorteds  Columnas ordenadas .
     */
    public function exportexcelAction($n_rows, $columns, $filtros, $groups, $sorteds)
    {   $this->view->disable();
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
            'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
            'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
            'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
            'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
            'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
            'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
            'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
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
            $excel->title_rpt = utf8_decode('Reporte Relación Laboral');
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
            $obj = new Frelaborales();
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
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
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
            $resul = $obj->getAllWithPersonsOneRecord($where, $groups);

            $relaboral = array();
            foreach ($resul as $v) {
                $relaboral[] = array(
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
                    'fecha_caducidad' => $v->fecha_caducidad,
                    'num_complemento' => '',
                    'fecha_nac' => $v->fecha_nac,
                    'edad' => $v->edad,
                    'lugar_nac' => $v->lugar_nac,
                    'genero' => $v->genero,
                    'e_civil' => $v->e_civil,
                    'item' => $v->item,
                    'carrera_amd' => $v->carrera_amd,
                    'num_contrato' => $v->num_contrato,
                    'contrato_numerador_estado' => $v->contrato_numerador_estado,
                    'id_solelabcontrato' => $v->id_solelabcontrato,
                    'solelabcontrato_regional_sigla' => $v->solelabcontrato_regional_sigla,
                    'solelabcontrato_numero' => $v->solelabcontrato_numero,
                    'solelabcontrato_gestion' => $v->solelabcontrato_gestion,
                    'solelabcontrato_codigo' => $v->solelabcontrato_codigo,
                    'solelabcontrato_user_reg_id' => $v->solelabcontrato_user_reg_id,
                    'solelabcontrato_fecha_sol' => $v->solelabcontrato_fecha_sol,
                    'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                    'fecha_incor' => $v->fecha_incor != "" ? date("d-m-Y", strtotime($v->fecha_incor)) : "",
                    'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                    'fecha_baja' => $v->fecha_baja != "" ? date("d-m-Y", strtotime($v->fecha_baja)) : "",
                    'fecha_ren' => $v->fecha_ren != "" ? date("d-m-Y", strtotime($v->fecha_ren)) : "",
                    'fecha_acepta_ren' => $v->fecha_acepta_ren != "" ? date("d-m-Y", strtotime($v->fecha_acepta_ren)) : "",
                    'fecha_agra_serv' => $v->fecha_agra_serv != "" ? date("d-m-Y", strtotime($v->fecha_agra_serv)) : "",
                    'motivo_baja' => $v->motivo_baja,
                    'motivosbajas_abreviacion' => $v->motivosbajas_abreviacion,
                    'descripcion_baja' => $v->descripcion_baja,
                    'descripcion_anu' => $v->descripcion_anu,
                    'id_cargo' => $v->id_cargo,
                    'cargo_codigo' => $v->cargo_codigo,
                    'cargo' => $v->cargo,
                    'id_nivelessalarial' => $v->id_nivelessalarial,
                    'nivelsalarial' => $v->nivelsalarial,
                    'nivelsalarial_resolucion_id' => $v->nivelsalarial_resolucion_id,
                    'numero_escala' => $v->numero_escala,
                    'gestion_escala' => $v->gestion_escala,
                    //'sueldo' => $v->sueldo,
                    'sueldo' => str_replace(".00", "", $v->sueldo),
                    'id_procesocontratacion' => $v->id_procesocontratacion,
                    'proceso_codigo' => $v->proceso_codigo,
                    'id_convocatoria' => $v->id_convocatoria,
                    'convocatoria_codigo' => $v->convocatoria_codigo,
                    'convocatoria_tipo' => $v->convocatoria_tipo,
                    'id_fin_partida' => $v->id_fin_partida,
                    'fin_partida' => $v->fin_partida,
                    'id_condicion' => $v->id_condicion,
                    'condicion' => $v->condicion,
                    'categoria_relaboral' => $v->categoria_relaboral,
                    'id_da' => $v->id_da,
                    'direccion_administrativa' => $v->direccion_administrativa,
                    'organigrama_regional_id' => $v->organigrama_regional_id,
                    'organigrama_regional' => $v->organigrama_regional,
                    'id_regional' => $v->id_regional,
                    'regional' => $v->regional,
                    'regional_codigo' => $v->regional_codigo,
                    'id_departamento' => $v->id_departamento,
                    'departamento' => $v->departamento,
                    'id_provincia' => $v->id_provincia,
                    'provincia' => $v->provincia,
                    'id_localidad' => $v->id_localidad,
                    'localidad' => $v->localidad,
                    'residencia' => $v->residencia,
                    'unidad_ejecutora' => $v->unidad_ejecutora,
                    'cod_ue' => $v->cod_ue,
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
                    'unidades_superiores' => $v->unidades_superiores,
                    'unidades_dependientes' => $v->unidades_dependientes,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo,
                    'observacion' => ($v->observacion != null) ? $v->observacion : "",
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'estado_abreviacion' => $v->estado_abreviacion,
                    'tiene_contrato_vigente' => $v->tiene_contrato_vigente,
                    'id_eventual' => $v->id_eventual,
                    'id_consultor' => $v->id_consultor,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'persona_user_reg_id' => $v->persona_user_reg_id,
                    'persona_fecha_reg' => $v->persona_fecha_reg,
                    'persona_user_mod_id' => $v->persona_user_mod_id,
                    'persona_fecha_mod' => $v->persona_fecha_mod
                );
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
            if (count($relaboral) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($relaboral);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($relaboral as $i => $val) {
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
                        $rowData = $excel->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
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
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
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
                $excel->display("reporte_relaboral.xls", "I");
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
     * Función para la obtención del listado de tipos de memorándum.
     */
    function listtiposmemorandumsmovilidadAction()
    {
        $this->view->disable();
        $resul = Tiposmemorandums::find(array('estado=:estado1: and movilidad=:movilidad1:', 'bind' => array('estado1' => 1, 'movilidad1' => 1), 'order' => 'id ASC'));
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tipomemorandum[] = array(
                    'id' => $v->id,
                    'id_agrupado' => $v->id . "-" . $v->fecha_fin . "-" . $v->hora_fin . "-" . $v->cargo . "-" . $v->organigrama . "-" . $v->ubicacion . "-" . $v->motivo . "-" . $v->pais . "-" . $v->ciudad . "-" . $v->lugar,
                    'tipo_memorandum' => $v->tipo_memorandum,
                    'cabecera' => $v->cabecera,
                    'abreviacion' => $v->abreviacion,
                    'fecha_fin' => $v->fecha_fin,
                    'hora_fin' => $v->hora_fin,
                    'cargo' => $v->cargo,
                    'organigrama' => $v->organigrama,
                    'ubicacion' => $v->ubicacion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'agrupador' => $v->agrupador
                );
            }
        }
        echo json_encode($tipomemorandum);
    }

    /**
     * Función para la obtención del listado de gestiones disponibles para la generación de memorándums.
     * Se muestra la gestión actual menos la gestión pasada.
     */
    function listgestionesmemorandumsAction()
    {
        $this->view->disable();
        $gestionActual = date("Y");
        $gestionPasada = $gestionActual - 1;
        $gestiones = array();
        for ($ges = $gestionActual; $ges >= $gestionPasada; $ges--) {
            $gestiones[] = array('gestion' => $ges);
        }
        echo json_encode($gestiones);
    }

    /**
     * Función para la obtención del listado de gerencias administrativas.
     */
    function listgerenciasAction()
    {
        $this->view->disable();
        $org = new Organigramas();
        $resul = $org->getGerencias();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $gerencias[] = array(
                    'id' => $v->id,
                    'padre_id' => $v->padre_id,
                    'gestion' => $v->gestion,
                    'da_id' => $v->da_id,
                    'regional_id' => $v->regional_id,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'nivel_estructural_id' => $v->nivel_estructural_id,
                    'sigla' => $v->sigla,
                    'fecha_ini' => $v->fecha_ini,
                    'fecha_fin' => $v->fecha_fin,
                    'orden' => $v->orden,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'area_sustantiva' => $v->area_sustantiva,
                    'visible' => $v->visible
                );
            }
        }
        echo json_encode($gerencias);
    }

    /**
     * Función para la obtención del listado de departamentos administrativos.
     * El nombre de la función se especifica así pues se prevé necesitarse el listado de los departamentos del país.
     */
    function listdepartamentosadministrativosAction()
    {
        $this->view->disable();
        $org = new Organigramas();
        $padre_id = $_GET["padre_id"];
        $resul = $org->getDepartamentosAdministrativosPorGerencia($padre_id);
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $gerencias[] = array(
                    'id' => $v->id,
                    'padre_id' => $v->padre_id,
                    'gestion' => $v->gestion,
                    'da_id' => $v->da_id,
                    'regional_id' => $v->regional_id,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'nivel_estructural_id' => $v->nivel_estructural_id,
                    'sigla' => $v->sigla,
                    'fecha_ini' => $v->fecha_ini,
                    'fecha_fin' => $v->fecha_fin,
                    'orden' => $v->orden,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'area_sustantiva' => $v->area_sustantiva,
                    'visible' => $v->visible
                );
            }
        }
        echo json_encode($gerencias);
    }

    /**
     * Función para la obtención del listado de áreas administrativas.
     */
    function listareasadministrativasAction()
    {
        $this->view->disable();
        $org = new Organigramas();
        $padre_id = $_GET["padre_id"];
        $resul = $org->getAreasAdministrativas($padre_id);
        //comprobamos si hay filas
        $areas = array();
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $areas[] = array(
                    'id' => $v->id,
                    'padre_id' => $v->padre_id,
                    'gestion' => $v->gestion,
                    'da_id' => $v->da_id,
                    'regional_id' => $v->regional_id,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'nivel_estructural_id' => $v->nivel_estructural_id,
                    'sigla' => $v->sigla,
                    'fecha_ini' => $v->fecha_ini,
                    'fecha_fin' => $v->fecha_fin,
                    'orden' => $v->orden,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,
                    'area_sustantiva' => $v->area_sustantiva,
                    'visible' => $v->visible
                );
            }
        }
        echo json_encode($areas);
    }

    /**
     * Función para el registro de una nueva movilidad funcionaria
     */
    public function savemovilidadAction()
    {
        $user_reg_id = 1;
        $user_mod_id = 1;
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $fecha_fin = "31/12/" . $gestion_actual;
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edición de registro
             */
            $id_relaboralmovilidad = $_POST["id"];
            $objRM = new Frelaboralesmovilidad();
            //$resultRelaboralMovilidad = $objRM->getOne($id_relaboralmovilidad);
            $objRelaboralMovilidad = Relaboralesmovilidades::findFirst(array("id=" . $id_relaboralmovilidad));
            if ($objRelaboralMovilidad != null && $objRelaboralMovilidad->id > 0) {

                if (isset($_POST['id_relaboral'])) {
                    $id_relaboral = $_POST['id_relaboral'];
                    $id_da = $_POST['id_da'];
                    $id_regional = $_POST['id_regional'];
                    $id_organigrama = $_POST['id_organigrama'];
                    $id_area = $_POST['id_area'];
                    $id_ubicacion = $_POST['id_ubicacion'];
                    $id_memorandum = $_POST['id_memorandum'];
                    if (isset($_POST['cargo']['value'])) {
                        $cargo = $_POST['cargo']['value'];
                    } elseif (isset($_POST['cargo'])) {
                        $cargo = $_POST['cargo'];
                    }
                    $id_evento = $_POST['id_evento'];
                    $motivo = $_POST['motivo'];
                    $id_pais = $_POST['id_pais'];
                    $id_departamento = $_POST['id_departamento'];
                    $lugar = $_POST['lugar'];
                    $id_tipomemorandum = $_POST['id_tipomemorandum'];
                    $correlativo = $_POST['correlativo'];
                    $gestion = $_POST['gestion'];
                    $datefm = new DateTime($_POST['fecha_mem']);
                    $fecha_mem = $datefm->format('Y-m-d');
                    $contenido_memorandum = $_POST['contenido'];
                    $datefi = new DateTime($_POST['fecha_ini']);
                    $fecha_ini = $datefi->format('Y-m-d');
                    /**
                     * Calculamos la fecha previa al inicio para uso posterior.
                     */
                    $datefiaux = new DateTime($_POST['fecha_ini']);
                    $datefiaux->format('Y-m-d');
                    $fecha_fin_aux = date('Y-m-d', strtotime('-1 days', strtotime($_POST['fecha_ini'])));

                    $hora_ini = $_POST['hora_ini'];
                    $dateff = new DateTime($_POST['fecha_fin']);
                    $fecha_fin = $dateff->format('Y-m-d');
                    $hora_fin = $_POST['hora_fin'];
                    $observacion = $_POST['observacion'];
                    $obj = new Frelaborales();
                    $resul = $obj->getOne($id_relaboral);
                    $frelaboral = $resul[0];
                    /**
                     * Una movilidad de personal sólo debería poderse registrar para registros de relación laboral ACTIVOS o EN PROCESO.
                     */
                    if ($frelaboral->id_relaboral > 0 && $frelaboral->estado >= 1) {
                        $memorandum = Memorandums::findFirst(array("id=" . $id_memorandum));
                        if ($memorandum != null && $memorandum->id > 0) {
                            $memorandum->relaboral_id = $frelaboral->id_relaboral;
                            $memorandum->finpartida_id = $frelaboral->id_fin_partida;
                            $memorandum->fecha_mem = $fecha_mem;
                            $memorandum->correlativo = $correlativo;
                            $memorandum->gestion = $gestion;
                            $memorandum->da_id = $frelaboral->id_da;
                            $memorandum->regional_id = $frelaboral->id_regional;
                            $memorandum->tipomemorandum_id = $id_tipomemorandum;
                            $memorandum->estado = 1;
                            $memorandum->baja_logica = 1;
                            $memorandum->agrupador = 0;
                            $memorandum->user_mod_id = $user_mod_id;
                            $memorandum->fecha_mod = $hoy;
                            try {
                                if ($memorandum->save()) {
                                    $objRelaboralMovilidad->relaboral_id = $frelaboral->id_relaboral;
                                    $objRelaboralMovilidad->da_id = $frelaboral->id_da;
                                    $objRelaboralMovilidad->regional_id = $frelaboral->id_regional;

                                    $modalidadmemorandum = Modalidadmemorandum::findFirst(array("tipomemorandum_id=" . $id_tipomemorandum));
                                    if ($modalidadmemorandum->id > 0) {
                                        $objRelaboralMovilidad->modalidadmemorandum_id = $modalidadmemorandum->id;
                                    }
                                    $objRelaboralMovilidad->memorandum_id = $memorandum->id;

                                    /**
                                     * Obtener la cantidad de memos registrados + 1
                                     */
                                    /*
                                    $objRel = Relaboralesmovilidades::find(array("baja_logica=1 AND relaboral_id=".$id_relaboral));
                                    $objRelaboralMovilidad->numero = $objRel->count()+1;*/

                                    if ($cargo != '') {
                                        $objRelaboralMovilidad->cargo = $cargo;
                                    }
                                    $objRelaboralMovilidad->fecha_ini = $fecha_ini;
                                    if ($hora_ini != '') {
                                        $objRelaboralMovilidad->hora_ini = $hora_ini;
                                    }
                                    #region Evaluación de la obligatoriedad de registro de algunos datos
                                    $tipomemorandum = Tiposmemorandums::findFirst(array("id=" . $id_tipomemorandum));
                                    if ($tipomemorandum->fecha_fin >= 1) {
                                        $objRelaboralMovilidad->fecha_fin = $fecha_fin;
                                        /**
                                         * Para el control de la hora es necesario que si o si se registre la fecha de finalización
                                         */
                                        if ($hora_fin != '') $objRelaboralMovilidad->hora_fin = $hora_fin;
                                    } else {
                                        /**
                                         * Evaluar la necesidad de registro de la fecha de finalización del registro de relación laboral como fecha de finalización de la movilidad.
                                         */
                                    }

                                    if ($tipomemorandum->cargo >= 1 && $cargo != '') {
                                        $objRelaboralMovilidad->cargo = $cargo;
                                    }

                                    if ($tipomemorandum->motivo >= 1 && $motivo != '') {
                                        $objRelaboralMovilidad->motivo = $motivo;
                                    }

                                    if ($tipomemorandum->pais >= 1 && $id_pais > 0) {
                                        $objRelaboralMovilidad->pais_id = $id_pais;
                                    }

                                    if ($tipomemorandum->ciudad >= 1 && $id_departamento > 0) {
                                        $objRelaboralMovilidad->departamento_id = $id_departamento;
                                    }

                                    if ($tipomemorandum->lugar >= 1 && $lugar != '') {
                                        $objRelaboralMovilidad->lugar = $lugar;
                                    }
                                    /**
                                     * Es necesario verificar si se ha especificado un valor para el organigrama,
                                     * si no es así se registra el correspondiente al registro de relación laboral.
                                     */
                                    if ($tipomemorandum->organigrama >= 1 && $id_organigrama > 0) {
                                        $objRelaboralMovilidad->organigrama_id = $id_organigrama;
                                        /**
                                         * Verificando el identificador del área enviada
                                         */
                                        if ($id_area > 0) {
                                            /**
                                             * Se evalua si el identificador del área enviada corresponde validamente al identificador del organigrama enviado.
                                             */
                                            $org = new Organigramas();
                                            $okArea = $org->verificarCorrectaCorrespondeciaArea($id_organigrama, $id_area);
                                            if ($okArea != null && $okArea > 0) {
                                                $objRelaboralMovilidad->area_id = $id_area;
                                            }
                                        }
                                    }
                                    if ($tipomemorandum->ubicacion >= 1) {
                                        //$relaboralesmovilidades->ubicacion_id = $id_ubicacion;

                                        /**
                                         * Verificando el identificador de la ubicación enviada
                                         */
                                        if ($id_ubicacion > 0) {
                                            $objRelaboralMovilidad->ubicacion_id = $id_ubicacion;
                                        } elseif ($id_ubicacion < 0) {
                                            /**
                                             * Este valor establece que se debe ubicar a la asignación de funciones en el mismo lugar donde
                                             * se encuentra registrado el cargo del jefe, pues se ha seleccionado para el registro.
                                             */
                                            $objCargo = new Cargos();
                                            $objSuperior = $objCargo->getCargoSuperiorPorRelaboral($frelaboral->id_relaboral);
                                            if ($objSuperior != null && count($objSuperior) > 0) {
                                                $cargoSup = $objSuperior[0];
                                                /**
                                                 * Se selecciona el último registro que haya sido usado por el jefe
                                                 */
                                                $relaboralAux = Relaborales::findFirst(array("cargo_id = " . $cargoSup->id, 'order' => 'fecha_ini DESC'));
                                                if ($relaboralAux != null && $relaboralAux->id > 0) {
                                                    $relaboralUbicacionAux = Relaboralesubicaciones::findFirst(array("estado=1 AND relaboral_id=" . $relaboralAux->id));
                                                    if ($relaboralUbicacionAux != null) {
                                                        $objRelaboralMovilidad->ubicacion_id = $relaboralUbicacionAux->ubicacion_id;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    #endregion Evaluación de la obligatoriedad de registro de algunos datos
                                    $objRelaboralMovilidad->observacion = $observacion;
                                    /**
                                     * Verificar que no haya otro memorándum de movilidad de personal que este activo,
                                     * si existe otro, darlo de baja inicialmente.
                                     */
                                    $objRelaboralMovilidad->estado = 1;
                                    $objRelaboralMovilidad->baja_logica = 1;
                                    $objRelaboralMovilidad->agrupador = 0;
                                    $objRelaboralMovilidad->user_mod_id = $user_mod_id;
                                    $objRelaboralMovilidad->fecha_mod = $hoy;
                                    try {
                                        /**
                                         * Es necesario verificar que no exista un registro, sea ACTIVO o PASIVO que tenga el mismo tipo de modalidad y tenga cruce de fechas con el registro que se desea realizar
                                         * Pueden tener la misma fecha, pues la variación puede ser en horas.
                                         */
                                        $swAnterior = true;
                                        $anteriorRelaboralMovilidadDelMismoTipo = Relaboralesmovilidades::findFirst(array("baja_logica=1 and modalidadmemorandum_id=" . $objRelaboralMovilidad->modalidadmemorandum_id . " AND relaboral_id = " . $objRelaboralMovilidad->relaboral_id . " AND id!=" . $id_relaboralmovilidad));
                                        if ($anteriorRelaboralMovilidadDelMismoTipo != null && $anteriorRelaboralMovilidadDelMismoTipo->id > 0) {
                                            /**
                                             * Viendo si hay cruce de fechas, si lo hay se impide el registro
                                             */
                                            if ($anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == null || $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == '' ||
                                                $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin > $objRelaboralMovilidad->fecha_ini
                                            ) {
                                                $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin = $fecha_fin_aux;
                                                $anteriorRelaboralMovilidadDelMismoTipo->estado = 0;
                                                $anteriorRelaboralMovilidadDelMismoTipo->user_mod_id = $user_reg_id;
                                                $anteriorRelaboralMovilidadDelMismoTipo->fecha_mod = $hoy;
                                                $datetime1 = new DateTime($anteriorRelaboralMovilidadDelMismoTipo->fecha_ini);
                                                $datetime2 = new DateTime($fecha_fin_aux);
                                                if ($datetime1 > $datetime2) {
                                                    $swAnterior = false;
                                                } else $anteriorRelaboralMovilidadDelMismoTipo->save();
                                            }
                                        }
                                        if ($swAnterior) {
                                            if ($objRelaboralMovilidad->save()) {
                                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente el registro de Movilidad de Personal.');
                                            } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal.');
                                        } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal debido a que presenta una inconsistencia de fechas con un registro anterior de Movilidad de Personal del mismo tipo. Verifique la fecha de inicio. (' . $objRelaboralMovilidad->relaboral_id . ':' . $objRelaboralMovilidad->modalidadmemorandum_id . ')');
                                    } catch (\Exception $e) {
                                        echo get_class($e), ": ", $e->getMessage(), "\n";
                                        echo " File=", $e->getFile(), "\n";
                                        echo " Line=", $e->getLine(), "\n";
                                        echo $e->getTraceAsString();
                                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                                    }
                                } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; el memor&aacute;ndum.');
                            } catch (\Exception $e) {
                                echo get_class($e), ": ", $e->getMessage(), "\n";
                                echo " File=", $e->getFile(), "\n";
                                echo " Line=", $e->getLine(), "\n";
                                echo $e->getTraceAsString();
                                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                            }
                        } else {
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal debido a que no se hall&oacute; registro del memor&acute;ndum.');
                        }
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal. Verifique los datos enviados.');
                    }

                } else {
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos de la relaci&oacute;n laboral.');
                }
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos al identificador del registro de relaci&oacute;n laboral por movilidad.');
            }
        } else {
            /**
             * Nuevo Registro
             */
            if (isset($_POST['id_relaboral'])) {
                $id_relaboral = $_POST['id_relaboral'];
                $id_da = $_POST['id_da'];
                $id_regional = $_POST['id_regional'];
                $id_organigrama = $_POST['id_organigrama'];
                $id_area = $_POST['id_area'];
                $id_ubicacion = $_POST['id_ubicacion'];
                if (isset($_POST['cargo']['value'])) {
                    $cargo = $_POST['cargo']['value'];
                } elseif (isset($_POST['cargo'])) {
                    $cargo = $_POST['cargo'];
                }
                $id_evento = $_POST['id_evento'];
                $motivo = $_POST['motivo'];
                $id_pais = $_POST['id_pais'];
                $id_departamento = $_POST['id_departamento'];
                $lugar = $_POST['lugar'];
                $id_tipomemorandum = $_POST['id_tipomemorandum'];
                $correlativo = $_POST['correlativo'];
                $gestion = $_POST['gestion'];
                $datefm = new DateTime($_POST['fecha_mem']);
                $fecha_mem = $datefm->format('Y-m-d');
                $contenido_memorandum = $_POST['contenido'];
                $datefi = new DateTime($_POST['fecha_ini']);
                $fecha_ini = $datefi->format('Y-m-d');
                /**
                 * Calculamos la fecha previa al inicio para uso posterior.
                 */
                $datefiaux = new DateTime($_POST['fecha_ini']);
                $datefiaux->format('Y-m-d');
                $fecha_fin_aux = date('Y-m-d', strtotime('-1 days', strtotime($_POST['fecha_ini'])));

                $hora_ini = $_POST['hora_ini'];
                $dateff = new DateTime($_POST['fecha_fin']);
                $fecha_fin = $dateff->format('Y-m-d');
                $hora_fin = $_POST['hora_fin'];
                $observacion = $_POST['observacion'];
                $obj = new Frelaborales();
                $resul = $obj->getOne($id_relaboral);
                $frelaboral = $resul[0];
                /**
                 * Una movilidad de personal sólo debería poderse registrar para registros de relación laboral ACTIVOS o EN PROCESO.
                 */
                if ($frelaboral->id_relaboral > 0 && $frelaboral->estado >= 1) {
                    $memorandum = new Memorandums();
                    $memorandum->relaboral_id = $frelaboral->id_relaboral;
                    $memorandum->finpartida_id = $frelaboral->id_fin_partida;
                    $memorandum->fecha_mem = $fecha_mem;
                    $memorandum->correlativo = $correlativo;
                    $memorandum->gestion = $gestion;
                    $memorandum->da_id = $frelaboral->id_da;
                    $memorandum->regional_id = $frelaboral->id_regional;
                    $memorandum->tipomemorandum_id = $id_tipomemorandum;
                    $memorandum->estado = 1;
                    $memorandum->baja_logica = 1;
                    $memorandum->agrupador = 0;
                    $memorandum->user_reg_id = $user_reg_id;
                    $memorandum->fecha_reg = $hoy;
                    try {
                        if ($memorandum->save()) {
                            $objRelaboralMovilidad = new Relaboralesmovilidades();
                            $objRelaboralMovilidad->relaboral_id = $frelaboral->id_relaboral;
                            $objRelaboralMovilidad->da_id = $frelaboral->id_da;
                            $objRelaboralMovilidad->regional_id = $frelaboral->id_regional;

                            $modalidadmemorandum = Modalidadmemorandum::findFirst(array("tipomemorandum_id=" . $id_tipomemorandum));
                            if ($modalidadmemorandum->id > 0) {
                                $objRelaboralMovilidad->modalidadmemorandum_id = $modalidadmemorandum->id;
                            }
                            $objRelaboralMovilidad->memorandum_id = $memorandum->id;

                            /**
                             * Obtener la cantidad de memos registrados + 1
                             */
                            $objRel = Relaboralesmovilidades::find(array("baja_logica=1 and relaboral_id=" . $id_relaboral));
                            $objRelaboralMovilidad->numero = $objRel->count() + 1;

                            if ($cargo != '') {
                                $objRelaboralMovilidad->cargo = $cargo;
                            }
                            $objRelaboralMovilidad->fecha_ini = $fecha_ini;
                            if ($hora_ini != '') {
                                $objRelaboralMovilidad->hora_ini = $hora_ini;
                            }
                            #region Evaluación de la obligatoriedad de registro de algunos datos
                            $tipomemorandum = Tiposmemorandums::findFirst(array("id=" . $id_tipomemorandum));
                            if ($tipomemorandum->fecha_fin >= 1) {
                                $objRelaboralMovilidad->fecha_fin = $fecha_fin;
                                /**
                                 * Para el control de la hora es necesario que si o si se registre la fecha de finalización
                                 */
                                if ($hora_fin != '') $objRelaboralMovilidad->hora_fin = $hora_fin;
                            } else {
                                /**
                                 * Evaluar la necesidad de registro de la fecha de finalización del registro de relación laboral como fecha de finalización de la movilidad.
                                 */
                            }

                            if ($tipomemorandum->cargo >= 1 && $cargo != '') {
                                $objRelaboralMovilidad->cargo = $cargo;
                            }

                            if ($tipomemorandum->motivo >= 1 && $motivo != '') {
                                $objRelaboralMovilidad->motivo = $motivo;
                            }

                            if ($tipomemorandum->pais >= 1 && $id_pais > 0) {
                                $objRelaboralMovilidad->pais_id = $id_pais;
                            }

                            if ($tipomemorandum->ciudad >= 1 && $id_departamento > 0) {
                                $objRelaboralMovilidad->departamento_id = $id_departamento;
                            }

                            if ($tipomemorandum->lugar >= 1 && $lugar != '') {
                                $objRelaboralMovilidad->lugar = $lugar;
                            }
                            /**
                             * Es necesario verificar si se ha especificado un valor para el organigrama,
                             * si no es así se registra el correspondiente al registro de relación laboral.
                             */
                            if ($tipomemorandum->organigrama >= 1 && $id_organigrama > 0) {
                                $objRelaboralMovilidad->organigrama_id = $id_organigrama;
                                /**
                                 * Verificando el identificador del área enviada
                                 */
                                if ($id_area > 0) {
                                    /**
                                     * Se evalua si el identificador del área enviada corresponde validamente al identificador del organigrama enviado.
                                     */
                                    $org = new Organigramas();
                                    $okArea = $org->verificarCorrectaCorrespondeciaArea($id_organigrama, $id_area);
                                    if ($okArea != null && $okArea > 0) {
                                        $objRelaboralMovilidad->area_id = $id_area;
                                    }
                                }
                            }
                            if ($tipomemorandum->ubicacion >= 1) {
                                //$relaboralesmovilidades->ubicacion_id = $id_ubicacion;

                                /**
                                 * Verificando el identificador de la ubicación enviada
                                 */
                                if ($id_ubicacion > 0) {
                                    $objRelaboralMovilidad->ubicacion_id = $id_ubicacion;
                                } elseif ($id_ubicacion < 0) {
                                    /**
                                     * Este valor establece que se debe ubicar a la asignación de funciones en el mismo lugar donde
                                     * se encuentra registrado el cargo del jefe, pues se ha seleccionado para el registro.
                                     */
                                    $objCargo = new Cargos();
                                    $objSuperior = $objCargo->getCargoSuperiorPorRelaboral($frelaboral->id_relaboral);
                                    if ($objSuperior != null && count($objSuperior) > 0) {
                                        $cargoSup = $objSuperior[0];
                                        /**
                                         * Se selecciona el último registro que haya sido usado por el jefe
                                         */
                                        $relaboralAux = Relaborales::findFirst(array("cargo_id = " . $cargoSup->id, 'order' => 'fecha_ini DESC'));
                                        if ($relaboralAux != null && $relaboralAux->id > 0) {
                                            $relaboralUbicacionAux = Relaboralesubicaciones::findFirst(array("estado=1 AND relaboral_id=" . $relaboralAux->id));
                                            if ($relaboralUbicacionAux != null) {
                                                $objRelaboralMovilidad->ubicacion_id = $relaboralUbicacionAux->ubicacion_id;
                                            }
                                        }
                                    }
                                }
                            }
                            #endregion Evaluación de la obligatoriedad de registro de algunos datos
                            $objRelaboralMovilidad->observacion = $observacion;
                            /**
                             * Verificar que no haya otro memorándum de movilidad de personal que este activo,
                             * si existe otro, darlo de baja inicialmente.
                             */
                            $objRelaboralMovilidad->estado = 1;
                            $objRelaboralMovilidad->baja_logica = 1;
                            $objRelaboralMovilidad->agrupador = 0;
                            $objRelaboralMovilidad->user_reg_id = $user_reg_id;
                            $objRelaboralMovilidad->fecha_reg = $hoy;
                            try {
                                /**
                                 * Es necesario verificar que no exista un registro, sea ACTIVO o PASIVO que tenga el mismo tipo de modalidad y tenga cruce de fechas con el registro que se desea realizar
                                 * Pueden tener la misma fecha, pues la variación puede ser en horas.
                                 */
                                $swAnterior = true;
                                $anteriorRelaboralMovilidadDelMismoTipo = Relaboralesmovilidades::findFirst(array("baja_logica=1 and modalidadmemorandum_id=" . $objRelaboralMovilidad->modalidadmemorandum_id . " AND relaboral_id=" . $objRelaboralMovilidad->relaboral_id));
                                if ($anteriorRelaboralMovilidadDelMismoTipo != null && $anteriorRelaboralMovilidadDelMismoTipo->id > 0) {
                                    /**
                                     * Viendo si hay cruce de fechas, si lo hay se impide el registro
                                     */
                                    if ($anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == null || $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin == '' ||
                                        $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin > $objRelaboralMovilidad->fecha_ini
                                    ) {
                                        $anteriorRelaboralMovilidadDelMismoTipo->fecha_fin = $fecha_fin_aux;
                                        $anteriorRelaboralMovilidadDelMismoTipo->estado = 0;
                                        $anteriorRelaboralMovilidadDelMismoTipo->user_mod_id = $user_reg_id;
                                        $anteriorRelaboralMovilidadDelMismoTipo->fecha_mod = $hoy;
                                        $datetime1 = new DateTime($anteriorRelaboralMovilidadDelMismoTipo->fecha_ini);
                                        $datetime2 = new DateTime($fecha_fin_aux);
                                        if ($datetime1 > $datetime2) {
                                            $swAnterior = false;
                                        } else $anteriorRelaboralMovilidadDelMismoTipo->save();
                                    }
                                }
                                if ($swAnterior) {
                                    if ($objRelaboralMovilidad->save()) {
                                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente el registro de Movilidad de Personal.');
                                    } else {
                                        $memorandum->delete();
                                    }

                                } else {
                                    $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal debido a que presenta una inconsistencia de fechas con un registro anterior de Movilidad de Personal del mismo tipo. Verifique la fecha de inicio. (' . $objRelaboralMovilidad->relaboral_id . ':' . $objRelaboralMovilidad->modalidadmemorandum_id . ')');
                                    /**
                                     * Es necesario dar de baja el registro del memorandum
                                     */
                                    $memorandum->delete();
                                }
                            } catch (\Exception $e) {
                                echo get_class($e), ": ", $e->getMessage(), "\n";
                                echo " File=", $e->getFile(), "\n";
                                echo " Line=", $e->getLine(), "\n";
                                echo $e->getTraceAsString();
                                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                            }
                        } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; el memor&aacute;ndum.');
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la movilidad de personal.');
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la movilidad de personal. Verifique los datos enviados.');
                }

            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos de la relaci&oacute;n laboral.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para el registro de la baja de relación laboral por movilidad.
     */
    public function downmovilidadAction()
    {
        $user_mod_id = 1;
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $fecha_fin = "31/12/" . $gestion_actual;
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $id_relaboralmovilidad = $_POST["id"];
            $dateff = new DateTime($_POST['fecha_fin']);
            $fecha_fin = $dateff->format('Y-m-d');
            $objRM = Relaboralesmovilidades::findFirst(array("id=" . $id_relaboralmovilidad));
            if ($objRM != null && $objRM->id > 0) {
                $objRM->fecha_fin = $fecha_fin;
                $objRM->estado = 0;
                $objRM->user_mod_id = $user_mod_id;
                $objRM->fecha_mod = $hoy;
                /**
                 * Es necesario verificar que la fecha de finalización registrada en la baja no tenga conflictos con las fechas de otros registros del mismo tipo.
                 */
                $swAnterior = true;
                $anteriorRelaboralMovilidadDelMismoTipo = Relaboralesmovilidades::findFirst(array("baja_logica=1 and modalidadmemorandum_id=" . $objRM->modalidadmemorandum_id . " AND relaboral_id=" . $objRM->relaboral_id . " AND id!=" . $objRM->id . " AND CAST('" . $fecha_fin . "' AS DATE) BETWEEN fecha_ini and fecha_fin"));
                if ($anteriorRelaboralMovilidadDelMismoTipo != null && $anteriorRelaboralMovilidadDelMismoTipo->id > 0) {
                    $swAnterior = false;
                }
                if ($swAnterior) {
                    if ($objRM->save()) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se di&oacute; de baja correctamente el registro de Movilidad de Personal.');
                    } else $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; la baja del registro de movilidad de personal. Verifique los datos enviados.');
                } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la baja del registro de movilidad de personal debido a que la fecha de finalizaci&oacute;n presenta una inconsistencia en relaci&oacute; a otro registro del mismo tipo . Verifique la fecha de finalizaci&oacute;n.');
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se registr&oacute; la baja debido a que no se hall&oacute; registro de la relaci&oacute;n laboral por movilidad.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para obtener registro correspondiente al cargo del inmediato superior considerando el identificador de la relación laboral.
     */
    public function getcargosuperiorrelaboralAction()
    {   $this->view->disable();
        $cargo = Array();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $id_relaboral = $_POST["id"];
            $obj = new Cargos();
            $result = $obj->getCargoSuperiorPorRelaboral($id_relaboral);
            if (count($result) > 0) $cargo = $result[0];
        }
        echo json_encode($cargo);
    }

    /**
     * Función para la obtención del listado de paises.
     */
    public function listpaisesAction()
    {
        $this->view->disable();
        $resul = Paises::find(array("estado=1 AND baja_logica=1"));
        //comprobamos si hay filas
        $paises = array();
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $paises[] = array(
                    'id' => $v->id,
                    'iso2' => $v->iso2,
                    'iso3' => $v->iso3,
                    'prefijo' => $v->prefijo,
                    'pais' => $v->pais,
                    'continente' => $v->continente,
                    'subcontinente' => $v->subcontinente,
                    'iso_moneda' => $v->iso_moneda,
                    'nombre_moneda' => $v->nombre_moneda,
                    'nacionalidad' => $v->nacionalidad,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador
                );
            }
        }
        echo json_encode($paises);
    }

    /**
     * Función para la obtención del listado de departamentos.
     */
    public function listdepartamentosAction()
    {
        $this->view->disable();
        $paises = array();
        if (isset($_GET["pais_id"])) {
            $pais_id = $_GET["pais_id"];
            $resul = Departamentos::find(array("estado=1 AND baja_logica=1 AND pais_id=" . $pais_id));
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $paises[] = array(
                        'id' => $v->id,
                        'pais_id' => $v->pais_id,
                        'departamento' => $v->departamento,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado,
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador
                    );
                }
            }
        }
        echo json_encode($paises);
    }

    public function cargamovilidadAction()
    {
        $this->assets->addJs('/js/relaborales/oasis.relaborales.move.js?v=' . $version);
        $this->view->disable();
    }
    #region Funciones referentes a la gestión de Calendario y Horarios Laborales
    /**
     * Función para el registro de horarios en el sistema.
     */
    public function savehorarioAction()
    {
        $user_reg_id = 1;
        $user_mod_id = 1;
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $fecha_fin = "31/12/" . $gestion_actual;
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edición de Horario
             */
        } else {
            /**
             * Registro de Horario
             */
            $nombre = $_POST['nombre'];
            $nombre_alternativo = $_POST['nombre_alternativo'];
            $color = $_POST['color'];
            $hora_entrada = $_POST['hora_entrada'];
            $hora_salida = $_POST['hora_salida'];
            $minutos_tolerancia_acu = $_POST['minutos_tolerancia_acu'];
            $minutos_tolerancia_ent = $_POST['minutos_tolerancia_ent'];
            $minutos_tolerancia_sal = $_POST['minutos_tolerancia_sal'];
            $rango_entrada = $_POST['rango_entrada'];
            $rango_salida = $_POST['rango_salida'];
            $hora_inicio_rango_ent = $_POST['hora_inicio_rango_ent'];
            $hora_final_rango_ent = $_POST['hora_final_rango_ent'];
            $hora_inicio_rango_sal = $_POST['hora_inicio_rango_sal'];
            $hora_final_rango_sal = $_POST['hora_final_rango_sal'];
            $observacion = $_POST['observacion'];
            if ($nombre != '' && $color != '' && $hora_entrada != '' && $hora_salida != '' && $minutos_tolerancia_acu != '' && $minutos_tolerancia_ent != '' && $minutos_tolerancia_sal != '' && $hora_inicio_rango_ent != '' && $hora_final_rango_ent != '' && $hora_inicio_rango_sal != '' && $hora_final_rango_sal != '') {
                $objHorarioLaboral = new Horarioslaborales();
                $objHorarioLaboral->nombre = $nombre;
                $objHorarioLaboral->nombre_alternativo = $nombre_alternativo;
                $objHorarioLaboral->hora_ent = $hora_entrada;
                $objHorarioLaboral->hora_sal = $hora_salida;
                $objHorarioLaboral->color = $color;
                $objHorarioLaboral->minutos_tolerancia_ent = $minutos_tolerancia_ent;
                $objHorarioLaboral->minutos_tolerancia_sal = $minutos_tolerancia_sal;
                $objHorarioLaboral->minutos_tolerancia_acu = $minutos_tolerancia_acu;
                $objHorarioLaboral->rango_entrada = $rango_entrada;
                $objHorarioLaboral->rango_salida = $rango_salida;
                $objHorarioLaboral->hora_inicio_rango_ent = $hora_inicio_rango_ent;
                $objHorarioLaboral->hora_final_rango_ent = $hora_final_rango_ent;
                $objHorarioLaboral->hora_inicio_rango_sal = $hora_inicio_rango_sal;
                $objHorarioLaboral->hora_final_rango_sal = $hora_final_rango_sal;
                $objHorarioLaboral->observacion = $observacion;
                $objHorarioLaboral->estado = 2;
                $objHorarioLaboral->baja_logica = 1;
                $objHorarioLaboral->agrupador = 0;
                $objHorarioLaboral->user_reg_id = $user_reg_id;
                $objHorarioLaboral->fecha_reg = $hoy;
                try {
                    $ok = $objHorarioLaboral->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el horario.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Horario laboral laboral.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención del listado de horarios registrados en el sistema
     */
    public function gethorariosregistradosAction()
    {
        $this->view->disable();
        $horariolaboral = Array();
        $obj = new Fhorarioslaborales();
        $resul = $obj->getHorariosLaboralesDisponibles();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $horariolaboral[] = array(
                    'id_horariolaboral' => $v->id_horariolaboral,
                    'nombre' => $v->nombre,
                    'nombre_alternativo' => $v->nombre_alternativo,
                    'hora_entrada' => $v->hora_entrada,
                    'hora_salida' => $v->hora_salida,
                    'horas_laborales' => $v->horas_laborales,
                    'dias_laborales' => $v->dias_laborales,
                    'minutos_tolerancia_ent' => $v->minutos_tolerancia_ent,
                    'minutos_tolerancia_sal' => $v->minutos_tolerancia_sal,
                    'minutos_tolerancia_acu' => $v->minutos_tolerancia_acu,
                    'rango_entrada' => $v->rango_entrada,
                    'rango_salida' => $v->rango_salida,
                    'hora_inicio_rango_ent' => $v->hora_inicio_rango_ent,
                    'hora_final_rango_ent' => $v->hora_final_rango_ent,
                    'hora_inicio_rango_sal' => $v->hora_inicio_rango_sal,
                    'hora_final_rango_sal' => $v->hora_final_rango_sal,
                    'color' => $v->color,
                    'fecha_ini' => $v->fecha_ini,
                    'fecha_fin' => $v->fecha_fin,
                    'observacion' => $v->observacion != null ? $v->observacion : '',
                    'estado' => $v->estado,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod
                );
            }
        }
        echo json_encode($horariolaboral);
    }

    /**
     * Función para la obtención del listado de fechas de acuerdo al rango solicitado.
     * Considerando el día de la semana al cual corresponde.
     */
    public function getrangofechasAction()
    {
        $this->view->disable();
        $rangoFechas = [];
        if (isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"]) && isset($_POST["fin_de_semana"])) {
            $objPerfil = new Perfileslaborales();
            $resul = $objPerfil->getRangoDeFechas($_POST["fecha_ini"], $_POST["fecha_fin"], $_POST["fin_de_semana"]);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    if ($v->o_gestion > 0) {
                        $rangoFechas[] = array(
                            'fecha' => $v->o_fecha,
                            'dia' => $v->o_dia,
                            'fin_de_semana' => $v->o_fin_de_semana
                        );
                    }
                }
            }
        }
        echo json_encode($rangoFechas);
    }

    /**
     * Función para la obtención del listado de gestiones para la generación de turnos laborales.
     */
    public function getgestionesAction()
    {
        $this->view->disable();
        $rangoGestiones = [];
        $hoy = date("Y-m-d");
        if (isset($_POST["id_perfillaboral"])) {
            $objPerfil = new Perfileslaborales();
            $resul = $objPerfil->getGestionesByPerfilLaboral(NULL, $_POST["id_perfillaboral"]);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $rangoGestiones[] = $v->o_gestiones;
                }
            }
        }
        echo json_encode($rangoGestiones);
    }

    /**
     * Función para la obtención del listado de meses para la generación de turnos laborales.
     */
    public function getmesesAction()
    {
        $this->view->disable();
        $rangoMeses = [];
        $hoy = date("Y-m-d");
        if (isset($_POST["id_perfillaboral"]) && isset($_POST["gestion"])) {
            $objPerfil = new Perfileslaborales();
            $resul = $objPerfil->getMesesByPerfilLaboralAndGestion($_POST["id_perfillaboral"], $_POST["gestion"]);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $rangoMeses[] = array(
                        'mes' => $v->mes,
                        'mes_nombre' => $v->mes_nombre
                    );
                }
            }
        }
        echo json_encode($rangoMeses);
    }
    #endregion Funciones referentes a la gestión de Calendario y Horarios Laborales
}