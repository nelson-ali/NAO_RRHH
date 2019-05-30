<?php 
/**
* 
*/

class CargosperfilesController extends ControllerBase
{
	public function initialize() {
		parent::initialize();
	}

	public function indexAction($nivelsalarial_id)
	{
		$this->assets
                    ->addCss('/js/datatables/dataTables.bootstrap.css')
                    ->addCss('/js/jqwidgets/styles/jqx.base.css')
                    ->addCss('/js/jqwidgets/styles/jqx.blackberry.css')
                    ->addCss('/js/jqwidgets/styles/jqx.windowsphone.css')
                    ->addCss('/js/jqwidgets/styles/jqx.blackberry.css')
                    ->addCss('/js/jqwidgets/styles/jqx.mobile.css')
                    ->addCss('/js/jqwidgets/styles/jqx.android.css');

        $this->assets
                    ->addJs('/js/jqwidgets/simulator.js')
                    ->addJs('/js/jqwidgets/jqxcore.js')
                    ->addJs('/js/jqwidgets/jqxdata.js')
                    ->addJs('/js/jqwidgets/jqxbuttons.js')
                    ->addJs('/js/jqwidgets/jqxscrollbar.js')
                    ->addJs('/js/jqwidgets/jqxdatatable.js')
                    ->addJs('/js/jqwidgets/jqxlistbox.js')
                    ->addJs('/js/jqwidgets/jqxdropdownlist.js')
                    ->addJs('/js/jqwidgets/jqxpanel.js')
                    ->addJs('/js/jqwidgets/jqxradiobutton.js')
                    ->addJs('/js/jqwidgets/jqxinput.js')
                    ->addJs('/js/datepicker/bootstrap-datepicker.js')
                    ->addJs('/js/datatables/dataTables.bootstrap.js')

                    ->addJs('/js/jqwidgets/jqxmenu.js')
                    ->addJs('/js/jqwidgets/jqxgrid.js')
                    ->addJs('/js/jqwidgets/jqxgrid.filter.js')
                    ->addJs('/js/jqwidgets/jqxgrid.sort.js')
                    ->addJs('/js/jqwidgets/jqxtabs.js')
                    ->addJs('/js/jqwidgets/jqxgrid.selection.js')
                    ->addJs('/js/jqwidgets/jqxcalendar.js')
                    ->addJs('/js/jqwidgets/jqxdatetimeinput.js')
                    ->addJs('/js/jqwidgets/jqxcheckbox.js')
                    ->addJs('/js/jqwidgets/jqxgrid.grouping.js')
                    ->addJs('/js/jqwidgets/jqxgrid.pager.js')
                    ->addJs('/js/jqwidgets/jqxnumberinput.js')
                    ->addJs('/js/jqwidgets/jqxwindow.js')
                    ->addJs('/js/jqwidgets/globalization/globalize.js')
                    ->addJs('/js/jqwidgets/jqxcombobox.js')
                    ->addJs('/js/jqwidgets/jqxexpander.js')
                    ->addJs('/js/jqwidgets/globalization/globalize.js')
                    ->addJs('/js/jqwidgets/jqxvalidator.js')
                    ->addJs('/js/jqwidgets/jqxmaskedinput.js')
                    ->addJs('/js/jqwidgets/jqxchart.js')
                    ->addJs('/js/jqwidgets/jqxgrid.columnsresize.js')
                    ->addJs('/js/jqwidgets/jqxsplitter.js')
                    ->addJs('/js/jqwidgets/jqxtree.js')
                    ->addJs('/js/jqwidgets/jqxdata.export.js')
                    ->addJs('/js/jqwidgets/jqxgrid.export.js')
                    ->addJs('/js/jqwidgets/jqxgrid.edit.js')
                    ->addJs('/js/jqwidgets/jqxnotification.js')
                    ->addJs('/js/jqwidgets/jqxbuttongroup.js')
                    ->addJs('/js/bootbox.js');

		$formacion_academica = $this->tag->select(
			array(
				'formacion_academica_id',
				//Resoluciones::find(array('baja_logica=1',"order"=>"tipo_resolucion","columns" => "id,CONCAT(tipo_resolucion, ' - ', numero_res) as fullname")),
				Parametros::find(array("baja_logica=1 and parametro='formacion_academica' and agrupador=0","order"=>"nivel ASC")),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('formacion_academica',$formacion_academica);

		$documento = $this->tag->select(
			array(
				'documento_id',
				Parametros::find(array("baja_logica=1 and parametro='tipo_documento'","order"=>"nivel ASC")),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '0',
				'class' => 'form-control'
				)
			);
		$this->view->setVar('documento',$documento);

		$aniomes_array=array(
                "Años" => "Años",
                "Meses"   => "Meses"
                ); 
		$exp_general_aniomes = $this->tag->selectStatic(
        array(
            "exp_general_aniomes",
            $aniomes_array,
            'useEmpty' => false,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
		$this->view->setVar('exp_general_aniomes',$exp_general_aniomes);        

		$exp_profesional_aniomes = $this->tag->selectStatic(
        array(
            "exp_profesional_aniomes",
            $aniomes_array,
            'useEmpty' => false,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
		$this->view->setVar('exp_profesional_aniomes',$exp_profesional_aniomes);        

		$exp_relacionado_aniomes = $this->tag->selectStatic(
        array(
            "exp_relacionado_aniomes",
            $aniomes_array,
            'useEmpty' => false,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
		$this->view->setVar('exp_relacionado_aniomes',$exp_relacionado_aniomes);

		$area_sustantiva = $this->tag->selectStatic(
        array(
            "area_sustantiva",
            array(
                "1" => "Area Sustantiva o Misional",
                "0"   => "Area no Sustantiva"
                ),
            'useEmpty' => false,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
		$this->view->setVar('area_sustantiva',$area_sustantiva);        
		//$this->view->setVar('nivelsalarial_id',$nivelsalarial_id);
		$resul = Nivelsalariales::findFirstById($nivelsalarial_id);
		$this->view->setVar('nivelsalarial',$resul);

	}

	public function listAction($nivelsalarial_id)
	{
		//$resul = Nivelsalariales::find(array('baja_logica=:activo1:','bind'=>array('activo1'=>'1'),'order' => 'id ASC'));
		$model = new Cargosperfiles();
        $resul = $model->lista($nivelsalarial_id);

		$this->view->disable();
		foreach ($resul as $v) {
			$customers[] = array(
				'id' => $v->id,
				'nivelsalarial_id' => $v->nivelsalarial_id,
				'formacion_academica_id' => $v->formacion_academica_id,
				'formacion_academica' => $v->formacion_academica,
				'documento_id' => $v->documento_id,
				'documento' => $v->documento,
				'exp_general' => $v->exp_general,
				'exp_general_aniomes' => $v->exp_general_aniomes,
				'exp_general_text' => $v->exp_general_text,
				'exp_profesional' => $v->exp_profesional,
				'exp_profesional_aniomes' => $v->exp_profesional_aniomes,
				'exp_profesional_text' => $v->exp_profesional_text,
				'exp_relacionado' => $v->exp_relacionado,
				'exp_relacionado_aniomes' => $v->exp_relacionado_aniomes,
				'exp_relacionado_text' => $v->exp_relacionado_text,
				'detalle' => $v->detalle,
				'prioridad' => $v->prioridad,
				'area_sustantiva' => $v->area_sustantiva,
				'area_sustantiva_text' => $v->area_sustantiva_text,
				);
		}
		echo json_encode($customers);
	}

	public function saveAction()
	{
		if (isset($_POST['id'])) {
			
			if ($_POST['id']>0) {
				$resul = Cargosperfiles::findFirstById($_POST['id']);
				//$resul->nivelsalarial_id = $_POST['nivelsalarial_id'];
				$resul->formacion_academica_id = $_POST['formacion_academica_id'];
				$resul->documento_id = $_POST['documento_id'];
				$resul->exp_general = $_POST['exp_general'];
				$resul->exp_general_aniomes = $_POST['exp_general_aniomes'];
				$resul->exp_profesional = $_POST['exp_profesional'];
				$resul->exp_profesional_aniomes = $_POST['exp_profesional_aniomes'];
				$resul->exp_relacionado = $_POST['exp_relacionado'];
				$resul->exp_relacionado_aniomes = $_POST['exp_relacionado_aniomes'];
				$resul->prioridad = $_POST['prioridad'];
				$resul->detalle = $_POST['detalle'];
				$resul->area_sustantiva = $_POST['area_sustantiva'];
				if ($resul->save()) {
				$msm = 'Exito: Se guardo correctamente';
				}else{
				$msm = 'Error: No se guardo el registro';
				}
			}
			else{
				$resul = new Cargosperfiles();
				$resul->nivelsalarial_id = $_POST['nivelsalarial_id'];
				$resul->formacion_academica_id = $_POST['formacion_academica_id'];
				$resul->documento_id = $_POST['documento_id'];
				$resul->exp_general = $_POST['exp_general'];
				$resul->exp_general_aniomes = $_POST['exp_general_aniomes'];
				$resul->exp_profesional = $_POST['exp_profesional'];
				$resul->exp_profesional_aniomes = $_POST['exp_profesional_aniomes'];
				$resul->exp_relacionado = $_POST['exp_relacionado'];
				$resul->exp_relacionado_aniomes = $_POST['exp_relacionado_aniomes'];
				$resul->prioridad = $_POST['prioridad'];
				$resul->detalle = $_POST['detalle'];
				$resul->area_sustantiva = $_POST['area_sustantiva'];
				$resul->estado = 0;
				$resul->baja_logica = 1;
				$resul->save();
				if ($resul->save()) {
				$msm = 'Exito: Se guardo correctamente';
				}else{
				$msm = 'Error: No se guardo el registro';
				}
				
			}

	}
	$this->view->disable();
	echo json_encode();
}

public function deleteAction(){
	$resul = Cargosperfiles::findFirstById($_POST['id']);
	$resul->baja_logica = 0;
	$resul->save();
	$this->view->disable();
	echo json_encode();
}





}
?>