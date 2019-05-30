<?php

use Phalcon\Mvc\Controller;
use Phalcon\Events\Event;

class ControllerBase extends Controller
{

    protected $_user;
    protected $_con;

    public function beforeExecuteRoute()
    {
        //Check whether the "auth" variable exists in session to define the active role
        $auth = $this->session->get('auth');
        if (!$auth) {
            header('Location: /login');
        }
        return true;
    }

    protected function initialize()
    {

        $auth = $this->session->get('auth');
        if (!isset($auth['id'])) {
            $this->response->redirect('/login');
            //parent::initialize();
        } else {
            if (isset($auth['version'])) {
                $version = $auth['version'];
            } else $version = "0.0.0";

            //obtenemos la instancia del usuario
            $user_id = $auth['id'];
            $this->_user = Usuarios::findFirst("id = '$user_id'");
            $this->_user->nivel = $auth['nivel'];
            //Prepend the application name to the title
            $this->tag->setTitle('VB - SRRHH');

            $this->assets
                ->addCss('/assets/css/bootstrap.min.css?v=' . $version)
                ->addCss('/assets/css/plugins.css?v=' . $version)
                ->addCss('/assets/css/main.css?v=' . $version)
                ->addCss('/assets/css/themes.css?v=' . $version)
                ->addCss('/js/datepicker/datepicker.css?v=' . $version)
                ->addCss('/js/datatables/dataTables.bootstrap.css?v=' . $version)
                ->addCss('/js/jqwidgets/styles/jqx.base.css?v=' . $version)
                ->addCss('/js/jqwidgets/styles/jqx.oasis.css?v=' . $version)
                ->addCss('/js/select/bootstrap-select.css?v=' . $version)
                ->addCss('/js/select/ajax-bootstrap-select.css?v=' . $version)

            ;
            $this->assets
                ->addJs('/js/jquery.min.js?v=' . $version)
                ->addJs('/js/wizard/jquery-latest.js?v=' . $version)
                ->addJs('/js/wizard/jquery.bootstrap.wizard.min.js?v=' . $version)
                ->addJs('/js/wizard/prettify.js?v=' . $version)
                ->addJs('/assets/js/vendor/modernizr-2.7.1-respond-1.4.2.min.js?v=' . $version)
                ->addJs('/assets/js/vendor/bootstrap.min.js?v=' . $version)
                ->addJs('/assets/js/plugins.js?v=' . $version)
                ->addJs('/assets/js/app.js?v=' . $version)
                ->addJs('/js/app.plugin.js?v=' . $version)
                ->addJs('/js/jqwidgets/simulator.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxcore.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxdata.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxbuttons.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxscrollbar.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxdatatable.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxlistbox.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxdropdownlist.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxpanel.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxradiobutton.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxinput.js?v=' . $version)
                ->addJs('/js/datepicker/bootstrap-datepicker.js?v=' . $version)
                ->addJs('/js/datatables/dataTables.bootstrap.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxmenu.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.filter.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.sort.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxtabs.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.selection.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxcalendar.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxdatetimeinput.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxcheckbox.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.grouping.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.pager.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxnumberinput.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxwindow.js?v=' . $version)
                ->addJs('/js/jqwidgets/globalization/globalize.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxcombobox.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxexpander.js?v=' . $version)
                ->addJs('/js/jqwidgets/globalization/globalize.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxvalidator.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxmaskedinput.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxchart.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.columnsresize.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxsplitter.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxtree.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxdata.export.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.export.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxgrid.edit.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxnotification.js?v=' . $version)
                ->addJs('/js/jqwidgets/jqxbuttongroup.js?v=' . $version)
                ->addJs('/js/bootbox.js?v=' . $version)
                ->addJs('/js/select/bootstrap-select.js?v=' . $version)
                ->addJs('/js/select/ajax-bootstrap-select.js?v=' . $version)
                ->addJs('/js/index/oasis.index.js?v=' . $version)
                ->addJs('/js/bootbox/bootbox.min.js?v=' . $version);
            //menu
            $this->menu($this->_user->nivel);
            $this->view->setVar('user', $this->_user);
            $this->definePermisosAction();
            #region Modificación de
            /**
             * Agregado para el control de las fotografías del usuario actual
             */
            $idUsuario = $this->_user->id;
            $usuario = Usuarios::findFirstById($idUsuario);
            $persona = Personas::findFirstById($usuario->persona_id);
            $ci_usuario = $persona->ci;
            $nombres = $persona->p_apellido . ($persona->s_apellido != '' ? ' ' . $persona->s_apellido : '') . ($persona->c_apellido != '' ? ' ' . $persona->c_apellido : '') . ($persona->p_nombre != '' ? ' ' . $persona->p_nombre : '') . ($persona->s_nombre != '' ? ' ' . $persona->s_nombre : '');
            $this->view->setVar('nombres', $nombres);
            $this->view->setVar('username', $usuario->username);
            $ruta = "";
            $rutaImagenesCredenciales = "/images/personal/";
            $extencionImagenesCredenciales = ".jpg";
            $num_complemento = "";
            if (isset($ci_usuario)) {
                $nombreImagenArchivo = $rutaImagenesCredenciales . trim($ci_usuario);
                $ruta = $nombreImagenArchivo . $extencionImagenesCredenciales ;
                if (!file_exists(getcwd() . $ruta)) $ruta = '/images/perfil-profesional.jpg';
                $this->view->setVar('ruta', $ruta. "?v=" . $version);
            }
            $this->view->setVar('version', $version);
        }
    }

    protected function usuario()
    {
        $auth = $this->session->get('cite');
        if ($auth) {
            $user_id = $auth['id'];
            $this->_user = Usuarios::findFirst("id = '$user_id'");
            return $this->_user;
        } else {
            return false;
        }
    }

    protected function forward($uri)
    {
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0],
                'action' => $uriParts[1]
            )
        );
    }

    //menu de acuerdo al nivel
    protected function menu($nivel)
    {
        $mMenu = new menus();
        $menus = $mMenu->listaNivel($nivel);
        $this->view->setVar('menus', $menus);
    }

    /**
     * Función para definir la matriz de permisos por rol de usuario.
     */
    public function definePermisosAction()
    {
        $auth = $this->session->get('auth');
        $user_id = $auth['id'];
        if ($user_id > 0) {
            $usuario = Usuarios::findFirstById($user_id);
            if (is_object($usuario)) {
                $result = Controlpermisos::Find(array("nivel_id=" . $usuario->nivel . " AND estado>=1 AND baja_logica=1"));
                $this->_registerPermissionSession($result);
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Función para el establecimiento de la variable de sesión para el control de permisos.
     * @param $controlador
     */
    private function _registerPermissionSession($permisos)
    {
        $arrPermisos = array();
        if (count($permisos) > 0) {
            foreach ($permisos as $perm) {
                $arrPermisos[] = array(
                    'id' => $perm->id,
                    'controlador' => $perm->controlador,
                    'nivel_id' => $perm->nivel_id,
                    'tipo' => $perm->tipo,
                    'identificador' => $perm->identificador,
                    'permisos' => $perm->permisos,
                    'descripcion' => $perm->descripcion,
                    'observacion' => $perm->observacion);
            }
        }
        $this->session->set('permisos', $arrPermisos);
    }

    /**
     * Función para obtener los permisos sobre el controlador e identificador.
     * @return mixed
     */
    public function obtenerPermisosPorControladorMasIdentificador($controlador, $identificador)
    {
        $resultado = '{"n":0,"v":0,"e":0,"b":0}';
        $permisos = $this->session->get('permisos');
        foreach ($permisos as $clave => $valor) {
            if ($valor["controlador"] == $controlador && $valor["identificador"] == $identificador) {
                $resultado = $valor["permisos"];
                break;
            }
        }
        return $resultado;
    }
    /**
     * Función para el almacenamiento de acciones sobre la base de datos del sistema.
     * @param string $tabla
     * @param int $idTabla
     * @param int $idAccion
     * @param string $operacion
     * @param string $observacion
     * @return bool
     */
    public function registrarBitacora($tabla = '', $idTabla = 0, $idAccion = 0, $operacion = '', $observacion = '')
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $hoy = date("Y-m-d H:i:s");
        $resultado = false;
        if ($tabla != '' && $idAccion > 0 && $operacion != '') {
            $objBitacora = new Bitacora();
            $objBitacora->tabla = $tabla;
            if ($idTabla > 0) {
                $objBitacora->tabla_reg_id = $idTabla;
            }
            $objBitacora->accion_id = $idAccion;
            $objBitacora->operacion = $operacion;
            $objBitacora->observacion = $observacion;
            $objBitacora->visible = 1;
            $objBitacora->baja_logica = 1;
            $objBitacora->agrupador = 0;
            $objBitacora->user_reg_id = $idUsuario;
            $objBitacora->fecha_reg = $hoy;
            $resultado = $objBitacora->save();
        }
        return $resultado;
    }

    /**
     * Función para sumar dias a una determinada fecha.
     * @param $fecha
     * @param $dia
     * @param string $sep
     * @return false|string
     */
    public function sumarDiasFecha($fecha,$dia,$sep="-")
    {   list($day,$mon,$year) = explode($sep,$fecha);
        return date('d'.$sep.'m'.$sep.'Y',mktime(0,0,0,$mon,$day+$dia,$year));
    }

    /**
     * Función para obtener el ip remoto.
     * @return mixed
     */
    public function getIp(){
        return $_SERVER['REMOTE_ADDR'];
    }
}
