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
		$resul = fuentefinanciamientos::find(array('activo=:activo1:','bind'=>array('activo1'=>'true'),'order' => 'id ASC'));
		$this->view->setVar('fuente', $resul);	
	}

	public function addAction()
	{
		if ($this->request->isPost()) {
			$resul = new fuentefinanciamientos();
			$resul->codigo = $this->request->getPost('codigo');
			$resul->fuente = $this->request->getPost('fuente');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/fuentefinanciamientos');
			//return $this->forward("products/new");
		}

	}

	public function editAction($id)
	{
		$resul = fuentefinanciamientos::findFirstById($id);
		if ($this->request->isPost()) {
			$resul = Fuentefinanciamientos::findFirstById($this->request->getPost('id'));
			$resul->codigo = $this->request->getPost('codigo');
			$resul->fuente = $this->request->getPost('fuente');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			
			$this->response->redirect('/fuentefinanciamientos');
		}
		$this->view->setVar('fuente', $resul);		
		//echo $this->view->render('../fuentes/add', array('fuente' => 'hola'));
	}

	public function deleteAction($id)
	{
		$resul = fuentefinanciamientos::findFirstById($id);
		$resul->activo = false;
		if ($resul->save()) {
				$this->flashSession->success("Exito: Elimino correctamente el registro...");
			}else{
				$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/fuentefinanciamientos');
	}

}
?>