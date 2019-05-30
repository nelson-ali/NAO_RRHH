<?php
/**
 *
 */

class ResolucionesController extends ControllerBase
{
    public function initialize()
    {
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

        $uso_array = array(
            "1" => "Estructura Organizacional",
            "2" => "Escala Salarial",
            "3" => "Estructura Organizacional & Escala Salarial",
            "4" => "Otros",
        );

        $uso = $this->tag->selectStatic(
            array(
                "uso",
                $uso_array,
                'useEmpty' => true,
                'emptyText' => '(Selecionar)',
                'emptyValue' => 0,
                'class' => 'form-control',
            )
        );
        $this->view->setVar('uso', $uso);

        $activo = $this->tag->selectStatic(
            array(
                "activo",
                array(0 => 'NO', 1 => 'SI'),
                'useEmpty' => false,
                'emptyText' => '(Selecionar)',
                'emptyValue' => 0,
                'class' => 'form-control',
            )
        );
        $this->view->setVar('activo', $activo);
    }

    public function listAction()
    {
        $uso_array = array(
            1 => "Estructura Organizacional",
            2 => "Escala Salarial",
            3 => "Estructura Organizacional & Escala Salarial",
            4 => "Otros"
        );
        $resul = Resoluciones::find(array('baja_logica=:activo1:', 'bind' => array('activo1' => '1'), 'order' => 'id ASC'));
        $this->view->disable();
        foreach ($resul as $v) {
            $customers[] = array(
                'id' => $v->id,
                'tipo_resolucion' => $v->tipo_resolucion,
                //'numero_res' => $v->numero_res,
                'fecha_emi' => $v->fecha_emi != "" ? date("Y-m-d", strtotime($v->fecha_emi)) : "",
                'fecha_apr' => $v->fecha_apr != "" ? date("Y-m-d", strtotime($v->fecha_apr)) : "",
                'fecha_fin' => $v->fecha_fin != "" ? date("Y-m-d", strtotime($v->fecha_fin)) : "",
                'activo' => $v->activo,
                'activo_descripcion' => $v->activo == 1 ? "ACTIVO" : "",
                'uso_string' => $uso_array[$v->uso],
                'uso' => $v->uso
            );
        }
        echo json_encode($customers);
    }

    public function saveAction()
    {
        if (isset($_POST['id'])) {

            $fecha_emi = $_POST['fecha_emi'] != "" ? date("Y-m-d", strtotime($_POST['fecha_emi'])) : null;
            $fecha_apr = $_POST['fecha_apr'] != "" ? date("Y-m-d", strtotime($_POST['fecha_apr'])) : null;
            $fecha_fin = $_POST['fecha_fin'] != "" ? date("Y-m-d", strtotime($_POST['fecha_fin'])) : null;
            $uso = $_POST['uso'] > 0 ? $_POST['uso'] : 0;
            $auth = $this->session->get('auth');

            if ($_POST['activo'] == 1) {
                $model = new Resoluciones();
                $model->desactivar($uso);
            }
            if ($_POST['id'] > 0) {
                $resul = Resoluciones::findFirstById($_POST['id']);
                $resul->tipo_resolucion = $_POST['tipo_resolucion'];
                $resul->numero_res = 0; // $_POST['numero_res'];
                $resul->fecha_emi = $fecha_emi;
                $resul->fecha_apr = $fecha_apr;
                $resul->fecha_fin = $fecha_fin;
                $resul->activo = $_POST['activo'];
                $resul->uso = $_POST['uso'];
                if ($resul->save()) {
                    $msm = array('msm' => 'Exito: Se guardo correctamente');
                } else {
                    $msm = array('msm' => 'Error: No se guardo el registro');
                }
            } else {
                $resul = new Resoluciones();
                $resul->tipo_resolucion = $_POST['tipo_resolucion'];
                $resul->numero_res = 0; // $_POST['numero_res'];
                $resul->institucion_sector_id = 1;
                $resul->institucion_rectora_id = 2;
                //$resul->instituciones_otras = "otra";
                $resul->gestion_res = date("Y");
                $resul->fecha_emi = $fecha_emi;
                $resul->fecha_apr = $fecha_apr;
                $resul->fecha_fin = $fecha_fin;
                $resul->activo = $_POST['activo'];
                $resul->uso = $_POST['uso'];
                //$resul->fecha_fin = $fecha_emi;
                $resul->estado = 1;
                $resul->baja_logica = 1;
                if ($resul->save()) {
                    $organigrama = Organigramas::findFirstById('1');
                    $resul2 = new Organigramas();
                    $resul2->padre_id = 0;
                    $resul2->gestion = date("Y");
                    $resul2->da_id = $organigrama->da_id;
                    $resul2->regional_id = 1;
                    $resul2->unidad_administrativa = $organigrama->unidad_administrativa;
                    $resul2->nivel_estructural_id = $organigrama->nivel_estructural_id;
                    $resul2->sigla = $organigrama->sigla;
                    $resul2->fecha_ini = date("Y-m-d");
                    $resul2->fecha_fin = date("Y-m-d");
                    $resul2->codigo = $organigrama->codigo;
                    $resul2->estado = 1;
                    $resul2->baja_logica = 1;
                    $resul2->user_reg_id = $auth['id'];
                    $resul2->visible = 1;
                    $resul2->fecha_reg = date("Y-m-d H:i:s");
                    $resul2->area_sustantiva = $organigrama->area_sustantiva;
                    $resul2->asistente = $organigrama->asistente;
                    $resul2->color = $organigrama->color;
                    $resul2->resolucion_ministerial_id = $resul->id;
                    $resul2->save();
                    $msm = array('msm' => 'Exito: Se guardo correctamente');
                } else {
                    $msm = array('msm' => 'Error: No se guardo el registro');
                }

            }


        }
        $this->view->disable();
        echo json_encode($msm);
    }

    public function deleteAction()
    {
        $resul = Resoluciones::findFirstById($_POST['id']);
        $resul->baja_logica = 0;
        $resul->save();
        $this->view->disable();
        echo json_encode();
    }

}

?>