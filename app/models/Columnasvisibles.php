<?php

/**
 *   RRHH - Sistema de Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  1.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  26-03-2017
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Columnasvisibles extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->_db = new Columnasvisibles();
    }

    private $_db;

}