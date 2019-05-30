<?php

class ComponentesController extends ControllerBase
{

    public function initialize() {
        parent::initialize();
    }

    public function indexAction()
	{
		//$resul = Componentes::find(array('activo=true','order' => 'id ASC'));
		$model = new Componentes();
        $resul = $model->lista();
		$this->view->setVar('componente', $resul);	
	}

	public function addAction()
	{
		if ($this->request->isPost()) {
			$resul = new Componentes();
			$resul->fk_normativa = $this->request->getPost('fk_normativa');
			$resul->componente = $this->request->getPost('componente');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/componentes');
			//return $this->forward("products/new");
		}

		$normativa = $this->tag->select(
			array(
				'fk_normativa',
				Normativas::find("activo = 'true'"),
				'using' => array('id', "normativa"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => ''
				)
			);
		$this->view->setVar('normativa',$normativa);

	}

	public function editAction($id)
	{
		$resul = Componentes::findFirstById($id);
		if ($this->request->isPost()) {
			$resul = Componentes::findFirstById($this->request->getPost('id'));
			$resul->fk_normativa = $this->request->getPost('fk_normativa');
			$resul->componente = $this->request->getPost('componente');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			
			$this->response->redirect('/componentes');
		}
		Phalcon\Tag::setDefault("fk_normativa", $resul->fk_normativa);
		$normativa = $this->tag->select(
			array(
				'fk_normativa',
				Normativas::find("activo = 'true'"),
				'using' => array('id', "normativa"),
				'useEmpty' => true,
				'emptyText' => '(Seleccionar)',
				'emptyValue' => ''
				)
			);
		$this->view->setVar('normativa',$normativa);
		$this->view->setVar('componente', $resul);		
		//echo $this->view->render('../componentes/add', array('componente' => 'hola'));
	}

	public function deleteAction($id)
	{
		$resul = Componentes::findFirstById($id);
		$resul->activo = false;
		if ($resul->save()) {
				$this->flashSession->success("Exito: Elimino correctamente el registro...");
			}else{
				$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/componentes');
	}

}

