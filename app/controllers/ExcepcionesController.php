<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  13-02-2015
*/

class ExcepcionesController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Función para la carga de la página de gestión de excepciones.
     * Se cargan los combos necesarios.
     */
    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $version);
        $this->assets->addCss('/css/oasis.grillas.css?v=' . $version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $version);
        //$this->assets->addJs('/js/kolorpicker/oasis.kolorpicker.js?v=' . $version);
        //$this->assets->addCss('/assets/css/kolorpicker.css?v=' . $version);

        $this->assets->addJs('/js/colorpicker-master/js/evol.colorpicker.min.js?v=' . $version);
        $this->assets->addCss('/js/colorpicker-master/css/evol.colorpicker.css?v=' . $version);

        $this->assets->addJs('/js/excepciones/oasis.excepciones.tab.js?v=' . $version);
        $this->assets->addJs('/js/excepciones/oasis.excepciones.index.js?v=' . $version);
        $this->assets->addJs('/js/excepciones/oasis.excepciones.new.edit.js?v=' . $version);
        $this->assets->addJs('/js/excepciones/oasis.excepciones.approve.js?v=' . $version);
        $this->assets->addJs('/js/excepciones/oasis.excepciones.export.js?v=' . $version);
        $this->assets->addJs('/js/excepciones/oasis.excepciones.down.js?v=' . $version);
    }

    /**
     * Función para la carga del primer listado sobre la página de gestión de excepciones.
     */
    public function listAction()
    {
        $this->view->disable();
        $obj = new Fexcepciones();
        $where = "";
        if (isset($_POST["genero"]) && $_POST["genero"] != '') {
            $genero = $_POST["genero"];
            switch ($genero) {
                case "F":
                    $where = " WHERE genero_id IN (0,1)";
                    break;
                case "M":
                    $where = " WHERE genero_id IN (0,2)";
                    break;
            }
        }
        if (isset($_POST["boleta"]) && $_POST["boleta"] != '') {
            $boleta = $_POST["boleta"];
            if ($where == "") $where = " WHERE ";
            else $where .= " AND ";
            switch ($boleta) {
                case 1:
                    $where .= " agrupador=1";
                    break;
                case 0:
                    $where .= " agrupador=0";
                    break;
            }
        }
        $resul = $obj->getAll($where);
        $excepciones = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $excepciones[] = array(
                    'nro_row' => 0,
                    'id' => $v->id_excepcion,
                    'excepcion' => $v->excepcion,
                    'tipoexcepcion_id' => $v->tipoexcepcion_id,
                    'tipo_excepcion' => $v->tipo_excepcion,
                    'codigo' => $v->codigo,
                    'color' => $v->color,
                    'descuento' => $v->descuento,
                    'descuento_descripcion' => $v->descuento_descripcion,
                    'compensatoria' => $v->compensatoria,
                    'compensatoria_descripcion' => $v->compensatoria_descripcion,
                    'genero_id' => $v->genero_id,
                    'genero' => $v->genero,
                    'cantidad' => $v->cantidad,
                    'unidad' => $v->unidad,
                    'fraccionamiento' => $v->fraccionamiento,
                    'frecuencia_descripcion' => $v->frecuencia_descripcion,
                    'redondeo' => $v->redondeo,
                    'redondeo_descripcion' => $v->redondeo_descripcion,
                    'horario' => $v->horario,
                    'horario_descripcion' => $v->horario_descripcion,
                    'refrigerio' => $v->refrigerio,
                    'refrigerio_descripcion' => $v->refrigerio_descripcion,
                    'lugar' => $v->lugar,
                    'lugar_descripcion' => $v->lugar_descripcion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'boleta' => $v->agrupador,
                    'boleta_descripcion' => ($v->agrupador == 1) ? "SI" : "NO",
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,

                );
            }
        }
        echo json_encode($excepciones);
    }

    /**
     * Función para la obtención de un listado de boletas disponibles considerando el registro de relación laboral.
     */
    public function listbyrelaboralAction()
    {
        $this->view->disable();
        $obj = new Fexcepciones();
        $where = "";
        $idRelaboral = 0;
        $genero = null;
        $boleta = null;
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] != '') {
            $idRelaboral = $_POST["id_relaboral"];
        }
        if (isset($_POST["genero"]) && $_POST["genero"] != '') {
            $genero = $_POST["genero"];
        }
        if (isset($_POST["boleta"]) && $_POST["boleta"] != '') {
            $boleta = $_POST["boleta"];
        }
        $resul = $obj->getAllByRelaboral($idRelaboral, $boleta, $genero, $where);
        $excepciones = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $excepciones[] = array(
                    'nro_row' => 0,
                    'id' => $v->id_excepcion,
                    'excepcion' => $v->excepcion,
                    'tipoexcepcion_id' => $v->tipoexcepcion_id,
                    'tipo_excepcion' => $v->tipo_excepcion,
                    'codigo' => $v->codigo,
                    'color' => $v->color,
                    'descuento' => $v->descuento,
                    'descuento_descripcion' => $v->descuento_descripcion,
                    'compensatoria' => $v->compensatoria,
                    'compensatoria_descripcion' => $v->compensatoria_descripcion,
                    'genero_id' => $v->genero_id,
                    'genero' => $v->genero,
                    'cantidad' => $v->cantidad,
                    'unidad' => $v->unidad,
                    'fraccionamiento' => $v->fraccionamiento,
                    'frecuencia_descripcion' => $v->frecuencia_descripcion,
                    'redondeo' => $v->redondeo,
                    'redondeo_descripcion' => $v->redondeo_descripcion,
                    'horario' => $v->horario,
                    'horario_descripcion' => $v->horario_descripcion,
                    'refrigerio' => $v->refrigerio,
                    'refrigerio_descripcion' => $v->refrigerio_descripcion,
                    'lugar' => $v->lugar,
                    'lugar_descripcion' => $v->lugar_descripcion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'boleta' => $v->agrupador,
                    'boleta_descripcion' => ($v->agrupador == 1) ? "SI" : "NO",
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod,

                );
            }
        }
        echo json_encode($excepciones);
    }

    /**
     * Función para la obtención del listado de tipos de excepciones.
     */
    public function listtiposexcepcionesAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'TIPO_EXCEPCION' AND estado=1 AND baja_logica=1", 'order' => 'valor_1 ASC'));
        $tiposexcepciones = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $tiposexcepciones[] = array(
                    'id' => $v->id,
                    'id_tipo_excepcion' => $v->nivel,
                    'tipo_excepcion' => $v->valor_1,
                    'descripcion' => $v->descripcion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado
                );
            }
        }
        echo json_encode($tiposexcepciones);
    }

    /**
     * Función para la obtención del listado de géneros disponibles en el sistema.
     */
    public function listgenerosAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'GENERO_EXCEPCION' AND estado=1 AND baja_logica=1", 'order' => 'valor_1 ASC'));
        $generos = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $generos[] = array(
                    'id' => $v->id,
                    'id_genero' => $v->nivel,
                    'genero' => $v->valor_1,
                    'descripcion' => $v->descripcion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado
                );
            }
        }
        echo json_encode($generos);
    }

    /**
     * Función para la obtención del listado de unidades disponibles en el sistema.
     */
    public function listunidadesAction()
    {
        $this->view->disable();
        $resul = Parametros::find(array("parametro LIKE 'UNIDAD_EXCEPCION' AND estado=1 AND baja_logica=1", 'order' => 'nivel ASC'));
        $unidades = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $unidades[] = array(
                    'id' => $v->id,
                    'id_unidad' => $v->nivel,
                    'unidad' => $v->valor_1,
                    'descripcion' => $v->descripcion,
                    'observacion' => $v->observacion,
                    'estado' => $v->estado
                );
            }
        }
        echo json_encode($unidades);
    }

    /**
     * Función para la obtención del listado de fraccionamientos disponibles en el sistema.
     */
    public function listfraccionamientosAction()
    {
        $this->view->disable();
        $unidades = Array();
        if (isset($_POST["id_minima"]) && $_POST["id_minima"] > 0) {
            $idMinima = $_POST["id_minima"];
            $resul = Parametros::find(array("parametro LIKE 'UNIDAD_EXCEPCION' AND estado=1 AND baja_logica=1 AND CAST(nivel AS integer)>" . $idMinima, 'order' => 'nivel ASC'));
            //comprobamos si hay filas
            if ($resul->count() > 0) {
                foreach ($resul as $v) {
                    $unidades[] = array(
                        'id' => $v->id,
                        'id_fraccionamiento' => $v->nivel,
                        'fraccionamiento' => $v->valor_1,
                        'descripcion' => $v->descripcion,
                        'observacion' => $v->observacion,
                        'estado' => $v->estado
                    );
                }
            }
        }
        echo json_encode($unidades);
    }

    /**
     * Función para el almacenamiento y actualización de un registro de Excepción.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Crítico Error
     *  -2: Error de Conexión
     *  -3: Usuario no Autorizado
     */
    public function saveAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Modificación de registro de Feriado
             */
            $excepcion = strtoupper($_POST['excepcion']);
            $idTipoExcepcion = $_POST['tipoexcepcion_id'];
            $codigo = $_POST['codigo'];
            $color = $_POST['color'];
            $descuento = $_POST['descuento'];
            $compensatoria = $_POST['compensatoria'];
            $idGenero = $_POST['genero_id'];
            $cantidad = $_POST['cantidad'];
            $unidad = $_POST['unidad'];
            $fraccionamiento = $_POST['fraccionamiento'];
            $redondeo = $_POST['redondeo'];
            $horario = $_POST['horario'];
            $refrigerio = $_POST['refrigerio'];
            $lugar = $_POST['lugar'];
            $articuloRip = $_POST['articulo_rip'];
            $incisoRip = $_POST['inciso_rip'];
            $boleta = $_POST['boleta'];
            $observacion = $_POST['observacion'];
            if ($excepcion != "" && $idTipoExcepcion > 0 && $codigo != '' && $color != '' && $descuento != '' && $compensatoria != '' && $horario != '' && $refrigerio != '' && $idGenero >= 0) {
                $objExcepciones = Excepciones::findFirst(array("id=" . $_POST["id"]));
                if (count($objExcepciones) > 0) {
                    $cantMismosDatos = Excepciones::count(array("id!=" . $_POST["id"] . " AND UPPER(excepcion) LIKE UPPER('" . $excepcion . "') AND baja_logica=1 AND estado>=0"));
                    if ($cantMismosDatos == 0) {
                        $objExcepciones->excepcion = $excepcion;
                        $objExcepciones->tipoexcepcion_id = $idTipoExcepcion;
                        $objExcepciones->codigo = $codigo;
                        $objExcepciones->color = $color;
                        $objExcepciones->descuento = $descuento;
                        $objExcepciones->compensatoria = $compensatoria;
                        $objExcepciones->horario = $horario;
                        $objExcepciones->refrigerio = $refrigerio;
                        $objExcepciones->lugar = $lugar;
                        $objExcepciones->agrupador = $boleta;
                        $objExcepciones->genero_id = $idGenero;
                        if ($cantidad > 0 && $unidad != '') {
                            $objExcepciones->cantidad = $cantidad;
                            $objExcepciones->unidad = $unidad;
                            if ($fraccionamiento != '') $objExcepciones->fraccionamiento = $fraccionamiento;
                            else $objExcepciones->fraccionamiento = null;
                        } else {
                            $objExcepciones->cantidad = null;
                            $objExcepciones->unidad = null;
                            $objExcepciones->fraccionamiento = null;
                        }
                        $objExcepciones->redondeo = $redondeo;
                        $objExcepciones->observacion = $observacion;
                        $objExcepciones->user_mod_id = $user_mod_id;
                        $objExcepciones->fecha_mod = $hoy;
                        try {
                            $ok = $objExcepciones->save();
                            if ($ok) {
                                $objEr = Excepcionesrip::findFirst(array("excepcion_id=" . $objExcepciones->id));
                                if (is_object($objEr)) {
                                    $objEr->articulo = $articuloRip;
                                    $objEr->articulo = $incisoRip;
                                    $objEr->user_mod_id = $user_mod_id;
                                    $objEr->fecha_mod = $hoy;
                                } else {
                                    $objEr = new Excepcionesrip();
                                    $objEr->user_reg_id = $user_reg_id;
                                    $objEr->fecha_reg = $hoy;
                                    $objEr->estado = 1;
                                    $objEr->baja_logica = 1;
                                    $objEr->agrupador = 0;
                                }
                                $objEr->articulo = $articuloRip;
                                $objEr->inciso = $incisoRip;
                                if ($objEr->save()) {
                                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente el registro de la excepci&oacute;n.');
                                } else
                                    $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente el registro de la excepci&oacute;n. Sin embargo, no se pudo realizar el registro del articulo e inciso de respaldo.');
                            } else {
                                $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro de la excepci&oacute;n.');
                            }
                        } catch (\Exception $e) {
                            echo get_class($e), ": ", $e->getMessage(), "\n";
                            echo " File=", $e->getFile(), "\n";
                            echo " Line=", $e->getLine(), "\n";
                            echo $e->getTraceAsString();
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la excepci&oacute;n.');
                        }
                    } else $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados son similares a otro registro existente, debe modificar los valores necesariamente.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        } else {
            /**
             * Registro de Excepción
             */
            $excepcion = strtoupper($_POST['excepcion']);
            $idTipoExcepcion = $_POST['tipoexcepcion_id'];
            $codigo = $_POST['codigo'];
            $color = $_POST['color'];
            $descuento = $_POST['descuento'];
            $compensatoria = $_POST['compensatoria'];
            $idGenero = $_POST['genero_id'];
            $cantidad = $_POST['cantidad'];
            $unidad = $_POST['unidad'];
            $fraccionamiento = $_POST['fraccionamiento'];
            $redondeo = $_POST['redondeo'];
            $horario = $_POST['horario'];
            $refrigerio = $_POST['refrigerio'];
            $lugar = $_POST['lugar'];
            $articuloRip = $_POST['articulo_rip'];
            $incisoRip = $_POST['inciso_rip'];
            $boleta = $_POST['boleta'];
            $observacion = $_POST['observacion'];
            if ($excepcion != "" && $idTipoExcepcion > 0 && $codigo != '' && $color != '' && $descuento != '' && $compensatoria != '' && $horario != '' && $refrigerio != '' && $idGenero >= 0) {
                $cantMismosDatos = Excepciones::count(array("UPPER(excepcion) LIKE UPPER('" . $excepcion . "') AND baja_logica=1 AND estado>=0"));
                if ($cantMismosDatos == 0) {
                    $objExcepciones = new Excepciones();
                    $objExcepciones->excepcion = $excepcion;
                    $objExcepciones->tipoexcepcion_id = $idTipoExcepcion;
                    $objExcepciones->codigo = $codigo;
                    $objExcepciones->color = $color;
                    $objExcepciones->descuento = $descuento;
                    $objExcepciones->compensatoria = $compensatoria;
                    $objExcepciones->horario = $horario;
                    $objExcepciones->refrigerio = $refrigerio;
                    $objExcepciones->genero_id = $idGenero;
                    if ($cantidad > 0 && $unidad != '') {
                        $objExcepciones->cantidad = $cantidad;
                        $objExcepciones->unidad = $unidad;
                        if ($fraccionamiento != '') $objExcepciones->fraccionamiento = $fraccionamiento;
                        else $objExcepciones->fraccionamiento = null;
                    }
                    $objExcepciones->redondeo = $redondeo;
                    $objExcepciones->observacion = $observacion;
                    $objExcepciones->estado = 1;
                    $objExcepciones->baja_logica = 1;
                    $objExcepciones->agrupador = $boleta;
                    $objExcepciones->user_reg_id = $user_reg_id;
                    $objExcepciones->fecha_reg = $hoy;
                    try {
                        $ok = $objExcepciones->save();
                        if ($ok) {
                            $objEr = Excepcionesrip::findFirst(array("excepcion_id=" . $objExcepciones->id));
                            if (is_object($objEr)) {
                                $objEr->articulo = $articuloRip;
                                $objEr->articulo = $incisoRip;
                                $objEr->user_mod_id = $user_mod_id;
                                $objEr->fecha_mod = $hoy;
                            } else {
                                $objEr = new Excepcionesrip();
                                $objEr->user_reg_id = $user_reg_id;
                                $objEr->fecha_reg = $hoy;
                                $objEr->estado = 1;
                                $objEr->baja_logica = 1;
                                $objEr->agrupador = 0;
                            }
                            $objEr->articulo = $articuloRip;
                            $objEr->inciso = $incisoRip;

                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se registr&oacute;.');
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro del feriado.');
                    }
                } else {
                    $msj = array('result' => 0, 'msj' => 'Error: El nombre de la Excepci&oacute;n es similar a otro registro existente, debe modificar este valor necesariamente.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        }
        echo json_encode($msj);
    }

    /*
     * Función para la aprobación del registro de una excepción.
     */
    public function approveAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Aprobación de registro
             */
            $objExcepciones = Excepciones::findFirstById($_POST["id"]);
            if ($objExcepciones->id > 0 && $objExcepciones->estado == 2) {
                try {
                    $objExcepciones->estado = 1;
                    $objExcepciones->user_mod_id = $user_mod_id;
                    $objExcepciones->fecha_mod = $hoy;
                    $ok = $objExcepciones->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se aprob&oacute; correctamente el registro de la excepci&oacute;n.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se aprob&oacute; el registro de la excepci&oacute;n.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la excepci&oacute;n.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: El registro de la excepci&oacute;n no cumple con el requisito establecido para su aprobaci&oacute;n, debe estar en estado EN PROCESO.');
            }
        } else {
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se envi&oacute; el identificador del registro de la excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para el la baja del registro de una excepción.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Crítico Error
     *  -2: Error de Conexión
     *  -3: Usuario no Autorizado
     */
    public function downAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        try {
            if (isset($_POST["id"]) && $_POST["id"] > 0) {
                $objCE = Controlexcepciones::find("excepcion_id=" . $_POST["id"] . " AND baja_logica=1");
                if ($objCE->count() == 0) {
                    /**
                     * Baja de registro
                     */
                    $objExcepciones = Excepciones::findFirstById($_POST["id"]);
                    $objExcepciones->estado = 0;
                    $objExcepciones->baja_logica = 0;//Debido a que no es necesario que el registro continúe apareciendo en estado pasivo.
                    $objExcepciones->user_mod_id = $user_mod_id;
                    $objExcepciones->fecha_mod = $hoy;
                    if ($objExcepciones->save()) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Registro de Baja realizado de modo satisfactorio.');
                    } else {
                        foreach ($objExcepciones->getMessages() as $message) {
                            echo $message, "\n";
                        }
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se pudo dar de baja al registro de la excepci&oacute;n.');
                    }
                } else {
                    $msj = array('result' => -1, 'msj' => 'Error: No se puedo dar de baja al registro de la excepci&oacute;n debido a la existencia de (' . $objCE->count() . ') registros vinculados.');
                }
            } else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se efectu&oacute; la baja debido a que no se especific&oacute; el registro de la excepci&oacute;n.');
        } catch (\Exception $e) {
            echo get_class($e), ": ", $e->getMessage(), "\n";
            echo " File=", $e->getFile(), "\n";
            echo " Line=", $e->getLine(), "\n";
            echo $e->getTraceAsString();
            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de la excepci&oacute;n.');
        }
        echo json_encode($msj);
    }

    /**
     * Función para obtener el objeto referido al respaldo en el RIP para la excepción.
     */
    public function getrespaldoripAction()
    {
        $this->view->disable();
        $exec = Array();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $objExcepcionesrip = Excepcionesrip::findFirst(array("excepcion_id=" . $_POST["id"] . " AND baja_logica=1"));
            if (is_object($objExcepcionesrip)) {
                $exec = array(
                    'id' => $objExcepcionesrip->id,
                    'articulo' => $objExcepcionesrip->articulo,
                    'inciso' => $objExcepcionesrip->inciso
                );
            }
        }
        echo json_encode($exec);
    }
} 