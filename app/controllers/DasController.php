<?php 
/**
* 
*/

class DasController extends ControllerBase
{
	public function initialize() {
        parent::initialize();
    }

	public function indexAction()
	{
		$resul = Das::find(array('baja_logica=:activo1:','bind'=>array('activo1'=>'1'),'order' => 'id ASC'));
		$this->view->setVar('da', $resul);	


	}

	public function addAction()
	{
		if ($this->request->isPost()) {
			$resul = new Das();
			$resul->direccion_administrativa = $this->request->getPost('direccion_administrativa');
			$resul->codigo = $this->request->getPost('codigo');
			$resul->observacion = "";
			$resul->estado = 1;
			$resul->visible = 1;
			$resul->baja_logica = 1;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/das');
			//return $this->forward("products/new");
		}

	}

	public function editAction($id)
	{
		$resul = Das::findFirstById($id);
		if ($this->request->isPost()) {
			$resul = Das::findFirstById($this->request->getPost('id'));
			$resul->direccion_administrativa = $this->request->getPost('direccion_administrativa');
			$resul->codigo = $this->request->getPost('codigo');
			$resul->observacion = "";
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			
			$this->response->redirect('/daes');
		}
		$this->view->setVar('da', $resul);		
		//echo $this->view->render('../nivelestructuras/add', array('nivelestructura' => 'hola'));
	}

	public function deleteAction($id)
	{
		$resul = Das::findFirstById($id);
		$resul->baja_logica = 0;
		if ($resul->save()) {
				$this->flashSession->success("Exito: Elimino correctamente el registro...");
			}else{
				$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/das');
	}

	/*public function cargarAction()
	{
		for ($i=0; $i < 1000; $i++) { 
			$resul = new Das();
			$resul->nivelestructura = "nivelestructura".$i;
			$resul->descripcion = "descripcion".$i;
			$resul->activo = true;
			$resul->save();
		}
		
	}
	*/
    function generarmarcacionprevistabAction(){
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if(isset($_POST["opcion"])&&$_POST["opcion"]>0){
            #region Edición de Registro
            $idRelaboral = $_POST["id_relaboral"];
            $gestion = $_POST["gestion"];
            $mes = $_POST["mes"];
            $fechaIni = $_POST["fecha_ini"];
            $fechaFin = $_POST["fecha_fin"];
            $clasemarcacion = $_POST["clasemarcacion"];
            $objCL = new Fcalendariolaboral();
            $turno = 0;
            $grupo = 0;
            $consultaEntrada="";
            $consultaSalida="";
            $objRango = new Ffechasrango();
            $rangoFechas = $objRango->getAll($fechaIni,$fechaFin);
            $matrizHorarios = array();
            $matrizIdCalendarios = array();
            if ($rangoFechas->count() > 0) {
                #region Estableciendo los valores para las variables del objeto
                foreach($rangoFechas as $rango) {
                    $resul = $objCL->getAllRegisteredByPerfilAndRelaboralRangoFechas(0,$idRelaboral,$rango->fecha,$rango->fecha);
                    $turno++;
                    if ($resul->count() > 0) {
                        foreach ($resul as $v) {
                            $grupo++;
                            $arrFecha= explode("-",$rango->fecha);
                            $dia = intval($arrFecha[2]);
                            $matrizHorarios[$dia][$turno][$grupo]=$v->hora_entrada;
                            $matrizIdCalendarios[$dia][$turno][$grupo]=$v->id_calendariolaboral;
                            $grupo++;
                            $matrizHorarios[$dia][$turno][$grupo]=$v->hora_salida;
                            $matrizIdCalendarios[$dia][$turno][$grupo]=$v->id_calendariolaboral;
                            }
                        }
                    }
                }
                if(count($matrizHorarios)>0){
                    foreach($matrizHorarios as $dia => $turnos){
                        foreach($turnos as $grupo => $horas){

                        }
                    }
                }
               }
                else{
                    //$msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el detalle correspondiente a los registro previstos de marcación debido a que no se hall&oacute; los registros correspondientes en la base de datos.');
                    #region Nuevo registro a pesar de haberse solicitado la edición
                    if(isset($_POST["id_relaboral"])&&$_POST["id_relaboral"]>0&&isset($_POST["gestion"])&&isset($_POST["mes"])&&isset($_POST["clasemarcacion"])){
                        #region Nuevo Registro
                        $idRelaboral = $_POST["id_relaboral"];
                        $gestion = $_POST["gestion"];
                        $mes = $_POST["mes"];
                        $fechaIni = $_POST["fecha_ini"];
                        $fechaFin = $_POST["fecha_fin"];
                        $clasemarcacion = $_POST["clasemarcacion"];
                        $objCL = new Fcalendariolaboral();
                        $turno = 0;
                        $grupo = 0;
                        /**
                         * Se obtiene el listado de calendarios registrados para los perfiles correspondientes al registro de relación laboral
                         */
                        $resul = $objCL->getAllRegisteredByPerfilAndRelaboralRangoFechas(0,$idRelaboral,$fechaIni,$fechaFin);
                        if ($resul->count() > 0) {
                            foreach ($resul as $v) {
                                $objMEntrada = new Horariosymarcaciones();
                                $objMSalida = new Horariosymarcaciones();
                                $tipo_horario = $v->tipo_horario;
                                $turno++;
                                $grupo++;
                                $ultimo_dia=0;
                                $objRango = new Ffechasrango();
                                $rangoFechas = $objRango->getAll($fechaIni,$fechaFin);
                                if ($rangoFechas->count() > 0) {
                                    #region Estableciendo los valores para las variables del objeto
                                    $objMEntrada->relaboral_id=$idRelaboral;$objMSalida->relaboral_id=$idRelaboral;
                                    $objMEntrada->gestion=$gestion;$objMSalida->gestion=$gestion;
                                    $objMEntrada->mes=$mes;$objMSalida->mes=$mes;
                                    $objMEntrada->turno=$turno;$objMSalida->turno=$turno;
                                    $objMEntrada->grupo=$grupo;$grupo++;$objMSalida->grupo=$grupo;
                                    $objMEntrada->clasemarcacion=$clasemarcacion;$objMSalida->clasemarcacion=$clasemarcacion;
                                    switch($clasemarcacion){
                                        case 'H':
                                            $objMEntrada->modalidadmarcacion_id=1;
                                            $objMSalida->modalidadmarcacion_id=4;
                                            break;
                                        case 'M':
                                            $objMEntrada->modalidadmarcacion_id=2;
                                            $objMSalida->modalidadmarcacion_id=5;
                                            break;
                                        case 'R':
                                            $objMEntrada->modalidadmarcacion_id=3;
                                            $objMSalida->modalidadmarcacion_id=6;
                                            break;
                                        case 'A':
                                            break;
                                    }
                                    foreach($rangoFechas as $rango){
                                        $cal = Calendarioslaborales::find(array("perfillaboral_id=".$v->id_perfillaboral." AND horariolaboral_id=".$v->id_horariolaboral." AND '".$rango->fecha."' BETWEEN fecha_ini AND fecha_fin AND estado>=1 AND baja_logica=1"));
                                        if($cal->count()>0){
                                            foreach($cal as $cl){
                                                /**
                                                 * Al perfil se le ha asignado una calendarización para esa fecha
                                                 */
                                                $arrFecha= explode("-",$rango->fecha);
                                                $dia = intval($arrFecha[2]);
                                                switch($dia){
                                                    case 1 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral1_id=$cl->id; $objMEntrada->estado1 =1;$objMEntrada->d1 =$v->hora_entrada;$objMSalida->calendariolaboral1_id=$cl->id; $objMSalida->estado1 =1;$objMSalida->d1 =$v->hora_salida;}$ultimo_dia=1 ;break;
                                                    case 2 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral2_id=$cl->id; $objMEntrada->estado2 =1;$objMEntrada->d2 =$v->hora_entrada;$objMSalida->calendariolaboral2_id=$cl->id; $objMSalida->estado2 =1;$objMSalida->d2 =$v->hora_salida;}$ultimo_dia=2 ;break;
                                                    case 3 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral3_id=$cl->id; $objMEntrada->estado3 =1;$objMEntrada->d3 =$v->hora_entrada;$objMSalida->calendariolaboral3_id=$cl->id; $objMSalida->estado3 =1;$objMSalida->d3 =$v->hora_salida;}$ultimo_dia=3 ;break;
                                                    case 4 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral4_id=$cl->id; $objMEntrada->estado4 =1;$objMEntrada->d4 =$v->hora_entrada;$objMSalida->calendariolaboral4_id=$cl->id; $objMSalida->estado4 =1;$objMSalida->d4 =$v->hora_salida;}$ultimo_dia=4 ;break;
                                                    case 5 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral5_id=$cl->id; $objMEntrada->estado5 =1;$objMEntrada->d5 =$v->hora_entrada;$objMSalida->calendariolaboral5_id=$cl->id; $objMSalida->estado5 =1;$objMSalida->d5 =$v->hora_salida;}$ultimo_dia=5 ;break;
                                                    case 6 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral6_id=$cl->id; $objMEntrada->estado6 =1;$objMEntrada->d6 =$v->hora_entrada;$objMSalida->calendariolaboral6_id=$cl->id; $objMSalida->estado6 =1;$objMSalida->d6 =$v->hora_salida;}$ultimo_dia=6 ;break;
                                                    case 7 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral7_id=$cl->id; $objMEntrada->estado7 =1;$objMEntrada->d7 =$v->hora_entrada;$objMSalida->calendariolaboral7_id=$cl->id; $objMSalida->estado7 =1;$objMSalida->d7 =$v->hora_salida;}$ultimo_dia=7 ;break;
                                                    case 8 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral8_id=$cl->id; $objMEntrada->estado8 =1;$objMEntrada->d8 =$v->hora_entrada;$objMSalida->calendariolaboral8_id=$cl->id; $objMSalida->estado8 =1;$objMSalida->d8 =$v->hora_salida;}$ultimo_dia=8 ;break;
                                                    case 9 :if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral9_id=$cl->id; $objMEntrada->estado9 =1;$objMEntrada->d9 =$v->hora_entrada;$objMSalida->calendariolaboral9_id=$cl->id; $objMSalida->estado9 =1;$objMSalida->d9 =$v->hora_salida;}$ultimo_dia=9 ;break;
                                                    case 10:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral10_id=$cl->id;$objMEntrada->estado10=1;$objMEntrada->d10=$v->hora_entrada;$objMSalida->calendariolaboral10_id=$cl->id;$objMSalida->estado10=1;$objMSalida->d10=$v->hora_salida;}$ultimo_dia=10;break;
                                                    case 11:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral11_id=$cl->id;$objMEntrada->estado11=1;$objMEntrada->d11=$v->hora_entrada;$objMSalida->calendariolaboral11_id=$cl->id;$objMSalida->estado11=1;$objMSalida->d11=$v->hora_salida;}$ultimo_dia=11;break;
                                                    case 12:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral12_id=$cl->id;$objMEntrada->estado12=1;$objMEntrada->d12=$v->hora_entrada;$objMSalida->calendariolaboral12_id=$cl->id;$objMSalida->estado12=1;$objMSalida->d12=$v->hora_salida;}$ultimo_dia=12;break;
                                                    case 13:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral13_id=$cl->id;$objMEntrada->estado13=1;$objMEntrada->d13=$v->hora_entrada;$objMSalida->calendariolaboral13_id=$cl->id;$objMSalida->estado13=1;$objMSalida->d13=$v->hora_salida;}$ultimo_dia=13;break;
                                                    case 14:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral14_id=$cl->id;$objMEntrada->estado14=1;$objMEntrada->d14=$v->hora_entrada;$objMSalida->calendariolaboral14_id=$cl->id;$objMSalida->estado14=1;$objMSalida->d14=$v->hora_salida;}$ultimo_dia=14;break;
                                                    case 15:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral15_id=$cl->id;$objMEntrada->estado15=1;$objMEntrada->d15=$v->hora_entrada;$objMSalida->calendariolaboral15_id=$cl->id;$objMSalida->estado15=1;$objMSalida->d15=$v->hora_salida;}$ultimo_dia=15;break;
                                                    case 16:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral16_id=$cl->id;$objMEntrada->estado16=1;$objMEntrada->d16=$v->hora_entrada;$objMSalida->calendariolaboral16_id=$cl->id;$objMSalida->estado16=1;$objMSalida->d16=$v->hora_salida;}$ultimo_dia=16;break;
                                                    case 17:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral17_id=$cl->id;$objMEntrada->estado17=1;$objMEntrada->d17=$v->hora_entrada;$objMSalida->calendariolaboral17_id=$cl->id;$objMSalida->estado17=1;$objMSalida->d17=$v->hora_salida;}$ultimo_dia=17;break;
                                                    case 18:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral18_id=$cl->id;$objMEntrada->estado18=1;$objMEntrada->d18=$v->hora_entrada;$objMSalida->calendariolaboral18_id=$cl->id;$objMSalida->estado18=1;$objMSalida->d18=$v->hora_salida;}$ultimo_dia=18;break;
                                                    case 19:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral19_id=$cl->id;$objMEntrada->estado19=1;$objMEntrada->d19=$v->hora_entrada;$objMSalida->calendariolaboral19_id=$cl->id;$objMSalida->estado19=1;$objMSalida->d19=$v->hora_salida;}$ultimo_dia=19;break;
                                                    case 20:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral20_id=$cl->id;$objMEntrada->estado20=1;$objMEntrada->d20=$v->hora_entrada;$objMSalida->calendariolaboral20_id=$cl->id;$objMSalida->estado20=1;$objMSalida->d20=$v->hora_salida;}$ultimo_dia=20;break;
                                                    case 21:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral21_id=$cl->id;$objMEntrada->estado21=1;$objMEntrada->d21=$v->hora_entrada;$objMSalida->calendariolaboral21_id=$cl->id;$objMSalida->estado21=1;$objMSalida->d21=$v->hora_salida;}$ultimo_dia=21;break;
                                                    case 22:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral22_id=$cl->id;$objMEntrada->estado22=1;$objMEntrada->d22=$v->hora_entrada;$objMSalida->calendariolaboral22_id=$cl->id;$objMSalida->estado22=1;$objMSalida->d22=$v->hora_salida;}$ultimo_dia=22;break;
                                                    case 23:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral23_id=$cl->id;$objMEntrada->estado23=1;$objMEntrada->d23=$v->hora_entrada;$objMSalida->calendariolaboral23_id=$cl->id;$objMSalida->estado23=1;$objMSalida->d23=$v->hora_salida;}$ultimo_dia=23;break;
                                                    case 24:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral24_id=$cl->id;$objMEntrada->estado24=1;$objMEntrada->d24=$v->hora_entrada;$objMSalida->calendariolaboral24_id=$cl->id;$objMSalida->estado24=1;$objMSalida->d24=$v->hora_salida;}$ultimo_dia=24;break;
                                                    case 25:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral25_id=$cl->id;$objMEntrada->estado25=1;$objMEntrada->d25=$v->hora_entrada;$objMSalida->calendariolaboral25_id=$cl->id;$objMSalida->estado25=1;$objMSalida->d25=$v->hora_salida;}$ultimo_dia=25;break;
                                                    case 26:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral26_id=$cl->id;$objMEntrada->estado26=1;$objMEntrada->d26=$v->hora_entrada;$objMSalida->calendariolaboral26_id=$cl->id;$objMSalida->estado26=1;$objMSalida->d26=$v->hora_salida;}$ultimo_dia=26;break;
                                                    case 27:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral27_id=$cl->id;$objMEntrada->estado27=1;$objMEntrada->d27=$v->hora_entrada;$objMSalida->calendariolaboral27_id=$cl->id;$objMSalida->estado27=1;$objMSalida->d27=$v->hora_salida;}$ultimo_dia=27;break;
                                                    case 28:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral28_id=$cl->id;$objMEntrada->estado28=1;$objMEntrada->d28=$v->hora_entrada;$objMSalida->calendariolaboral28_id=$cl->id;$objMSalida->estado28=1;$objMSalida->d28=$v->hora_salida;}$ultimo_dia=28;break;
                                                    case 29:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral29_id=$cl->id;$objMEntrada->estado29=1;$objMEntrada->d29=$v->hora_entrada;$objMSalida->calendariolaboral29_id=$cl->id;$objMSalida->estado29=1;$objMSalida->d29=$v->hora_salida;}$ultimo_dia=29;break;
                                                    case 30:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral30_id=$cl->id;$objMEntrada->estado30=1;$objMEntrada->d30=$v->hora_entrada;$objMSalida->calendariolaboral30_id=$cl->id;$objMSalida->estado30=1;$objMSalida->d30=$v->hora_salida;}$ultimo_dia=30;break;
                                                    case 31:if(($tipo_horario!=3&&$rango->dia!=0&&$rango->dia!=6)||$tipo_horario==3){$objMEntrada->calendariolaboral31_id=$cl->id;$objMEntrada->estado31=1;$objMEntrada->d31=$v->hora_entrada;$objMSalida->calendariolaboral31_id=$cl->id;$objMSalida->estado31=1;$objMSalida->d31=$v->hora_salida;}$ultimo_dia=31;break;
                                                }
                                            }
                                        }
                                    }
                                    #endregion Estableciendo los valores para las variables del objeto
                                }
                                $objMEntrada->ultimo_dia=$ultimo_dia;$objMSalida->ultimo_dia=$ultimo_dia;
                                $objMEntrada->estado=1;$objMSalida->estado=1;
                                $objMEntrada->baja_logica=1;$objMSalida->baja_logica=1;
                                $objMEntrada->agrupador=0;$objMSalida->agrupador=0;
                                $objMEntrada->user_reg_id=$user_reg_id;$objMSalida->user_reg_id=$user_reg_id;
                                $objMEntrada->fecha_reg=$hoy;$objMSalida->fecha_reg=$hoy;
                                try{
                                    $okE = $objMEntrada->save();
                                    $okS = $objMSalida->save();
                                    if ($okE&&$okS)  {
                                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: El detalle correspondiente a los registros previstos de marcaci&oacute;n fueron generados correctamente.');
                                    } else {
                                        $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el detalle correspondiente a los registro previstos de marcación.');
                                    }
                                }catch (\Exception $e) {
                                    echo get_class($e), ": ", $e->getMessage(), "\n";
                                    echo " File=", $e->getFile(), "\n";
                                    echo " Line=", $e->getLine(), "\n";
                                    echo $e->getTraceAsString();
                                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el detalle correspondiente a registros previstos de marcaci&oacute;n.');
                                }
                            }
                        }
                        else{
                            $msj = array('result' => 0, 'msj' => 'Error: No se gener&oacute; el registro de marcaciones debido a que no se encontr&oacute; registros de calendarizaci&oacute;n para el mes solicitado.');
                        }
                        #endregion Nuevo Registro
                    }

                    #endregion Nuevo registro a pesar de haberse solicitado la edición



                }
                #endregion Estableciendo los valores para las variables del objeto

              echo json_encode($msj);
            }
}
?>