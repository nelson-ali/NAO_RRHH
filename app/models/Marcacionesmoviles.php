<?php

/**
*   Oasis - Sistema de Gestión para Recursos Humanos
*   Empresa Estatal de Transporte por Cable "Mi Teleférico"
*   Versión:  1.0.0
*   Usuario Creador: Lic. Javier Loza
*   Fecha Creación:  13-06-2016
*/
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Marcacionesmoviles extends \Phalcon\Mvc\Model
{
    public function initialize() {
        $this->_db = new Marcaciones();
    }
    private $_db;
}