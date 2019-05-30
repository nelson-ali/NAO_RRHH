<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';


// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("Javier Loza")
							 ->setLastModifiedBy("Javier Loza")
							 ->setTitle("Office 2007 XLSX Reporte Relaborales")
							 ->setSubject("Office 2007 XLSX Reporte Relaborales")
							 ->setDescription("documento para la exportacion de Office 2007 XLSX, generado usando clases PHP.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Archivo resultado");


// Create a first sheet, representing sales data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Estado Plurinacional de Bolivia');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Empresa Estatal de Transporte Por Cable "Mi Teleférico"');
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Reporte Relación Laboral');
$objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ));
$objPHPExcel->getActiveSheet()->getStyle('D1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
$objPHPExcel->getActiveSheet()->setCellValue('E1', '#12566');

//$data = array( array("firstname" => "Mary", "lastname" => "Johnson", "age" => 25), array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18), array("firstname" => "James", "lastname" => "Brown", "age" => 31), array("firstname" => "Patricia", "lastname" => "Williams", "age" => 7), array("firstname" => "Michael", "lastname" => "Davis", "age" => 43), array("firstname" => "Sarah", "lastname" => "Miller", "age" => 24), array("firstname" => "Patrick", "lastname" => "Miller", "age" => 27) );

//$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A2');
//$objPHPExcel->getActiveSheet()->fromArray(array("firstname" => "Amanda", "lastname" => "Miller", "age" => 18), null, 'A10');

$generalConfigForAllColumns = array(
    'nro_row' => array('title' => 'Nro.', 'width' => 8, 'title-align'=>'C','align' => 'C', 'type' => 'int4'),
    'ubicacion' => array('title' => 'Ubicacion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
    'condicion' => array('title' => 'Condicion', 'width' => 20, 'align' => 'C', 'type' => 'varchar'),
    'estado_descripcion' => array('title' => 'Estado', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
    'nombres' => array('title' => 'Nombres', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
    'ci' => array('title' => 'CI', 'width' => 12, 'align' => 'C', 'type' => 'varchar'),
    'expd' => array('title' => 'Exp.', 'width' => 8, 'align' => 'C', 'type' => 'bpchar'),
    'fecha_caducidad' => array('title' => 'Fecha Cad', 'width' => 18, 'align' => 'C', 'type' => 'bpchar'),
    'gerencia_administrativa' => array('title' => 'Gerencia', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
    'departamento_administrativo' => array('title' => 'Departamento', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
    'area' => array('title' => 'Area', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
    'fin_partida' => array('title' => 'Fuente', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
    'proceso_codigo' => array('title' => 'Proceso', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
    'nivelsalarial' => array('title' => 'Nivel', 'width' => 15, 'align' => 'C', 'type' => 'varchar'),
    'cargo' => array('title' => 'Cargo', 'width' => 30, 'align' => 'L', 'type' => 'varchar'),
    'sueldo' => array('title' => 'Haber', 'width' => 10, 'align' => 'R', 'type' => 'numeric'),
    'fecha_ini' => array('title' => 'Fecha Ini', 'width' => 18, 'align' => 'C', 'type' => 'date'),
    'fecha_incor' => array('title' => 'Fecha Inc', 'width' => 18, 'align' => 'C', 'type' => 'date'),
    'fecha_fin' => array('title' => 'Fecha Fin', 'width' => 18, 'align' => 'C', 'type' => 'date'),
    'fecha_baja' => array('title' => 'Fecha Baja', 'width' => 18, 'align' => 'C', 'type' => 'date'),
    'motivo_baja' => array('title' => 'Motivo Baja', 'width' => 20, 'align' => 'L', 'type' => 'varchar'),
    'observacion' => array('title' => 'Observacion', 'width' => 15, 'align' => 'L', 'type' => 'varchar')
);
$colTitleSelecteds = array('nro_row','ubicacion','condicion','estado_descripcion','nombres','ci','expd','fecha_caducidad','gerencia_administrativa','departamento_administrativo','area','fin_partida','proceso_codigo','nivelsalarial','cargo',
    'sueldo',
    'fecha_ini',
    'fecha_incor',
    'fecha_fin',
    'fecha_baja',
    'motivo_baja',
    'observacion'
);
//$cabeceraLetra = "E";
$columnasExcel = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE");
$cantCol = count($colTitleSelecteds);
$ultimaLetraCabeceraTabla = $columnasExcel[$cantCol-1];
$penultimaLetraCabeceraTabla = $columnasExcel[$cantCol-2];
$numFilaCabeceraTabla = 4;
$primeraLetraCabeceraTabla = "A";
$segundaLetraCabeceraTabla = "B";
$celdaInicial = $primeraLetraCabeceraTabla.$numFilaCabeceraTabla;
$celdaFinal = $ultimaLetraCabeceraTabla.$numFilaCabeceraTabla;
for($i=0;$i<=$cantCol;$i++){
    if(isset($colTitleSelecteds[$i])&&isset($columnasExcel[$i]))
    $objPHPExcel->getActiveSheet()->setCellValue($columnasExcel[$i].'4', $colTitleSelecteds[$i]);
}
#region Combinación de celdas para la cabecera (3 primeras filas)
$objPHPExcel->getActiveSheet()->mergeCells($segundaLetraCabeceraTabla.'1:'.$penultimaLetraCabeceraTabla.'1');
$objPHPExcel->getActiveSheet()->mergeCells($segundaLetraCabeceraTabla.'2:'.$penultimaLetraCabeceraTabla.'2');
$objPHPExcel->getActiveSheet()->mergeCells($segundaLetraCabeceraTabla.'3:'.$penultimaLetraCabeceraTabla.'3');
#endregion Combinación de celdas para la cabecera (3 primeras filas)


$objPHPExcel->getActiveSheet()->setCellValue('A5', '1001');
$objPHPExcel->getActiveSheet()->setCellValue('B5', 'PHP for dummies');
$objPHPExcel->getActiveSheet()->setCellValue('C5', '20');
$objPHPExcel->getActiveSheet()->setCellValue('D5', '1');
$objPHPExcel->getActiveSheet()->setCellValue('E5', '=IF(D5<>"",C5*D5,"")');

$objPHPExcel->getActiveSheet()->setCellValue('A6', '1012');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'OpenXML for dummies');
$objPHPExcel->getActiveSheet()->setCellValue('C6', '22');
$objPHPExcel->getActiveSheet()->setCellValue('D6', '2');
$objPHPExcel->getActiveSheet()->setCellValue('E6', '=IF(D6<>"",C6*D6,"")');

$objPHPExcel->getActiveSheet()->setCellValue('E7', '=IF(D7<>"",C7*D7,"")');
$objPHPExcel->getActiveSheet()->setCellValue('E8', '=IF(D8<>"",C8*D8,"")');
$objPHPExcel->getActiveSheet()->setCellValue('E9', '=IF(D9<>"",C9*D9,"")');
$objPHPExcel->getActiveSheet()->setCellValue('E10', '=IF(D10<>"",C10*D10,"")');

/*$objPHPExcel->getActiveSheet()->setCellValue('D12', 'Total excl.:');
$objPHPExcel->getActiveSheet()->setCellValue('E12', '=SUM(E5:E10)');

$objPHPExcel->getActiveSheet()->setCellValue('D13', 'VAT:');
$objPHPExcel->getActiveSheet()->setCellValue('E13', '=E12*0.21');

$objPHPExcel->getActiveSheet()->setCellValue('D14', 'Total incl.:');
$objPHPExcel->getActiveSheet()->setCellValue('E14', '=E13+E13');
*/
// Add comment
//echo date('H:i:s') , " Add comments" , EOL;

/*$objPHPExcel->getActiveSheet()->getComment('E12')->setAuthor('PHPExcel');
$objCommentRichText = $objPHPExcel->getActiveSheet()->getComment('E12')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getComment('E12')->getText()->createTextRun("\r\n");
$objPHPExcel->getActiveSheet()->getComment('E12')->getText()->createTextRun('Total amount on the current invoice, excluding VAT.');

$objPHPExcel->getActiveSheet()->getComment('E13')->setAuthor('PHPExcel');
$objCommentRichText = $objPHPExcel->getActiveSheet()->getComment('E13')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getComment('E13')->getText()->createTextRun("\r\n");
$objPHPExcel->getActiveSheet()->getComment('E13')->getText()->createTextRun('Total amount of VAT on the current invoice.');

$objPHPExcel->getActiveSheet()->getComment('E14')->setAuthor('PHPExcel');
$objCommentRichText = $objPHPExcel->getActiveSheet()->getComment('E14')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getComment('E14')->getText()->createTextRun("\r\n");
$objPHPExcel->getActiveSheet()->getComment('E14')->getText()->createTextRun('Total amount on the current invoice, including VAT.');
$objPHPExcel->getActiveSheet()->getComment('E14')->setWidth('100pt');
$objPHPExcel->getActiveSheet()->getComment('E14')->setHeight('100pt');
$objPHPExcel->getActiveSheet()->getComment('E14')->setMarginLeft('150pt');
$objPHPExcel->getActiveSheet()->getComment('E14')->getFillColor()->setRGB('EEEEEE');
*/

// Add rich-text string
//echo date('H:i:s') , " Add rich-text string" , EOL;
/*$objRichText = new PHPExcel_RichText();
$objRichText->createText('This invoice is ');

$objPayable = $objRichText->createTextRun('payable within thirty days after the end of the month');
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ) );

$objRichText->createText(', unless specified otherwise on the invoice.');

$objPHPExcel->getActiveSheet()->getCell('A18')->setValue($objRichText);
*/

// Celdas combinadas
//echo date('H:i:s') , " Merge cells" , EOL;
//$objPHPExcel->getActiveSheet()->mergeCells('A18:E22');

//$objPHPExcel->getActiveSheet()->mergeCells('A28:B28');		// Just to test...
//$objPHPExcel->getActiveSheet()->unmergeCells('A28:B28');	// Just to test...

// Protegiendo celdas
//echo date('H:i:s') , " Protect cells" , EOL;
/*$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);	// Needs to be set to true in order to enable any worksheet protection!
$objPHPExcel->getActiveSheet()->protectCells('A4:E13', 'PHPExcel');
*/

// Estableciendo el formato de las celdas
//echo date('H:i:s') , " Set cell number formats" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

// Set column widths
//echo date('H:i:s') , " Set column widths" , EOL;
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);

// Set fonts
//echo date('H:i:s') , " Set fonts" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B2:B3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUETELEFERICO);

$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('D13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E13')->getFont()->setBold(true);

// Set alignments
//echo date('H:i:s') , " Set alignments" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->getStyle('A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
$objPHPExcel->getActiveSheet()->getStyle('A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

#region Centrando los dos líneas que corresponden a las cabeceras.
$objPHPExcel->getActiveSheet()->getStyle('B1:B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
#endregion Centrando los dos líneas que corresponden a las cabeceras.

$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setShrinkToFit(true);

// Estableciendo el color del borde de la tabla
//echo date('H:i:s') , " Set thin black border outline around column" , EOL;
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '4F81BD'),
		),
	),
);
$ultimaFilaTabla = 10;
$celdaFinalDiagonalTabla = $ultimaLetraCabeceraTabla.$ultimaFilaTabla;
$objPHPExcel->getActiveSheet()->getStyle($celdaInicial.':'.$celdaFinalDiagonalTabla)->applyFromArray($styleThinBlackBorderOutline);


// Marcando los bordes como cuadro de resultados
//echo date('H:i:s') , " Set thick brown border outline around Total" , EOL;
/*$styleThickBrownBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THICK,
			'color' => array('argb' => 'FF993300'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('D13:E13')->applyFromArray($styleThickBrownBorderOutline);*/

// Establecer fondos de celda
//echo date('H:i:s') , " Set fills" , EOL;
//$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FF808080');

// Set style for header row using alternative method
//echo date('H:i:s') , " Set style for header row using alternative method" , EOL;
$objPHPExcel->getActiveSheet()->getStyle($celdaInicial.':'.$celdaFinal)->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => '4F81BD'
	 			),
	 			'endcolor'   => array(
	 				'argb' => '4F81BD'
	 			)
	 		)
		)
);
/**
 * Estableciendo el color de letras para las cabeceras
 */
$celdaInicial = $primeraLetraCabeceraTabla.$numFilaCabeceraTabla;
$celdaFinal = $ultimaLetraCabeceraTabla.$numFilaCabeceraTabla;
$objPHPExcel->getActiveSheet()->getStyle($celdaInicial.':'.$celdaFinal)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
			'borders' => array(
				'left'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);
/*
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		)
);*/

$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray(
		array(
			'borders' => array(
				'right'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			)
		)
);

// Unprotect a cell
//echo date('H:i:s') , " Unprotect a cell" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

// Add a hyperlink to the sheet
//echo date('H:i:s') , " Add a hyperlink to the sheet" , EOL;
/*$objPHPExcel->getActiveSheet()->setCellValue('E26', 'www.phpexcel.net');
$objPHPExcel->getActiveSheet()->getCell('E26')->getHyperlink()->setUrl('http://www.phpexcel.net');
$objPHPExcel->getActiveSheet()->getCell('E26')->getHyperlink()->setTooltip('Navigate to website');
$objPHPExcel->getActiveSheet()->getStyle('E26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->setCellValue('E27', 'Terms and conditions');
$objPHPExcel->getActiveSheet()->getCell('E27')->getHyperlink()->setUrl("sheet://'Terms and conditions'!A1");
$objPHPExcel->getActiveSheet()->getCell('E27')->getHyperlink()->setTooltip('Review terms and conditions');
$objPHPExcel->getActiveSheet()->getStyle('E27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
*/
// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Escudo');
$objDrawing->setDescription('Escudo de Bolivia');
$objDrawing->setPath('./images/escudo.jpg');
$objDrawing->setOffsetX(5);
$objDrawing->setHeight(50);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo Mi Teleferico');
$objDrawing->setPath('./images/logoMT.jpg');
$objDrawing->setCoordinates($ultimaLetraCabeceraTabla.'1');
$objDrawing->setOffsetX(5);
//$objDrawing->setRotation(25);
//$objDrawing->getShadow()->setVisible(true);
//$objDrawing->getShadow()->setDirection(45);
$objDrawing->setHeight(50);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('./images/phpexcel_logo.gif');
$objDrawing->setHeight(36);
$objDrawing->setCoordinates('D24');
$objDrawing->setOffsetX(10);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
*/
// Play around with inserting and removing rows and columns
//echo date('H:i:s') , " Play around with inserting and removing rows and columns" , EOL;
$objPHPExcel->getActiveSheet()->insertNewRowBefore(6, 10);
$objPHPExcel->getActiveSheet()->removeRow(6, 10);
$objPHPExcel->getActiveSheet()->insertNewColumnBefore('E', 5);
$objPHPExcel->getActiveSheet()->removeColumn('E', 5);

// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
//echo date('H:i:s') , " Set header/footer" , EOL;
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BInvoice&RPrinted on &D');
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

// Set page orientation and size
//echo date('H:i:s') , " Set page orientation and size" , EOL;
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Rename first worksheet
//echo date('H:i:s') , " Rename first worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Reporte');


// Create a new worksheet, after the default sheet
//echo date('H:i:s') , " Create a second Worksheet object" , EOL;
$objPHPExcel->createSheet();

// Llorem ipsum...
$sLloremIpsum = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus eget ante. Sed cursus nunc semper tortor. Aliquam luctus purus non elit. Fusce vel elit commodo sapien dignissim dignissim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur accumsan magna sed massa. Nullam bibendum quam ac ipsum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin augue. Praesent malesuada justo sed orci. Pellentesque lacus ligula, sodales quis, ultricies a, ultricies vitae, elit. Sed luctus consectetuer dolor. Vivamus vel sem ut nisi sodales accumsan. Nunc et felis. Suspendisse semper viverra odio. Morbi at odio. Integer a orci a purus venenatis molestie. Nam mattis. Praesent rhoncus, nisi vel mattis auctor, neque nisi faucibus sem, non dapibus elit pede ac nisl. Cras turpis.';

// Add some data to the second sheet, resembling some different data types
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Recomendaciones');
$objPHPExcel->getActiveSheet()->setCellValue('A3', $sLloremIpsum);
$objPHPExcel->getActiveSheet()->setCellValue('A4', $sLloremIpsum);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $sLloremIpsum);
$objPHPExcel->getActiveSheet()->setCellValue('A6', $sLloremIpsum);

// Set the worksheet tab color
//echo date('H:i:s') , " Set the worksheet tab color" , EOL;
$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');;

// Set alignments
//echo date('H:i:s') , " Set alignments" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getAlignment()->setWrapText(true);

// Set column widths
//echo date('H:i:s') , " Set column widths" , EOL;
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);

// Set fonts
//echo date('H:i:s') , " Set fonts" , EOL;
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

$objPHPExcel->getActiveSheet()->getStyle('A3:A6')->getFont()->setSize(8);

// Add a drawing to the worksheet
//echo date('H:i:s') , " Add a drawing to the worksheet" , EOL;
/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Terms and conditions');
$objDrawing->setDescription('Terms and conditions');
$objDrawing->setPath('./images/termsconditions.jpg');
$objDrawing->setCoordinates('B14');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
*/
// Set page orientation and size
//echo date('H:i:s') , " Set page orientation and size" , EOL;
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Rename second worksheet
//echo date('H:i:s') , " Rename second worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Recomendaciones');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
