<?php

/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  05-09-2016
 */
class IntercambioperfilesController extends ControllerBase
{
    private $version = "0.0.0";

    public function initialize()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $this->version = $auth['version'];
        }
        parent::initialize();
    }

    /**
     * Función para la carga de la página de gestión de excepciones.
     * Se cargan los combos necesarios.
     */
    public function indexAction()
    {
        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $this->version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $this->version);
        $this->assets->addCss('/js/select2/dist/css/select2.min.css?v=' . $this->version);
        $this->assets->addJs('/js/select2/dist/js/select2.min.js?v=' . $this->version);
        $this->assets->addJs('/js/jqwidgets/jqxdragdrop.js?v=' . $this->version);
        $this->assets->addJs('/js/jqwidgets/jqxgrid.aggregates.js?v=' . $this->version);

        $this->assets->addJs('/js/intercambioperfiles/oasis.intercambioperfiles.index.js?v=' . $this->version);
    }

    /**
     * Función para la obtención del listado de usuarios a los que puede asignarse permiso de acceso al calendario de manera individual y específica.
     */
    public function getusersforselectAction()
    {
        $this->view->disable();
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $q = '';
        $where = '';
        if (isset($_POST['q'])) {
            $q = $_POST['q'];
            $where = "WHERE (nombres ILIKE ''%" . $q . "%'') OR (ci ILIKE ''%" . $q . "%'') OR (cargo ILIKE ''%" . $q . "%'') OR (departamento_administrativo ILIKE ''%" . $q . "%'')  OR (gerencia_administrativa ILIKE ''%" . $q . "%'') ";
        }
        $relaborales = array();
        $users = new Frelaborales();
        $rs = $users->getSoloActivosResumido(0, 0, $where);
        if ($rs->count() > 0) {
            foreach ($rs as $rel) {
                $carpeta_image = "/images/personal/";
                $image_src = $carpeta_image . trim($rel->ci) . ".jpg";
                if (!file_exists(getcwd() . $image_src)) $image_src = '/images/perfil-profesional.jpg';
                $relaborales[] = array(
                    'id' => $rel->ci,
                    'text' => $rel->nombres,
                    'ci' => $rel->ci,
                    'image_src' => $image_src,
                    'expd' => $rel->expd,
                    'gerencia_administrativa' => $rel->gerencia_administrativa,
                    'departamento_administrativo' => $rel->departamento_administrativo,
                    'cargo' => $rel->cargo
                );
            }
        }
        echo json_encode($relaborales);
    }

    /**
     * Función para el almacenamiento y actualización de un registro de Excepción.
     * return array(EstadoResultado,Mensaje)
     * Los valores posibles para la variable EstadoResultado son:
     *  0: Error
     *   1: Procesado
     *  -1: Error Crítico
     *  -2: Error de Conexión
     *  -3: Usuario no Autorizado
     */
    public function applyAction()
    {
        $auth = $this->session->get('auth');
        $idUsuario = $auth['id'];
        $msj = Array();
        $resultado = 0;
        $hoy = date("Y-m-d H:i:s");
        $this->view->disable();
        if (isset($_POST["tipo_intercambio"]) && $_POST["tipo_intercambio"] >= 0 && isset($_POST["id_perfil_origen"]) && $_POST["id_perfil_origen"] > 0 && isset($_POST["id_perfil_destino"]) && $_POST["id_perfil_destino"] > 0 && isset($_POST["fecha_int"]) && $_POST["fecha_int"] != '' && isset($_POST["observacion"]) && $_POST["observacion"] != '') {
            $tipoIntercambio = $_POST["tipo_intercambio"];
            $idPerfilLaboralOrigen = $_POST["id_perfil_origen"];
            $idPerfilLaboralDestino = $_POST["id_perfil_destino"];
            $idOrganigrama = 0;
            if (isset($_POST["id_organigrama"]) && $_POST["id_organigrama"] > 0) {
                $idOrganigrama = $_POST["id_organigrama"];
            }
            $genero = 0;
            if (isset($_POST["genero"]) && $_POST["genero"] > 0) {
                $genero = $_POST["genero"];
            }
            $fecha = $_POST["fecha_int"];
            $observacion = $_POST["observacion"];
            $retorno = isset($_POST["retorno"]) && $_POST["retorno"] > 0 ? $_POST["retorno"] : 0;
            $todaMarcacion = isset($_POST["toda_marcacion"]) && $_POST["toda_marcacion"] > 0 ? $_POST["toda_marcacion"] : 0;
            $rp = new Frelaboralesperfiles();
            switch ($tipoIntercambio) {
                case 1:
                    if (isset($_POST["ci_personas"]) && $_POST["ci_personas"] != '') {
                        $listCI = str_replace(",", "'',''", $_POST["ci_personas"]);
                        $listCI = "''" . $listCI . "''";
                        $resultado = $rp->intercambiarPerfilesPorCi($idPerfilLaboralOrigen, $idPerfilLaboralDestino, $listCI, $fecha, $observacion, $idUsuario, $todaMarcacion);
                        if ($resultado > 0 && $retorno > 0) {
                            $resultado = $rp->intercambiarPerfilesPorCi($idPerfilLaboralDestino, $idPerfilLaboralOrigen, $listCI, $fecha, $observacion, $idUsuario, $todaMarcacion);
                        }
                    }
                    break;
                default:
                    $resultado = $rp->intercambiarPerfilesRegular($idPerfilLaboralOrigen, $idPerfilLaboralDestino, $idOrganigrama, $genero, $fecha, $observacion, $idUsuario, $todaMarcacion);
                    if ($resultado > 0 &&  $retorno > 0) {
                        $resultado = $rp->intercambiarPerfilesRegular($idPerfilLaboralDestino, $idPerfilLaboralOrigen, $idOrganigrama, $genero, $fecha, $observacion, $idUsuario, $todaMarcacion);
                    }
            }
            if ($resultado > 0) {
                $msj = array('result' => 1, 'msj' => '&Eacute;xito: Se ejecut&oacute; de forma satisfactoria el intercambio de perfiles.');
            } else {
                $msj = array('result' => 0, 'msj' => 'Error: No se pudo realizar el intercambio solicitado.');
            }
        }

        echo json_encode($msj);
    }
}