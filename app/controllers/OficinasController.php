<?php

class OficinasController extends ControllerBase {

    public function initialize() {
        parent::initialize();
    }
    public function indexAction(){
        $oficinas=  oficinas::find();
        $this->view->setVar('oficinas', $oficinas);
    }
}
