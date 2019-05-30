<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  02-12-2015
*/


class MisencuestasController extends ControllerBase
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
        //$this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        //$this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);

        $this->assets->addJs('/js/misencuestas/oasis.misencuestas.tab.js?v=' . $version);
        $this->assets->addJs('/js/misencuestas/oasis.misencuestas.index.js?v=' . $version);
        $this->assets->addJs('/js/misencuestas/oasis.misencuestas.list.js?v=' . $version);
        $this->assets->addJs('/js/misencuestas/oasis.misencuestas.finish.js?v=' . $version);
        $this->assets->addJs('/js/misencuestas/oasis.misencuestas.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/misencuestas/oasis.misencuestas.view.js?v=' . $version);
        $this->assets->addJs('/js/misencuestas/oasis.misencuestas.view.splitter.js?v=' . $version);
        $auth = $this->session->get('auth');
        $objUsr = new Usuarios();
        $relaboral = $objUsr->getOneRelaboralActivo($auth['id']);
        if (is_object($relaboral)) {
            $this->view->setVar('idRelaboral', $relaboral[0]->id_relaboral);
            /*$this->view->setVar('idPersona', $relaboral[0]->id_persona);*/
            $this->view->setVar('idPersona', "21");
            $this->view->setVar('ci', $relaboral[0]->ci);
            $this->view->setVar('nombres', $relaboral[0]->nombres);
        }
    }

    /**
     * Función para la obtención del listado de encuestas habilitadas para la persona.
     */
    public function listenabledsAction()
    {
        $this->view->disable();
        $obj = new Fencuestas();
        $encuestas = Array();
        $idRelaboral = 0;
        if (isset($_GET["id"])) {
            $result = $obj->getAllByRelaboral($_GET["id"], 1);
            //comprobamos si hay filas
            if (count($result) > 0) {
                foreach ($result as $v) {
                    $encuestas[] = array(
                        'nro_row' => 0,
                        'id_encuesta' => $v->id_encuesta,
                        'codigo' => $v->codigo,
                        'titulo' => $v->titulo,
                        'descripcion' => $v->descripcion,
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'hora_ini' => $v->hora_ini,
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'hora_fin' => $v->hora_fin,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                        'tiempo_restante_vencido' => $v->tiempo_restante_vencido,
                        'permanentes' => $v->permanentes,
                        'permanentes_descripcion' => $v->permanentes_descripcion,
                        'eventuales' => $v->eventuales,
                        'eventuales_descripcion' => $v->eventuales_descripcion,
                        'consultores' => $v->consultores,
                        'consultores_descripcion' => $v->consultores_descripcion,
                        'otros' => $v->otros,
                        'otros_descripcion' => $v->otros_descripcion,
                        'gerencia_administrativa_id' => $v->gerencia_administrativa_id,
                        'gerencia_administrativa' => $v->gerencia_administrativa,
                        'departamento_administrativo_id' => $v->departamento_administrativo_id,
                        'departamento_administrativo' => $v->departamento_administrativo,
                        'area_id' => $v->area_id,
                        'area' => $v->area,
                        'ubicacion_id' => $v->ubicacion_id,
                        'ubicacion' => $v->ubicacion,
                        'num_preguntas' => $v->num_preguntas,
                        'num_respondidas' => $v->num_respondidas,
                        'observacion' => $v->observacion,
                        'agrupador' => $v->agrupador,
                    );
                }
            }

        }
        echo json_encode($encuestas);
    }

    /**
     * Función para la obtención de las preguntas relacionadas a una encuesta en particular, considerando aquellas preguntas ya respondidas por una persona en particular
     * identifificada por el identificador de relación laboral.
     */
    public function getquestionsAction()
    {
        $this->view->disable();
        $obj = new Fencuestas();
        $pregresp = Array();
        if (isset($_POST["id_relaboral"]) && isset($_POST["id_encuesta"])) {
            $result = $obj->getAllQuestionsAndAnswers($_POST["id_relaboral"], $_POST["id_encuesta"]);
            //comprobamos si hay filas
            if (count($result) > 0) {
                $pregresp =$result->toArray();
                /*foreach ($result as $v) {
                    $pregresp[] = array(
                        'nro_row' => 0,
                        'id_encuesta' => $v->id_encuesta,
                        'id_pregunta' => $v->id_pregunta,
                        'pregunta_numero' => $v->pregunta_numero,
                        'pregunta_inciso' => $v->pregunta_inciso,
                        'pregunta' => $v->pregunta,
                        'tipopregunta_id' => $v->tipopregunta_id,
                        'tipo_pregunta' => $v->tipo_pregunta,
                        'pregunta_descripcion' => $v->pregunta_descripcion,
                        'pregunta_observacion' => $v->pregunta_observacion,
                        'pregunta_estado' => $v->pregunta_estado,
                        'pregunta_estado_descripcion' => $v->pregunta_estado_descripcion,
                        'pregunta_despliegue' => $v->pregunta_despliegue,
                        'pregunta_agrupador' => $v->pregunta_agrupador,
                        'id_opcionrespuesta' => $v->id_opcionrespuesta,
                        'opcionrespuesta_respuesta' => $v->opcionrespuesta_respuesta,
                        'opcionrespuesta_puntaje' => $v->opcionrespuesta_puntaje,
                        'opcionrespuesta_agregacion' => $v->opcionrespuesta_agregacion,
                        'id_respuesta' => $v->id_respuesta,
                        'respuesta_relaboral_id' => $v->respuesta_relaboral_id,
                        'asignacionencuesta_id' => $v->asignacionencuesta_id,
                        'respuesta' => $v->respuesta,
                        'respuesta_agregacion' => $v->respuesta_agregacion,
                        'respuesta_observacion' => $v->respuesta_observacion,
                        'respuesta_estado' => $v->respuesta_estado,
                        'respuesta_estado_descripcion' => $v->respuesta_estado_descripcion
                    );
                }*/
            }

        }
        echo json_encode($pregresp);
    }

    /**
     * Función para el registro de una respuesta a una pregunta de opción multiple.
     */
    public function saveomultipleAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $msj = array();
        if (isset($_POST["id_relaboral"]) && isset($_POST["id_encuesta"]) && isset($_POST["id_pregunta"]) && isset($_POST["id_opcion_respuesta"])) {
            $idRelaboral = $_POST["id_relaboral"];
            $idEncuesta = $_POST["id_encuesta"];
            $idPregunta = $_POST["id_pregunta"];
            $idOpcionRespuesta = $_POST["id_opcion_respuesta"];
            $respuestaTexto = $_POST["respuesta_texto"];
            $idTipoPregunta = $_POST["id_tipopregunta"];
            $agregacion = "";
            if ($_POST["agregacion"] != '') {
                $agregacion = $_POST["agregacion"];
            }
            $observacion = "";
            if ($_POST["observacion"] != '') {
                $observacion = $_POST["observacion"];
            }
            /**
             * Inicialmente se busca el identificador del registro de asignación en la encuesta. No se puede asignar la misma pregunta más de una vez en la misma encuesta.
             */
            $asignacionesencuestas = Asignacionesencuestas::FindFirst(array("encuesta_id = :id_encuesta: AND pregunta_id = :id_pregunta: AND estado>=1 AND baja_logica=1", 'bind' => array('id_encuesta' => $idEncuesta, 'id_pregunta' => $idPregunta)));
            if (is_object($asignacionesencuestas)) {
                if ($idTipoPregunta == 2) {
                    $respuesta = Respuestas::findFirst(array("relaboral_id=:id_relaboral: AND asignacionencuesta_id=:id_asignacionencuesta: AND opcionrespuesta_id=:id_opcionrespuesta:", 'bind' => array('id_relaboral' => $idRelaboral, 'id_asignacionencuesta' => $asignacionesencuestas->id, 'id_opcionrespuesta' => $idOpcionRespuesta)));
                } else {
                    $respuesta = Respuestas::findFirst(array("relaboral_id=:id_relaboral: AND asignacionencuesta_id=:id_asignacionencuesta:", 'bind' => array('id_relaboral' => $idRelaboral, 'id_asignacionencuesta' => $asignacionesencuestas->id)));
                }
                /**
                 * Si la respuesta ya estaba registrada, aunque hubiera sido otra la respuesta sólo es necesario modificar, caso contrario es necesario crear el registro.
                 */
                if (is_object($respuesta)) {
                    $respuesta->user_mod_id = $user_mod_id;
                    $respuesta->fecha_mod = $hoy;
                } else {
                    $respuesta = new Respuestas();
                    $respuesta->relaboral_id = $idRelaboral;
                    $respuesta->asignacionencuesta_id = $asignacionesencuestas->id;
                    $respuesta->user_reg_id = $user_mod_id;
                    $respuesta->fecha_reg = $hoy;
                }
                $respuesta->opcionrespuesta_id = $idOpcionRespuesta;
                $respuesta->respuesta = $respuestaTexto;
                $respuesta->agregacion = $agregacion;
                $respuesta->observacion = $observacion;
                $respuesta->estado = 1;
                $respuesta->baja_logica = 1;
                $respuesta->agrupador = 0;
                if ($respuesta->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro satisfactorio de la respuesta.');
                } else $msj = array('result' => -2, 'msj' => 'Error: La respuesta no pudo ser registrada.');
            } else {
                $msj = array('result' => -2, 'msj' => 'Error: La pregunta no se encuentra asignada a esta encuesta actualmente.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
        }
        echo json_encode($msj);
    }
} 