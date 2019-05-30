<?php 
/**
* 
*/

class ServercargosController extends ControllerBase
{
	public function initialize() {
		parent::initialize();
	}

	public function indexAction()
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

                    $resolucion_ministerial0 = Resoluciones::findFirst(array("uso=1 and activo=1 and baja_logica=1"));
		$this->view->setVar('tipo_resolucion',$resolucion_ministerial0->tipo_resolucion);

	}

// 	public function listAction()
// 	{
// 		$sql= "SELECT c.id,c.resolucion_ministerial_id,c.organigrama_id,c.fin_partida_id,o.unidad_administrativa,c.codigo_nivel,
// c.depende_id,c.codigo,c.ordenador,c.cargo,n.categoria,c.asistente,c.jefe,n.clase,n.nivel,n.denominacion,n.sueldo,co.condicion,
// CASE WHEN r.estado>0  THEN 'ADJUDICADO' ELSE 'ACEFALO'  END as estado,CONCAT(p.p_nombre,' ',p.s_nombre,' ',p.p_apellido,' ',p.s_apellido) as nombre, CONCAT(p.ci,' ',p.expd) as ci,
// f.partida,
// cp.gestion,cp.programa,cp.proyecto,cp.actividad,
// org.codigo as organismo_codigo,org.organismo,
// fu.codigo as fuente_codigo,fu.fuente,
// re.tipo_resolucion
// FROM cargos c 
// INNER JOIN organigramas o ON c.organigrama_id=o.id
// INNER JOIN nivelsalariales n ON c.codigo_nivel = n.nivel AND n.activo=1 
// INNER JOIN finpartidas f ON c.fin_partida_id = f.id
// INNER JOIN condiciones co ON f.condicion_id = co.id
// INNER JOIN resoluciones re ON c.resolucion_ministerial_id = re.id
// LEFT JOIN relaborales r ON r.cargo_id=c.id AND r.estado>0 AND r.baja_logica=1
// LEFT JOIN personas p ON r.persona_id=p.id
// LEFT JOIN financiamientos fi ON fi.id=f.financiamiento_id
// LEFT JOIN categoriasprog cp ON cp.id = fi.categoriaprog_id
// LEFT JOIN organismos org ON org.id = fi.organismo_id
// LEFT JOIN fuentes fu ON fu.id = fi.fuente_id
// WHERE c.baja_logica=1 
// ORDER BY c.organigrama_id asc, c.codigo_nivel asc ";

// 		$pagenum = $_GET['pagenum'];
// 		$pagesize = $_GET['pagesize'];
// 		$start = $pagenum * $pagesize;
// 		$query = "SELECT * FROM (".$sql.") as v ";
// 		//$query = "SELECT * FROM cargos";
// 		if (isset($_GET['sortdatafield']))
// 		{
// 			$sortfield = $_GET['sortdatafield'];
// 			$sortorder = $_GET['sortorder'];
// 			$model = new Cargos();
// 			$resul = $model->serverlista($query);
// 			$rows = count($resul);
// 			$model = new Cargos();
// 			$resul2 = $model->serverlista($sql);
// 			$total_rows = count($resul2);
// 			if ($sortfield != NULL)
// 			{
// 				if ($sortorder == "desc")
// 				{
// 					$query = $query." ORDER BY" . " " . $sortfield . " DESC";
// 				}
// 				else if ($sortorder =="asc")
// 				{
// 					$query = $query." ORDER BY" . " " . $sortfield . " ASC";
// 				}			
// 				$query=$query." LIMIT '$pagesize' OFFSET '$start'";
// 				$model = new Cargos();
// 				$resul = $model->serverlista($query);
// 			}
			
// 		}
// 		else
// 		{
			
// 			$model = new Cargos();
// 			$resul = $model->serverlista($query);
// 			$rows = count($resul);

// 			$model = new Cargos();
// 			$resul2 = $model->serverlista($sql);
// 			$total_rows = count($resul2);
// 		}
// 		$query=$query." LIMIT '$pagesize' OFFSET '$start'";

// //		$model = new Cargos();
// //		$resul = $model->lista();
// 		$this->view->disable();
// 		foreach ($resul as $v) {
// 			$customers[] = array(
// 				'id' => $v->id,
// 				'resolucion_ministerial_id' => $v->resolucion_ministerial_id,
// 				'organigrama_id' => $v->organigrama_id,
// 				'codigo_nivel' => $v->codigo_nivel,
// 				'codigo' => $v->codigo,
// 				'ordenador' => $v->ordenador,
// 				'cargo' => $v->cargo,
// 				'depende_id' => $v->depende_id,
// 				'estado' => $v->estado,
// 				'fin_partida_id' => $v->fin_partida_id,
// 				'jefe' => $v->jefe,
// 				);
// 		}
// 		$data[] = array('TotalRows' => $total_rows,'Rows' => $customers);
// 		echo json_encode($data);
// 	}

public function listAction()
	{
		$sql= "SELECT c.id,c.resolucion_ministerial_id,c.organigrama_id,c.fin_partida_id,o.unidad_administrativa,c.codigo_nivel,
c.depende_id,c.codigo,c.ordenador,c.cargo,n.categoria,c.asistente,c.jefe,n.clase,n.nivel,n.denominacion,n.sueldo,co.condicion,
CASE WHEN r.estado>0  THEN 'ADJUDICADO' ELSE 'ACEFALO'  END as estado,CONCAT(p.p_nombre,' ',p.s_nombre,' ',p.p_apellido,' ',p.s_apellido) as nombre, CONCAT(p.ci,' ',p.expd) as ci,
f.partida,
cp.gestion,cp.programa,cp.proyecto,cp.actividad,
org.codigo as organismo_codigo,org.organismo,
fu.codigo as fuente_codigo,fu.fuente,
re.tipo_resolucion,
c.fecha_reg
FROM cargos c 
INNER JOIN organigramas o ON c.organigrama_id=o.id
INNER JOIN nivelsalariales n ON c.codigo_nivel = n.nivel AND n.activo=1 
INNER JOIN finpartidas f ON c.fin_partida_id = f.id
INNER JOIN condiciones co ON f.condicion_id = co.id
INNER JOIN resoluciones re ON c.resolucion_ministerial_id = re.id
LEFT JOIN relaborales r ON r.cargo_id=c.id AND r.estado>0 AND r.baja_logica=1
LEFT JOIN personas p ON r.persona_id=p.id
LEFT JOIN financiamientos fi ON fi.id=f.financiamiento_id
LEFT JOIN categoriasprog cp ON cp.id = fi.categoriaprog_id
LEFT JOIN organismos org ON org.id = fi.organismo_id
LEFT JOIN fuentes fu ON fu.id = fi.fuente_id
WHERE c.baja_logica=1 
ORDER BY c.organigrama_id asc, c.codigo_nivel asc ";

		$pagenum = $_GET['pagenum'];
		$pagesize = $_GET['pagesize'];
		$start = $pagenum * $pagesize;
		$query = "SELECT * FROM (".$sql.") as v ";

		if (isset($_GET['filterscount']))
		{
			$filterscount = $_GET['filterscount'];
			if ($filterscount > 0)
			{
				$where = " WHERE (";
				$tmpdatafield = "";
				$tmpfilteroperator = "";

				for ($i=0; $i < $filterscount; $i++)
				{
				// get the filter's value.
					$filtervalue = $_GET["filtervalue" . $i];
				// get the filter's condition.
					$filtercondition = $_GET["filtercondition" . $i];
				// get the filter's column.
					$filterdatafield = $_GET["filterdatafield" . $i];
				// get the filter's operator.
					$filteroperator = $_GET["filteroperator" . $i];

					if ($tmpdatafield == ""){
						$tmpdatafield = $filterdatafield;
					}else if($tmpdatafield <> $filterdatafield){ 
						$where .= ")AND(";
					}else if ($tmpdatafield == $filterdatafield){
						if ($tmpfilteroperator == 0){ 
							$where .= " AND ";
						}else { 
							$where .= " OR ";
						}					
					}
					switch($filtercondition){
						case "CONTAINS":$where .= " " . $filterdatafield . " ILIKE '%" . $filtervalue ."%'";
						break;
						case "DOES_NOT_CONTAIN":$where .= " " . $filterdatafield . " NOT ILIKE '%" . $filtervalue ."%'";
						break;
						case "EQUAL": $where .= " " . $filterdatafield . " = '" . $filtervalue ."'";
						break; 
						case "NOT_EQUAL":$where .= " " . $filterdatafield . " <> '" . $filtervalue ."'";
						break;
						case "GREATER_THAN": $where .= " " . $filterdatafield . " > '" . $filtervalue ."'";
						break; 
						case "LESS_THAN": $where .= " " . $filterdatafield . " < '" . $filtervalue ."'";
						break;
						case "GREATER_THAN_OR_EQUAL":$where .= " " . $filterdatafield . " >= '" . $filtervalue ."'";
						break;
						case "LESS_THAN_OR_EQUAL": $where .= " " . $filterdatafield . " <= '" . $filtervalue ."'";
						break;
						case "STARTS_WITH": $where .= " " . $filterdatafield . " ILIKE '" . $filtervalue ."%'";
						break;
						case "ENDS_WITH": $where .= " " . $filterdatafield . " ILIKE '%" . $filtervalue ."'";
						break;
					}
					if ($i == $filterscount - 1){
						$where .= ")";
						}
					$tmpfilteroperator = $filteroperator;
					$tmpdatafield = $filterdatafield;
					}

					$query = $query . $where;
				}
			}

		/*
		ordenamos
		 */	
		if (isset($_GET['sortdatafield']))
		{
			$sortfield = $_GET['sortdatafield'];
			$sortorder = $_GET['sortorder'];
			if ($sortfield != NULL)
			{
				$query = $query." ORDER BY" . " " . $sortfield . " ".$sortorder;
			}
			
		}
		
		$model = new Cargos();
		$resul = $model->serverlista($query);
		$total_rows = count($resul);

		$query=$query." LIMIT '$pagesize' OFFSET '$start'";			
		$model = new Cargos();
		$resul = $model->serverlista($query);

		$this->view->disable();
		foreach ($resul as $v) {
			$customers[] = array(
				'id' => $v->id,
			'resolucion_ministerial_id' => $v->resolucion_ministerial_id,
			'tipo_resolucion' => $v->tipo_resolucion,
			'unidad_administrativa' => $v->unidad_administrativa,
			'organigrama_id' => $v->organigrama_id,
			'codigo_nivel' => $v->codigo_nivel,
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
				);
		}
		$data[] = array('TotalRows' => $total_rows,'Rows' => $customers);
		echo json_encode($data);
	}



	

	

}
?>