<?php

/**
 *   Oasis - Sistema de Gestión para Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Telefórico"
 *   Versión:  2.0.3
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  08-09-2016
 */
class BoletasmasivasController extends ControllerBase
{
    private $version = '0.0.0';

    public function initialize()
    {
        $auth = $this->session->get('auth');
        if (isset($auth['version'])) {
            $this->version = $auth['version'];
        } else $this->version = "0.0.0";
        parent::initialize();
    }

    public function indexAction()
    {
        $this->assets->addCss('/assets/css/bootstrap-switch.css?v=' . $this->version);
        $this->assets->addJs('/js/switch/bootstrap-switch.js?v=' . $this->version);
        $this->assets->addCss('/assets/css/oasis.principal.css?v=' . $this->version);
        $this->assets->addCss('/assets/css/jquery-ui.css?v=' . $this->version);
        $this->assets->addCss('/css/oasis.grillas.css?v=' . $this->version);
        $this->assets->addJs('/js/numeric/jquery.numeric.js?v=' . $this->version);
        $this->assets->addJs('/js/jquery.PrintArea.js?v=' . $this->version);
        $this->assets->addCss('/assets/css/PrintArea.css?v=' . $this->version);

        $this->assets->addCss('/assets/css/clockpicker.css?v=' . $this->version);
        $this->assets->addJs('/js/clockpicker/clockpicker.js?v=' . $this->version);
        $this->assets->addJs('/js/tagsimput/bootstrap-tagsinput.js?v=' . $this->version);
        $this->assets->addCss('/js/tagsimput/bootstrap-tagsinput.css?v=' . $this->version);

        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.tab.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.index.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.turns.excepts.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.view.js?v=' . $this->version);

        /*$this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.list.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.approve.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.calculations.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.new.edit.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.down.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.move.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.export.marc.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.export.calc.js?v=' . $this->version);
        $this->assets->addJs('/js/boletasmasivas/oasis.boletasmasivas.view.splitter.js?v=' . $this->version);*/

    }
}