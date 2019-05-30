<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  30-01-2015
*/

class CuposturnosController extends ControllerBase{
    public function initialize()
    {
        parent::initialize();
    }
    /**
     * Función para el almacenamiento y actualización de un registro de cupo por turno.
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
        $user_mod_id = $auth['id'];
        $user_reg_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Edición de registro
             */
            if(isset($_POST["cupo"])&&$_POST["cupo"]>0){
                try {
                    $objCuposTurnos = Cuposturnos::findFirstById($_POST["id"]);
                    if($objCuposTurnos->id>0){
                        $objCuposTurnos->cupo = $_POST["cupo"];
                        $objCuposTurnos->user_mod_id = $user_mod_id;
                        $objCuposTurnos->fecha_mod = $hoy;
                        $ok = $objCuposTurnos->save();
                        if ($ok) {
                            $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se modific&oacute; correctamente.');
                        } else {
                            foreach ($objCuposTurnos->getMessages() as $message) {
                                echo $message, "\n";
                            }
                            $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de cupo por turno.');
                        }
                    }else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de cupo por turno debido a que el registro para editar no se encontr&oacute;.');
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de cupo por turno.');
                }
            }else {

            }
        } else {
            /**
             * Nuevo Registro
             */
            if (isset($_POST['cupo'])&&$_POST["cupo"]>0&&
                isset($_POST["id_perfillaboral"])&&$_POST["id_perfillaboral"]>0&&
                isset($_POST["id_ubicacion"])&&$_POST["id_ubicacion"]>0&&
                isset($_POST["fecha_ini"])&&$_POST["fecha_ini"]!=''&&
                isset($_POST["fecha_fin"])&&$_POST["fecha_fin"]!=''
            ) {
                try {
                    $objCuposTurnos = new Cuposturnos();
                    $objCuposTurnos->id = null;
                    $objCuposTurnos->perfillaboral_id = $_POST["id_perfillaboral"];
                    $objCuposTurnos->ubicacion_id = $_POST["id_ubicacion"];
                    $date1 = new DateTime($_POST['fecha_ini']);
                    $date2 = new DateTime($_POST['fecha_fin']);
                    $objCuposTurnos->fecha_ini = $date1->format('Y-m-d');
                    $objCuposTurnos->fecha_fin = $date2->format('Y-m-d');
                    $objCuposTurnos->cupo = $_POST["cupo"];
                    $objCuposTurnos->estado = 1;
                    $objCuposTurnos->baja_logica = 1;
                    $objCuposTurnos->agrupador = 0;
                    $objCuposTurnos->user_reg_id = $user_reg_id;
                    $objCuposTurnos->fecha_reg = $hoy;
                    $ok = $objCuposTurnos->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se guard&oacute; correctamente.');
                    } else {
                        foreach ($objCuposTurnos->getMessages() as $message) {
                            echo $message, "\n";
                        }
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de cupo por turno.');
                    }
                } catch (\Exception $e) {
                    echo get_class($e), ": ", $e->getMessage(), "\n";
                    echo " File=", $e->getFile(), "\n";
                    echo " Line=", $e->getLine(), "\n";
                    echo $e->getTraceAsString();
                    $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de cupo por turno.');
                }
            } else {
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro debido a datos erroneos en el cupo por turno.');
            }
        }
        echo json_encode($msj);
    }
    /**
     * Función para la baja del registro de cupo por turno.
     */
    public function downAction()
    {
        $auth = $this->session->get('auth');
        $user_mod_id = $auth['id'];
        $msj = Array();
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["id"]) && $_POST["id"] > 0) {
            /**
             * Baja de registro
             */
            try {
                $objCuposTurnos = Cuposturnos::findFirstById($_POST["id"]);
                if($objCuposTurnos->id>0){
                    $objCuposTurnos->estado=0;
                    $objCuposTurnos->baja_logica=0;
                    $objCuposTurnos->user_mod_id = $user_mod_id;
                    $objCuposTurnos->fecha_mod = $hoy;
                    $ok = $objCuposTurnos->save();
                    if ($ok) {
                        $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se di&oacute; de baja correctamente el registro de cupo por turno.');
                    } else {
                        foreach ($objCuposTurnos->getMessages() as $message) {
                            echo $message, "\n";
                        }
                        $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se di&oacute; de baja el registro de cupo por turno.');
                    }
                }else $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se di&oacute; de baja el registro de cupo por turno debido a que el registro no se encontr&oacute;.');
            } catch (\Exception $e) {
                echo get_class($e), ": ", $e->getMessage(), "\n";
                echo " File=", $e->getFile(), "\n";
                echo " Line=", $e->getLine(), "\n";
                echo $e->getTraceAsString();
                $msj = array('result' => -1, 'msj' => 'Error cr&iacute;tico: No se guard&oacute; el registro de cupo por turno.');
            }

        } else {
                $msj = array('result' => -1, 'msj' => 'Error: No se pudo eliminar el registro de cupo por turno debido a que no se envi&oacute; el identificador del registro.');
        }
        echo json_encode($msj);
    }
} 