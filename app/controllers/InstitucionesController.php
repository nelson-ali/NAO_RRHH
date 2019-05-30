<?php

/**
 *
 */
class InstitucionesController extends ControllerBase
{

    public function indexAction()
    {
        $this->assets->addJs('/js/instituciones/oasis.instituciones.index.js');
    }

    public function listAction()
    {
        $instituciones = array();
        $resul = Instituciones::find(array('baja_logica=1', 'order' => 'id ASC'));
        $this->view->disable();
        foreach ($resul as $v) {
            $instituciones[] = array(
                'id' => $v->id,
                'razon_social' => $v->razon_social,
                'sigla' => $v->sigla,
                'tipo_institucion' => $v->tipo_institucion,
                'representante_legal' => $v->representante_legal,
                'nit' => $v->nit,
                'pais_id' => $v->pais_id,
                'ciudad' => $v->ciudad,
                'direccion' => $v->direccion,
                'telefono_fijo' => $v->telefono_fijo,
                'telefono_fax' => $v->telefono_fax,
                'celular' => $v->celular,
                'e_mail' => $v->e_mail,
                'pagina_web' => $v->pagina_web,
                'observacion' => $v->observacion,
                'estado' => $v->estado,
                'estado_descripcion' => $v->estado = 1 ? 'ACTIVO' : $v->estado = 0 ? 'PASIVO' : 'EN PROCESO',
//                'visible' => $v->visible,
//                'baja_logica' => $v->baja_logica,
//                'agrupador' => $v->agrupador,
//                'user_reg_id' => $v->user_reg_id,
//                'fecha_reg' => $v->fecha_reg,
//                'uaer_mod_id' => $v->uaer_mod_id,
//                'fecha_mod' => $v->fecha_mod,

            );
        }
        echo json_encode($instituciones);
    }

    public function saveAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth["id"];
        $hoy = date("Y-m-d H:i:s");
        //echo "<p>".$this->request->getPost('razon_social');
        if ($_POST['id'] > 0) {
            /**
             * MODIFICACIÓN DE REGISTRO
             */
            $institucion = Instituciones::findFirstById($this->request->getPost('id'));
            $institucionAux = Instituciones::find(array("id!=" . $_POST['id'] . " AND baja_logica=1 AND (razon_social ILIKE '" . $this->request->getPost('razon_social') . "' OR sigla LIKE '" . $this->request->getPost('sigla') . "')"));
            if (count($institucionAux) == 0) {
                $institucion->razon_social = $this->request->getPost('razon_social');
                $institucion->sigla = $this->request->getPost('sigla');
                $institucion->tipo_institucion = $this->request->getPost('tipo_institucion');
                $institucion->representante_legal = $this->request->getPost('representante_legal');
                $nit = 0;
                if ($this->request->getPost('nit') > 0) {
                    $institucion->nit = $nit;
                }
                $institucion->nit = $nit;
                $institucion->pais_id = $this->request->getPost('id_pais');
                $institucion->ciudad = $this->request->getPost('ciudad');
                $institucion->direccion = $this->request->getPost('direccion');
                $institucion->telefono_fijo = $this->request->getPost('telefono_fijo');
                $institucion->telefono_fax = $this->request->getPost('telefono_fax');
                $institucion->telefono_fax = $this->request->getPost('telefono_fax');
                $institucion->celular = $this->request->getPost('celular');
                $institucion->e_mail = $this->request->getPost('e_mail');
                $institucion->pagina_web = $this->request->getPost('pagina_web');
                $institucion->observacion = $this->request->getPost('observacion');
                $institucion->user_mod_id = $idUsuario;
                $institucion->fecha_mod = $hoy;
                if ($institucion->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute;.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Ya existe registro con similares datos.');
            }


        } else {
            /**
             * Nuevo Registro
             */
            $institucionAux = Instituciones::find(array("baja_logica =1 AND (razon_social LIKE '" . $this->request->getPost('razon_social') . "' OR sigla like '" . $this->request->getPost('sigla') . "' OR CAST(nit AS TEXT) LIKE '" . $this->request->getPost('nit') . "')"));
            if (count($institucionAux) == 0) {
                $institucion = new Instituciones();
                $institucion->razon_social = $this->request->getPost('razon_social');
                $institucion->sigla = $this->request->getPost('sigla');
                $institucion->tipo_institucion = $this->request->getPost('tipo_institucion');

                $institucion->representante_legal = $this->request->getPost('representante_legal');
                $nit = 0;
                if ($this->request->getPost('nit') > 0) {
                    $institucion->nit = $nit;
                }
                $institucion->nit = $nit;
                $institucion->pais_id = $this->request->getPost('pais_id');
                $institucion->ciudad = $this->request->getPost('ciudad');
                $institucion->direccion = $this->request->getPost('direccion');
                $institucion->telefono_fijo = $this->request->getPost('telefono_fijo');
                $institucion->telefono_fax = $this->request->getPost('telefono_fax');
                $institucion->telefono_fax = $this->request->getPost('telefono_fax');
                $institucion->celular = $this->request->getPost('celular');
                $institucion->e_mail = $this->request->getPost('e_mail');
                $institucion->pagina_web = $this->request->getPost('pagina_web');
                $institucion->observacion = $this->request->getPost('observacion');
                $institucion->estado = 1;
                $institucion->visible = 1;
                $institucion->pais_id = 1;
                $institucion->baja_logica = 1;
                $institucion->agrupador = 0;
                $institucion->user_reg_id = $idUsuario;
                $institucion->fecha_reg = $hoy;
                if ($institucion->save()) {
                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute;.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Ya existe registro con similares datos.');
            }

        }
        $this->view->disable();
        echo json_encode($msj);
    }

    public function editAction()
    {
        $institucion = Instituciones::findFirstById($this->request->getPost('id'));
        $institucion->baja_logica = 0;
        if ($institucion->save()) {
            $msj = 'Exito: Se edito correctamente';
        } else {
            $msj = 'Error: No se edito el registro';
        }
        $this->view->disable();
        echo json_encode($msj);

    }

    /**
     * Funci�n para listado de paises.
     */
    public function listpaisesAction()
    {
        $paises = array();
        $resul = Paises::find(array('baja_logica=1', 'order' => 'id ASC'));
        $this->view->disable();
        foreach ($resul as $v) {
            $paises[] = array(
                'id' => $v->id,
                'iso2' => $v->iso2,
                'iso3' => $v->iso3,
                'prefijo' => $v->prefijo,
                'pais' => $v->pais,
                'continente' => $v->continente
            );
        }
        echo json_encode($paises);
    }

    public function deleteAction()
    {
        $institucion = Instituciones::findFirstById($this->request->getPost('id'));
        $institucion->baja_logica = 0;
        if ($institucion->save()) {
            $msj = array('result' => 1, 'msj' => 'Exito: Se elimino correctamente');
        } else {
            $msj = array('result' => 0, 'msj' => 'Error: No se guardo el registro');
        }
        $this->view->disable();
        echo json_encode($msj);
    }

    public function darDeBajaAction()
    {
        $institucion = Instituciones::findFirstById($this->request->getPost('id'));
        $institucion->baja_logica = 0;
        if ($institucion->save()) {
            $msm = 'Exito: Se elimino correctamente';
        } else {
            $msm = 'Error: No se guardo el registro';
        }
        $this->view->disable();
        echo $msm;
    }

    public function pruebaAction()
    {
        $this->assets
            ->addCss('/jqwidgets/styles/jqx.base.css')
            ->addCss('/jqwidgets/styles/jqx.custom.css');

        $this->assets
            ->addJs('/jqwidgets/jqxcore.js')
            ->addJs('/jqwidgets/jqxdraw.js')
            ->addJs('/jqwidgets/jqxgauge.js')
            ->addJs('/scripts/instituciones/oasis.instituciones.index.js');
    }


}