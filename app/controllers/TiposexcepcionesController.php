<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  13-02-2015
*/

class TiposexcepcionesController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }
    /**
     * Función para la carga de la página de gestión de relaciones laborales.
     * Se cargan los combos necesarios.
     */
    public function indexAction()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $version = $auth['version'];
        } else $version = "0.0.0";

        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $version);
        $this->assets->addJs('/js/tiposexcepciones/oasis.tiposexcepciones.tab.j?v=' . $version);
        $this->assets->addJs('/js/tiposexcepciones/oasis.tiposexcepciones.index.j?v=' . $version);
        $this->assets->addJs('/js/tiposexcepciones/oasis.tiposexcepciones.new.edit.j?v=' . $version);
        $this->assets->addJs('/js/tiposexcepciones/oasis.tiposexcepciones.approve.j?v=' . $version);
        $this->assets->addJs('/js/tiposexcepciones/oasis.tiposexcepciones.export.j?v=' . $version);
        $this->assets->addJs('/js/tiposexcepciones/oasis.tiposexcepciones.down.j?v=' . $version);
    }


} 