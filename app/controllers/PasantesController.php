<?php 
/**
* 
*/

class PasantesController extends ControllerBase
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

        
	}

	public function listAction()
	{
		//echo "hola";
		
          $resul = Pasantes::find(array("baja_logica=1"));
          $model = new Pasantes();
          $resul = $model->lista();

		$this->view->disable();
		foreach ($resul as $v) {
			$customers[] = array(
				'id' => $v->id,
				'nombre_completo' => $v->app.' '.$v->apm.' '.$v->nombre,
				'sexo' => $v->sexo,
                    'ci' => $v->ci. ' '.$v->expedido,
                    'fecha_nac' => $v->fecha_nac.' 00:00:00',
				'direccion' => $v->direccion,
				'telefono'=>$v->telefono,
				'correo'=>$v->correo,
				'universidad'=>$v->universidad,
				'facultad'=>$v->facultad,
                    'carrera'=>$v->carrera,
				);
		}
		echo json_encode($customers);
		
	}

     

	public function formularioregistroAction($id=0)
     {

         $resul = Pasantes::findFirstById($id);
         if($resul!=false){

          $pdf = new pdfoasis('P', 'mm', 'Letter');
          $pdf->pageWidth = 215.9;

          $pdf->AliasNbPages();
          $pdf->AddPage();
          $pdf->SetFont('Times','',12);
          $pdf->ln();

          $universidad = Parametros::findFirst(array("parametro='pasantes_universidad' and CAST(nivel AS integer)=".$resul->universidad_id));
          $pdf->Cell(0,5,'FORMULARIO DE REGISTRO DE ESTUDIANTES ',0,1);
          $pdf->SetLineWidth(1);
          $pdf->Cell(0,5,'Programa Trabajo con Altura ','B',1);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->SetTextColor(0);
          $pdf->SetLineWidth();
          $pdf->SetFont('Times','',10);
          $pdf->Cell(70,8,'Universidad: ','RB',0,'L');
          $pdf->Cell(0,8,utf8_decode($universidad->valor_1),'B',0,'C',0);
          $pdf->ln(13);

          $sw=0;
          $pdf->SetLineWidth(1);
          $pdf->Cell(0,5,'DATOS DEL ESTUDIANTE','B',1);

          $pdf->SetFillColor(232, 232, 232);
          $pdf->SetTextColor(0);
          $pdf->SetLineWidth();
          $pdf->Cell(70,6,'Apellido Paterno:','RB',0,'L',$sw);
          $pdf->Cell(0,6,utf8_decode($resul->app),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,'Apellido Materno:','RB',0,'L',$sw);
          $pdf->Cell(0,6,utf8_decode($resul->apm),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,'Nombres:','RB',0,'L',$sw);
          $pdf->Cell(0,6,utf8_decode($resul->nombre),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,'Cedula de Identidad:','RB',0,'L',$sw);
          $pdf->Cell(0,6,$resul->ci.' '.$resul->expedido,'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,'Fecha de Nacimiento:','RB',0,'L',$sw);
          $pdf->Cell(0,6,date("d-m-Y",strtotime($resul->fecha_nac)),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,'Domicilio:','RB',0,'L',$sw);
          $pdf->Cell(0,6,utf8_decode($resul->direccion),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,'Telefono Fijo / Celular:','RB',0,'L',$sw);
          $pdf->Cell(0,6,$resul->telefono,'B',0,'C',$sw);
          $pdf->ln(10);

          $sw=0;
          $pdf->SetLineWidth(1);
          $pdf->Cell(0,5,'DATOS EDUCACIONALES DEL ESTUDIANTE','B',1);

          $pdf->SetFillColor(232, 232, 232);
          $pdf->SetTextColor(0);
          $pdf->SetLineWidth();
          $pdf->Cell(70,6,'Facultad:','RB',0,'L',$sw);
          $pdf->Cell(0,6,utf8_decode($resul->facultad),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,'Carrera:','RB',0,'L',$sw);
          $pdf->Cell(0,6,utf8_decode($resul->carrera),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->Cell(70,6,utf8_decode('Año o Semestre que Cursa:'),'RB',0,'L',$sw);
          $pdf->Cell(0,6,utf8_decode($resul->anio_semestre),'B',0,'C',$sw);
          $pdf->ln();$sw=!$sw;
          $pdf->MultiCell(70, 6, utf8_decode('Promedio de Notas del Ultimo Año / Semestre Cursado:'), 'RB', 'J', $sw);
          $pdf->SetXY(80,129);
          $pdf->Cell(0,12,$resul->promedio_notas,'B',0,'C',$sw);
          $pdf->ln(15);

          $pdf->SetLineWidth(1);
          $pdf->Cell(0,5,'DOCUMENTACION EXIGIDA A LOS POSTULANTES','B',1);
          $sw=0;
          $pdf->SetFillColor(232, 232, 232);
          $pdf->SetTextColor(0);
          $pdf->SetLineWidth();
          $doc_array = explode(",", $resul->documentos);
        $documentos= Parametros::find(array("parametro='pasantes_documentacion' and baja_logica=1"));
          foreach ($documentos as $v) {
               $pdf->Cell(70,6,$v->valor_1,'RB',0,'L',$sw);

               if (in_array($v->nivel, $doc_array)) {
                $pdf->Cell(0,6,'SI','B',0,'C',$sw);    
               }else{
                $pdf->Cell(0,6,'NO','B',0,'C',$sw); 
               }
               $pdf->ln();$sw=!$sw;
          }
          
          
          $pdf->SetXY(10,200);
          // $pdf->Cell(80,80,'SELLO O FIRMA AUTORIDAD COMPETENTE \n Firma autoridad',1,0,'C',$sw);
          $pdf->MultiCell(80, 30, '', 'LTR', 'J', 0);
          $pdf->MultiCell(80, 6, utf8_decode('Firma y Sello de la Universidad'), 'LR', 'C', 0);
          $pdf->MultiCell(80, 6, utf8_decode('Autoridad Competente'), 'LRB', 'C', 0);
          $pdf->SetXY(90,200);
          $pdf->MultiCell(115, 6, utf8_decode('Con la firma al pie del presente formulario, me compremeto a cumplir con lo establecido en el convenio entre mi Universidad y la Empresa de Trasporte por Cable "MI TELEFERICO".'), 'TR', 'J', 0);
          $pdf->SetXY(90,200);
          $pdf->MultiCell(115, 36,'', 'R', 'J', 0);
          $pdf->SetXY(90,236);
          $pdf->MultiCell(115, 6,'Firma del Estudiante', 'BR', 'C', 0);
          $pdf->Output('formulario_registro.pdf',I);

     }
     else{
          echo "El usuario no existe...";
     }


     $this->view->disable();

}

    

}
?>