<?php

class IndexController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        $this->assets->addJs('/js/highcharts/highcharts.js');
        $this->assets->addJs('/js/highcharts/modules/exporting.js');
        $this->assets->addJs('/js/dashboard/oasis.dashboard.js');
        $this->assets->addJs('/js/jquery.PrintArea.js');
        $this->assets->addCss('/assets/css/PrintArea.css');
        $gestionActual = date("Y");
        $this->view->setVar('usuario', $this->_user);
        $this->view->setVar('gestion', $gestionActual);
        $auth = $this->session->get('auth');
        $idUsuario = $this->_user->id;
        $usuario = Usuarios::findFirstById($idUsuario);
        $persona = Personas::findFirstById($usuario->persona_id);
        $nombres = $persona->p_apellido.($persona->s_apellido!=''?' '.$persona->s_apellido:'').($persona->c_apellido!=''?' '.$persona->c_apellido:'').($persona->p_nombre!=''?' '.$persona->p_nombre:'').($persona->s_nombre!=''?' '.$persona->s_nombre:'');
        $this->view->setVar('nombres', $nombres);
        $modalidad = $auth['modalidad'];
        $this->view->setVar('modalidad', $modalidad);
        if($modalidad==1){
            /**
             * Cantidad de Personal
             */
            $objRelab = new Relaborales();
            $contratoPlazoFijo = $objRelab->getCantidadPersonalActivoPorCondicion(7);
            $contratoPlazoIndefinido = $objRelab->getCantidadPersonalActivoPorCondicion(6);
            $contratoConsultoria = $objRelab->getCantidadPersonalActivoPorCondicion(3);

            $this->view->setVar('permanentes', $contratoPlazoIndefinido);

            $this->view->setVar('eventuales', $contratoPlazoFijo);

            $this->view->setVar('consultores', $contratoConsultoria);

            $this->view->setVar('total_personal', ($contratoPlazoIndefinido+$contratoPlazoFijo+$contratoConsultoria));

            /**
             * Procesos Pendientes de Conclusión
             */
            $hoy = date("d-m-Y");
            $procesos_pendientes = Procesoscontrataciones::find("fecha_concl>='".$hoy."' AND normativamod_id IN (1,2,5,6) AND tipoconvocatoria_id=1");
            $procesos_concluidos = Procesoscontrataciones::find("fecha_concl<'".$hoy."' AND normativamod_id IN (1,2,5,6) AND tipoconvocatoria_id=1");
            $this->view->setVar('procesos_pendientes', $procesos_pendientes->count());
            $this->view->setVar('procesos_concluidos', $procesos_concluidos->count());
        }else {
            $objFR = new Frelaborales();
            $this->view->setVar('id_persona', $persona->id);
            $lstAntiguedad = $objFR->getListAntiguedadPorPeriodos($persona->id);
            $this->view->setVar('antiguedad', $lstAntiguedad);
            $this->view->setVar('descuentos', array());
            $objRel = Relaborales::findFirst(array("persona_id=".$persona->id." AND estado=1 AND baja_logica=1"));
            if(is_object($objRel)){
                $list = Controlexcepciones::find(array("relaboral_id = ".$objRel->id." AND estado>0 AND baja_logica=1"));
                $this->view->setVar('boletasExcepcionesRegistradas', $list->count());
            }else $this->view->setVar('boletasExcepcionesRegistradas', "0");
            $objIdeas = new Fideas();
            $listIdeas = $objIdeas->getAllByGestionAndMonth($persona->id,0,0,100);
            $this->view->setVar('ideasPublicadas', count($listIdeas));
        }
        $ci_usuario = $persona->ci;
        $ruta = "";
        $rutaImagenesCredenciales = "/images/personal/";
        $extencionImagenesCredenciales = ".jpg";
        $num_complemento = "";
        if (isset($ci_usuario)) {
            $ruta = "";
            $nombreImagenArchivo = $rutaImagenesCredenciales . trim($ci_usuario);
            if ($num_complemento != "") $nombreImagenArchivo = $nombreImagenArchivo . trim($num_complemento);
            $ruta = $nombreImagenArchivo . $extencionImagenesCredenciales;
            if (!file_exists(getcwd() . $ruta))$ruta = '/images/perfil-profesional.jpg';
            $this->view->setVar('ruta', $ruta);
        }
    }

    /**
     * Función para la obtención del listado de descuentos mensuales de una determinada persona
     */
    public function getdescuentospersonalesAction(){
        $this->view->disable();
        $descuentos = array();
        $gestion = 0;
        if(isset($_POST["gestion"])){
            $gestion = $_POST["gestion"];
        }
        $idUsuario = $this->_user->id;
        $usuario = Usuarios::findFirstById($idUsuario);
        $objD = new Fdescuentos();
        $result = $objD->getAllByPerson($usuario->persona_id,$gestion);
        if($result->count()>0){
            foreach ($result as $v) {
                $descuentos[] = array(
                        'id_descuento'=>$v->id_descuento,
                        'relaboral_id'=>$v->relaboral_id,
                        'gestion'=>$v->gestion,
                        'mes'=>$v->mes,
                        'mes_descripcion'=>$v->mes_descripcion,
                        'faltas'=>$v->faltas,
                        'atrasos'=>$v->atrasos,
                        'faltas_atrasos'=>$v->faltas_atrasos,
                        'lsgh'=>$v->lsgh,
                        'abandono'=>$v->abandono,
                        'omision'=>$v->omision,
                        'retencion'=>$v->retencion,
                        'otros'=>$v->otros,
                        'total_descuentos'=>$v->total_descuentos,
                        'observacion'=>$v->observacion,
                        'motivo_anu'=>$v->motivo_anu,
                        'estado'=>$v->estado,
                        'baja_logica'=>$v->baja_logica,
                        'agrupador'=>$v->agrupador,
                        'user_reg_id'=>$v->user_reg_id,
                        'fecha_reg'=>$v->fecha_reg,
                        'user_mod_id'=>$v->user_mod_id,
                        'fecha_mod'=>$v->fecha_mod,
                        'user_anu_id'=>$v->user_anu_id,
                        'fecha_anu'=>$v->fecha_anu
                );
            }

        }
        echo json_encode($descuentos);
    }
    /*
     * Función para la obtención del detalle de cantidades por respuesta de acuerdo a una pregunta.
     */
    public function getdatospieAction(){
        $this->view->disable();
        $encuestas = array();
        if(isset($_POST["id_encuesta"])&&isset($_POST["id_pregunta"])){
            $idEncuesta = $_POST["id_encuesta"];
            $idPregunta = $_POST["id_pregunta"];
            $obj = new Fencuestas();
            $result = $obj->getCountByQuestionsOptionsRestricted($idEncuesta,$idPregunta);
            if($result->count()>0){
                foreach ($result as $v) {
                    $encuestas[] = array(
                        'id_pregunta'=>$v->id_pregunta,
                        'orden'=>$v->orden,
                        'id_opcionrespuesta'=>$v->id_opcionrespuesta,
                        'opcion_respuesta'=>$v->opcion_respuesta,
                        'cantidad'=>$v->cantidad,
                        'total'=>0,
                        'porcentaje'=>0
                    );
                }

            }
        }
        echo json_encode($encuestas);
    }
}