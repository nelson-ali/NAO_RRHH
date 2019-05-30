<?php 
/**
* 
*/

class TiposdocumentosController extends ControllerBase
{
	public function initialize() {
		parent::initialize();
	}

	public function indexAction()
	{
		$auth = $this->session->get('auth');
		if (isset($auth['version'])) {
			$version = $auth['version'];
		} else $version = "0.0.0";

		$this->assets
                    ->addCss('/js/datatables/dataTables.bootstrap.css?v=' . $version)
                    ->addCss('/js/jqwidgets/styles/jqx.base.css?v=' . $version)
                    ->addCss('/js/jqwidgets/styles/jqx.blackberry.css?v=' . $version)
                    ->addCss('/js/jqwidgets/styles/jqx.windowsphone.css?v=' . $version)
                    ->addCss('/js/jqwidgets/styles/jqx.blackberry.css?v=' . $version)
                    ->addCss('/js/jqwidgets/styles/jqx.mobile.css?v=' . $version)
                    ->addCss('/js/jqwidgets/styles/jqx.android.css?v=' . $version);

        $this->assets
                    ->addJs('/js/jqwidgets/simulator.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxcore.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxdata.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxbuttons.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxscrollbar.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxdatatable.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxlistbox.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxdropdownlist.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxpanel.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxradiobutton.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxinput.js?v=' . $version)
                    ->addJs('/js/datepicker/bootstrap-datepicker.js?v=' . $version)
                    ->addJs('/js/datatables/dataTables.bootstrap.js?v=' . $version)

                    ->addJs('/js/jqwidgets/jqxmenu.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.filter.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.sort.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxtabs.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.selection.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxcalendar.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxdatetimeinput.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxcheckbox.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.grouping.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.pager.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxnumberinput.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxwindow.js?v=' . $version)
                    ->addJs('/js/jqwidgets/globalization/globalize.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxcombobox.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxexpander.js?v=' . $version)
                    ->addJs('/js/jqwidgets/globalization/globalize.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxvalidator.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxmaskedinput.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxchart.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.columnsresize.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxsplitter.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxtree.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxdata.export.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.export.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxgrid.edit.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxnotification.js?v=' . $version)
                    ->addJs('/js/jqwidgets/jqxbuttongroup.js?v=' . $version)
                    ->addJs('/js/bootbox.js?v=' . $version);

		$condicion = Condiciones::find(array('baja_logica=1','order' => 'id ASC'));
		$this->view->setVar('condicion',$condicion);

		$this->tag->setDefault("sexo", 'I');
		$sexo = $this->tag->selectStatic(
        array(
            "sexo",
            array(
                "M" => "Masculino",
                "F"   => "Femenino",
                "I"   => "Independiente al Sexo"
                ),
            'useEmpty' => false,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
        $this->view->setVar('sexo',$sexo);

        $normativa = $this->tag->select(
			array(
				'tipo_proceso_contratacion',
				Normativasmod::find(array('baja_logica=1',"order"=>"id ASC","columns" => "id,CONCAT(modalidad, ' - ', denominacion) as fullname")),
				//Nivelsalariales::find(array('baja_logica=1','order' => 'id ASC')),
				'using' => array('id', "fullname"),
				'useEmpty' => tue,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control'
				)
			);
        $this->view->setVar('normativa',$normativa);

        $tipo_presentacion = $this->tag->select(
			array(
				'tipopresdoc_id',
				Parametros::find(array('baja_logica=1 and parametro ="tipopresdoc" ','order' => 'nivel ASC')),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control',
				)
			);
		$this->view->setVar('tipo_presentacion',$tipo_presentacion);

		$periodo_presentacion = $this->tag->select(
			array(
				'periodopresdoc_id',
				Parametros::find(array('baja_logica=1 and parametro ="periodopresdoc" ','order' => 'nivel ASC')),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control',
				)
			);
		$this->view->setVar('periodo_presentacion',$periodo_presentacion);

		$persistencia_solicitud = $this->tag->select(
			array(
				'tipoperssoldoc_id',
				Parametros::find(array('baja_logica=1 and parametro ="tipoperssoldoc" ','order' => 'nivel ASC')),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control',
				)
			);
		$this->view->setVar('persistencia_solicitud',$persistencia_solicitud);

		$tipo_emisor = $this->tag->select(
			array(
				'tipoemisordoc_id',
				Parametros::find(array('baja_logica=1 and parametro ="tipoemisordoc" ','order' => 'nivel ASC')),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control',
				)
			);
		$this->view->setVar('tipo_emisor',$tipo_emisor);

		$tipo_fecha_emision = $this->tag->select(
			array(
				'tipofechaemidoc_id',
				Parametros::find(array('baja_logica=1 and parametro ="tipofechaemidoc" ','order' => 'nivel ASC')),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control',
				)
			);
		$this->view->setVar('tipo_fecha_emision',$tipo_fecha_emision);

		$grupo_archivo = $this->tag->select(
			array(
				'grupoarchivos_id',
				Parametros::find(array('baja_logica=1 and parametro ="grupoarchivos" ','order' => 'nivel ASC')),
				'using' => array('id', "valor_1"),
				'useEmpty' => true,
				'emptyText' => '(Selecionar)',
				'emptyValue' => '',
				'class' => 'form-control',
				)
			);
		$this->view->setVar('grupo_archivo',$grupo_archivo);

		$tipo_dato = array(
                "numeric" => "Numerico",
                "string"   => "Texto",
                "date" => "Fecha",
                "boolean" => "Logico"
        );

        $tipo_dato_campo_aux_a = $this->tag->selectStatic(
        array(
            "tipo_dato_campo_aux_a",
            $tipo_dato,
            'useEmpty' => true,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
        $this->view->setVar('tipo_dato_campo_aux_a',$tipo_dato_campo_aux_a);

        $tipo_dato_campo_aux_b = $this->tag->selectStatic(
        array(
            "tipo_dato_campo_aux_b",
            $tipo_dato,
            'useEmpty' => true,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
        $this->view->setVar('tipo_dato_campo_aux_b',$tipo_dato_campo_aux_b);

        $tipo_dato_campo_aux_c = $this->tag->selectStatic(
        array(
            "tipo_dato_campo_aux_c",
            $tipo_dato,
            'useEmpty' => true,
            'emptyText' => '(Selecionar)',
            'emptyValue' => '',
            'class' => 'form-control',
            )
        );
        $this->view->setVar('tipo_dato_campo_aux_c',$tipo_dato_campo_aux_c);

	}

	public function listAction()
	{
		$resul = Tiposdocumentos::find(array('baja_logica=1','order' => 'id ASC'));
		$this->view->disable();
		foreach ($resul as $v) {
			$customers[] = array(
			'id' =>$v->id, 
            'tipo_documento' => $v->tipo_documento, 
            'codigo' => $v->codigo, 
            'tipopresdoc_id' => $v->tipopresdoc_id, 
            'periodopresdoc_id' => $v->periodopresdoc_id, 
            'tipoemisordoc_id' => $v->tipoemisordoc_id, 
            'tipofechaemidoc_id' => $v->tipofechaemidoc_id, 
            'tipoperssoldoc_id' => $v->tipoperssoldoc_id, 
            'ruta_carpeta' => $v->ruta_carpeta, 
            'nombre_carpeta' => $v->nombre_carpeta, 
            'formato_archivo_digital' => $v->formato_archivo_digital, 
            'resolucion_archivo_digital' => $v->resolucion_archivo_digital, 
            'altura_archivo_digital' => $v->altura_archivo_digital, 
            'anchura_archivo_digital' => $v->anchura_archivo_digital, 
            'campo_aux_a' => $v->campo_aux_a, 
            'tipo_dato_campo_aux_a' => $v->tipo_dato_campo_aux_a, 
            'campo_aux_b' => $v->campo_aux_b, 
            'tipo_dato_campo_aux_b' => $v->tipo_dato_campo_aux_b, 
            'campo_aux_c' => $v->campo_aux_c, 
            'tipo_dato_campo_aux_c' => $v->tipo_dato_campo_aux_c, 
            'observacion' => $v->observacion, 
            'estado' => $v->estado, 
            'baja_logica' => $v->baja_logica, 
            'agrupador' => $v->agrupador,
            'user_reg_id' => $v->user_reg_id,
            'fecha_reg' => $v->fecha_reg,
            'user_mod_id' => $v->user_mod_id,
            'fecha_mod' => $v->fecha_mod,
            'grupoarchivos_id' => $v->grupoarchivos_id,
            'sexo' => $v->sexo,
            'tipo_proceso_contratacion' => $v->tipo_proceso_contratacion
				);
		}
		echo json_encode($customers);
	}
}
?>