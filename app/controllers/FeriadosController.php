<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  18-02-2015
*/

class FeriadosController extends ControllerBase
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

        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $version);
        $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $version);

        $this->assets->addJs('/js/relaborales/oasis.localizacion.js?v=' . $version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $version);

        $this->assets->addJs('/js/feriados/oasis.feriados.tab.js?v=' . $version);
        $this->assets->addJs('/js/feriados/oasis.feriados.index.js?v=' . $version);
        $this->assets->addJs('/js/feriados/oasis.feriados.approve.js?v=' . $version);
        $this->assets->addJs('/js/feriados/oasis.feriados.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/feriados/oasis.feriados.down.js?v=' . $version);
        $this->assets->addJs('/js/feriados/oasis.feriados.calendar.js?v=' . $version);
    }
    /**
     * Función para la carga del primer listado sobre la página de gestión de tolerancias de ingreso.
     * Se inhabilita la vista para el uso de jqwidgets,
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Fferiados();
        $resul = $obj->getAll();
        $feriados = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            $feriados =$resul->toArray();
            /*foreach ($resul as $v) {
                $feriados[] = array(
                    'nro_row' => 0,
                    'id'=>$v->id,
                    'feriado'=>$v->feriado,
                    'descripcion'=>$v->descripcion,
                    'regional_id'=>$v->regional_id,
                    'regional'=>$v->regional,
                    'horario_discontinuo'=>$v->horario_discontinuo,
                    'horario_discontinuo_descripcion'=>$v->horario_discontinuo_descripcion,
                    'horario_continuo'=>$v->horario_continuo,
                    'horario_continuo_descripcion'=>$v->horario_continuo_descripcion,
                    'horario_multiple'=>$v->horario_multiple,
                    'horario_multiple_descripcion'=>$v->horario_multiple_descripcion,
                    'cantidad_dias'=>$v->cantidad_dias,
                    'repetitivo'=>$v->repetitivo,
                    'repetitivo_descripcion'=>$v->repetitivo_descripcion,
                    'dia'=>$v->dia,
                    'mes'=>$v->mes,
                    'mes_nombre'=>$v->mes_nombre,
                    'gestion'=>$v->gestion,
                    'observacion'=>$v->observacion!=null?$v->observacion:'',
                    'estado'=> $v->estado,
                    'estado_descripcion'=> $v->estado_descripcion,
                    'baja_logica'=> $v->baja_logica,
                    'agrupador'=> $v->agrupador,
                    'user_reg_id'=> $v->user_reg_id,
                    'fecha_reg'=> $v->fecha_reg,
                    'user_mod_id'=> $v->user_mod_id,
                    'fecha_mod'=> $v->fecha_mod
                );
            }*/
        }
        echo json_encode($feriados);
    }
    /**
     * Función para el almacenamiento y actualización de un registro de Tolerancia.
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
            $feriado = strtoupper($_POST['feriado']);
            $descripcion = $_POST['descripcion'];
            $horarioDiscontinuo = $_POST['horario_discontinuo'];
            $horarioContinuo = $_POST['horario_continuo'];
            $horarioMultiple = $_POST['horario_multiple'];
            $cantidadDias = $_POST["cantidad_dias"];
            $repetitivo = $_POST["repetitivo"];
            $dia = $_POST["dia"];
            $mes = $_POST["mes"];
            $gestion = 0;
            if(isset($_POST["gestion"])&&$_POST["gestion"]>0)
            $gestion = $_POST["gestion"];
            $observacion = $_POST['observacion'];
            if($feriado!=""&&$mes>0&&$dia>0){
                $objFeriado = Feriados::findFirst(array("id=".$_POST["id"]));
                if(count($objFeriado)>0){
                    if($gestion>0)
                    $cantMismosDatos = Feriados::count(array("id!=".$_POST["id"]." AND feriado LIKE '".$feriado."' AND dia=".$dia." AND mes=".$mes." AND gestion=".$gestion." AND baja_logica=1"));
                    else $cantMismosDatos = Feriados::count(array("id!=".$_POST["id"]." AND feriado LIKE '".$feriado."' AND dia=".$dia." AND mes=".$mes." AND baja_logica=1"));
                    if($cantMismosDatos==0){
                        $objFeriado->feriado = $feriado;
                        $objFeriado->descripcion = $descripcion;
                        $objFeriado->horario_discontinuo=$horarioDiscontinuo;
                        $objFeriado->horario_continuo=$horarioContinuo;
                        $objFeriado->horario_multiple=$horarioMultiple;
                        $objFeriado->cantidad_dias = $cantidadDias;
                        $objFeriado->repetitivo = $repetitivo;
                        $objFeriado->dia = $dia;
                        $objFeriado->mes = $mes;
                        if($repetitivo==0)$objFeriado->gestion = $gestion;
                        else $objFeriado->gestion = null;
                        $objFeriado->observacion=$observacion;
                        $objFeriado->user_mod_id=$user_mod_id;
                        $objFeriado->fecha_mod=$hoy;
                        try{
                            $ok = $objFeriado->save();
                            if ($ok)  {
                                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente el registro del feriado.');
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro de feriado.');
                            }
                        }catch (\Exception $e) {
                            echo get_class($e), ": ", $e->getMessage(), "\n";
                            echo " File=", $e->getFile(), "\n";
                            echo " Line=", $e->getLine(), "\n";
                            echo $e->getTraceAsString();
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de feriado.');
                        }
                    }else $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados son similares a otro registro existente, debe modificar los valores necesariamente.');
                }
            }else{
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }else{
            /**
             * Registro de Feriado
             */
            $feriado = strtoupper($_POST['feriado']);
            $descripcion = $_POST['descripcion'];
            $idRegional = $_POST['id_regional'];
            $horarioDiscontinuo = $_POST['horario_discontinuo'];
            $horarioContinuo = $_POST['horario_continuo'];
            $horarioMultiple = $_POST['horario_multiple'];
            $cantidadDias = $_POST["cantidad_dias"];
            $repetitivo = $_POST["repetitivo"];
            $dia = $_POST["dia"];
            $mes = $_POST["mes"];
            $gestion = 0;
            if(isset($_POST["gestion"])&&$_POST["gestion"]>0)
                $gestion = $_POST["gestion"];
            $observacion = $_POST['observacion'];
            if($feriado!=""&&$mes>0&&$dia>0){
                if($gestion>0)
                    $cantMismosDatos = Feriados::count(array("feriado LIKE '".$feriado."' AND dia=".$dia." AND mes=".$mes." AND gestion=".$gestion." AND baja_logica=1 AND estado>=0"));
                else
                    $cantMismosDatos = Feriados::count(array("feriado LIKE '".$feriado."' AND dia=".$dia." AND mes=".$mes." AND baja_logica=1 AND estado>=0"));
                if($cantMismosDatos==0){
                    $objFeriado = new Feriados();
                    $objFeriado->feriado = $feriado;
                    $objFeriado->descripcion = $descripcion;
                    $objFeriado->regional_id = $idRegional;
                    $objFeriado->horario_discontinuo = $horarioDiscontinuo;
                    $objFeriado->horario_continuo = $horarioContinuo;
                    $objFeriado->horario_multiple = $horarioMultiple;
                    $objFeriado->cantidad_dias = $cantidadDias;
                    $objFeriado->repetitivo = $repetitivo;
                    $objFeriado->dia = $dia;
                    $objFeriado->mes = $mes;
                    if($repetitivo==0)$objFeriado->gestion = $gestion;
                    $objFeriado->observacion=$observacion;
                    $objFeriado->estado=2;
                    $objFeriado->baja_logica=1;
                    $objFeriado->agrupador=0;
                    $objFeriado->user_reg_id=$user_reg_id;
                    $objFeriado->fecha_reg=$hoy;
                    try{
                        $ok = $objFeriado->save();
                        if ($ok)  {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute; el feriado.');
                        }
                    }catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del feriado.');
                    }
                }    else{
                    $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados son similares a otro registro existente, debe modificar los valores necesariamente.');
                }
            }else{
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }
    /*
     * Función para la aprobación del registro de feriado.
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
            $objFeriados = Feriados::findFirstById($_POST["id"]);
            if ($objFeriados->id > 0 && $objFeriados->estado == 2) {
                try {
                    $objFeriados->estado = 1;
                    $objFeriados->user_mod_id = $user_mod_id;
                    $objFeriados->fecha_mod = $hoy;
                    $ok = $objFeriados->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se aprob&oacute; correctamente el registro del feriado.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se aprob&oacute; el registro del feriado.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del feriado.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro del feriado no cumple con el requisito establecido para su aprobaci&oacute;n, debe estar en estado EN PROCESO.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro del feriado.');
        }
        echo json_encode($msj);
    }
    /**
     * Función para el la baja del registro de un feriado.
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
                $objFeriado = Feriados::findFirstById($_POST["id"]);
                $objFeriado->estado = 0;
                $objFeriado->user_mod_id = $user_mod_id;
                $objFeriado->fecha_mod = $hoy;
                if ($objFeriado->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                } else {
                    foreach ($objFeriado->getMessages() as $message) {
                        echo $message, "\n";
                    }
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro del feriado.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro del feriado.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del feriado.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para la obtención de la lista de feriados de acuerdo a un rango especificado de fechas.
     */
    public function listrangeAction()
    {
        $this->view->disable();
        $obj = new Fferiados();
        $feriados = Array();
        $gestion = 0;
        if(isset($_POST["gestion"])&&isset($_POST["fecha_ini"])&&isset($_POST["fecha_fin"])){
            $gestion = $_POST["gestion"];
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $resul = $obj->getAllRange($gestion,$fechaIni,$fechaFin);
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                $feriados =$resul->toArray();
            }
        }
        echo json_encode($feriados);
    }
} 