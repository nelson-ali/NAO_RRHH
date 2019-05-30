<?php 
/**
* 
*/

class SeguimientosController extends ControllerBase
{
	public function initialize() {
        parent::initialize();
    }

	public function indexAction()
	{
		//$resul = Seguimientos::find(array('activo=:activo1:','bind'=>array('activo1'=>'true'),'order' => 'id ASC'));
		$model = new Seguimientos();
        $resul = $model->lista();
		$this->view->setVar('seguimiento', $resul);	
	}

	public function addAction()
	{
		if ($this->request->isPost()) {
			$resul = new Seguimientos();
			$resul->fk_usuario = 1;  //sesion id usuario
			$resul->fk_unidad_organizacional = $this->request->getPost('fk_unidad_organizacional');
			$resul->fk_usuariosolicitud = $this->request->getPost('fk_usuariosolicitud');
			$resul->codigo_proceso = $this->request->getPost('codigo_proceso');
			$resul->codigo_cargo = $this->request->getPost('codigo_cargo');
			$resul->fk_cargo = $this->request->getPost('fk_cargo');
			$resul->vacante = $this->request->getPost('vacante');
			$resul->nro_solicitud = $this->request->getPost('nro_solicitud');
			$resul->f_solicitud = $this->request->getPost('f_solicitud');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/seguimientos');
			//return $this->forward("products/new");
		}

		$oficinatmp = $this->tag->select(
			array(
				'fk_unidad_organizacional',
				Oficinatmp::find(),
				'using' => array('id', "oficina"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('oficina',$oficinatmp);

		$usuario = $this->tag->select(
			array(
				'fk_usuariosolicitud',
				Usuarios::find(),
				'using' => array('id', "nombre"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('usuario',$usuario);

		$cargo = $this->tag->select(
			array(
				'fk_cargo',
				Cargotmp::find(),
				'using' => array('id', "cargo"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('cargo',$cargo);
	}

	public function editAction($id)
	{
		$resul = Seguimientos::findFirstById($id);
		if ($this->request->isPost()) {
			$resul->fk_usuario = 1;  //sesion id usuario
			$resul->fk_unidad_organizacional = $this->request->getPost('fk_unidad_organizacional');
			$resul->fk_usuariosolicitud = $this->request->getPost('fk_usuariosolicitud');
			$resul->codigo_proceso = $this->request->getPost('codigo_proceso');
			$resul->codigo_cargo = $this->request->getPost('codigo_cargo');
			$resul->fk_cargo = $this->request->getPost('fk_cargo');
			$resul->vacante = $this->request->getPost('vacante');
			$resul->nro_solicitud = $this->request->getPost('nro_solicitud');
			$resul->f_solicitud = $this->request->getPost('f_solicitud');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/seguimientos');
		}
		$this->view->setVar('seguimiento', $resul);		

		$this->tag->setDefault("fk_unidad_organizacional", $resul->fk_unidad_organizacional);
		$oficinatmp = $this->tag->select(
			array(
				'fk_unidad_organizacional',
				Oficinatmp::find(),
				'using' => array('id', "oficina"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('oficina',$oficinatmp);

		$this->tag->setDefault("fk_usuariosolicitud", $resul->fk_usuariosolicitud);
		$usuario = $this->tag->select(
			array(
				'fk_usuariosolicitud',
				Usuarios::find(),
				'using' => array('id', "nombre"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('usuario',$usuario);

		$this->tag->setDefault("fk_cargo", $resul->fk_cargo);
		$cargo = $this->tag->select(
			array(
				'fk_cargo',
				Cargotmp::find(),
				'using' => array('id', "cargo"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('cargo',$cargo);

		//$this->tag->setDefault("fk_normativa", $resul->fk_cargo);
		$normativa = $this->tag->select(
			array(
				'fk_normativa',
				Normativas::find(),
				'using' => array('id', "normativa"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('normativa',$normativa);

		$this->tag->setDefault("fk_componente", $resul->fk_componente);
		$componente = $this->tag->select(
			array(
				'fk_componente',
				Componentes::find(),
				'using' => array('id', "componente"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('componente',$componente);

		//$this->tag->setDefault("fk_fuentefinanciamiento", $resul->fk_fuentefinanciamiento);
		$fuente = $this->tag->select(
			array(
				'fk_fuentefinanciamiento',
				fuentefinanciamientos::find(),
				'using' => array('id', "fuente"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('fuente',$fuente);		
		

		$this->tag->setDefault("fk_partida", $resul->fk_partida);
		$partida = $this->tag->select(
			array(
				'fk_partida',
				Partidas::find(),
				'using' => array('id', "partida"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('partida',$partida);
	}

	public function deleteAction($id)
	{
		$resul = Seguimientos::findFirstById($id);
		$resul->activo = false;
		if ($resul->save()) {
				$this->flashSession->success("Exito: Elimino correctamente el registro...");
			}else{
				$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/seguimientos');
	}

}
?>