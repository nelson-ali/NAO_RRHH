<?php
use Phalcon\Mvc\Controller,
    Phalcon\Mvc\View;

class PresentaciondocController extends ControllerBase{

    public function initialize() {
        parent::initialize();
    }
    
    public function gestionAction() {
      //echo 'hola';
    }
    public function registroAction() {
      $this->assets
            ->addCss('/js/dropzone/css/dropzone.css')
            ->addCss('/js/jscrop/css/jquery.Jcrop.css')
        ;
        $this->assets
            ->addJs('/js/dropzone/dropzone.min.js')
            ->addJs('/js/jscrop/js/jquery.Jcrop.js')
        ;
    }
    public function filepersonalAction($id_personas){
        $this->assets
            ->addCss('/js/dropzone/css/dropzone.css')
            ->addCss('/js/jscrop/css/jquery.Jcrop.css')
        ;
        $this->assets
            ->addJs('/js/dropzone/dropzone.min.js')
            ->addJs('/js/jscrop/js/jquery.Jcrop.js')
        ;
        $resul = new Personas();
        $resul = Personas::findFirstById($id_personas);
        $res = new Personascontactos();
        $res = Personascontactos::findFirst(array('persona_id='.$id_personas.' AND baja_logica = 1','order' => 'id ASC'));
        $mod = new tipodoccondicion();
        $resul_doc = $mod->listaDocXPersona($resul->ci , $resul->genero);
        foreach ($resul_doc as $vr){
            $doc_x_persona[] = array (
                'condicion' => $vr->condicion,
                'tipo_doc_id' => $vr->tipo_doc_id,
                'tipo_documento' => $vr->tipo_documento,
                'doc_presentado_id' => $vr->doc_presentado_id,
                'grupoarchivos' => $vr->grupoarchivos,
                'codigo' => $vr->codigo,
                'rellaboral_id' => $vr->rellaboral_id,
                'nombre' => $vr->nombre,
                'campo_a' => $vr->campo_a,
                'tipo_a' => $vr->tipo_a,
                'campo_b' => $vr->campo_b,
                'tipo_b' => $vr->tipo_b,
                'campo_c' => $vr->campo_c,
                'tipo_c' => $vr->tipo_c,
                'fecha_emi' => $vr->fecha_emi,
                'fecha_pres' => $vr->fecha_pres,
                'campo_aux_v1' => $vr->campo_aux_v1,
                'campo_aux_v2' => $vr->campo_aux_v2,
                'campo_aux_v3' => $vr->campo_aux_v3,
                'campo_aux_d1' => $vr->campo_aux_d1,
                'campo_aux_d2' => $vr->campo_aux_d2,
                'campo_aux_d3' => $vr->campo_aux_d3,
                'observacion' => $vr->observacion,
                'tamanio' => $vr->tamanio,
                'tipo' => $vr->tipo
            );
        };
        $resul_doc = $mod->listaGrupoDoc($resul->ci , $resul->genero);
        foreach ($resul_doc as $lgd){
            $lista_doc[] = array(
                'id' => $lgd->id,
                'grupoarchivos' => $lgd->grupoarchivos
            );
        };
        $datos_personal = array(
            'id' => $resul->id,
            'p_nombre' => $resul->p_nombre,
            's_nombre' => $resul->s_nombre,
            't_nombre' => $resul->t_nombre,
            'p_apellido' => $resul->p_apellido,
            's_apellido' => $resul->s_apellido,
            'c_apellido' => $resul->c_apellido,
            'tipo_doc' => $resul->tipo_doc,
            'ci' => $resul->ci,
            'expd' => $resul->expd,
            'num_complemento' => $resul->num_complemento,
            'nacionalidad' => $resul->nacionalidad,
            'lugar_nac' => $resul->lugar_nac,
            'fecha_nac' => date("d-m-Y",strtotime($resul->fecha_nac)),
            'e_civil' => $resul->e_civil,
            'grupo_sanguineo'=> $resul->grupo_sanguineo,
            'genero' => $resul->genero,
            'nit' => $resul->nit,
            'num_func_sigma' => $resul->num_func_sigma,
            'num_lib_ser_militar' => $resul->num_lib_ser_militar,
            'num_reg_profesional' => $resul->num_reg_profesional,
            'observacion' => $resul->observacion,
            'id_personas_contactos' => $res->id,
            'direccion_dom' => $res->direccion_dom,
            'telefono_fijo' => $res->telefono_fijo,
            'telefono_inst' => $res->telefono_inst,
            'telefono_fax' => $res->telefono_fax,
            'celular_per' => $res->celular_per,
            'celular_inst' => $res->celular_inst,
            'e_mail_per' => $res->e_mail_per,
            'e_mail_inst' => $res->e_mail_inst,
            'interno_inst' => $res->interno_inst
        );
        $this->view->setVar('datos_personal', $datos_personal);
        $this->view->setVar('doc_x_persona', $doc_x_persona);
        $this->view->setVar('lista_doc', $lista_doc);
    }
    public function visualizarAction($id_personas){
        $resul = new Personas();
        $resul = Personas::findFirstById($id_personas);
        $res = new Personascontactos();
        $res = Personascontactos::findFirst(array('persona_id='.$id_personas.' AND baja_logica = 1','order' => 'id ASC'));
        $datos_personal = array(
            'id' => $resul->id,
            'p_nombre' => $resul->p_nombre,
            's_nombre' => $resul->s_nombre,
            't_nombre' => $resul->t_nombre,
            'p_apellido' => $resul->p_apellido,
            's_apellido' => $resul->s_apellido,
            'c_apellido' => $resul->c_apellido,
            'tipo_doc' => $resul->tipo_doc,
            'ci' => $resul->ci,
            'expd' => $resul->expd,
            'num_complemento' => $resul->num_complemento,
            'nacionalidad' => $resul->nacionalidad,
            'lugar_nac' => $resul->lugar_nac,
            'fecha_nac' => date("d-m-Y",strtotime($resul->fecha_nac)),
            'e_civil' => $resul->e_civil,
            'grupo_sanguineo'=> $resul->grupo_sanguineo,
            'genero' => $resul->genero,
            'nit' => $resul->nit,
            'num_func_sigma' => $resul->num_func_sigma,
            'num_lib_ser_militar' => $resul->num_lib_ser_militar,
            'num_reg_profesional' => $resul->num_reg_profesional,
            'observacion' => $resul->observacion,
            'id_personas_contactos' => $res->id,
            'direccion_dom' => $res->direccion_dom,
            'telefono_fijo' => $res->telefono_fijo,
            'telefono_inst' => $res->telefono_inst,
            'telefono_fax' => $res->telefono_fax,
            'celular_per' => $res->celular_per,
            'celular_inst' => $res->celular_inst,
            'e_mail_per' => $res->e_mail_per,
            'e_mail_inst' => $res->e_mail_inst,
            'interno_inst' => $res->interno_inst
        );
        $this->view->setVar('datos_personal', $datos_personal);
    }
    public function subirAction($codigo,$ci,$rellaboral,$param){
        //$ds = DIRECTORY_SEPARATOR;
        //$storeFolder = "/images/personal/";
        $ruta = 'filepersonal/'.$ci.'/';
        if (!file_exists ($ruta)){
            mkdir($ruta,0777);
        }
        $this->view->disable();
        if ($this->request->hasFiles() == true) {
            //Print the real file names and their sizes
            foreach ($this->request->getUploadedFiles() as $file){
                //echo $file->getName(), " ", $file->getSize(), "\n";
                $file_nombre = $codigo.'_'.$ci.'_'.$rellaboral.'.pdf';
                $file->moveTo($ruta.$file_nombre);
                $size = $file->getSize();
                $type = $file->getType();
                $msm = array(
                    'param' => $param,
                    'size' => $size,
                    'type' => $type
                );
                echo json_encode($msm);
            }
        }
    }
    public function eliminarAction($codigo,$ci,$rellaboral,$param){
        $ruta = 'filepersonal/'.$ci.'/';
        $file_nombre = $codigo.'_'.$ci.'_'.$rellaboral.'.pdf';
        $ruta = $ruta.$file_nombre;
        $this->view->disable();
        unlink($ruta);
        echo $param;
        
    }
    public function listAction()
    {
        $modelo = new Personas();
        $resul = $modelo->listaPerRelLab();
        $this->view->disable();
        foreach ($resul as $v) {
            $mod = new tipodoccondicion();
            $res = $mod->listaDocXPersona($v->ci , $v->genero);
            $cont = 0;
            foreach ($res as $t){
                if ($t->doc_presentado_id == ''){
                    $cont++;
                }
            }
            $customers[] = array(
                'id' => $v->id,
                'p_nombre' => $v->p_nombre,
                's_nombre' => $v->s_nombre,
                'p_apellido' => $v->p_apellido,
                's_apellido' => $v->s_apellido,
                'ci' => $v->ci,
                'fecha_nac' => date("d-m-Y",strtotime($v->fecha_nac)),
                'lugar_nac' => $v->lugar_nac,
                'genero' => $v->genero,
                'num_docs' => count($res),
                'num_falt' => $cont,
                'expd' => $v->expd,
            );
        }
        echo json_encode($customers);
    }
    public function mostrarAction($id,$ci){
        $resul = presentaciondoc::findFirstById($id);
        $this->view->disable();

        //$file = 'D:\xampp\htdocs\rrhh\public\filepersonal\\'.$ci.'\\'.$resul->nombre;
        
        //************************************************************************
        //**                                                                    **
        //** Se debe habilitar la línea de abajo para el publicado              **
        //**                                                                    **
        //************************************************************************
        
        //$file = '/var/www/html/rrhh_publicado/public/filepersonal/'.$ci.'/'.$resul->nombre;
        
        //************************************************************************
        //**                                                                    **
        //** Se debe habilitar la línea de abajo para el RRHH"                  **
        //**                                                                    **
        //************************************************************************
        
        //$file = '/var/www/html/rrhh/public/filepersonal/'.$ci.'/'.$resul->nombre;

        if ( file_exists($file)){
        $filetemp = substr($resul->nombre, 0,-4);
        header("Content-Disposition: attachment; filename=" . $filetemp . "\n\n");
        header("Content-Type: " . $resul->tipo);
        header("Content-Length: " . $resul->tamanio);
        readfile($file);}echo($file);
    }
    public function saveAction()
    {
        if (isset($_POST['id'])) {
            $hoy = date("Y-m-d H:i:s");
            //$date = new DateTime($hoydia);
            //$hoy = $date->format('Y-m-d H:i:s');
            $date = new DateTime($_POST['fecha_pres']);
            $fecha_pres = $date->format('Y-m-d');//echo $fecha_nac." | ".$hoy;*/
            if ($_POST['id']>0) {
                $resul = new presentaciondoc();
                $resul = presentaciondoc::findFirstById($_POST['id']);
                $resul->gestion_emi = $_POST['gestion_emi'];
                $resul->mes_emi = $_POST['mes_emi'];
                $resul->dia_emi = $_POST['dia_emi'];
                $resul->tipodocumento_id = $_POST['tipodocumento_id'];
                $resul->rellaboral_id = $_POST['rellaboral_id'];
                $resul->fecha_pres = $fecha_pres;
                if ($_POST['campo_aux_v1']==''){
                    $resul->campo_aux_v1 = NULL;
                } else {
                    $resul->campo_aux_v1 = $_POST['campo_aux_v1'];
                }
                if ($_POST['campo_aux_v2']==''){
                    $resul->campo_aux_v2 = NULL;
                } else {
                    $resul->campo_aux_v2 = $_POST['campo_aux_v2'];
                }
                if ($_POST['campo_aux_v3']==''){
                    $resul->campo_aux_v3 = NULL;
                } else {
                    $resul->campo_aux_v3 = $_POST['campo_aux_v3'];
                }
                if ($_POST['campo_aux_d1']==''){
                    $resul->campo_aux_d1 = NULL;
                } else {
                    $resul->campo_aux_d1 = $_POST['campo_aux_d1'];
                }
                if ($_POST['campo_aux_d2']==''){
                    $resul->campo_aux_d2 = NULL;
                } else {
                    $resul->campo_aux_d2 = $_POST['campo_aux_d2'];
                }
                if ($_POST['campo_aux_d3']==''){
                    $resul->campo_aux_d3 = NULL;
                } else {
                    $resul->campo_aux_d3 = $_POST['campo_aux_d3'];
                }
                if ($_POST['observacion']==''){
                    $resul->observacion = NULL;
                } else {
                    $resul->observacion = $_POST['observacion'];
                }
                if ($_POST['tamanio']==''){
                    $resul->tamanio = NULL;
                } else {
                    $resul->tamanio = $_POST['tamanio'];
                }
                if ($_POST['tipo']==''){
                    $resul->tipo = NULL;
                } else {
                    $resul->tipo = $_POST['tipo'];
                }
                $resul->user_mod_id = 1;
                $resul->fecha_mod = $hoy;
                if ($resul->save()) {
                    $msm = array('msm' => 'Exito: Se guardo correctamente' );
                } else {
                    $msm = array('msm' => 'Error: No se guardo el registro' );
                }
            } else {
                try{
                $resul = new presentaciondoc();
                $resul->gestion_emi = $_POST['gestion_emi'];
                $resul->mes_emi = $_POST['mes_emi'];
                $resul->dia_emi = $_POST['dia_emi'];
                $resul->tipodocumento_id = $_POST['tipodocumento_id'];
                $resul->rellaboral_id = $_POST['rellaboral_id'];
                $resul->fecha_pres = $fecha_pres;
                if ($_POST['campo_aux_v1']==''){
                    $resul->campo_aux_v1 = NULL;
                } else {
                    $resul->campo_aux_v1 = $_POST['campo_aux_v1'];
                }
                if ($_POST['campo_aux_v2']==''){
                    $resul->campo_aux_v2 = NULL;
                } else {
                    $resul->campo_aux_v2 = $_POST['campo_aux_v2'];
                }
                if ($_POST['campo_aux_v3']==''){
                    $resul->campo_aux_v3 = NULL;
                } else {
                    $resul->campo_aux_v3 = $_POST['campo_aux_v3'];
                }
                if ($_POST['campo_aux_d1']==''){
                    $resul->campo_aux_d1 = NULL;
                } else {
                    $resul->campo_aux_d1 = $_POST['campo_aux_d1'];
                }
                if ($_POST['campo_aux_d2']==''){
                    $resul->campo_aux_d2 = NULL;
                } else {
                    $resul->campo_aux_d2 = $_POST['campo_aux_d2'];
                }
                if ($_POST['campo_aux_d3']==''){
                    $resul->campo_aux_d3 = NULL;
                } else {
                    $resul->campo_aux_d3 = $_POST['campo_aux_d3'];
                }
                if ($_POST['observacion']==''){
                    $resul->observacion = NULL;
                } else {
                    $resul->observacion = $_POST['observacion'];
                }
                if ($_POST['tamanio']==''){
                    $resul->tamanio = NULL;
                } else {
                    $resul->tamanio = $_POST['tamanio'];
                }
                if ($_POST['tipo']==''){
                    $resul->tipo = NULL;
                } else {
                    $resul->tipo = $_POST['tipo'];
                }
                $resul->estado = 1;
                $resul->visible = 1;
                $resul->baja_logica = 1;
                $resul->user_reg_id = 1;
                $resul->fecha_reg = $hoy;
                $resul->nombre = $_POST['nombre'];
                if ($resul->save()) {
                    $msm = array('msm' => 'Exito: Se guardo correctamente' );
                } else {
                    $msm = array('msm' => 'Error: No se guardo el registro' );
                }
                }catch (\Exception $e){
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
        }
            }	
        } else {
          $msm = array('msm' => 'Error: no define Id' );
        }

        //$msm = array('msm' => 'Error: No..' );
	$this->view->disable();
	echo json_encode($msm);
        //echo $msm;
    }
    public function imprimirAction($n_rows, $columns, $filtros){
        //$rows = base64_decode(str_pad(strtr($rows, '-_', '+/'), strlen($rows) % 4, '=', STR_PAD_RIGHT)); 
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));

        //echo $filtros." - ".$columns;
        //$pdf = new fpdf();

        //echo $rows." - ".$columns;

        $pdf = new fpdf();
        //$rows = (string)$rows;
        
        //echo $filtros;
        //$rows = json_decode($rows,true);
        $columns = json_decode($columns,true);
        $filtros = json_decode($filtros,true);
        $pdf->AddPage('L','Letter');
        //$pdf->SetFont('Arial','B',16);
        $sub_keys = array_keys($columns);//echo $sub_keys[0];
        //$keys = array_keys($rows[0]);
        $n_col = count($columns);//echo$keys[1];
        //echo $n_col;
        $title = utf8_decode('Reporte de Personal "Mi teleférico".');
        $pdf->SetFont('Arial','B',12);
        $w = $pdf->GetStringWidth($title)+6;
        $pdf->SetX((260-$w)/2);
        $pdf->SetDrawColor(0,80,80);
        $pdf->SetFillColor(0,153,153);
        $pdf->SetTextColor(255);
        // Ancho del borde (1 mm)
        $pdf->SetLineWidth(1);
        // Título
        $pdf->Cell($w+15,9,$title,1,1,'C',true);
        $pdf->Ln();
        $pdf->SetFont('Arial','',10);
        // Color de fondo
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        // Título
        $pdf->Cell(0,6,"Filtrado por:",0,1,'L',true);
        $where = '';
        for($k=0;$k<count($filtros);$k++){
            for ($j=0;$j<$n_col;$j++){
                if ($sub_keys[$j] == $filtros[$k]['columna']){
                    $col_fil = $columns[$sub_keys[$j]]['text'];//echo $col_fil;
                }
            }
            $cond_fil = ' '.$col_fil;
            if (strlen($where)>0){
                $where .= ' AND ';
            }
            if ($filtros[$k]['tipo'] == 'datefilter'){
                $filtros[$k]['valor'] = date("Y-m-d",strtotime($filtros[$k]['valor']));
                //echo $filtros[$k]['valor'];
            }
            switch ($filtros[$k]['condicion']){
            /*case 'EMPTY':
                $cond_fil .= utf8_encode(" que sea vacía ");
                $where .= $filtros[$k]['columna'].
                break;
            case 'NOT_EMPTY':
                $cond_fil .= utf8_encode(" que no sea vacía ");
                break;*/
            case 'CONTAINS':
                $cond_fil .= utf8_encode(" que contenga el valor:  ".$filtros[$k]['valor']);
                $where .= $filtros[$k]['columna'].' ILIKE "%'.$filtros[$k]['valor'].'%"';
                break;
            case 'GREATER_THAN_OR_EQUAL':
                $cond_fil .= utf8_encode(" que sea mayor o igual que:  ".$filtros[$k]['valor']);
                $where .= $filtros[$k]['columna'].' >= "'.$filtros[$k]['valor'].'"';
                break;
            case 'LESS_THAN_OR_EQUAL':
                $cond_fil .= utf8_encode(" que sea menor o igual que:  ".$filtros[$k]['valor']);
                $where .= $filtros[$k]['columna'].' <= "'.$filtros[$k]['valor'].'"';
                break;
            }//echo $cond_fil;
            $pdf->Cell(0,6,  utf8_decode($cond_fil),0,1,'L',true);
        }
        //echo $where;
        // Salto de línea
        $pdf->Ln(4);
        $pdf->SetFillColor(0,153,153);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(0,80,80);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(10,7,'Nro.',1,0,'C',true);
        for ($j=0;$j<$n_col;$j++){
            if ($columns[$sub_keys[$j]]['hidden'] == FALSE){
                $pdf->Cell(35,7,$columns[$sub_keys[$j]]['text'],1,0,'C',true);
            }
        }
        $pdf->Ln();
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $fill = false;
        $ancho = 0;
        $resul = Personas::find(array($where,'order' => 'id ASC'));
        //$this->view->disable();
        foreach ($resul as $v) {
            $personal[] = array(
                'id' => $v->id,
                'p_nombre' => $v->p_nombre,
                's_nombre' => $v->s_nombre,
                'p_apellido' => $v->p_apellido,
                's_apellido' => $v->s_apellido,
                'ci' => $v->ci,
                'fecha_nac' => date("d-m-Y",strtotime($v->fecha_nac)),
                'lugar_nac' => $v->lugar_nac,
                'genero' => $v->genero,
                'e_civil' => $v->e_civil,
                'tipo_doc' => $v->tipo_doc,
                'expd'=> $v->expd,
            );
        } //echo $personal[0]['id'];
        for ($i=0;$i<$n_rows;$i++){
            $pdf->Cell(10,6,$i,'LR',0,'L',$fill);
            $ancho = 10;
            for ($j=0;$j<$n_col;$j++){
                if ($columns[$sub_keys[$j]]['hidden'] == FALSE){
                        $pdf->Cell(35,6,  utf8_decode($personal[$i][$sub_keys[$j]]),'LR',0,'L',$fill);
                        $ancho = $ancho + 35;
                    }
                }
            //$pdf->Cell(40,10, ($keys[$j]),0,1);
            
            $fill = !$fill;
            $pdf->Ln();
            //$pdf->Cell(40,10, ($rows[$i]['id']),0,1);
        }
        $pdf->Cell($ancho,0,'','T');
        //$pdf->Output('reporte_personal.pdf','I');
        $pdf->Output();
        $this->view->disable();
    }

}

