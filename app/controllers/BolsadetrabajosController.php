<?php 
/**
* 
*/

class BolsadetrabajosController extends ControllerBase
{
	public function initialize() {
		parent::initialize();
	}

	public function indexAction()
	{


		$this->assets
			->addCss('/jqwidgets/styles/jqx.base.css')
			->addCss('/jqwidgets/styles/jqx.custom.css')
			//->addCss('/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')
			//->addCss('/media/plugins/form-stepy/jquery.stepy.css')
		;
		$this->assets
			->addJs('/js/jqwidgets/jqxcore.js')
			->addJs('/js/jqwidgets/jqxmenu.js')
			->addJs('/js/jqwidgets/jqxdropdownlist.js')
			->addJs('/js/jqwidgets/jqxlistbox.js')
			->addJs('/js/jqwidgets/jqxcheckbox.js')
			->addJs('/js/jqwidgets/jqxscrollbar.js')
			->addJs('/js/jqwidgets/jqxgrid.js')
			->addJs('/js/jqwidgets/jqxdata.js')
			->addJs('/js/jqwidgets/jqxgrid.sort.js')
			->addJs('/js/jqwidgets/jqxgrid.pager.js')
			->addJs('/js/jqwidgets/jqxgrid.filter.js')
			->addJs('/js/jqwidgets/jqxgrid.selection.js')
			->addJs('/js/jqwidgets/jqxgrid.grouping.js')
			->addJs('/js/jqwidgets/jqxgrid.columnsreorder.js')
			->addJs('/js/jqwidgets/jqxgrid.columnsresize.js')
			->addJs('/js/jqwidgets/jqxdatetimeinput.js')
			->addJs('/js/jqwidgets/jqxcalendar.js')
			->addJs('/js/jqwidgets/jqxbuttons.js')
			->addJs('/js/jqwidgets/jqxdata.export.js')
			->addJs('/js/jqwidgets/jqxgrid.export.js')
			->addJs('/js/jqwidgets/globalization/globalize.js')
			->addJs('/js/jqwidgets/jqxgrid.aggregates.js')
			->addJs('/js/jqwidgets/jqxtooltip.js')
			->addJs('/scripts/bolsadetrabajos/exportar.js');
		

	}

	
	public function listAction(){
		$pagenum = $_GET['pagenum'];
		$pagesize = $_GET['pagesize'];
		$start = $pagenum * $pagesize;

		$where = "";
		$orderby = "";
		$limit = " LIMIT $pagesize OFFSET $start ";

		// filter data.
		if (isset($_GET['filterscount'])) {
			$filterscount = $_GET['filterscount'];

			if ($filterscount > 0) {
				$where = " WHERE (";
				$tmpdatafield = "";
				$tmpfilteroperator = "";
				for ($i = 0; $i < $filterscount; $i++) {
					// get the filter's value.
					$filtervalue = $_GET["filtervalue" . $i];
					// get the filter's condition.
					$filtercondition = $_GET["filtercondition" . $i];
					// get the filter's column.
					$filterdatafield = $_GET["filterdatafield" . $i];
					// get the filter's operator.
					$filteroperator = $_GET["filteroperator" . $i];

					if ($tmpdatafield == "") {
						$tmpdatafield = $filterdatafield;
					} else if ($tmpdatafield <> $filterdatafield) {
						$where .= " ) AND ( ";
					} else if ($tmpdatafield == $filterdatafield) {
						if ($tmpfilteroperator == 0) {
							$where .= " AND ";
						} else
							$where .= " OR ";
					}

					// build the "WHERE" clause depending on the filter's condition, value and datafield.
					switch ($filtercondition) {
						case "NOT_EMPTY":
						case "NOT_NULL":
							$where .= " " . $filterdatafield . " NOT ILIKE '" . "" . "'";
							break;
						case "EMPTY":
						case "NULL":
							$where .= " " . $filterdatafield . " ILIKE '" . "" . "'";
							break;
						case "CONTAINS_CASE_SENSITIVE":
							$where .= " BINARY  " . $filterdatafield . " ILIKE '%" . $filtervalue . "%'";
							break;
						case "CONTAINS":
							$where .= " " . $filterdatafield . " ILIKE '%" . $filtervalue . "%'";
							break;
						case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " NOT ILIKE '%" . $filtervalue . "%'";
							break;
						case "DOES_NOT_CONTAIN":
							$where .= " " . $filterdatafield . " NOT ILIKE '%" . $filtervalue . "%'";
							break;
						case "EQUAL_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " = '" . $filtervalue . "'";
							break;
						case "EQUAL":
							$where .= " " . $filterdatafield . " = '" . $filtervalue . "'";
							break;
						case "NOT_EQUAL_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " <> '" . $filtervalue . "'";
							break;
						case "NOT_EQUAL":
							$where .= " " . $filterdatafield . " <> '" . $filtervalue . "'";
							break;
						case "GREATER_THAN":
							$where .= " " . $filterdatafield . " > '" . $filtervalue . "'";
							break;
						case "LESS_THAN":
							$where .= " " . $filterdatafield . " < '" . $filtervalue . "'";
							break;
						case "GREATER_THAN_OR_EQUAL":
							$where .= " " . $filterdatafield . " >= '" . date("Y-m-d", strtotime($filtervalue)) . "'";
							break;
						case "LESS_THAN_OR_EQUAL":
							$where .= " " . $filterdatafield . " <= '" . date("Y-m-d", strtotime($filtervalue)) . "'";
							break;
						case "STARTS_WITH_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " ILIKE '" . $filtervalue . "%'";
							break;
						case "STARTS_WITH":
							$where .= " " . $filterdatafield . " ILIKE '" . $filtervalue . "%'";
							break;
						case "ENDS_WITH_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " ILIKE '%" . $filtervalue . "'";
							break;
						case "ENDS_WITH":
							$where .= " " . $filterdatafield . " ILIKE '%" . $filtervalue . "'";
							break;
					}

					if ($i == $filterscount - 1) {
						$where .= ")";
					}

					$tmpfilteroperator = $filteroperator;
					$tmpdatafield = $filterdatafield;
				}
				// build the query.
//                $query = $query . $where;

			}
		}

		if (isset($_GET['sortdatafield'])) {

			$sortfield = $_GET['sortdatafield'];
			$sortorder = $_GET['sortorder'];

			if ($sortorder != '') {
				if ($_GET['filterscount'] > 0) {
					$orderby = " ORDER BY " . " " . $sortfield . " " . $sortorder;
				}
			}

		}


		$model = new Ppostulantes();
		$resul = $model->listaRegistrate($where,$orderby);
		$total_rows = count($resul);

		$model = new Ppostulantes();
		$resul = $model->listaRegistrate($where, $orderby, $limit);
		
		$customers = array();
		foreach ($resul as $v) {
			$customers[] = array(
				'id' => $v->id,
				'nombre' => $v->nombre,
				'apellidos' => $v->apellidos,
				'sexo' => $v->sexo,
				'ci' => $v->ci,
				'fecha_nac' => $v->fecha_nac,
				'nacionalidad' => $v->nacionalidad,
				'estado_civil' => $v->estado_civil,
				'direccion' => $v->direccion,
				'telefono' => $v->telefono,
				'celular' => $v->celular,
				'correo' => $v->correo,
				'libreta_militar' => $v->libreta_militar,
				'institucion' => $v->institucion,
				'grado' => $v->grado,
				'valor_1' => $v->valor_1,
				'conalpedis' => $v->conalpedis,
				'reg_dominio'=>$v->reg_dominio
				);
		}

		$data[] = array(
			'TotalRows' => $total_rows,
			'Rows' => $customers
		);
		$this->view->disable();
		echo json_encode($data);
	}

	/*
    Exportar excel desde grid
     */
	public function exportexcelAction($n_rows, $columns, $filtros, $groups, $sorteds)
	{
		$this->view->disable();
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
			'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
			'apellidos' => array('title' => 'Apellidos', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
			'nombre' => array('title' => 'Nombres', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
			'ci' => array('title' => 'CI', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
			'sexo' => array('title' => 'Sexo', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
			'grado' => array('title' => 'Grado', 'width' => 12, 'align' => 'L', 'type' => 'varchar'),
			'institucion' => array('title' => 'Institucion', 'width' => 8, 'align' => 'L', 'type' => 'varchar'),
			'valor_1' => array('title' => 'Titulo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'fecha_nac' => array('title' => 'Fecha Nacimiento', 'width' => 18, 'align' => 'C', 'type' => 'date'),
			'nacionalidad' => array('title' => 'Nacionalidad', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'estado_civil' => array('title' => 'Estado Civil', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'direccion' => array('title' => 'Direccion', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
			'telefono' => array('title' => 'Telefono', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'celular' => array('title' => 'Celular', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'correo' => array('title' => 'Correo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'libreta_militar' => array('title' => 'Libreta Militar', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'conalpedis' => array('title' => 'CONALPEDIS', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
			'reg_dominio' => array('title' => 'Dominio', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
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
			$excel->title_rpt = utf8_decode('Reporte Bolsa de Trabajo');
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
								$where .= "(" . $filtros[$k]['columna'] . " IS NULL OR " . $filtros[$k]['columna'] . " LIKE '')";
								break;
							case 'NOT_EMPTY':
								$cond_fil .= utf8_encode(" que no sea vacía ");
								$where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT LIKE '')";
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
										//$where .= $filtros[$k]['columna']." LIKE '".$filtros[$k]['valor']."'";
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
												//$whereEqueals .= $filtros[$k]['columna']." LIKE '".$filtros[$k]['valor']."'";
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
										//$where .= $filtros[$k]['columna']." LIKE '".$filtros[$k]['valor']."'";
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
												//$whereEqueals .= $filtros[$k]['columna']." LIKE '".$filtros[$k]['valor']."'";
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
							$where .= "(" . $filtros[$k]['columna'] . " IS NOT NULL OR " . $filtros[$k]['columna'] . " NOT LIKE '')";
							break;
						case 'CONTAINS':
							$cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
							if ($filtros[$k]['columna'] == "nombres") {
								$where .= "(p_nombre LIKE '%" . $filtros[$k]['valor'] . "%' OR s_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR t_nombre ILIKE '%" . $filtros[$k]['valor'] . "%' OR p_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR s_apellido ILIKE '%" . $filtros[$k]['valor'] . "%' OR c_apellido ILIKE '%" . $filtros[$k]['valor'] . "%')";
							} else {
								$where .= $filtros[$k]['columna'] . " ILIKE '%" . $filtros[$k]['valor'] . "%'";
							}
							break;
						case 'EQUAL':
							$cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
							if (isset($generalConfigForAllColumns[$filtros[$k]['columna']]['type'])) {
								//$where .= $filtros[$k]['columna']." LIKE '".$filtros[$k]['valor']."'";
								switch (@$generalConfigForAllColumns[$filtros[$k]['columna']]['type']) {
									case 'int4':
									case 'numeric':
									case 'date':
										//$whereEqueals .= $filtros[$k]['columna']." = '".$filtros[$k]['valor']."'";
										$where .= $filtros[$k]['columna'] . " = '" . $filtros[$k]['valor'] . "'";
										break;
									case 'varchar':
									case 'bpchar':
										//$whereEqueals .= $filtros[$k]['columna']." LIKE '".$filtros[$k]['valor']."'";
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
			$obj = new Ppostulantes();
//			$obj = new Planpagos();
			if ($where != "") $where = " where " . $where;
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
//			$resul = $obj->lista(0, $where, $groups);
			$resul = $obj->listaRegistrate($where,$groups);

			$relaboral = array();
			foreach ($resul as $v) {
				$relaboral[] = array(
					'apellidos' => $v->apellidos,
					'nombre' => $v->nombre,
					'ci' => $v->ci,
					'sexo' => $v->sexo,
					'grado' => $v->grado,
					'institucion' => $v->institucion,
					'valor_1' => $v->valor_1,
					'fecha_nac' => $v->fecha_nac,
					'nacionalidad' => $v->nacionalidad,
					'estado_civil' => $v->estado_civil,
					'direccion' => $v->direccion,
					'telefono' => $v->telefono,
					'celular' => $v->celular,
					'correo' => $v->correo,
					'libreta_militar' => $v->libreta_militar,
					'conalpedis' => $v->conalpedis,
					'reg_dominio' => $v->reg_dominio,
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
			if (count($relaboral) > 0) {
				$excel->RowTitle($colTitleSelecteds, $fila);
				$celdaInicial = $excel->primeraLetraCabeceraTabla . $fila;
				$celdaFinalDiagonalTabla = $excel->ultimaLetraCabeceraTabla . $fila;
				if ($excel->debug == 1) {
					echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
					print_r($relaboral);
					echo "<p>|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||<p></p>";
				}
				foreach ($relaboral as $i => $val) {
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
						$rowData = $excel->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
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
				$excel->display("AppData/reporte_bolsa_trabajo.xls", "I");
			}
			#endregion Proceso de generación del documento PDF
		}
	}


	/**
	 * Función para la obtención del reporte de relaciones laborales en formato PDF.
	 * @param $n_rows Cantidad de lineas
	 * @param $gestion_consulta
	 * @param $columns Array con las columnas mostradas en el reporte
	 * @param $filtros Array con los filtros aplicados sobre las columnas.
	 * @param $groups String con la cadena representativa de las columnas agrupadas. La separación es por comas.
	 * @param $sorteds
	 */
	public function exportpdfAction($n_rows, $columns, $filtros, $groups, $sorteds)
	{
		$this->view->disable();
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
			'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align' => 'C', 'align' => 'C', 'type' => 'int4'),
			'apellidos' => array('title' => 'Apellidos', 'width' => 25, 'align' => 'L', 'type' => 'varchar'),
			'nombre' => array('title' => 'Nombres', 'width' => 25, 'align' => 'L', 'type' => 'varchar'),
			'ci' => array('title' => 'CI', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
			'sexo' => array('title' => 'Sexo', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
			'grado' => array('title' => 'Grado', 'width' => 25, 'align' => 'C', 'type' => 'varchar'),
			'institucion' => array('title' => 'Institución', 'width' => 25, 'align' => 'L', 'type' => 'varchar'),
			'valor_1' => array('title' => 'Titulo', 'width' => 25, 'align' => 'L', 'type' => 'varchar'),
			'fecha_nac' => array('title' => 'Fecha Nacimiento', 'width' => 18, 'align' => 'C', 'type' => 'date'),
			'nacionalidad' => array('title' => 'Nacionalidad', 'width' => 18, 'align' => 'L', 'type' => 'varchar'),
			'estado_civil' => array('title' => 'Estado Civil', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
			'direccion' => array('title' => 'Dirección', 'width' => 25, 'align' => 'L', 'type' => 'varchar'),
			'telefono' => array('title' => 'Telefono', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
			'celular' => array('title' => 'Celular', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
			'correo' => array('title' => 'Correo', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
			'libreta_militar' => array('title' => 'Libreta Militar', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
			'conalpedis' => array('title' => 'CONALPEDIS', 'width' => 15, 'align' => 'L', 'type' => 'varchar'),
			'reg_dominio' => array('title' => 'Dominio', 'width' => 20, 'align' => 'L', 'type' => 'varchar')
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
			$pdf->title_rpt = utf8_decode('Reporte Bolsa de Trabajo');
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
												$fecha = date("d-m-Y", strtotime($fecha));
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
			$obj = new Ppostulantes();
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
					$groups = " ORDER BY " . $groups;
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
			//$resul = $obj->getAllWithPersonsByGestion($gestion_consulta, $where, $groups);
		//	$where = str_replace("'", "''", $where);
			$resul = $obj->listaRegistrate($where, $groups);
			$relaboral = array();
			foreach ($resul as $v) {
				$relaboral[] = array(
					'apellidos' => $v->apellidos,
					'nombre' => $v->nombre,
					'ci' => $v->ci,
					'sexo' => $v->sexo,
					'grado' => $v->grado,
					'institucion' => $v->institucion,
					'valor_1' => $v->valor_1,
					'fecha_nac' => $v->fecha_nac,
					'nacionalidad' => $v->nacionalidad,
					'estado_civil' => $v->estado_civil,
					'direccion' => $v->direccion,
					'telefono' => $v->telefono,
					'celular' => $v->celular,
					'correo' => $v->correo,
					'libreta_militar' => $v->libreta_militar,
					'conalpedis' => $v->conalpedis,
					'reg_dominio' => $v->reg_dominio,

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

			if (count($relaboral) > 0) {
				foreach ($relaboral as $i => $val) {
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
						$rowData = $pdf->DefineRows($j + 1, $relaboral[$j], $colSelecteds);
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
			if ($pdf->debug == 0) $pdf->Output('reporte_bolsa_trabajo.pdf', 'I');
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


}
?>