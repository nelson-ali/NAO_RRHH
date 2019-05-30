<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  10-11-2015
*/


class MicuentaController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }
    /**
     * Función para la carga de la página de gestión de la cuenta personal en el sistema
     */
    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addJs('/js/pschecker/pschecker.js?v=' . $version);
        $this->assets->addCss('/assets/css/pschecker.css?v=' . $version);
        $this->assets->addJs('/js/micuenta/oasis.micuenta.index.js?v=' . $version);
    }
} 