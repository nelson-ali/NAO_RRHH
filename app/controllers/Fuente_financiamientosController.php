<?php 
/**
* 
*/

class FuentefinanciamientosController extends ControllerBase
{
	public function initialize() {
        parent::initialize();
    }

	public function indexAction()
	{
		$resul = Fuentefinanciamientos::find(array('activo=:activo1:','bind'=>array('activo1'=>'true'),'order' => 'id ASC'));
		$this->view->setVar('fuente', $resul);	
	}

	public function addAction()
	{
		if ($this->request->isPost()) {
			$resul = new Procesos();
			$resul->proceso = $this->request->getPost('proceso');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/procesos');
			//return $this->forward("products/new");
		}

	}

	public function editAction($id)
	{
		$resul = Procesos::findFirstById($id);
		if ($this->request->isPost()) {
			$resul = Procesos::findFirstById($this->request->getPost('id'));
			$resul->proceso = $this->request->getPost('proceso');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			
			$this->response->redirect('/procesos');
		}
		$this->view->setVar('proceso', $resul);		
		//echo $this->view->render('../procesos/add', array('proceso' => 'hola'));
	}

	public function deleteAction($id)
	{
		$resul = Procesos::findFirstById($id);
		$resul->activo = false;
		if ($resul->save()) {
				$this->flashSession->success("Exito: Elimino correctamente el registro...");
			}else{
				$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/procesos');
	}

}
?>