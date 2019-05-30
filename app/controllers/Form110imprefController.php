<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  31-07-2015
*/


class Form110imprefController extends ControllerBase{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
    }
    /**
     * Función para la carga del primer listado sobre la página de gestión de tolerancias de ingreso.
     * Se inhabilita la vista para el uso de jqwidgets,
     */
    public function getoneforrelaboralAction()
    {
        $this->view->disable();
        $obj = new Form110impref();
        $idRelaboral = 0;
        $form110impref = Array();
        $id = 0;
        $idRelaboral = 0;
        $gestion = 0;
        $mes = 0;
        if(isset($_POST["id"])&&$_POST["id"]>0)
        {
            $id = $_POST["id"];
        }
        if(isset($_POST["id_relaboral"])&&$_POST["id_relaboral"]>0)
        {
            $idRelaboral = $_POST["id_relaboral"];
        }
        if(isset($_POST["gestion"])&&$_POST["gestion"]>0)
        {
            $gestion = $_POST["gestion"];
        }
        if(isset($_POST["mes"])&&$_POST["mes"]>0)
        {
            $mes = $_POST["mes"];
        }
        if(isset($_POST["id_relaboral"])&&$_POST["id_relaboral"]>0&&$gestion>0&&$mes>0){
            if($id>0) $resul = Form110impref::find("id=".$id);
            else $resul = Form110impref::find("relaboral_id=".$idRelaboral." AND gestion=".$gestion." AND mes=".$mes." AND estado=1 AND baja_logica=1");
            if(count($resul)>0){
                foreach ($resul as $v) {
                    $form110impref[] = array(
                        'nro_row' => 0,
                        'id'=>$v->id,
                        'relaboral_id'=>$v->relaboral_id,
                        'gestion'=>$v->gestion,
                        'mes'=>$v->mes,
                        'cantidad'=>$v->cantidad,
                        'monto_diario'=>$v->monto_diario,
                        'importe'=>$v->importe,
                        'impuesto'=>$v->impuesto,
                        'retencion'=>$v->retencion,
                        'fecha_form'=>$v->fecha_form != "" ? date("d-m-Y", strtotime($v->fecha_form)) : "",
                        'codigo'=>$v->codigo,
                        'observacion'=>$v->observacion!=null?$v->observacion:'',
                        'estado'=> $v->estado,
                        'estado_descripcion'=> ($v->estado==1)?"ACTIVO":"PASIVO",
                        'baja_logica'=> $v->baja_logica,
                        'agrupador'=> $v->agrupador,
                        'user_reg_id'=> $v->user_reg_id,
                        'fecha_reg'=> $v->fecha_reg,
                        'user_mod_id'=> $v->user_mod_id,
                        'fecha_mod'=> $v->fecha_mod
                    );
                }
            }
        }
        echo json_encode($form110impref);
    }
    /**
     * Función para el almacenamiento y actualización de un registro de formulario 110 por impuesto de refrigerio.
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
             * Modificación de registro del formulario 110 por impuesto de refrigerio
             */
            $idRelaboral = $_POST['id_relaboral'];
            $gestion = $_POST['gestion'];
            $mes = $_POST['mes'];
            $importe = $_POST['importe'];
            $totalGanado = $_POST['total_ganado'];
            $observacion = $_POST['observacion'];
            $fechaForm = $_POST['fecha_form'];
            $arrFechaForm = explode("-",$fechaForm);
            if(checkdate($arrFechaForm[1],$arrFechaForm[0],$arrFechaForm[2])){
            if($idRelaboral>0){
                $objForm110ImpRef = Form110impref::findFirstById($_POST["id"]);
                if(count($objForm110ImpRef)>0){
                    $objForm110ImpRef->importe = $importe;
                    $rcIvaDebido = $totalGanado * 0.13;
                    $impuesto =  round($importe * 0.13,0);
                    $retencion = round($rcIvaDebido - $impuesto,0);
                    if($retencion<0){
                        $retencion = 0;
                    }
                    $objForm110ImpRef->impuesto = $impuesto;
                    $objForm110ImpRef->retencion = $retencion;
                    $objForm110ImpRef->fecha_form = $fechaForm;
                    $objForm110ImpRef->observacion = $observacion;
                    $objForm110ImpRef->user_mod_id=$user_mod_id;
                    $objForm110ImpRef->fecha_mod=$hoy;
                    try{
                        $ok = $objForm110ImpRef->save();
                        if ($ok)  {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente el registro del Formulario 110 de Impuestos por Refrigerio.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro de Formulario 110 de Impuestos por Refrigerio.');
                        }
                    }catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Formulario 110 de Impuestos por Refrigerio.');
                    }


                }
            }else{
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
            }else{
                $msj = array('result' => 0, 'msj' => 'Error: La fecha enviada para el registro no es correcta.');
            }
        }else{
            /**
             * Registro nuevo del formulario 110 por impuesto de refrigerio
             */
            $idRelaboral = $_POST['id_relaboral'];
            $gestion = $_POST['gestion'];
            $mes = $_POST['mes'];
            $importe = $_POST['importe'];
            $cantidad = $_POST['cantidad'];
            $totalGanado = $_POST['total_ganado'];
            $observacion = $_POST['observacion'];
            $fechaForm = $_POST['fecha_form'];
            $objMontoDiario = Parametros::FindFirst("parametro='MONTO_REFRIGERIO_DIARIO' AND estado=1");
            $montoDiario = 0;
            if(is_object($objMontoDiario)>0){
                $montoDiario = $objMontoDiario->readAttribute('nivel');
            }
            if($montoDiario==0)$montoDiario=17;
            $arrFechaForm = explode("-",$fechaForm);
            if(checkdate($arrFechaForm[1],$arrFechaForm[0],$arrFechaForm[2])) {
                if ($idRelaboral > 0 && $gestion > 0 && $mes > 0) {
                    $objForm110ImpRef = Form110impref::findFirst(array("relaboral_id=" . $idRelaboral . " AND gestion=" . $gestion . " AND mes=" . $mes));
                    if (is_object($objForm110ImpRef)) {
                        $objForm110ImpRef->user_mod_id = $user_mod_id;
                        $objForm110ImpRef->fecha_mod = $hoy;
                    } else {
                        $objForm110ImpRef = new Form110impref();
                        $objForm110ImpRef->user_reg_id = $user_reg_id;
                        $objForm110ImpRef->fecha_reg = $hoy;
                    }
                    $objForm110ImpRef->relaboral_id = $idRelaboral;
                    $objForm110ImpRef->gestion = $gestion;
                    $objForm110ImpRef->mes = $mes;
                    $objForm110ImpRef->cantidad = $cantidad;
                    $objForm110ImpRef->monto_diario = $montoDiario;
                    $objForm110ImpRef->importe = $importe;

                    $rcIvaDebido = $totalGanado * 0.13;
                    $impuesto = round($importe * 0.13,0);
                    $retencion = round($rcIvaDebido - $impuesto,0);
                    if ($retencion < 0) {
                        $retencion = 0;
                    }
                    $objForm110ImpRef->impuesto = $impuesto;
                    $objForm110ImpRef->retencion = $retencion;

                    $objForm110ImpRef->fecha_form = $fechaForm;
                    $objForm110ImpRef->observacion = $observacion;
                    $objForm110ImpRef->estado = 1;
                    $objForm110ImpRef->baja_logica = 1;
                    $objForm110ImpRef->agrupador = 0;
                    try {
                        $ok = $objForm110ImpRef->save();
                        if ($ok) {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente el registro del Formulario 110 de Impuestos por Refrigerio.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el registro de Formulario 110 de Impuestos por Refrigerio.');
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Formulario 110 de Impuestos por Refrigerio.');
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
                }
            }else $msj = array('result' => 0, 'msj' => 'La fecha enviada para el registro no es correcta.');
        }
        echo json_encode($msj);
    }
} 