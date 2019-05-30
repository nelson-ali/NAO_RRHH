<?php

class NormativasController extends ControllerBase
{

    public function initialize() {
        parent::initialize();
    }

    public function indexAction()
	{
		$resul = Normativas::find(array('activo=true','order' => 'id ASC'));
		$this->view->setVar('normativa', $resul);	
	}

	public function addAction()
	{
		if ($this->request->isPost()) {
			$resul = new Normativas();
			$resul->normativa = $this->request->getPost('normativa');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/normativas');
			//return $this->forward("products/new");
		}

	}

	public function editAction($id)
	{
		$resul = Normativas::findFirstById($id);
		if ($this->request->isPost()) {
			$resul = Normativas::findFirstById($this->request->getPost('id'));
			$resul->normativa = $this->request->getPost('normativa');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			
			$this->response->redirect('/normativas');
		}
		$this->view->setVar('normativa', $resul);		
		//echo $this->view->render('../normativas/add', array('normativa' => 'hola'));
	}

	public function deleteAction($id)
	{
		$resul = Normativas::findFirstById($id);
		$resul->activo = false;
		if ($resul->save()) {
				$this->flashSession->success("Exito: Elimino correctamente el registro...");
			}else{
				$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/normativas');
	}


}

