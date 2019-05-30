<?php

/*use Phalcon\Mvc\Controller;*/
use Phalcon\Mvc\View;

class PersonasController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
    }

    /* ivan */

    public function indexAction()
    {


        $this->persistent->parameters = null;
        $this->assets
            ->addCss('/js/jqwidgets/styles/jqx.base.css')
            //->addCss('/media/plugins/form-daterangepicker/daterangepicker-bs3.css')
            ->addCss('/js/jqwidgets/styles/jqx.oasis.css');
        $this->assets
            // ->addJs('/jqwidgets/jqxcore.js')
            // ->addJs('/jqwidgets/jqxmenu.js')
            // ->addJs('/jqwidgets/jqxdropdownlist.js')
            // ->addJs('/jqwidgets/jqxlistbox.js')
            // ->addJs('/jqwidgets/jqxcheckbox.js')
            // ->addJs('/jqwidgets/jqxscrollbar.js')
            // ->addJs('/jqwidgets/jqxgrid.js')
            // ->addJs('/jqwidgets/jqxdata.js')
            // ->addJs('/jqwidgets/jqxgrid.sort.js')
            // ->addJs('/jqwidgets/jqxgrid.pager.js')
            // ->addJs('/jqwidgets/jqxgrid.filter.js')
            // ->addJs('/jqwidgets/jqxgrid.selection.js')
            // ->addJs('/jqwidgets/jqxgrid.grouping.js')
            // ->addJs('/jqwidgets/jqxgrid.columnsreorder.js')
            // ->addJs('/jqwidgets/jqxgrid.columnsresize.js')
            // ->addJs('/jqwidgets/jqxdatetimeinput.js')
            // ->addJs('/jqwidgets/jqxcalendar.js')
            // ->addJs('/jqwidgets/jqxbuttons.js')
            // ->addJs('/jqwidgets/jqxdata.export.js')
            // ->addJs('/jqwidgets/jqxgrid.export.js')
            // ->addJs('/jqwidgets/globalization/globalize.js')
            // ->addJs('/jqwidgets/jqxgrid.aggregates.js')
            ->addJs('/scripts/personal/index.js');
    }

    public function nuevoAction()
    {
        if (isset($_POST['submit'])) {

            // $this->view->disable();
            echo $hoy = date("Y-m-d H:i:s");
            //$date = new DateTime($hoydia);
            //$hoy = $date->format('Y-m-d H:i:s');
            $date = new DateTime($this->request->getPost('fecha_nacimiento'));
            $fecha_nac = $date->format('Y-m-d'); //echo $fecha_nac." | ".$hoy;
            $date1 = new DateTime($this->request->getPost('f_caducidad'));
            $fecha_caducidad = $date1->format('Y-m-d');
            //guardamos los datos de la persona
            $persona = new Personas();
            // var_dump($_POST);
            $persona->p_nombre = $this->request->getPost('p_nombre');
            $persona->s_nombre = $this->request->getPost('s_nombre');
            $persona->t_nombre = $this->request->getPost('t_nombre');
            $persona->p_apellido = $this->request->getPost('p_apellido');
            $persona->s_apellido = $this->request->getPost('s_apellido');
            $persona->c_apellido = $this->request->getPost('a_casada');
            $persona->genero = $this->request->getPost('sexo');
            $persona->e_civil = $this->request->getPost('estado_civil');
            $persona->ci = $this->request->getPost('ci');
            $persona->expd = $this->request->getPost('expd');
            $persona->fecha_caducidad = $fecha_caducidad;
            $persona->fecha_nac = $fecha_nac;
            $persona->lugar_nac = $this->request->getPost('l_nacimiento');
            $persona->nacionalidad = $this->request->getPost('nacionalidad');
            $persona->foto = $this->request->getPost('ci') . '.jpg';
            $persona->nit = $this->request->getPost('nit');
            $persona->num_func_sigma = (int)$this->request->getPost('sigma');
            $persona->grupo_sanguineo = $this->request->getPost('grupo');
            $persona->num_lib_ser_militar = $this->request->getPost('libreta');
            $persona->num_reg_profesional = $this->request->getPost('reg_profesional');
            // $persona->observacion = $this->request->getPost('observacion');
            $persona->codigo = 0;
            $persona->estado = 0;
            $persona->user_reg_id = $this->_user->id;
            $persona->fecha_reg = $hoy;
            $persona->tipo_doc = $this->request->getPost('tipo_doc');
            $persona->estado = 1;
            $persona->baja_logica = 1;
            $persona->agrupador = 0;
            //         $persona->fecha_mod = $hoy;
            //           $persona->user_mod_id = 1; *
            $persona->observacion = "";
            $personaAux = Personas::findFirstByCi($this->request->getPost('ci'));
            if(!is_object($personaAux)){
                if ($persona->save()) {
                    $objPersonasContactoAux = Personascontactos::findFirst("e_mail_inst ILIKE '" . $this->request->getPost('email_i') . "' AND baja_logica = 1");
                    if(!is_object($objPersonasContactoAux)){
                        $contacto = new Personascontactos();
                        $contacto->persona_id = $persona->id;
                        $contacto->direccion_dom = $this->request->getPost('direccion');
                        $contacto->telefono_fijo = $this->request->getPost('telefono');
                        $contacto->celular_per = $this->request->getPost('celular');
                        $contacto->e_mail_per = $this->request->getPost('email');
                        $contacto->telefono_inst = $this->request->getPost('telefono_i');
                        $contacto->interno_inst = $this->request->getPost('interno');
                        $contacto->celular_inst = $this->request->getPost('celular_i');
                        $contacto->e_mail_inst = $this->request->getPost('email_i');
                        $contacto->telefono_emerg = $this->request->getPost('t_emergencia');
                        $contacto->persona_emerg = $this->request->getPost('p_emergencia');
                        $contacto->relacion_emerg = $this->request->getPost('p_emergencia');
                        $contacto->estado = 1;
                        $contacto->baja_logica = 1;
                        if ($contacto->save()) {
                            $this->flashSession->success("Registro agregado satisfactoriamente...!");
                            $this->response->redirect('/personas');
                        } else {
                            $this->flashSession->error("Ocurrio un error, favor comuniquese con el administrador del sistema. xx");
                        }
                    }else{
                        $objPer = Personas::findFirstById($objPersonasContactoAux->persona_id);
                        $this->flashSession->error("Error: El correo institucional '".$this->request->getPost('email_i')."' ya esta en uso por '" . $objPer->p_nombre . " " . $objPer->p_apellido . "', cuyo carnet es: ".$objPer->ci." ".trim($objPer->expd).".");
                    }
                } else {
                    $this->flashSession->error("Ocurrio un error, favor comuniquese con el administrador del sistema.");
                }
            }else{
                $this->flashSession->error("Error: El Nro. de documento '".$this->request->getPost('ci')."' ya esta en uso por '" . $personaAux->p_nombre . " " . $personaAux->p_apellido . "'");
            }
        }
        $this->assets->addCss('/media/plugins/form-stepy/jquery.stepy.css')
            ->addCss('/js/dropzone/css/dropzone.css')
            ->addCss('/js/jscrop/css/jquery.Jcrop.css');

        $this->assets
            ->addJs('/media/plugins/form-validation/jquery.validate.min.js')
            ->addJs('/media/plugins/form-stepy/jquery.stepy.js')
            ->addJs('/media/plugins/bootbox/bootbox.min.js')
            ->addJs('/media/demo/demo-formwizard.js')
            ->addJs('/js/dropzone/dropzone.min.js')
            ->addJs('/js/jscrop/js/jquery.Jcrop.js')
            ->addJs('/scripts/personal/nuevo.js')

            // ->addJs('/js/jquery-ui.js')
            // ->addJs('/jquery.picture.cut/src/jquery.picture.cut.js')
        ;

    }

    public function editarAction($id)
    {

        $this->assets->addCss('/media/plugins/form-stepy/jquery.stepy.css')
            ->addCss('/js/dropzone/css/dropzone.css')
            ->addCss('/js/jscrop/css/jquery.Jcrop.css');
        $this->assets
            ->addJs('/media/plugins/form-validation/jquery.validate.min.js')
            ->addJs('/media/plugins/form-stepy/jquery.stepy.js')
            ->addJs('/media/plugins/bootbox/bootbox.min.js')
            ->addJs('/media/demo/demo-formwizard.js')
            ->addJs('/js/dropzone/dropzone.min.js')
            ->addJs('/js/jscrop/js/jquery.Jcrop.js')
            ->addJs('/scripts/personal/nuevo.js');


        if (isset($_POST['submit'])) {

            // $this->view->disable();
            echo $hoy = date("Y-m-d H:i:s");
            //$date = new DateTime($hoydia);
            //$hoy = $date->format('Y-m-d H:i:s');
            $date = new DateTime($this->request->getPost('fecha_nacimiento'));
            $fecha_nac = $date->format('Y-m-d'); //echo $fecha_nac." | ".$hoy;
            $date1 = new DateTime($this->request->getPost('f_caducidad'));
            $fecha_caducidad = $date1->format('Y-m-d');
            //guardamos los datos de la persona
            $persona = Personas::findFirstById($id);
            // var_dump($_POST);
            $persona->p_nombre = $this->request->getPost('p_nombre');
            $persona->s_nombre = $this->request->getPost('s_nombre');
            $persona->t_nombre = $this->request->getPost('t_nombre');
            $persona->p_apellido = $this->request->getPost('p_apellido');
            $persona->s_apellido = $this->request->getPost('s_apellido');
            $persona->c_apellido = $this->request->getPost('a_casada');
            $persona->genero = $this->request->getPost('sexo');
            $persona->e_civil = $this->request->getPost('estado_civil');
            //$persona->ci = $this->request->getPost('ci');
            $persona->expd = $this->request->getPost('expd');
            $persona->fecha_caducidad = $fecha_caducidad;
            $persona->fecha_nac = $fecha_nac;
            $persona->lugar_nac = $this->request->getPost('l_nacimiento');
            $persona->nacionalidad = $this->request->getPost('nacionalidad');
            $persona->foto = $this->request->getPost('ci') . '.jpg';
            $persona->nit = $this->request->getPost('nit');
            $persona->num_func_sigma = (int)$this->request->getPost('sigma');
            $persona->grupo_sanguineo = $this->request->getPost('grupo');
            $persona->num_lib_ser_militar = $this->request->getPost('libreta');
            $persona->num_reg_profesional = $this->request->getPost('reg_profesional');
            // $persona->observacion = $this->request->getPost('observacion');
            $persona->codigo = 0;
            $persona->estado = 0;
            $persona->user_mod_id = $this->_user->id;
            $persona->fecha_mod = $hoy;
            $persona->tipo_doc = $this->request->getPost('tipo_doc');
            // $persona->estado = 1;
            // $persona->baja_logica = 1;
            // $persona->agrupador = 0;
            //         $persona->fecha_mod = $hoy;
            //           $persona->user_mod_id = 1; *
            // $persona->observacion = "";
            if ($persona->save()) {
                $contacto = Personascontactos::findFirst(array('persona_id=' . $id . ' AND baja_logica = 1', 'order' => 'id ASC'));
                if (is_object($contacto)) {
                    $objPersonasContactoAux = Personascontactos::findFirst("persona_id != " . $id . " AND e_mail_inst ILIKE '" . $this->request->getPost('email_i') . "' AND baja_logica = 1");
                    if (!is_object($objPersonasContactoAux)) {
                        //$contacto->persona_id = $persona->id;
                        $contacto->direccion_dom = $this->request->getPost('direccion');
                        $contacto->telefono_fijo = $this->request->getPost('telefono');
                        $contacto->celular_per = $this->request->getPost('celular');
                        $contacto->e_mail_per = $this->request->getPost('email');
                        $contacto->telefono_inst = $this->request->getPost('telefono_i');
                        $contacto->interno_inst = $this->request->getPost('interno');
                        $contacto->celular_inst = $this->request->getPost('celular_i');
                        $contacto->e_mail_inst = $this->request->getPost('email_i');
                        $contacto->telefono_emerg = $this->request->getPost('t_emergencia');
                        $contacto->persona_emerg = $this->request->getPost('p_emergencia');
                        $contacto->relacion_emerg = $this->request->getPost('r_emergencia');
                        $contacto->estado = 1;
                        // $contacto->baja_logica = 1;
                        if ($contacto->save()) {
                            $this->flashSession->success("Registro guardado satisfactoriamente...!");
                            $this->response->redirect('/personas');
                        } else {
                            $this->flashSession->error("Ocurrio un error, favor comuniquese con el administrador del sistema.");
                        }
                    } else {
                        $objPer = Personas::findFirstById($objPersonasContactoAux->persona_id);
                        $this->flashSession->error("Error: El correo institucional '".$this->request->getPost('email_i')."' ya esta en uso por '" . $objPer->p_nombre . " " . $objPer->p_apellido . "', cuyo carnet es: ".$objPer->ci." ".trim($objPer->expd).".");
                    }
                } else {
                    $objPersonasContactoAux = Personascontactos::findFirst("persona_id != " . $persona->id . " AND e_mail_inst ILIKE '" . $this->request->getPost('email_i') . "' AND baja_logica = 1");
                    if (!is_object($objPersonasContactoAux)) {
                        $contacto = new Personascontactos();
                        $contacto->persona_id = $persona->id;
                        $contacto->direccion_dom = $this->request->getPost('direccion');
                        $contacto->telefono_fijo = $this->request->getPost('telefono');
                        $contacto->celular_per = $this->request->getPost('celular');
                        $contacto->e_mail_per = $this->request->getPost('email');
                        $contacto->telefono_inst = $this->request->getPost('telefono_i');
                        $contacto->interno_inst = $this->request->getPost('interno');
                        $contacto->celular_inst = $this->request->getPost('celular_i');
                        $contacto->e_mail_inst = $this->request->getPost('email_i');
                        $contacto->telefono_emerg = $this->request->getPost('t_emergencia');
                        $contacto->persona_emerg = $this->request->getPost('p_emergencia');
                        $contacto->relacion_emerg = $this->request->getPost('r_emergencia');
                        $contacto->estado = 1;
                        $contacto->baja_logica = 1;
                        if ($contacto->save()) {
                            $this->flashSession->success("Registro guardado satisfactoriamente...!");
                            $this->response->redirect('/personas');
                        } else {
                            $this->flashSession->error("Ocurrio un error, favor comuniquese con el administrador del sistema.");
                        }
                    } else {
                        $objPer = Personas::findFirstById($objPersonasContactoAux->persona_id);
                        $this->flashSession->error("Error: El correo institucional '".$this->request->getPost('email_i')."' ya esta en uso por '" . $objPer->p_nombre . " " . $objPer->p_apellido  . "', cuyo carnet es: ".$objPer->ci." ".trim($objPer->expd).".");
                    }
                }
            } else {
                $this->flashSession->error("Ocurrio un error, favor comuniquese con el administrador del sistema.");
            }
        }

        $resul = Personas::findFirstById($id);
        $res = Personascontactos::findFirst(array('persona_id=' . $id . ' AND baja_logica = 1', 'order' => 'id ASC'));
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
            /*'num_complemento' => $resul->num_complemento,*/
            'nacionalidad' => $resul->nacionalidad,
            'lugar_nac' => $resul->lugar_nac,
            'fecha_nac' => $resul->fecha_nac,
            'e_civil' => $resul->e_civil,
            'grupo_sanguineo' => $resul->grupo_sanguineo,
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
            't_emergencia' => $res->telefono_emerg,
            'p_emergencia' => $res->persona_emerg,
            'r_emergencia' => $res->relacion_emerg
        );
        $this->view->setVar('personas', $datos_personal);

        $expd = trim($datos_personal['expd']);
        $this->tag->setDefault("expd", $expd);
        $expd = $this->tag->selectStatic(array('expd',
            array(
                'LP' => 'LP',
                'OR' => 'OR',
                'CBA' => 'CBA',
                'TRJ' => 'TRJ',
                'PND' => 'PDO',
                'SC' => 'SC',
                'BN' => 'BN',
                'PT' => 'PT',
                'CH' => 'CH'),
            "class" => 'form-control',
            'useEmpty' => false,
            'emptyText' => '[Lugar Expedicion]',
            'emptyValue' => '',
            'required' => 'required',
            'title' => 'Campo requerido'
        ));

        $this->view->setVar('expd', $expd);


    }

    // public function editarAction($id_personas) {
    //     $resul = new Personas();
    //     $resul = Personas::findFirstById($id_personas);
    //     $res = new Personascontactos();
    //     $res = Personascontactos::findFirst(array('persona_id=' . $id_personas . ' AND baja_logica = 1', 'order' => 'id ASC'));
    //     $datos_personal = array(
    //         'id' => $resul->id,
    //         'p_nombre' => $resul->p_nombre,
    //         's_nombre' => $resul->s_nombre,
    //         't_nombre' => $resul->t_nombre,
    //         'p_apellido' => $resul->p_apellido,
    //         's_apellido' => $resul->s_apellido,
    //         'c_apellido' => $resul->c_apellido,
    //         'tipo_doc' => $resul->tipo_doc,
    //         'ci' => $resul->ci,
    //         'expd' => $resul->expd,
    //         'num_complemento' => $resul->num_complemento,
    //         'nacionalidad' => $resul->nacionalidad,
    //         'lugar_nac' => $resul->lugar_nac,
    //         'fecha_nac' => $resul->fecha_nac,
    //         'e_civil' => $resul->e_civil,
    //         'grupo_sanguineo' => $resul->grupo_sanguineo,
    //         'genero' => $resul->genero,
    //         'nit' => $resul->nit,
    //         'num_func_sigma' => $resul->num_func_sigma,
    //         'num_lib_ser_militar' => $resul->num_lib_ser_militar,
    //         'num_reg_profesional' => $resul->num_reg_profesional,
    //         'observacion' => $resul->observacion,
    //         'id_personas_contactos' => $res->id,
    //         'direccion_dom' => $res->direccion_dom,
    //         'telefono_fijo' => $res->telefono_fijo,
    //         'telefono_inst' => $res->telefono_inst,
    //         'telefono_fax' => $res->telefono_fax,
    //         'celular_per' => $res->celular_per,
    //         'celular_inst' => $res->celular_inst,
    //         'e_mail_per' => $res->e_mail_per,
    //         'e_mail_inst' => $res->e_mail_inst,
    //         'interno_inst' => $res->interno_inst
    //     );
    //     $this->view->setVar('datos_personal', $datos_personal);
    // }

    //verificar ci
    public function ciAction()
    {
        $this->view->disable();
        if ($this->request->isPost()) {
            $ci = $this->request->getPost('ci');
            $result = array(
                'mensaje' => '',
                'existe' => 0,
            );
            $persona = Personas::findFirst("ci='$ci'");
            if ($persona) {
                $result = array(
                    'mensaje' => 'El numero de cedula de identidad ya existe en la base de datos y pertenece a :  ' . $persona->p_nombre . ' ' . $persona->s_nombre . ' ' . $persona->p_apellido,
                    'existe' => 1
                );
            }
            echo json_encode($result);
        }
    }

    public function uploadAction()
    {
        $this->view->disable();
        // Check if the user has uploaded files
        if ($this->request->hasFiles() == true) {

            // Print the real file names and sizes
            foreach ($this->request->getUploadedFiles() as $file) {

                //Print file details
                // echo $file->getName(), " ", $file->getSize(), "\n";
                //Move the file into the application
                $file->moveTo('files/' . $file->getName());
            }
        }
        //echo $_POST['id_relacion'];
        echo 'r';
    }

    public function visualizarAction($id_personas)
    {
        $resul = new Personas();
        $resul = Personas::findFirstById($id_personas);
        $res = new Personascontactos();
        $res = Personascontactos::findFirst(array('persona_id=' . $id_personas . ' AND baja_logica = 1', 'order' => 'id ASC'));
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
            'fecha_nac' => date("d-m-Y", strtotime($resul->fecha_nac)),
            'e_civil' => $resul->e_civil,
            'grupo_sanguineo' => $resul->grupo_sanguineo,
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

    /* ivan */

    public function listajsonAction()
    {

        //$resul = Personas::find(array('baja_logica=:activo1:', 'bind' => array('activo1' => '1'), 'order' => 'p_apellido ASC'));
        $resul = consultas::personasActivo();
        $this->view->disable();
        foreach ($resul as $v) {
            $customers[] = array(
                'id' => $v->id,
                'p_nombre' => $v->nombres,
                's_nombre' => $v->apellidos,
                'p_apellido' => utf8_encode($v->apellidos),
                's_apellido' => utf8_encode($v->s_apellido),
                'ci' => $v->ci,
                'fecha_nac' => date("d-m-Y", strtotime($v->fecha_nac)),
                'lugar_nac' => $v->lugar_nac,
                'genero' => $v->genero,
                'e_civil' => $v->e_civil,
                'nacionalidad' => $v->nacionalidad,
                'tipo_doc' => $v->tipo_doc,
                'foto' => $this->foto($v->foto, $v->genero),
                'expd' => $v->expd,
                'estado_actual' => $v->estado_actual,
                'suma' => 1,
            );
        }
        echo json_encode($customers);
    }

    public function foto($foto, $genero)
    {
        $file = "/images/personal/hombre.jpg";
        if (file_exists("images/personal/" . $foto)) {
            $file = "/images/personal/" . $foto;
        } else {
            if ($genero == 'F') {
                $file = "/images/personal/mujer.jpg";
            }
        }
        return $file;
    }

    public function listAction()
    {
        $resul = Personas::find(array('baja_logica=:activo1:', 'bind' => array('activo1' => '1'), 'order' => 'id ASC'));
        $this->view->disable();
        foreach ($resul as $v) {
            $customers[] = array(
                'id' => $v->id,
                'p_nombre' => $v->p_nombre,
                's_nombre' => $v->s_nombre,
                'p_apellido' => $v->p_apellido,
                's_apellido' => $v->s_apellido,
                'ci' => $v->ci,
                'fecha_nac' => date("d-m-Y", strtotime($v->fecha_nac)),
                'lugar_nac' => $v->lugar_nac,
                'genero' => $v->genero,
                'e_civil' => $v->e_civil,
                'tipo_doc' => $v->tipo_doc,
                'expd' => $v->expd,
            );
        }
        echo json_encode($customers);
    }

    public function deleteAction()
    {
        //$resul = Personas::findFirstById($_POST['id']);
        $resul = consultas::deletePersona($_POST['id']);
        // $resul->user_mod_id = $auth['id'];
        // $resul->fecha_mod = date("Y-m-d H:i:s");
        //  $resul->baja_logica = 0;
        //  if ($resul->save()) {
        //     $msm = 'Exito: Se guardo correctamente';
        // }else{
        //     $msm = 'Error: No se guardo el registro';
        // }
        $this->view->disable();
        echo json_encode();
    }


    public function subirfotoAction($ci=0)
    {
        if ($ci) {
            $foto_persona = $ci + '.jpg';
            $this->view->setVar('foto_persona', $foto_persona);
            $this->view->setRenderLevel(View::LEVEL_BEFORE_TEMPLATE);
        } else {
            $this->view->setRenderLevel(View::LEVEL_BEFORE_TEMPLATE);
        }
    }

    public function subirAction()
    {
        //$ds = DIRECTORY_SEPARATOR;
        //$storeFolder = "/images/personal/";
        $this->view->disable();
        if ($this->request->hasFiles() == true) {
            //Print the real file names and their sizes
            foreach ($this->request->getUploadedFiles() as $file) {
                //echo $file->getName(), " ", $file->getSize(), "\n";
                $file->moveTo('images/personal/tmp.jpg');
            }
        }
    }

    public function cargarcropAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    public function cropAction()
    {
        //$this->view->disable();
        $targ_w = $targ_h = 472;
        $jpeg_quality = 90;
        $src = 'images/personal/tmp.jpg';
        if ($src) {
            $img_r = imagecreatefromjpeg($src);
            $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
            imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
            //header('Content-type: image/jpeg');
            //imagejpeg($dst_r,null,$jpeg_quality);
            imagejpeg($dst_r, 'images/personal/' . $_POST['ci'] . '.jpg', $jpeg_quality);
            imagedestroy($dst_r);
            unlink($src);
        }
        $this->view->disable();
    }

    // public function saveAction() {
    //     if (isset($_POST['id'])) {
    //         $hoy = date("Y-m-d H:i:s");
    //         //$date = new DateTime($hoydia);
    //         //$hoy = $date->format('Y-m-d H:i:s');
    //         $date = new DateTime($_POST['fecha_nac']);
    //         $fecha_nac = $date->format('Y-m-d'); //echo $fecha_nac." | ".$hoy;
    //         if ($_POST['id'] > 0) {
    //             $resul = new Personas();
    //             $resul = Personas::findFirstById($_POST['id']);
    //             $resul->p_nombre = $_POST['p_nombre'];
    //             if ($_POST['s_nombre'] == '') {
    //                 $resul->s_nombre = NULL;
    //             } else {
    //                 $resul->s_nombre = $_POST['s_nombre'];
    //             }
    //             if ($_POST['t_nombre'] == '') {
    //                 $resul->t_nombre = NULL;
    //             } else {
    //                 $resul->t_nombre = $_POST['t_nombre'];
    //             }
    //             $resul->p_apellido = $_POST['p_apellido'];
    //             if ($_POST['s_apellido'] == '') {
    //                 $resul->s_apellido = NULL;
    //             } else {
    //                 $resul->s_apellido = $_POST['s_apellido'];
    //             }
    //             if (isset($_POST['c_apellido'])) {
    //                 $resul->c_apellido = $_POST['c_apellido'];
    //             } else {
    //                 $resul->c_apellido = NULL;
    //             }
    //             $resul->ci = $_POST['ci'];
    //             $resul->expd = $_POST['expd'];
    //             if (isset($_POST['num_complemento'])) {
    //                 $resul->num_complemento = $_POST['num_complemento'];
    //             } else {
    //                 $resul->num_complemento = NULL;
    //             }
    //             $resul->fecha_nac = $fecha_nac;
    //             $resul->lugar_nac = $_POST['lugar_nac'];
    //             $resul->genero = $_POST['sexo'];
    //             $resul->e_civil = $_POST['e_civil'];
    //             $resul->codigo = $_POST['ci'];
    //             $resul->nacionalidad = $_POST['nacionalidad'];
    //             if ($_POST['nit'] == '') {
    //                 $resul->nit = NULL;
    //             } else {
    //                 $resul->nit = $_POST['nit'];
    //             }
    //             if ($_POST['num_func_sigma'] != '') {
    //                 $resul->num_func_sigma = $_POST['num_func_sigma'];
    //             } else {
    //                 $resul->num_func_sigma = NULL;
    //             }
    //             $resul->grupo_sanguineo = $_POST['grupo_sanguineo'];
    //             if ($_POST['num_lib_ser_militar'] == '') {
    //                 $resul->num_lib_ser_militar = NULL;
    //             } else {
    //                 $resul->num_lib_ser_militar = $_POST['num_lib_ser_militar'];
    //             }
    //             if (isset($_POST['num_reg_profesional'])) {
    //                 $resul->num_reg_profesional = $_POST['num_reg_profesional'];
    //             } else {
    //                 $resul->num_reg_profesional = NULL;
    //             }
    //             if ($_POST['observacion'] == '') {
    //                 $resul->observacion = NULL;
    //             } else {
    //                 $resul->observacion = $_POST['observacion'];
    //             }
    //             $resul->user_mod_id = 1;
    //             $resul->fecha_mod = $hoy;
    //             $resul->tipo_doc = $_POST['tipo_doc'];
    //             $resul->save();
    //             if ($resul->save()) {
    //                 $res = new Personascontactos();
    //                 $res = Personascontactos::findFirst(array('persona_id=' . $_POST['id'] . ' AND baja_logica = 1', 'order' => 'id ASC'));
    //                 if (!$res) {
    //                     $res = new Personascontactos();
    //                 }
    //                 $res->persona_id = $resul->id;
    //                 if ($_POST['direccion_dom'] == '') {
    //                     $res->direccion_dom = NULL;
    //                 } else {
    //                     $res->direccion_dom = $_POST['direccion_dom'];
    //                 }
    //                 if ($_POST['telefono_fijo'] == '') {
    //                     $res->telefono_fijo = NULL;
    //                 } else {
    //                     $res->telefono_fijo = $_POST['telefono_fijo'];
    //                 }
    //                 if ($_POST['telefono_inst'] == '') {
    //                     $res->telefono_inst = NULL;
    //                 } else {
    //                     $res->telefono_inst = $_POST['telefono_inst'];
    //                 }
    //                 if ($_POST['telefono_fax'] == '') {
    //                     $res->telefono_fax = NULL;
    //                 } else {
    //                     $res->telefono_fax = $_POST['telefono_fax'];
    //                 }
    //                 if ($_POST['interno_inst'] == '') {
    //                     $res->interno_inst = NULL;
    //                 } else {
    //                     $res->interno_inst = $_POST['interno_inst'];
    //                 }
    //                 if ($_POST['celular_per'] == '') {
    //                     $res->celular_per = NULL;
    //                 } else {
    //                     $res->celular_per = $_POST['celular_per'];
    //                 }
    //                 if ($_POST['celular_inst'] == '') {
    //                     $res->celular_inst = NULL;
    //                 } else {
    //                     $res->celular_inst = $_POST['celular_inst'];
    //                 }
    //                 if ($_POST['e_mail_per'] == '') {
    //                     $res->e_mail_per = NULL;
    //                 } else {
    //                     $res->e_mail_per = $_POST['e_mail_per'];
    //                 }
    //                 if ($_POST['e_mail_inst'] == '') {
    //                     $res->e_mail_inst = NULL;
    //                 } else {
    //                     $res->e_mail_inst = $_POST['e_mail_inst'];
    //                 }
    //                 $res->estado = 0;
    //                 $res->baja_logica = 1;
    //                 if ($res->save()) {
    //                     $msm = array('msm' => 'Exito: Se guardo correctamente');
    //                 } else {
    //                     $msm = array('msm' => 'Error: No se guardo el registro');
    //                 }
    //             }
    //         } else {
    //             try {
    //                 $resul = new Personas();
    //                 $resul->p_nombre = $_POST['p_nombre'];
    //                 if ($_POST['s_nombre'] == '') {
    //                     $resul->s_nombre = NULL;
    //                 } else {
    //                     $resul->s_nombre = $_POST['s_nombre'];
    //                 }
    //                 if ($_POST['t_nombre'] == '') {
    //                     $resul->t_nombre = NULL;
    //                 } else {
    //                     $resul->t_nombre = $_POST['t_nombre'];
    //                 }
    //                 $resul->p_apellido = $_POST['p_apellido'];
    //                 if ($_POST['s_apellido'] == '') {
    //                     $resul->s_apellido = NULL;
    //                 } else {
    //                     $resul->s_apellido = $_POST['s_apellido'];
    //                 }
    //                 if (isset($_POST['c_apellido'])) {
    //                     $resul->c_apellido = $_POST['c_apellido'];
    //                 } else {
    //                     $resul->c_apellido = NULL;
    //                 }
    //                 $resul->ci = $_POST['ci'];
    //                 $resul->expd = $_POST['expd'];
    //                 if (isset($_POST['num_complemento'])) {
    //                     $resul->num_complemento = $_POST['num_complemento'];
    //                 } else {
    //                     $resul->num_complemento = NULL;
    //                 }
    //                 $resul->fecha_nac = $fecha_nac;
    //                 $resul->lugar_nac = $_POST['lugar_nac'];
    //                 $resul->genero = $_POST['sexo'];
    //                 $resul->e_civil = $_POST['e_civil'];
    //                 $resul->codigo = $_POST['ci'];
    //                 $resul->nacionalidad = $_POST['nacionalidad'];
    //                 if ($_POST['nit'] == '') {
    //                     $resul->nit = NULL;
    //                 } else {
    //                     $resul->nit = $_POST['nit'];
    //                 }
    //                 if ($_POST['num_func_sigma'] != '') {
    //                     $resul->num_func_sigma = $_POST['num_func_sigma'];
    //                 } else {
    //                     $resul->num_func_sigma = NULL;
    //                 }
    //                 $resul->grupo_sanguineo = $_POST['grupo_sanguineo'];
    //                 if ($_POST['num_lib_ser_militar'] == '') {
    //                     $resul->num_lib_ser_militar = NULL;
    //                 } else {
    //                     $resul->num_lib_ser_militar = $_POST['num_lib_ser_militar'];
    //                 }
    //                 if (isset($_POST['num_reg_profesional'])) {
    //                     $resul->num_reg_profesional = $_POST['num_reg_profesional'];
    //                 } else {
    //                     $resul->num_reg_profesional = NULL;
    //                 }
    //                 if ($_POST['observacion'] == '') {
    //                     $resul->observacion = NULL;
    //                 } else {
    //                     $resul->observacion = $_POST['observacion'];
    //                 }
    //                 $resul->estado = 0;
    //                 $resul->baja_logica = 1;
    //                 $resul->user_reg_id = 1;
    //                 $resul->fecha_reg = $hoy;
    //                 $resul->tipo_doc = $_POST['tipo_doc'];
    //                 $resul->agrupador = 0;
    //                 //echo $_POST['tipo_doc'];
    //                 //$resul->save();
    //                 if ($resul->save()) {
    //                     $resul = Personas::findFirst(array('ci="' . $_POST['ci'] . '" AND p_apellido = "' . $_POST['p_apellido'] . '"', 'order' => 'id ASC'));
    //                     $res = new Personascontactos();
    //                     $res->persona_id = $resul->id;
    //                     if ($_POST['direccion_dom'] == '') {
    //                         $res->direccion_dom = NULL;
    //                     } else {
    //                         $res->direccion_dom = $_POST['direccion_dom'];
    //                     }
    //                     if ($_POST['telefono_fijo'] == '') {
    //                         $res->telefono_fijo = NULL;
    //                     } else {
    //                         $res->telefono_fijo = $_POST['telefono_fijo'];
    //                     }
    //                     if ($_POST['telefono_inst'] == '') {
    //                         $res->telefono_inst = NULL;
    //                     } else {
    //                         $res->telefono_inst = $_POST['telefono_inst'];
    //                     }
    //                     if ($_POST['telefono_fax'] == '') {
    //                         $res->telefono_fax = NULL;
    //                     } else {
    //                         $res->telefono_fax = $_POST['telefono_fax'];
    //                     }
    //                     if ($_POST['interno_inst'] == '') {
    //                         $res->interno_inst = NULL;
    //                     } else {
    //                         $res->interno_inst = $_POST['interno_inst'];
    //                     }
    //                     if ($_POST['celular_per'] == '') {
    //                         $res->celular_per = NULL;
    //                     } else {
    //                         $res->celular_per = $_POST['celular_per'];
    //                     }
    //                     if ($_POST['celular_inst'] == '') {
    //                         $res->celular_inst = NULL;
    //                     } else {
    //                         $res->celular_inst = $_POST['celular_inst'];
    //                     }
    //                     if ($_POST['e_mail_per'] == '') {
    //                         $res->e_mail_per = NULL;
    //                     } else {
    //                         $res->e_mail_per = $_POST['e_mail_per'];
    //                     }
    //                     if ($_POST['e_mail_inst'] == '') {
    //                         $res->e_mail_inst = NULL;
    //                     } else {
    //                         $res->e_mail_inst = $_POST['e_mail_inst'];
    //                     }
    //                     $res->estado = 0;
    //                     $res->baja_logica = 1;
    //                     if ($res->save()) {
    //                         $msm = array('msm' => 'Exito: Se guardo correctamente');
    //                     } else {
    //                         $msm = array('msm' => 'Error: No se guardo el registro');
    //                     }
    //                 } else {
    //                     $msm = array('msm' => 'Error: No se guardo el registro');
    //                 }
    //             } catch (\Exception $e) {
    //                 echo get_class($e), ": ", $e->getMessage(), "\n";
    //                 echo " File=", $e->getFile(), "\n";
    //                 echo " Line=", $e->getLine(), "\n";
    //                 echo $e->getTraceAsString();
    //             }
    //         }
    //     } else {
    //         $msm = array('msm' => 'Error: no define Id');
    //     }

    //     //$msm = array('msm' => 'Error: No..' );
    //     $this->view->disable();
    //     echo json_encode($msm);
    //     //echo $msm;
    // }

    public function imprimirAction($n_rows, $columns, $filtros)
    {
        //$rows = base64_decode(str_pad(strtr($rows, '-_', '+/'), strlen($rows) % 4, '=', STR_PAD_RIGHT)); 
        $columns = base64_decode(str_pad(strtr($columns, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        $filtros = base64_decode(str_pad(strtr($filtros, '-_', '+/'), strlen($columns) % 4, '=', STR_PAD_RIGHT));
        //echo $rows." - ".$columns;
        $pdf = new FPDF();
        //$rows = (string)$rows;
        //echo $filtros;
        //$rows = json_decode($rows,true);
        $columns = json_decode($columns, true);
        $filtros = json_decode($filtros, true);
        $pdf->AddPage('L', 'Letter');
        //$pdf->SetFont('Arial','B',16);
        $sub_keys = array_keys($columns); //echo $sub_keys[0];
        //$keys = array_keys($rows[0]);
        $n_col = count($columns); //echo$keys[1];
        //echo $n_col;
        $title = utf8_decode('Reporte de Personal "Mi teleférico".');
        $pdf->SetFont('Arial', 'B', 12);
        $w = $pdf->GetStringWidth($title) + 6;
        $pdf->SetX((260 - $w) / 2);
        $pdf->SetDrawColor(0, 80, 80);
        $pdf->SetFillColor(0, 153, 153);
        $pdf->SetTextColor(255);
        // Ancho del borde (1 mm)
        $pdf->SetLineWidth(1);
        // Título
        $pdf->Cell($w + 15, 9, $title, 1, 1, 'C', true);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 10);
        // Color de fondo
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        // Título
        $pdf->Cell(0, 6, "Filtrado por:", 0, 1, 'L', true);
        $where = '';
        for ($k = 0; $k < count($filtros); $k++) {
            for ($j = 0; $j < $n_col; $j++) {
                if ($sub_keys[$j] == $filtros[$k]['columna']) {
                    $col_fil = $columns[$sub_keys[$j]]['text']; //echo $col_fil;
                }
            }
            $cond_fil = ' ' . $col_fil;
            if (strlen($where) > 0) {
                $where .= ' AND ';
            }
            if ($filtros[$k]['tipo'] == 'datefilter') {
                $filtros[$k]['valor'] = date("Y-m-d", strtotime($filtros[$k]['valor']));
                //echo $filtros[$k]['valor'];
            }
            switch ($filtros[$k]['condicion']) {
                /* case 'EMPTY':
                  $cond_fil .= utf8_encode(" que sea vacía ");
                  $where .= $filtros[$k]['columna'].
                  break;
                  case 'NOT_EMPTY':
                  $cond_fil .= utf8_encode(" que no sea vacía ");
                  break; */
                case 'CONTAINS':
                    $cond_fil .= utf8_encode(" que contenga el valor:  " . $filtros[$k]['valor']);
                    $where .= $filtros[$k]['columna'] . ' ILIKE "%' . $filtros[$k]['valor'] . '%"';
                    break;
                case 'GREATER_THAN_OR_EQUAL':
                    $cond_fil .= utf8_encode(" que sea mayor o igual que:  " . $filtros[$k]['valor']);
                    $where .= $filtros[$k]['columna'] . ' >= "' . $filtros[$k]['valor'] . '"';
                    break;
                case 'LESS_THAN_OR_EQUAL':
                    $cond_fil .= utf8_encode(" que sea menor o igual que:  " . $filtros[$k]['valor']);
                    $where .= $filtros[$k]['columna'] . ' <= "' . $filtros[$k]['valor'] . '"';
                    break;
            }//echo $cond_fil;
            $pdf->Cell(0, 6, utf8_decode($cond_fil), 0, 1, 'L', true);
        }
        //echo $where;
        // Salto de línea
        $pdf->Ln(4);
        $pdf->SetFillColor(0, 153, 153);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(0, 80, 80);
        $pdf->SetLineWidth(.3);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 7, 'Nro.', 1, 0, 'C', true);
        for ($j = 0; $j < $n_col; $j++) {
            if ($columns[$sub_keys[$j]]['hidden'] == FALSE) {
                $pdf->Cell(35, 7, $columns[$sub_keys[$j]]['text'], 1, 0, 'C', true);
            }
        }
        $pdf->Ln();
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        $fill = false;
        $ancho = 0;
        $resul = Personas::find(array($where, 'order' => 'id ASC'));
        //$this->view->disable();
        foreach ($resul as $v) {
            $personal[] = array(
                'id' => $v->id,
                'p_nombre' => $v->p_nombre,
                's_nombre' => $v->s_nombre,
                'p_apellido' => $v->p_apellido,
                's_apellido' => $v->s_apellido,
                'ci' => $v->ci,
                'fecha_nac' => date("d-m-Y", strtotime($v->fecha_nac)),
                'lugar_nac' => $v->lugar_nac,
                'genero' => $v->genero,
                'e_civil' => $v->e_civil,
                'tipo_doc' => $v->tipo_doc,
                'expd' => $v->expd,
            );
        } //echo $personal[0]['id'];
        for ($i = 0; $i < $n_rows; $i++) {
            $pdf->Cell(10, 6, $i, 'LR', 0, 'L', $fill);
            $ancho = 10;
            for ($j = 0; $j < $n_col; $j++) {
                if ($columns[$sub_keys[$j]]['hidden'] == FALSE) {
                    $pdf->Cell(35, 6, utf8_decode($personal[$i][$sub_keys[$j]]), 'LR', 0, 'L', $fill);
                    $ancho = $ancho + 35;
                }
            }
            //$pdf->Cell(40,10, ($keys[$j]),0,1);

            $fill = !$fill;
            $pdf->Ln();
            //$pdf->Cell(40,10, ($rows[$i]['id']),0,1);
        }
        $pdf->Cell($ancho, 0, '', 'T');
        $pdf->Output('reporte_personal.pdf', 'I');
        $this->view->disable();
    }
}
