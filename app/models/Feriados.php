<?php
/*
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  23-02-2015
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Feriados extends \Phalcon\Mvc\Model {
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("");
    }
}