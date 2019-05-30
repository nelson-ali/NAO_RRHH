<?php 
/**
* 
*/

class PartidasController extends ControllerBase
{
	public function initialize() {
        parent::initialize();
    }

	public function indexAction()
	{
		$resul = Partidas::find(array('activo=:activo1:','bind'=>array('activo1'=>'true'),'order' => 'id ASC'));
		$this->view->setVar('partida', $resul);	
	}

	public function addAction()
	{
		if ($this->request->isPost()) {
			$resul = new Partidas();
			$resul->codigo = $this->request->getPost('codigo');
			$resul->partida = $this->request->getPost('partida');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			$this->response->redirect('/partidas');
			//return $this->forward("products/new");
		}

	}

	public function editAction($id)
	{
		$resul = Partidas::findFirstById($id);
		if ($this->request->isPost()) {
			$resul = Partidas::findFirstById($this->request->getPost('id'));
			$resul->codigo = $this->request->getPost('codigo');
			$resul->partida = $this->request->getPost('partida');
			$resul->descripcion = $this->request->getPost('descripcion');
			$resul->activo = true;
			if ($resul->save()) {
				$this->flashSession->success("Exito: Registro guardado correctamente...");
			}else{
				$this->flashSession->error("Error: no se guardo el registro...");
			}
			
			$this->response->redirect('/partidas');
		}
		$this->view->setVar('partida', $resul);		
		//echo $this->view->render('../partidas/add', array('partida' => 'hola'));
	}

	public function deleteAction($id)
	{
		$resul = Partidas::findFirstById($id);
		$resul->activo = false;
		if ($resul->save()) {
				$this->flashSession->success("Exito: Elimino correctamente el registro...");
			}else{
				$this->flashSession->error("Error: no se elimino ningun registro...");
		}
		$this->response->redirect('/partidas');
	}

}
?>