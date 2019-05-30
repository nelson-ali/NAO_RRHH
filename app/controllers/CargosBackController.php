<?php 
/**
* 
*/

class CargosController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        $this->assets
            ->addCss('/js/jqwidgets/styles/jqx.base.css')
            ;

        $this->assets
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
            ->addJs('/js/jqwidgets/jqxgrid.js')
            ->addJs('/js/jqwidgets/jqxgrid.filter.js')
            ->addJs('/js/jqwidgets/jqxgrid.sort.js')
            ->addJs('/js/jqwidgets/jqxgrid.selection.js')
            ->addJs('/js/jqwidgets/jqxcalendar.js')
            ->addJs('/js/jqwidgets/jqxdatetimeinput.js')
            ->addJs('/js/jqwidgets/jqxcheckbox.js')
            ->addJs('/js/jqwidgets/jqxgrid.grouping.js')
            ->addJs('/js/jqwidgets/jqxgrid.pager.js')
            ->addJs('/js/jqwidgets/jqxnumberinput.js')
            ->addJs('/js/jqwidgets/jqxexpander.js')
            ->addJs('/js/jqwidgets/jqxgrid.columnsresize.js')
            ->addJs('/js/jqwidgets/jqxsplitter.js')
            ->addJs('/js/bootbox.js');

        $model = new Cargos();
        $resul = $model->listGerencias();
        $gerencia = $this->tag->select(
            array(
                'gerencia_id',
                $resul,
                'using' => array('id', "unidad_administrativa"),
                'useEmpty' => true,
                'emptyText' => '(Selecionar)',
                'emptyValue' => '',
                'class' => 'form-control',
            )
        );
        $this->view->setVar('gerencia', $gerencia);


        $organigrama_rep_pac = $this->tag->select(
            array(
                'organigrama_id_rep_pac',
                Organigramas::find(array('baja_logica=1', 'order' => 'unidad_administrativa ASC')),
                'using' => array('id', "unidad_administrativa"),
                'useEmpty' => true,
                'emptyText' => '(Selecionar)',
                'emptyValue' => '',
                'class' => 'form-control',
            )
        );
        $this->view->setVar('organigrama_rep_pac', $organigrama_rep_pac);

        $finpartida = $this->tag->select(
            array(
                'fin_partida_id',
                Finpartidas::find(array('baja_logica=1 and agrupador=1', 'order' => 'id ASC')),
                'using' => array('id', "denominacion"),
                'useEmpty' => true,
                'emptyText' => '(Selecionar)',
                'emptyValue' => '0',
                'class' => 'form-control'
            )
        );
        $this->view->setVar('finpartida', $finpartida);

        $model = new Nivelsalariales();
        $resul = $model->listaSelect();
        $nivelsalarial = $this->tag->select(
            array(
                'codigo_nivel',
                $resul,
                'using' => array('id', "opcion"),
                'useEmpty' => true,
                'emptyText' => '(Selecionar)',
                'emptyValue' => '',
                'nivel' => 'nivel',
                'class' => 'form-control'
            )
        );

        $this->view->setVar('nivelsalarial', $nivelsalarial);

        $condicion = $this->tag->select(
            array(
                'condicion_id',
                Condiciones::find(array('baja_logica=1', 'order' => 'id ASC')),
                'using' => array('id', "condicion"),
                'useEmpty' => true,
                'emptyText' => '(Selecionar)',
                'emptyValue' => '',
                'class' => 'form-control',
            )
        );
        $this->view->setVar('condicion', $condicion);


        $resolucion_ministerial0 = Resoluciones::findFirst(array("activo=1 and baja_logica=1"));
        $this->view->setVar('tipo_resolucion', $resolucion_ministerial0->tipo_resolucion);
        $this->view->setVar('resolucion_ministerial_id', $resolucion_ministerial0->id);

        $this->tag->setDefault("resolucion_ministerial_id", $resolucion_ministerial0->id);
        $resolucion_ministerial = $this->tag->select(
            array(
                'resolucion_ministerial_id',
                Resoluciones::find(array('uso=1 and baja_logica=1', "order" => "id ASC")),
                'using' => array('id', "tipo_resolucion"),
                'useEmpty' => FALSE,
                'emptyText' => '(Selecionar)',
                'emptyValue' => '',
                'class' => 'form-control'
            )
        );

        $this->view->setVar('resolucion_ministerial', $resolucion_ministerial);


        $resolucion_ministerial_escala = $this->tag->select(
            array(
                'resolucion_escala_id',
                Resoluciones::find(array('uso=2 and baja_logica=1', "order" => "id ASC")),
                'using' => array('id', "tipo_resolucion"),
                'useEmpty' => true,
                'emptyText' => '(Selecionar)',
                'emptyValue' => '',
                'class' => 'form-control',
            )
        );

        $this->view->setVar('resolucion_ministerial_escala', $resolucion_ministerial_escala);

    }

    public function listAction()
    {
        //$resul = Cargos::find(array('baja_logica=:activo1:','bind'=>array('activo1'=>'1'),'order' => 'id ASC'));
        $model = new Cargos();
        $resul = $model->lista();
        $this->view->disable();
        foreach ($resul as $v) {
            $customers[] = array(
                'id' => $v->id,
                'resolucion_ministerial_id' => $v->resolucion_ministerial_id,
                'tipo_resolucion' => $v->tipo_resolucion,
                'unidad_administrativa' => $v->unidad_administrativa,
                'organigrama_id' => $v->organigrama_id,
                'codigo_nivel' => $v->codigo_nivel,
                'nivelsalarial_id' => $v->nivelsalarial_id,
                'codigo' => $v->codigo,
                'ordenador' => $v->ordenador,
                'cargo' => $v->cargo,
                'denominacion' => $v->denominacion,
                'sueldo' => intval($v->sueldo),
                'depende_id' => $v->depende_id,
                'estado' => $v->estado,
                'condicion' => $v->condicion,
                'fin_partida_id' => $v->fin_partida_id,
                'partida' => $v->partida,
                'fuente_codigo' => $v->fuente_codigo,
                'fuente' => $v->fuente,
                'organismo_codigo' => $v->organismo_codigo,
                'organismo' => $v->organismo,
                'asistente' => $v->asistente,
                'jefe' => $v->jefe,
                'gestion' => $v->gestion,
            );
        }
        echo json_encode($customers);
    }

    public function listorganigramaAction()
    {
        $resul = Organigramas::find(array('baja_logica=1', 'order' => 'id ASC'));
        foreach ($resul as $v) {
            $customers[] = array(
                'id' => $v->id,
                'unidad_administrativa' => $v->unidad_administrativa,
                'sigla' => $v->sigla
            );
        }
        $this->view->disable();
        echo json_encode($customers);
    }

    public function listnivelsalarialAction()
    {
        $resul = Nivelsalariales::find(array('baja_logica=1 and activo=1', 'order' => 'id ASC'));
        foreach ($resul as $v) {
            $customers[] = array(
                'id' => $v->id,
                'denominacion' => $v->denominacion,
                'sueldo' => $v->sueldo
            );
        }
        $this->view->disable();
        echo json_encode($customers);
    }

    public function listfinpartidaAction()
    {
        $resul = Finpartidas::find(array('baja_logica=1', 'order' => 'id ASC'));
        foreach ($resul as $v) {
            $customers[] = array(
                'id' => $v->id,
                'denominacion' => $v->denominacion,
                'partida' => $v->partida
            );
        }
        $this->view->disable();
        echo json_encode($customers);
    }

    public function getSueldoAction()
    {
        $resul = Nivelsalariales::findFirstById($_POST['id']);
        $datos = array(
            'sueldo' => floatval($resul->sueldo),
            'nivel' => $resul->nivel,
        );
        $this->view->disable();
        echo json_encode($datos);
    }

    public function getGestionAction()
    {
        $resul = new Cargos();
        $model = $resul->getGestion($_POST['fin_partida_id']);
        $datos = array(
            'gestion' => $model[0]->gestion
        );
        $this->view->disable();
        echo json_encode($datos);
    }

    public function saveAction()
    {
        if (isset($_POST['id'])) {
            $auth = $this->session->get('auth');
            if ($_POST['id'] > 0) {
                $resul = Cargos::findFirstById($_POST['id']);
                $resul->organigrama_id = $_POST['organigrama_id'];
                if ($_POST['depende_id'] != "") {
                    $resul->depende_id = $_POST['depende_id'];
                } else {
                    $resul->depende_id = 0;
                }
                $resul->codigo = $_POST['codigo'];
                $resul->ordenador = $_POST['ordenador'];
                $resul->cargo = $_POST['cargo'];
                $resul->codigo_nivel = $_POST['nivel'];
                $resul->nivelsalarial_id = $_POST['codigo_nivel'];
                $resul->fin_partida_id = $_POST['fin_partida_id'];
                $resul->user_mod_id = $auth['id'];
                $resul->fecha_mod = date("Y-m-d H:i:s");
                $resul->formacion_requerida = $_POST['formacion_requerida'];
                $resul->asistente = $_POST['asistente'];
                $resul->jefe = $_POST['jefe'];
                $resul->resolucion_ministerial_id = $_POST['resolucion_ministerial_id'];
                $resul->gestion = $_POST['gestion_fp'];
                $resul->save();
            } else {
                $resul = new Cargos();
                $resul->organigrama_id = $_POST['organigrama_id'];
                if ($_POST['depende_id'] != "") {
                    $resul->depende_id = $_POST['depende_id'];
                } else {
                    $resul->depende_id = 0;
                }

                $resul->ejecutora_id = 1;
                $resul->codigo = $_POST['codigo'];
                $resul->ordenador = $_POST['ordenador'];
                $resul->cargo = $_POST['cargo'];
                $resul->codigo_nivel = $_POST['nivel'];
                $resul->nivelsalarial_id = $_POST['codigo_nivel'];
                $resul->cargo_estado_id = 1;
                $resul->estado = 0;
                $resul->baja_logica = 1;
                $resul->user_reg_id = $auth['id'];
                $resul->fecha_reg = date("Y-m-d H:i:s");
                $resul->fin_partida_id = $_POST['fin_partida_id'];
                $resul->asistente = $_POST['asistente'];
                $resul->jefe = $_POST['jefe'];
                $resul->poa_id = 1;
                $resul->formacion_requerida = $_POST['formacion_requerida'];
                $resul->resolucion_ministerial_id = $_POST['resolucion_ministerial_id'];
                $resul->gestion = $_POST['gestion_fp'];
                if ($resul->save()) {
                    $msm = array('msm' => 'Exito: Se guardo correctamente');
                } else {
                    $msm = array('msm' => 'Error: No se guardo el registro');
                }
            }
        }
        $this->view->disable();
        echo json_encode();
    }

    public function save_pacAction()
    {
        if (isset($_POST['cargo_id_pac'])) {
            // $date = new DateTime($_POST['fecha_ini']);
            // $fecha_ini = $date->format('Y-m-d');
            // $date = new DateTime($_POST['fecha_fin']);
            // $fecha_fin = $date->format('Y-m-d');

            $fecha_ini = date("Y-m-d", strtotime($_POST['fecha_ini']));
            $fecha_fin = date("Y-m-d", strtotime($_POST['fecha_fin']));

            if ($_POST['cargo_id_pac'] > 0) {
                $resul = new Pacs();
                $resul->cargo_id = $_POST['cargo_id_pac'];
                $resul->gestion = $_POST['gestion'];
                $resul->fecha_ini = $fecha_ini;
                $resul->fecha_fin = $fecha_fin; //generar
                $resul->unidad_sol_id = 1;
                $resul->usuario_sol_id = 1;
                //$resul->fecha_apr=date('Y-m-d');
                //$resul->usuario_apr_id=1;

                $resul->estado = 1;
                $resul->baja_logica = 1;
                if ($resul->save()) {
                    $msm = 'Exito: Se guardo correctamente';
                } else {
                    $msm = 'Error: No se guardo el registro';
                }
            }
        }
        $this->view->disable();
        echo json_encode($msm);
    }

    public function deleteAction()
    {
        $resul = Cargos::findFirstById($_POST['id']);
        $resul->user_mod_id = $auth['id'];
        $resul->fecha_mod = date("Y-m-d H:i:s");
        $resul->baja_logica = 0;
        $resul->save();
        $this->view->disable();
        echo json_encode();
    }


    public function listpacAction()
    {
        //$resul = Cargos::find(array('baja_logica=:activo1:','bind'=>array('activo1'=>'1'),'order' => 'id ASC'));
        $model = new Cargos();
        $resul = $model->listapac();
        $this->view->disable();
        foreach ($resul as $v) {
            $customers[] = array(
                'nro' => $v->nro,
                'id' => $v->id,
                'tipo_resolucion' => $v->tipo_resolucion,
                'unidad_administrativa' => $v->unidad_administrativa,
                'codigo' => $v->codigo,
                'cargo' => $v->cargo,
                'gestion' => $v->gestion,
                'estado' => $v->estado,
                'fecha_ini' => date("d-m-Y", strtotime($v->fecha_ini)),
                'fecha_fin' => date("d-m-Y", strtotime($v->fecha_fin))
            );
        }
        echo json_encode($customers);
    }

    public function listdependeAction()
    {
        if (isset($_GET['organigrama_id'])) {
            $resul = Cargos::find(array('baja_logica=1 and organigrama_id=' . $_GET['organigrama_id'], 'order' => 'id ASC'));
            $this->view->disable();
            foreach ($resul as $v) {
                $customers[] = array(
                    'id' => $v->id,
                    'organigrama' => $v->organigrama_id,
                    'codigo' => $v->codigo,
                    'cargo' => $v->cargo
                );
            }
        }

        echo json_encode($customers);
    }

    public function listPersonalAction($organigrama_id)
    {
        $options = '';

        $model = new Cargos();
        $resul = $model->getPersonalOrganigrama($organigrama_id);
        $this->view->disable();
        $options = '<option value="">(Seleccionar)</option>';
        foreach ($resul as $v) {
            $options .= '<option value="' . $v->id . '" nombre="' . $v->nombre . '" cargo="' . $v->cargo . '">' . $v->cargo . ' - ' . $v->nombre . '</option>';
        }


        echo $options;
    }

    public function delete_pacAction()
    {
        $resul = Pacs::findFirstById($_POST['id']);
        $resul->baja_logica = 0;
        $resul->save();
        $this->view->disable();
        echo json_encode();
    }

    public function getEstadoSeguimientoAction()
    {
        $model = new Cargos();
        $resul = $model->getEstadoSeguimiento($_POST['cargo_id']);
        $this->view->disable();
        $estado = null;
        foreach ($resul as $v) {
            $estado = $v->estado1;
        }
        echo json_encode($estado);
    }

// public function exportarPdfAction()
// {
// 		//$pdf = new fpdf();
// 	$pdf = new pdfoasis('L','mm','Letter');
// 	$pdf->pageWidth=280;
// 	$pdf->AddPage();
// 	//$title = utf8_decode('Reporte de Cargos');
// 	$pdf->debug=0;
// 	$pdf->title_rpt = utf8_decode('Reporte de Cargos');
// 	$pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
// 	$pdf->SetFont('Arial','B',14);
// 	$pdf->SetXY(110, 28);
// 	$pdf->Cell(0,0,"REPORTE DE CARGOS");
// 	$miCabecera = array('Nro', 'Organigrama', 'Item', 'Cargo','Sueldo','Estado','Tipo Cargo');

// 	$pdf->SetXY(10, 35);
// 	$pdf->SetFont('Arial','B',10);
// 	$pdf->SetFillColor(52, 151, 219);//Fondo verde de celda
// 	$pdf->SetTextColor(240, 255, 240); //Letra color blanco
// 			$pdf->Cell(10,7, 'Nro',1, 0 , 'L', true );
// 			$pdf->Cell(80,7, 'Organigrama',1, 0 , 'L', true );
// 			$pdf->Cell(15,7, 'Item',1, 0 , 'L', true);
// 			$pdf->Cell(80,7, 'Cargo',1, 0 , 'L', true );
// 			$pdf->Cell(20,7, 'Sueldo',1, 0 , 'L', true );
// 			$pdf->Cell(20,7, 'Estado',1, 0 , 'L', true );
// 			$pdf->Cell(20,7, 'Tipo Cargo',1, 0 , 'L', true );
// 	// foreach($miCabecera as $fila)
// 	// 	{
// 	// 	    //Atención!! el parámetro true rellena la celda con el color elegido
// 	// 		$pdf->Cell(24,7, utf8_decode($fila),1, 0 , 'L', true);
// 	// 	}
// 		$pdf->SetXY(10,42);
// 		$pdf->SetFont('Arial','',7);
// 		$pdf->SetFillColor(229, 229, 229); //Gris tenue de cada fila
// 		$pdf->SetTextColor(3, 3, 3); //Color del texto: Negro
// 		$bandera = false; //Para alternar el relleno
// 		$model = new Cargos();
// 		$resul = $model->lista($_POST['organigrama_id'],$_POST['estado_rep'],$_POST['condicion_id']);
// 		foreach ($resul as $v) {
// 			$pdf->Cell(10,7, utf8_decode($v->nro),1, 0 , 'L', $bandera );
// 			$pdf->Cell(80,7, utf8_decode($v->unidad_administrativa),1, 0 , 'L', $bandera );
// 			$pdf->Cell(15,7, utf8_decode($v->codigo),1, 0 , 'L', $bandera );
// 			$pdf->Cell(80,7, utf8_decode($v->cargo),1, 0 , 'L', $bandera );
// 			$pdf->Cell(20,7, utf8_decode($v->sueldo),1, 0 , 'L', $bandera );
// 			$pdf->Cell(20,7, utf8_decode($v->estado1),1, 0 , 'L', $bandera );
// 			$pdf->Cell(20,7, utf8_decode($v->condicion),1, 0 , 'L', $bandera );
// 		    $pdf->Ln();//Salto de línea para generar otra fila
// 		    $bandera = !$bandera;//Alterna el valor de la bandera
// 		}
// 		$pdf->Output();
// 		$this->view->disable();
    public function exportarExcelAction($n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 7, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'tipo_resolucion' => array('title' => 'Resolución', 'width' => 40, 'align' => 'C', 'type' => 'varchar'),
            'unidad_administrativa' => array('title' => 'Organigrama', 'width' => 35, 'align' => 'C', 'type' => 'varchar'),
            'denominacion' => array('title' => 'Denominación', 'width' => 30, 'align' => 'C', 'type' => 'varchar'),
            'ordenador' => array('title' => 'Ordenador', 'width' => 10, 'align' => 'L', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 35, 'align' => 'C', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Sueldo Bs.', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'codigo' => array('title' => 'Item', 'width' => 10, 'align' => 'L', 'type' => 'varchar'),
            'estado' => array('title' => 'Estado', 'width' => 20, 'align' => 'C', 'type' => 'bpchar'),
            'condicion' => array('title' => 'Tipo Cargo', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'partida' => array('title' => 'Partida', 'width' => 15, 'align' => 'L', 'type' => 'int4'),
            'fuente_codigo' => array('title' => 'Fuente Codigo', 'width' => 15, 'align' => 'L', 'type' => 'int4'),
            'fuente' => array('title' => 'Fuente', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'organismo_codigo' => array('title' => 'Organismo Codigo', 'width' => 20, 'align' => 'C', 'type' => 'int4'),
            'organismo' => array('title' => 'Organismo', 'width' => 20, 'align' => 'C', 'type' => 'varchar')
        );

        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            $excel = new exceloasis();
            $excel->tableWidth = $ancho;
            #region Proceso de generación del documento Excel
            $excel->debug = 0;
            $excel->title_rpt = utf8_decode('Reporte de Cargos');
            $excel->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $excel->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $excel->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $excel->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $excel->DefineTitleAligns(count($colTitleSelecteds));
            $formatTypes = $excel->DefineTypeCols($generalConfigForAllColumns, $columns, $agruparPor);
            $gruposSeleccionadosActuales = $excel->DefineDefaultValuesForGroups($groups);
            $excel->generalConfigForAllColumns = $generalConfigForAllColumns;
            $excel->colTitleSelecteds = $colTitleSelecteds;
            $excel->widthsSelecteds = $widthsSelecteds;
            $excel->alignSelecteds = $alignSelecteds;
            $excel->alignTitleSelecteds = $alignTitleSelecteds;

            $cantCol = count($colTitleSelecteds);
            $excel->ultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 1];
            $excel->penultimaLetraCabeceraTabla = $excel->columnasExcel[$cantCol - 2];
            $excel->numFilaCabeceraTabla = 4;
            $excel->primeraLetraCabeceraTabla = "A";
            $excel->segundaLetraCabeceraTabla = "B";
            $excel->celdaInicial = $excel->primeraLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            $excel->celdaFinal = $excel->ultimaLetraCabeceraTabla . $excel->numFilaCabeceraTabla;
            if ($cantCol <= 9) {
                $excel->defineOrientation("V");
                $excel->defineSize("C");
            } elseif ($cantCol <= 13) {
                $excel->defineOrientation("H");
                $excel->defineSize("C");
            } else {
                $excel->defineOrientation("H");
                $excel->defineSize("O");
            }
            if ($excel->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $this->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $this->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($excel->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                if ($filtros[$k]['columna'] == "nombres") {
                                    $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                } else {
                                    $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($excel->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if ($filtros[$k]['columna'] == "nombres") {
                                $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Cargos();
            if ($where != "") $where = " WHERE " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($excel->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($excel->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($excel->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($excel->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            $resul = $obj->lista($where, $groups);

            $cargos = array();
            foreach ($resul as $v) {
                $cargos[] = array(
                    'tipo_resolucion' => $v->tipo_resolucion,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'denominacion' => $v->denominacion,
                    'ordenador' => $v->ordenador,
                    'cargo' => $v->cargo,
                    'sueldo' => $v->sueldo,
                    'codigo' => $v->codigo,
                    'estado' => $v->estado,
                    'condicion' => $v->condicion,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo
                );
            }

            #region Espacio para la definición de valores para la cabecera de la tabla
            $excel->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();
            $excel->header();
            $fila = $excel->numFilaCabeceraTabla;
            if (count($cargos) > 0) {
                $excel->RowTitle($colTitleSelecteds, $fila);
                $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                if ($excel->debug == 1) {
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                    print_r($cargos);
                    echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                }
                foreach ($cargos as $i => $val) {
                    if (count($agrupadores) > 0) {
                        if ($excel->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $agr = $excel->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            if ($excel->debug == 1) {
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                                print_r($agr);
                                echo "<p>+++++++++++++++++++++++++++AGRUPADO POR++++++++++++++++++++++++++++++++++++++++<p></p>";
                            }
                            $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
                            $fila++;
                            /*
                             * Si es que hay agrupadores, se inicia el conteo desde donde empieza el agrupador
                             */
                            $celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
                            $excel->Agrupador($agr, $fila);
                            $excel->RowTitle($colTitleSelecteds, $fila);
                        }
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $cargos[$j], $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;

                    } else {
                        $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                        $rowData = $excel->DefineRows($j + 1, $val, $colSelecteds);
                        if ($excel->debug == 1) {
                            echo "<p>···································FILA·················································<p></p>";
                            print_r($rowData);
                            echo "<p>···································FILA·················································<p></p>";
                        }
                        $excel->Row($rowData, $alignSelecteds, $formatTypes, $fila);
                        $fila++;
                    }
                    $j++;
                }
                $fila--;
                $celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
                $excel->borderGroup($celdaInicial, $celdaFinalDiagonalTabla);
            }
            $excel->ShowLeftFooter = true;
            //$excel->secondPage();
            if ($excel->debug == 0) {
                $excel->display("AppData/reporte_cargos.xls", "I");
            }
            #endregion Proceso de generación del documento PDF
        }
    }

    public function exportarPdfAction($n_rows, $columns, $filtros, $groups, $sorteds)
    {
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 7, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'tipo_resolucion' => array('title' => 'Resolución', 'width' => 40, 'align' => 'C', 'type' => 'varchar'),
            'unidad_administrativa' => array('title' => 'Organigrama', 'width' => 35, 'align' => 'C', 'type' => 'varchar'),
            'denominacion' => array('title' => 'Denominación', 'width' => 30, 'align' => 'C', 'type' => 'varchar'),
            'ordenador' => array('title' => 'Ordenador', 'width' => 10, 'align' => 'L', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 35, 'align' => 'C', 'type' => 'varchar'),
            'sueldo' => array('title' => 'Sueldo Bs.', 'width' => 18, 'align' => 'C', 'type' => 'int4'),
            'codigo' => array('title' => 'Item', 'width' => 10, 'align' => 'L', 'type' => 'varchar'),
            'estado' => array('title' => 'Estado', 'width' => 20, 'align' => 'C', 'type' => 'bpchar'),
            'condicion' => array('title' => 'Tipo Cargo', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'partida' => array('title' => 'Partida', 'width' => 15, 'align' => 'L', 'type' => 'int4'),
            'fuente_codigo' => array('title' => 'Fuente Codigo', 'width' => 15, 'align' => 'L', 'type' => 'int4'),
            'fuente' => array('title' => 'Fuente', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
            'organismo_codigo' => array('title' => 'Organismo Codigo', 'width' => 20, 'align' => 'C', 'type' => 'int4'),
            'organismo' => array('title' => 'Organismo', 'width' => 20, 'align' => 'C', 'type' => 'varchar')
        );

        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $pdf->title_rpt = utf8_decode('Reporte de Cargos');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $pdf->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $pdf->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                // if ($filtros[$k]['columna'] == "nombres") {
                                //     $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                // } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                // }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            // if ($filtros[$k]['columna'] == "nombres") {
                            //     $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            // } else {
                            $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            // }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Cargos();
            if ($where != "") $where = " WHERE " . $where;
            //if ($where != "") $where = " AND " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAll($where, $groups);
            $resul = $obj->lista($where, $groups);

            $cargos = array();
            foreach ($resul as $v) {
                $cargos[] = array(
                    'tipo_resolucion' => $v->tipo_resolucion,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'denominacion' => $v->denominacion,
                    'ordenador' => $v->ordenador,
                    'cargo' => $v->cargo,
                    'sueldo' => $v->sueldo,
                    'codigo' => $v->codigo,
                    'estado' => $v->estado,
                    'condicion' => $v->condicion,
                    'partida' => $v->partida,
                    'fuente_codigo' => $v->fuente_codigo,
                    'fuente' => $v->fuente,
                    'organismo_codigo' => $v->organismo_codigo,
                    'organismo' => $v->organismo
                );
            }
            //$pdf->Open("L");
            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($cargos) > 0) {
                foreach ($cargos as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $cargos[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('AppData/reporte_cargos.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }
    }

    public function exportarPacPdfAction($n_rows, $columns, $filtros, $groups, $sorteds)
    {

        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $groups = base64_decode(str_pad(strtr($groups, '-_', '+/'), strlen($groups) % 4, '=', STR_PAD_RIGHT));
        if ($groups == 'null' || $groups == null) $groups = "";
        $sorteds = base64_decode(str_pad(strtr($sorteds, '-_', '+/'), strlen($sorteds) % 4, '=', STR_PAD_RIGHT));
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        $n_col = count($columns);//echo$keys[1];
        $sorteds = json_decode($sorteds, true);
        /**
         * Especificando la configuración de las columnas
         */
        $generalConfigForAllColumns = array(
            'nro_row' => array('title' => 'Nro.', 'width' => 7, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
            'tipo_resolucion' => array('title' => 'Resolución', 'width' => 45, 'align' => 'C', 'type' => 'varchar'),
            'unidad_administrativa' => array('title' => 'Organigrama', 'width' => 45, 'align' => 'C', 'type' => 'varchar'),
            'codigo' => array('title' => 'Item', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
            'cargo' => array('title' => 'Cargo', 'width' => 45, 'align' => 'L', 'type' => 'varchar'),
            'gestion' => array('title' => 'Gestión', 'width' => 15, 'align' => 'C', 'type' => 'int4'),
            'fecha_ini' => array('title' => 'Fecha Inicio', 'width' => 20, 'align' => 'C', 'type' => 'date'),
            'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 20, 'align' => 'C', 'type' => 'date'),
            'estado' => array('title' => 'Estado', 'width' => 20, 'align' => 'L', 'type' => 'varchar')

        );

        $agruparPor = ($groups != "") ? explode(",", $groups) : array();
        $widthsSelecteds = $this->DefineWidths($generalConfigForAllColumns, $columns, $agruparPor);
        $ancho = 0;
        if (count($widthsSelecteds) > 0) {
            foreach ($widthsSelecteds as $w) {
                $ancho = $ancho + $w;
            }
            if ($ancho > 215.9) {
                if ($ancho > 270) {
                    $pdf = new pdfoasis('L', 'mm', 'Legal');
                    $pdf->pageWidth = 355;
                } else {
                    $pdf = new pdfoasis('L', 'mm', 'Letter');
                    $pdf->pageWidth = 280;
                }
            } else {
                $pdf = new pdfoasis('P', 'mm', 'Letter');
                $pdf->pageWidth = 215.9;
            }
            $pdf->tableWidth = $ancho;
            #region Proceso de generación del documento PDF
            $pdf->debug = 0;
            $pdf->title_rpt = utf8_decode('Reporte de Cargos');
            $pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
            $alignSelecteds = $pdf->DefineAligns($generalConfigForAllColumns, $columns, $agruparPor);
            $colSelecteds = $pdf->DefineCols($generalConfigForAllColumns, $columns, $agruparPor);
            $colTitleSelecteds = $pdf->DefineTitleCols($generalConfigForAllColumns, $columns, $agruparPor);
            $alignTitleSelecteds = $pdf->DefineTitleAligns(count($colTitleSelecteds));
            $gruposSeleccionadosActuales = $pdf->DefineDefaultValuesForGroups($groups);
            $pdf->generalConfigForAllColumns = $generalConfigForAllColumns;
            $pdf->colTitleSelecteds = $colTitleSelecteds;
            $pdf->widthsSelecteds = $widthsSelecteds;
            $pdf->alignSelecteds = $alignSelecteds;
            $pdf->alignTitleSelecteds = $alignTitleSelecteds;
            if ($pdf->debug == 1) {
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::COLUMNAS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($columns);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::FILTROS::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($filtros);
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::GRUPOS::::::::::::::::::::::::::::::::::::::::::::<p>";
                echo "<p>" . $groups;
                echo "<p>::::::::::::::::::::::::::::::::::::::::::::ORDEN::::::::::::::::::::::::::::::::::::::::::::<p>";
                print_r($sorteds);
                echo "<p>:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::<p>";
            }
            $where = '';
            $yaConsiderados = array();
            for ($k = 0; $k < count($filtros); $k++) {
                $cant = $pdf->obtieneCantidadVecesConsideracionPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);
                $arr_val = $pdf->obtieneValoresConsideradosPorColumnaEnFiltros($filtros[$k]['columna'], $filtros);

                for ($j = 0; $j < $n_col; $j++) {
                    if ($sub_keys[$j] == $filtros[$k]['columna']) {
                        $col_fil = $columns[$sub_keys[$j]]['text'];
                    }
                }
                if ($filtros[$k]['tipo'] == 'datefilter') {
                    $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                }
                $cond_fil = ' ' . $col_fil;
                if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {

                    if (strlen($where) > 0) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'NOT_EMPTY':
                                $where .= ' AND ';
                                break;
                            case 'CONTAINS':
                                $where .= ' AND ';
                                break;
                            case 'EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $where .= ' AND ';
                                break;
                        }
                    }
                }
                if ($cant > 1) {
                    if ($pdf->debug == 1) {
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                        print_r($yaConsiderados);
                        echo "<p>::::::::::::::::::::::::::::::::::::YA CONSIDERADOS:::::::::::::::::::::::::::::::::::::::::::::::<p>";
                    }
                    if (!in_array($filtros[$k]['columna'], $yaConsiderados)) {
                        switch ($filtros[$k]['condicion']) {
                            case 'EMPTY':
                                $cond_fil .= utf8_encode(" que sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                                break;
                            case 'NOT_EMPTY':
                                $cond_fil .= utf8_encode(" que no sea vacía ");
                                $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                                break;
                            case 'CONTAINS':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                // if ($filtros[$k]['columna'] == "nombres") {
                                //     $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                                // } else {
                                $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                                // }
                                break;
                            case 'EQUAL':
                                $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    if ($pdf->debug == 1) {

                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                        echo $filtros[$k]['columna'] . "-->" . $col;
                                        echo "<p>.........................recorriendo las columnas multiseleccionadas: .............................................";
                                    }
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " = '" . $col . "'";
                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        } else $where .= " OR ";
                                    }
                                }
                                break;
                            case 'GREATER_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                                $ini = 0;
                                foreach ($arr_val as $col) {
                                    //$fecha = date("Y-m-d", $col);
                                    $fecha = $col;
                                    if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                        //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        if ($ini == 0) {
                                            $where .= " (";
                                        }
                                        switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                            case 'int4':
                                            case 'numeric':
                                                $where .= $filtros[$k]['columna'] . " = '" . $fecha . "'";
                                                break;
                                            case 'date':
                                                //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                                if ($ini == 0) {
                                                    $where .= $filtros[$k]['columna'] . " BETWEEN ";
                                                } else {
                                                    $where .= " AND ";
                                                }
                                                $where .= "'" . $fecha . "'";

                                                break;
                                            case 'varchar':
                                            case 'bpchar':
                                                //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                                $where .= $filtros[$k]['columna'] . " ILIKE '" . $col . "'";
                                                break;
                                        }
                                        $ini++;
                                        if ($ini == count($arr_val)) {
                                            $where .= ") ";
                                        }
                                    }
                                }
                                break;
                            case 'LESS_THAN_OR_EQUAL':
                                $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                                $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                                break;
                        }
                        $yaConsiderados[] = $filtros[$k]['columna'];
                    }
                } else {
                    switch ($filtros[$k]['condicion']) {
                        case 'EMPTY':
                            $cond_fil .= utf8_encode(" que sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " ILIKE '')";
                            break;
                        case 'NOT_EMPTY':
                            $cond_fil .= utf8_encode(" que no sea vacía ");
                            $where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT ILIKE '')";
                            break;
                        case 'CONTAINS':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            // if ($filtros[$k]['columna'] == "nombres") {
                            //     $where .= "(p_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
                            // } else {
                            $where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
                            // }
                            break;
                        case 'EQUAL':
                            $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                            if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
                                //$where .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
                                    case 'int4':
                                    case 'numeric':
                                    case 'date':
                                        //$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
                                        break;
                                    case 'varchar':
                                    case 'bpchar':
                                        //$whereEqueals .= $filtros[$k]['columna']." ILIKE '".$filtros[$k]['valor']."'";
                                        $where .= $filtros[$k]['columna'] . " ILIKE '" . $filtros[$k]['valor'] . "'";
                                        break;
                                }
                            }
                            break;
                        case 'GREATER_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                            break;
                        case 'LESS_THAN_OR_EQUAL':
                            $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                            $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                            break;
                    }
                }

            }
            $obj = new Cargos();
            //if ($where != "") $where = " WHERE " . $where;
            if ($where != "") $where = " AND " . $where;
            $groups_aux = "";
            if ($groups != "") {
                /**
                 * Se verifica que no se considere la columna nombres debido a que contenido ya esta ordenado por las
                 * columnas p_apellido, s_apellido, c_apellido, p_anombre, s_nombre, t_nombre
                 */
                if (strrpos($groups, "nombres")) {
                    if (strrpos($groups, ",")) {
                        $arr = explode(",", $groups);
                        foreach ($arr as $val) {
                            if ($val != "nombres")
                                $groups_aux[] = $val;
                        }
                        $groups = implode(",", $groups_aux);
                    } else $groups = "";
                }
                if (is_array($sorteds) && count($sorteds) > 0) {
                    if ($groups != "") $groups .= ",";
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                }
                if ($groups != "")
                    $groups = " ORDER BY " . $groups . ",p_apellido,s_apellido,c_apellido,p_nombre,s_nombre,t_nombre,id_da,fecha_ini";
                if ($pdf->debug == 1) echo "<p>La consulta es: " . $groups . "<p>";
            } else {
                if (is_array($sorteds) && count($sorteds) > 0) {
                    $coma = "";
                    if ($pdf->debug == 1) {
                        echo "<p>===========================================Orden======================================</p>";
                        print_r($sorteds);
                        echo "<p>===========================================Orden======================================</p>";
                    }
                    foreach ($sorteds as $ord => $orden) {
                        $groups .= $coma . $ord;
                        if ($orden['asc'] == '') $groups .= " ASC"; else$groups .= " DESC";
                        $coma = ",";
                    }
                    $groups = " ORDER BY " . $groups;
                }

            }
            if ($pdf->debug == 1) echo "<p>WHERE------------------------->" . $where . "<p>";
            if ($pdf->debug == 1) echo "<p>GROUP BY------------------------->" . $groups . "<p>";
            //$resul = $obj->getAll($where, $groups);
            $resul = $obj->listapac(0, $where, $groups);

            $cargos = array();
            foreach ($resul as $v) {
                $cargos[] = array(
                    'tipo_resolucion' => $v->tipo_resolucion,
                    'unidad_administrativa' => $v->unidad_administrativa,
                    'codigo' => $v->codigo,
                    'cargo' => $v->cargo,
                    'gestion' => $v->gestion,
                    'fecha_ini' => date("d-m-Y", strtotime($v->fecha_ini)),
                    'fecha_fin' => date("d-m-Y", strtotime($v->fecha_fin)),
                    'estado' => $v->estado
                );
            }
            //$pdf->Open("L");
            /**
             * Si el ancho supera el establecido para una hoja tamaño carta, se la pone en posición horizontal
             */

            $pdf->AddPage();
            if ($pdf->debug == 1) {
                echo "<p>El ancho es:: " . $ancho;
            }
            #region Espacio para la definición de valores para la cabecera de la tabla
            $pdf->FechaHoraReporte = date("d-m-Y H:i:s");
            $j = 0;
            $agrupadores = array();
            //echo "<p>++++++++++>".$groups;
            if ($groups != "")
                $agrupadores = explode(",", $groups);

            $dondeCambio = array();
            $queCambio = array();

            if (count($cargos) > 0) {
                foreach ($cargos as $i => $val) {
                    if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                    if (count($agrupadores) > 0) {
                        if ($pdf->debug == 1) {
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                            print_r($gruposSeleccionadosActuales);
                            echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
                        }
                        $agregarAgrupador = 0;
                        $aux = array();
                        $dondeCambio = array();
                        foreach ($gruposSeleccionadosActuales as $key => $valor) {
                            if ($valor != $val[$key]) {
                                $agregarAgrupador = 1;
                                $aux[$key] = $val[$key];
                                $dondeCambio[] = $key;
                                $queCambio[$key] = $val[$key];
                            } else {
                                $aux[$key] = $val[$key];
                            }
                        }
                        $gruposSeleccionadosActuales = $aux;
                        if ($agregarAgrupador == 1) {
                            $pdf->Ln();
                            $pdf->DefineColorHeaderTable();
                            $pdf->SetAligns($alignTitleSelecteds);
                            //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                            $agr = $pdf->DefineTitleColsByGroups($generalConfigForAllColumns, $dondeCambio, $queCambio);
                            $pdf->Agrupador($agr);
                            $pdf->RowTitle($colTitleSelecteds);
                        }
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        if (($pdf->pageWidth - $pdf->tableWidth) > 0) $pdf->SetX(($pdf->pageWidth - $pdf->tableWidth) / 2);
                        $rowData = $pdf->DefineRows($j + 1, $cargos[$j], $colSelecteds);
                        $pdf->Row($rowData);

                    } else {
                        //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                        $pdf->DefineColorBodyTable();
                        $pdf->SetAligns($alignSelecteds);
                        $rowData = $pdf->DefineRows($j + 1, $val, $colSelecteds);
                        $pdf->Row($rowData);
                    }
                    //if(($pdf->pageWidth-$pdf->tableWidth)>0)$pdf->SetX(($pdf->pageWidth-$pdf->tableWidth)/2);
                    $j++;
                }
            }
            $pdf->ShowLeftFooter = true;
            if ($pdf->debug == 0) $pdf->Output('reporte_cargos_pac.pdf', 'I');
            #endregion Proceso de generación del documento PDF
        }

    }

    function DefineWidths($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = 8;
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $widthAlignAll[$key]['width'];
                }
            }
        }
        return $arrRes;
    }

    /*
         * Función para obtener la cantidad de veces que se considera una misma columna en el filtrado.
         * @param $columna
         * @param $array
         * @return int
         */
    function obtieneCantidadVecesConsideracionPorColumnaEnFiltros($columna, $array)
    {
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $cont++;
                }
            }
        }
        return $cont;
    }

    /**
     * Función para la obtención de los valores considerados en el filtro enviado.
     * @param $columna Nombre de la columna
     * @param $array Array con los registro de busquedas.
     * @return array Array con los valores considerados en el filtrado enviado.
     */
    function obtieneValoresConsideradosPorColumnaEnFiltros($columna, $array)
    {
        $arr_col = array();
        $cont = 0;
        if (count($array) >= 1) {
            foreach ($array as $key => $val) {
                if (in_array($columna, $val)) {
                    $arr_col[] = $val["valor"];
                }
            }
        }
        return $arr_col;
    }

// 	}

    // public function exportarExcelAction()
    // {
    // 	global $config;

    // 	$loader = new \Phalcon\Loader();

    // 	$loader->registerDirs(
    // 		array(
    // 			$config->application->libraryDir."PHPExcel/"
    // 			)
    // 		);

    // 	$loader->register();

    // 	$excel = new PHPExcel();
    // 	$excel->setActiveSheetIndex(0);
    // 	$excel->getActiveSheet()->setTitle('test worksheet');

    // 	$excel->getActiveSheet()->setCellValue('A1', 'Rezultati pretrage');
    // 	$excel->getActiveSheet()->setCellValue('A2', "Ime");
    // 	$excel->getActiveSheet()->setCellValue('C2', "Prezime");
    // 	$excel->getActiveSheet()->setCellValue('F2', "Adresa stanovanja");

    // 	$br = rand(0,1000000);
    // 	$naziv = $br.".xls";
    // 	$objWriter = new PHPExcel_Writer_Excel2007($excel);
    // 	$objWriter->save('../tmp/'.$naziv);
    // }


// public function exportarPacPdfAction()
// {
// 		//$pdf = new fpdf();
// 	$pdf = new pdfoasis('L','mm','Letter');
// 	$pdf->pageWidth=280;
// 	$pdf->AddPage();
// 	//$title = utf8_decode('Reporte de Cargos');
// 	$pdf->debug=0;
// 	$pdf->title_rpt = utf8_decode('Reporte de Plan Anual de Contratacion de Personal');
// 	$pdf->header_title_empresa_rpt = utf8_decode('Empresa Estatal de Transporte por Cable "Mi Teleférico"');
// 	$pdf->SetFont('Arial','B',14);
// 	$pdf->SetXY(50, 28);
// 	$pdf->Cell(0,0,"REPORTE DE PLAN ANUAL DE CONTRATACIONES DE PERSONAL");
// 	// $miCabecera = array('Nro', 'Organigrama', 'Item', 'Cargo','Sueldo','Estado','Tipo Cargo');

// 	$pdf->SetXY(10, 35);
// 	$pdf->SetFont('Arial','B',10);
// 	$pdf->SetFillColor(52, 151, 219);//Fondo verde de celda
// 	$pdf->SetTextColor(240, 255, 240); //Letra color blanco
// 	$pdf->Cell(10,7, 'Nro',1, 0 , 'L', true );
// 	$pdf->Cell(80,7, 'Organigrama',1, 0 , 'L', true );
// 	$pdf->Cell(80,7, 'Cargo',1, 0 , 'L', true );
// 	$pdf->Cell(20,7, 'Fecha Inicio',1, 0 , 'L', true );
// 	$pdf->Cell(20,7, 'Fecha Finalizacion',1, 0 , 'L', true );
// 	$pdf->Cell(20,7, 'Estado',1, 0 , 'L', true );
// 	// foreach($miCabecera as $fila)
// 	// 	{
// 	// 	    //Atención!! el parámetro true rellena la celda con el color elegido
// 	// 		$pdf->Cell(24,7, utf8_decode($fila),1, 0 , 'L', true);
// 	// 	}
// 	$pdf->SetXY(10,42);
// 	$pdf->SetFont('Arial','',7);
// 			$pdf->SetFillColor(229, 229, 229); //Gris tenue de cada fila
// 		$pdf->SetTextColor(3, 3, 3); //Color del texto: Negro
// 		$bandera = false; //Para alternar el relleno
// 		$model = new Cargos();
// 		$fecha_ini=date("Y-m-d", strtotime($_POST['fecha_ini_rep_pac']));
// 		$fecha_fin=date("Y-m-d", strtotime($_POST['fecha_fin_rep_pac']));
// 		$resul = $model->listapac('',$_POST['organigrama_id_rep_pac'],$fecha_ini,$fecha_fin);
// 		foreach ($resul as $v) {
// 			$pdf->Cell(10,7, utf8_decode($v->nro),1, 0 , 'L', $bandera );
// 			$pdf->Cell(80,7, utf8_decode($v->unidad_administrativa),1, 0 , 'L', $bandera );
// 			$pdf->Cell(80,7, utf8_decode($v->cargo),1, 0 , 'L', $bandera );
// 			$pdf->Cell(20,7, date("d-m-Y",strtotime($v->fecha_ini)),1, 0 , 'L', $bandera );
// 			$pdf->Cell(20,7, date("d-m-Y",strtotime($v->fecha_fin)),1, 0 , 'L', $bandera );
// 			$pdf->Cell(20,7, utf8_decode($v->estado1),1, 0 , 'L', $bandera );
// 		    $pdf->Ln();//Salto de línea para generar otra fila
// 		    $bandera = !$bandera;//Alterna el valor de la bandera
// 		}
// 		$pdf->Output();
// 		$this->view->disable();


// 	}

    /**
     * [dependenciaAction selecciona los cargos dependientes de un organigrama]
     * @param  string $id [criterio de busqueda]
     * @param  string $depende_id [criterio de selected al editar]
     */
    // public function select_organigramaAction($id='',$organigrama_id='')
    // {
    // 	$resul = Organigramas::find(array('baja_logica=1 and resolucion_ministerial_id='.$id,'order' => 'unidad_administrativa ASC'));
    // 	$this->view->disable();
    // 	$options = '<option value="">(Seleccionar)</option>';
    // 	foreach ($resul as $v) {
    // 		$checked='';
    // 		if($organigrama_id==$v->id)
    // 		{
    // 			$checked='selected=selected';
    // 		}
    // 		$options.='<option value="'.$v->id.'" '.$checked.'>'.$v->unidad_administrativa.'</option>';
    // 	}


    // echo $options;
    // }


    // public function select_fuentefinanciamientoAction($id='',$fin_partida_id='')
    // {
    // 	if ($id>16) {
    // 		$resul = Finpartidas::find(array('baja_logica=1 and agrupador=1','order' => 'id ASC'));
    // 	}else{
    // 		$resul = Finpartidas::find(array('baja_logica=1 and agrupador=0','order' => 'id ASC'));
    // 	}


    // 	$this->view->disable();
    // 	$options = '<option value="">(Seleccionar)</option>';
    // 	foreach ($resul as $v) {
    // 		$checked='';
    // 		if($fin_partida_id==$v->id)
    // 		{
    // 			$checked='selected=selected';
    // 		}
    // 		$options.='<option value="'.$v->id.'" '.$checked.'>'.$v->denominacion.'</option>';
    // 	}
    // echo $options;
    // }

    // public function dependenciaAction($id='',$depende_id='')
    // {

    // 	//$resul = Cargos::find(array('baja_logica=1 and organigrama_id='.$id,'order' => 'id ASC'));
    // 	$model = new Cargos();
    // 	$resul = $model->dependientes($id);

    // 	$this->view->disable();
    // 	$options = '<option value="">(Seleccionar)</option>';
    // 	foreach ($resul as $v) {
    // 		$checked='';
    // 		if($depende_id==$v->id)
    // 		{
    // 			$checked='selected=selected';
    // 		}
    // 		$options.='<option value="'.$v->id.'" '.$checked.'>'.$v->cargo.'</option>';
    // 	}


    // echo $options;
    // }
    //
    //
    public function select_organigramaAction()
    {
        $resul = Organigramas::find(array('baja_logica=1 and resolucion_ministerial_id=' . $_POST["elegido"], 'order' => 'unidad_administrativa ASC'));
        $this->view->disable();
        $options = '<option value="0">(Seleccionar)</option>';
        foreach ($resul as $v) {
            $checked = '';
            // if($organigrama_id==$v->id)
            // {
            // 	$checked='selected=selected';
            // }
            $options .= '<option value="' . $v->id . '" ' . $checked . '>' . $v->unidad_administrativa . '</option>';
        }
        echo $options;
    }

    public function select_fuentefinanciamientoAction()
    {
        if ($_POST['elegido'] > 16) {
            $resul = Finpartidas::find(array('baja_logica=1 and agrupador=1', 'order' => 'id ASC'));
        } else {
            $resul = Finpartidas::find(array('baja_logica=1 and agrupador=0', 'order' => 'id ASC'));
        }
        $this->view->disable();
        $options = '<option value="">(Seleccionar)</option>';
        foreach ($resul as $v) {
            $checked = '';
            $options .= '<option value="' . $v->id . '" ' . $checked . '>' . $v->denominacion . '</option>';
        }
        echo $options;
    }

    public function select_dependenciaAction()
    {
        $model = new Cargos();
        $resul = $model->dependientes($_POST["elegido"], $_POST["gestion"]);
        $this->view->disable();
        $options = '<option value="">(Seleccionar)</option>';
        foreach ($resul as $v) {
            $checked = '';
            // if($organigrama_id==$v->id)
            // {
            // 	$checked='selected=selected';
            // }
            $options .= '<option value="' . $v->id . '" ' . $checked . '>' . $v->cargo . ' (' . $v->gestion . ')</option>';
        }
        echo $options;
    }

    public function updateescalaAction()
    {
        // if (isset($_POST['fin_partida_id'])) {
        // 	$fin_partida_id=$_POST['fin_partida_id'];
        // 	$resolucion_ministerial_id = $_POST['res_min_id'];
        // 	$resolucion_escala_id = $_POST['resolucion_escala_id'];
        // 	$model = new Cargos();

        // 	$sql = "UPDATE cargos SET nivelsalarial_id = (SELECT id FROM nivelsalariales n WHERE n.resolucion_id='$resolucion_escala_id' AND n.baja_logica=1 AND n.nivel=codigo_nivel)
        //       			WHERE baja_logica = 1 AND resolucion_ministerial_id ='$resolucion_ministerial_id' AND fin_partida_id = '$fin_partida_id'";
        //       	// echo $sql;
        // 	$resul = $model->update_escala($sql);
        // 	$msm = 'actulaizado correctamente';
        // 	// if($resul == true){
        // 	// 	$msm = 'Actualizado correctamente';
        // 	// }else{
        // 	// 	$msm = 'NO se actualizado. Coordine con el administrador del sistema';
        // 	// }
        // }
        // $msm="prueba";
        // echo $msm;
        $this->view->disable();
        echo json_encode();
    }

}