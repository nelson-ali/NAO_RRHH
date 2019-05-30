<?php 
/**
* 
*/

class OrganigramasController extends ControllerBase
{
	public function initialize() {
		parent::initialize();
	}

	public function indexAction($resolucion_ministerial_id=0)
	{
		if ($resolucion_ministerial_id!=0) {
			$organigrama=  Organigramas::findFirst(array("padre_id = '0' and baja_logica = 1 and resolucion_ministerial_id=".$resolucion_ministerial_id));
			$this->listar($organigrama->id,$organigrama->unidad_administrativa, $organigrama->sigla,$organigrama->resolucion_ministerial_id);
		}else{

			$resolucion_ministerial = Resoluciones::findFirst(array("uso=1 and activo=1 and baja_logica=1"));
			if ($resolucion_ministerial!=FALSE) {
				$organigrama=  Organigramas::findFirst(array("padre_id = '0' and baja_logica = 1 and resolucion_ministerial_id=".$resolucion_ministerial->id));	
				$resolucion_ministerial_id=$resolucion_ministerial->id;
				$this->listar($organigrama->id,$organigrama->unidad_administrativa, $organigrama->sigla,$organigrama->resolucion_ministerial_id);
			}else{
				$resolucion_ministerial = Resoluciones::findFirst(array("uso=1 and baja_logica=1", 'order'=>' id DESC', 'limit'=>'1'));
				
					$organigrama=  Organigramas::findFirst(array("padre_id = '0' and baja_logica = 1 and resolucion_ministerial_id=".$resolucion_ministerial->id));	
					$resolucion_ministerial_id=$resolucion_ministerial->id;
					$this->listar($organigrama->id,$organigrama->unidad_administrativa, $organigrama->sigla,$organigrama->resolucion_ministerial_id);
				
			}
                        
		}

			
		
		
		//Organigramas::find(array('baja_logica=1','order' => 'unidad_administrativa ASC')),
		
		$this->lista.='</ul>';
		$config = array();

		$this->assets
		->addCss('/js/jorgchart/jquery.jOrgChart.css')
		->addCss('/js/jorgchart/custom.css')
		;
		$this->assets->addJs('/js/jorgchart/jquery.jOrgChart.js');

		$this->view->setVar('lista', $this->lista);

		// $resul = Resolucionesministeriales::find(array('baja_logica=1','order' => 'id ASC'));

		$this->tag->setDefault("resolucion_ministerial_id", $resolucion_ministerial_id);
        $resolucion_ministerial = $this->tag->select(
			array(
				'resolucion_ministerial_id',
				Resoluciones::find(array('baja_logica=1 and uso =1',"order"=>"id ASC")),
				'using' => array('id', "tipo_resolucion"),
				'useEmpty' => FALSE,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);

		$this->view->setVar('resolucion_ministerial',$resolucion_ministerial);



	}

	public function addAction($id='')
	{	
		$this->assets->addJs('/js/jquery.kolorpicker.js');
        $this->assets->addCss('/assets/css/kolorpicker.css');
                    
		$auth = $this->session->get('auth');
		if ($this->request->isPost()) {
			$resul = new Organigramas();
			$resul->padre_id = $this->request->getPost('padre_id');
			$resul->gestion = date("Y");
			$resul->da_id = 1;
			$resul->regional_id = 1;
			$resul->unidad_administrativa = $this->request->getPost('unidad_administrativa');
			$resul->nivel_estructural_id = $this->request->getPost('nivel_estructural_id');
			$resul->sigla = $this->request->getPost('sigla');
			$resul->fecha_ini = date("Y-m-d",strtotime($_POST['fecha_ini']));
			$resul->codigo = $this->request->getPost('codigo');
			$resul->estado = 1;
			$resul->baja_logica = 1;
			$resul->user_reg_id = $auth['id'];
			$resul->visible = 1;
			$resul->fecha_reg = date("Y-m-d H:i:s");
			$resul->area_sustantiva = $this->request->getPost('area_sustantiva');
			$resul->asistente = $this->request->getPost('asistente');
			$resul->color = $this->request->getPost('color');
			$resul->resolucion_ministerial_id = $this->request->getPost('resolucion_ministerial_id');
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/organigramas');
		}

		$resul = Organigramas::findFirstById($id);
		$this->view->setVar('sigla',$resul->sigla);
		$this->view->setVar('resolucion_ministerial_id',$resul->resolucion_ministerial_id);		
		$this->tag->setDefault("padre_id", $id);
		$padre = $this->tag->select(
			array(
				'padre_id',
				Organigramas::find(array('baja_logica=1','order' => 'unidad_administrativa ASC')),
				'using' => array('id', "unidad_administrativa"),
				'useEmpty' => true,
				'emptyText' => 'Seleccionar',
				'emptyValue' => '0',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('padre',$padre);

		$nivelestructural= $this->tag->select(
			array(
				'nivel_estructural_id',
				Nivelestructurales::find(array('baja_logica=1','order' => 'id ASC')),
				'using' => array('id', "nivel_estructural"),
				'useEmpty' => false,
				'emptyText' => 'Mi Teleferico',
				'emptyValue' => '0',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('nivelestructural',$nivelestructural);		
	}

	public function editAction($id='')
	{
		$this->assets->addJs('/js/jquery.kolorpicker.js');
        $this->assets->addCss('/assets/css/kolorpicker.css');
		$auth = $this->session->get('auth');
		$resul = Organigramas::findFirstById($id);
		if ($this->request->isPost()) {
			$resul = Organigramas::findFirstById($this->request->getPost('id'));
			$resul->padre_id = $this->request->getPost('padre_id');
			$resul->unidad_administrativa = $this->request->getPost('unidad_administrativa');
			$resul->nivel_estructural_id = $this->request->getPost('nivel_estructural_id');
			$resul->sigla = $this->request->getPost('sigla');
			$resul->codigo = $this->request->getPost('codigo');
			$resul->fecha_ini = date("Y-m-d",strtotime($this->request->getPost('fecha_ini')));
			$resul->user_mod_id = $auth['id'];
			$resul->fecha_mod = date("Y-m-d H:i:s");
			$resul->area_sustantiva = $this->request->getPost('area_sustantiva');
			$resul->asistente = $this->request->getPost('asistente');
			$resul->color = $this->request->getPost('color');
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			
			$this->response->redirect('/organigramas');
		}
		$this->view->setVar('organigrama', $resul);		

		$this->tag->setDefault("padre_id", $resul->padre_id);
		$padre = $this->tag->select(
			array(
				'padre_id',
				Organigramas::find(array('baja_logica=1','order' => 'id ASC')),
				'using' => array('id', "unidad_administrativa"),
				'useEmpty' => true,
				'emptyText' => 'Mi Teleferico',
				'emptyValue' => '0',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('padre',$padre);

		$this->tag->setDefault("nivel_estructural_id", $resul->nivel_estructural_id);
		$nivelestructural = $this->tag->select(
			array(
				'nivel_estructural_id',
				Nivelestructurales::find(array('baja_logica=1','order' => 'id ASC')),
				'using' => array('id', "nivel_estructural"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('nivelestructural',$nivelestructural);

	}

	public function listar($id, $oficina, $sigla,$resolucion_ministerial_id) {
		$h=  organigramas::count("padre_id='$id'");        
		$this->lista.='<li id="org" style="display:none">
		<a href="/organigramas/add/'.$id.'" title="adicionar"><i class="fa fa-plus-circle fa-lg"></i></a>
		<a href="/organigramas/edit/'.$id.'" title="editar"><i class="fa fa-pencil fa-lg"></i></a>
		<a href="/organigramas/delete/'.$id.'" title="eliminar"><i class="fa fa-minus-circle fa-lg"></i></a>
		<br>
		<a href="/organigramas/personal/'.$id.'" title="Ver Personigrama" style="color:#f2f2f2">'.$oficina.'</a>';
		if ($h > 0) {
            //echo '<ul>';
			$this->lista.='<ul>';
			$hijos=  Organigramas::find(array("padre_id='$id' and baja_logica=1 and visible=1 and resolucion_ministerial_id='$resolucion_ministerial_id'"));
            //$hijos = ORM::factory('oficinas')->where('padre', '=', $id)->find_all();
			foreach ($hijos as $hijo) {
				$oficina = $hijo->unidad_administrativa;
				$this->listar($hijo->id, $oficina, $hijo->sigla,$hijo->resolucion_ministerial_id);
			}
			$this->lista.='</ul>';
            // echo '</ul>';
		} else {
			$this->lista.='</li>';
            //   echo '</li>';
		}
	}


	public function listAction()
	{
		//$resul = Organigramas::find(array('baja_logica=:activo1:','bind'=>array('activo1'=>'1'),'order' => 'id ASC'));
		$model = new Organigramas();
		$resul = $model->lista();

		$this->view->disable();
		foreach ($resul as $v) {
			$customers[] = array(
				'id' => $v->id,
				'padre_id' => $v->padre_id,
				'padre' => $v->padre,
				//'direccion_administrativa' => $v->direccion_administrativa,
				'unidad_administrativa' => $v->unidad_administrativa,
				'nivel_estructural_id' => $v->nivel_estructural_id,
				'sigla' => $v->sigla,
				);
		}
		echo json_encode($customers);
	}

	public function saveAction()
	{
		if (isset($_POST['id'])) {
			$date = new DateTime($_POST['fecha_ini']);
			$fecha_ini = $date->format('Y-m-d');

			if ($_POST['id']>0) {
				$resul = Organigramas::findFirstById($_POST['id']);
				$resul->padre_id = $_POST['padre_id'];
				$resul->da_id = $_POST['da_id'];
				$resul->unidad_administrativa = $_POST['unidad_administrativa'];
				$resul->nivel_estructural_id = $_POST['nivel_estructural_id'];
				$resul->sigla = $_POST['sigla'];
				$resul->fecha_ini = $fecha_ini;
				$resul->user_mod_id = $auth['id'];
				$resul->fecha_mod = date("Y-m-d H:i:s");
				$resul->save();
				
			}
			else{
				$resul = new Organigramas();
				$resul->padre_id = $_POST['padre_id'];
				$resul->gestion = date("Y");
				$resul->da_id = 1;
				$resul->regional_id = 1;
				$resul->unidad_administrativa = $_POST['unidad_administrativa'];
				$resul->nivel_estructural_id = $_POST['nivel_estructural_id'];
				$resul->sigla = $_POST['sigla'];
				$resul->fecha_ini = $fecha_ini;
				$resul->estado = 1;
				$resul->baja_logica = 1;
				$resul->user_reg_id = $auth = $this->session->get('auth');
				$resul->fecha_reg = date("Y-m-d H:i:s");
				if ($resul->save()) {
					$msm = array('msm' => 'Exito: Se guardo correctamente' );
				}else{
					$msm = array('msm' => 'Error: No se guardo el registro' );
				}
				
			}	
		}
		$this->view->disable();
		echo json_encode($msm);
	}

	public function deleteAction($id)
	{
		$resul = Organigramas::findFirstById($id);
		$resul->user_mod_id = $auth['id'];
		$resul->fecha_mod = date("Y-m-d H:i:s");
		$resul->baja_logica = 0;
		if ($resul->save()) {
			$this->flashSession->success("Exito: Elimino correctamente el registro...");
		}else{
			$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/organigramas');
	}

	/*public function deleteAction(){
		$resul = Organigramas::findFirstById($_POST['id']);
		$resul->baja_logica = 0;
		$resul->save();
		$this->view->disable();
		echo json_encode();
	}
*/


	public function personalAction($organigrama_id,$gestion='0')
    {
        if ($gestion == 0) {
            $model = new Cargos();
            $gestiones = $model->getGestiones($organigrama_id);
            $gestion = $gestiones[0]->gestion;
        }

        $primer_dependiente = Cargos::findFirst(array("organigrama_id='$organigrama_id' and gestion= '" . $gestion . "' and baja_logica='1'", 'order' => 'id ASC', 'limit' => 1));

        if ($primer_dependiente != FALSE) {
            $model = new Cargos();
            $cargo = $model->listPersonal($organigrama_id, $primer_dependiente->depende_id, $gestion);
            $cont = count($cargo);
            if ($cont > 0) {
                foreach ($cargo as $v) {
                    $this->listarPersonal($v->id, $v->cargo, $v->codigo, $v->estado1, $v->organigrama_id, $gestion);
                    $this->lista .= '</ul>';
                    $config = array();
                }
            } else {
                $this->lista .= '<h3>No existe cargos dentro la oficina..</h3>';
            }
            $this->assets
                ->addCss('/js/jorgchart/jquery.jOrgChartPersonal.css')
                ->addCss('/js/jorgchart/customPersonal.css');
            $this->assets->addJs('/js/jorgchart/jquery.jOrgChart.js');

            $this->view->setVar('lista', $this->lista);


            $this->tag->setDefault("gestion", $gestion);
            $gestiones = $this->tag->select(
                array(
                    'gestion',
                    $model->getGestiones($organigrama_id),
                    'using' => array('gestion', "gestion"),
                    'useEmpty' => FALSE,
                    'emptyText' => '(Selecionar)',
                    'emptyValue' => '',
                    'class' => 'form-control'
                )
            );
            $this->view->setVar('gestiones', $gestiones);
            $this->view->setVar('organigrama_id', $organigrama_id);
        } else {
            echo "No existe cargos dentro de la oficina..";
        }


    }

	public function listarPersonal($id, $cargo, $codigo,$estado,$organigrama_id,$gestion) {
		$h=  Cargos::count("depende_id='$id'");
		$datos_usuario="";
		$nombre="";
		if($estado>0){
			$ci_activo='1';
			$cargo_ci=new Cargos();
			$ci=$cargo_ci->getCI($id);
			foreach ($ci as $v) {
				$ci_activo = $v->ci;
				$nombre = $v->p_nombre.', '.$v->p_apellido.' '.$v->s_apellido;
			}
			$ruta="./images/personal/".$ci_activo.".jpg";
			if (file_exists($ruta)) {
				$datos_usuario = ' <img src="/images/personal/'.$ci_activo.'.jpg" height="50" width="50">';	
			} else {
				$datos_usuario = ' <img src="/images/personal/imagen_comodin.png" title="Adjudicado" height="50" width="50">';	
			}
			$datos_usuario.="<br>".$nombre;

		}else{
			$datos_usuario = ' <img src="/images/personal/imagen_acefalo.jpg" title="ACEFALO" height="50" width="50"><br>ACEFALO';
		}        
		$this->lista.='<li id="org" style="display:none"><span>'.$codigo.'</span><br>'.$cargo.'<br>'.$datos_usuario;
		if ($h > 0) {
            //echo '<ul>';
			$this->lista.='<ul>';
			//$hijos=  Cargos::find(array("depende_id='$id' and baja_logica=1"));
            $model=  new Cargos();
            $hijos = $model->listPersonal($organigrama_id,$id,$gestion);
			foreach ($hijos as $hijo) {
				$cargo = $hijo->cargo;
				$this->listarPersonal($hijo->id, $cargo, $hijo->codigo,$hijo->estado1,$organigrama_id,$gestion);
			}
			$this->lista.='</ul>';
            // echo '</ul>';
		} else {
			$this->lista.='</li>';
            //   echo '</li>';
		}
	}

	public function pruebaAction()
	{
		$model = new Cargos();
		$gestiones = $model->getGestiones(34);
		// $gestiones = Cargos::find(array('organigrama_id = 34','DISTINCT'=>'gestion','order'=>'gestion desc'));
		 echo $gestiones[0]->gestion;
		// foreach ($gestiones as $v) {
		// 	echo $v->gestion."<br>";
		// }
	}


    /**
     * Función para listar los organigramas activos.
     */
    public function listactivesAction()
    {
        $this->view->disable();
        $obj = new Organigramas();
        $organigramas = Array();
        $data = array();
        $resul = $obj->getListadoOrganigramaVigente();
        if (count($resul) > 0) {
            foreach ($resul as $v) {
                $organigramas[] = array(
                    'padre_id' => $v->padre_id,
                    'id' => $v->id,
                    'unidad_administrativa' => $v->unidad_administrativa
                );
            }
        }
        echo json_encode($organigramas);
    }

    /**
     * Función para la obtención del listado de registros de organigramas paginados.
     */
    public function listpagedAction(){
        $this->view->disable();
        $obj = new Organigramas();
        $organigramas = Array();
        $gestion = 0;
        if (isset($_GET["gestion"])) {
            $gestion = $_GET["gestion"];
        }
        $idResolucion = 0;
        if (isset($_GET["id_resolucion"])) {
            $idResolucion = $_GET["id_resolucion"];
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
        if ($gestion >= 0) {
            $resul = $obj->getListadoOrganigramaVigentePorGestion($gestion, $idResolucion, $where, '', $start, $pagesize);
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $total_rows = $v->total_rows;
                    $organigramas[] = array(
                        'nro_row' => 0,
                        'id' => $v->id,
                        'padre_id' => $v->padre_id,
                        'gestion' => $v->gestion,
                        'unidad_administrativa' => $v->unidad_administrativa,
                        'orden' => $v->orden,
                        'codigo' => $v->codigo,
                        'a' => $v->a,
                        'b' => $v->b,
                        'c' => $v->c,
                        'd' => $v->d,
                        'sigla' => $v->sigla,
                        'fecha_ini' => $v->fecha_ini != "" ? date("d-m-Y", strtotime($v->fecha_ini)) : "",
                        'fecha_fin' => $v->fecha_fin != "" ? date("d-m-Y", strtotime($v->fecha_fin)) : "",
                        'id_nivelestructural' => $v->id_nivelestructural,
                        'nivel_estructural' => $v->nivel_estructural,
                        'id_resolucion' => $v->id_resolucion,
                        'tipo_resolucion' => $v->tipo_resolucion,
                        'gestion_res' => $v->gestion_res,
                        'fecha_emi_res' => $v->fecha_emi_res != "" ? date("d-m-Y", strtotime($v->fecha_emi_res)) : "",
                        'fecha_apr_res' => $v->fecha_apr_res != "" ? date("d-m-Y", strtotime($v->fecha_apr_res)) : "",
                        'activo' => $v->activo,
                        'activo_descripcion' => $v->activo_descripcion,
                        'estado' => $v->estado,
                        'estado_descripcion' => $v->estado_descripcion,
                    );
                }
            }

        }
        $data[] = array(
            'TotalRows' => $total_rows,
            'Rows' => $organigramas
        );
        echo json_encode($data);
    }
}
?>