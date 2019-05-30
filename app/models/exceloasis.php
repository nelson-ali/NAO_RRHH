<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 11/12/2014
 * Time: 8:58
 */
require_once('../app/libs/phpexcel180/PHPExcel.php');
require_once('../app/libs/phpexcel180/PHPExcel/IOFactory.php');

class exceloasis extends PHPExcel
{
    public $title_rpt = '';
    public $title_total_rpt = '';
    public $title_sheet_rpt = 'Reporte';
    public $header_title_estado_rpt = 'Estado Plurinacional de Bolivia';
    public $header_title_empresa_rpt = 'Empresa Estatal de Transporte por Cable "Mi Teleferico"';
    public $style_header_table = '';
    public $style_footer_table = '';
    var $debug;              //Valor de seguimiento 1: Hacer debug; 0: No hacer debug
    var $widths;             //Array de anchuras
    var $aligns;             //Array de alineaciones
    var $pageWidth;          //Ancho de la hoja (Sea si esta vertical u horizontal)
    var $tableWidth;         //Ancho de la tabla
    var $FechaHoraReporte;     //Fecha y hora del reporte
    var $FechaHoraCreacion;     //Fecha y hora de creación
    var $IdUsrPrint;         //Identificador del usuario impresor
    var $ContadorFilas;         //Contador de filas
    var $PrimerNumero;         //Primer número de la lista en la página
    var $UltimoNumero;         //Último número de la lista en la página
    var $TotalNumeros;         //Total de números de la lista
    var $ShowLeftFooter;     //Opción para mostrar el pie de página izquierdo.
    var $ShowNumeralLeftFooter;    //Opción para mostrar el pie de página izquierdo (Numeral).
    var $Condicion;            //Opción para conocer que tipo de condición tiene un determinado formulario
    var $angle;             //Angulo
    var $generalConfigForAllColumns;    //Array multidimencional con todas las configuraciones necesarias para el despliegue de valores
    var $widthsSelecteds;       //Anchuras seleccionadas
    //var $colTitleSelecteds;     //Titulos de las columnas seleccionadas
    var $alignSelecteds;        //Alineaciones de la columnas seleccionadas
    var $columnasExcel = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
        "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ",
        "BA", "BB", "BC", "BD", "BE", "BF", "BG", "BH", "BI", "BJ", "BK", "BL", "BM", "BN", "BO", "BP", "BQ", "BR", "BS", "BT", "BU", "BV", "BW", "BX", "BY", "BZ",
        "CA", "CB", "CC", "CD", "CE", "CF", "CG", "CH", "CI", "CJ", "CK", "CL", "CM", "CN", "CO", "CP", "CQ", "CR", "CS", "CT", "CU", "CV", "CW", "CX", "CY", "CZ");
    //Array con las letras de las columnas del archivo excel. Se calcula este numero máximo de columnas a usarse.
    var $ultimaLetraCabeceraTabla; //Ultima letra que coincide con la última columna del reporte.
    var $penultimaLetraCabeceraTabla; //penúltima letra que coincide con la última columna del reporte.
    var $numFilaCabeceraTabla;  //Número de fila que corresponde a la cabecera de la tabla del reporte.
    var $primeraLetraCabeceraTabla; //Primera letra que corresponde donde empieza la tabla del reporte.
    var $segundaLetraCabeceraTabla; //Segunda letra que corresponde donde empieza la tabla del reporte.
    var $celdaInicial;             //Representación de la celda donde empieza el reporte: [LETRA_COLUMNA][NUMERO_FILA]
    var $celdaFinal;            //Representación de la celda donde termina el reporte: [LETRA_COLUMNA][NUMERO_FILA]
    var $celdaFinalDiagonalTabla;//Celda final diagonal
    const ALINEACION = '';


    /**
     * Función para establecer la cabecera del reporte.
     */
    function Header()
    {
        $this->getProperties()->setCreator("Javier Loza")
            ->setLastModifiedBy("Javier Loza")
            ->setTitle("Reporte exportado en formato Excel")
            ->setSubject("Reporte exportado en formato Excel")
            ->setDescription("documento para la exportacion de Office 2007 XLSX, generado usando clases PHP.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Archivo resultado");


        // Creación de la primera página
        $this->setActiveSheetIndex(0);

        $this->getActiveSheet()->setCellValue('B1', 'Estado Plurinacional de Bolivia');
        $this->getActiveSheet()->setCellValue('B2', 'Empresa Estatal de Transporte Por Cable "Mi Teleférico"');
        $this->getActiveSheet()->setCellValue('B3', ($this->title_rpt != false && $this->title_rpt != '') ? $this->title_rpt : 'Reporte Relación Laboral');
        //$this->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
        //$this->getActiveSheet()->getStyle('D1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
        //$this->getActiveSheet()->setCellValue('E1', '#12566');
        $this->getActiveSheet()->getStyle('B1:B3')->getFont()->setName('Arial');
        $this->getActiveSheet()->getStyle('B1')->getFont()->setSize(14);
        $this->getActiveSheet()->getStyle('B2:B3')->getFont()->setSize(12);
        $this->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
        $this->getActiveSheet()->getStyle('B1:B3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUEMITELEFERICO);

        #region Combinación de celdas para la cabecera (3 primeras filas)
        $key1 = array_search($this->segundaLetraCabeceraTabla, $this->columnasExcel);
        $key2 = array_search($this->ultimaLetraCabeceraTabla, $this->columnasExcel);
        if ($key1 < $key2) {
            /**
             * En caso de que sólo se muestre una columna.
             */
            $this->getActiveSheet()->mergeCells($this->segundaLetraCabeceraTabla . '1:' . $this->penultimaLetraCabeceraTabla . '1');
            $this->getActiveSheet()->mergeCells($this->segundaLetraCabeceraTabla . '2:' . $this->penultimaLetraCabeceraTabla . '2');
            $this->getActiveSheet()->mergeCells($this->segundaLetraCabeceraTabla . '3:' . $this->penultimaLetraCabeceraTabla . '3');
        }
        #endregion Combinación de celdas para la cabecera (3 primeras filas)

        #region Centrando los dos líneas que corresponden a las cabeceras.
        $this->getActiveSheet()->getStyle('B1:B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        #endregion Centrando los dos líneas que corresponden a las cabeceras.

        #region Estableciendo el lugar de los logotipos (Imágenes)
        // Add a drawing to the worksheet
        //echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Escudo');
        $objDrawing->setDescription('Escudo de Bolivia');
        $objDrawing->setPath('./images/escudo.jpg');
        $objDrawing->setOffsetX(5);
        $objDrawing->setHeight(50);
        $objDrawing->setWorksheet($this->getActiveSheet());

        // Add a drawing to the worksheet
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo Mi Teleferico');
        $objDrawing->setPath('./images/logoMT.jpg');
        $objDrawing->setCoordinates($this->ultimaLetraCabeceraTabla . '1');
        $objDrawing->setOffsetX(5);
        $objDrawing->setHeight(50);
        $objDrawing->setWorksheet($this->getActiveSheet());
        #endregion Estableciendo el lugar de los logotipos (Imágenes)

        #region para el establecimiento de las cabeceras y pie de páginas del documento
        $this->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte ' . $this->title_sheet_rpt . '&RImpreso el &D');
        $this->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->getProperties()->getTitle() . '&RPagina &P de &N');
        #endregion para el establecimiento de las cabeceras y pie de páginas del documento

        // Nombrando la primera pestaña
        $this->getActiveSheet()->setTitle($this->title_sheet_rpt);
    }

    /**
     * Función para establecer la orientación del documento.
     * @param $orientation
     */
    public function defineOrientation($orientation)
    {

        switch ($orientation) {
            case 'H':
                $this->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                break;
            case 'V':
                $this->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
                break;
            default:
                $this->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
                break;
        }
    }

    /**
     * Función para definir el tamaño del documento
     * @param $size
     */
    public function defineSize($size)
    {

        switch ($size) {
            case 'C':
                $this->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
                break;
            case 'O':
                $this->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
                break;
            default:
                $this->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_A4);
                break;
        }
    }

    /**
     * Función para definir el borde por grupo de datos
     * @param $celdaInicial
     * @param $celdaFinalDiagonalTabla
     * @throws PHPExcel_Exception
     */
    public function borderGroup($celdaInicial, $celdaFinalDiagonalTabla)
    {
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '4F81BD'),
                ),
            ),
        );
        $this->getActiveSheet()->getStyle($celdaInicial . ':' . $celdaFinalDiagonalTabla)->applyFromArray($styleThinBlackBorderOutline);
    }

    /**
     * Función para crear una hoja adicional al establecido
     * @throws PHPExcel_Exception
     */
    public function secondPage()
    {

        // Creando una nueva página en la cual se establece recomendaciones
        $this->createSheet();

        $recomendaciones = 'El documento que acaba de exportar tiene información importante.';

        $this->setActiveSheetIndex(1);
        $this->getActiveSheet()->setCellValue('A1', 'Recomendaciones');
        $this->getActiveSheet()->setCellValue('A3', $recomendaciones);

        $this->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;

    }

    /**
     * Función para el despliegue de un registro correspondiente.
     * @param $data Array con el listado de datos a desplegarse.
     * @param $alignSelecteds Array con el listado de alineaciones correspondientes por columna.
     * @param $formatTypes Array con el listado de typos de datos que se almacenan en cada columna.
     * @param $fila Número de fila
     * @param $celdacolor Array con la definición del color asignado.
     */
    function Row($data, $alignSelecteds, $formatTypes, $fila, $celdacolor = array(), $border = true)
    {
        $arr = array();
        for ($i = 0; $i <= count($data); $i++) {
            if (isset($data[$i]) && isset($this->columnasExcel[$i])) {
                $dato = $data[$i];
                /*if($formatTypes[$i]=='date'){
                    $dato = str_replace("-","/",$dato);
                }*/
                /**
                 * Si existe alguna celda que requiere un color diferente.
                 */
                if (count($celdacolor) > 0 && isset($celdacolor[$i])) {
                    $this->getActiveSheet()->getColumnDimension($this->columnasExcel[$i])->setAutoSize(true);
                    $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->applyFromArray(
                        array(
                            'font' => array(
                                'bold' => false,
                            ),
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            ),
                            'borders' => ($border) ? array(
                                'top' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                ), 'left' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            , 'right' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                ), 'bottom' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            ) : array(),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startcolor' => array(
                                    'argb' => $celdacolor[$i]
                                ),
                                'endcolor' => array(
                                    'argb' => $celdacolor[$i]
                                )
                            )
                        )
                    );
                }
                $this->getActiveSheet()->setCellValue($this->columnasExcel[$i] . $fila, $dato);
                switch ($alignSelecteds[$i]) {
                    case 'C':
                        $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        break;
                    case 'R':
                        $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        break;
                    case 'L':
                        $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        break;
                    case 'J':
                        $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
                        break;
                }
                switch ($formatTypes[$i]) {
                    case 'int4':
                        $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
                        break;
                    case 'varchar':
                    case 'bpchar':
                        $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                        break;
                    case 'date':
                        $this->getActiveSheet()->getStyle($this->columnasExcel[$i] . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
                        break;
                }

            }
        }
    }
    /**
     * Función para establecer los anchos de columnas.
     */
    function setWidthForColumns()
    {
        $arr = $this->widthsSelecteds;
        if (count($arr > 0)) {
            for ($i = 0; $i < count($arr); $i++) {
                $this->getActiveSheet()->getColumnDimension($this->columnasExcel[$i])->setWidth($this->widthsSelecteds[$i]);
            }
        }
    }
    /**
     * Función para el despliegue de la fila con un agrupador
     * @param $data
     * @param $fila
     * @throws PHPExcel_Exception
     */
    function Agrupador($data, &$fila)
    {
        if (count($data) > 0) {

            for ($i = 0; $i < count($data); $i++) {
                if (isset($data[$i]) && isset($this->columnasExcel[$i])) {
                    if ($this->debug == 1) {
                        echo "<p>>" . $i . " con valor--->" . $data[$i];
                    }
                    $this->getActiveSheet()->setCellValue('A' . $fila, $data[$i]);
                    $this->getActiveSheet()->mergeCells('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila);
                    $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUEMITELEFERICO);
                    #region Definiendo el estilo de la celda de cabecera del reporte
                    $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->applyFromArray(
                        array(
                            'font' => array(
                                'bold' => true
                            ),
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                            ),
                            'borders' => array(
                                'top' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startcolor' => array(
                                    'argb' => '4F81BD'
                                ),
                                'endcolor' => array(
                                    'argb' => '4F81BD'
                                )
                            )
                        )
                    );
                    $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
                    #endregion Definiendo el estilo de la celda de cabecera del reporte
                } else {
                    if ($this->debug == 1) {
                        echo "<p>>" . $i . " sin valor--->" . $data[$i];
                    }

                    $this->getActiveSheet()->setCellValue('A' . $fila, '');
                    $this->getActiveSheet()->mergeCells('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila);
                    $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUEMITELEFERICO);
                    #region Definiendo el estilo de la celda de cabecera del reporte
                    $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->applyFromArray(
                        array(
                            'font' => array(
                                'bold' => true
                            ),
                            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                            ),
                            'borders' => array(
                                'top' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startcolor' => array(
                                    'argb' => '4F81BD'
                                ),
                                'endcolor' => array(
                                    'argb' => '4F81BD'
                                )
                            )
                        )
                    );
                    $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
                    #endregion Definiendo el estilo de la celda de cabecera del reporte


                }
                $fila++;
            }
        }
    }

    /**
     * Función para definir la fila de títulos de la tabla
     * @param array $data
     * @version 17-02-2012
     */
    function RowTitle($colTitleSelecteds, &$fila)
    {

        for ($i = 0; $i <= count($colTitleSelecteds); $i++) {
            if (isset($colTitleSelecteds[$i]) && isset($this->columnasExcel[$i]))
                $this->getActiveSheet()->setCellValue($this->columnasExcel[$i] . $fila, $colTitleSelecteds[$i]);
            $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUEMITELEFERICO);
            #region Definiendo el estilo de la celda de cabecera del reporte
            $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'borders' => array(
                        'top' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => array(
                            'argb' => '4F81BD'
                        ),
                        'endcolor' => array(
                            'argb' => '4F81BD'
                        )
                    )
                )
            );
            $this->getActiveSheet()->getStyle('A' . $fila . ':' . $this->ultimaLetraCabeceraTabla . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
            #endregion Definiendo el estilo de la celda de cabecera del reporte
        }
        $fila++;
    }

    #region nuevas funciones
    function tablaHorizontal($cabeceraHorizontal, $datosHorizontal)
    {
        $this->cabeceraHorizontal($cabeceraHorizontal);
        $this->datosHorizontal($datosHorizontal);
    }

    /**
     * Función para definir las columnas a mostrarse.
     * @param $widthAlignAll
     * @param $columns
     * @return array
     */
    function DefineCols($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = "nro_row";
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $key;
                }
            }
        }
        return $arrRes;
    }

    function DefineTotalCols($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = "nro_row";
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $key;
                }
            }
        }
        return $arrRes;
    }

    /*
     * Función para la definición de los contenidos de la cabecera.
     * @param $widthAlignAll
     * @param $columns
     * @return array
     */
    function DefineTitleCols($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = "Nro.";
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0) {
                        $arrRes[] = $widthAlignAll[$key]['title'];
                    }
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para definir el tipo de datos para cada columna
     * @param $widthAlignAll
     * @param $columns
     * @param array $exclude
     * @return array
     */
    function DefineTypeCols($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = "int4";
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0) {
                        $arrRes[] = $widthAlignAll[$key]['type'];
                    }
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para definir las columnas a mostrarse, de acuerdo a que hayan sido designados para un grupo en particular, en ese caso no se los considera.
     * @param $widthAlignAll
     * @param $dondeCambio
     * @param $queCambio
     * @return array
     */
    function DefineTitleColsByGroups($widthAlignAll, $dondeCambio, $queCambio)
    {
        $arrRes = Array();
        foreach ($dondeCambio as $val) {
            if (isset($widthAlignAll[$val]['title'])) {
                $arrRes[] = $queCambio[$val];
            }
        }

        return $arrRes;
    }

    /**
     * Función para la definición de las alignaciones de las cabeceras de la tabla.
     * @param $numCols
     * @return array
     */
    function DefineTitleAligns($numCols)
    {
        $arrRes = Array();
        for ($i = 0; $i <= $numCols; $i++) {
            $arrRes[] = "C";
        }
        return $arrRes;
    }

    /**
     * Función para definir el contenido de la fila a mostrarse.
     * @param int $numRow
     * @param $rowRelaboral
     * @param $colSelecteds
     * @return array
     */
    function DefineRows($numRow = 0, $rowRelaboral, $colSelecteds)
    {
        $arrRes = Array();
        $arrRes[] = $numRow;
        foreach ($colSelecteds as $val) {
            if (array_key_exists($val, $rowRelaboral)) {
                $arrRes[] = $rowRelaboral[$val];
            }
        }
        return $arrRes;
    }

    /*
     * Función para la generación del array con las alineaciones de columna en el cuerpo de la tabla.
     * @param $generalWiths Array de los anchos y alineaciones de columnas disponibles.
     * @param $columns Array de las columnas con las propiedades de oculto (hidden:1) y visible (hidden:null).
     * @return array Array con el listado de alineaciones a desplegarse.
     */
    function DefineAligns($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        $arrRes[] = 'C';
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if (!isset($val['hidden']) || $val['hidden'] != true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $widthAlignAll[$key]['align'];
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para establecer los valores por defecto (Vacios) para los grupos seleccionados.
     * @param $groups
     * @return array
     */
    function DefineDefaultValuesForGroups($groups)
    {
        $arrRes = Array();
        if ($groups != "") {
            $gr = explode(",", $groups);
            if (count($gr) > 0) {
                foreach ($gr as $val) {
                    $arrRes[$val] = array("valor" => "");
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para la obtención del listado de columnas a mostrarse descontando los que se han solicitado en agrupador.
     * @param $colTitleSelecteds
     * @param $agrupadores
     * @return array
     */
    function defineListadoSinColumnasEnAgrupador($colTitleSelecteds, $agrupadores)
    {
        $arrRes = Array();
        if (count($colTitleSelecteds) > 0 && count($agrupadores) > 0) {
            foreach ($colTitleSelecteds as $val) {
                if (!in_array($val, $agrupadores)) {
                    $arrRes[] = $val;
                }
            }
        } else $arrRes = $colTitleSelecteds;

        return $arrRes;
    }

    /**
     * Función para el despliegue del reporte.
     * @param $nombreArchivo
     * @param $alineacion
     */
    function display($nombreArchivo, $alineacion)
    {
        $callStartTime = microtime(true);
        $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel2007');
        //$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        //$objWriter->save(str_replace('.php', '.xlsx', $nombreArchivo));
        $objWriter->save($nombreArchivo);
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_excel.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * Función para la agregación de una nueva página al reporte en formato excel a objeto de mostrar un resumen de totales
     * @param array $arrListado
     * @param array $totalColSelecteds
     * @param int $totalAtrasos
     * @param int $totalFaltas
     * @param int $totalAbandono
     * @param int $totalOmision
     * @param int $totalLsgh
     * @param int $totalCompensacion
     * @throws Exception
     */
    function agregarPaginaTotales($arrListado = array(), $totalColSelecteds = array(), $totalTitleColSelecteds = array(), $totalAtrasos = 0, $totalAtrasados = 0, $totalFaltas = 0, $totalAbandono = 0, $totalOmision = 0, $totalLsgh = 0, $totalAgrupador = 0, $totalDescanso = 0, $totalCompensacion = 0)
    {

        $this->createSheet();
        $this->setActiveSheetIndex(1);
        $fila = 4;
        $contador = 1;
        $i = 0;
        $cantColTotal = count($totalColSelecteds);
        $primeraLetra = "A";
        $ultimaLetra = "A";
        $letraInicioTotales = "L";
        $letrasUsadas = array();
        foreach ($this->columnasExcel as $letra) {
            $this->getActiveSheet()->setCellValue($letra . $fila, $totalTitleColSelecteds[$i]);
            $this->getActiveSheet()->getColumnDimension($letra)->setAutoSize(true);
            $i++;
            $ultimaLetra = $letra;
            $letrasUsadas[] = $letra;
            if ($cantColTotal < ($i + 1)) break;
        }
        $this->getActiveSheet()->getStyle($primeraLetra . $fila . ':' . $ultimaLetra . $fila)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => '4F81BD'
                    ),
                    'endcolor' => array(
                        'argb' => '4F81BD'
                    )
                )
            )
        );
        $this->getActiveSheet()->getStyle($primeraLetra . $fila . ':' . $ultimaLetra . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $fila++;

        foreach ($arrListado as $clave2 => $valor2) {
            foreach ($valor2 as $clave1 => $valor1) {
                foreach ($valor1 as $clave => $valor) {
                    $j = 0;
                    foreach ($letrasUsadas as $letra) {

                        if ($letra == $primeraLetra) {
                            $this->getActiveSheet()->getStyle($letra . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $this->getActiveSheet()->setCellValue($letra . $fila, $contador);
                        } else {
                            $this->getActiveSheet()->getStyle($letra . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                            if (isset($valor[$totalColSelecteds[$j]])) {
                                $this->getActiveSheet()->setCellValue($letra . $fila, $valor[$totalColSelecteds[$j]]);
                            } else {
                                $this->getActiveSheet()->setCellValue($letra . $fila, NULL);
                            }

                        }
                        $j++;
                    }
                    $fila++;
                    $contador++;
                }
            }
        }
        $this->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;
        $this->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        // Rename second worksheet
        $this->getActiveSheet()->setTitle('Totales');

        $penultimaLetraCabeceraTabla = "Q";
        $this->getActiveSheet()->setCellValue('B1', 'Estado Plurinacional de Bolivia');
        $this->getActiveSheet()->setCellValue('B2', 'Empresa Estatal de Transporte Por Cable "Mi Teleférico"');
        $this->getActiveSheet()->setCellValue('B3', ($this->title_rpt != false && $this->title_rpt != '') ? $this->title_rpt : 'Reporte Relación Laboral');
        $this->getActiveSheet()->getStyle('B1:B3')->getFont()->setName('Arial');
        $this->getActiveSheet()->getStyle('B1')->getFont()->setSize(14);
        $this->getActiveSheet()->getStyle('B2:B3')->getFont()->setSize(12);
        $this->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
        $this->getActiveSheet()->getStyle('B1:B3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUEMITELEFERICO);

        #region Combinación de celdas para la cabecera (3 primeras filas)
        $this->getActiveSheet()->mergeCells($this->segundaLetraCabeceraTabla . '1:' . $penultimaLetraCabeceraTabla . '1');
        $this->getActiveSheet()->mergeCells($this->segundaLetraCabeceraTabla . '2:' . $penultimaLetraCabeceraTabla . '2');
        $this->getActiveSheet()->mergeCells($this->segundaLetraCabeceraTabla . '3:' . $penultimaLetraCabeceraTabla . '3');
        #endregion Combinación de celdas para la cabecera (3 primeras filas)

        #region Centrando los dos líneas que corresponden a las cabeceras.
        $this->getActiveSheet()->getStyle('B1:B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        #endregion Centrando los dos líneas que corresponden a las cabeceras.

        #region Estableciendo el lugar de los logotipos (Imágenes)
        // Add a drawing to the worksheet
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Escudo');
        $objDrawing->setDescription('Escudo de Bolivia');
        $objDrawing->setPath('./images/escudo.jpg');
        $objDrawing->setOffsetX(5);
        $objDrawing->setHeight(50);
        $objDrawing->setWorksheet($this->getActiveSheet());

        // Add a drawing to the worksheet
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo Mi Teleferico');
        $objDrawing->setPath('./images/logoMT.jpg');
        $objDrawing->setCoordinates($ultimaLetra . '1');
        $objDrawing->setOffsetX(5);
        $objDrawing->setHeight(50);
        $objDrawing->setWorksheet($this->getActiveSheet());
        #endregion Estableciendo el lugar de los logotipos (Imágenes)

        #region para el establecimiento de las cabeceras y pie de páginas del documento
        $this->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte Relación laboral&RImpreso el &D');
        $this->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->getProperties()->getTitle() . '&RPagina &P de &N');
        #endregion para el establecimiento de las cabeceras y pie de páginas del documento

        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '4F81BD'),
                ),
            ),
        );
        #region Sector para añadir la fila de totales globales
        //$this->getActiveSheet()->mergeCells('A'.$fila.':0'.$fila);
        $this->getActiveSheet()->getStyle($primeraLetra . $fila . ':' . $letraInicioTotales . $fila)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => '4F81BD'
                    ),
                    'endcolor' => array(
                        'argb' => '4F81BD'
                    )
                )
            )
        );
        $sw = false;
        $h = 0;
        $k = 0;
        $arrCantidadesTotales = array("Totales:", $totalAtrasos, $totalAtrasados, $totalFaltas, $totalAbandono, $totalOmision, $totalLsgh, $totalAgrupador, $totalDescanso, $totalCompensacion);
        foreach ($letrasUsadas as $letra) {
            if ($letraInicioTotales == $letra) $sw = true;
            if ($sw) {
                $this->getActiveSheet()->getStyle($letra . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->getActiveSheet()->setCellValue($letra . $fila, $arrCantidadesTotales[$k]);
                $k++;
            } else {
                $this->getActiveSheet()->getStyle($letra . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->getActiveSheet()->setCellValue($letra . $fila, "");
            }
        }
        #endregion Sector para añadir la fila de totales globales
        $celdaInicial = "A4";
        $celdaFinalDiagonalTabla = "";
        $this->getActiveSheet()->getStyle($celdaInicial . ':' . $ultimaLetra . $fila)->applyFromArray($styleThinBlackBorderOutline);

        // Estableciendo la hoja a mostrarse
        $this->setActiveSheetIndex(0);
    }

    /**
     * Función para obtener las columnas establecidas para el cálculo de totales y seleccionadas para aparecer.
     * @param $generalConfigForAllColumns
     * @param $columns
     * @param array $exclude
     * @return array
     */
    function DefineSelectedTotalColsWithExclude($widthAlignAll, $columns, $exclude = array())
    {
        $arrRes = Array();
        foreach ($columns as $key => $val) {
            if (isset($widthAlignAll[$key])) {
                if ((!isset($val['hidden']) || $val['hidden'] != true) && $widthAlignAll[$key]["totales"] === true) {
                    if (!in_array($key, $exclude) || count($exclude) == 0)
                        $arrRes[] = $key;
                }
            }
        }
        return $arrRes;
    }

    /**
     * Función para conocer el listado de columnas para el resumen de totales
     * @param $generalConfigForAllColumns
     * @return array
     */
    function DefineSelectedTotalCols($generalConfigForAllColumns)
    {
        $arrRes = Array();
        $arrRes[] = "nro_row";
        foreach ($generalConfigForAllColumns as $key => $val) {
            if ($val['totales'] === true) {
                $arrRes[] = $key;
            }
        }
        return $arrRes;
    }

    /**
     * Función para definir las columnas a considerarse dentro de la grilla de totales
     * @param $widthAlignAll
     * @param $columns
     * @param array $exclude
     * @return array
     */
    function DefineTotalTitleCols($widthAlignAll, $totalCols)
    {
        $arrRes = Array();
        foreach ($totalCols as $key => $val) {
            if (array_key_exists($val, $widthAlignAll)) {
                $arrRes[] = $widthAlignAll[$val]['title'];
            }
        }
        return $arrRes;
    }

    /**
     * Función para la obtención de la fila que contiene los totales
     * @param $colSelecteds
     * @param $colTotalSelecteds
     * @param $arrTodosTotales
     * @return array
     */
    public function generaFilaTotales($colSelecteds, $colTotalSelecteds, $arrTodosTotales)
    {
        $arrTotales = array();
        $sw = 0;
        $clave = -1;
        if (count($colSelecteds) > 0 && count($colTotalSelecteds) > 0) {
            foreach ($colSelecteds as $val) {
                if (in_array($val, $colTotalSelecteds)) {
                    //echo "$val<------>".$colTotalSelecteds[$val]."&&".in_array($val,$colTotalSelecteds);
                    $arrTotales [] = $arrTodosTotales[$val];
                    $sw = 1;
                } else {
                    $arrTotales [] = '';
                }
                if ($sw == 0) {
                    $clave++;
                }
            }
            if ($sw == 1) {
                if ($clave >= 0) {
                    $arrTotales[$clave] = "Totales:";
                }
            } else {
                $arrTotales = array();
            }
        }
        return $arrTotales;
    }

    /**
     * Función para la agregación de una página de totales en el reporte transpuesto.
     * @param $idRelaboral
     * @param $fechaIni
     * @param $fechaFin
     * @param $totalTitleColSelecteds
     * @param $totalValuesColSelecteds
     * @throws PHPExcel_Exception
     */
    function agregarPaginaTotalesTrans($fila, $totalTitleColSelecteds, $totalValuesColSelecteds)
    {
        $fila = $fila + 2;
        $i = 0;
        $cantColTotal = count($totalTitleColSelecteds);
        $primeraLetra = "A";
        $ultimaLetra = "E";
        $this->getActiveSheet()->getStyle($primeraLetra . $fila . ':' . $ultimaLetra . $fila)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                )
            )
        );
        $this->getActiveSheet()->setCellValue($primeraLetra . $fila, "Totales");
        $fila++;
        $letrasUsadas = array();
        foreach ($this->columnasExcel as $letra) {
            $this->getActiveSheet()->setCellValue($letra . $fila, $totalTitleColSelecteds[$i]);
            $this->getActiveSheet()->getColumnDimension($letra)->setAutoSize(true);
            $i++;
            $ultimaLetra = $letra;
            $letrasUsadas[] = $letra;
            if ($cantColTotal < ($i + 1)) break;
        }
        $this->getActiveSheet()->getStyle($primeraLetra . $fila . ':' . $ultimaLetra . $fila)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => '4F81BD'
                    ),
                    'endcolor' => array(
                        'argb' => '4F81BD'
                    )
                )
            )
        );
        $this->getActiveSheet()->getStyle($primeraLetra . $fila . ':' . $ultimaLetra . $fila)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $this->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;
        $this->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);

        // Rename second worksheet

        #region para el establecimiento de las cabeceras y pie de páginas del documento
        $this->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BReporte Horarios Vs. Marcaciones&RImpreso el &D');
        $this->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->getProperties()->getTitle() . '&RPagina &P de &N');
        #endregion para el establecimiento de las cabeceras y pie de páginas del documento

        #region Sector para añadir la fila de totales globales

        $fila++;
        $j = 0;
        foreach ($this->columnasExcel as $letra) {
            $this->getActiveSheet()->getStyle($letra . $fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->getActiveSheet()->setCellValue($letra . $fila, $totalValuesColSelecteds[$j]);
            $j++;
            if ($cantColTotal < ($j + 1)) break;
        }
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '4F81BD'),
                ),
            ),
        );
        $this->getActiveSheet()->getStyle($primeraLetra . $fila . ':' . $ultimaLetra . $fila)->applyFromArray($styleThinBlackBorderOutline);
        #endregion Sector para añadir la fila de totales globales
        $this->setActiveSheetIndex(0);
    }
    #endregion nuevas funciones
}