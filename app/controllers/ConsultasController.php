<?php
/**
 * Created by PhpStorm.
 * User: GGE-JLOZA
 * Date: 09/09/2015
 * Time: 03:11 PM
 */

class ConsultasController extends ControllerBase{
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
        $this->assets->addCss('/assets/css/bootstrap-switch.css');
        $this->assets->addJs('/js/switch/bootstrap-switch.js');
        $this->assets->addCss('/assets/css/oasis.principal.css');
        $this->assets->addCss('/assets/css/jquery-ui.css');
        $this->assets->addCss('/css/oasis.grillas.css');
        $this->assets->addJs('/js/numeric/jquery.numeric.js');
        $this->assets->addJs('/js/jquery.PrintArea.js');
        $this->assets->addCss('/assets/css/PrintArea.css');

        $this->assets->addCss('/assets/css/clockpicker.css');
        $this->assets->addJs('/js/clockpicker/clockpicker.js');

        $this->assets->addJs('/js/consultas/oasis.consultas.tab.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.index.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.list.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.approve.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.new.edit.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.turns.excepts.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.down.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.move.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.export.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.view.js');
        $this->assets->addJs('/js/consultas/oasis.consultas.view.splitter.js');
    }
} 