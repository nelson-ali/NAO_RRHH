<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  04-12-2014
*/

class CalendariolaboralController extends ControllerBase
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
        $this->assets->addJs('/js/calendariolaboral/oasis.calendariolaboral.index.js?v=' . $version);
        $this->assets->addJs('/js/jquery.kolorpicker.js?v=' . $version);
        $this->assets->addCss('/assets/css/kolorpicker.css?v=' . $version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);
    }

    /**
     * Función para obtener la fecha del día de anteayer.
     * @param $fecha
     * @return bool|string
     */
    function fechaAnteAyer($fecha)
    {
        $sol = (strtotime($fecha) - 10800);
        return date('d-m-Y', $sol);
    }

    /**
     * Función para la obtención
     */
    public function listregisteredAction()
    {
        $this->view->disable();
        $calendariolaboral = Array();
        $obj = new Fcalendariolaboral();
        if (isset($_POST["id"]) && isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"]) && $_POST["id"] > 0) {
            $idPerfilLaboral = $_POST["id"];
            $fecha_ini = $_POST["fecha_ini"];
            $fecha_fin = $_POST["fecha_fin"];
            $cantidadDias = 0;
            $prm = Parametros::findFirst(array("parametro LIKE 'CANTIDAD_DIAS_RETRASO_PROGRAMACION_CALENDARIOS'"));
            if (is_object($prm)) {
                $cantidadDias = intval($prm->nivel);
            }
            /**
             * Función aplicada para posibilitar modificación hacia atrás dos días.
             */
            if ($cantidadDias > 0) {
                $fechaLimite = date("d-m-Y", strtotime('-' . $cantidadDias . ' day'));
            } else {
                $fechaLimite = date('d-m-Y');
            }

            $resul = $obj->getAllRegisteredByPerfilLaboralRangoFechas($idPerfilLaboral, $fecha_ini, $fecha_fin);
            $permisos = $this->obtenerPermisosPorControladorMasIdentificador(strtolower(str_replace("Controller.php", "", basename(__FILE__))), "boolEsPosibleAccionarSobreHorariosEnFechasPasadas");
            $obj = json_decode($permisos);
            //comprobamos si hay registros
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $cantDias = $this->compararFechas($fechaLimite, date("d-m-Y", strtotime($v->calendario_fecha_fin)));
                    if ($cantDias >= 0) {
                        $nuevo = $obj->n;
                        $ver = $obj->v;
                        $editar = $obj->e;
                        $borrar = $obj->b;
                    } else {
                        $nuevo = $ver = $editar = $borrar = 1;
                    }
                    $calendariolaboral[] = array(
                        'chk' => "",
                        'nro_row' => 0,
                        'n' => $nuevo,
                        'v' => $ver,
                        'e' => $editar,
                        'b' => $borrar,
                        'id_calendariolaboral' => $v->id_calendariolaboral,
                        'calendario_fecha_ini' => $v->calendario_fecha_ini,
                        'calendario_fecha_fin' => $v->calendario_fecha_fin,
                        'calendario_observacion' => $v->calendario_observacion,
                        'calendario_estado' => $v->calendario_estado,
                        'calendario_estado_descripcion' => $v->calendario_estado_descripcion,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'perfil_laboral_grupo' => $v->perfil_laboral_grupo != null ? $v->perfil_laboral_grupo : '',
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion,
                        'perfil_laboral_observacion' => $v->perfil_laboral_observacion,
                        'perfil_laboral_estado' => $v->perfil_laboral_estado,
                        'perfil_laboral_estado_descripcion' => $v->perfil_laboral_estado_descripcion,
                        'id_horariolaboral' => $v->id_horariolaboral,
                        'horario_nombre' => $v->horario_nombre,
                        'horario_nombre_alternativo' => $v->horario_nombre_alternativo,
                        'hora_entrada' => $v->hora_entrada,
                        'hora_salida' => $v->hora_salida,
                        'horas_laborales' => $v->horas_laborales,
                        'dias_laborales' => $v->dias_laborales,
                        'rango_entrada' => $v->rango_entrada,
                        'rango_salida' => $v->rango_salida,
                        'hora_inicio_rango_ent' => $v->hora_inicio_rango_ent,
                        'hora_final_rango_ent' => $v->hora_final_rango_ent,
                        'hora_inicio_rango_sal' => $v->hora_inicio_rango_sal,
                        'hora_final_rango_sal' => $v->hora_final_rango_sal,
                        'color' => $v->color,
                        'horario_fecha_ini' => $v->horario_fecha_ini,
                        'horario_fecha_fin' => $v->horario_fecha_fin,
                        'horario_observacion' => $v->horario_observacion,
                        'horario_estado' => $v->horario_estado,
                        'horario_estado_descripcion' => $v->horario_estado_descripcion,
                        'horario_agrupador' => $v->horario_agrupador,
                        'horario_agrupador_descripcion' => $v->horario_agrupador_descripcion,
                        'id_tolerancia' => $v->id_tolerancia,
                        'tolerancia' => $v->tolerancia,
                        'tolerancia_tipo_acumulacion' => $v->tolerancia_tipo_acumulacion,
                        'tolerancia_tipo_acumulacion_descripcion' => $v->tolerancia_tipo_acumulacion_descripcion,
                        'tolerancia_consideracion_retraso' => $v->tolerancia_consideracion_retraso,
                        'tolerancia_consideracion_retraso_descripcion' => $v->tolerancia_consideracion_retraso_descripcion,
                        'tolerancia_descripcion' => $v->tolerancia_descripcion,
                        'tolerancia_fecha_ini' => $v->tolerancia_fecha_ini,
                        'tolerancia_fecha_fin' => $v->tolerancia_fecha_fin,
                        'tolerancia_observacion' => $v->tolerancia_observacion,
                        'tolerancia_estado' => $v->tolerancia_estado,
                        'tolerancia_estado_descripcion' => $v->tolerancia_estado_descripcion
                    );
                }
            }
        }
        echo json_encode($calendariolaboral);
    }

    /**
     * Función para la obtención del listado de horarios laborales registrados en el calendario laboral para un determinado registro de relación laboral y perfil laboral.
     */
    public function listregisteredbyperfilyrelaboralAction()
    {
        $this->view->disable();
        $calendariolaboral = Array();
        $obj = new Fcalendariolaboral();
        if (isset($_POST["id"]) && $_POST["id"] > 0 && isset($_POST["id_perfillaboral"]) && $_POST["id_perfillaboral"] > 0 && isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $idRelaboral = $_POST["id"];
            $idPerfilRelaboral = $_POST["id_perfillaboral"];
            $fecha_ini = $_POST["fecha_ini"];
            $fecha_fin = $_POST["fecha_fin"];
            $resul = $obj->getAllRegisteredByPerfilAndRelaboralRangoFechas($idPerfilRelaboral, $idRelaboral, $fecha_ini, $fecha_fin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $calendariolaboral[] = array(
                        'chk' => "",
                        'nro_row' => 0,
                        'id_calendariolaboral' => $v->id_calendariolaboral,
                        'calendario_fecha_ini' => $v->calendario_fecha_ini,
                        'calendario_fecha_fin' => $v->calendario_fecha_fin,
                        'calendario_observacion' => $v->calendario_observacion,
                        'calendario_estado' => $v->calendario_estado,
                        'calendario_estado_descripcion' => $v->calendario_estado_descripcion,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'perfil_laboral_grupo' => $v->perfil_laboral_grupo,
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion,
                        'perfil_laboral_observacion' => $v->perfil_laboral_observacion,
                        'perfil_laboral_estado' => $v->perfil_laboral_estado,
                        'perfil_laboral_estado_descripcion' => $v->perfil_laboral_estado_descripcion,
                        'id_horariolaboral' => $v->id_horariolaboral,
                        'horario_nombre' => $v->horario_nombre,
                        'horario_nombre_alternativo' => $v->horario_nombre_alternativo,
                        'hora_entrada' => $v->hora_entrada,
                        'hora_salida' => $v->hora_salida,
                        'horas_laborales' => $v->horas_laborales,
                        'dias_laborales' => $v->dias_laborales,
                        'rango_entrada' => $v->rango_entrada,
                        'rango_salida' => $v->rango_salida,
                        'hora_inicio_rango_ent' => $v->hora_inicio_rango_ent,
                        'hora_final_rango_ent' => $v->hora_final_rango_ent,
                        'hora_inicio_rango_sal' => $v->hora_inicio_rango_sal,
                        'hora_final_rango_sal' => $v->hora_final_rango_sal,
                        'color' => $v->color,
                        'horario_fecha_ini' => $v->horario_fecha_ini,
                        'horario_fecha_fin' => $v->horario_fecha_fin,
                        'horario_observacion' => $v->horario_observacion,
                        'horario_estado' => $v->horario_estado,
                        'horario_estado_descripcion' => $v->horario_estado_descripcion,
                        'id_tolerancia' => $v->id_tolerancia,
                        'tolerancia' => $v->tolerancia,
                        'tolerancia_tipo_acumulacion' => $v->tolerancia_tipo_acumulacion,
                        'tolerancia_tipo_acumulacion_descripcion' => $v->tolerancia_tipo_acumulacion_descripcion,
                        'tolerancia_consideracion_retraso' => $v->tolerancia_consideracion_retraso,
                        'tolerancia_consideracion_retraso_descripcion' => $v->tolerancia_consideracion_retraso_descripcion,
                        'tolerancia_descripcion' => $v->tolerancia_descripcion,
                        'tolerancia_fecha_ini' => $v->tolerancia_fecha_ini,
                        'tolerancia_fecha_fin' => $v->tolerancia_fecha_fin,
                        'tolerancia_observacion' => $v->tolerancia_observacion,
                        'tolerancia_estado' => $v->tolerancia_estado,
                        'tolerancia_estado_descripcion' => $v->tolerancia_estado_descripcion,
                        'relaboralperfil_ubicacion' => $v->relaboralperfil_ubicacion,
                        'relaboralperfil_estacion' => $v->relaboralperfil_estacion,
                        'relaboralperfil_observacion' => $v->relaboralperfil_observacion
                    );
                }
            }
        }
        echo json_encode($calendariolaboral);
    }

    /**
     * Función para la obtención de los calendarios registrados para un registro laboral específico en un periodo determinado de tiempo.
     * En caso de enviarse un valor numérico mayor a cero en el parámetro id_perfillaboral se considera para efectuar el filtro.
     * En caso de enviarse un valor diferente de vacio para ambas fechas como parámetros se consideran para efectuar el filtro.
     */
    public function listallregisteredbyrelaboralAction()
    {
        $this->view->disable();
        $calendariolaboral = Array();
        $obj = new Fcalendariolaboral();
        if (isset($_POST["id"]) && $_POST["id"] > 0 && isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $idRelaboral = $_POST["id"];
            $idPerfilRelaboral = 0;
            if (isset($_POST["id_perfillaboral"]) && $_POST["id_perfillaboral"] > 0)
                $idPerfilRelaboral = $_POST["id_perfillaboral"];
            $fecha_ini = $_POST["fecha_ini"];
            $fecha_fin = $_POST["fecha_fin"];
            $resul = $obj->getAllRegisteredByPerfilAndRelaboralRangoFechas($idPerfilRelaboral, $idRelaboral, $fecha_ini, $fecha_fin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $calendariolaboral[] = array(
                        'chk' => "",
                        'nro_row' => 0,
                        'id_calendariolaboral' => $v->id_calendariolaboral,
                        'calendario_fecha_ini' => $v->calendario_fecha_ini,
                        'calendario_fecha_fin' => $v->calendario_fecha_fin,
                        'calendario_observacion' => $v->calendario_observacion,
                        'calendario_estado' => $v->calendario_estado,
                        'calendario_estado_descripcion' => $v->calendario_estado_descripcion,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'perfil_laboral_grupo' => $v->perfil_laboral_grupo,
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion,
                        'perfil_laboral_observacion' => $v->perfil_laboral_observacion,
                        'perfil_laboral_estado' => $v->perfil_laboral_estado,
                        'perfil_laboral_estado_descripcion' => $v->perfil_laboral_estado_descripcion,
                        'id_horariolaboral' => $v->id_horariolaboral,
                        'horario_nombre' => $v->horario_nombre,
                        'horario_nombre_alternativo' => $v->horario_nombre_alternativo,
                        'hora_entrada' => $v->hora_entrada,
                        'hora_salida' => $v->hora_salida,
                        'horas_laborales' => $v->horas_laborales,
                        'dias_laborales' => $v->dias_laborales,
                        'rango_entrada' => $v->rango_entrada,
                        'rango_salida' => $v->rango_salida,
                        'hora_inicio_rango_ent' => $v->hora_inicio_rango_ent,
                        'hora_final_rango_ent' => $v->hora_final_rango_ent,
                        'hora_inicio_rango_sal' => $v->hora_inicio_rango_sal,
                        'hora_final_rango_sal' => $v->hora_final_rango_sal,
                        'color' => $v->color,
                        'horario_fecha_ini' => $v->horario_fecha_ini,
                        'horario_fecha_fin' => $v->horario_fecha_fin,
                        'horario_observacion' => $v->horario_observacion,
                        'horario_estado' => $v->horario_estado,
                        'horario_estado_descripcion' => $v->horario_estado_descripcion,
                        'id_tolerancia' => $v->id_tolerancia,
                        'tolerancia' => $v->tolerancia,
                        'tolerancia_tipo_acumulacion' => $v->tolerancia_tipo_acumulacion,
                        'tolerancia_tipo_acumulacion_descripcion' => $v->tolerancia_tipo_acumulacion_descripcion,
                        'tolerancia_consideracion_retraso' => $v->tolerancia_consideracion_retraso,
                        'tolerancia_consideracion_retraso_descripcion' => $v->tolerancia_consideracion_retraso_descripcion,
                        'tolerancia_descripcion' => $v->tolerancia_descripcion,
                        'tolerancia_fecha_ini' => $v->tolerancia_fecha_ini,
                        'tolerancia_fecha_fin' => $v->tolerancia_fecha_fin,
                        'tolerancia_observacion' => $v->tolerancia_observacion,
                        'tolerancia_estado' => $v->tolerancia_estado,
                        'tolerancia_estado_descripcion' => $v->tolerancia_estado_descripcion,
                        'relaboralperfil_ubicacion' => $v->relaboralperfil_ubicacion,
                        'relaboralperfil_estacion' => $v->relaboralperfil_estacion,
                        'relaboralperfil_observacion' => $v->relaboralperfil_observacion
                    );
                }
            }
        }
        echo json_encode($calendariolaboral);
    }

    /**
     * Función para la obtención del listado de calendarios laborales con las reglas de marcación de un contrato en un rango de fechas (Grilla).
     */
    public function getlistallregisteredbyrelaboralAction()
    {
        $this->view->disable();
        $calendariolaboral = Array();
        $obj = new Fcalendariolaboral();
        if (isset($_GET["id"]) && $_GET["id"] > 0 && isset($_GET["fecha_ini"]) && isset($_GET["fecha_fin"])) {
            $idRelaboral = $_GET["id"];
            $idPerfilRelaboral = 0;
            if (isset($_GET["id_perfillaboral"]) && $_GET["id_perfillaboral"] > 0)
                $idPerfilRelaboral = $_GET["id_perfillaboral"];
            $fecha_ini = $_GET["fecha_ini"];
            $fecha_fin = $_GET["fecha_fin"];
            $resul = $obj->getAllRegisteredByPerfilAndRelaboralRangoFechas($idPerfilRelaboral, $idRelaboral, $fecha_ini, $fecha_fin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $calendariolaboral[] = array(
                        'chk' => "",
                        'nro_row' => 0,
                        'id_calendariolaboral' => $v->id_calendariolaboral,
                        'calendario_fecha_ini' => $v->calendario_fecha_ini != "" ? date("d-m-Y", strtotime($v->calendario_fecha_ini)) : "",
                        'calendario_fecha_fin' => $v->calendario_fecha_fin != "" ? date("d-m-Y", strtotime($v->calendario_fecha_fin)) : "",
                        'calendario_observacion' => $v->calendario_observacion,
                        'calendario_estado' => $v->calendario_estado,
                        'calendario_estado_descripcion' => $v->calendario_estado_descripcion,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'perfil_laboral_grupo' => $v->perfil_laboral_grupo,
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion,
                        'perfil_laboral_observacion' => $v->perfil_laboral_observacion,
                        'perfil_laboral_estado' => $v->perfil_laboral_estado,
                        'perfil_laboral_estado_descripcion' => $v->perfil_laboral_estado_descripcion,
                        'id_horariolaboral' => $v->id_horariolaboral,
                        'horario_nombre' => $v->horario_nombre,
                        'horario_nombre_alternativo' => $v->horario_nombre_alternativo,
                        'hora_entrada' => $v->hora_entrada,
                        'hora_salida' => $v->hora_salida,
                        'horas_laborales' => $v->horas_laborales,
                        'dias_laborales' => $v->dias_laborales,
                        'rango_entrada' => $v->rango_entrada,
                        'rango_salida' => $v->rango_salida,
                        'hora_inicio_rango_ent' => $v->hora_inicio_rango_ent,
                        'hora_final_rango_ent' => $v->hora_final_rango_ent,
                        'hora_inicio_rango_sal' => $v->hora_inicio_rango_sal,
                        'hora_final_rango_sal' => $v->hora_final_rango_sal,
                        'color' => $v->color,
                        'horario_fecha_ini' => $v->horario_fecha_ini != "" ? date("d-m-Y", strtotime($v->horario_fecha_ini)) : "",
                        'horario_fecha_fin' => $v->horario_fecha_fin != "" ? date("d-m-Y", strtotime($v->horario_fecha_fin)) : "",
                        'horario_fecha_ini' => $v->horario_fecha_ini,
                        'horario_fecha_fin' => $v->horario_fecha_fin,
                        'horario_observacion' => $v->horario_observacion,
                        'horario_estado' => $v->horario_estado,
                        'horario_estado_descripcion' => $v->horario_estado_descripcion,
                        'id_tolerancia' => $v->id_tolerancia,
                        'tolerancia' => $v->tolerancia,
                        'tolerancia_tipo_acumulacion' => $v->tolerancia_tipo_acumulacion,
                        'tolerancia_tipo_acumulacion_descripcion' => $v->tolerancia_tipo_acumulacion_descripcion,
                        'tolerancia_consideracion_retraso' => $v->tolerancia_consideracion_retraso,
                        'tolerancia_consideracion_retraso_descripcion' => $v->tolerancia_consideracion_retraso_descripcion,
                        'tolerancia_descripcion' => $v->tolerancia_descripcion,
                        'tolerancia_fecha_ini' => $v->tolerancia_fecha_ini,
                        'tolerancia_fecha_fin' => $v->tolerancia_fecha_fin,
                        'tolerancia_observacion' => $v->tolerancia_observacion,
                        'tolerancia_estado' => $v->tolerancia_estado,
                        'tolerancia_estado_descripcion' => $v->tolerancia_estado_descripcion,
                        'relaboralperfil_ubicacion' => $v->relaboralperfil_ubicacion,
                        'relaboralperfil_estacion' => $v->relaboralperfil_estacion,
                        'relaboralperfil_observacion' => $v->relaboralperfil_observacion,
                        'relaboralperfilmaquina_ubicacion_entrada_id' => $v->relaboralperfilmaquina_ubicacion_entrada_id,
                        'relaboralperfilmaquina_ubicacion_entrada' => $v->relaboralperfilmaquina_ubicacion_entrada,
                        'relaboralperfilmaquina_ubicacion_salida_id' => $v->relaboralperfilmaquina_ubicacion_salida_id,
                        'relaboralperfilmaquina_ubicacion_salida' => $v->relaboralperfilmaquina_ubicacion_salida,
                        'relaboralperfilmaquina_estacion_entrada_id' => $v->relaboralperfilmaquina_estacion_entrada_id,
                        'relaboralperfilmaquina_estacion_entrada' => $v->relaboralperfilmaquina_estacion_entrada,
                        'relaboralperfilmaquina_estacion_salida_id' => $v->relaboralperfilmaquina_estacion_salida_id,
                        'relaboralperfilmaquina_estacion_salida' => $v->relaboralperfilmaquina_estacion_salida,
                        'relaboralperfilmaquina_tipo_marcacion_salida_descripcion' => $v->relaboralperfilmaquina_tipo_marcacion_salida_descripcion,
                        'relaboralperfilmaquina_tipo_marcacion_entrada_descripcion' => $v->relaboralperfilmaquina_tipo_marcacion_entrada_descripcion,
                    );
                }
            }
        }
        echo json_encode($calendariolaboral);
    }

    /**
     * Función para la obtención del listado de horarios disponibles para un registro de relación laboral, considerando
     * los tipos de horarios continuos, discontinuos y multiples en una sóla vista (Calendario).
     */
    public function listallregisteredbyrelaboralmixtoAction()
    {
        $this->view->disable();
        $calendariolaboral = Array();
        $obj = new Fcalendariolaboral();
        if (isset($_POST["id"]) && $_POST["id"] > 0 && isset($_POST["fecha_ini"]) && isset($_POST["fecha_fin"])) {
            $idRelaboral = $_POST["id"];
            $fecha_ini = $_POST["fecha_ini"];
            $fecha_fin = $_POST["fecha_fin"];
            $resul = $obj->getAllRegisteredByPerfilMixtoAndRelaboralRangoFechas($idRelaboral, $fecha_ini, $fecha_fin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $calendariolaboral[] = array(
                        'chk' => "",
                        'nro_row' => 0,
                        'id_calendariolaboral' => $v->id_calendariolaboral,
                        'calendario_fecha_ini' => $v->calendario_fecha_ini,
                        'calendario_fecha_fin' => $v->calendario_fecha_fin,
                        'calendario_observacion' => $v->calendario_observacion,
                        'calendario_estado' => $v->calendario_estado,
                        'calendario_estado_descripcion' => $v->calendario_estado_descripcion,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'perfil_laboral_grupo' => $v->perfil_laboral_grupo,
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion,
                        'perfil_laboral_observacion' => $v->perfil_laboral_observacion,
                        'perfil_laboral_estado' => $v->perfil_laboral_estado,
                        'perfil_laboral_estado_descripcion' => $v->perfil_laboral_estado_descripcion,
                        'id_horariolaboral' => $v->id_horariolaboral,
                        'horario_nombre' => $v->horario_nombre,
                        'horario_nombre_alternativo' => $v->horario_nombre_alternativo,
                        'hora_entrada' => $v->hora_entrada,
                        'hora_salida' => $v->hora_salida,
                        'horas_laborales' => $v->horas_laborales,
                        'dias_laborales' => $v->dias_laborales,
                        'rango_entrada' => $v->rango_entrada,
                        'rango_salida' => $v->rango_salida,
                        'hora_inicio_rango_ent' => $v->hora_inicio_rango_ent,
                        'hora_final_rango_ent' => $v->hora_final_rango_ent,
                        'hora_inicio_rango_sal' => $v->hora_inicio_rango_sal,
                        'hora_final_rango_sal' => $v->hora_final_rango_sal,
                        'color' => $v->color,
                        'horario_fecha_ini' => $v->horario_fecha_ini,
                        'horario_fecha_fin' => $v->horario_fecha_fin,
                        'horario_observacion' => $v->horario_observacion,
                        'horario_estado' => $v->horario_estado,
                        'horario_estado_descripcion' => $v->horario_estado_descripcion,
                        'id_tolerancia' => $v->id_tolerancia,
                        'tolerancia' => $v->tolerancia,
                        'tolerancia_tipo_acumulacion' => $v->tolerancia_tipo_acumulacion,
                        'tolerancia_tipo_acumulacion_descripcion' => $v->tolerancia_tipo_acumulacion_descripcion,
                        'tolerancia_consideracion_retraso' => $v->tolerancia_consideracion_retraso,
                        'tolerancia_consideracion_retraso_descripcion' => $v->tolerancia_consideracion_retraso_descripcion,
                        'tolerancia_descripcion' => $v->tolerancia_descripcion,
                        'tolerancia_fecha_ini' => $v->tolerancia_fecha_ini,
                        'tolerancia_fecha_fin' => $v->tolerancia_fecha_fin,
                        'tolerancia_observacion' => $v->tolerancia_observacion,
                        'tolerancia_estado' => $v->tolerancia_estado,
                        'tolerancia_estado_descripcion' => $v->tolerancia_estado_descripcion,
                        'relaboralperfil_ubicacion' => $v->relaboralperfil_ubicacion,
                        'relaboralperfil_estacion' => $v->relaboralperfil_estacion,
                        'relaboralperfil_observacion' => $v->relaboralperfil_observacion
                    );
                }
            }
        }
        echo json_encode($calendariolaboral);
    }

    /**
     * Función para la obtención del listado de horarios laborales registrados considerando el tipo de horario seleccionado.
     */
    public function listregisteredportipohorarioAction()
    {
        $this->view->disable();
        $calendariolaboral = Array();
        $obj = new Fcalendariolaboral();
        if (isset($_POST["tipo_horario"])) {
            $tipoHorario = $_POST["tipo_horario"];
            if (isset($_POST["fecha_ini"]))
                $fecha_ini = $_POST["fecha_ini"];
            else $fecha_ini = "";
            if (isset($_POST["fecha_fin"]))
                $fecha_fin = $_POST["fecha_fin"];
            else $fecha_fin = "";
            $resul = $obj->getAllRegisteredByTipoHorario($tipoHorario, $fecha_ini, $fecha_fin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $calendariolaboral[] = array(
                        'chk' => "",
                        'nro_row' => 0,
                        'id_calendariolaboral' => $v->id_calendariolaboral,
                        'calendario_fecha_ini' => $v->calendario_fecha_ini,
                        'calendario_fecha_fin' => $v->calendario_fecha_fin,
                        'calendario_observacion' => $v->calendario_observacion,
                        'calendario_estado' => $v->calendario_estado,
                        'calendario_estado_descripcion' => $v->calendario_estado_descripcion,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'perfil_laboral_grupo' => $v->perfil_laboral_grupo,
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion,
                        'perfil_laboral_observacion' => $v->perfil_laboral_observacion,
                        'perfil_laboral_estado' => $v->perfil_laboral_estado,
                        'perfil_laboral_estado_descripcion' => $v->perfil_laboral_estado_descripcion,
                        'id_horariolaboral' => $v->id_horariolaboral,
                        'horario_nombre' => $v->horario_nombre,
                        'horario_nombre_alternativo' => $v->horario_nombre_alternativo,
                        'hora_entrada' => $v->hora_entrada,
                        'hora_salida' => $v->hora_salida,
                        'horas_laborales' => $v->horas_laborales,
                        'dias_laborales' => $v->dias_laborales,
                        'rango_entrada' => $v->rango_entrada,
                        'rango_salida' => $v->rango_salida,
                        'hora_inicio_rango_ent' => $v->hora_inicio_rango_ent,
                        'hora_final_rango_ent' => $v->hora_final_rango_ent,
                        'hora_inicio_rango_sal' => $v->hora_inicio_rango_sal,
                        'hora_final_rango_sal' => $v->hora_final_rango_sal,
                        'color' => $v->color,
                        'horario_fecha_ini' => $v->horario_fecha_ini,
                        'horario_fecha_fin' => $v->horario_fecha_fin,
                        'horario_observacion' => $v->horario_observacion,
                        'horario_estado' => $v->horario_estado,
                        'horario_estado_descripcion' => $v->horario_estado_descripcion,
                        'id_tolerancia' => $v->id_tolerancia,
                        'tolerancia' => $v->tolerancia,
                        'tolerancia_tipo_acumulacion' => $v->tolerancia_tipo_acumulacion,
                        'tolerancia_tipo_acumulacion_descripcion' => $v->tolerancia_tipo_acumulacion_descripcion,
                        'tolerancia_consideracion_retraso' => $v->tolerancia_consideracion_retraso,
                        'tolerancia_consideracion_retraso_descripcion' => $v->tolerancia_consideracion_retraso_descripcion,
                        'tolerancia_descripcion' => $v->tolerancia_descripcion,
                        'tolerancia_fecha_ini' => $v->tolerancia_fecha_ini,
                        'tolerancia_fecha_fin' => $v->tolerancia_fecha_fin,
                        'tolerancia_observacion' => $v->tolerancia_observacion,
                        'tolerancia_estado' => $v->tolerancia_estado,
                        'tolerancia_estado_descripcion' => $v->tolerancia_estado_descripcion
                    );
                }
            }
        }
        echo json_encode($calendariolaboral);
    }

    /**
     * Función para la obtención del listado de los distintos perfiles laborales a partir del tipo de horario solicitado a través del parámetro enviado.
     */
    public function listperfilesregisteredportipohorarioAction()
    {
        $this->view->disable();
        $perfilesLaboralesDisponibles = Array();
        $obj = new Fcalendariolaboral();
        if (isset($_POST["tipo_horario"])) {
            $tipoHorario = $_POST["tipo_horario"];
            if (isset($_POST["fecha_ini"]))
                $fecha_ini = $_POST["fecha_ini"];
            else $fecha_ini = "";
            if (isset($_POST["fecha_fin"]))
                $fecha_fin = $_POST["fecha_fin"];
            else $fecha_fin = "";
            $resul = $obj->getAllPerfilesRegisteredByTipoHorario($tipoHorario, $fecha_ini, $fecha_fin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $perfilesLaboralesDisponibles[] = array(
                        'nro_row' => 0,
                        'id_perfillaboral' => $v->id_perfillaboral,
                        'perfil_laboral' => $v->perfil_laboral,
                        'perfil_laboral_grupo' => $v->perfil_laboral_grupo,
                        'tipo_horario_descripcion' => $v->tipo_horario_descripcion
                    );
                }
            }
        }
        echo json_encode($perfilesLaboralesDisponibles);
    }

    /**
     * Función para el registro de turnos en la tabla de calendarios laborales.
     */
    public function saveturnoAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $user_reg_id = $auth['id'];
        $msj = Array();
        $gestion_actual = date("Y");
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edición de Horario
             */
            $idCalendarioLaboral = $_POST["id"];
            $idPerfilLaboral = $_POST['id_perfillaboral'];
            $idHorario = $_POST['id_horario'];
            $idTolerancia = $_POST['id_tolerancia'];
            $idJornadaLaboral = $_POST['id_jornada_laboral'];
            $opcion = $_POST['opcion'];
            if ($opcion >= 1 && $idCalendarioLaboral > 0 && $idPerfilLaboral > 0 && $idHorario > 0 && $_POST['fecha_ini'] != '' && $_POST['fecha_fin'] != '' && $idJornadaLaboral > 0) {
                $objCalendarioLaboral = Calendarioslaborales::findFirst(array("id=" . $idCalendarioLaboral));
                $objCalendarioLaboral->perfillaboral_id = $idPerfilLaboral;
                $objCalendarioLaboral->horariolaboral_id = $idHorario;
                $objCalendarioLaboral->jornada_laboral_id = $idJornadaLaboral;
                if ($idTolerancia > 0) {
                    $objCalendarioLaboral->tolerancia_id = $idTolerancia;
                }
                $date1 = new DateTime($_POST['fecha_ini']);
                $date2 = new DateTime($_POST['fecha_fin']);
                $fechaIni = $date1->format('Y-m-d');
                $fechaFin = $date2->format('Y-m-d');
                $objCalendarioLaboral->fecha_ini = $fechaIni;
                $objCalendarioLaboral->fecha_fin = $fechaFin;
                switch ($opcion) {
                    case 1:
                        $objCalendarioLaboral->tolerancia_id = null;
                        break;
                    case 2:
                        //Si se envía la opción 2, quiere decir que se desea concluir la elaboración del calendario.
                        if ($idTolerancia > 0) {
                            $objCalendarioLaboral->tolerancia_id = $idTolerancia;
                        }
                        $objCalendarioLaboral->estado = 2;
                        $objCalendarioLaboral->user_elab_id = $user_mod_id;
                        $objCalendarioLaboral->fecha_elab = $hoy;
                        break;
                    case 3:
                        //Si se envía la opción 3, quiere decir que se desea aprobar la elaboración del calendario.
                        if ($idTolerancia > 0) {
                            $objCalendarioLaboral->tolerancia_id = $idTolerancia;
                        }
                        $objCalendarioLaboral->estado = 3;
                        $objCalendarioLaboral->user_apr_id = $user_mod_id;
                        $objCalendarioLaboral->fecha_apr = $hoy;
                        break;
                }
                $objCalendarioLaboral->user_mod_id = $user_mod_id;
                $objCalendarioLaboral->fecha_mod = $hoy;
                try {
                    $ok = $objCalendarioLaboral->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el turno en el calendario.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de turno dentro del calendario laboral.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        } else {
            /**
             * Registro de Horario
             */
            $idPerfilLaboral = $_POST['id_perfillaboral'];
            $idHorario = $_POST['id_horario'];
            $idTolerancia = $_POST['id_tolerancia'];
            $idJornadaLaboral = $_POST['id_jornada_laboral'];
            if ($idPerfilLaboral > 0 && $idHorario > 0 && $_POST['fecha_ini'] != '' && $_POST['fecha_fin'] != '' && $idJornadaLaboral > 0) {
                $objCalendarioLaboral = new Calendarioslaborales();
                $objCalendarioLaboral->perfillaboral_id = $idPerfilLaboral;
                $objCalendarioLaboral->horariolaboral_id = $idHorario;
                if ($idTolerancia > 0) {
                    $objCalendarioLaboral->tolerancia_id = $idTolerancia;
                }
                $objCalendarioLaboral->jornada_laboral_id = $idJornadaLaboral;
                $date1 = new DateTime($_POST['fecha_ini']);
                $date2 = new DateTime($_POST['fecha_fin']);
                $fechaIni = $date1->format('Y-m-d');
                $fechaFin = $date2->format('Y-m-d');
                $objCalendarioLaboral->fecha_ini = $fechaIni;
                $objCalendarioLaboral->fecha_fin = $fechaFin;
                $objCalendarioLaboral->estado = 1;//La primera vez se registra como "EN ELABORACIÓN"
                $objCalendarioLaboral->baja_logica = 1;
                $objCalendarioLaboral->agrupador = 0;
                $objCalendarioLaboral->user_reg_id = $user_reg_id;
                $objCalendarioLaboral->fecha_reg = $hoy;
                try {
                    $ok = $objCalendarioLaboral->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el turno en el calendario.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de turno dentro del calendario laboral.');
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
     * Función para la baja de un registro (Turno) dentro del calendario laboral.
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
                $objCalendarioLaboral = Calendarioslaborales::findFirstById($_POST["id"]);
                $objCalendarioLaboral->estado = 0;
                $objCalendarioLaboral->baja_logica = 0;
                $objCalendarioLaboral->user_mod_id = $user_mod_id;
                $objCalendarioLaboral->fecha_mod = $hoy;
                if ($objCalendarioLaboral->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro dado de Baja de modo satisfactorio.');
                } else {
                    foreach ($objCalendarioLaboral->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de turno en el calendario laboral.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de evento turno en el calendario laboral.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de turno en el calendario laboral.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para obtener el listado de tipos de jornadas laborales disponibles.
     */
    public function listjornadaslaboralesAction()
    {
        $this->view->disable();
        $jornadasLaborales = Array();
        $resul = Parametros::find(array("parametro='TIPO_JORNADA_LABORAL' AND estado=1 AND baja_logica=1", 'order' => 'valor_1'));
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $jornadasLaborales[] = array(
                    'id' => $v->nivel,
                    'jornada_laboral' => $v->valor_1,
                    'horas_semanales' => $v->valor_2,
                    'horas_dia' => $v->valor_3,
                    'horas_noche' => $v->valor_4
                );
            }
        }
        echo json_encode($jornadasLaborales);
    }

    /**
     * Función para la modificación del estado de los registros de turnos de un calendario al estado EN ELABORACIÓN, de acuerdo a los parámetros enviados.
     */
    public function retornaestadoelaboracionAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $idPerfilLaboral = $_POST["id_perfillaboral"];
        $tipo_horario = $_POST["tipo_horario"];
        $id_horarios = "";
        if ($tipo_horario == 1) {
            $id_horarios = $_POST["id_horarios"];
        }
        $fechaIni = $_POST["fecha_ini"];
        $fechaFin = $_POST["fecha_fin"];
        $obj = new Calendarioslaborales();
        try {
            if ($tipo_horario == 3) {
                $result = $obj->retornaEstadoElaboracion($user_mod_id, $idPerfilLaboral, $fechaIni, $fechaFin, $id_horarios);
            } else {
                $result = $obj->retornaEstadoElaboracionPorIdsCalendarios($user_mod_id, $idPerfilLaboral, $id_horarios);
            }
            $msj = array('result' => 1, 'msj' => 'Exito: Registro exitoso de la modificaci&oacute;n a estado EN ELABORACI&Oacute;N.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de turno en el calendario laboral.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para verificar si una hora se encuentra dentro de un rango de horas.
     */
    public function verificahoraenrangoAction()
    {
        $this->view->disable();
        $msj = array();
        if (isset($_POST["hora"]) && isset($_POST["hora_ini"]) && isset($_POST["hora_fin"])) {
            try {
                $obj = new Calendarioslaborales();
                $result = $obj->verificaHoraEnRango($_POST["hora"], $_POST["hora_ini"], $_POST["hora_fin"]);
                foreach ($result as $valor) {
                    $msj = array('result' => $valor->resultado, 'msj' => $valor->msj);
                }
            } catch (\Exception $e) {
                echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de turno en el calendario laboral.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para verificar si una hora se encuentra dentro de un rango de horas.
     */
    public function verificafechayhoraenrangoAction()
    {
        $this->view->disable();
        $msj = array();
        if (isset($_POST["fecha_hora"]) && isset($_POST["fecha_hora_ini"]) && isset($_POST["fecha_hora_fin"])) {
            try {
                $obj = new Calendarioslaborales();
                $sw = $obj->verificaFechaHoraEnRango($_POST["fecha_hora"], $_POST["fecha_hora_ini"], $_POST["fecha_hora_fin"]);
                if ($sw >= 0) {
                    if ($sw == 1) {
                        $msj = array('result' => 1, 'msj' => "La hora '" . $_POST["fecha_hora"] . "' se encuentra dentro del rango entre '" . $_POST["fecha_hora_ini"] . "' A '" . $_POST["fecha_hora_fin"] . "'");
                    } else {
                        $msj = array('result' => 0, 'msj' => "La hora '" . $_POST["fecha_hora"] . "' NO se encuentra dentro del rango entre '" . $_POST["fecha_hora_ini"] . "' A '" . $_POST["fecha_hora_fin"] . "'");
                    }
                } else {
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: Datos inv&aacute;lidos enviados.');
                }
            } catch (\Exception $e) {
                echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();
                $msj = array('result' => -2, 'msj' => 'Error cr&iacute;tico: No se pudo obtener los datos.');
            }
        }
        echo json_encode($msj);
    }

    /**
     * Función para obtener la fecha de inicio y finalización de un calendario en una gestión y mes respectivos.
     */
    public function obtenerfechainifincalendarioAction()
    {
        $this->view->disable();
        $msj = array();
        if (isset($_POST["gestion"]) && $_POST["gestion"] > 0 && isset($_POST["mes"]) && $_POST["mes"] > 0) {
            try {
                $obj = new Calendarioslaborales();
                $result = $obj->getFechaIniFinCalendar($_POST["gestion"], $_POST["mes"]);
                foreach ($result as $valor) {
                    $msj = array('result' => 1, 'fecha_ini' => $valor->fecha_ini, 'fecha_fin' => $valor->fecha_fin);
                }
            } catch (\Exception $e) {
                echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de turno en el calendario laboral.');
            }
        }
        echo json_encode($msj);
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