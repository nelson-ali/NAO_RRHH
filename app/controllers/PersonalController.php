<?php
use Phalcon\Mvc\Controller,
    Phalcon\Mvc\View;

class PersonalController extends ControllerBase{

    public function initialize() {
        parent::initialize();
    }
    
    public function listarAction() {
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
    public function subirfotoAction($ci){
        if ($ci){
            $foto_persona = $ci+'.jpg';
            $this->view->setVar('foto_persona', $foto_persona);
            $this->view->setRenderLevel(View::LEVEL_BEFORE_TEMPLATE);
        } else {
            $this->view->setRenderLevel(View::LEVEL_BEFORE_TEMPLATE);
        }
    }
    public function cropAction(){
        //$this->view->disable();
        $targ_w = $targ_h = 472;
        $jpeg_quality = 90;
        $src = 'images/personal/tmp.jpg';
        if ($src){
            $img_r = imagecreatefromjpeg($src);
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
            imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
            //header('Content-type: image/jpeg');
            //imagejpeg($dst_r,null,$jpeg_quality);
            imagejpeg($dst_r,'images/personal/'.$_POST['ci'].'.jpg',$jpeg_quality);
            imagedestroy($dst_r);
            unlink($src);
        }
        $this->view->disable();
    }
    public function verificarciAction() {
        $resul = Personas::findFirst(array('ci="'.$_POST['ci'].'"','order' => 'id ASC'));
        if ($resul->id){
            $msm = true;
        } else {
            $msm = false;
        }
	$this->view->disable();
        echo json_encode($msm);
    }
    
    public function editarAction($id_personas){
        $this->assets
            ->addCss('/js/dropzone/css/dropzone.css')
            ->addCss('/js/jscrop/css/jquery.Jcrop.css')
        ;
        $this->assets
            ->addJs('/js/dropzone/dropzone.min.js')
            ->addJs('/js/jscrop/js/jquery.Jcrop.js')
        ;
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
            'fecha_caducidad' => $resul->fecha_caducidad,
            'nacionalidad' => $resul->nacionalidad,
            'lugar_nac' => $resul->lugar_nac,
            'fecha_nac' => $resul->fecha_nac,
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
            'interno_inst' => $res->interno_inst,
            'telefono_emerg' => $res->telefono_emerg,
            'persona_emerg' => $res->persona_emerg,
            'relacion_emerg' => $res->relacion_emerg
        );
        $this->view->setVar('datos_personal', $datos_personal);
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
            'fecha_caducidad' => date("d-m-Y",strtotime($resul->fecha_caducidad)),
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
            'interno_inst' => $res->interno_inst,
            'telefono_emerg' => $res->telefono_emerg,
            'persona_emerg' => $res->persona_emerg,
            'relacion_emerg' => $res->relacion_emerg
        );
        $this->view->setVar('datos_personal', $datos_personal);
    }
    public function cargarcropAction() {
        //$this->view->disable();
        /*$this->assets
             ->addCss('/js/jscrop/css/jquery.Jcrop.css')
        ;
        $this->assets
             ->addJs('/js/jscrop/js/jquery.Jcrop.js')
        ;*/
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
    public function subirAction(){
        //$ds = DIRECTORY_SEPARATOR;
        //$storeFolder = "/images/personal/";
        $this->view->disable();
        if ($this->request->hasFiles() == true) {
            //Print the real file names and their sizes
            foreach ($this->request->getUploadedFiles() as $file){
                //echo $file->getName(), " ", $file->getSize(), "\n";
                $file->moveTo('images/personal/tmp.jpg');
            }
        }
    }
    public function eliminarAction(){
        
    }
    public function listAction()
    {
        $resul = Personas::find(array('baja_logica=:activo1:','bind'=>array('activo1'=>'1'),'order' => 'id ASC'));
        $this->view->disable();
        foreach ($resul as $v) {
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
                'e_civil' => $v->e_civil,
                'tipo_doc' => $v->tipo_doc,
                'expd'=> $v->expd,
            );
        }
        echo json_encode($customers);
    }
    public function deleteAction(){
        $resul = Personas::findFirstById($_POST['id']);
        $resul->baja_logica = 0;
        $resul->save();
        $this->view->disable();
        echo json_encode();
    }
    public function saveAction()
    {
        if (isset($_POST['id'])) {
            $hoy = date("Y-m-d H:i:s");
            //$date = new DateTime($hoydia);
            //$hoy = $date->format('Y-m-d H:i:s');
            $date = new DateTime($_POST['fecha_nac']);
            $fecha_nac = $date->format('Y-m-d');//echo $fecha_nac." | ".$hoy;
            $date1 = new DateTime($_POST['fecha_caducidad']);
            $fecha_caducidad = $date1->format('Y-m-d');
            if ($_POST['id']>0) {
                $resul = Personas::findFirstById($_POST['id']);
                $resul->p_nombre = strtr(strtoupper($_POST['p_nombre']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                if ($_POST['s_nombre'] == ''){
                    $resul->s_nombre = NULL;
                } else {
                    $resul->s_nombre = strtr(strtoupper($_POST['s_nombre']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                }
                if ($_POST['t_nombre'] == ''){
                    $resul->t_nombre = NULL;
                } else {
                    $resul->t_nombre = strtr(strtoupper($_POST['t_nombre']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                }
                $resul->p_apellido = strtr(strtoupper($_POST['p_apellido']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                if ($_POST['s_apellido']==''){
                    $resul->s_apellido = NULL;
                } else {
                    $resul->s_apellido = strtr(strtoupper($_POST['s_apellido']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                }
                if (isset($_POST['c_apellido'])){
                  $resul->c_apellido = strtr(strtoupper($_POST['c_apellido']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                } else {
                  $resul->c_apellido = NULL;
                }
                $resul->ci = $_POST['ci'];
                $resul->expd = $_POST['expd'];
                if (isset($_POST['fecha_caducidad'])){
                  $resul->fecha_caducidad = $fecha_caducidad;
                } else {
                  $resul->fecha_caducidad = NULL;
                }
                $resul->fecha_nac = $fecha_nac;
                $resul->lugar_nac = $_POST['lugar_nac'];
                $resul->genero = $_POST['sexo'];
                $resul->e_civil = $_POST['e_civil'];
                $resul->codigo = $_POST['ci'];
                $resul->nacionalidad = $_POST['nacionalidad'];
                if ($_POST['nit'] == ''){
                    $resul->nit = NULL;
                } else {
                    $resul->nit = $_POST['nit'];
                }
                if ($_POST['num_func_sigma'] != ''){
                  $resul->num_func_sigma = $_POST['num_func_sigma'];
                } else {
                  $resul->num_func_sigma = NULL;
                }
                $resul->grupo_sanguineo = $_POST['grupo_sanguineo'];
                if ($_POST['num_lib_ser_militar'] == ''){
                    $resul->num_lib_ser_militar = NULL;
                } else {
                    $resul->num_lib_ser_militar = $_POST['num_lib_ser_militar'];
                }
                if (isset($_POST['num_reg_profesional'])){
                  $resul->num_reg_profesional = $_POST['num_reg_profesional'];
                } else {
                  $resul->num_reg_profesional = NULL;
                }
                if ($_POST['observacion'] == ''){
                    $resul->observacion = NULL;
                } else {
                    $resul->observacion = $_POST['observacion'];
                }
                $resul->user_mod_id = 1;
                $resul->fecha_mod = $hoy;
                $resul->tipo_doc = $_POST['tipo_doc'];
                $resul->foto = $_POST['ci'].'.jpg';
                $resul->save();
                if ($resul->save()) {
                    $res = new Personascontactos();
                    $res = Personascontactos::findFirst(array('persona_id='.$_POST['id'].' AND baja_logica = 1','order' => 'id ASC'));
                    if(!$res){
                        $res = new Personascontactos();
                    }
                    $res->persona_id = $resul->id;
                    if($_POST['direccion_dom'] == ''){
                        $res->direccion_dom = NULL;
                    } else {
                        $res->direccion_dom = $_POST['direccion_dom'];
                    }
                    if($_POST['telefono_fijo'] == ''){
                        $res->telefono_fijo = NULL;
                    } else {
                        $res->telefono_fijo = $_POST['telefono_fijo'];
                    }
                    if($_POST['telefono_inst'] == ''){
                        $res->telefono_inst = NULL;
                    } else {
                        $res->telefono_inst = $_POST['telefono_inst'];
                    }
                    if($_POST['telefono_fax'] == ''){
                        $res->telefono_fax = NULL;
                    } else {
                        $res->telefono_fax = $_POST['telefono_fax'];
                    }
                    if($_POST['interno_inst'] == ''){
                        $res->interno_inst = NULL;
                    } else {
                        $res->interno_inst = $_POST['interno_inst'];
                    }
                    if($_POST['celular_per'] == ''){
                        $res->celular_per = NULL;
                    } else {
                        $res->celular_per = $_POST['celular_per'];
                    }
                    if($_POST['celular_inst'] == ''){
                        $res->celular_inst = NULL;
                    } else {
                        $res->celular_inst = $_POST['celular_inst'];
                    }
                    if($_POST['e_mail_per'] == ''){
                        $res->e_mail_per = NULL;
                    } else {
                        $res->e_mail_per = $_POST['e_mail_per'];
                    }
                    if($_POST['e_mail_inst'] == ''){
                        $res->e_mail_inst = NULL;
                    } else {
                        $res->e_mail_inst = $_POST['e_mail_inst'];
                    }
                    if($_POST['telefono_emerg'] == ''){
                        $res->telefono_emerg = NULL;
                    } else {
                        $res->telefono_emerg = $_POST['telefono_emerg'];
                    }
                    if($_POST['persona_emerg'] == ''){
                        $res->persona_emerg = NULL;
                    } else {
                        $res->persona_emerg = strtr(strtoupper($_POST['persona_emerg']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                    }
                    if($_POST['relacion_emerg'] == ''){
                        $res->relacion_emerg = NULL;
                    } else {
                        $res->relacion_emerg = $_POST['relacion_emerg'];
                    }
                    $res->estado = 0;
                    $res->baja_logica = 1;
                    if ($res->save()){
                        $msm = array('msm' => 'Exito: Se guardo correctamente' );
                    } else {
                        $msm = array('msm' => 'Error: No se guardo el registro' );
                    }
                }
            } else {
                try{
                $resul = new Personas();
                $resul->p_nombre = strtr(strtoupper($_POST['p_nombre']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                if ($_POST['s_nombre'] == ''){
                    $resul->s_nombre = NULL;
                } else {
                    $resul->s_nombre = strtr(strtoupper($_POST['s_nombre']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                }
                if ($_POST['t_nombre'] == ''){
                    $resul->t_nombre = NULL;
                } else {
                    $resul->t_nombre = strtr(strtoupper($_POST['t_nombre']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                }
                $resul->p_apellido = strtr(strtoupper($_POST['p_apellido']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                if ($_POST['s_apellido']==''){
                    $resul->s_apellido = NULL;
                } else {
                    $resul->s_apellido = strtr(strtoupper($_POST['s_apellido']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                }
                if (isset($_POST['c_apellido'])){
                  $resul->c_apellido = strtr(strtoupper($_POST['c_apellido']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                } else {
                  $resul->c_apellido = NULL;
                }
                $resul->ci = $_POST['ci'];
                $resul->expd = $_POST['expd'];
                if (isset($_POST['fecha_caducidad'])){
                  $resul->fecha_caducidad = $fecha_caducidad;
                } else {
                  $resul->fecha_caducidad = NULL;
                }
                $resul->fecha_nac = $fecha_nac;
                $resul->lugar_nac = $_POST['lugar_nac'];
                $resul->genero = $_POST['sexo'];
                $resul->e_civil = $_POST['e_civil'];
                $resul->codigo = $_POST['ci'];
                $resul->nacionalidad = $_POST['nacionalidad'];
                if ($_POST['nit'] == ''){
                    $resul->nit = NULL;
                } else {
                    $resul->nit = $_POST['nit'];
                }
                if ($_POST['num_func_sigma'] != ''){
                  $resul->num_func_sigma = $_POST['num_func_sigma'];
                } else {
                  $resul->num_func_sigma = NULL;
                }
                $resul->grupo_sanguineo = $_POST['grupo_sanguineo'];
                if ($_POST['num_lib_ser_militar'] == ''){
                    $resul->num_lib_ser_militar = NULL;
                } else {
                    $resul->num_lib_ser_militar = $_POST['num_lib_ser_militar'];
                }
                if (isset($_POST['num_reg_profesional'])){
                  $resul->num_reg_profesional = $_POST['num_reg_profesional'];
                } else {
                  $resul->num_reg_profesional = NULL;
                }
                if ($_POST['observacion'] == ''){
                    $resul->observacion = NULL;
                } else {
                    $resul->observacion = $_POST['observacion'];
                }
                $resul->estado = 0;
                $resul->baja_logica = 1;
                $resul->user_reg_id = 1;
                $resul->fecha_reg = $hoy;
                $resul->tipo_doc = $_POST['tipo_doc'];
                $resul->agrupador = 0;
                $resul->foto = $_POST['ci'].'.jpg';
                //echo $_POST['tipo_doc'];
                //$resul->save();
                if ($resul->save()) {
                    $resul = Personas::findFirst(array('ci="'.$_POST['ci'].'" AND p_apellido = "'.strtr(strtoupper($_POST['p_apellido']),"áéíóúñü","ÁÉÍÓÚÑÜ").'"','order' => 'id ASC'));
                    $res = new Personascontactos();
                    $res->persona_id = $resul->id;
                    if($_POST['direccion_dom'] == ''){
                        $res->direccion_dom = NULL;
                    } else {
                        $res->direccion_dom = $_POST['direccion_dom'];
                    }
                    if($_POST['telefono_fijo'] == ''){
                        $res->telefono_fijo = NULL;
                    } else {
                        $res->telefono_fijo = $_POST['telefono_fijo'];
                    }
                    if($_POST['telefono_inst'] == ''){
                        $res->telefono_inst = NULL;
                    } else {
                        $res->telefono_inst = $_POST['telefono_inst'];
                    }
                    if($_POST['telefono_fax'] == ''){
                        $res->telefono_fax = NULL;
                    } else {
                        $res->telefono_fax = $_POST['telefono_fax'];
                    }
                    if($_POST['interno_inst'] == ''){
                        $res->interno_inst = NULL;
                    } else {
                        $res->interno_inst = $_POST['interno_inst'];
                    }
                    if($_POST['celular_per'] == ''){
                        $res->celular_per = NULL;
                    } else {
                        $res->celular_per = $_POST['celular_per'];
                    }
                    if($_POST['celular_inst'] == ''){
                        $res->celular_inst = NULL;
                    } else {
                        $res->celular_inst = $_POST['celular_inst'];
                    }
                    if($_POST['e_mail_per'] == ''){
                        $res->e_mail_per = NULL;
                    } else {
                        $res->e_mail_per = $_POST['e_mail_per'];
                    }
                    if($_POST['e_mail_inst'] == ''){
                        $res->e_mail_inst = NULL;
                    } else {
                        $res->e_mail_inst = $_POST['e_mail_inst'];
                    }
                    if($_POST['telefono_emerg'] == ''){
                        $res->telefono_emerg = NULL;
                    } else {
                        $res->telefono_emerg = $_POST['telefono_emerg'];
                    }
                    if($_POST['persona_emerg'] == ''){
                        $res->persona_emerg = NULL;
                    } else {
                        $res->persona_emerg = strtr(strtoupper($_POST['persona_emerg']),"áéíóúñü","ÁÉÍÓÚÑÜ");
                    }
                    if($_POST['relacion_emerg'] == ''){
                        $res->relacion_emerg = NULL;
                    } else {
                        $res->relacion_emerg = $_POST['relacion_emerg'];
                    }
                    $res->estado = 0;
                    $res->baja_logica = 1;
                    if ($res->save()){
                        $msm = array('msm' => 'Exito: Se guardo correctamente' );
                    } else {
                        $msm = array('msm' => 'Error: No se guardo el registro' );
                    }
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

