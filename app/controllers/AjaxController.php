<?php 
/**
* 
*/
class AjaxController extends ControllerBase
{
	
	public function siglaAction($id)
	{
        $this->view->disable();
	    $resul = Organigramas::findFirstById($id);
	    //$data = "Ja se zovem Nedim OmerbegovicFreddy";
	    //echo json_encode($data);
	    echo $resul->sigla;

	}
}
 ?>