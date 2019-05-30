<?php

/**
 *   OASIS - Sistema de Recursos Humanos
 *   Empresa Estatal de Transporte por Cable "Mi Teleférico"
 *   Versión:  2.0.0
 *   Usuario Creador: Lic. Javier Loza
 *   Fecha Creación:  19-01-2018
 */
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Bitacora extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->_db = new Bitacora();
    }

    private $_db;


}