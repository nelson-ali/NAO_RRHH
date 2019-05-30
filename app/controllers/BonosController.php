<?php

/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Telefórico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  18-01-2016
*/

class BonosController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Función para registrar un nuevo bono
     */
    public function saveAction()
    {
        $auth = $this->session->get('auth');
        $user_reg_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $idTipoBono = 1;
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Modificación de registro del Bono de Antigüedad
             */
            $codigo = $_POST["codigo"];
            $idRelaboral = $_POST['id_relaboral'];
            $gestion = $_POST['gestion'];
            $mes = $_POST['mes'];
            $anios = $_POST['importe'];
            $meses = $_POST['meses'];
            $dias = $_POST['dias'];
            $monto = 0;
            $diasEfectivos = $_POST["dias_efectivos"];
            if ($anios > 0 && $diasEfectivos > 0) {
                $obj = new Bonos();
                $monto = $obj->getCalculoBonoAntiguedad($anios, $diasEfectivos);
            }
            $observacion = $_POST['observacion'];
            if ($idRelaboral > 0 && $gestion > 0 && $mes > 0 && $anios > 0) {
                $objBonoAntiguedad = Bonos::findFirstById($_POST["id"]);
                if (count($objBonoAntiguedad) > 0) {
                    $objBonoAntiguedad->tipobono_id = $idTipoBono;
                    $objBonoAntiguedad->codigo = $codigo;
                    $objBonoAntiguedad->relaboral_id = $idRelaboral;
                    $objBonoAntiguedad->gestion = $gestion;
                    $objBonoAntiguedad->mes = $mes;
                    $objBonoAntiguedad->anios = $anios;
                    $objBonoAntiguedad->meses = $meses;
                    $objBonoAntiguedad->dias = $dias;
                    $objBonoAntiguedad->monto = $monto;
                    if ($monto == 0) {
                        $objBonoAntiguedad->baja_logica = 0;
                    }
                    $objBonoAntiguedad->observacion = $observacion;
                    $objBonoAntiguedad->user_mod_id = $user_mod_id;
                    $objBonoAntiguedad->fecha_mod = $hoy;
                    try {
                        $ok = $objBonoAntiguedad->save();
                        if ($ok) {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente el registro del Formulario 110 de Impuestos por Refrigerio.');
                        } else {
                            $msj = array('result' => 0, 'msj' => 'Error: No se modific&oacute; el registro de Formulario 110 de Impuestos por Refrigerio.');
                        }
                    } catch (\Exception $e) {
                        echo get_class($e), ": ", $e->getMessage(), "\n";
                        echo " File=", $e->getFile(), "\n";
                        echo " Line=", $e->getLine(), "\n";
                        echo $e->getTraceAsString();
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Formulario 110 de Impuestos por Refrigerio.');
                    }


                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }
        } else {
            /**
             * Registro nuevo del Bono de Antigóedad
             */
            $idRelaboral = $_POST['id_relaboral'];
            $codigo = $_POST['codigo'];
            $gestion = $_POST['gestion'];
            $mes = $_POST['mes'];
            $anios = $_POST['anios'];
            $meses = $_POST['meses'];
            $dias = $_POST['dias'];
            $monto = 0;
            $diasEfectivos = $_POST["dias_efectivos"];
            if ($anios >= 0 && $diasEfectivos > 0) {
                $obj = new Bonos();
                $monto = $obj->getCalculoBonoAntiguedad($anios, $diasEfectivos);
            }
            $observacion = $_POST['observacion'];
            if ($codigo != '' && $idRelaboral > 0 && $gestion > 0 && $mes > 0 && $anios >= 0) {
                $objBonoAntiguedad = Bonos::findFirst(array("relaboral_id=" . $idRelaboral . " AND gestion=" . $gestion . " AND mes=" . $mes));
                if (is_object($objBonoAntiguedad)) {
                    $objBonoAntiguedad->user_mod_id = $user_mod_id;
                    $objBonoAntiguedad->fecha_mod = $hoy;
                } else {
                    $objBonoAntiguedad = new Bonos();
                    $objBonoAntiguedad->user_reg_id = $user_reg_id;
                    $objBonoAntiguedad->fecha_reg = $hoy;
                }
                $objBonoAntiguedad->tipobono_id = $idTipoBono;
                $objBonoAntiguedad->codigo = $codigo;
                $objBonoAntiguedad->relaboral_id = $idRelaboral;
                $objBonoAntiguedad->gestion = $gestion;
                $objBonoAntiguedad->mes = $mes;
                $objBonoAntiguedad->anios = $anios;
                $objBonoAntiguedad->meses = $meses;
                $objBonoAntiguedad->dias = $dias;
                $objBonoAntiguedad->monto = $monto;
                $objBonoAntiguedad->observacion = $observacion;
                $objBonoAntiguedad->estado = 1;
                $objBonoAntiguedad->baja_logica = 1;
                if ($monto == 0) {
                    $objBonoAntiguedad->baja_logica = 0;
                }
                $objBonoAntiguedad->agrupador = 0;
                try {
                    $ok = $objBonoAntiguedad->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente el registro del Bono de Antig&uuml;edad.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se guard&oacute; el registro de Bono de Antig&uuml;edad.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de Bono de Antig&uuml;edad.');
                }
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su registro.');
            }

        }
        echo json_encode($msj);
    }

    /**
     * Función para dar de baja un registro de Bono de Antigóedad.
     */
    public function downAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        $idBono = 0;
        $idRelaboral = $_POST['id_relaboral'];
        $gestion = $_POST['gestion'];
        $mes = $_POST['mes'];
        if (isset($_POST['id']) && $_POST['id'] > 0) {
            $idBono = $_POST['id'];
        }
        if ($idRelaboral > 0 && $gestion > 0 && $mes > 0) {
            if ($idBono > 0) {
                $objBonoAntiguedad = Bonos::findFirstById($idBono);
            } else $objBonoAntiguedad = Bonos::findFirst(array("relaboral_id=" . $idRelaboral . " AND gestion=" . $gestion . " AND mes=" . $mes));
            if (is_object($objBonoAntiguedad)) {
                $objBonoAntiguedad->baja_logica = 0;
                $objBonoAntiguedad->user_mod_id = $user_mod_id;
                $objBonoAntiguedad->fecha_mod = $hoy;
                try {
                    $ok = $objBonoAntiguedad->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se di&oacute; de baja el registro del Bono de Antig&uuml;edad.');
                    } else {
                        $msj = array('result' => 0, 'msj' => 'Error: No se di&oacute; de baja el registro de Bono de Antig&uuml;edad.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se di&oacute; de baja el registro de Bono de Antig&uuml;edad.');
                }
            }
        } else {
            $msj = array('result' => 0, 'msj' => 'Error: Los datos enviados no cumplen los criterios necesarios para su baja.');
        }
        echo json_encode($msj);
    }

    public function listAction()
    {
        $this->view->disable();
        $obj = new Fferiados();
        $resul = $obj->getAll();
        $horariolaboral = Array();
        //comprobamos si hay filas
        if ($resul->count() > 0) {
            foreach ($resul as $v) {
                $horariolaboral[] = array(
                    'nro_row' => 0,
                    'id' => $v->id,
                    'feriado' => $v->feriado,
                    'descripcion' => $v->descripcion,
                    'regional_id' => $v->regional_id,
                    'regional' => $v->regional,
                    'horario_discontinuo' => $v->horario_discontinuo,
                    'horario_discontinuo_descripcion' => $v->horario_discontinuo_descripcion,
                    'horario_continuo' => $v->horario_continuo,
                    'horario_continuo_descripcion' => $v->horario_continuo_descripcion,
                    'horario_multiple' => $v->horario_multiple,
                    'horario_multiple_descripcion' => $v->horario_multiple_descripcion,
                    'cantidad_dias' => $v->cantidad_dias,
                    'repetitivo' => $v->repetitivo,
                    'repetitivo_descripcion' => $v->repetitivo_descripcion,
                    'dia' => $v->dia,
                    'mes' => $v->mes,
                    'mes_nombre' => $v->mes_nombre,
                    'gestion' => $v->gestion,
                    'observacion' => $v->observacion != null ? $v->observacion : '',
                    'estado' => $v->estado,
                    'estado_descripcion' => $v->estado_descripcion,
                    'baja_logica' => $v->baja_logica,
                    'agrupador' => $v->agrupador,
                    'user_reg_id' => $v->user_reg_id,
                    'fecha_reg' => $v->fecha_reg,
                    'user_mod_id' => $v->user_mod_id,
                    'fecha_mod' => $v->fecha_mod
                );
            }
        }
        echo json_encode($horariolaboral);
    }

    /**
     * Función para la obtención del objeto cargado con los datos referentes a un registro de bono en particular.
     */
    public function getoneforrelaboralAction()
    {
        $this->view->disable();
        $obj = new Bonos();
        $idRelaboral = 0;
        $bonoAntiguedad = Array();
        $id = 0;
        $idRelaboral = 0;
        $gestion = 0;
        $mes = 0;
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            $id = $_POST["id"];
        }
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0) {
            $idRelaboral = $_POST["id_relaboral"];
        }
        if (isset($_POST["gestion"]) && $_POST["gestion"] > 0) {
            $gestion = $_POST["gestion"];
        }
        if (isset($_POST["mes"]) && $_POST["mes"] > 0) {
            $mes = $_POST["mes"];
        }
        if (isset($_POST["id_relaboral"]) && $_POST["id_relaboral"] > 0 && $gestion > 0 && $mes > 0) {
            if ($id > 0) $resul = Bonos::findFirstById($id);
            else $resul = Bonos::find(array("relaboral_id=" . $idRelaboral . " AND gestion=" . $gestion . " AND mes=" . $mes . " AND estado=1 AND baja_logica=1 LIMIT 1"));
            if (is_object($resul)) {
                foreach ($resul as $v) {
                    $bonoAntiguedad[] = array(
                        'nro_row' => 0,
                        'id' => $v->id,
                        'tipobono_id' => $v->tipobono_id,
                        'codigo' => $v->codigo,
                        'relaboral_id' => $v->relaboral_id,
                        'presentaciondoc_id' => $v->presentaciondoc_id,
                        'gestion' => $v->gestion,
                        'mes' => $v->mes,
                        'anios' => $v->anios,
                        'meses' => $v->meses,
                        'dias' => $v->dias,
                        'monto' => $v->monto,
                        'observacion' => $v->observacion != null ? $v->observacion : '',
                        'estado' => $v->estado,
                        'estado_descripcion' => ($v->estado == 1) ? "ACTIVO" : "PASIVO",
                        'baja_logica' => $v->baja_logica,
                        'agrupador' => $v->agrupador,
                        'user_reg_id' => $v->user_reg_id,
                        'fecha_reg' => $v->fecha_reg,
                        'user_mod_id' => $v->user_mod_id,
                        'fecha_mod' => $v->fecha_mod
                    );
                }
            }
        }
        echo json_encode($bonoAntiguedad);
    }

    /**
     * Función para la obtención del monto calculado para pago por bono de antigóedad.
     */
    public function calculatebonoAction()
    {
        $this->view->disable();
        $monto = 0;
        $anios = 0;
        $diasEfectivos = 0;
        if (isset($_POST["anios"]) && $_POST["anios"] > 0) {
            $anios = $_POST["anios"];
        }
        if (isset($_POST["dias_efectivos"]) && $_POST["dias_efectivos"] > 0) {
            $diasEfectivos = $_POST["dias_efectivos"];
        }
        if ($anios >= 0 && $diasEfectivos > 0) {
            $obj = new Bonos();
            $monto = $obj->getCalculoBonoAntiguedad($anios, $diasEfectivos);
        }
        echo $monto;
    }
}